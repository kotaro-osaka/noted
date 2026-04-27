<?php
session_start();
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
require_once __DIR__ . '/../src/db.php';

if (!isset($_SESSION['user_id'])) {
	header('Location: /');
	exit;
}

$q = trim($_GET['q'] ?? '');
$notes = [];
$count = 0;

if ($q !== '') {
	$stmt = $pdo->prepare('
		SELECT notes.id, notes.title, notes.updated_at, notes.status
		FROM notes
		JOIN notes_fts ON notes.id = notes_fts.rowid
		WHERE notes_fts MATCH ?
		AND notes.user_id = ?
		AND notes.status = \'active\'
		ORDER BY rank
	');
	$stmt->execute([$q, $_SESSION['user_id']]);
	$notes = $stmt->fetchAll();
	$count = count($notes);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>noted - Search</title>
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
			<a href="/notes.php" class="btn-icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
			</a>
		</div>
		<h1>Search</h1>
		<p class="note-count"><?= $count ?> <?= $count === 1 ? 'Result' : 'Results' ?></p>
	</header>
	<main>
		<form method="get" action="/search.php" role="search" class="search-form">
			<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/>
            </svg>
			<input type="search" name="q" placeholder="Search" value="<?= htmlspecialchars($q) ?>" autofocus>
		</form>
		<?php if ($q === ''): ?>
			<p class="empty">Start typing to search.</p>
		<?php elseif (empty($notes)): ?>
			<p class="empty">No results for "<?= htmlspecialchars($q) ?>".</p>
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
						$display = $updated >= $today ? $updated->format('G:i') : $updated->format('d.m.Y');
						?>
						<time><?= $display ?></time>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</main>
	<script src="/js/notes.js"></script>
</body>
</html>