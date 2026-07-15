<?php
require 'auth.php';
require 'db.php';

require_login();
cart_init();

$productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
$qty       = filter_input(INPUT_POST, 'qty', FILTER_VALIDATE_INT);
$qty       = ($qty && $qty > 0) ? min($qty, 20) : 1;

if (!$productId) {
    header('Location: products.php');
    exit;
}


$check = mysqli_prepare($connection, "SELECT product_id FROM tbl_products WHERE product_id = ?");
mysqli_stmt_bind_param($check, 'i', $productId);
mysqli_stmt_execute($check);
if (!mysqli_stmt_get_result($check)->fetch_assoc()) {
    header('Location: 404.php');
    exit;
}

if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId] += $qty;
} else {
    $_SESSION['cart'][$productId] = $qty;
}

header('Location: item.php?id=' . $productId . '&added=1');
exit;
