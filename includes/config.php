<?php
session_start();

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "hair_booking";

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die(" Connection failed: " . mysqli_connect_error());
}

// Set timezone
date_default_timezone_set('Africa/Accra');
?>