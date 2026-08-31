<?php
// Private messages.
// Donors and beneficiaries always write to the admin.
// The admin picks who to write to from a list.
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$me = $_SESSION['user_id'];

if ($_SESSION['role'] === 'admin') {
    // the admin chooses the other person from the buttons at the top
    $other_id = $_GET['with'] ?? 0;
} else {
    // everybody else talks to the admin
    $admin = $pdo->query("SELECT id FROM users WHERE role = 'admin' ORDER BY id LIMIT 1")->fetch();
    $other_id = $admin ? $admin['id'] : 0;
}

// send a new message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $body = trim($_POST['body'] ?? '');

    if ($body !== '' && $other_id != 0) {
        $insert = $pdo->prepare('INSERT INTO messages (sender_id, receiver_id, body) VALUES (?, ?, ?)');
        $insert->execute([$me, $other_id, $body]);
    }

    // reload so that refreshing does not send the message twice
    header('Location: messages.php' . ($_SESSION['role'] === 'admin' ? '?with=' . $other_id : ''));
    exit;
}

// the other person's name
$other = null;
if ($other_id != 0) {
    $find_other = $pdo->prepare('SELECT id, name, role FROM users WHERE id = ?');
    $find_other->execute([$other_id]);
    $other = $find_other->fetch();
}

// the conversation between the two of us, in both directions
$conversation = [];
if ($other) {
    $find_messages = $pdo->prepare('SELECT * FROM messages
                            WHERE (sender_id = ? AND receiver_id = ?)
                               OR (sender_id = ? AND receiver_id = ?)
                            ORDER BY id');
    $find_messages->execute([$me, $other_id, $other_id, $me]);
    $conversation = $find_messages->fetchAll();
}

// the admin needs the list of everybody else
$people = [];
if ($_SESSION['role'] === 'admin') {
    $people = $pdo->query("SELECT id, name, role FROM users WHERE role != 'admin' ORDER BY name")->fetchAll();
}

$page_title = 'Messages';
include 'header.php';
?>

<div class="page-head">
    <div>
        <h1>Messages</h1>

        <?php if ($_SESSION['role'] === 'admin') { ?>
            <p class="subtitle">Choose who you want to write to.</p>
        <?php } else { ?>
            <p class="subtitle">
                Write to the charity if you have a question or need more support.
                Only you and the admin can read this.
            </p>
        <?php } ?>
    </div>
</div>

<?php if ($_SESSION['role'] === 'admin') { ?>

    <?php if (count($people) === 0) { ?>
        <p class="empty">Nobody else has registered yet.</p>
    <?php } else { ?>
        <div class="choices people">
            <?php foreach ($people as $person) { ?>
                <a href="messages.php?with=<?php echo $person['id']; ?>"
                   class="<?php echo $person['id'] == $other_id ? 'on' : ''; ?>">
                    <?php echo htmlspecialchars($person['name']); ?> (<?php echo $person['role']; ?>)
                </a>
            <?php } ?>
        </div>
    <?php } ?>

<?php } ?>


<?php if (!$other) { ?>

    <p class="empty">Pick a person above to see the conversation.</p>

<?php } else { ?>

    <h2>Conversation with <?php echo htmlspecialchars($other['name']); ?></h2>

    <?php if (count($conversation) === 0) { ?>
        <p class="empty">No messages yet. Write the first one below.</p>
    <?php } else { ?>
        <?php foreach ($conversation as $message) { ?>
            <div class="message <?php echo $message['sender_id'] == $me ? 'mine' : ''; ?>">
                <p class="who">
                    <?php echo $message['sender_id'] == $me ? 'You' : htmlspecialchars($other['name']); ?>
                    &middot; <?php echo $message['created_at']; ?>
                </p>
                <p><?php echo nl2br(htmlspecialchars($message['body'])); ?></p>
            </div>
        <?php } ?>
    <?php } ?>

    <div class="form-box composer">
        <form method="post">
            <label for="body">Your message</label>
            <textarea id="body" name="body"></textarea>
            <button type="submit">Send</button>
        </form>
    </div>

<?php } ?>

<?php include 'footer.php'; ?>
