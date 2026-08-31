<?php
// "Login with Google" and "Login with Facebook".
//
// This one file does both halves of the job:
//   1. it sends the visitor to Google / Facebook,
//   2. it catches them when they come back and logs them in.
require 'config.php';

// Facebook numbers its API. If Facebook stops supporting this version,
// change the number here - the README says where to check.
$facebook_version = 'v19.0';

$provider = $_GET['provider'] ?? '';

if ($provider !== 'google' && $provider !== 'facebook') {
    die('Unknown login provider.');
}


// ------------------------------------------------------------------
// Part 1 - no code yet, so send the visitor off to log in
// ------------------------------------------------------------------

if (!isset($_GET['code'])) {

    if ($provider === 'google' && $google_client_id === '') {
        die('Google login is not set up yet. Add your keys to config.php.');
    }

    if ($provider === 'facebook' && $facebook_app_id === '') {
        die('Facebook login is not set up yet. Add your keys to config.php.');
    }

    // a random secret that must come back with the visitor,
    // so that somebody else cannot fake the return trip
    $_SESSION['oauth_state'] = bin2hex(random_bytes(16));

    if ($provider === 'google') {
        $url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'client_id'     => $google_client_id,
            'redirect_uri'  => $site_url . '/oauth.php?provider=google',
            'response_type' => 'code',
            'scope'         => 'email profile',
            'state'         => $_SESSION['oauth_state'],
        ]);
    } else {
        $url = 'https://www.facebook.com/' . $facebook_version . '/dialog/oauth?' . http_build_query([
            'client_id'    => $facebook_app_id,
            'redirect_uri' => $site_url . '/oauth.php?provider=facebook',
            'scope'        => 'email',
            'state'        => $_SESSION['oauth_state'],
        ]);
    }

    header('Location: ' . $url);
    exit;
}


// ------------------------------------------------------------------
// Part 2 - the visitor came back, so find out who they are
// ------------------------------------------------------------------

// the secret has to match the one we sent
if (!isset($_GET['state']) || $_GET['state'] !== ($_SESSION['oauth_state'] ?? '')) {
    die('The login could not be verified. Please try again.');
}

unset($_SESSION['oauth_state']);


if ($provider === 'google') {

    // swap the code for a token
    $curl = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query([
        'code'          => $_GET['code'],
        'client_id'     => $google_client_id,
        'client_secret' => $google_client_secret,
        'redirect_uri'  => $site_url . '/oauth.php?provider=google',
        'grant_type'    => 'authorization_code',
    ]));
    $token = json_decode(curl_exec($curl), true);
    curl_close($curl);

    if (!isset($token['access_token'])) {
        die('Google did not give us a token. Please try again.');
    }

    // ask Google for the name and email
    $curl = curl_init('https://www.googleapis.com/oauth2/v2/userinfo?access_token=' . urlencode($token['access_token']));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $person = json_decode(curl_exec($curl), true);
    curl_close($curl);

} else {

    // swap the code for a token
    $curl = curl_init('https://graph.facebook.com/' . $facebook_version . '/oauth/access_token?' . http_build_query([
        'client_id'     => $facebook_app_id,
        'client_secret' => $facebook_app_secret,
        'redirect_uri'  => $site_url . '/oauth.php?provider=facebook',
        'code'          => $_GET['code'],
    ]));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $token = json_decode(curl_exec($curl), true);
    curl_close($curl);

    if (!isset($token['access_token'])) {
        die('Facebook did not give us a token. Please try again.');
    }

    // ask Facebook for the name and email
    $curl = curl_init('https://graph.facebook.com/' . $facebook_version . '/me?' . http_build_query([
        'fields'       => 'id,name,email',
        'access_token' => $token['access_token'],
    ]));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $person = json_decode(curl_exec($curl), true);
    curl_close($curl);
}


// Facebook only gives an email if the person allowed it
if (empty($person['email'])) {
    die('We did not receive an email address from ' . $provider . ', so the account cannot be created. Please register with the form instead.');
}

$email = $person['email'];
$name  = $person['name'] ?? $email;


// ------------------------------------------------------------------
// Part 3 - log them in, and make an account first if they are new
// ------------------------------------------------------------------

$find = $pdo->prepare('SELECT * FROM users WHERE email = ?');
$find->execute([$email]);
$user = $find->fetch();

if (!$user) {
    // new visitor - they start as a donor, with no password.
    // Somebody who needs help registers with the form instead, because
    // a beneficiary has to give the details the admin checks.
    $insert = $pdo->prepare('INSERT INTO users (name, email, password, role, provider) VALUES (?, ?, NULL, ?, ?)');
    $insert->execute([$name, $email, 'donor', $provider]);

    $find->execute([$email]);
    $user = $find->fetch();
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['name']    = $user['name'];
$_SESSION['role']    = $user['role'];

if ($user['role'] === 'admin') {
    header('Location: admin_dashboard.php');
} elseif ($user['role'] === 'beneficiary') {
    header('Location: beneficiary_profile.php');
} else {
    header('Location: index.php');
}
exit;
