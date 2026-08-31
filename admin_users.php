<?php
// Who is allowed into the admin side of the site.
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id  = $_POST['user_id'] ?? 0;
    $decision = $_POST['decision'] ?? '';

    $find = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $find->execute([$user_id]);
    $user = $find->fetch();

    if (!$user) {
        $error = 'That user was not found.';

    } elseif ($user['id'] == $_SESSION['user_id']) {
        // stops an admin locking themselves out by mistake
        $error = 'You cannot change your own access.';

    } elseif ($decision === 'make_admin') {

        // beneficiaries are left out on purpose: their own records are the
        // private data the admin side is there to protect
        if ($user['role'] !== 'donor') {
            $error = 'Only a donor account can be made an admin.';
        } else {
            $update = $pdo->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
            $update->execute([$user_id]);
        }

    } elseif ($decision === 'remove_admin') {

        // The site can never end up with no admin at all: you are not allowed to
        // change your own access, so anyone you are able to remove is a second admin.
        if ($user['role'] !== 'admin') {
            $error = 'That user is not an admin.';
        } else {
            $update = $pdo->prepare("UPDATE users SET role = 'donor' WHERE id = ?");
            $update->execute([$user_id]);
        }
    }

    if ($error === '') {
        header('Location: admin_users.php');
        exit;
    }
}

$users = $pdo->query('SELECT * FROM users ORDER BY FIELD(role, "admin", "donor", "beneficiary"), name')->fetchAll();

$page_title = 'Users';
include 'header.php';
?>

<div class="page-head">
    <div>
        <h1>Users</h1>
        <p class="subtitle">Everyone with an account, and who can reach the admin pages.</p>
    </div>
</div>

<?php if ($error !== '') { ?>
    <p class="notice error"><?php echo htmlspecialchars($error); ?></p>
<?php } ?>

<p class="notice info">
    Only donor accounts can be made admins. Beneficiary records hold private
    information, so those accounts are kept out of the admin side.
</p>

<table>
    <tr><th>Name</th><th>Email</th><th>Role</th><th>Signed up with</th><th>Access</th></tr>
    <?php foreach ($users as $user) { ?>
        <tr>
            <td><?php echo htmlspecialchars($user['name']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo $user['role']; ?></td>
            <td><?php echo $user['provider']; ?></td>
            <td>
                <?php if ($user['id'] == $_SESSION['user_id']) { ?>
                    <span class="meta">this is you</span>

                <?php } elseif ($user['role'] === 'admin') { ?>
                    <form method="post">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" name="decision" value="remove_admin" class="small red">Remove admin</button>
                    </form>

                <?php } elseif ($user['role'] === 'donor') { ?>
                    <form method="post">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" name="decision" value="make_admin" class="small">Make admin</button>
                    </form>

                <?php } else { ?>
                    <span class="meta">&mdash;</span>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>

<?php include 'footer.php'; ?>
