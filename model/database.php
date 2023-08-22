<?php
$servername = "localhost";
$dbname = "assignment_tracker";
$username = 'root';
$password = "Apple@5044";

try {
    $dsn = "mysql:host=$servername;dbname=$dbname";
    $db = new PDO($dsn, $username);

    // Set PDO error mode to exception
    // $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // echo "Connected successfully to the database!";
} catch (PDOException $e) {
    $error = "Databse Error: ";
    $error .= $e->getMessage();
    include('view/error.php');
    exit();
}
