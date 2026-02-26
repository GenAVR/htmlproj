<?php
// Database configuration
$host = 'localhost'; // Database host
$user = 'admin';     // Database username
$password = 'YES';   // Database password
$database = 'bibique'; // Database name

// Defensive connection setup: prefer mysqli, fallback to PDO when available.
$db_error = null;
$conn = null;

if (class_exists('mysqli')) {
	// MySQLi available
	$conn = @new mysqli($host, $user, $password, $database);
	if ($conn->connect_errno) {
		$db_error = 'MySQLi connection error: ' . $conn->connect_error;
		$conn = null;
	}
} elseif (class_exists('PDO')) {
	// Try PDO as a fallback
	try {
		$dsn = 'mysql:host=' . $host . ';dbname=' . $database . ';charset=utf8mb4';
		$conn = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
	} catch (Exception $e) {
		$db_error = 'PDO connection error: ' . $e->getMessage();
		$conn = null;
	}
} else {
	$db_error = 'No supported database extensions are available. Please enable the mysqli or PDO extension in your PHP configuration.';
}

// Export $db_error and $conn to the including script
?>
