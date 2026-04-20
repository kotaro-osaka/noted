<?php
require_once __DIR__ . '/../src/db.php';

session_start(); // Start or resume session via session id
// if (isset($_SESSION['user_id'])) { // Check if global array's user_id is set
// 	header('Location: /notes.php'); // Reroute user (=> Auto log in)
// 	exit; // Stop PHP rendering
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>noted - Sign in</title>
	<link rel="stylesheet" href="/css/index.css">
</head>

<body>
	<main class="auth-container">
		<h1>Sign in</h1>
		<?php if (isset($_GET['error'])): ?>
			<p class="error">
				<?=  $_GET['error'] === 'empty' ? 'Please fill in all fields.' : 'Invalid email or password.' ?>
			</p>
		<?php endif; ?>
		<form method="post" action="/auth/login.php">
			<input type="email" name="email" placeholder="Email" required>
			<input type="password" name="password" placeholder="Password" required>
			<a href="/register.php">Create an Account</a>
			<button type="submit">Continue</button>
		</form>
	</main>
</body>

</html>