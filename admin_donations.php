<?php
// Every donation the charity has received, newest first.
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$donations = $pdo->query('SELECT donations.*, programs.title, users.name, users.email
                          FROM donations
                          JOIN programs ON programs.id = donations.program_id
                          JOIN users    ON users.id = donations.donor_id
                          ORDER BY donations.id DESC')->fetchAll();

$page_title = 'Donations';
include 'header.php';
?>

<div class="page-head">
    <div>
        <h1>Donations</h1>
        <p class="subtitle">Everything that has been given to the charity.</p>
    </div>
</div>

<?php if (count($donations) === 0) { ?>

    <p class="empty">There are no donations yet.</p>

<?php } else { ?>

    <table>
        <tr>
            <th>Number</th><th>Donor</th><th>Program</th><th>Amount</th><th>Date</th>
        </tr>
        <?php foreach ($donations as $donation) { ?>
            <tr>
                <td>#<?php echo $donation['id']; ?></td>
                <td>
                    <?php echo htmlspecialchars($donation['name']); ?><br>
                    <span class="raised"><?php echo htmlspecialchars($donation['email']); ?></span>
                </td>
                <td><?php echo htmlspecialchars($donation['title']); ?></td>
                <td><?php echo number_format($donation['amount'], 2); ?> JOD</td>
                <td><?php echo $donation['created_at']; ?></td>
            </tr>
        <?php } ?>
    </table>

<?php } ?>

<?php include 'footer.php'; ?>
