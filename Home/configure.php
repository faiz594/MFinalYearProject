<?php
error_reporting(E_ALL);

//connection credentials
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "fyp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// If connection fails, stop execution
if ($conn->connect_error) {
    die("Connection failed");
}
?>
