<?php
require 'auth.php';
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$next  = $_POST['next'] ?? 'index.php';

$stmt = mysqli_prepare($connection, "SELECT * FROM tbl_users WHERE user_email = ?");
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
$user = mysqli_stmt_get_result($stmt)->fetch_assoc();


if ($user && password_verify($password, $user['user_pass'])) {
    session_regenerate_id(true);
    $_SESSION['user_id']   = $user['user_id'];
    $_SESSION['user_name'] = $user['user_full_name'];

    // Only allow redirecting back to a local page, never an external URL.
    if (!preg_match('#^[a-zA-Z0-9_\-./?=&%]+$#', $next) || str_starts_with($next, '//')) {
        $next = 'index.php';
    }
    header('Location: ' . $next);
    exit;
}

header('Location: login.php?error=1');
exit;
