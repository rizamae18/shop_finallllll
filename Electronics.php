<?php
session_start();
include 'includes/db.php';

$products = array(
    array('name'=>'Pro Wireless Headphones','price'=>'199.99','category'=>'electronics','image'=>'https://via.placeholder.com/250x250','badge'=>'TOP PICK','rating'=>'★★★★★','reviews'=>'621'),
    array('name'=>'Smart Watch Series 5','price'=>'149.99','category'=>'electronics','image'=>'https://via.placeholder.com/250x250','badge'=>'SALE','rating'=>'★★★★☆','reviews'=>'309'),
    array('name'=>'Bluetooth Speaker','price'=>'89.99','category'=>'electronics','image'=>'https://via.placeholder.com/250x250','badge'=>'NEW','rating'=>'★★★★☆','reviews'=>'97'),
    array('name'=>'Portable Power Bank','price'=>'49.99','category'=>'electronics','image'=>'https://via.placeholder.com/250x250','badge'=>'HOT','rating'=>'★★★★☆','reviews'=>'143')
);

if (isset($conn)) {
    $db_products = mysqli_query($conn, "SELECT * FROM products WHERE is_active=1 AND category='electronics' ORDER BY created_at DESC");
    if ($db_products) {
        while ($row = mysqli_fetch_assoc($db_products)) {
            $img = 'https://via.placeholder.com/250x250';
            if (!empty($row['upload_image'])) { $img = $row['upload_image']; }
            if (!empty($row['image_url'])) { $img = $row['image_url']; }
            $products[] = array(
                'name'=>$row['name'],
                'price'=>$row['price'],
                'category'=>$row['category'],
                'image'=>$img,
                'badge'=>!empty($row['badge']) ? $row['badge'] : 'NEW',
                'rating'=>'★★★★☆',
                'reviews'=>'0'
            );
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electronics | Lumine</title>
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
                <li><a href="Electronics.php" class="active">Electronics</a></li>
                <li><a href="MyAccount.php">My Account <i class="fa-solid fa-chevron-down"></i></a></li>
            </ul>
            <div class="nav-icons">
                <a href="Cart.php" class="cart-icon" title="View Cart"><i class="fa-solid fa-cart-shopping"></i></a>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </nav>
    </header>

    <header class="category-banner tech-bg">
        <div class="banner-content">
            <div class="category-tag"><i class="fa-solid fa-microchip"></i> CATEGORY</div>
            <h1>Electronics</h1>
            <p>Top-rated gadgets and tech products from verified sellers.</p>
        </div>
    </header>

    <main class="container">
        <section class="clothing-controls">
            <div class="sub-categories">
                <a href="Shop.php" class="pill">All Products</a>
                <a href="Clothing.php?category=mens_fashion" class="pill">Men's Fashion</a>
                <a href="Clothing.php?category=womens_fashion" class="pill">Women's Fashion</a>
                <a href="Electronics.php" class="pill active">Electronics</a>
            </div>
            <p class="results-info">Showing <strong><?php echo count($products); ?></strong> electronics</p>
        </section>

        <section class="product-grid">
            <?php if (count($products) == 0) { ?>
                <p>No electronics products found.</p>
            <?php } ?>

            <?php foreach ($products as $product) { ?>
            <div class="product-card">
                <div class="product-img">
                    <span class="badge top-pick"><?php echo htmlspecialchars($product['badge']); ?></span>
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                <div class="product-info">
                    <p class="label">ELECTRONICS</p>
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <div class="rating"><?php echo $product['rating']; ?> <span>(<?php echo $product['reviews']; ?>)</span></div>
                    <p class="price">$<?php echo htmlspecialchars($product['price']); ?></p>
                    <div class="card-btns">
                        <form action="BuyNow.php" method="POST" class="buy-now-form">
                            <input type="hidden" name="name" value="<?php echo htmlspecialchars($product['name']); ?>">
                            <input type="hidden" name="price" value="<?php echo htmlspecialchars($product['price']); ?>">
                            <input type="hidden" name="image" value="<?php echo htmlspecialchars($product['image']); ?>">
                            <input type="hidden" name="category" value="ELECTRONICS">
                            <input type="hidden" name="redirect" value="Electronics.php">
                            <button type="submit" class="btn-buy">Buy Now</button>
                        </form>
                        <form action="add_to_cart.php" method="POST" class="cart-form">
                            <input type="hidden" name="name" value="<?php echo htmlspecialchars($product['name']); ?>">
                            <input type="hidden" name="price" value="<?php echo htmlspecialchars($product['price']); ?>">
                            <input type="hidden" name="image" value="<?php echo htmlspecialchars($product['image']); ?>">
                            <input type="hidden" name="category" value="ELECTRONICS">
                            <input type="hidden" name="redirect" value="Electronics.php">
                            <button type="submit" class="btn-add">Add</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php } ?>
        </section>
    </main>

</body>
</html>
