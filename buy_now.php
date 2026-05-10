<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$name = $_POST['name'] ?? '';
$price = isset($_POST['price']) ? (float)$_POST['price'] : 0;
$image = $_POST['image'] ?? 'https://via.placeholder.com/250x300';
$category = $_POST['category'] ?? 'Product';
$redirect = $_POST['redirect'] ?? 'Shop.php';
$quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;

if ($name == '' || $price <= 0) {
    header('Location: Shop.php');
    exit();
}

$subtotal = $price * $quantity;
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

<main class="cart-page">
    <div class="cart-title-row">
        <div>
            <p class="label-accent">BUY NOW</p>
            <h1>Product Details</h1>
            <p class="cart-subtitle">Review this product before placing your order.</p>
        </div>
        <a href="<?php echo htmlspecialchars($redirect); ?>" class="continue-shopping">Back</a>
    </div>

    <div class="cart-layout">
        <section class="cart-list">
            <div class="cart-item buy-now-item">
                <span></span>
                <img src="<?php echo htmlspecialchars($image); ?>" alt="Product">
                <div class="cart-item-info">
                    <p class="category"><?php echo htmlspecialchars($category); ?></p>
                    <h3><?php echo htmlspecialchars($name); ?></h3>
                    <p class="cart-price">₱<?php echo number_format($price, 2); ?></p>
                    <p>Quantity: <?php echo $quantity; ?></p>
                </div>
                <div class="quantity-box">
                    <span>Qty</span>
                    <strong><?php echo $quantity; ?></strong>
                </div>
                <div class="subtotal-box">
                    <span>Subtotal</span>
                    <strong>₱<?php echo number_format($subtotal, 2); ?></strong>
                </div>
            </div>
        </section>

        <aside class="cart-summary">
            <h2>Order Details</h2>
            <div class="summary-row"><span>Product</span><strong><?php echo htmlspecialchars($name); ?></strong></div>
            <div class="summary-row"><span>Category</span><strong><?php echo htmlspecialchars($category); ?></strong></div>
            <div class="summary-row"><span>Price</span><strong>₱<?php echo number_format($price, 2); ?></strong></div>
            <div class="summary-row"><span>Quantity</span><strong><?php echo $quantity; ?></strong></div>
            <hr>
            <div class="summary-total"><span>Total</span><strong>₱<?php echo number_format($subtotal, 2); ?></strong></div>

            <form action="add_to_cart.php" method="POST">
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                <input type="hidden" name="price" value="<?php echo htmlspecialchars($price); ?>">
                <input type="hidden" name="image" value="<?php echo htmlspecialchars($image); ?>">
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                <input type="hidden" name="redirect" value="Cart.php">
                <button type="submit" class="checkout-btn"><i class="fa-solid fa-cart-plus"></i> Add to Cart</button>
            </form>
        </aside>
    </div>
</main>

</body>
</html>
