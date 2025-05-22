<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'includes/session.php';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

echo "Database connected successfully!";
?>
