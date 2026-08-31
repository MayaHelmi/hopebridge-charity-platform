<?php
// Progress reports for the programs this donor has given to.
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donor') {
    header('Location: login.php');
    exit;
}

// Only updates about programs this donor actually supported.
// The two tables both have a "title" column, so the program one is
// renamed with AS, otherwise it would cover up the update's own title.
// DISTINCT is used because a donor may have given to the same program twice.
$query = $pdo->prepare('SELECT DISTINCT updates.id,
                                        updates.title,
                                        updates.body,
                                        updates.created_at,
                                        programs.title AS program_title
                        FROM updates
                        JOIN programs  ON programs.id = updates.program_id
                        JOIN donations ON donations.program_id = programs.id
                        WHERE donations.donor_id = ?
                        ORDER BY updates.id DESC');
$query->execute([$_SESSION['user_id']]);
$updates = $query->fetchAll();

$page_title = 'Updates';
include 'header.php';
?>

<div class="page-head">
    <div>
        <h1>Updates</h1>
        <p class="subtitle">What has happened in the programs you gave to.</p>
    </div>
</div>

<?php if (count($updates) === 0) { ?>

    <p class="empty">
        There are no updates for you yet. They appear here once you have donated
        to a program and the charity has written a report about it.
    </p>

<?php } else { ?>

    <div class="list">

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
