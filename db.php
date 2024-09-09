<?php
session_start(); // Start the session to store messages

// Database connection details
$host = 'localhost';   // Hostname (usually 'localhost' for local development)
$user = 'root';        // MySQL username
$password = '';        // MySQL password (empty for default root account)
$database = 'crud_apps'; // Database name

// Create connection
$conn = mysqli_connect($host, $user, $password, $database);

// Check if the connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
