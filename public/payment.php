<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
session_start();

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$payment_method = $_SESSION['checkout']['payment_method'] ?? 'COD';


$total = 0;
$ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($ids), '?'));

$stmt = $pdo->prepare("SELECT id, price FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$products = $stmt->fetchAll();

foreach ($products as $p) {
    $total += $p['price'] * $_SESSION['cart'][$p['id']];
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        INSERT INTO orders (user_id, total_amount, payment_method)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$user_id, $total, $payment_method]);

    $order_id = $pdo->lastInsertId();

    $stmtItem = $pdo->prepare("
        INSERT INTO order_items (order_id, products_id, quantity, price_at_order)
        VALUES (?, ?, ?, ?)
    ");

    foreach ($products as $p) {
        $stmtItem->execute([
            $order_id,
            $p['id'],
            $_SESSION['cart'][$p['id']],
            $p['price']
        ]);
    }

    $pdo->commit();

    unset($_SESSION['cart'], $_SESSION['checkout']);

    header("Location: order-success.php?id=" . $order_id);
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    die("Could not place order: " . $e->getMessage());
}
