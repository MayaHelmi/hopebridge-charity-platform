<?php
// Log in with an email address and a password.
require 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    $find = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $find->execute([$email]);
    $user = $find->fetch();

    // users who signed up with Google or Facebook have no password saved,
    // so they have to use the buttons underneath instead
    if ($user && $user['password'] === null) {
        $error = 'This account was created with ' . $user['provider'] . '. Please use that button below.';

    } elseif ($user && password_verify($pass, $user['password'])) {

        // a brand new session id, so a session id somebody already knew is useless
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['role']    = $user['role'];

        // "Remember me": keep a token in the database and the same token in a
        // cookie, so this browser can sign in again without the password.
        if (isset($_POST['remember'])) {

            $token = bin2hex(random_bytes(32));

            $insert = $pdo->prepare('INSERT INTO remember_tokens (user_id, token_hash, expires_at)
                                     VALUES (?, ?, DATE_ADD(NOW(), INTERVAL ? DAY))');
            $insert->execute([$user['id'], hash('sha256', $token), $remember_days]);

            setcookie('remember', $token, [
                'expires'  => time() + 60 * 60 * 24 * $remember_days,
                'path'     => '/',
                'httponly' => true,   // JavaScript cannot read it
                'samesite' => 'Lax',  // not sent from another site
            ]);
        }

        if ($user['role'] === 'admin') {
            header('Location: admin_dashboard.php');
        } elseif ($user['role'] === 'beneficiary') {
            header('Location: beneficiary_profile.php');
        } else {
            header('Location: index.php');
        }
        exit;

    } else {
        // the same message for a wrong email and a wrong password,
        // so nobody can find out which emails exist
        $error = 'Wrong email or password.';
    }
}

$page_title = 'Login';
include 'header.php';
?>

<div class="auth">

    <div class="form-box">

        <div class="brand">
            <img class="mark" src="images/logo.png" alt="">
            <span class="name">HopeBridge</span>
            <p>Sign in to continue to your dashboard</p>
        </div>

        <?php if (isset($_GET['reset'])) { ?>
            <p class="notice ok">Your password has been changed. You can log in with it now.</p>
        <?php } ?>

        <?php if ($error !== '') { ?>
            <p class="notice error"><?php echo htmlspecialchars($error); ?></p>
        <?php } ?>

        <form method="post">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="you@example.com"
                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">

            <div class="label-row">
                <label for="password">Password</label>
                <a href="forgot.php">Forgot password?</a>
            </div>
            <div class="reveal-wrap">
                <input type="password" id="password" name="password">
                <button type="button" class="reveal" aria-label="Show the password" hidden></button>
            </div>

            <label class="check">
                <input type="checkbox" name="remember" value="yes"
                    <?php if (isset($_POST['remember'])) echo 'checked'; ?>>
                Remember me for <?php echo $remember_days; ?> days
            </label>

            <button type="submit">Login</button>
        </form>

        <p class="divider">OR</p>

        <div class="social">
            <a href="oauth.php?provider=google">
                <img src="images/google.svg" alt=""> Continue with Google
            </a>
            <a href="oauth.php?provider=facebook">
                <img src="images/facebook.svg" alt=""> Continue with Facebook
            </a>
        </div>

        <p class="foot">New to HopeBridge? <a href="register.php">Register here</a></p>

    </div>

</div>

<?php include 'footer.php'; ?>
