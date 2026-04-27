<?php
session_start();
require_once __DIR__ . '/../../../src/db.php';

if (!isset($_SESSION['user_id'])) {
	http_response_code(401);
	exit;
}

$note_id = $_GET['id'] ?? null;
$status = $_GET['status'] ?? null;

if (!$note_id || !in_array($status, ['active', 'archived', 'trashed'])) {
	http_response_code(400);
	exit;
}

$stmt = $pdo->prepare('UPDATE notes SET status = ? WHERE id = ? AND user_id = ?');
$stmt->execute([$status, $note_id, $_SESSION['user_id']]);

header('Location: /notes.php');
exit;