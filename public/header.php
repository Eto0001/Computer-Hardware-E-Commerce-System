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
    <link rel="stylesheet" href="../css/search.css">
    <title>CHES</title>
</head>
<body>
    <header class="header">
        <div class="container">
            <h1 class="home"><a href="index.php">CHES</a></h1>
            <div class="search-container">
                <form action="search.php" method="get" class="search-form">
                    <input type="text" 
                           name="q" 
                           placeholder="Search products, categories..." 
                           value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>"
                           class="search-input">
                    <button type="submit" class="search-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </button>
                </form>
            </div>
            <nav>
                <a href="index.php">Home</a>
                <a href="cart.php">Cart (<?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>)</a>
                <?php if (!isLoggedIn()): ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
                <?php else: ?>
                    <a href="orders.php">My orders</a>
                    <a href="logout.php">Logout</a>
                    <a href="change-password.php">Change Password</a>
                    <span class="welcome">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    <?php endif; ?>
            </nav>
        </div>
    </header>
    
    <main class="container">



