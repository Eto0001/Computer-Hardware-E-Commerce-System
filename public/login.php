<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $email = trim($_POST['email'] ?? '');
    if ($email === '') {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Enter valid email.";
    }

    
    $password = trim($_POST['password'] ?? '');
    if ($password === '') {
        $errors['password'] = "Password is required.";
    }

    if (empty($errors)) {

    
        $stmt = $pdo->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

    
        if ($user && password_verify($password, $user['password'])) {

            session_regenerate_id(true);
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role']      = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: ../admin/index.php");
                exit;
            } else {
                header("Location: index.php");
                exit;
            }

        } else {
            $errors['login'] = "Invalid email or password.";
        }
    }
}

include 'header.php';
?>
<link rel="stylesheet" href="../css/form.css">

<?php foreach ($errors as $e): ?>
    <p class="error"><?= htmlspecialchars($e) ?></p>
<?php endforeach; ?>

<form method="post" class="form" id="loginForm">
    <fieldset>
        <legend class="legend"><h2>Login</h2></legend>

        <label>Email</label>
        <input type="text" name="email" id="email">
        <small class="error-text" id="emailError"></small>

        <label>Password</label>
        <input type="password" name="password" id="password">
        <small class="error-text" id="passwordError"></small>

        <button type="submit">Login</button>
    </fieldset>
</form>

<script src="../src/login.js"></script>

<?php include 'footer.php'; ?>
