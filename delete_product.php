<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_GET['id'] ?? ($_POST['id'] ?? '');

if ($product_id == '') {
    header("Location: MyAccount.php?tab=shop");
    exit();
}

/* Get the logged-in user's shop */
$stmt = mysqli_prepare($conn, "SELECT id FROM shops WHERE user_id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $user_id);
mysqli_stmt_execute($stmt);
$shop_result = mysqli_stmt_get_result($stmt);
$shop = mysqli_fetch_assoc($shop_result);

if (!$shop) {
    header("Location: MyAccount.php?tab=shop");
    exit();
}

$shop_id = $shop['id'];

/* Make sure the product really belongs to this user's shop */
$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id = ? AND shop_id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "ss", $product_id, $shop_id);
mysqli_stmt_execute($stmt);
$product_result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($product_result);

if (!$product) {
    header("Location: MyAccount.php?tab=shop");
    exit();
}

/* If the user clicked YES, delete the product */
if (isset($_POST['delete_yes'])) {
    $stmt = mysqli_prepare($conn, "DELETE FROM product_images WHERE product_id = ?");
    mysqli_stmt_bind_param($stmt, "s", $product_id);
    mysqli_stmt_execute($stmt);

    $stmt = mysqli_prepare($conn, "DELETE FROM cart_items WHERE product_id = ?");
    mysqli_stmt_bind_param($stmt, "s", $product_id);
    mysqli_stmt_execute($stmt);

    $stmt = mysqli_prepare($conn, "DELETE FROM products WHERE id = ? AND shop_id = ?");
    mysqli_stmt_bind_param($stmt, "ss", $product_id, $shop_id);
    mysqli_stmt_execute($stmt);

    header("Location: MyAccount.php?tab=shop&msg=deleted");
    exit();
}

/* If the user clicked NO, go back without deleting */
if (isset($_POST['delete_no'])) {
    header("Location: MyAccount.php?tab=shop");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Delete</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #faf7f5;
            color: #222;
        }
        .confirm-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .confirm-box {
            width: 100%;
            max-width: 430px;
            background: white;
            border-radius: 18px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        .confirm-box h2 {
            margin-top: 0;
            color: #5b2414;
        }
        .product-name {
            margin: 15px 0;
            font-weight: bold;
        }
        .confirm-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 25px;
        }
        .confirm-actions button {
            border: none;
            cursor: pointer;
            padding: 12px 28px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 15px;
        }
        .yes-btn {
            background: #5b2414;
            color: white;
        }
        .no-btn {
            background: #e8e0dc;
            color: #5b2414;
        }
    </style>
</head>
<body>

<div class="confirm-page">
    <div class="confirm-box">
        <h2>Are you sure you want to delete this product?</h2>
        <p class="product-name"><?php echo htmlspecialchars($product['name']); ?></p>
        <p>Click Yes to delete. Click No to cancel.</p>

        <form method="POST" action="delete_product.php" class="confirm-actions">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($product_id); ?>">
            <button type="submit" name="delete_yes" class="yes-btn">Yes</button>
            <button type="submit" name="delete_no" class="no-btn">No</button>
        </form>
    </div>
</div>

</body>
</html>
