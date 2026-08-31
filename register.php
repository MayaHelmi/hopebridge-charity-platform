<?php
// Create a new account, either as a donor or as a beneficiary.
require 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $role  = $_POST['role'] ?? '';

    if ($name === '' || $email === '' || $pass === '') {
        $error = 'Please fill in all the fields.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';

    } elseif (strlen($pass) < 6) {
        $error = 'The password must be at least 6 characters.';

    } elseif ($role !== 'donor' && $role !== 'beneficiary') {
        $error = 'Please choose an account type.';

    } else {
        // has somebody already registered with this email?
        $check = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $check->execute([$email]);

        if ($check->fetch()) {
            $error = 'This email address is already registered.';

        } else {
            // password_hash scrambles the password so the real one is never stored
            $hash = password_hash($pass, PASSWORD_DEFAULT);

            $insert = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
            $insert->execute([$name, $email, $hash, $role]);

            $user_id = $pdo->lastInsertId();

            // a beneficiary also needs a profile, which the admin has to approve
            if ($role === 'beneficiary') {
                $profile = $pdo->prepare('INSERT INTO beneficiaries (user_id) VALUES (?)');
                $profile->execute([$user_id]);
            }

            $_SESSION['user_id'] = $user_id;
            $_SESSION['name']    = $name;
            $_SESSION['role']    = $role;

            if ($role === 'beneficiary') {
                header('Location: beneficiary_profile.php');
            } else {
                header('Location: index.php');
            }
            exit;
        }
    }
}

// which card starts off chosen
$chosen = $_POST['role'] ?? 'donor';

$page_title = 'Register';
include 'header.php';
?>

<div class="auth">

    <div class="form-box">

        <div class="brand">
            <span class="name">Join HopeBridge.</span>
            <p>What type of account are you creating?</p>
        </div>

        <?php if ($error !== '') { ?>
            <p class="notice error"><?php echo htmlspecialchars($error); ?></p>
        <?php } ?>

        <form method="post">

            <div class="pick">
                <label>
                    <input type="radio" name="role" value="donor"
                        <?php if ($chosen === 'donor') echo 'checked'; ?>>
                    <span>Donor</span>
                    <small>I want to give to a program.</small>
                </label>

                <label>
                    <input type="radio" name="role" value="beneficiary"
                        <?php if ($chosen === 'beneficiary') echo 'checked'; ?>>
                    <span>Beneficiary</span>
                    <small>I need help from a program.</small>
                </label>
            </div>

            <label for="name">Full Name</label>
            <input type="text" id="name" name="name"
                   value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="you@example.com"
                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">

            <label for="password">Password</label>
            <div class="reveal-wrap">
                <input type="password" id="password" name="password">
                <button type="button" class="reveal" aria-label="Show the password" hidden></button>
            </div>

            <button type="submit">Create my account</button>
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

        <p class="foot">Already have an account? <a href="login.php">Login</a></p>

    </div>

</div>

<?php include 'footer.php'; ?>
