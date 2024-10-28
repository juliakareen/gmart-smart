<?php
$servername = "localhost"; // Your server name
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP is empty
$dbname = "online_shop"; // Your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Optional: Set the character set to UTF-8 for proper encoding
mysqli_set_charset($conn, "utf8");
?>

