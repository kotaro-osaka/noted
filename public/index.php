<?php
// Entry point
session_start(); // Start or resume session via session id
if (isset($_SESSION['user_id'])) { // Check if global array's user_id is set
	header('Location: /app.php'); // Reroute user (=> Auto log in)
	exit; // Stop PHP rendering
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>noted - Sign in</title>
	<link rel="stylesheet" href="/css/style.css">
</head>
<body>
	<main class="auth-container">
		<h1>Sign in</h1>
		<form method="post" action="/auth/login.php">
			<input type="email" name="email" placeholder="Email" required>
			<input type="password" name="password" placeholder="Password" required>
			<a href="/register.php">Create an Account</a>
			<button type="submit">Continue</button>
		</form>
	</main>
</body>
</html>