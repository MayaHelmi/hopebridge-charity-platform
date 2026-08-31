<?php
// Choose a new password, using the link from the reset email.
require 'config.php';

// the token arrives in the address on the first visit, and in the form after that
$token = $_GET['token'] ?? $_POST['token'] ?? '';
$error = '';

// Is this a real link that has not expired and has not been used already?
$reset = false;

if ($token !== '') {
    $find = $pdo->prepare('SELECT * FROM password_resets
                           WHERE token_hash = ? AND used = 0 AND expires_at > NOW()');
    $find->execute([hash('sha256', $token)]);
    $reset = $find->fetch();
}

if ($reset && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $pass    = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($pass === '' || $confirm === '') {
        $error = 'Please fill in both boxes.';

    } elseif (strlen($pass) < 6) {
        $error = 'The password must be at least 6 characters.';

    } elseif ($pass !== $confirm) {
        $error = 'The two passwords are not the same.';

    } else {
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        $update = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
        $update->execute([$hash, $reset['user_id']]);

        // the link is now spent
        $spend = $pdo->prepare('UPDATE password_resets SET used = 1 WHERE id = ?');
        $spend->execute([$reset['id']]);

        // Anybody still signed in on another browser through "remember me" is
        // signed out, because changing the password is how you lock somebody out.
        $forget = $pdo->prepare('DELETE FROM remember_tokens WHERE user_id = ?');
        $forget->execute([$reset['user_id']]);

        header('Location: login.php?reset=done');
        exit;
    }
}

$page_title = 'Choose a new password';
include 'header.php';
?>

<div class="auth">

    <div class="form-box">

        <div class="brand">
            <span class="name">New password</span>
            <p>Choose the password you want to use from now on.</p>
        </div>

        <?php if (!$reset) { ?>

            <p class="notice error">
                This link does not work any more. It may have been used already, or it
                may be more than an hour old.
            </p>

            <p class="foot"><a href="forgot.php">Ask for a new link</a></p>

        <?php } else { ?>

            <?php if ($error !== '') { ?>
                <p class="notice error"><?php echo htmlspecialchars($error); ?></p>
            <?php } ?>

            <form method="post">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                <label for="password">New password</label>
                <div class="reveal-wrap">
                    <input type="password" id="password" name="password">
                    <button type="button" class="reveal" aria-label="Show the password" hidden></button>
                </div>

                <label for="confirm">Type it again</label>
                <div class="reveal-wrap">
                    <input type="password" id="confirm" name="confirm">
                    <button type="button" class="reveal" aria-label="Show the password" hidden></button>
                </div>

                <button type="submit">Save my new password</button>
            </form>

            <p class="foot">Changed your mind? <a href="login.php">Back to login</a></p>

        <?php } ?>

    </div>

</div>

<?php include 'footer.php'; ?>
