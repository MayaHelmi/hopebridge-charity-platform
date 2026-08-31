<?php
// The donor chooses an amount and the donation is saved.
require 'config.php';

// only a logged-in donor may open this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donor') {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? 0;

$query = $pdo->prepare('SELECT * FROM programs WHERE id = ? AND active = 1');
$query->execute([$id]);
$program = $query->fetch();

if (!$program) {
    $page_title = 'Program not found';
    include 'header.php';
    echo '<p class="empty">This program was not found. <a href="programs.php">See all programs</a>.</p>';
    include 'footer.php';
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $amount = $_POST['amount'] ?? '';

    if (!is_numeric($amount) || $amount <= 0) {
        $error = 'Please enter an amount bigger than zero.';

    } elseif ($amount > 100000) {
        $error = 'Please enter an amount under 100,000 JOD.';

    } else {
        $insert = $pdo->prepare('INSERT INTO donations (donor_id, program_id, amount) VALUES (?, ?, ?)');
        $insert->execute([$_SESSION['user_id'], $program['id'], $amount]);

        header('Location: donor_receipt.php?id=' . $pdo->lastInsertId());
        exit;
    }
}

$page_title = 'Donate';
include 'header.php';
?>

<p class="back">
    <a href="program.php?id=<?php echo $program['id']; ?>">
        &larr; Back to <?php echo htmlspecialchars($program['title']); ?>
    </a>
</p>

<div class="page-head">
    <div>
        <h1>Donate</h1>
        <p class="subtitle">You are giving to <strong><?php echo htmlspecialchars($program['title']); ?></strong>.</p>
    </div>
</div>

<div class="form-box">

    <?php if ($error !== '') { ?>
        <p class="notice error"><?php echo htmlspecialchars($error); ?></p>
    <?php } ?>

    <form method="post">
        <label for="amount">Amount in JOD</label>
        <input type="text" id="amount" name="amount" value="<?php echo htmlspecialchars($_POST['amount'] ?? $_GET['amount'] ?? ''); ?>">

        <div class="choices">
            <a href="donate.php?id=<?php echo $program['id']; ?>&amount=10">10 JOD</a>
            <a href="donate.php?id=<?php echo $program['id']; ?>&amount=25">25 JOD</a>
            <a href="donate.php?id=<?php echo $program['id']; ?>&amount=50">50 JOD</a>
            <a href="donate.php?id=<?php echo $program['id']; ?>&amount=100">100 JOD</a>
        </div>

        <button type="submit">Donate</button>
    </form>
</div>

<?php include 'footer.php'; ?>
