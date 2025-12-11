<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
session_start();

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    foreach ($_POST['qty'] as $pid => $q) {
        $pid = intval($pid); $q = max(0, intval($q));
        if ($q === 0) {
            unset($_SESSION['cart'][$pid]);
        } else {
            $_SESSION['cart'][$pid] = $q;
        }
    }
    header('Location: cart.php');
    exit;
}   

$items = [];
$total = 0.0;
if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $rows = $stmt->fetchAll();
    foreach ($rows as $r) {
        $qty = $_SESSION['cart'][$r['id']] ?? 0;
        $subtotal = $r['price'] * $qty;
        $items[] = ['product'=>$r,'qty'=>$qty,'subtotal'=>$subtotal];
        $total += $subtotal;
    }
}
include 'header.php';
?>
<link rel="stylesheet" href="../css/table.css">
<link rel="stylesheet" href="../css/cart.css">
<h2>Your Cart</h2>
<?php if(empty($items)): ?>
  <p>Your cart is empty. <a href="index.php">Continue shopping</a></p>
<?php else: ?>
  <form method="post">
  <table class="cart-table">
    <tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>
    <?php foreach($items as $it): ?>
    <tr>
      <td><?php echo htmlspecialchars($it['product']['name']); ?></td>
      <td>Rs. <?php echo number_format($it['product']['price'],2); ?></td>
      <td><input type="number" name="qty[<?php echo $it['product']['id']; ?>]" value="<?php echo $it['qty']; ?>" min="0"></td>
      <td>Rs. <?php echo number_format($it['subtotal'],2); ?></td>
    </tr>
    <?php endforeach; ?>
    <tr><td colspan="3" class="right">Total</td><td>Rs. <?php echo number_format($total,2); ?></td></tr>
  </table>
  <div class="actions">
    <button type="submit" name="update">Update Cart</button>
    <a class="btn" href="checkout.php">Proceed to Checkout</a>
  </div>
  </form>
<?php endif; ?>
<?php include 'footer.php'; ?>