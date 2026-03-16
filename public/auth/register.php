<?php
session_start(); // Start or resume session via session id
require_once __DIR__ . '/../../src/auth.php'; // Add auth.php + db.php

// If not POST, route to register
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { // Check active request for form submission (POST)
	header('Location: /register.php');
	exit; // Stop php rendering
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
	header('Location: /register.php?error=empty'); // Route to register with error query param
	exit; // Stop php rendering
}

if (register($email, $password)) {
	login($email, $password); // Auto login after registration
	header('Location: /app.php'); // Route to app
	exit; // Stop php rendering
}

header('Location: /register.php?error=exists'); // Route to register with error query param
exit; // Stop php rendering