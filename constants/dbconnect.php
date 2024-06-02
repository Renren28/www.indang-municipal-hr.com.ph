<?php
$hostname = 'localhost'; 
$username = 'root'; 
$password = '';
$database = 'hr-indang-municipal';

$database = new mysqli($hostname, $username, $password, $database);

if ($database->connect_error) {
    die("Connection failed: " . $database->connect_error);
} else {
    //echo "Connected successfully";
}
?>