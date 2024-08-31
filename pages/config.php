<?php 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qa_system"; // Name of the database

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>