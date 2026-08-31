<?php
// Database settings and shared setup.
// Every page in the site starts by including this file.

session_start();

$host     = 'localhost';
$dbname   = 'hopebridge';
$username = 'root';
$password = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Where the "forgot password" links are written, because this project has no
// mail server. It sits one folder ABOVE the website, so a visitor cannot open it
// in the browser. Only somebody with the files can read it.
$outbox_file = dirname(__DIR__) . '/hopebridge-outbox.txt';

// How long a "remember me" cookie lasts.
$remember_days = 30;

// If there is no session but the browser still has a "remember me" cookie,
// sign the user back in from it.
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember'])) {

    // The cookie holds the real token. The database only ever holds its hash,
    // so somebody who copies the table still cannot log in as anybody.
    $find = $pdo->prepare('SELECT user_id FROM remember_tokens
                           WHERE token_hash = ? AND expires_at > NOW()');
    $find->execute([hash('sha256', $_COOKIE['remember'])]);
    $remembered = $find->fetch();

    if ($remembered) {
        $_SESSION['user_id'] = $remembered['user_id'];
    } else {
        // the token has expired or is not ours, so throw the cookie away
        setcookie('remember', '', time() - 3600, '/');
    }
}

// If somebody is logged in, read their role from the database again on every page.
// Without this an admin who had their access taken away would keep it until they
// logged out, because the old role would still be sitting in their session.
if (isset($_SESSION['user_id'])) {

    $check = $pdo->prepare('SELECT name, role FROM users WHERE id = ?');
    $check->execute([$_SESSION['user_id']]);
    $current = $check->fetch();

    if ($current) {
        $_SESSION['name'] = $current['name'];
        $_SESSION['role'] = $current['role'];
    } else {
        // the account is gone, so the session and the cookie are both no longer valid
        session_destroy();
        setcookie('remember', '', time() - 3600, '/');
        header('Location: login.php');
        exit;
    }
}

// Settings for "Login with Google" and "Login with Facebook".
// These are empty on purpose. Add your own keys - the README explains how.
$google_client_id     = '';
$google_client_secret = '';
$facebook_app_id      = '';
$facebook_app_secret  = '';

// The address the site runs on. The login buttons come back here.
$site_url = 'http://localhost:8000';
