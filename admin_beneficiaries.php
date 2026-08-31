<?php
// The admin checks the people asking for help and approves or rejects them.
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $beneficiary_id = $_POST['beneficiary_id'] ?? 0;
    $decision       = $_POST['decision'] ?? '';
    $note           = trim($_POST['note'] ?? '');

    if ($decision === 'approved' || $decision === 'rejected') {

        $update = $pdo->prepare('UPDATE beneficiaries SET status = ?, admin_note = ? WHERE id = ?');
        $update->execute([$decision, $note, $beneficiary_id]);

        // tell the person what happened
        $find = $pdo->prepare('SELECT user_id FROM beneficiaries WHERE id = ?');
        $find->execute([$beneficiary_id]);
        $row = $find->fetch();

        if ($row) {
            $body = $decision === 'approved'
                ? 'Your account has been approved. You can now apply for help.'
                : 'Your account was not approved. Please read the reason on your profile.';

            $insert = $pdo->prepare('INSERT INTO notifications (user_id, body) VALUES (?, ?)');
            $insert->execute([$row['user_id'], $body]);
        }
    }

    // reload the page so refreshing does not repeat the decision
    header('Location: admin_beneficiaries.php');
    exit;
}

$people = $pdo->query('SELECT beneficiaries.*, users.name, users.email
                       FROM beneficiaries
                       JOIN users ON users.id = beneficiaries.user_id
                       ORDER BY FIELD(beneficiaries.status, "pending", "approved", "rejected"), beneficiaries.id DESC')->fetchAll();

$page_title = 'Beneficiaries';
include 'header.php';
?>

<div class="page-head">
    <div>
        <h1>Beneficiaries</h1>
        <p class="subtitle">Check the details people have given and decide who is eligible.</p>
    </div>
</div>

<?php if (count($people) === 0) { ?>

    <p class="empty">Nobody has registered as a beneficiary yet.</p>

<?php } else { ?>

    <div class="list">

    <?php foreach ($people as $person) { ?>
        <div class="card">
            <h3>
                <?php echo htmlspecialchars($person['name']); ?>
                <span class="tag <?php echo $person['status']; ?>"><?php echo $person['status']; ?></span>
            </h3>

            <p class="raised">
                <?php echo htmlspecialchars($person['email']); ?>
                <?php if ($person['phone'] !== null && $person['phone'] !== '') { ?>
                    &middot; <?php echo htmlspecialchars($person['phone']); ?>
                <?php } ?>
    </div>

                <?php if ($person['city'] !== null && $person['city'] !== '') { ?>
                    &middot; <?php echo htmlspecialchars($person['city']); ?>
                <?php } ?>
            </p>

            <table>
                <tr>
                    <th>People in the home</th>
                    <td><?php echo $person['household_size'] === null ? 'not given' : $person['household_size']; ?></td>
                    <th>Monthly income</th>
                    <td>
                        <?php echo $person['monthly_income'] === null
                            ? 'not given'
                            : number_format($person['monthly_income'], 2) . ' JOD'; ?>
                    </td>
                </tr>
            </table>

            <p class="gap-t">
                <?php
                if ($person['situation'] === null || $person['situation'] === '') {
                    echo 'This person has not described their situation yet.';
                } else {
                    echo nl2br(htmlspecialchars($person['situation']));
                }
                ?>
            </p>

            <form method="post">
                <input type="hidden" name="beneficiary_id" value="<?php echo $person['id']; ?>">

                <label for="note<?php echo $person['id']; ?>">Note for this person (optional)</label>
                <input type="text" id="note<?php echo $person['id']; ?>" name="note"
                       value="<?php echo htmlspecialchars($person['admin_note'] ?? ''); ?>">

                <button type="submit" name="decision" value="approved">Approve</button>
                <button type="submit" name="decision" value="rejected" class="red">Reject</button>
            </form>
        </div>
    <?php } ?>

<?php } ?>

<?php include 'footer.php'; ?>
