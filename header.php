<?php
// The top of every page.
//
// There are two bars, and the split is what keeps anybody from getting stuck:
//
//   1. The site bar is the same for everyone, logged in or not. Home, Programs,
//      About and Impact are always reachable, so an admin or a beneficiary is
//      never trapped inside their own account with no way back to the site.
//
//   2. The section bar only appears once you are logged in, and holds the pages
//      that belong to your role. Because every page in a role sits in this bar,
//      each one is one click from all of its siblings.

$here = basename($_SERVER['PHP_SELF']);

$section       = '';
$section_links = [];

if (isset($_SESSION['role'])) {

    if ($_SESSION['role'] === 'donor') {
        $section = 'My giving';
        $section_links = [
            'donor_donations.php' => 'My donations',
            'donor_updates.php'   => 'Updates',
            'messages.php'        => 'Messages',
        ];

    } elseif ($_SESSION['role'] === 'beneficiary') {
        $section = 'My support';
        $section_links = [
            'beneficiary_services.php' => 'Help available',
            'beneficiary_requests.php' => 'My requests',
            'beneficiary_profile.php'  => 'My profile',
            'messages.php'             => 'Messages',
        ];

    } elseif ($_SESSION['role'] === 'admin') {
        $section = 'Administration';
        $section_links = [
            'admin_dashboard.php'     => 'Dashboard',
            'admin_beneficiaries.php' => 'Beneficiaries',
            'admin_requests.php'      => 'Applications',
            'admin_programs.php'      => 'Manage programs',
            'admin_donations.php'     => 'Donations',
            'admin_users.php'         => 'Users',
            'messages.php'            => 'Messages',
        ];
    }
}

// the four pages everybody can see
$site_links = [
    'index.php'    => 'Home',
    'programs.php' => 'Programs',
    'about.php'    => 'About',
    'impact.php'   => 'Impact',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - HopeBridge' : 'HopeBridge'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<a class="skip" href="#content">Skip to the content</a>

<div class="bars">

<header class="topbar">
<div class="bar-inner">

    <a class="logo" href="index.php">
        <img src="images/logo.png" alt="">
        HopeBridge
    </a>

    <!-- a checkbox is used so the small-screen menu works without JavaScript -->
    <input type="checkbox" id="menu-toggle">
    <label for="menu-toggle" class="menu-button">Menu</label>

    <nav class="site" aria-label="Site">
        <?php foreach ($site_links as $file => $label) { ?>
            <a href="<?php echo $file; ?>"
               class="<?php echo $here === $file ? 'on' : ''; ?>"
               <?php if ($here === $file) echo 'aria-current="page"'; ?>><?php echo $label; ?></a>
        <?php } ?>
    </nav>

    <div class="account">
        <?php if (!isset($_SESSION['user_id'])) { ?>

            <a href="login.php">Login/Register</a>
            <a class="give" href="programs.php">Donate Now</a>

        <?php } else { ?>

            <span class="signed-in">
                <?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?>
                <b><?php echo htmlspecialchars($_SESSION['role']); ?></b>
            </span>

            <?php if ($_SESSION['role'] === 'donor') { ?>
                <a class="give" href="programs.php">Donate Now</a>
            <?php } ?>

            <a href="logout.php">Logout</a>

        <?php } ?>
    </div>

</div>
</header>

<?php if ($section !== '') { ?>
<div class="subbar">
<div class="bar-inner">

    <span class="section"><?php echo $section; ?></span>

    <nav aria-label="<?php echo $section; ?>">
        <?php foreach ($section_links as $file => $label) { ?>
            <a href="<?php echo $file; ?>"
               class="<?php echo $here === $file ? 'on' : ''; ?>"
               <?php if ($here === $file) echo 'aria-current="page"'; ?>><?php echo $label; ?></a>
        <?php } ?>
    </nav>

</div>
</div>
<?php } ?>

</div><!-- .bars -->

<main id="content" class="page<?php echo isset($full_width) ? ' full' : ''; ?>">
