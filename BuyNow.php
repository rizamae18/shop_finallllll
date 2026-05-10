<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_SESSION['orders'])) {
    $_SESSION['orders'] = [];
}
if (!isset($_SESSION['orders'][$user_id])) {
    $_SESSION['orders'][$user_id] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'place_order') {
    $pending = $_SESSION['pending_order'][$user_id] ?? null;

    if ($pending && !empty($pending['items'])) {
        $order = [
            'order_no' => 'ORD-' . date('YmdHis') . '-' . rand(100, 999),
            'date' => date('M d, Y h:i A'),
            'items' => $pending['items'],
            'total' => $pending['total'],
            'status' => 'To Pay'
        ];

        $_SESSION['orders'][$user_id][] = $order;

        if (!empty($pending['cart_keys'])) {
            foreach ($pending['cart_keys'] as $cart_key) {
                if (isset($_SESSION['cart'][$cart_key])) {
                    unset($_SESSION['cart'][$cart_key]);
                }
            }
        }

        unset($_SESSION['pending_order'][$user_id]);
        header('Location: MyAccount.php?tab=orders&ordered=1');
        exit();
    }

    header('Location: Shop.php');
    exit();
}

$items = [];
$cart_keys = [];
$redirect = $_POST['redirect'] ?? $_GET['redirect'] ?? 'Shop.php';

if (isset($_POST['cart_keys']) && is_array($_POST['cart_keys'])) {
    $cart = $_SESSION['cart'] ?? [];

    foreach ($_POST['cart_keys'] as $cart_key) {
        if (isset($cart[$cart_key])) {
            $cart_item = $cart[$cart_key];
            $quantity = (int)($cart_item['quantity'] ?? 1);
            if ($quantity < 1) { $quantity = 1; }

            $items[] = [
                'name' => $cart_item['name'] ?? 'Product',
                'price' => (float)($cart_item['price'] ?? 0),
                'image' => $cart_item['image'] ?? 'https://via.placeholder.com/250x300',
                'category' => $cart_item['category'] ?? 'Product',
                'quantity' => $quantity
            ];
            $cart_keys[] = $cart_key;
        }
    }

    $redirect = 'Cart.php';
} else {
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : (int)($_GET['quantity'] ?? 1);
    if ($quantity < 1) { $quantity = 1; }

    $items[] = [
        'name' => $_POST['name'] ?? $_GET['name'] ?? 'Product',
        'price' => (float)($_POST['price'] ?? $_GET['price'] ?? 0),
        'image' => $_POST['image'] ?? $_GET['image'] ?? 'https://via.placeholder.com/250x300',
        'category' => $_POST['category'] ?? $_GET['category'] ?? 'Product',
        'quantity' => $quantity
    ];
}

if (empty($items)) {
    header('Location: Cart.php');
    exit();
}

$total = 0;
foreach ($items as $item) {
    $total += $item['price'] * $item['quantity'];
}

if (!isset($_SESSION['pending_order'])) {
    $_SESSION['pending_order'] = [];
}
$_SESSION['pending_order'][$user_id] = [
    'items' => $items,
    'total' => $total,
    'cart_keys' => $cart_keys
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Now | Lumine</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<header>
    <nav class="navbar">
        <div class="logo">Lumine</div>
        <ul class="nav-links">
            <li><a href="Home.php">Home</a></li>
            <li><a href="Shop.php">Shop</a></li>
            <li><a href="Clothing.php">Clothing</a></li>
            <li><a href="Electronics.php">Electronics</a></li>
            <li><a href="MyAccount.php">My Account</a></li>
        </ul>
        <div class="nav-icons">
            <a href="Cart.php" class="cart-icon" title="View Cart"><i class="fa-solid fa-cart-shopping"></i></a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>
</header>

<main class="buy-page">
    <div class="buy-title-row">
        <div>
            <p class="label-accent">BUY NOW</p>
            <h1>Product Details</h1>
            <p class="cart-subtitle">Check the product details before placing your order.</p>
        </div>
        <a href="<?php echo htmlspecialchars($redirect); ?>" class="continue-shopping">Back</a>
    </div>

    <section class="buy-layout">
        <div>
            <?php foreach ($items as $item): ?>
                <?php $subtotal = $item['price'] * $item['quantity']; ?>
                <div class="buy-product-card" style="margin-bottom:18px;">
                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <div class="buy-product-info">
                        <p class="category"><?php echo htmlspecialchars($item['category']); ?></p>
                        <h2><?php echo htmlspecialchars($item['name']); ?></h2>
                        <p class="cart-price">₱<?php echo number_format($item['price'], 2); ?></p>
                        <p class="buy-detail-text">Quantity: <strong><?php echo $item['quantity']; ?></strong></p>
                        <p class="buy-detail-text">Subtotal: <strong>₱<?php echo number_format($subtotal, 2); ?></strong></p>
                        <p class="buy-detail-text">Shipping: <strong>To be arranged</strong></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <aside class="cart-summary">
            <h2>Order Details</h2>
            <div class="summary-row"><span>Selected Items</span><strong><?php echo count($items); ?></strong></div>
            <div class="summary-row"><span>Shipping</span><strong>To be arranged</strong></div>
            <hr>
            <div class="summary-total"><span>Total</span><strong>₱<?php echo number_format($total, 2); ?></strong></div>

            <?php if (empty($cart_keys) && count($items) === 1): ?>
                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="name" value="<?php echo htmlspecialchars($items[0]['name']); ?>">
                    <input type="hidden" name="price" value="<?php echo htmlspecialchars($items[0]['price']); ?>">
                    <input type="hidden" name="image" value="<?php echo htmlspecialchars($items[0]['image']); ?>">
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($items[0]['category']); ?>">
                    <input type="hidden" name="quantity" value="<?php echo $items[0]['quantity']; ?>">
                    <input type="hidden" name="redirect" value="Cart.php">
                    <button type="submit" class="checkout-btn"><i class="fa-solid fa-cart-plus"></i> Add to Cart</button>
                </form>
            <?php endif; ?>

            <form action="BuyNow.php" method="POST">
                <input type="hidden" name="action" value="place_order">
                <button type="submit" class="checkout-btn buy-confirm-btn">Buy Now</button>
            </form>
        </aside>
    </section>
</main>

</body>
</html>
