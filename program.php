<?php
// One program on its own page, with the button to donate.
require 'config.php';

$id = $_GET['id'] ?? 0;

$query = $pdo->prepare("SELECT programs.*, COALESCE(SUM(donations.amount), 0) AS raised
                        FROM programs
                        LEFT JOIN donations ON donations.program_id = programs.id
                        WHERE programs.id = ? AND programs.active = 1
                        GROUP BY programs.id");
$query->execute([$id]);
$program = $query->fetch();

if (!$program) {
    $page_title = 'Program not found';
    include 'header.php';
    echo '<p class="empty">This program was not found. <a href="programs.php">See all programs</a>.</p>';
    include 'footer.php';
    exit;
}

// how many families this program has already helped
$query = $pdo->prepare("SELECT COUNT(*) AS total FROM requests
                        WHERE program_id = ? AND status = 'approved'");
$query->execute([$id]);
$helped = $query->fetch();

// the progress reports written about this program
$query = $pdo->prepare('SELECT * FROM updates WHERE program_id = ? ORDER BY id DESC');
$query->execute([$id]);
$updates = $query->fetchAll();

$percent = 0;
if ($program['goal_amount'] > 0) {
    $percent = ($program['raised'] / $program['goal_amount']) * 100;
    if ($percent > 100) {
        $percent = 100;
    }
}

$page_title = $program['title'];
include 'header.php';
?>

<p class="back"><a href="programs.php">&larr; All programs</a></p>

<div class="page-head">
    <div>
        <h1><?php echo htmlspecialchars($program['title']); ?></h1>
        <p class="subtitle"><?php echo htmlspecialchars($program['description']); ?></p>
    </div>
    <span class="tag approved"><?php echo htmlspecialchars($program['category']); ?></span>
</div>

<div class="grid two">

    <div class="figure">
        <?php if ($program['image'] !== null && $program['image'] !== '') { ?>
            <img src="images/programs/<?php echo htmlspecialchars($program['image']); ?>"
                 alt="<?php echo htmlspecialchars($program['title']); ?>">
        <?php } else { ?>
            <div class="photo">
                <span class="stand-in"><?php echo htmlspecialchars($program['category']); ?></span>
            </div>
        <?php } ?>
    </div>

    <div class="card">
        <div class="money">
            <span>Raised <?php echo number_format($program['raised'], 2); ?> JOD</span>
            <span class="goal">of <?php echo number_format($program['goal_amount'], 2); ?> JOD</span>
        </div>
        <div class="bar"><div style="width:<?php echo round($percent); ?>%"></div></div>

        <p class="served gap-t">Beneficiaries helped: <?php echo $helped['total']; ?></p>

        <h3>Who this program is for</h3>
        <p><?php echo htmlspecialchars($program['eligibility']); ?></p>

        <?php if (!isset($_SESSION['user_id'])) { ?>
            <p class="notice info">Please <a href="login.php">log in</a> to donate to this program.</p>

        <?php } elseif ($_SESSION['role'] === 'donor') { ?>
            <a class="button give" href="donate.php?id=<?php echo $program['id']; ?>">Donate to this program</a>

        <?php } elseif ($_SESSION['role'] === 'beneficiary') { ?>
            <p class="notice info">
                If you need help from this program, apply from
                <a href="beneficiary_services.php">the services page</a>.
            </p>

        <?php } else { ?>
            <p class="notice info">You are logged in as the admin.</p>
        <?php } ?>
    </div>

</div>

<h2>What your donation has done</h2>

<?php if (count($updates) === 0) { ?>
    <p class="empty">There are no progress reports for this program yet.</p>
<?php } else { ?>
    <div class="grid two">
        <?php foreach ($updates as $update) { ?>
            <div class="card">
                <h3><?php echo htmlspecialchars($update['title']); ?></h3>
                <p class="raised"><?php echo $update['created_at']; ?></p>
                <p><?php echo nl2br(htmlspecialchars($update['body'])); ?></p>
            </div>
        <?php } ?>
    </div>
<?php } ?>

<?php include 'footer.php'; ?>
