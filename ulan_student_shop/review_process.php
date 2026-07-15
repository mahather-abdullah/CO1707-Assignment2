<?php
require 'auth.php';
require 'db.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: products.php');
    exit;
}

$productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
$title     = trim($_POST['review_title'] ?? '');
$desc      = trim($_POST['review_desc'] ?? '');
$rating    = filter_input(INPUT_POST, 'review_rating', FILTER_VALIDATE_INT);


if (!$productId || $title === '' || $desc === '' || $rating < 1 || $rating > 5) {
    header('Location: item.php?id=' . (int)$productId . '&error=review');
    exit;
}


$check = mysqli_prepare($connection, "SELECT product_id FROM tbl_products WHERE product_id = ?");
mysqli_stmt_bind_param($check, 'i', $productId);
mysqli_stmt_execute($check);
if (!mysqli_stmt_get_result($check)->fetch_assoc()) {
    header('Location: 404.php');
    exit;
}

$stmt = mysqli_prepare($connection,
    "INSERT INTO tbl_reviews (user_id, product_id, review_title, review_desc, review_rating)
     VALUES (?, ?, ?, ?, ?)");
$userId = $_SESSION['user_id'];
mysqli_stmt_bind_param($stmt, 'iissi', $userId, $productId, $title, $desc, $rating);
mysqli_stmt_execute($stmt);

header('Location: item.php?id=' . $productId . '#reviews');
exit;
