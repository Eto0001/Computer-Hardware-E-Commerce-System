<?php

session_start();
require_once __DIR__ . '/../src/db.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$order_id = $_GET['id'] ?? null;
$status = $_GET['status'] ?? null;

$allowed = ['accepted', 'rejected', 'dispatched', 'delivered'];

if (!$order_id || !in_array($status, $allowed)) {
    die("Invalid request.");
}

$stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
$stmt->execute([$status, $order_id]);

header("Location: orders.php");
exit;
