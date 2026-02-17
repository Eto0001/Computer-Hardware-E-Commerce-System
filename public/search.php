<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
session_start();

$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$selected_category = isset($_GET['category']) ? $_GET['category'] : 'all';

$products = [];

if (!empty($search_query)) {
    if ($selected_category && $selected_category !== 'all') {
        $stmt = $pdo->prepare("
            SELECT p.*, c.name AS category 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE (p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?)
            AND c.name = ?
            ORDER BY p.created_at DESC
        ");
        $search_term = "%{$search_query}%";
        $stmt->execute([$search_term, $search_term, $search_term, $selected_category]);
    } else {
        $stmt = $pdo->prepare("
            SELECT p.*, c.name AS category 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?
            ORDER BY p.created_at DESC
        ");
        $search_term = "%{$search_query}%";
        $stmt->execute([$search_term, $search_term, $search_term]);
    }
    $products = $stmt->fetchAll();
} else {
    if ($selected_category && $selected_category !== 'all') {
        $stmt = $pdo->prepare("
            SELECT p.*, c.name AS category 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE c.name = ?
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$selected_category]);
        $products = $stmt->fetchAll();
    }
}

include 'header.php';
?>

<link rel="stylesheet" href="../css/search.css">
<link rel="stylesheet" href="../css/index.css">

<div class="layout">
  
  <?php 
  $currentCategory = $selected_category;
  include 'sidebar.php'; 
  ?>

  <section class="content">
    <?php if (!empty($search_query)): ?>
        <h2>Search Results for "<?= htmlspecialchars($search_query) ?>"</h2>
        <?php if (!empty($selected_category) && $selected_category !== 'all'): ?>
            <p class="search-filter">Filtered by: <strong><?= htmlspecialchars($selected_category) ?></strong></p>
        <?php endif; ?>
    <?php else: ?>
        <h2>Products <?= $selected_category !== 'all' ? '- ' . htmlspecialchars($selected_category) : '' ?></h2>
    <?php endif; ?>

    <?php if (count($products) > 0): ?>
    <div class="grid">
      <?php foreach($products as $p): ?>
        <div class="card product" data-category="<?= htmlspecialchars($p['category']) ?>">
          <img src="<?= $p['image'] ? '../uploads/'.$p['image'] : '../images/no-image.png'; ?>" 
               alt="<?= htmlspecialchars($p['name']) ?>">
          <h3><?= htmlspecialchars($p['name']) ?></h3>
          <p><?= htmlspecialchars($p['category']) ?></p>
          <p class="price">Rs. <?= number_format($p['price'],2) ?></p>
          <a class="btn" href="product.php?id=<?= $p['id'] ?>">View Details</a>
        </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
      <div class="no-results">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"></circle>
          <path d="m21 21-4.35-4.35"></path>
        </svg>
        <h3>No products found</h3>
        <?php if (!empty($search_query)): ?>
            <p>No results found for "<?= htmlspecialchars($search_query) ?>"</p>
            <p>Try searching with different keywords or browse all products.</p>
        <?php else: ?>
            <p>No products available in this category.</p>
        <?php endif; ?>
        <a href="index.php" class="btn btn-primary">View All Products</a>
      </div>
    <?php endif; ?>
  </section>

</div>

<?php include 'footer.php'; ?>