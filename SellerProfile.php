<?php
session_start();
include 'includes/db.php';

$shop = mysqli_query($conn, "SELECT * FROM shops ORDER BY created_at DESC LIMIT 1");
$shop_row = mysqli_fetch_assoc($shop);
$shop_id = $shop_row ? $shop_row['id'] : '';
$products = mysqli_query($conn, "SELECT * FROM products WHERE shop_id='$shop_id' ORDER BY created_at DESC");
$product_count = mysqli_num_rows($products);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Profile | Lumine</title>
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
        <a href="logout.php" class="logout-btn">Logout</a>
    </nav>
</header>

<section class="seller-hero">
    <div class="seller-profile-header">
        <div class="store-icon"><i class="fa-solid fa-shop"></i></div>
        <div class="store-details">
            <h1><?php echo $shop_row ? htmlspecialchars($shop_row['name']) : 'Seller Shop'; ?></h1>
            <p class="store-bio"><?php echo $shop_row ? htmlspecialchars($shop_row['bio'] ?? 'Seller products') : 'Seller products'; ?></p>
        </div>
    </div>
</section>

<main class="container">
    <div class="shop-filter-row">
        <div class="text-group">
            <h2>Shop Products</h2>
            <p><?php echo $product_count; ?> item/s listed</p>
        </div>
    </div>

    <section class="product-grid">
        <?php while ($p = mysqli_fetch_assoc($products)) { ?>
            <div class="product-card">
                <div class="product-img">
                    <?php if (!empty($p['badge'])) { ?><span class="badge"><?php echo htmlspecialchars($p['badge']); ?></span><?php } ?>
                    <img src="<?php echo !empty($p['image_url']) ? htmlspecialchars($p['image_url']) : 'https://via.placeholder.com/250x300'; ?>" alt="Product">
                </div>
                <div class="product-info">
                    <p class="label"><?php echo htmlspecialchars($p['category']); ?></p>
                    <h3><?php echo htmlspecialchars($p['name']); ?></h3>
                    <p class="price">₱<?php echo number_format($p['price'], 2); ?></p>
                    <p>Stock: <?php echo htmlspecialchars($p['stock']); ?></p>
                </div>
            </div>
        <?php } ?>
    </section>
</main>
</body>
</html>
