<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
session_start();

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    
    if (isset($_POST['remove'])) {
        $id = intval($_POST['remove']);
        unset($_SESSION['cart'][$id]);
        $_SESSION['success'] = "Item removed from cart.";
        header('Location: cart.php');
        exit;
    }
    
    
    if (isset($_POST['update'])) {
        $quantities = $_POST['qty'] ?? [];
        foreach ($quantities as $id => $qty) {
            $id = intval($id);
            $qty = max(0, intval($qty));
            
            if ($qty == 0) {
                unset($_SESSION['cart'][$id]);
            } else {
                
                $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
                $stmt->execute([$id]);
                $product = $stmt->fetch();
                
                if ($product && $qty <= $product['stock']) {
                    $_SESSION['cart'][$id] = $qty;
                } else {
                    $_SESSION['error'] = "Not enough stock available for one or more items.";
                    
                    if ($product && $product['stock'] > 0) {
                        $_SESSION['cart'][$id] = min($_SESSION['cart'][$id], $product['stock']);
                    } else {
                        unset($_SESSION['cart'][$id]);
                    }
                }
            }
        }
        $_SESSION['success'] = "Cart updated successfully.";
        header('Location: cart.php');
        exit;
    }
}

$items = [];
$total = 0.0;

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $rows = $stmt->fetchAll();
    
    foreach ($rows as $r) {
        $qty = $_SESSION['cart'][$r['id']] ?? 0;
        $subtotal = $r['price'] * $qty;
        $items[] = [
            'product' => $r,
            'qty' => $qty,
            'subtotal' => $subtotal
        ];
        $total += $subtotal;
    }
}

include 'header.php';
?>

<link rel="stylesheet" href="../css/cart.css">

<div class="cart-container">
    <h1>Shopping Cart</h1>
    
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
    
    <?php if (empty($items)): ?>
        <div class="empty-cart">
            <p>Your cart is empty</p>
            <a href="index.php" class="btn btn-primary" >Continue Shopping</a>
        </div>
    <?php else: ?>
        <form method="post" class="cart-form">
            <div class="cart-items">
                <?php foreach ($items as $item): ?>
                    <div class="cart-item">
                        <img src="<?= $item['product']['image'] ? '../uploads/'.$item['product']['image'] : '../images/no-image.png'; ?>" 
                             alt="<?= htmlspecialchars($item['product']['name']) ?>">
                        
                        <div class="item-details">
                            <h3><?= htmlspecialchars($item['product']['name']) ?></h3>
                            <p class="item-price">Rs. <?= number_format($item['product']['price'], 2) ?></p>
                            <p class="stock-info">Stock: <?= $item['product']['stock'] ?> available</p>
                        </div>
                        
                        <div class="item-quantity">
                            <label>Quantity:</label>
                            <input type="number" 
                                   name="qty[<?= $item['product']['id'] ?>]" 
                                   value="<?= $item['qty'] ?>" 
                                   min="1" 
                                   max="<?= $item['product']['stock'] ?>"
                                   class="qty-input">
                        </div>
                        
                        <div class="item-subtotal">
                            <p>Rs. <?= number_format($item['subtotal'], 2) ?></p>
                        </div>
                        
                        <div class="item-actions">
                            <button type="submit" name="remove" value="<?= $item['product']['id'] ?>" class="btn-remove">Remove</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="cart-summary">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>Rs. <?= number_format($total, 2) ?></span>
                </div>
                <div class="summary-row total">
                    <span>Total:</span>
                    <span>Rs. <?= number_format($total, 2) ?></span>
                </div>
                
                <button type="submit" name="update" class="btn btn-secondary">Update Cart</button>
                <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
                <a href="index.php" class="btn btn-link">Continue Shopping</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>