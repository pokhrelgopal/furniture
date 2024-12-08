<?php
// Database configuration
$host = 'localhost'; // Hostname (usually localhost for XAMPP)
$user = 'root';      // Database username
$password = 'root';      // Database password (empty by default for XAMPP)
$dbname = 'furniture'; // Your database name

// Create a connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set the charset to UTF-8 (recommended for internationalization)
$conn->set_charset("utf8");

// Now $conn can be used to interact with the database
