<?php
session_start();
require_once __DIR__ . '/../src/db.php';
require_once 'header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$sql = "
    SELECT o.id, o.total, o.payment_method, o.status, o.created_at,
           u.name AS customer
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    ORDER BY o.id DESC
";
$orders = $pdo->query($sql)->fetchAll();
?>

<h2 class="center">Order Management</h2>
<link rel="stylesheet" href="../css/admin-order.css">

<?php if (isset($_GET['updated'])): ?>
    <p style="background:#d4edda;padding:10px;border-left:4px solid #28a745;">
        ✔ Order status updated successfully.
    </p>
<?php endif; ?>

<table border="1" cellpadding="10" width="100%">
    <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Total (Rs.)</th>
        <th>Payment</th>
        <th>Status</th>
        <th>Ordered At</th>
        <th>Action</th>
    </tr>

    <?php foreach ($orders as $o): ?>
    <tr>
        <td><?= $o['id'] ?></td>
        <td><?= htmlspecialchars($o['customer']) ?></td>
        <td>Rs. <?= number_format($o['total'], 2) ?></td>
        <td><?= $o['payment_method'] ?></td>
        <td><strong><?= strtoupper($o['status']) ?></strong></td>
        <td><?= $o['created_at'] ?></td>

        <td>
            <?php if ($o['status'] == 'pending'): ?>

                <a href="update-order-status.php?id=<?= $o['id'] ?>&status=accepted"
                   class="btn accept"
                   onclick="return confirm('Accept this order?');">Accept</a>

                <a href="update-order-status.php?id=<?= $o['id'] ?>&status=rejected"
                   class="btn reject"
                   onclick="return confirm('Reject this order?');">Reject</a>

            <?php elseif ($o['status'] == 'accepted'): ?>

                <a href="update-order-status.php?id=<?= $o['id'] ?>&status=dispatched"
                   class="btn dispatch"
                   onclick="return confirm('Dispatch this order?');">Dispatch</a>

            <?php elseif ($o['status'] == 'dispatched'): ?>

                <a href="update-order-status.php?id=<?= $o['id'] ?>&status=delivered"
                   class="btn delivered"
                   onclick="return confirm('Mark this order as delivered?');">Delivered</a>

            <?php else: ?>
                <em>No further actions</em>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php require 'footer.php'; ?>
