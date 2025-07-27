<?php
$servername = "localhost"; 
$username = "root";            
$password = "";        
$database = "sale_system";      

// Unda connection
$conn = new mysqli($servername, $username, $password, $database);

// Kagua connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
