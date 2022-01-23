<?php

const SERVERNAME = "localhost";
const USERNAME = "root";
const PASSWORD = "";
const DB_NAME = "products_crud";

// Create connection
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DB_NAME);

// $servername = "localhost";
// $username = "root";
// $password = "";

// // Create connection
// $conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
