<?php
// ches/public/payment.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
session_start();

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}
if (empty($_SESSION['cart']) || empty($_SESSION['checkout'])) {
    header('Location: cart.php');
    exit;
}

$checkout = $_SESSION['checkout'];
$address = $checkout['address'];
$payment_method = $checkout['payment_method'];

$items = []; $total = 0.0;
$ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT id, name, price, stock FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$rows = $stmt->fetchAll();
foreach ($rows as $r) {
    $qty = $_SESSION['cart'][$r['id']];
    $items[] = ['product'=>$r,'qty'=>$qty];
    $total += $r['price'] * $qty;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();
        $ins = $pdo->prepare("INSERT INTO orders (user_id, total_amount, payment_method, shipping_address) VALUES (?, ?, ?, ?)");
        $ins->execute([$_SESSION['user_id'], $total, $payment_method, $address]);
        $orderId = $pdo->lastInsertId();

        $insItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_order) VALUES (?, ?, ?, ?)");
        foreach ($items as $it) {
            $insItem->execute([$orderId, $it['product']['id'], $it['qty'], $it['product']['price']]);
        }

        $pdo->commit();
        unset($_SESSION['cart']);
        unset($_SESSION['checkout']);
        $_SESSION['order_success'] = "Order #$orderId placed successfully.";
        header('Location: index.php');
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Could not place order: " . $e->getMessage();
    }
}

include 'header.php';
?>
<h2>Payment (Mock)</h2>
<?php if(!empty($error)) echo "<p class='error'>".htmlspecialchars($error)."</p>"; ?>
<p>Total: <strong>Rs. <?php echo number_format($total,2); ?></strong></p>

<?php if ($payment_method === 'MockPay'): ?>
  <p>This is a mock payment gateway — enter any card details to simulate a payment.</p>
  <form method="post" class="form">
    <label>Card Number <input name="card" required value="4242424242424242"></label>
    <label>Expiry <input name="exp" required value="12/34"></label>
    <label>CVV <input name="cvv" required value="123"></label>
    <button type="submit">Pay Now (Mock)</button>
  </form>
<?php else: ?>
  <p>You chose Cash On Delivery (COD). Click Confirm to place your order.</p>
  <form method="post">
    <button type="submit">Confirm Order (COD)</button>
  </form>
<?php endif; ?>

<?php include 'footer.php'; ?>
