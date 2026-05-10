<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart | Lumine</title>
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
            <a href="Cart.php" class="cart-icon active" title="View Cart"><i class="fa-solid fa-cart-shopping"></i></a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>
</header>

<main class="cart-page">
    <div class="cart-title-row">
        <div>
            <p class="label-accent">MY CART</p>
            <h1>All Orders</h1>
            <p class="cart-subtitle">The total is calculated using PHP only.</p>
        </div>
        <a href="Shop.php" class="continue-shopping">Continue Shopping</a>
    </div>

    <?php if (empty($cart)): ?>
        <div class="empty-cart">
            <i class="fa-solid fa-cart-shopping"></i>
            <h2>Your cart is empty</h2>
            <p>Click Add on a product to see it here.</p>
            <a href="Shop.php" class="btn-primary cart-shop-btn">Shop Now</a>
        </div>
    <?php else: ?>
        <div class="cart-layout">
            <form action="BuyNow.php" method="POST" class="cart-list" id="cartCheckoutForm">
                <?php foreach ($cart as $key => $item): ?>
                    <?php
                        $quantity = (int)($item['quantity'] ?? 1);
                        if ($quantity < 1) { $quantity = 1; }
                        $subtotal = $item['price'] * $quantity;
                    ?>
                    <div class="cart-item">
                        <input type="checkbox" name="cart_keys[]" value="<?php echo htmlspecialchars($key); ?>" class="cart-check" checked>
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Product">
                        <div class="cart-item-info">
                            <p class="category"><?php echo htmlspecialchars($item['category']); ?></p>
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p class="cart-price">₱<?php echo number_format($item['price'], 2); ?></p>
                        </div>
                        <div class="quantity-box">
                            <span>Qty</span>
                            <div class="quantity-control">
                                <button type="submit" name="minus" value="<?php echo htmlspecialchars($key); ?>" formaction="update_cart_quantity.php" formmethod="POST" class="qty-btn">−</button>
                                <strong><?php echo $quantity; ?></strong>
                                <button type="submit" name="plus" value="<?php echo htmlspecialchars($key); ?>" formaction="update_cart_quantity.php" formmethod="POST" class="qty-btn">+</button>
                            </div>
                        </div>
                        <div class="subtotal-box">
                            <span>Subtotal</span>
                            <strong>₱<?php echo number_format($subtotal, 2); ?></strong>
                        </div>
                        <a href="remove_from_cart.php?key=<?php echo urlencode($key); ?>" class="remove-item">Remove</a>
                    </div>
                <?php endforeach; ?>
            </form>

            <aside class="cart-summary">
                <h2>Order Summary</h2>
                <div class="summary-row"><span>Merchandise Total</span><strong>₱<?php echo number_format($total, 2); ?></strong></div>
                <div class="summary-row"><span>Shipping</span><strong>To be arranged</strong></div>
                <hr>
                <div class="summary-total"><span>Total</span><strong>₱<?php echo number_format($total, 2); ?></strong></div>
                <button type="submit" form="cartCheckoutForm" class="checkout-btn">Checkout</button>
            </aside>
        </div>
    <?php endif; ?>
</main>



</body>
</html>
