<?php

require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
session_start();


$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();


$selectedCategory = isset($_GET['category']) ? $_GET['category'] : 'all';

$stmt = $pdo->query("
    SELECT p.*, SUM(oi.quantity) AS total_sold
    FROM products p
    JOIN order_items oi ON oi.product_id = p.id
    WHERE p.status = 1
    GROUP BY p.id
    ORDER BY total_sold DESC
    LIMIT 4
");
$popularProducts = $stmt->fetchAll();



if ($selectedCategory && $selectedCategory !== 'all') {
    $stmt = $pdo->prepare("
      SELECT p.*, c.name AS category 
      FROM products p 
      LEFT JOIN categories c ON p.category_id = c.id 
      WHERE c.name = ? AND p.status = 1
      ORDER BY p.created_at DESC
  ");

    $stmt->execute([$selectedCategory]);
    $products = $stmt->fetchAll();
} else {
    $sth = $pdo->query("
      SELECT p.*, c.name AS category 
      FROM products p 
      LEFT JOIN categories c ON p.category_id = c.id 
      WHERE p.status = 1
      ORDER BY p.created_at DESC
  ");
    $products = $sth->fetchAll();
}


include 'header.php';
?>

<link rel="stylesheet" href="../css/index.css">
<link rel="stylesheet" href="../css/popular.css">

<script src="../src/sidebar.js"></script>

<div class="layout">

  
  <aside class="sidebar" id="sidebar">


  <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

  <h3 class="sidebar-title">Categories</h3>

  <ul class="sidebar-list">
    <li class="<?= ($currentCategory === 'all' || $currentCategory === '') ? 'active' : '' ?>">
      <a href="index.php?category=all">All Products</a>
    </li>

    <li class="<?= $currentCategory === 'Processor' ? 'active' : '' ?>">
      <a href="index.php?category=Processor">Processor</a>
    </li>

    <li class="<?= $currentCategory === 'Mouse' ? 'active' : '' ?>">
      <a href="index.php?category=Mouse">Mouse</a>
    </li>

    <li class="<?= $currentCategory === 'Keyboard' ? 'active' : '' ?>">
      <a href="index.php?category=Keyboard">Keyboard</a>
    </li>

    <li class="<?= $currentCategory === 'Monitor' ? 'active' : '' ?>">
      <a href="index.php?category=Monitor">Monitor</a>
    </li>

    <li class="<?= $currentCategory === 'CPU Case' ? 'active' : '' ?>">
      <a href="index.php?category=CPU%20Case">CPU Case</a>
    </li>

    <li class="<?= $currentCategory === 'Graphic Cards' ? 'active' : '' ?>">
      <a href="index.php?category=Graphic Cards">Graphic Cards</a>
    </li>

    <li class="<?= $currentCategory === 'RAM' ? 'active' : '' ?>">
      <a href="index.php?category=RAM">RAM</a>
    </li>

    <li class="<?= $currentCategory === 'Storage' ? 'active' : '' ?>">
      <a href="index.php?category=Storage">Storage</a>
    </li>

    <li class="<?= $currentCategory === 'PSU' ? 'active' : '' ?>">
      <a href="index.php?category=PSU">PSU</a>
    </li>
    <li class="<?= $currentCategory === 'headset' ? 'active' : '' ?>">
      <a href="index.php?category=headset">Headset</a>
    </li>

    <li class="<?= $currentCategory === 'controller' ? 'active' : '' ?>">
      <a href="index.php?category=controller">Controller</a>
    </li>
  </ul>

</aside>

<div class="content">

<?php if ($selectedCategory === 'all' && !empty($popularProducts)): ?>
  <h2>🔥 Most Sold Products</h2>

  <div class="grid">
    <?php foreach($popularProducts as $p): ?>
      <div class="card product">

        <img src="<?= $p['image'] 
              ? '../uploads/'.$p['image'] 
              : '../images/no-image.png'; ?>" 
             alt="<?= htmlspecialchars($p['name']) ?>">

        <h3><?= htmlspecialchars($p['name']) ?></h3>

        <p class="price">
          Rs. <?= number_format($p['price'],2) ?>
        </p>

        <p class="sold">
          Sold: <?= $p['total_sold'] ?>+
        </p>

        <div class="card-actions">
            <a class="btn" href="product.php?id=<?= $p['id'] ?>">
              View Details
            </a>
        </div>

      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>


    <h2>Products <?= $selectedCategory !== 'all' ? '- ' . htmlspecialchars($selectedCategory) : '' ?></h2>

    <?php if (count($products) > 0): ?>
    <div class="grid">
      <?php foreach($products as $p): ?>
        <div class="card product" data-category="<?= htmlspecialchars($p['category']) ?>">
  <img src="<?= $p['image'] ? '../uploads/'.$p['image'] : '../images/no-image.png'; ?>" 
       alt="<?= htmlspecialchars($p['name']) ?>">
  <h3><?= htmlspecialchars($p['name']) ?></h3>
  <p><?= htmlspecialchars($p['category']) ?></p>
  <p class="price">Rs. <?= number_format($p['price'],2) ?></p>

  <div class="card-actions">
      <a class="btn" href="product.php?id=<?= $p['id'] ?>">View Details</a>
      <a class="btn buy" href="buy-now.php?id=<?= $p['id'] ?>">Buy Now</a>
  </div>
</div>

      <?php endforeach; ?>
    </div>
    <?php else: ?>
      <p class="no-products">No products found in this category.</p>
    <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
