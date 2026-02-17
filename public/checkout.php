<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
session_start();

if (!isLoggedIn()) {
    $_SESSION['after_login_redirect'] = 'checkout.php';
    header('Location: login.php');
    exit;
}

if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

$cart = $_SESSION['cart'];

$ids = array_keys($cart);
$placeholders = implode(',', array_fill(0, count($ids), '?'));

$stmt = $pdo->prepare("SELECT id, name, price, stock, image FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$products_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

$products = [];
foreach ($products_list as $p) {
    $products[$p['id']] = $p;
}

$items = [];
$total = 0;

foreach ($cart as $product_id => $qty) {
    if (isset($products[$product_id])) {
        $product = $products[$product_id];
        $subtotal = floatval($product['price']) * intval($qty);
        $total += $subtotal;
        
        $items[] = [
            'product' => $product,
            'quantity' => $qty,
            'subtotal' => $subtotal
        ];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = sanitize($_POST['address'] ?? '');
    $method = sanitize($_POST['payment_method'] ?? 'COD');
    
    if (empty($address)) {
        $_SESSION['error'] = "Please enter a shipping address.";
    } else {
        try {
            $pdo->beginTransaction();
            
            foreach ($cart as $product_id => $qty) {
                if (!isset($products[$product_id])) {
                    throw new Exception("Product ID {$product_id} not found.");
                }
                
                $product = $products[$product_id];
                
                $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ? FOR UPDATE");
                $stmt->execute([$product_id]);
                $current_stock = $stmt->fetchColumn();
                
                if ($current_stock < $qty) {
                    throw new Exception("Not enough stock for " . htmlspecialchars($product['name']) . ". Only " . $current_stock . " available.");
                }
            }
            
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, status, created_at) VALUES (?, ?, 'pending', NOW())");
            $stmt->execute([$_SESSION['user_id'], $total]);
            $order_id = $pdo->lastInsertId();
            
            $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt_stock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            
            foreach ($cart as $product_id => $qty) {
                $product = $products[$product_id];
                $price = floatval($product['price']);
                
                $stmt_item->execute([$order_id, $product_id, $qty, $price]);
                
                $stmt_stock->execute([$qty, $product_id]);
            }
            
            $pdo->commit();
            
            unset($_SESSION['cart']);
            $_SESSION['success'] = "Order placed successfully!";
            header('Location: order_success.php?id=' . $order_id);
            exit;
            
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['error'] = "Order failed: " . $e->getMessage();
        }
    }
}

include 'header.php';
?>
<link rel="stylesheet" href="../css/checkout.css">
<h2>Checkout</h2>

<form method="post" class="form">
    <label>
        Shipping Address
        <textarea name="address" required><?= htmlspecialchars($_SESSION['user_name']) ?></textarea>
    </label>

    <label>
        Payment Method
        <select name="payment_method">
            <option value="COD">Cash On Delivery</option>
        </select>
    </label>

    <p><strong>Total: Rs. <?= number_format($total,2) ?></strong></p>

    <button type="submit">Place Order</button>
</form>

<?php include 'footer.php'; ?>
