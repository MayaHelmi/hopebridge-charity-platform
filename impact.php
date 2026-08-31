<?php
// What the charity has actually done so far. Every number on this page is
// counted from the database, and every report was written by an administrator.
require 'config.php';

$helped  = $pdo->query("SELECT COUNT(*) AS total FROM requests WHERE status = 'approved'")->fetch();
$given   = $pdo->query('SELECT COALESCE(SUM(amount), 0) AS total, COUNT(*) AS times FROM donations')->fetch();
$running = $pdo->query('SELECT COUNT(*) AS total FROM programs WHERE active = 1')->fetch();
$donors  = $pdo->query("SELECT COUNT(*) AS total FROM users WHERE role = 'donor'")->fetch();

// how each program is doing
$programs = $pdo->query('SELECT programs.title,
                                programs.category,
                                programs.goal_amount,
                                COALESCE(SUM(donations.amount), 0) AS raised,
                                (SELECT COUNT(*) FROM requests
                                 WHERE requests.program_id = programs.id
                                   AND requests.status = "approved") AS helped
                         FROM programs
                         LEFT JOIN donations ON donations.program_id = programs.id
                         WHERE programs.active = 1
                         GROUP BY programs.id
                         ORDER BY raised DESC')->fetchAll();

// the reports the administrators have published
$updates = $pdo->query('SELECT updates.*, programs.title AS program_title
                        FROM updates
                        JOIN programs ON programs.id = updates.program_id
                        ORDER BY updates.id DESC')->fetchAll();

$page_title = 'Impact';
include 'header.php';
?>

<div class="page-head">
    <div>
        <h1>Our impact</h1>
        <p class="subtitle">Every number here is counted from our own records, as it stands today.</p>
    </div>
</div>

<div class="stats">
    <div>
        <span class="number"><?php echo number_format($helped['total']); ?></span>
        <span class="label">Applications Approved</span>
    </div>
    <div>
        <span class="number"><?php echo number_format($given['total'], 0); ?></span>
        <span class="label">JOD Donated</span>
    </div>
    <div>
        <span class="number"><?php echo number_format($given['times']); ?></span>
        <span class="label">Donations Made</span>
    </div>
    <div>
        <span class="number"><?php echo number_format($donors['total']); ?></span>
        <span class="label">Registered Donors</span>
    </div>
</div>

<h2>How each program is doing</h2>

<?php if (count($programs) === 0) { ?>

    <p class="empty">There are no programs running at the moment.</p>

<?php } else { ?>

    <table>
        <tr>
            <th>Program</th>
            <th>Category</th>
            <th>Raised</th>
            <th>Goal</th>
            <th>Families helped</th>
        </tr>
        <?php foreach ($programs as $program) { ?>
            <tr>
                <td><?php echo htmlspecialchars($program['title']); ?></td>
                <td><?php echo htmlspecialchars($program['category']); ?></td>
                <td><?php echo number_format($program['raised'], 2); ?> JOD</td>
                <td><?php echo number_format($program['goal_amount'], 2); ?> JOD</td>
                <td><?php echo $program['helped']; ?></td>
            </tr>
        <?php } ?>
    </table>

<?php } ?>

<h2>Reports from the field</h2>

<?php if (count($updates) === 0) { ?>

    <p class="empty">No reports have been published yet.</p>

<?php } else { ?>

    <div class="grid two">
        <?php foreach ($updates as $update) { ?>
            <div class="card">
                <h3><?php echo htmlspecialchars($update['title']); ?></h3>
                <p class="raised">
                    <?php echo htmlspecialchars($update['program_title']); ?>
                    &middot; <?php echo $update['created_at']; ?>
                </p>
                <p><?php echo nl2br(htmlspecialchars($update['body'])); ?></p>
            </div>
        <?php } ?>
    </div>

<?php } ?>

<?php include 'footer.php'; ?>
