<?php
session_start();
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
require_once __DIR__ . '/../src/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
	header('Location: /');
	exit;
}

$note_id = $_GET['id'] ?? null; // Null = New note
$note = null;

if ($note_id) {
	$stmt = $pdo->prepare('SELECT * FROM notes WHERE id = ? AND user_id = ?');
	$stmt->execute([$note_id, $_SESSION['user_id']]);
	$note = $stmt->fetch();
	var_dump($note['status']);

	// Note not found or doesn't belong to user
	if (!$note) {
		header('Location: /notes.php');
		exit;
	}
}

$back = '/notes.php';
if ($note) {
	if ($note['status'] === 'archived') $back = '/archive.php';
}
var_dump($back);
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>noted - <?= $note ? htmlspecialchars($note['title']) : 'New Note' ?></title>
	<link rel="stylesheet" href="/css/index.css">
	<script>
		// Prevent from navigating back after logout
		fetch('/api/auth/check.php').then(r => {
			if (r.status === 401) window.location.replace('/');
		});
	</script>
</head>

<body>
	<header class="editor-header">
		<a href="<?= $back ?>" class="btn-icon">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left">
				<path d="m15 18-6-6 6-6" />
			</svg>
		</a>
		<div class="editor-header-actions">
			<?php if ($note_id): ?>
				<div class="menu-wrapper">
					<button class="btn-icon" id="menu-btn">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        	<circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/>
                    	</svg>
					</button>
					<div class="dropdown" id="dropdown">
						<?php if ($note && $note['status'] !== 'archived'): ?>
							<a href="/api/notes/status.php?id=<?= $note_id ?>&status=archived">Archive</a>
						<?php endif; ?>
						<?php if ($note && $note['status'] !== 'trashed'): ?>
							<a href="/api/notes/status.php?id=<?= $note_id ?>&status=trashed">Move to Trash</a>
						<?php endif; ?>
						<?php if ($note && $note['status'] !== 'active'): ?>
							<a href="/api/notes/status.php?id=<?= $note_id ?>&status=active">Restore</a>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
			<button class="btn-icon btn-confirm" id="confirm-btn">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check">
					<path d="M20 6 9 17l-5-5" />
				</svg>
			</button>
		</div>
	</header>
	<main>
		<input type="text" id="title" placeholder="Title" value="<?= $note ? htmlspecialchars($note['title']) : '' ?>">
		<textarea id="content" placeholder="Start writing..."><?= $note ? htmlspecialchars($note['content']) : '' ?></textarea>
	</main>
	<script src="/js/editor.js"></script>
	<script src="/js/notes.js"></script>
</body>

</html>