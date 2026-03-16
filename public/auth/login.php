<?php
session_start(); // Start or resume session via session id
require_once __DIR__ . '/../../src/auth.php'; // Add auth.php + db.php

// If not POST, route to index
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { // Check active request for form submission (POST)
	header('Location: /');
	exit; // Stop php rendering
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
	header('Location: /?error=empty'); // Route to index with error query param
	exit; // Stop php rendering
}

if (login($email, $password)) {
	header('Location: /app.php'); // Route to app
	exit; // Stop php rendering
}

header('Location: /?error=invalid'); // Route to index with error query param
exit; // Stop php rendering