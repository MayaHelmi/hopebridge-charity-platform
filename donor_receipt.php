<?php
// A printable receipt for one donation.
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donor') {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? 0;

// "AND donations.donor_id = ?" stops a donor opening somebody else's receipt
$query = $pdo->prepare('SELECT donations.*, programs.title, users.name AS donor_name, users.email
                        FROM donations
                        JOIN programs ON programs.id = donations.program_id
                        JOIN users    ON users.id = donations.donor_id
                        WHERE donations.id = ? AND donations.donor_id = ?');
$query->execute([$id, $_SESSION['user_id']]);
$donation = $query->fetch();

if (!$donation) {
    $page_title = 'Receipt not found';
    include 'header.php';
    echo '<p class="empty">This receipt was not found. <a href="donor_donations.php">Back to my donations</a>.</p>';
    include 'footer.php';
    exit;
}

$page_title = 'Receipt';
include 'header.php';
?>

<p class="back"><a href="donor_donations.php">&larr; My donations</a></p>

<div class="page-head">
    <div>
        <h1>Donation receipt</h1>
        <p class="subtitle">Receipt #<?php echo $donation['id']; ?></p>
    </div>
</div>

<div class="receipt">
    <h2>HopeBridge</h2>

    <table>
        <tr><th>Receipt number</th><td>#<?php echo $donation['id']; ?></td></tr>
        <tr><th>Date</th><td><?php echo $donation['created_at']; ?></td></tr>
        <tr><th>Donor</th><td><?php echo htmlspecialchars($donation['donor_name']); ?></td></tr>
        <tr><th>Email</th><td><?php echo htmlspecialchars($donation['email']); ?></td></tr>
        <tr><th>Program</th><td><?php echo htmlspecialchars($donation['title']); ?></td></tr>
        <tr><th>Amount</th><td><strong><?php echo number_format($donation['amount'], 2); ?> JOD</strong></td></tr>
    </table>

    <p class="meta gap-t">
        Thank you for supporting HopeBridge.
    </p>
</div>

<button onclick="window.print()">Print this receipt</button>

<?php include 'footer.php'; ?>
