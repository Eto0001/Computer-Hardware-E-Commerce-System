<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
session_start();

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT p.*, c.name as category FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) {
    header('Location: index.php');
    exit;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qty = max(1, intval($_POST['quantity'] ?? 1));
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id] += $qty;
    else $_SESSION['cart'][$id] = $qty;
    header('Location: cart.php');
    exit;
}

include 'header.php';
?>

<link rel="stylesheet" href="../css/index.css">
<link rel="stylesheet" href="../css/product.css">

<div class="layout">

  <?php 
  $currentCategory = $product['category'];
  include 'sidebar.php'; 
  ?>

  <section class="content">
    <div class="product-detail">
      <div class="product-image">
        <img src="<?php echo $product['image'] ? '../uploads/'.htmlspecialchars($product['image']) : '../images/no-image.png'; ?>" 
             alt="<?php echo htmlspecialchars($product['name']); ?>">
      </div>
      
      <div class="product-info">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <p class="category-badge"><?php echo htmlspecialchars($product['category'] ?? 'Uncategorized'); ?></p>
        <p class="price">Rs. <?php echo number_format($product['price'],2); ?></p>
        
        <div class="description">
          <h3>Description</h3>
          <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
        </div>
        
        <div class="stock-info">
  <?php if ($product['stock'] > 0): ?>
    <p class="in-stock"><strong>Stock Available:</strong> <?= intval($product['stock']); ?> units</p>
  <?php else: ?>
    <p class="out-stock"><strong>Out of Stock</strong></p>
  <?php endif; ?>
</div>

<form method="post" class="cart-form" id="productForm">
  <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

  <div class="quantity-selector">
    <label for="quantity">Quantity:</label>
    <input 
      type="number" 
      name="quantity" 
      id="quantity" 
      value="0" 
      min="1"
      max="<?= intval($product['stock']); ?>"
      <?= $product['stock'] == 0 ? 'disabled' : '' ?>
      required
    >
  </div>

  <div class="action-buttons">
    <button 
      type="submit" 
      name="action"
      value="add_to_cart"
      class="btn btn-primary"
      <?= $product['stock'] == 0 ? 'disabled' : '' ?>>
      Add to Cart
    </button>

    <button 
      type="submit" 
      name="action"
      value="buy_now"
      class="btn buy"
      <?= $product['stock'] == 0 ? 'disabled' : '' ?>>
      Buy Now
    </button>
  </div>
</form>

<script>
document.getElementById('productForm').addEventListener('submit', function(e) {
  const quantity = parseInt(document.getElementById('quantity').value);
  const maxStock = parseInt(document.getElementById('quantity').max);
  
  if (quantity > maxStock) {
    e.preventDefault();
    alert(`Only ${maxStock} units available in stock.`);
    return false;
  }
});
</script>

  <a href="index.php?category=<?= urlencode($product['category']); ?>" class="btn btn-secondary">
    Back to <?= htmlspecialchars($product['category']); ?>
  </a>

</form>

      </div>
    </div>
  </section>

</div>
<div id="stockDialog" class="dialog-overlay">
  <div class="dialog-box">
    <h3>Out of Stock</h3>
    <p>Sorry, this product is currently unavailable.</p>
    <button onclick="closeDialog()">OK</button>
  </div>
</div>
<script src="../src/stock.js"></script>

<?php include 'footer.php'; ?>