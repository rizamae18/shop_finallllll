<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {
        $error = 'Passwords do not match!';
    } else {
        $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' LIMIT 1");

        if (mysqli_num_rows($check) > 0) {
            $error = 'Email is already registered!';
        } else {
            $id = uniqid();
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (id, full_name, email, password_hash) VALUES ('$id', '$full_name', '$email', '$password_hash')";

            if (mysqli_query($conn, $sql)) {
                $shop_id = uniqid();
                $shop_name = $full_name . "'s Shop";
                $shop_name = mysqli_real_escape_string($conn, $shop_name);

                mysqli_query($conn, "INSERT INTO shops (id, user_id, name) VALUES ('$shop_id', '$id', '$shop_name')");

                header('Location: login.php');
                exit();
            } else {
                $error = 'Something went wrong. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ShopEase - Create Account</title>
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
      max-width: 460px;
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

    .auth-box input[type="text"],
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
      margin-top: 4px;
    }

    .auth-btn:hover {
      background: #7C442A;
      transform: translateY(-1px);
    }

    .terms,
    .login-link {
      text-align: center;
      color: #777;
      font-size: 13px;
      line-height: 1.6;
      margin-top: 18px;
    }

    .auth-box a {
      color: #7C442A;
      text-decoration: none;
      font-weight: 600;
    }

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
    <form class="auth-box" action="signin.php" method="POST">
      <h2>Create Your Account</h2>
      <p class="auth-subtitle">Sign up and start your ShopEase journey.</p>

      <?php if(isset($error)): ?>
        <p class="auth-error"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <label>Full Name</label>
      <input type="text" name="full_name" placeholder="Enter your full name" required />

      <label>Email Address</label>
      <input type="email" name="email" placeholder="Enter your email" required />

      <label>Password</label>
      <input type="password" name="password" placeholder="Enter your password" required />

      <label>Confirm Password</label>
      <input type="password" name="confirm_password" placeholder="Confirm your password" required />

      <button class="auth-btn" type="submit">Register</button>

      <p class="terms">
        By signing up, you agree to our <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>.
      </p>

      <p class="login-link">
        Already have an account? <a href="login.php">Log in ›</a>
      </p>
    </form>
  </main>
</body>
</html>
