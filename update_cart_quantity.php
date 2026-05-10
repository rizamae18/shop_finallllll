<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$key = '';
$change = 0;

if (isset($_POST['plus'])) {
    $key = $_POST['plus'];
    $change = 1;
} elseif (isset($_POST['minus'])) {
    $key = $_POST['minus'];
    $change = -1;
}

if ($key !== '' && isset($_SESSION['cart'][$key])) {
    $currentQty = (int)($_SESSION['cart'][$key]['quantity'] ?? 1);
    $newQty = $currentQty + $change;

    if ($newQty <= 0) {
        unset($_SESSION['cart'][$key]);
    } else {
        $_SESSION['cart'][$key]['quantity'] = $newQty;
    }
}

header('Location: Cart.php');
exit();
?>
