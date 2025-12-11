<?php
// ches/public/index.php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
session_start();

// Fetch products
$sth = $pdo->query("SELECT p.*, c.name as category FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC");
$products = $sth->fetchAll();

include 'header.php';
?>
<link rel="stylesheet" href="../css/index.css">
<h2>Products</h2>
<div class="grid">
<?php foreach($products as $p): ?>
  <div class="card">
    <img src="<?php echo $p['image'] ? '../uploads/'.htmlspecialchars($p['image']) : '../images/no-image.png'; ?>" alt="">
    <h3><?php echo htmlspecialchars($p['name']); ?></h3>
    <p class="category"><?php echo htmlspecialchars($p['category'] ?? 'Uncategorized'); ?></p>
    <p class="price">Rs. <?php echo number_format($p['price'],2); ?></p>
    <p><a class="btn" href="product.php?id=<?php echo $p['id']; ?>">View</a></p>
  </div>
<?php endforeach; ?>
</div>
<?php include 'footer.php'; ?>
