<?php
session_start();
require_once __DIR__ . '/../src/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit;
}

$orderId = $_GET['id'];


$stmt = $pdo->prepare("
    SELECT o.*, u.name AS customer_name, u.email 
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
");
$stmt->execute([$orderId]);
$order = $stmt->fetch();

if (!$order) {
    echo "Order not found.";
    exit;
}

$itemStmt = $pdo->prepare("
    SELECT oi.*, p.name 
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$itemStmt->execute([$orderId]);
$items = $itemStmt->fetchAll();

include 'header.php';
?>
<link rel="stylesheet" href="../css/order_details.css">

<h2>Order #<?= $order['id'] ?></h2>

<?php if (isset($_GET['updated'])): ?>
    <p class="success-msg">✔ Order status updated successfully.</p>
<?php endif; ?>

<div class="order-info">
    <div class="card">
        <h3>Customer Information</h3>
        <p><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
    </div>

    <div class="card">
        <h3>Order Details</h3>
        <p><strong>Status:</strong> <span class="status-badge"><?= strtoupper($order['status']) ?></span></p>
        <p><strong>Payment:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
        <p><strong>Total:</strong> Rs. <?= number_format($order['total'], 2) ?></p>
        <p><strong>Placed On:</strong> <?= $order['created_at'] ?></p>
    </div>
</div>

<h3>Order Items</h3>

<table>
    <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($items as $i): ?>
        <tr>
            <td><?= htmlspecialchars($i['name']) ?></td>
            <td>Rs. <?= number_format($i['price'], 2) ?></td>
            <td><?= $i['quantity'] ?></td>
            <td>Rs. <?= number_format(floatval($i['price']) * intval($i['quantity']), 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="status-actions">
    <h3>Update Order Status</h3>
    
    <?php if ($order['status'] == 'pending'): ?>
        <a href="update-order-status.php?id=<?= $order['id'] ?>&status=accepted" 
           class="btn accept"
           onclick="return confirm('Accept this order?');">Accept Order</a>
        
        <a href="update-order-status.php?id=<?= $order['id'] ?>&status=rejected" 
           class="btn reject"
           onclick="return confirm('Reject this order?');">Reject Order</a>
           
    <?php elseif ($order['status'] == 'accepted'): ?>
        <a href="update-order-status.php?id=<?= $order['id'] ?>&status=dispatched" 
           class="btn dispatch"
           onclick="return confirm('Dispatch this order?');">Dispatch Order</a>
           
    <?php elseif ($order['status'] == 'dispatched'): ?>
        <a href="update-order-status.php?id=<?= $order['id'] ?>&status=delivered" 
           class="btn delivered"
           onclick="return confirm('Mark as delivered?');">Mark as Delivered</a>
           
    <?php else: ?>
        <p class="no-actions">No further actions available for this order.</p>
    <?php endif; ?>
    
    <a href="orders.php" class="btn-back">← Back to Orders</a>
</div>

<?php include 'footer.php'; ?>