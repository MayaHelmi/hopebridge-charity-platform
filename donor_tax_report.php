<?php
// A donor's giving for one year, on one page they can print and keep.
// Every figure is added up from the donations table, so the statement always
// matches the receipts.
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donor') {
    header('Location: login.php');
    exit;
}

// the years this donor actually gave in, newest first
$years = $pdo->prepare('SELECT DISTINCT YEAR(created_at) AS year
                        FROM donations
                        WHERE donor_id = ?
                        ORDER BY year DESC');
$years->execute([$_SESSION['user_id']]);
$years = $years->fetchAll();

// the year being shown: the one asked for, if the donor really gave in it,
// otherwise the most recent one
$year = $_GET['year'] ?? 0;

$allowed = [];
foreach ($years as $row) {
    $allowed[] = $row['year'];
}

if (!in_array($year, $allowed)) {
    $year = count($allowed) > 0 ? $allowed[0] : date('Y');
}

// every gift in that year
$gifts = $pdo->prepare('SELECT donations.*, programs.title
                        FROM donations
                        JOIN programs ON programs.id = donations.program_id
                        WHERE donations.donor_id = ? AND YEAR(donations.created_at) = ?
                        ORDER BY donations.created_at');
$gifts->execute([$_SESSION['user_id'], $year]);
$gifts = $gifts->fetchAll();

// the summary at the top
$summary = $pdo->prepare('SELECT COUNT(*) AS gifts,
                                 COALESCE(SUM(amount), 0) AS total,
                                 COALESCE(ROUND(AVG(amount), 2), 0) AS average,
                                 COALESCE(MAX(amount), 0) AS largest
                          FROM donations
                          WHERE donor_id = ? AND YEAR(created_at) = ?');
$summary->execute([$_SESSION['user_id'], $year]);
$summary = $summary->fetch();

// which cause they gave the most to that year
$favourite = $pdo->prepare('SELECT programs.title, SUM(donations.amount) AS total
                            FROM donations
                            JOIN programs ON programs.id = donations.program_id
                            WHERE donations.donor_id = ? AND YEAR(donations.created_at) = ?
                            GROUP BY programs.id
                            ORDER BY total DESC
                            LIMIT 1');
$favourite->execute([$_SESSION['user_id'], $year]);
$favourite = $favourite->fetch();

$page_title = 'Tax report';
include 'header.php';
?>

<div class="page-head">
    <div>
        <h1>Annual statement <?php echo $year; ?></h1>
        <p class="subtitle">Everything you gave in <?php echo $year; ?>, ready to print for your records.</p>
    </div>

    <?php if (count($years) > 1) { ?>
        <form class="tools" method="get">
            <label class="visually-hidden" for="year">Year</label>
            <select id="year" name="year">
                <?php foreach ($years as $row) { ?>
                    <option value="<?php echo $row['year']; ?>"
                        <?php if ($row['year'] == $year) echo 'selected'; ?>>
                        <?php echo $row['year']; ?>
                    </option>
                <?php } ?>
            </select>
            <button type="submit">Show</button>
        </form>
    <?php } ?>
</div>

<?php if (count($gifts) === 0) { ?>

    <p class="empty">You did not give anything in <?php echo $year; ?>.
        <a href="programs.php">See the programs</a>.</p>

<?php } else { ?>

    <div class="stats">
        <div>
            <span class="number"><?php echo number_format($summary['total'], 0); ?></span>
            <span class="label">JOD Given</span>
        </div>
        <div>
            <span class="number"><?php echo $summary['gifts']; ?></span>
            <span class="label">Donations</span>
        </div>
        <div>
            <span class="number"><?php echo number_format($summary['average'], 0); ?></span>
            <span class="label">Average Gift</span>
        </div>
        <div>
            <span class="number"><?php echo number_format($summary['largest'], 0); ?></span>
            <span class="label">Largest Gift</span>
        </div>
    </div>

    <?php if ($favourite) { ?>
        <p class="notice ok">
            The cause you supported most in <?php echo $year; ?> was
            <strong><?php echo htmlspecialchars($favourite['title']); ?></strong>,
            with <?php echo number_format($favourite['total'], 2); ?> JOD.
        </p>
    <?php } ?>

    <div class="receipt statement">
        <h2>HopeBridge</h2>
        <p class="meta">
            Annual statement for <?php echo htmlspecialchars($_SESSION['name']); ?>
            &middot; <?php echo $year; ?>
        </p>

        <table>
            <tr>
                <th>Date</th>
                <th>Program</th>
                <th>Receipt</th>
                <th>Amount</th>
            </tr>
            <?php foreach ($gifts as $gift) { ?>
                <tr>
                    <td><?php echo $gift['created_at']; ?></td>
                    <td><?php echo htmlspecialchars($gift['title']); ?></td>
                    <td>#<?php echo $gift['id']; ?></td>
                    <td><?php echo number_format($gift['amount'], 2); ?> JOD</td>
                </tr>
            <?php } ?>
            <tr>
                <th>Total</th>
                <th></th>
                <th></th>
                <th><?php echo number_format($summary['total'], 2); ?> JOD</th>
            </tr>
        </table>
    </div>

    <button onclick="window.print()">Print this statement</button>

<?php } ?>

<?php include 'footer.php'; ?>
