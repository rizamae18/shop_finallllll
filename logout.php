<?php
session_start();

$back = $_SERVER['HTTP_REFERER'] ?? 'Home.php';
$host = $_SERVER['HTTP_HOST'] ?? '';

/* Keep the back link safe inside this website only */
if (!empty($back)) {
    $back_host = parse_url($back, PHP_URL_HOST);
    if ($back_host && $back_host !== $host) {
        $back = 'Home.php';
    }
} else {
    $back = 'Home.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Logout</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f6f6f6;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .logout-box {
            background: white;
            width: 340px;
            padding: 30px;
            border-radius: 14px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .logout-box h2 {
            margin-bottom: 25px;
            font-size: 22px;
        }

        .logout-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .logout-actions a {
            text-decoration: none;
            padding: 10px 24px;
            border-radius: 8px;
            color: white;
            font-weight: bold;
        }

        .yes-btn { background: #d62828; }
        .no-btn { background: #555; }
    </style>
</head>
<body>

<div class="logout-box">
    <h2>Are you sure you want to logout?</h2>

    <div class="logout-actions">
        <a href="logout_confirm.php" class="yes-btn">Yes</a>
        <a href="<?php echo htmlspecialchars($back); ?>" class="no-btn">No</a>
    </div>
</div>

</body>
</html>
