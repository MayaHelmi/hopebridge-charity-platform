<?php
// All the programs a donor can give to, with a search box, a category
// filter and a sort order.
require 'config.php';

$search   = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');
$sort     = $_GET['sort'] ?? 'newest';

// The sort order is picked from this list instead of being put straight into
// the query, because anything coming from the address bar cannot be trusted.
if ($sort === 'raised') {
    $order = 'raised DESC, programs.id DESC';
} elseif ($sort === 'closest') {
    $order = '(COALESCE(SUM(donations.amount), 0) / programs.goal_amount) DESC, programs.id DESC';
} else {
    $sort  = 'newest';
    $order = 'programs.id DESC';
}

// The search words and the category are still put in with ?, which is what
// keeps somebody from injecting SQL through the search box.
$sql = "SELECT programs.*,
               COALESCE(SUM(donations.amount), 0) AS raised,
               (SELECT COUNT(*) FROM requests
                WHERE requests.program_id = programs.id
                  AND requests.status = 'approved') AS helped
        FROM programs
        LEFT JOIN donations ON donations.program_id = programs.id
        WHERE programs.active = 1";

$values = [];

if ($search !== '') {
    $sql .= ' AND programs.title LIKE ?';
    $values[] = '%' . $search . '%';
}

if ($category !== '') {
    $sql .= ' AND programs.category = ?';
    $values[] = $category;
}

$sql .= ' GROUP BY programs.id ORDER BY ' . $order;

$query = $pdo->prepare($sql);
$query->execute($values);
$programs = $query->fetchAll();

// the categories to put in the drop down
$categories = $pdo->query('SELECT DISTINCT category FROM programs WHERE active = 1 ORDER BY category')->fetchAll();

$page_title = 'Programs';
include 'header.php';
?>

<div class="page-head">
    <div>
        <h1>Active Programs</h1>
        <p class="subtitle">Discover and support initiatives making a real impact.</p>
    </div>

    <form class="tools" method="get">
        <input type="text" name="search" placeholder="Search programs..."
               value="<?php echo htmlspecialchars($search); ?>">

        <select name="category">
            <option value="">All Categories</option>
            <?php foreach ($categories as $row) { ?>
                <option value="<?php echo htmlspecialchars($row['category']); ?>"
                    <?php if ($row['category'] === $category) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($row['category']); ?>
                </option>
            <?php } ?>
        </select>

        <select name="sort">
            <option value="newest"  <?php if ($sort === 'newest')  echo 'selected'; ?>>Sort By: Newest</option>
            <option value="raised"  <?php if ($sort === 'raised')  echo 'selected'; ?>>Sort By: Most raised</option>
            <option value="closest" <?php if ($sort === 'closest') echo 'selected'; ?>>Sort By: Closest to goal</option>
        </select>

        <button type="submit">Search</button>
    </form>
</div>

<?php if (count($programs) === 0) { ?>

    <p class="empty">No programs found. <a href="programs.php">Show them all</a>.</p>

<?php } else { ?>

    <div class="grid">
        <?php foreach ($programs as $program) { ?>
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
                             alt="<?php echo htmlspecialchars($program['title']); ?>"
                             loading="lazy" decoding="async">
                    <?php } else { ?>
                        <span class="stand-in"><?php echo htmlspecialchars($program['category']); ?></span>
                    <?php } ?>

                    <?php $kind = preg_replace('/[^a-z]/', '', strtolower($program['category'])); ?>
                    <span class="kind kind-<?php echo $kind; ?>"><?php echo htmlspecialchars($program['category']); ?></span>
                </div>

                <div class="body">
                    <h2><?php echo htmlspecialchars($program['title']); ?></h2>
                    <p><?php echo htmlspecialchars($program['description']); ?></p>

                    <span class="served">People helped: <?php echo $program['helped']; ?></span>

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

<?php include 'footer.php'; ?>
