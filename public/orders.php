<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
session_start();

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['cancel']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $order_id = intval($_GET['cancel']);
    
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("SELECT id, status FROM orders WHERE id = ? AND user_id = ?");
        $stmt->execute([$order_id, $_SESSION['user_id']]);
        $order = $stmt->fetch();
        
        if ($order && $order['status'] === 'pending') {

            $stmt = $pdo->prepare("SELECT product_id, quantity FROM order_items WHERE order_id = ?");
            $stmt->execute([$order_id]);
            $items = $stmt->fetchAll();
            
            $stmt_restore = $pdo->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
            foreach ($items as $item) {
                $stmt_restore->execute([$item['quantity'], $item['product_id']]);
            }
            
            $stmt = $pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
            $stmt->execute([$order_id]);
            
            $pdo->commit();
            $_SESSION['success'] = "Order #" . $order_id . " cancelled successfully.";
        } else {
            $_SESSION['error'] = "Cannot cancel this order. It may not exist or is no longer pending.";
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Error cancelling order: " . $e->getMessage();
    }
    
    header('Location: orders.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();

include 'header.php';
?>

<link rel="stylesheet" href="../css/order.css">

<div class="orders-container">
    <h1>My Orders</h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (empty($orders)): ?>
        <div class="no-orders">
            <div class="no-orders-icon">📦</div>
            <h2>No orders yet</h2>
            <p>Start shopping and your orders will appear here!</p>
            <a href="index.php" class="btn btn-primary">Start Shopping</a>
        </div>
    <?php else: ?>
        <div class="orders-grid">
            <?php foreach($orders as $o): ?>
                <?php
                $stmt = $pdo->prepare("
                    SELECT oi.*, p.name as product_name, p.image 
                    FROM order_items oi 
                    JOIN products p ON oi.product_id = p.id 
                    WHERE oi.order_id = ?
                ");
                $stmt->execute([$o['id']]);
                $items = $stmt->fetchAll();
                ?>
                
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-info">
                            <h3>Order #<?= $o['id'] ?></h3>
                            <p class="order-date"><?= date('F j, Y, g:i a', strtotime($o['created_at'])) ?></p>
                        </div>
                        <div class="order-status">
                            <span class="status-badge status-<?= strtolower($o['status']) ?>">
                                <?= ucfirst($o['status']) ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="order-items">
                        <?php foreach($items as $item): ?>
                            <div class="order-item">
                                <img src="<?= $item['image'] ? '../uploads/'.$item['image'] : '../images/no-image.png'; ?>" 
                                     alt="<?= htmlspecialchars($item['product_name']) ?>">
                                <div class="item-details">
                                    <h4><?= htmlspecialchars($item['product_name']) ?></h4>
                                    <p>Quantity: <?= $item['quantity'] ?></p>
                                    
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="order-footer">
                        <div class="order-total">
                            <span>Total:</span>
                            <span class="total-amount">Rs. <?= number_format($o['total'], 2) ?></span>
                        </div>
                        <div class="order-actions">
                            
                            <?php if ($o['status'] === 'pending'): ?>
                                <a href="?cancel=<?= $o['id'] ?>" 
                                   class="btn btn-cancel" 
                                   onclick="return confirm('Are you sure you want to cancel this order?')">
                                    Cancel Order
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>