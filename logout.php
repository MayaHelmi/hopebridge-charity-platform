<?php
// Empty the session and send the visitor back to the home page.
require 'config.php';

// The "remember me" token has to go as well, otherwise the next page load
// would quietly sign the same browser straight back in.
if (isset($_COOKIE['remember'])) {

    $delete = $pdo->prepare('DELETE FROM remember_tokens WHERE token_hash = ?');
    $delete->execute([hash('sha256', $_COOKIE['remember'])]);

    setcookie('remember', '', time() - 3600, '/');
}

session_destroy();

header('Location: index.php');
exit;
