<?php
// The admin's reports page.
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// the four numbers at the top
$money    = $pdo->query('SELECT COALESCE(SUM(amount), 0) AS total, COUNT(*) AS donations FROM donations')->fetch();
$donors   = $pdo->query("SELECT COUNT(*) AS total FROM users WHERE role = 'donor'")->fetch();
$waiting  = $pdo->query("SELECT COUNT(*) AS total FROM beneficiaries WHERE status = 'pending'")->fetch();
$requests = $pdo->query("SELECT COUNT(*) AS total FROM requests WHERE status = 'pending'")->fetch();

// how much came in each month
$by_month = $pdo->query("SELECT DATE_FORMAT(created_at, '%Y-%m') AS month,
                                COUNT(*) AS donations,
                                SUM(amount) AS total
                         FROM donations
                         GROUP BY month
                         ORDER BY month DESC")->fetchAll();

// who gives, how often, and how much - the donor behaviour report
$top_donors = $pdo->query('SELECT users.name,
                                  users.email,
                                  COUNT(donations.id) AS times,
                                  SUM(donations.amount) AS total,
                                  ROUND(AVG(donations.amount), 2) AS average,
                                  MAX(donations.created_at) AS last_gift
                           FROM donations
                           JOIN users ON users.id = donations.donor_id
                           GROUP BY users.id
                           ORDER BY total DESC
                           LIMIT 5')->fetchAll();

// which programs donors prefer, and how much of each goal is covered
$by_program = $pdo->query('SELECT programs.title,
                                  programs.goal_amount,
                                  COUNT(donations.id) AS donations,
                                  COALESCE(SUM(donations.amount), 0) AS raised,
                                  (SELECT COUNT(*) FROM requests
                                   WHERE requests.program_id = programs.id
                                     AND requests.status = "approved") AS helped
                           FROM programs
                           LEFT JOIN donations ON donations.program_id = programs.id
                           GROUP BY programs.id
                           ORDER BY raised DESC')->fetchAll();

$page_title = 'Dashboard';
include 'header.php';
?>

<div class="page-head">
    <div>
        <h1>Dashboard</h1>
        <p class="subtitle">An overview of the whole charity.</p>
    </div>
</div>

<div class="stats">
    <div>
        <span class="number"><?php echo number_format($money['total'], 2); ?></span>
        <span class="label">JOD Raised</span>
    </div>
    <div>
        <span class="number"><?php echo $money['donations']; ?></span>
        <span class="label">Donations</span>
    </div>
    <div>
        <span class="number"><?php echo $donors['total']; ?></span>
        <span class="label">Donors</span>
    </div>
    <div>
        <span class="number"><?php echo $waiting['total'] + $requests['total']; ?></span>
        <span class="label">Waiting for You</span>
    </div>
</div>

<?php if ($waiting['total'] > 0) { ?>
    <p class="notice info">
        <?php echo $waiting['total']; ?> beneficiary profile(s) are waiting to be checked.
        <a href="admin_beneficiaries.php">Review them</a>.
    </p>
<?php } ?>

<?php if ($requests['total'] > 0) { ?>
    <p class="notice info">
        <?php echo $requests['total']; ?> application(s) for help are waiting for a decision.
        <a href="admin_requests.php">Review them</a>.
    </p>
<?php } ?>

<h2>Money by month</h2>

<?php if (count($by_month) === 0) { ?>
    <p class="empty">There are no donations yet.</p>
<?php } else { ?>
    <table>
        <tr><th>Month</th><th>Donations</th><th>Total</th></tr>
        <?php foreach ($by_month as $row) { ?>
            <tr>
                <td><?php echo $row['month']; ?></td>
                <td><?php echo $row['donations']; ?></td>
                <td><?php echo number_format($row['total'], 2); ?> JOD</td>
            </tr>
        <?php } ?>
    </table>
<?php } ?>

<h2>Who gives the most</h2>

<?php if (count($top_donors) === 0) { ?>
    <p class="empty">Nobody has donated yet.</p>
<?php } else { ?>
    <table>
        <tr>
            <th>Donor</th><th>Times</th><th>Total</th><th>Average gift</th><th>Last gift</th>
        </tr>
        <?php foreach ($top_donors as $row) { ?>
            <tr>
                <td>
                    <?php echo htmlspecialchars($row['name']); ?><br>
                    <span class="meta"><?php echo htmlspecialchars($row['email']); ?></span>
                </td>
                <td><?php echo $row['times']; ?></td>
                <td><?php echo number_format($row['total'], 2); ?> JOD</td>
                <td><?php echo number_format($row['average'], 2); ?> JOD</td>
                <td><?php echo $row['last_gift']; ?></td>
            </tr>
        <?php } ?>
    </table>
<?php } ?>

<h2>How each program is doing</h2>

<table>
    <tr>
        <th>Program</th><th>Donations</th><th>Raised</th><th>Goal</th><th>People helped</th>
    </tr>
    <?php foreach ($by_program as $row) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo $row['donations']; ?></td>
            <td><?php echo number_format($row['raised'], 2); ?> JOD</td>
            <td><?php echo number_format($row['goal_amount'], 2); ?> JOD</td>
            <td><?php echo $row['helped']; ?></td>
        </tr>
    <?php } ?>
</table>

<?php include 'footer.php'; ?>
