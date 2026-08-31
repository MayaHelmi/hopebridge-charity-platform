<?php
// The admin adds programs, turns them on and off, and writes progress reports.
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$error = '';
$saved = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $what = $_POST['what'] ?? '';

    // ---- a new program ----
    if ($what === 'program') {

        $title       = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $category    = trim($_POST['category'] ?? '');
        $image       = trim($_POST['image'] ?? '');
        $eligibility = trim($_POST['eligibility'] ?? '');
        $goal        = $_POST['goal_amount'] ?? '';

        // basename keeps the picture to a plain file name, so nothing can be
        // pointed at a file outside the images folder
        $image = $image === '' ? null : basename($image);

        if ($category === '') {
            $category = 'General';
        }

        if ($title === '' || $goal === '') {
            $error = 'Please give the program a name and a goal.';

        } elseif (!is_numeric($goal) || $goal <= 0) {
            $error = 'The goal has to be a number bigger than zero.';

        } else {
            $insert = $pdo->prepare('INSERT INTO programs (title, description, category, image, eligibility, goal_amount) VALUES (?, ?, ?, ?, ?, ?)');
            $insert->execute([$title, $description, $category, $image, $eligibility, $goal]);
            $saved = 'The program has been added.';
        }
    }

    // ---- a progress report about a program ----
    if ($what === 'update') {

        $program_id = $_POST['program_id'] ?? 0;
        $title      = trim($_POST['update_title'] ?? '');
        $body       = trim($_POST['update_body'] ?? '');

        $check = $pdo->prepare('SELECT id FROM programs WHERE id = ?');
        $check->execute([$program_id]);

        if (!$check->fetch()) {
            $error = 'That program was not found.';

        } elseif ($title === '' || $body === '') {
            $error = 'Please write a title and a report.';

        } else {
            $insert = $pdo->prepare('INSERT INTO updates (program_id, title, body) VALUES (?, ?, ?)');
            $insert->execute([$program_id, $title, $body]);
            $saved = 'The report has been published. Donors to this program can now read it.';
        }
    }

    // ---- change the picture on a program that already exists ----
    if ($what === 'picture') {

        $image = trim($_POST['image'] ?? '');

        // basename keeps it to a plain file name inside the pictures folder
        $image = $image === '' ? null : basename($image);

        $update = $pdo->prepare('UPDATE programs SET image = ? WHERE id = ?');
        $update->execute([$image, $_POST['program_id'] ?? 0]);

        header('Location: admin_programs.php');
        exit;
    }

    // ---- turn a program on or off ----
    if ($what === 'toggle') {
        $update = $pdo->prepare('UPDATE programs SET active = 1 - active WHERE id = ?');
        $update->execute([$_POST['program_id'] ?? 0]);

        header('Location: admin_programs.php');
        exit;
    }
}

$programs = $pdo->query('SELECT programs.*,
                                COALESCE(SUM(donations.amount), 0) AS raised
                         FROM programs
                         LEFT JOIN donations ON donations.program_id = programs.id
                         GROUP BY programs.id
                         ORDER BY programs.id')->fetchAll();

$page_title = 'Manage programs';
include 'header.php';
?>

<div class="page-head">
    <div>
        <h1>Manage programs</h1>
        <p class="subtitle">Add a program, switch one off, or write a report for the donors.</p>
    </div>
</div>

<?php if ($error !== '') { ?>
    <p class="notice error"><?php echo htmlspecialchars($error); ?></p>
<?php } ?>

<?php if ($saved !== '') { ?>
    <p class="notice ok"><?php echo htmlspecialchars($saved); ?></p>
<?php } ?>

<h2>All programs</h2>

<table>
    <tr><th>Program</th><th>Category</th><th>Picture</th><th>Raised</th><th>Goal</th><th>Shown on the site</th><th></th></tr>
    <?php foreach ($programs as $program) { ?>
        <tr>
            <td><?php echo htmlspecialchars($program['title']); ?></td>
            <td><?php echo htmlspecialchars($program['category']); ?></td>
            <td>
                <form method="post" class="tools">
                    <input type="hidden" name="what" value="picture">
                    <input type="hidden" name="program_id" value="<?php echo $program['id']; ?>">

                    <select name="image">
                        <option value="">No picture</option>
                        <?php foreach (glob('images/programs/*.{jpg,jpeg,png}', GLOB_BRACE) as $file) { ?>
                            <?php $name = basename($file); ?>
                            <option value="<?php echo htmlspecialchars($name); ?>"
                                <?php if ($name === $program['image']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($name); ?>
                            </option>
                        <?php } ?>
                    </select>

                    <button type="submit" class="small quiet">Save</button>
                </form>
            </td>
            <td><?php echo number_format($program['raised'], 2); ?> JOD</td>
            <td><?php echo number_format($program['goal_amount'], 2); ?> JOD</td>
            <td>
                <span class="tag <?php echo $program['active'] ? 'approved' : 'rejected'; ?>">
                    <?php echo $program['active'] ? 'yes' : 'no'; ?>
                </span>
            </td>
            <td>
                <form method="post">
                    <input type="hidden" name="what" value="toggle">
                    <input type="hidden" name="program_id" value="<?php echo $program['id']; ?>">
                    <button type="submit" class="small grey">
                        <?php echo $program['active'] ? 'Hide' : 'Show'; ?>
                    </button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>

<h2>Add a program</h2>

<div class="form-box">
    <form method="post">
        <input type="hidden" name="what" value="program">

        <label for="title">Name of the program</label>
        <input type="text" id="title" name="title">

        <label for="description">What it does</label>
        <textarea id="description" name="description"></textarea>

        <label for="category">Category</label>
        <input type="text" id="category" name="category" placeholder="Education, Health, Food, Relief">

        <label for="image">Picture</label>
        <select id="image" name="image">
            <option value="">No picture - show the category on a plain panel</option>
            <?php foreach (glob('images/programs/*.{jpg,jpeg,png}', GLOB_BRACE) as $file) { ?>
                <option value="<?php echo htmlspecialchars(basename($file)); ?>">
                    <?php echo htmlspecialchars(basename($file)); ?>
                </option>
            <?php } ?>
        </select>

        <label for="eligibility">Who it is for</label>
        <textarea id="eligibility" name="eligibility"></textarea>

        <label for="goal_amount">Goal in JOD</label>
        <input type="text" id="goal_amount" name="goal_amount">

        <button type="submit">Add the program</button>
    </form>
</div>

<h2>Write a report for the donors</h2>

<div class="form-box">
    <form method="post">
        <input type="hidden" name="what" value="update">

        <label for="program_id">Which program</label>
        <select id="program_id" name="program_id">
            <?php foreach ($programs as $program) { ?>
                <option value="<?php echo $program['id']; ?>"><?php echo htmlspecialchars($program['title']); ?></option>
            <?php } ?>
        </select>

        <label for="update_title">Title</label>
        <input type="text" id="update_title" name="update_title">

        <label for="update_body">What happened</label>
        <textarea id="update_body" name="update_body"></textarea>

        <button type="submit">Publish the report</button>
    </form>
</div>

<?php include 'footer.php'; ?>
