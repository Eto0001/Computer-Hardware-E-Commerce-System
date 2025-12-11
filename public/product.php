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
<link rel="stylesheet" href="../css/product.css">
<div class="product-detail">
  <img src="<?php echo $product['image'] ? '../uploads/'.htmlspecialchars($product['image']) : '../images/no-image.png'; ?>" alt="">
  <div class="info">
    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
    <p class="category"><?php echo htmlspecialchars($product['category'] ?? 'Uncategorized'); ?></p>
    <p class="price">Rs. <?php echo number_format($product['price'],2); ?></p>
    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
    <p>Stock: <?php echo intval($product['stock']); ?></p>
    <form method="post" class="form-inline">
      <label>Quantity <input name="quantity" type="number" value="1" min="1" max="<?php echo intval($product['stock']); ?>"></label>
      <button type="submit">Add to Cart</button>
    </form>
  </div>
</div>
<?php include 'footer.php'; ?>