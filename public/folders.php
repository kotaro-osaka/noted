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

// Count notes per status
$counts = [];
foreach (['active', 'archived', 'trashed'] as $status) {
	$stmt = $pdo->prepare('SELECT COUNT(*) FROM notes WHERE user_id = ? AND status = ?');
	$stmt->execute([$_SESSION['user_id'], $status]);
	$counts[$status] = $stmt->fetchColumn();
}
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
			<h1>Folders</h1>
			<button class="btn-icon" id="menu-btn">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/>
                </svg>
				<div class="dropdown" id="dropdown">
					<a href="/logout.php">Log out</a>
				</div>
			</button>
		</div>
		<p class="note-count"><?= $counts['active'] + $counts['archived'] ?> Notes</p>
	</header>
	<main>
		<div class="folders-grid">
			<a href="/notes.php" class="folder-card">
				<div class="folder-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"/>
                    </svg>
				</div>
				<span>Notes</span>
				<span class="note-count"><?= $counts['active'] ?></span>
			</a>
			<a href="/archive.php" class="folder-card">
				<div class="folder-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="5" x="2" y="3" rx="1"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><path d="M10 12h4"/>
                    </svg>
				</div>
				<span>Archive</span>
				<span class="note-count"><?= $counts['archived'] ?></span>
			</a>
			<a href="/trash.php" class="folder-card">
				<div class="folder-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                    </svg>
				</div>
				<span>Trash</span>
				<span class="note-count"><?= $counts['trashed'] ?></span>
			</a>
		</div>
	</main>
	<footer>
		<form method="get" action="/search.php" role="search">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/>
            </svg>
			<input type="search" name="q" placeholder="Search">
		</form>
		<a href="/editor.php" class="btn-icon">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/>
            </svg>
		</a>
	</footer>
	<script src="/js/notes.js"></script>
</body>
</html>