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

// Fetch all 'active' notes from current user
$stmt = $pdo->prepare('SELECT id, title, updated_at FROM notes WHERE user_id = ? AND status = ? ORDER BY updated_at DESC');
$stmt->execute([$_SESSION['user_id'], 'active']);
$notes = $stmt->fetchAll();
$count = count($notes);
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>noted</title>
	<link rel="stylesheet" href="/css/index.css">
	<script>
		fetch('/api/auth/check.php').then(r => {
			if (r.status === 401) window.location.replace('/');
		});
	</script>
</head>

<body>
	<header>
		<div class="header-top">
			<a href="/folders.php" class="btn-icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left">
					<path d="m15 18-6-6 6-6" />
				</svg>
			</a>
			<button class="btn-icon" id="menu-btn">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ellipsis-icon lucide-ellipsis">
					<circle cx="12" cy="12" r="1" />
					<circle cx="19" cy="12" r="1" />
					<circle cx="5" cy="12" r="1" />
				</svg>
				<div class="dropdown" id="dropdown">
					<a href="/logout.php">Log out</a>
				</div>
			</button>
		</div>
		<h1>Notes</h1>
		<p class="note-count"><?= $count ?> <?= $count === 1 ? 'Note' : 'Notes' ?></p>
	</header>
	<main>
		<?php if (empty($notes)): ?>
			<p class="empty">No notes yet.</p>
		<?php else: ?>
			<div class="notes-grid">
				<?php foreach ($notes as $note): ?>
					<a href="/editor.php?id=<?= $note['id'] ?>" class="note-card">
						<div class="note-preview"></div>
						<h2><?= htmlspecialchars($note['title']) ?></h2>
						<?php
						$updated = new DateTime($note['updated_at'], new DateTimeZone('UTC'));
						$updated->setTimeZone(new DateTimeZone('Europe/Berlin'));
						$today = new DateTime('today');
						$display = $updated >= $today
							? $updated->format('G:i') // G = Remove leading 0, i = min (2 digits)
							: $updated->format('d.m.Y');
						?>
						<time><?= $display ?></time>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</main>
	<footer>
		<form method="get" action="/search.php" role="search">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search">
				<path d="m21 21-4.34-4.34" />
				<circle cx="11" cy="11" r="8" />
			</svg>
			<input type="search" name="q" placeholder="Search">
		</form>
		<a href="/editor.php" class="btn-icon">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen">
				<path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
				<path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z" />
			</svg>
		</a>
	</footer>
	<script src="/js/notes.js"></script>
</body>

</html>