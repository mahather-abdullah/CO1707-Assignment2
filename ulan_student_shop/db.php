<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "ulan_student_shop";

$connection = mysqli_connect($host, $user, $pass, $dbname);

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($connection, "utf8mb4");
?>
