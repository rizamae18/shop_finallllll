<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$name = $_POST['name'] ?? 'Product';
$price = $_POST['price'] ?? '0';
$image = $_POST['image'] ?? 'https://via.placeholder.com/250x300';
$category = $_POST['category'] ?? 'Product';
$redirect = $_POST['redirect'] ?? 'Cart.php';
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
if ($quantity < 1) { $quantity = 1; }

$key = md5($name . $price);

if (isset($_SESSION['cart'][$key])) {
    $_SESSION['cart'][$key]['quantity'] += $quantity;
} else {
    $_SESSION['cart'][$key] = [
        'name' => $name,
        'price' => (float)$price,
        'image' => $image,
        'category' => $category,
        'quantity' => $quantity
    ];
}

$separator = (strpos($redirect, '?') !== false) ? '&' : '?';
header('Location: ' . $redirect . $separator . 'added=1');
exit();
?>
