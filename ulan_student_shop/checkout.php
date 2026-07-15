<?php
require 'auth.php';
require 'db.php';
require_login();
cart_init();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}


$pairs = [];
foreach ($_SESSION['cart'] as $productId => $qty) {
    $pairs[] = (int)$productId . ':' . (int)$qty;
}
$productIds = implode(',', $pairs);

$connection = $connection ?? ($conn ?? ($db ?? ($link ?? ($mysqli ?? null))));
if ($connection === null) {
    // DB connection not available
    header('Location: cart.php?ordered=0');
    exit;
}

$stmt = mysqli_prepare($connection, "INSERT INTO tbl_orders (user_id, product_ids) VALUES (?, ?)");
$userId = $_SESSION['user_id'];
mysqli_stmt_bind_param($stmt, 'is', $userId, $productIds);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['cart'] = []; // empty the cart once the order is confirmed
    clear_promo();
    header('Location: cart.php?ordered=1');
} else {
    header('Location: cart.php?ordered=0');
}
exit;
