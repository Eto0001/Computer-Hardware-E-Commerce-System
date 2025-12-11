<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
session_start();

if (!isLoggedIn()) {
    $_SESSION['after_login_redirect'] = 'checkout.php';
    header('Location: login.php');
    exit;
}

if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

$total = 0.0;
$ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$rows = $stmt->fetchAll();
foreach ($rows as $r) {
    $qty = $_SESSION['cart'][$r['id']];
    $total += $r['price'] * $qty;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = sanitize($_POST['address'] ?? '');
    $method = sanitize($_POST['payment_method'] ?? 'COD');

    $_SESSION['checkout'] = ['address' => $address, 'payment_method' => $method];
    header('Location: payment.php');
    exit;
}

include 'header.php';
?>
<h2>Checkout</h2>
<form method="post" class="form">
  <label>Shipping Address <textarea name="address" required><?php echo htmlspecialchars($_SESSION['checkout']['address'] ?? $_SESSION['user_name'] ?? ''); ?></textarea></label>
  <label>Payment Method
    <select name="payment_method">
      <option value="COD">Cash On Delivery (COD)</option>
      <option value="MockPay">Mock Payment</option>
    </select>
  </label>
  <p>Total Amount: <strong>Rs. <?php echo number_format($total,2); ?></strong></p>
  <button type="submit">Proceed to Payment</button>
</form>
<?php include 'footer.php'; ?>