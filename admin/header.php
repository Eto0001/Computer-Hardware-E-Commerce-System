<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../src/functions.php';
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin - CHES</title>
  <link rel="stylesheet" href="../css/admin-header.css">
</head>
<body>

<header class="site-header">
  <div class="container">
    <h1><a href="index.php">CHES Admin</a></h1>

    <nav>
      <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'admin-active' : '' ?>">Dashboard</a>
      <a href="products.php" class="<?= basename($_SERVER['PHP_SELF']) === 'products.php' ? 'admin-active' : '' ?>">Products</a>
      <a href="categories.php" class="<?= basename($_SERVER['PHP_SELF']) === 'categories.php' ? 'admin-active' : '' ?>">Categories</a>
      <a href="orders.php" class="<?= basename($_SERVER['PHP_SELF']) === 'orders.php' ? 'admin-active' : '' ?>">Orders</a>
      <a href="users.php" class="<?= basename($_SERVER['PHP_SELF']) === 'users.php' ? 'admin-active' : '' ?>">Users</a>
      <a href="../public/logout.php">Logout</a>
    </nav>
  </div>
</header>

<main class="container">
