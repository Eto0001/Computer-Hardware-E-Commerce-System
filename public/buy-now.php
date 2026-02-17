<?php
session_start();
require_once __DIR__ . '/../src/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$product_id = intval($_GET['id'] ?? 0);
if ($product_id <= 0) {
    header("Location: index.php");
    exit;
}

$_SESSION['cart'] = [];

$_SESSION['cart'][$product_id] = 1;

header("Location: checkout.php");
exit;