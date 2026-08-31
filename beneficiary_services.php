<?php
// What help is available, who each program is for, and how to apply.
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'beneficiary') {
    header('Location: login.php');
    exit;
}

$query = $pdo->prepare('SELECT * FROM beneficiaries WHERE user_id = ?');
$query->execute([$_SESSION['user_id']]);
$me = $query->fetch();

$error = '';
$saved = false;

// applying is only possible once the admin has approved the profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $me['status'] === 'approved') {

    $program_id = $_POST['program_id'] ?? 0;
    $note       = trim($_POST['note'] ?? '');

    // is this a real, active program?
    $check = $pdo->prepare('SELECT id FROM programs WHERE id = ? AND active = 1');
    $check->execute([$program_id]);

    if (!$check->fetch()) {
        $error = 'That program was not found.';

    } else {
        // one open application per program at a time
        $already = $pdo->prepare("SELECT id FROM requests
                                  WHERE beneficiary_id = ? AND program_id = ? AND status = 'pending'");
        $already->execute([$me['id'], $program_id]);

        if ($already->fetch()) {
            $error = 'You have already applied for this program and it is still being looked at.';

        } else {
            $insert = $pdo->prepare('INSERT INTO requests (beneficiary_id, program_id, note) VALUES (?, ?, ?)');
            $insert->execute([$me['id'], $program_id, $note]);
            $saved = true;
        }
    }
}

$programs = $pdo->query('SELECT * FROM programs WHERE active = 1 ORDER BY id')->fetchAll();

$page_title = 'Help available';
include 'header.php';
?>

<div class="page-head">
    <div>
        <h1>Help available</h1>
        <p class="subtitle">Read who each program is for, then apply for the ones that match your situation.</p>
    </div>
</div>

<?php if ($me['status'] !== 'approved') { ?>

    <p class="notice info">
        You can apply once the admin has approved your account.
        Check <a href="beneficiary_profile.php">your profile</a>.
    </p>

<?php } ?>

<?php if ($error !== '') { ?>
    <p class="notice error"><?php echo htmlspecialchars($error); ?></p>
<?php } ?>

<?php if ($saved) { ?>
    <p class="notice ok">
        Your application has been sent. You can follow it on
        <a href="beneficiary_requests.php">my requests</a>.
    </p>
<?php } ?>

<div class="grid two">
    <?php foreach ($programs as $program) { ?>
        <div class="card">
            <h2><?php echo htmlspecialchars($program['title']); ?></h2>
            <p><?php echo htmlspecialchars($program['description']); ?></p>

            <h3 class="mini">Who it is for</h3>
            <p class="meta"><?php echo htmlspecialchars($program['eligibility']); ?></p>

            <?php if ($me['status'] === 'approved') { ?>
                <form method="post">
                    <input type="hidden" name="program_id" value="<?php echo $program['id']; ?>">

                    <label for="note<?php echo $program['id']; ?>">Why you need this help (optional)</label>
                    <textarea id="note<?php echo $program['id']; ?>" name="note"></textarea>

                    <button type="submit">Apply</button>
                </form>
            <?php } ?>
        </div>
    <?php } ?>
</div>

<?php include 'footer.php'; ?>
