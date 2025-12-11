<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
session_start();

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();

include 'header.php';
?>
<h2>My Orders</h2>
<?php if (empty($orders)): ?>
  <p>No orders yet. <a href="index.php">Shop now</a></p>
<?php else: ?>
  <table class="orders-table">
    <tr><th>Order ID</th><th>Date</th><th>Total</th><th>Status</th></tr>
    <?php foreach($orders as $o): ?>
      <tr>
        <td><?php echo $o['id']; ?></td>
        <td><?php echo $o['created_at']; ?></td>
        <td>Rs. <?php echo number_format($o['total_amount'],2); ?></td>
        <td><?php echo $o['status']; ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>
<?php include 'footer.php'; ?>
