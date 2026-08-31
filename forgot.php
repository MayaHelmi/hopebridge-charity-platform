<?php
// Ask for a password reset link.
require 'config.php';

$done  = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');

    if ($email === '') {
        $error = 'Please enter your email address.';

    } else {
        $find = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $find->execute([$email]);
        $user = $find->fetch();

        $when = date('Y-m-d H:i:s');

        if ($user) {

            // an old unused link for the same person is thrown away first,
            // so only the newest link ever works
            $clear = $pdo->prepare('DELETE FROM password_resets WHERE user_id = ? AND used = 0');
            $clear->execute([$user['id']]);

            $token = bin2hex(random_bytes(32));

            // only the hash is stored, exactly like the "remember me" tokens
            $insert = $pdo->prepare('INSERT INTO password_resets (user_id, token_hash, expires_at)
                                     VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))');
            $insert->execute([$user['id'], hash('sha256', $token)]);

            $link = $site_url . '/reset.php?token=' . $token;
            $line = $when . '  reset link for ' . $email . '  ->  ' . $link;

        } else {
            // A line is written even when nobody has that address, so that both
            // cases take the same path and an outsider cannot use this page to
            // find out which email addresses are registered.
            $line = $when . '  reset asked for ' . $email . '  ->  no account with that address';
        }

        file_put_contents($outbox_file, $line . PHP_EOL, FILE_APPEND);

        $done = true;
    }
}

$page_title = 'Forgot password';
include 'header.php';
?>

<div class="auth">

    <div class="form-box">

        <div class="brand">
            <span class="name">Forgot password?</span>
            <p>Enter your email address and we will send you a link to choose a new one.</p>
        </div>

        <?php if ($error !== '') { ?>
            <p class="notice error"><?php echo htmlspecialchars($error); ?></p>
        <?php } ?>

        <?php if ($done) { ?>

            <p class="notice ok">
                If that email address has an account, a reset link has been sent to it.
                The link stops working after one hour.
            </p>

        <?php } else { ?>

            <form method="post">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="you@example.com"
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">

                <button type="submit">Send me a reset link</button>
            </form>

        <?php } ?>

        <p class="foot">Remembered it? <a href="login.php">Back to login</a></p>

    </div>

</div>

<?php include 'footer.php'; ?>
