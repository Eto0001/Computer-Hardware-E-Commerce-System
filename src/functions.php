<?php
function redirect($url) {
    header("Location: " . $url);
    exit();
}

function isLoggedIn(){
    return isset($_SESSION['user_id']);
}

function isAdmin(){
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
?>