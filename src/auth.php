<?php
require_once __DIR__ . '/db.php'; // Add file for accessing pdo

function register(string $email, string $password): bool {
	global $pdo; // Use global pdo instead of local

	// Check if email already exists
	$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
	$stmt->execute([$email]);
	if ($stmt->fetch()) return false; // Check for entry

	$hash = password_hash($password, PASSWORD_ARGON2ID);

	// Insert new user
	$stmt = $pdo->prepare('INSERT INTO users (email, password_hash) VALUES (?, ?)');
	return $stmt->execute([$email, $hash]);
}

function login(string $email, string $password): bool {
	global $pdo; // Use global pdo instead of local

	// Find user by email
	$stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE email = ?');
	$stmt->execute([$email]);
	$user = $stmt->fetch();

	if (!$user) return false; // Check for entry

	// Verify password against hash
	if (!password_verify($password, $user['password_hash'])) return false;

	// Store user id in session
	$_SESSION['user_id'] = $user['id'];
	return true;
}