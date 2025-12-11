<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../src/functions.php';
require_once __DIR__ . '/../src/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>CHES</title>
</head>
<body>
    <header class="header">
        <div class="container">
            <h1 class="home"><a href="index.php">CHES</a></h1>
            <nav>
                <a href="index.php">Home</a>
                <a href="cart.php">Cart (<?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>)</a>
                <?php if (!isLoggedIn()): ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
                <?php else: ?>
                    <a href="orders.php">My orders</a>
                    <a href="logout.php">Logout</a>
                    <span class="welcome">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="container">


