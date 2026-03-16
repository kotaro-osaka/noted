<?php
function loadEnv(string $path): void {
	if (!file_exists($path)) return;

	$lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	foreach ($lines as $line) {
		if (str_starts_with(trim($line), '#')) continue; // Skip commments
		[$key, $value] = explode('=', $line, 2);
		$_ENV[trim($key)] = trim($value); // Save value in global PHP-Array for environment variables
	}
}

loadEnv(__DIR__ . '/../.env');

try {
	$dsn = "pgsql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']}";

	$pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Return exception instead of 'false'
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Only return named indices
	]);
} catch (PDOException $e) {
	http_response_code(500); // Tell browser a server error occurred
	die(json_encode(['error' => 'Database connection failed'])); // Stop execution & return JSON error instead of PHP error
}