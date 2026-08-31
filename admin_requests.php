<?php
// The admin decides on each application for help.
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $request_id = $_POST['request_id'] ?? 0;
    $decision   = $_POST['decision'] ?? '';
    $note       = trim($_POST['note'] ?? '');

    if ($decision === 'approved' || $decision === 'rejected') {

        $update = $pdo->prepare('UPDATE requests SET status = ?, admin_note = ? WHERE id = ?');
        $update->execute([$decision, $note, $request_id]);

        // tell the person what happened
        $find = $pdo->prepare('SELECT beneficiaries.user_id, programs.title
                               FROM requests
                               JOIN beneficiaries ON beneficiaries.id = requests.beneficiary_id
                               JOIN programs      ON programs.id = requests.program_id
                               WHERE requests.id = ?');
        $find->execute([$request_id]);
        $row = $find->fetch();

        if ($row) {
            $body = $decision === 'approved'
                ? 'Your application for ' . $row['title'] . ' was accepted.'
                : 'Your application for ' . $row['title'] . ' was not accepted.';

            $insert = $pdo->prepare('INSERT INTO notifications (user_id, body) VALUES (?, ?)');
            $insert->execute([$row['user_id'], $body]);
        }
    }

    header('Location: admin_requests.php');
    exit;
}

$requests = $pdo->query('SELECT requests.*,
                                programs.title,
                                users.name,
                                users.email,
                                beneficiaries.city,
                                beneficiaries.household_size,
                                beneficiaries.monthly_income,
                                beneficiaries.status AS profile_status
                         FROM requests
                         JOIN beneficiaries ON beneficiaries.id = requests.beneficiary_id
                         JOIN users         ON users.id = beneficiaries.user_id
                         JOIN programs      ON programs.id = requests.program_id
                         ORDER BY FIELD(requests.status, "pending", "approved", "rejected"), requests.id DESC')->fetchAll();

$page_title = 'Applications';
include 'header.php';
?>

<div class="page-head">
    <div>
        <h1>Applications for help</h1>
        <p class="subtitle">Each application, with the details you need to decide.</p>
    </div>
</div>

<?php if (count($requests) === 0) { ?>

    <p class="empty">Nobody has applied for help yet.</p>

<?php } else { ?>

    <div class="list">

    <?php foreach ($requests as $request) { ?>
        <div class="card">
            <h2>
                <?php echo htmlspecialchars($request['title']); ?>
                <span class="tag <?php echo $request['status']; ?>"><?php echo $request['status']; ?></span>
            </h2>

            <p class="meta">
                <?php echo htmlspecialchars($request['name']); ?>
                &middot; <?php echo htmlspecialchars($request['email']); ?>
                <?php if ($request['city'] !== null && $request['city'] !== '') { ?>
                    &middot; <?php echo htmlspecialchars($request['city']); ?>
                <?php } ?>
    </div>

                &middot; applied <?php echo $request['created_at']; ?>
            </p>

            <p class="meta">
                Home:
                <?php echo $request['household_size'] === null ? 'not given' : $request['household_size'] . ' people'; ?>
                &middot; income
                <?php echo $request['monthly_income'] === null
                    ? 'not given'
                    : number_format($request['monthly_income'], 2) . ' JOD'; ?>
            </p>

            <?php if ($request['note'] !== null && $request['note'] !== '') { ?>
                <p><?php echo nl2br(htmlspecialchars($request['note'])); ?></p>
            <?php } ?>

            <form method="post">
                <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">

                <label for="note<?php echo $request['id']; ?>">Reply for this person (optional)</label>
                <input type="text" id="note<?php echo $request['id']; ?>" name="note"
                       value="<?php echo htmlspecialchars($request['admin_note'] ?? ''); ?>">

                <button type="submit" name="decision" value="approved">Accept</button>
                <button type="submit" name="decision" value="rejected" class="red">Refuse</button>
            </form>
        </div>
    <?php } ?>

<?php } ?>

<?php include 'footer.php'; ?>
