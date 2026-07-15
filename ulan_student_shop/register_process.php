<?php
require 'auth.php';
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

$name     = trim($_POST['fullname'] ?? '');
$address  = trim($_POST['address'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($name === '' || $address === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: register.php?error=fields');
    exit;
}

if (strlen($password) < 8) {
    header('Location: register.php?error=password');
    exit;
}

$stmt = mysqli_prepare($connection, "SELECT user_id FROM tbl_users WHERE user_email = ?");
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
if (mysqli_stmt_get_result($stmt)->fetch_assoc()) {
    header('Location: register.php?error=exists');
    exit;
}


$hashed = password_hash($password, PASSWORD_BCRYPT);

$stmt = mysqli_prepare($connection,
    "INSERT INTO tbl_users (user_full_name, user_address, user_email, user_pass) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'ssss', $name, $address, $email, $hashed);
mysqli_stmt_execute($stmt);

$newId = mysqli_insert_id($connection);
session_regenerate_id(true);
$_SESSION['user_id']   = $newId;
$_SESSION['user_name'] = $name;

header('Location: index.php');
exit;
