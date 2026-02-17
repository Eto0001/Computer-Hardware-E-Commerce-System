<?php
session_start();
require_once __DIR__ . '/../src/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

try {
    $totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
} catch (Exception $e) {
    $totalProducts = 0;
}

try {
    $totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
} catch (Exception $e) {
    $totalOrders = 0;
}

try {
    $pendingOrders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status='pending'")->fetchColumn();
} catch (Exception $e) {
    $pendingOrders = 0;
}

try {
    $totalCustomers = $pdo->query("SELECT COUNT(*) FROM users WHERE role='user' OR role='customer'")->fetchColumn();
} catch (Exception $e) {
    $totalCustomers = 0;
}

try {
    $recentOrders = $pdo->query("
        SELECT o.id, o.total, o.status, o.created_at, u.name as customer_name 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.created_at DESC 
        LIMIT 5
    ")->fetchAll();
} catch (Exception $e) {
    $recentOrders = [];
}

try {
    $totalRevenue = $pdo->query("SELECT SUM(total) FROM orders WHERE status != 'cancelled'")->fetchColumn() ?? 0;
} catch (Exception $e) {
    $totalRevenue = 0;
}

include 'header.php';
?>

<link rel="stylesheet" href="../css/admin-dashboard.css">

<div class="admin-dashboard">
    <div class="welcome-section">
        <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>! 👋</h1>
        <p class="subtitle">Here's what's happening with your store today</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card products">
            <div class="stat-icon">📦</div>
            <div class="stat-info">
                <h3>Total Products</h3>
                <p class="stat-number"><?php echo number_format($totalProducts); ?></p>
            </div>
        </div>

        <div class="stat-card orders">
            <div class="stat-icon">🛒</div>
            <div class="stat-info">
                <h3>Total Orders</h3>
                <p class="stat-number"><?php echo number_format($totalOrders); ?></p>
            </div>
        </div>

        <div class="stat-card pending">
            <div class="stat-icon">⏳</div>
            <div class="stat-info">
                <h3>Pending Orders</h3>
                <p class="stat-number"><?php echo number_format($pendingOrders); ?></p>
            </div>
        </div>

        <div class="stat-card customers">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
                <h3>Total Customers</h3>
                <p class="stat-number"><?php echo number_format($totalCustomers); ?></p>
            </div>
        </div>

        <div class="stat-card revenue">
            <div class="stat-icon">💰</div>
            <div class="stat-info">
                <h3>Total Revenue</h3>
                <p class="stat-number">Rs. <?php echo number_format($totalRevenue); ?></p>
            </div>
        </div>
    </div>

    <div class="quick-actions">
        <h2>Quick Actions</h2>
        <div class="action-buttons">
            <a href="products.php" class="action-btn add-product">
                <span class="btn-icon">➕</span>
                <span class="btn-text">Add New Product</span>
            </a>
            <a href="products.php" class="action-btn manage-products">
                <span class="btn-icon">📝</span>
                <span class="btn-text">Manage Products</span>
            </a>
            <a href="orders.php" class="action-btn view-orders">
                <span class="btn-icon">📦</span>
                <span class="btn-text">View All Orders</span>
            </a>
            <a href="users.php" class="action-btn manage-users">
                <span class="btn-icon">👥</span>
                <span class="btn-text">Manage Users</span>
            </a>
        </div>
    </div>

    <?php if (!empty($recentOrders)): ?>
    <div class="recent-orders">
        <h2>Recent Orders</h2>
        <div class="orders-table-wrapper">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td>#<?= $order['id'] ?></td>
                            <td><?= htmlspecialchars($order['customer_name']) ?></td>
                            <td>Rs. <?= number_format($order['total'], 2) ?></td>
                            <td>
                                <span class="status-badge status-<?= strtolower($order['status']) ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                            <td><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                            <td>
                                <a href="order_details.php?id=<?= $order['id'] ?>" class="view-btn">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <a href="orders.php" class="view-all-link">View All Orders →</a>
    </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>