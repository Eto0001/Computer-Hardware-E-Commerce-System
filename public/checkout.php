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

// Fetch products
$ids = array_keys($cart);
$placeholders = implode(',', array_fill(0, count($ids), '?'));

$stmt = $pdo->prepare("SELECT id, name, price, stock, image FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$products_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create product map
$products = [];
foreach ($products_list as $p) {
    $products[$p['id']] = $p;
}

// Calculate total
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = sanitize($_POST['address'] ?? '');
    $method = sanitize($_POST['payment_method'] ?? 'COD');
    
    if (empty($address)) {
        $_SESSION['error'] = "Please enter a shipping address.";
        // FIX Bug 5: persist address so textarea repopulates on error
        $_SESSION['checkout']['address'] = $_POST['address'] ?? '';
    } else {
        try {
            $pdo->beginTransaction();
            
            // Verify stock
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

            // FIX Bug 1: generate transaction_uuid ONCE, before the INSERT, so the DB and eSewa form always match
            $transaction_uuid = ($method === 'esewa') ? "ORDER-" . time() . "-" . rand(1000, 9999) : null;

            $stmt = $pdo->prepare("
                INSERT INTO orders (user_id, total, status, payment_method, transaction_uuid, created_at)
                VALUES (?, ?, 'pending', ?, ?, NOW())
            ");
            $stmt->execute([
                $_SESSION['user_id'],
                $total,
                $method,
                $transaction_uuid,
            ]);

            $order_id = $pdo->lastInsertId();
            
            // Insert order items (and deduct stock for COD only)
            $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt_stock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            
            foreach ($cart as $product_id => $qty) {
                $product = $products[$product_id];
                $price = floatval($product['price']);
    
                $stmt_item->execute([$order_id, $product_id, $qty, $price]);

                // Stock for COD is deducted here; for eSewa it is deducted in order_success.php after confirmed payment
                if ($method === 'COD') {
                    $stmt_stock->execute([$qty, $product_id]);
                }
            }
            
            $pdo->commit();
            
            // If eSewa payment, redirect to eSewa
            if ($method === 'esewa') {

                $secret = "8gBm/:&EnhH.1/q"; // Test key
                $message = "total_amount={$total},transaction_uuid={$transaction_uuid},product_code=EPAYTEST";
                $signature = base64_encode(hash_hmac('sha256', $message, $secret, true));
                
                // FIX Bug 4: escape all values echoed into HTML attributes
                $safe_total = htmlspecialchars($total, ENT_QUOTES, 'UTF-8');
                $safe_uuid  = htmlspecialchars($transaction_uuid, ENT_QUOTES, 'UTF-8');
                $safe_sig   = htmlspecialchars($signature, ENT_QUOTES, 'UTF-8');
                ?>
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Redirecting to eSewa...</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            height: 100vh;
                            background: linear-gradient(135deg, #60bb46 0%, #4a9637 100%);
                            margin: 0;
                        }
                        .loader-container {
                            text-align: center;
                            background: white;
                            padding: 40px;
                            border-radius: 20px;
                            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
                        }
                        .loader {
                            border: 5px solid #f3f3f3;
                            border-top: 5px solid #60bb46;
                            border-radius: 50%;
                            width: 50px;
                            height: 50px;
                            animation: spin 1s linear infinite;
                            margin: 0 auto 20px;
                        }
                        @keyframes spin {
                            0% { transform: rotate(0deg); }
                            100% { transform: rotate(360deg); }
                        }
                        h2 { color: #333; margin-bottom: 10px; }
                        p { color: #666; }
                    </style>
                </head>
                <body>
                    <div class="loader-container">
                        <div class="loader"></div>
                        <h2>Redirecting to eSewa...</h2>
                        <p>Please wait while we redirect you to the payment gateway.</p>
                    </div>
                    <form id="esewaForm" action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
                        <input type="hidden" name="amount" value="<?= $safe_total ?>">
                        <input type="hidden" name="tax_amount" value="0">
                        <input type="hidden" name="total_amount" value="<?= $safe_total ?>">
                        <input type="hidden" name="transaction_uuid" value="<?= $safe_uuid ?>">
                        <input type="hidden" name="product_code" value="EPAYTEST">
                        <input type="hidden" name="product_service_charge" value="0">
                        <input type="hidden" name="product_delivery_charge" value="0">
                        <input type="hidden" name="success_url" value="https://palaeanthropic-ramon-precomprehensive.ngrok-free.dev/CHES_PROJECT/public/order_success.php">
                        <input type="hidden" name="failure_url" value="https://palaeanthropic-ramon-precomprehensive.ngrok-free.dev/CHES_PROJECT/public/failure.php">
                        <input type="hidden" name="signed_field_names" value="total_amount,transaction_uuid,product_code">
                        <input type="hidden" name="signature" value="<?= $safe_sig ?>">
                    </form>
                    <script>
                        document.getElementById('esewaForm').submit();
                    </script>
                </body>
                </html>
                <?php
                exit;
            }
            
            // For COD, clear cart and redirect to success
            unset($_SESSION['cart']);
            unset($_SESSION['checkout']);
            $_SESSION['success'] = "Order placed successfully!";
            header('Location: cod_order_success.php?id=' . $order_id);
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

<div class="checkout-container">
    <h1>Checkout</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <div class="checkout-layout">
        <div class="order-summary">
            <h2>Order Summary</h2>
            
            <div class="order-items">
                <?php foreach ($items as $item): ?>
                    <div class="order-item">
                        <img src="<?= $item['product']['image'] ? '../uploads/'.$item['product']['image'] : '../images/no-image.png'; ?>" 
                             alt="<?= htmlspecialchars($item['product']['name']) ?>">
                        <div class="item-info">
                            <h4><?= htmlspecialchars($item['product']['name']) ?></h4>
                            <p>Quantity: <?= $item['quantity'] ?></p>
                            <p>Price: Rs. <?= number_format($item['product']['price'], 2) ?></p>
                            <p class="subtotal">Subtotal: Rs. <?= number_format($item['subtotal'], 2) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="order-total">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>Rs. <?= number_format($total, 2) ?></span>
                </div>
                <div class="total-row">
                    <span>Shipping:</span>
                    <span>Free</span>
                </div>
                <div class="total-row grand-total">
                    <span>Total:</span>
                    <span>Rs. <?= number_format($total, 2) ?></span>
                </div>
            </div>
        </div>
        
        <div class="checkout-form">
            <h2>Shipping Details</h2>
            <form method="post">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" value="<?= htmlspecialchars($_SESSION['user_name']) ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" value="<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label>Shipping Address *</label>
                    <textarea name="address" rows="4" required placeholder="Enter your complete shipping address"><?= htmlspecialchars($_SESSION['checkout']['address'] ?? '') ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Payment Method</label>
                    <select name="payment_method" required>
                        <option value="COD">Cash On Delivery (COD)</option>
                        <option value="esewa">eSewa - Digital Payment</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Place Order</button>
                <a href="cart.php" class="btn btn-secondary">Back to Cart</a>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
