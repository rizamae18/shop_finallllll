<?php
session_start();
include 'includes/db.php';

$selected_category = isset($_GET['category']) ? $_GET['category'] : 'all';

$products = array(
    array('name'=>'Classic Navy Blazer','price'=>'129.99','category'=>'mens_fashion','image'=>'https://via.placeholder.com/250x300','badge'=>'BEST SELLER','rating'=>'★★★★☆','reviews'=>'312'),
    array('name'=>'Men Casual Shirt','price'=>'39.99','category'=>'mens_fashion','image'=>'https://via.placeholder.com/250x300','badge'=>'NEW','rating'=>'★★★★☆','reviews'=>'154'),
    array('name'=>'Elegant Summer Dress','price'=>'59.99','category'=>'womens_fashion','image'=>'https://via.placeholder.com/250x300','badge'=>'TRENDING','rating'=>'★★★★★','reviews'=>'221'),
    array('name'=>'Women Leather Handbag','price'=>'79.99','category'=>'womens_fashion','image'=>'https://via.placeholder.com/250x300','badge'=>'HOT','rating'=>'★★★★☆','reviews'=>'188'),
    array('name'=>'Pro Wireless Headphones','price'=>'199.99','category'=>'electronics','image'=>'https://via.placeholder.com/250x250','badge'=>'TOP PICK','rating'=>'★★★★★','reviews'=>'621'),
    array('name'=>'Smart Watch Series 5','price'=>'149.99','category'=>'electronics','image'=>'https://via.placeholder.com/250x250','badge'=>'SALE','rating'=>'★★★★☆','reviews'=>'309')
);

if (isset($conn)) {
    $db_products = mysqli_query($conn, "SELECT * FROM products WHERE is_active=1 ORDER BY created_at DESC");
    if ($db_products) {
        while ($row = mysqli_fetch_assoc($db_products)) {
            $img = 'https://via.placeholder.com/250x300';
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

function categoryTitle($category) {
    if ($category == 'mens_fashion') return "Men's Fashion";
    if ($category == 'womens_fashion') return "Women's Fashion";
    if ($category == 'electronics') return 'Electronics';
    return 'All Products';
}

$shown_products = array();
foreach ($products as $product) {
    if ($selected_category == 'all' || $product['category'] == $selected_category) {
        $shown_products[] = $product;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop | Lumine</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <header>
        <nav class="navbar">
            <div class="logo">Lumine</div>
            <ul class="nav-links">
                <li><a href="Home.php">Home</a></li>
                <li><a href="Shop.php" class="active">Shop</a></li>
                <li><a href="Clothing.php">Clothing</a></li>
                <li><a href="Electronics.php">Electronics</a></li>
                <li><a href="MyAccount.php">My Account <i class="fa-solid fa-chevron-down"></i></a></li>
            </ul>
            <div class="nav-icons">
                <a href="Cart.php" class="cart-icon" title="View Cart"><i class="fa-solid fa-cart-shopping"></i></a>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </nav>
    </header>

    <main class="shop-container">
        <header class="shop-header">
            <p class="breadcrumb"><?php echo strtoupper(categoryTitle($selected_category)); ?></p>
            <h1><?php echo categoryTitle($selected_category); ?></h1>
        </header>

        <section class="controls-section">
            <div class="category-filters">
                <a href="Shop.php" class="filter-btn <?php if($selected_category=='all') echo 'active'; ?>">All</a>
                <a href="Shop.php?category=mens_fashion" class="filter-btn <?php if($selected_category=='mens_fashion') echo 'active'; ?>">Men's Fashion</a>
                <a href="Shop.php?category=womens_fashion" class="filter-btn <?php if($selected_category=='womens_fashion') echo 'active'; ?>">Women's Fashion</a>
                <a href="Shop.php?category=electronics" class="filter-btn <?php if($selected_category=='electronics') echo 'active'; ?>">Electronics</a>
            </div>

            <p class="results-count"><strong><?php echo count($shown_products); ?></strong> products found</p>
        </section>

        <section class="product-grid">
            <?php foreach ($shown_products as $product) { ?>
            <div class="product-card">
                <div class="product-img">
                    <span class="badge"><?php echo htmlspecialchars($product['badge']); ?></span>
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                <div class="product-info">
                    <p class="category"><?php echo strtoupper(categoryTitle($product['category'])); ?></p>
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <div class="rating"><?php echo $product['rating']; ?> <span>(<?php echo $product['reviews']; ?>)</span></div>
                    <p class="price">$<?php echo htmlspecialchars($product['price']); ?></p>
                    <div class="card-btns">
                        <form action="BuyNow.php" method="POST" class="buy-now-form">
                            <input type="hidden" name="name" value="<?php echo htmlspecialchars($product['name']); ?>">
                            <input type="hidden" name="price" value="<?php echo htmlspecialchars($product['price']); ?>">
                            <input type="hidden" name="image" value="<?php echo htmlspecialchars($product['image']); ?>">
                            <input type="hidden" name="category" value="<?php echo strtoupper(categoryTitle($product['category'])); ?>">
                            <input type="hidden" name="redirect" value="Shop.php?category=<?php echo urlencode($selected_category); ?>">
                            <button type="submit" class="buy-now">Buy Now</button>
                        </form>
                        <form action="add_to_cart.php" method="POST" class="cart-form">
                            <input type="hidden" name="name" value="<?php echo htmlspecialchars($product['name']); ?>">
                            <input type="hidden" name="price" value="<?php echo htmlspecialchars($product['price']); ?>">
                            <input type="hidden" name="image" value="<?php echo htmlspecialchars($product['image']); ?>">
                            <input type="hidden" name="category" value="<?php echo strtoupper(categoryTitle($product['category'])); ?>">
                            <input type="hidden" name="redirect" value="Shop.php?category=<?php echo $selected_category; ?>">
                            <button type="submit" class="add-cart"><i class="fa-solid fa-cart-plus"></i> Add</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php } ?>
        </section>
    </main>

</body>
</html>
