<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'student_management';

try {
    $conn = new mysqli($host, $username, $password, $database);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}

define('BASE_URL', 'http://localhost/student_management');
?>