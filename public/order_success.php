<?php
require_once __DIR__ . '/../src/functions.php';
session_start();

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$orderId = intval($_GET['id'] ?? 0);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Order Successful - CHES</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .success-box {
      max-width: 500px;
      margin: 4rem auto;
      background: #fff;
      padding: 2rem;
      text-align: center;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .success-box h2 {
      color: #27ae60;
      margin-bottom: 1rem;
    }
    .success-box p {
      margin-bottom: 1.5rem;
      font-size: 1.05rem;
    }
    .success-box .btn {
      display: inline-block;
      background: #d57627;
      color: #fff;
      padding: 0.7rem 1.4rem;
      border-radius: 5px;
      text-decoration: none;
      font-weight: 600;
    }
    .success-box .btn:hover {
      opacity: 0.9;
    }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="success-box">
  <h2>🎉 Order Placed Successfully!</h2>
  <p>Your order has been placed successfully.</p>
  <p>Thank you for shopping with <strong>CHES</strong>.</p>

  <a class="btn" href="index.php">← Return to Home</a>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
