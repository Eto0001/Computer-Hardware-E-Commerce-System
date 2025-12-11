<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // NAME
    $name = trim($_POST['name'] ?? '');
    if ($name === '') {
        $errors['name'] = "Enter name";
    } elseif (!preg_match("/^[A-Z][a-zA-Z\s]{1,30}$/", $name)) {
        $errors['name'] = "Enter valid name (first letter capital)";
    }

    // EMAIL
    $email = trim($_POST['email'] ?? '');
    if ($email === '') {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    // ADDRESS
    $address = trim($_POST['address'] ?? '');
    if ($address === '') {
        $errors['address'] = "Address is required";
    }

    // PHONE
    $phone = trim($_POST['phone'] ?? '');
    if ($phone === '') {
        $errors['phone'] = "Phone is required";
    } elseif (!preg_match("/^(98|97|96)[0-9]{8}$/", $phone)) {
        $errors['phone'] = "Invalid Phone Number";
    }

    // PASSWORD
    $password = $_POST["password"] ?? "";
    if (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters";
    }

    // If valid in PHP — insert user
    if (empty($errors)) {

        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $errors['email'] = "Email already registered.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $ins = $pdo->prepare("INSERT INTO users (name,email,password,phone,address)
                                  VALUES (?, ?, ?, ?, ?)");
            $ins->execute([$name, $email, $hash, $phone, $address]);

            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['user_name'] = $name;
            $_SESSION['role'] = 'customer';

            header("Location: index.php");
            exit;
        }
    }
}

include 'header.php';
?>
<link rel="stylesheet" href="../css/form.css">


<form method="post" class="form" id="registerForm">
    <fieldset>
    <legend class="legend"><h2>Register</h2></legend>
    
    <label>Name</label>
    <input type="text" name="name" id="name" value="<?= $name ?? '' ?>">
    <small class="error-text" id="nameError">
    <?= $errors['name'] ?? '' ?>
    </small>


    <label>Email</label>
    <input type="text" name="email" id="email" value="<?= $email ?? '' ?>">
    <small class="error-text" id="emailError">
    <?= $errors['email'] ?? '' ?>
    </small>


    <label>Phone</label>
    <input type="number" name="phone" id="phone" value="<?= $phone ?? '' ?>">
    <small class="error-text" id="phoneError">
    <?= $errors['phone'] ?? '' ?>
    </small>


    <label>Address</label>
    <input type="text" name="address" id="address" value="<?= $address ?? '' ?>">
    <small class="error-text" id="addressError">
    <?= $errors['address'] ?? '' ?>
    </small>

    <label>Password</label>
    <input type="password" name="password" id="password">
    <small class="error-text" id="passwordError">
    <?= $errors['password'] ?? '' ?>
    </small>


    <button type="submit" class="reg">Register</button>
    </fieldset>
</form>

<script src="../src/sregister.js"></script>

<?php include 'footer.php'; ?>
