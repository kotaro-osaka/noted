<?php
session_start();
header('Cache-Control: no-store, no-cache, must-revalidate'); // Disallow browser from saving; must ask server if page is up to date before showing; if cached version is outdated, must req server for new version
header('Pragma: no-cache'); // Older version of `no-cache`, used for compatibility reasons
require_once __DIR__ . '/../src/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
	header('Location: /');
	exit;
}

$stmt = $pdo->prepare('SELECT id, title, updated_at FROM notes WHERE user_id = ? AND status = ? ORDER BY updated_at DESC');
$stmt->execute([$_SESSION['user_id'], 'trashed']);
$notes = $stmt->fetchAll();
$count = count($notes);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>noted - Trash</title>
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
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
			</a>
		</div>
		<h1>Trash</h1>
		<p class="note-count"><?= $count ?> <?= $count === 1 ? 'Note' : 'Notes' ?></p>
	</header>
	<main>
		<?php if (empty($notes)): ?>
			<p class="empty">Trash is empty.</p>
		<?php else: ?>
			<div class="notes-grid">
				<?php foreach ($notes as $note): ?>
					<div class="note-card">
						<div class="note-preview"></div>
						<h2><?= htmlspecialchars($note['title']) ?></h2>
						<?php
						$updated = new DateTime($note['updated_at'], new DateTimeZone('UTC'));
						$updated->setTimeZone(new DateTimeZone('Europe/Berlin'));
						$today = new DateTime('today');
						$display = $updated >= $today ? $updated->format('G:i') : $updated->format('d.m.Y');
						?>
						<time><?= $display ?></time>
						<div class="note-actions">
							<a href="/api/notes/status.php?id=<?= $note['id'] ?>&status=active">Restore</a>
							<a href="/api/notes/delete.php?id=<?= $note['id'] ?>">Delete</a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</main>
</body>
</html>