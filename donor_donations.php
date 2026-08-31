<?php
// Everything this donor has given.
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donor') {
    header('Location: login.php');
    exit;
}

$query = $pdo->prepare('SELECT donations.*, programs.title
                        FROM donations
                        JOIN programs ON programs.id = donations.program_id
                        WHERE donations.donor_id = ?
                        ORDER BY donations.id DESC');
$query->execute([$_SESSION['user_id']]);
$donations = $query->fetchAll();

// the total this donor has given
$total = 0;
foreach ($donations as $donation) {
    $total = $total + $donation['amount'];
}

$page_title = 'My donations';
include 'header.php';
?>

<div class="page-head">
    <div>
        <h1>My donations</h1>
        <p class="subtitle">You have given <?php echo number_format($total, 2); ?> JOD in total. Thank you.</p>
    </div>
</div>

<?php if (count($donations) === 0) { ?>

    <p class="empty">You have not donated yet. <a href="programs.php">See the programs</a>.</p>

<?php } else { ?>

    <table>
        <tr>
            <th>Number</th>
            <th>Program</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Receipt</th>
        </tr>
        <?php foreach ($donations as $donation) { ?>
            <tr>
                <td>#<?php echo $donation['id']; ?></td>
                <td><?php echo htmlspecialchars($donation['title']); ?></td>
                <td><?php echo number_format($donation['amount'], 2); ?> JOD</td>
                <td><?php echo $donation['created_at']; ?></td>
                <td><a href="donor_receipt.php?id=<?php echo $donation['id']; ?>">View</a></td>
            </tr>
        <?php } ?>
    </table>

<?php } ?>

<?php include 'footer.php'; ?>
