<?php
function loadEnv(string $path): void {
	if (!file_exists($path)) return;

	$lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	foreach ($lines as $line) {
		if (str_starts_with(trim($line), '#')) continue;
		[$key, $value] = explode('=', $line, 2);
		$_ENV[trim($key)] = trim($value);
	}
}

loadEnv(__DIR__ . '/../.env');

try {
	$dsn = 'sqlite:' . __DIR__ . '/../' . $_ENV['DB_PATH'];

	$pdo = new PDO($dsn, null, null, [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Return exception instead of 'false'
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Only return named indices
	]);

	// Enable foreign keys
	$pdo->exec('PRAGMA foreign_keys = ON;');
} catch (PDOException $e) {
	http_response_code(500); // Tell browser a server error occurred
	die(json_encode(['error' => 'Database connection failed'])); // Stop execution & return JSON error instead of PHP error
}