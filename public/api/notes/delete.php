<?php
session_start();
require_once __DIR__ . '/../../../src/db.php';

if (!isset($_SESSION['user_id'])) {
	http_response_code(401);
	exit;
}

$note_id = $_GET['id'] ?? null;

if (!$note_id) {
	http_response_code(400);
	exit;
}

$stmt = $pdo->prepare('DELETE FROM notes WHERE id = ? AND user_id = ? AND status = ?');
$stmt->execute([$note_id, $_SESSION['user_id'], 'trashed']);

header('Location: /trash.php');
exit;