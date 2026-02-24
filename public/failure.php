<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
session_start();

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$order = null;

$transaction_uuid = $_GET['transaction_uuid'] ?? null;

if ($transaction_uuid) {
    try {
        $stmt = $pdo->prepare("SELECT id, total FROM orders WHERE transaction_uuid = ? AND status = 'pending_payment'");
        $stmt->execute([$transaction_uuid]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
            $stmt->execute([$order['id']]);

            $pdo->commit();
        }

    } catch (Exception $e) {
        $pdo->rollBack();
    }
}

include 'header.php';
?>

<link rel="stylesheet" href="../css/payment-failure.css">

<div class="failure-container">
    <div class="failure-animation">
        <div class="error-circle">
            <svg class="error-icon" viewBox="0 0 52 52">
                <circle class="error-circle-bg" cx="26" cy="26" r="25" fill="none"/>
                <path class="error-cross" fill="none" d="M16 16 L36 36 M36 16 L16 36"/>
            </svg>
        </div>
    </div>
    
    <h1 class="failure-title">Payment Failed</h1>
    <p class="failure-message">Your eSewa payment could not be completed</p>
    
    <div class="failure-reasons">
        <h3>Possible reasons:</h3>
        <ul>
            <li>Payment was cancelled by user</li>
            <li>Insufficient balance in eSewa account</li>
            <li>Network connection issue</li>
            <li>Transaction timeout</li>
        </ul>
    </div>
    
    <?php if ($order): ?>
    <div class="order-info">

        <p><strong>Order ID:</strong> #<?= htmlspecialchars($order['id']) ?></p>
        <p><strong>Amount:</strong> Rs. <?= number_format(floatval($order['total']), 2) ?></p>
        <p class="info-note">The order has been cancelled. Your cart is still intact — you can try again.</p>
    </div>
    <?php endif; ?>
    
    <div class="failure-actions">
        <a href="checkout.php" class="btn btn-primary">Try Again</a>
        <a href="cart.php" class="btn btn-secondary">Back to Cart</a>
        <a href="index.php" class="btn btn-outline">Continue Shopping</a>
    </div>
</div>

<style>
.failure-container {
    max-width: 600px;
    margin: 60px auto;
    padding: 40px 20px;
    text-align: center;
}

.error-circle {
    width: 120px;
    height: 120px;
    margin: 0 auto 30px;
}

.error-icon {
    width: 120px;
    height: 120px;
    border-radius: 50%;
}

.error-circle-bg {
    stroke: #f56565;
    stroke-width: 3;
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
}

.error-cross {
    stroke: #f56565;
    stroke-width: 3;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
}

@keyframes stroke {
    100% { stroke-dashoffset: 0; }
}

.failure-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: #f56565;
    margin: 20px 0 10px 0;
}

.failure-message {
    font-size: 1.2rem;
    color: #718096;
    margin-bottom: 40px;
}

.failure-reasons {
    background: #fff5f5;
    padding: 25px;
    border-radius: 12px;
    border-left: 4px solid #f56565;
    margin-bottom: 30px;
    text-align: left;
}

.failure-reasons h3 {
    color: #742a2a;
    margin: 0 0 15px 0;
}

.failure-reasons ul {
    color: #742a2a;
    margin: 0;
    padding-left: 20px;
}

.failure-reasons li {
    margin: 8px 0;
}

.order-info {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.order-info p {
    margin: 10px 0;
    font-size: 1.1rem;
}

.info-note {
    color: #718096;
    font-size: 0.95rem !important;
    margin-top: 15px !important;
}

.failure-actions {
    display: flex;
    gap: 15px;
    justify-content: center;    
    flex-wrap: wrap;
}

.btn {
    padding: 14px 30px;
    border-radius: 10px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #d57627 0%, #e2a676 100%);
    color: white;
}

.btn-secondary {
    background: white;
    color: #4a5568;
    border: 2px solid #e2e8f0;
}

.btn-outline {
    background: transparent;
    color: #d57627;
    border: 2px solid #d57627;
}
</style>

<?php include 'footer.php'; ?>
