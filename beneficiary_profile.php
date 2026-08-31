<?php
// The beneficiary's own details. The admin reads these to check they are eligible.
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'beneficiary') {
    header('Location: login.php');
    exit;
}

$saved = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $household = $_POST['household_size'] ?? '';
    $income    = $_POST['monthly_income'] ?? '';

    if ($household !== '' && (!ctype_digit($household) || $household < 1)) {
        $error = 'The number of people has to be a whole number, 1 or more.';

    } elseif ($income !== '' && (!is_numeric($income) || $income < 0)) {
        $error = 'The monthly income has to be a number, 0 or more.';

    } else {
        $update = $pdo->prepare('UPDATE beneficiaries
                                 SET phone = ?, city = ?, household_size = ?, monthly_income = ?, situation = ?
                                 WHERE user_id = ?');
        $update->execute([
            trim($_POST['phone'] ?? ''),
            trim($_POST['city'] ?? ''),
            $household !== '' ? $household : null,
            $income !== '' ? $income : null,
            trim($_POST['situation'] ?? ''),
            $_SESSION['user_id'],
        ]);
        $saved = true;
    }
}

$query = $pdo->prepare('SELECT * FROM beneficiaries WHERE user_id = ?');
$query->execute([$_SESSION['user_id']]);
$me = $query->fetch();

// the automatic messages the system has written for this user
$query = $pdo->prepare('SELECT * FROM notifications WHERE user_id = ? ORDER BY id DESC LIMIT 10');
$query->execute([$_SESSION['user_id']]);
$notifications = $query->fetchAll();

$page_title = 'My profile';
include 'header.php';
?>

<div class="page-head">
    <div>
        <h1>My profile</h1>
        <p class="subtitle">The admin reads these details to check which programs you can use.</p>
    </div>
</div>

<?php if ($saved) { ?>
    <p class="notice ok">Your details have been saved.</p>
<?php } ?>

<?php if ($error !== '') { ?>
    <p class="notice error"><?php echo htmlspecialchars($error); ?></p>
<?php } ?>

<?php if ($me['status'] === 'pending') { ?>
    <p class="notice info">
        Your account is waiting to be checked. Please fill in the details below.
        Once the admin approves you, you will be able to apply for help.
    </p>
<?php } elseif ($me['status'] === 'approved') { ?>
    <p class="notice ok">
        Your account is approved. You can <a href="beneficiary_services.php">see what help is available</a>.
    </p>
<?php } else { ?>
    <p class="notice error">
        Your account was not approved.
        <?php if ($me['admin_note'] !== null && $me['admin_note'] !== '') { ?>
            Reason: <?php echo htmlspecialchars($me['admin_note']); ?>
        <?php } ?>
        You can change your details below and <a href="messages.php">message the admin</a>.
    </p>
<?php } ?>

<?php if (count($notifications) > 0) { ?>
    <h2>Recent updates for you</h2>
    <?php foreach ($notifications as $note) { ?>
        <div class="note-row">
            <?php echo htmlspecialchars($note['body']); ?>
            <span><?php echo $note['created_at']; ?></span>
        </div>
    <?php } ?>
<?php } ?>

<h2>My details</h2>

<div class="form-box">
    <form method="post">
        <label for="phone">Phone number</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($me['phone'] ?? ''); ?>">

        <label for="city">City</label>
        <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($me['city'] ?? ''); ?>">

        <label for="household_size">How many people live in your home</label>
        <input type="text" id="household_size" name="household_size" value="<?php echo htmlspecialchars($me['household_size'] ?? ''); ?>">

        <label for="monthly_income">Monthly income in JOD</label>
        <input type="text" id="monthly_income" name="monthly_income" value="<?php echo htmlspecialchars($me['monthly_income'] ?? ''); ?>">

        <label for="situation">Tell us about your situation</label>
        <textarea id="situation" name="situation"><?php echo htmlspecialchars($me['situation'] ?? ''); ?></textarea>

        <button type="submit">Save my details</button>
    </form>
</div>

<?php include 'footer.php'; ?>
