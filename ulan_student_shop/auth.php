<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

function h(mixed $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}


function require_login(): void {
    if (!is_logged_in()) {
        $next = basename($_SERVER['PHP_SELF']);
        if (!empty($_SERVER['QUERY_STRING'])) {
            $next .= '?' . $_SERVER['QUERY_STRING'];
        }
        header("Location: login.php?next=" . urlencode($next));
        exit;
    }
}


function cart_init(): void {
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

function cart_count(): int {
    cart_init();
    $total = 0;
    foreach ($_SESSION['cart'] as $qty) {
        $total += (int)$qty;
    }
    return $total;
}


function valid_promo_codes(): array {
    return [
        'GRAD25' => 25,
    ];
}

function clear_promo(): void {
    unset($_SESSION['promo_code'], $_SESSION['promo_error']);
}
