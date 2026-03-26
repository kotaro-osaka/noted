<?php
session_start();
require_once __DIR__ . '/../../../src/db.php';

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405); // Code = 'Method not allowed'
	exit;
}

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
	http_response_code(401); // Code = 'Unauthorized'
	exit;
}

// Read JSON body
$body = json_decode(file_get_contents('php://input'), true); // PHP-Stream that reads raw request-body
$title = trim($body['title'] ?? 'Untitled');
$content = $body['content'] ?? '';
$note_id = $body['id'] ?? null;

header('Content-Type: application/json');

if ($note_id) {
	// Update existing note
	$stmt = $pdo->prepare('UPDATE notes SET title = ?, content = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND user_id = ?');
	$stmt->execute([$title, $content, $note_id, $_SESSION['user_id']]);
	echo json_encode(['id' => $note_id]);
} else {
	// Create new note
	$stmt = $pdo->prepare('INSERT INTO notes (user_id, title, content) VALUES (?, ?, ?)');
	$stmt->execute([$_SESSION['user_id'], $title, $content]);
	echo json_encode(['id' => $pdo->lastInsertId()]);
}