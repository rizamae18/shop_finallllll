<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

//fetch information from db
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = '$user_id' LIMIT 1";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

//if wala sa db and user
if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'profile';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account | Lumine</title>
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
                <li><a href="MyAccount.php" class="active">My Account <i class="fa-solid fa-chevron-down"></i></a></li>
            </ul>
            <div class="nav-icons"><a href="Cart.php" class="cart-icon" title="View Cart"><i class="fa-solid fa-cart-shopping"></i></a><a href="logout.php" class="logout-btn">Logout</a></div>
        </nav>
    </header>

    <main class="account-container">
        <header class="dashboard-header">
            <p class="label-accent">DASHBOARD</p>
            <h1>My Account</h1>
        </header>

        <div class="dashboard-layout">
            <aside class="profile-sidebar">
                <div class="profile-card">
                    <div class="card-header-bg"></div>
                    <div class="user-avatar">
                        <img src="<?php echo !empty($user['avatar_url']) ? $user['avatar_url'] : 'https://via.placeholder.com/120'; ?>" alt="User">
                    </div>
                    <div class="user-identity">
                        <h3><?php echo htmlspecialchars($user['full_name']); ?></h3>
                        <p class="email"><?php echo htmlspecialchars($user['email']); ?></p>
                        <span class="seller-tag">
                            <i class="fa-solid fa-shop"></i>
                            <?php echo 'Lumine Member'; ?>
                            <small>Seller</small>
                        </span>
                        <p class="bio"><?php echo htmlspecialchars($user['bio'] ?? 'No bio yet.'); ?></p>
                    </div>
                    <div class="user-stats">
                        <div class="stat"><strong><?php echo $user['order_count'] ?? 0; ?></strong><small>Orders</small></div>
                        <div class="stat"><strong><?php echo $user['rating'] ?? '0.0'; ?>★</strong><small>Reviews</small></div>
                        <div class="stat"><strong><?php echo $user['sale_count'] ?? 0; ?></strong><small>Sales</small></div>
                        <div class="stat"><strong>₱<?php echo number_format($user['revenue'] ?? 0); ?></strong><small>Revenue</small></div>
                    </div>
                </div>
            </aside>

            <?php
            // current tab nga active
            $current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'profile';
            ?>

            <section class="account-content">
                <nav class="tab-nav">
                    <a href="MyAccount.php?tab=profile" 
                       class="tab-btn <?= ($current_tab == 'profile') ? 'active' : '' ?>">Profile</a>
           
                <a href="MyAccount.php?tab=orders" 
                    class="tab-btn <?= ($current_tab == 'orders') ? 'active' : '' ?>">My Orders</a>
           
                <a href="MyAccount.php?tab=shop" 
                    class="tab-btn <?= ($current_tab == 'shop') ? 'active' : '' ?>">My Shop</a>
           
                <a href="MyAccount.php?tab=sales" 
                        class="tab-btn <?= ($current_tab == 'sales') ? 'active' : '' ?>">Sales</a>
                </nav>

            <div class="tab-display-area">
                <?php
                // This is where the magic happens!
                switch ($current_tab) {
                case 'orders': include 'account/my_order.php'; break;
                case 'shop':   include 'account/my_shop.php'; break;
                case 'sales':  include 'account/my_sales.php'; break;
                default:       include 'account/my_profile.php'; break;
                }
                ?>
            </div>
            </section>
        </div>
    </main>

    </body>
</html>