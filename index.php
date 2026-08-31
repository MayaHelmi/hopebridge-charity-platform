<?php
// Home page - the hero, the four numbers, and a few programs to give to.
require 'config.php';

// The four numbers in the white bar. They are all counted from the database,
// so they are always true - nothing here is typed in by hand.
$helped   = $pdo->query("SELECT COUNT(*) AS total FROM requests WHERE status = 'approved'")->fetch();
$given    = $pdo->query('SELECT COALESCE(SUM(amount), 0) AS total FROM donations')->fetch();
$running  = $pdo->query('SELECT COUNT(*) AS total FROM programs WHERE active = 1')->fetch();
$donors   = $pdo->query("SELECT COUNT(*) AS total FROM users WHERE role = 'donor'")->fetch();

// the three programs people have given to the most
$featured = $pdo->query('SELECT programs.*, COALESCE(SUM(donations.amount), 0) AS raised
                         FROM programs
                         LEFT JOIN donations ON donations.program_id = programs.id
                         WHERE programs.active = 1
                         GROUP BY programs.id
                         ORDER BY raised DESC, programs.id
                         LIMIT 3')->fetchAll();

$page_title = 'Together, We Can Make a Difference';
$full_width = true;
include 'header.php';
?>

<section class="hero">
    <img src="images/hero-crop.jpg" alt="Volunteers painting a community mural together at sunset">
    <div class="veil"></div>

    <div class="words">
        <div>
            <h1>Together, We Can Make a Difference.</h1>
            <p>
                We connect compassionate donors with critical community needs.
                Every contribution builds a stronger, more resilient future for
                those who need it most.
            </p>

            <div class="actions">
                <a class="button give" href="programs.php">Donate Now &#9825;</a>
                <a class="button quiet" href="programs.php">Explore Programs</a>
            </div>
        </div>
    </div>
</section>

<section class="impact-bar">
    <div class="stats">
        <div>
            <span class="number"><?php echo number_format($helped['total']); ?></span>
            <span class="label">People Helped</span>
        </div>
        <div>
            <span class="number"><?php echo number_format($given['total'], 0); ?></span>
            <span class="label">JOD Donated</span>
        </div>
        <div>
            <span class="number"><?php echo number_format($running['total']); ?></span>
            <span class="label">Active Programs</span>
        </div>
        <div>
            <span class="number"><?php echo number_format($donors['total']); ?></span>
            <span class="label">Donors</span>
        </div>
    </div>
</section>

<section class="band">
    <div class="band-head">
        <h2>Featured programs</h2>
        <a href="programs.php">See all programs</a>
    </div>

    <?php if (count($featured) === 0) { ?>

        <p class="empty">There are no programs running at the moment.</p>

    <?php } else { ?>

        <div class="grid">
            <?php foreach ($featured as $program) { ?>
                <?php
                // how full the bar should be, never more than 100 per cent
                $percent = 0;
                if ($program['goal_amount'] > 0) {
                    $percent = ($program['raised'] / $program['goal_amount']) * 100;
                    if ($percent > 100) {
                        $percent = 100;
                    }
                }
                ?>
                <div class="program">
                    <div class="photo">
                        <?php if ($program['image'] !== null && $program['image'] !== '') { ?>
                            <img src="images/programs/<?php echo htmlspecialchars($program['image']); ?>"
                                 alt="<?php echo htmlspecialchars($program['title']); ?>">
                        <?php } else { ?>
                            <span class="stand-in"><?php echo htmlspecialchars($program['category']); ?></span>
                        <?php } ?>

                        <span class="kind"><?php echo htmlspecialchars($program['category']); ?></span>
                    </div>

                    <div class="body">
                        <h3><?php echo htmlspecialchars($program['title']); ?></h3>
                        <p><?php echo htmlspecialchars($program['description']); ?></p>

                        <div class="money">
                            <span>Raised <?php echo number_format($program['raised'], 0); ?> JOD</span>
                            <span class="goal">of <?php echo number_format($program['goal_amount'], 0); ?> JOD</span>
                        </div>
                        <div class="bar"><div style="width:<?php echo round($percent); ?>%"></div></div>
                    </div>

                    <div class="foot">
                        <a class="button give" href="donate.php?id=<?php echo $program['id']; ?>">Donate Now</a>
                        <a class="button quiet" href="program.php?id=<?php echo $program['id']; ?>">Read More</a>
                    </div>
                </div>
            <?php } ?>
        </div>

    <?php } ?>
</section>

<?php include 'footer.php'; ?>
