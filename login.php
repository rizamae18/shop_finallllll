<?php
session_start();
include 'includes/db.php';

if (isset($_COOKIE['remember_email']) && !isset($_SESSION['user_id'])) {
    $_SESSION['remember_email'] = $_COOKIE['remember_email'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];

        $shop = mysqli_query($conn, "SELECT * FROM shops WHERE user_id='".$user['id']."' LIMIT 1");
        $shop_row = mysqli_fetch_assoc($shop);
        if ($shop_row) {
            $_SESSION['shop_id'] = $shop_row['id'];
        }

        if (isset($_POST['remember'])) {
            setcookie('remember_email', $email, time() + (86400 * 30), '/');
        }

        header('Location: Home.php');
        exit();
    } else {
        $error = 'Invalid email or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ShopEase - Login</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .auth-page {
      min-height: 100vh;
      background: #F9F7F5;
      color: #333;
    }

    .auth-container {
      min-height: calc(100vh - 72px);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 50px 8%;
      background: linear-gradient(135deg, #F9F7F5 0%, #F4ECE6 100%);
    }

    .auth-box {
      width: 100%;
      max-width: 430px;
      background: #fff;
      padding: 38px;
      border-radius: 18px;
      border: 1px solid #eee;
      box-shadow: 0 20px 45px rgba(93, 42, 24, 0.12);
    }

    .auth-box h2 {
      color: #4B2416;
      font-family: Georgia, serif;
      font-size: 32px;
      text-align: center;
      margin-bottom: 8px;
    }

    .auth-subtitle {
      text-align: center;
      color: #888;
      font-size: 14px;
      margin-bottom: 28px;
    }

    .auth-box label {
      display: block;
      font-size: 12px;
      font-weight: bold;
      color: #555;
      margin-bottom: 8px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .auth-box input[type="email"],
    .auth-box input[type="password"] {
      width: 100%;
      padding: 13px 14px;
      margin-bottom: 18px;
      border: 1px solid #ddd;
      border-radius: 10px;
      background: #fff;
      font-size: 14px;
      outline: none;
    }

    .auth-box input:focus {
      border-color: #A67558;
      box-shadow: 0 0 0 3px rgba(166, 117, 88, 0.15);
    }

    .remember {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 22px;
    }

    .remember label {
      margin: 0;
      text-transform: none;
      font-size: 13px;
      color: #666;
      font-weight: 500;
    }

    .auth-btn {
      width: 100%;
      border: none;
      border-radius: 10px;
      padding: 13px;
      background: #4B2416;
      color: #fff;
      font-weight: bold;
      cursor: pointer;
      transition: 0.2s ease;
    }

    .auth-btn:hover {
      background: #7C442A;
      transform: translateY(-1px);
    }

    .auth-links {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
      font-size: 14px;
    }

    .auth-links a,
    .auth-box a {
      color: #7C442A;
      text-decoration: none;
      font-weight: 600;
    }

    .auth-links a:hover,
    .auth-box a:hover {
      color: #4B2416;
      text-decoration: underline;
    }

    .auth-error {
      background: #FFF3F0;
      color: #C0392B;
      border: 1px solid #F3C5BC;
      border-radius: 10px;
      padding: 10px 12px;
      font-size: 13px;
      margin-bottom: 18px;
      text-align: center;
    }

    @media (max-width: 700px) {
      .navbar {
        flex-direction: column;
        gap: 15px;
      }

      .nav-links {
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
      }

      .auth-box {
        padding: 28px 22px;
      }
    }
  </style>
</head>
<body class="auth-page">
  <header class="navbar">
    <div class="logo">ShopEase</div>
  </header>

  <main class="auth-container">
    <form class="auth-box" action="login.php" method="POST">
      <h2>Welcome Back!</h2>
      <p class="auth-subtitle">Log in to continue shopping with ShopEase.</p>

      <?php if(isset($error)): ?>
        <p class="auth-error"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <label>Email Address</label>
      <input type="email" name="email" placeholder="Enter your email" value="<?php echo isset($_COOKIE['remember_email']) ? htmlspecialchars($_COOKIE['remember_email']) : ''; ?>" required />

      <label>Password</label>
      <input type="password" name="password" placeholder="Enter your password" required />

      <div class="remember">
        <input type="checkbox" id="remember" name="remember" />
        <label for="remember">Remember Me</label>
      </div>

      <button class="auth-btn" type="submit">Log in</button>

      <div class="auth-links">
        <a href="#">Forgot Password?</a>
        <a href="signin.php">Sign Up ›</a>
      </div>
    </form>
  </main>
</body>
</html>
