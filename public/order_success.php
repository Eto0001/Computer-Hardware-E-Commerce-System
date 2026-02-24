<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
session_start();

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['data'])) {
    $_SESSION['error'] = "Invalid payment response.";
    header('Location: orders.php');
    exit;
}

// Decode eSewa response
$data = json_decode(base64_decode($_GET['data']), true);

if (!$data || !isset($data['status'])) {
    $_SESSION['error'] = "Invalid payment data.";
    header('Location: orders.php');
    exit;
}

if ($data['status'] === "COMPLETE") {

    // Get transaction UUID from eSewa
    $transaction_uuid = $data['transaction_uuid'];

    // Find order using transaction_uuid — also fetch total for fraud check
    $stmt = $pdo->prepare("SELECT id, total FROM orders WHERE transaction_uuid = ?");
    $stmt->execute([$transaction_uuid]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        $_SESSION['error'] = "Order not found.";
        header('Location: orders.php');
        exit;
    }

    // Verify amount BEFORE making any changes to prevent fraud
    if ($data['total_amount'] != $order['total']) {
        $_SESSION['error'] = "Payment amount mismatch. Please contact support.";
        header('Location: orders.php');
        exit;
    }

    $order_id = $order['id'];

    $pdo->beginTransaction();

    try {
        // Deduct stock
        $stmt_items = $pdo->prepare("SELECT product_id, quantity FROM order_items WHERE order_id=?");
        $stmt_items->execute([$order_id]);
        $items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

        $stmt_stock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id=?");

        foreach ($items as $item) {
            $stmt_stock->execute([$item['quantity'], $item['product_id']]);
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "An error occurred while processing your order.";
        header('Location: orders.php');
        exit;
    }

    unset($_SESSION['cart']);

} else {
    $_SESSION['error'] = "Payment failed.";
    header('Location: orders.php');
    exit;
}
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
