<?php
require 'auth.php';
require 'db.php';
require_login();
cart_init();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cart.php');
    exit;
}

$action    = $_POST['action'] ?? '';
$productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);

if ($action === 'apply_promo') {
    $code = strtoupper(trim($_POST['promo_code'] ?? ''));
    $validCodes = valid_promo_codes();
    if ($code === '') {
        $_SESSION['promo_error'] = 'Please enter a promo code.';
        unset($_SESSION['promo_code']);
    } elseif (isset($validCodes[$code])) {
        $_SESSION['promo_code'] = $code;
        unset($_SESSION['promo_error']);
    } else {
        $_SESSION['promo_error'] = 'Sorry, that promo code isn\'t valid.';
        unset($_SESSION['promo_code']);
    }
    header('Location: cart.php');
    exit;
}

if ($action === 'remove_promo') {
    clear_promo();
    header('Location: cart.php');
    exit;
}

if ($productId && isset($_SESSION['cart'][$productId])) {
    switch ($action) {
        case 'increase':
            if ($_SESSION['cart'][$productId] < 20) $_SESSION['cart'][$productId]++;
            break;
        case 'decrease':
            $_SESSION['cart'][$productId]--;
            if ($_SESSION['cart'][$productId] <= 0) unset($_SESSION['cart'][$productId]);
            break;
        case 'remove':
            unset($_SESSION['cart'][$productId]);
            break;
        case 'empty':
            $_SESSION['cart'] = [];
            clear_promo();
            break;
    }
} elseif ($action === 'empty') {
    $_SESSION['cart'] = [];
    clear_promo();
}

header('Location: cart.php');
exit;
