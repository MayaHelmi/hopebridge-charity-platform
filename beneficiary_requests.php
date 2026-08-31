<?php
// The applications this beneficiary has sent, and what happened to them.
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'beneficiary') {
    header('Location: login.php');
    exit;
}

$query = $pdo->prepare('SELECT * FROM beneficiaries WHERE user_id = ?');
$query->execute([$_SESSION['user_id']]);
$me = $query->fetch();

$query = $pdo->prepare('SELECT requests.*, programs.title
                        FROM requests
                        JOIN programs ON programs.id = requests.program_id
                        WHERE requests.beneficiary_id = ?
                        ORDER BY requests.id DESC');
$query->execute([$me['id']]);
$requests = $query->fetchAll();

$page_title = 'My requests';
include 'header.php';
?>

<div class="page-head">
    <div>
        <h1>My requests</h1>
        <p class="subtitle">Every application you have sent and where it has got to.</p>
    </div>
</div>

<?php if (count($requests) === 0) { ?>

    <p class="empty">
        You have not applied for anything yet.
        <a href="beneficiary_services.php">See what help is available</a>.
    </p>

<?php } else { ?>

    <div class="list">

    <?php foreach ($requests as $request) { ?>
        <div class="card">
            <h3>
                <?php echo htmlspecialchars($request['title']); ?>
                <span class="tag <?php echo $request['status']; ?>"><?php echo $request['status']; ?></span>
            </h3>

            <p class="raised">Applied on <?php echo $request['created_at']; ?></p>

            <?php if ($request['note'] !== null && $request['note'] !== '') { ?>
                <p>What you wrote: <?php echo nl2br(htmlspecialchars($request['note'])); ?></p>
            <?php } ?>
    </div>


            <?php if ($request['admin_note'] !== null && $request['admin_note'] !== '') { ?>
                <p class="notice gap-t <?php echo $request['status'] === 'rejected' ? 'error' : 'ok'; ?>">
                    Reply from the charity: <?php echo htmlspecialchars($request['admin_note']); ?>
                </p>
            <?php } ?>
        </div>
    <?php } ?>

<?php } ?>

<?php include 'footer.php'; ?>
