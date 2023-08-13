<?php
$con = 'mysql:host=localhost;dbname=assignment_tracker';
$username = 'root';
$password = "Apple@5044";
//pdo = php data object
try {
    $db = new PDO($con, $username);
} catch (PDOException $e) {
    $error = "Databse Error: ";
    $error .= $e->getMessage();
    include('view/error.php');
    exit();
}
