<?php
session_start();
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = trim($_POST['current_password'] ?? '');
    $newPassword = trim($_POST['new_password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');


    if ($currentPassword === '') {
        $errors['current_password'] = "Current password is required.";
    }

    
    if ($newPassword === '') {
        $errors['new_password'] = "New password is required.";
    } elseif (strlen($newPassword) < 6) {
        $errors['new_password'] = "Password must be at least 6 characters.";
    }

    
    if ($confirmPassword === '') {
        $errors['confirm_password'] = "Please confirm your password.";
    } elseif ($newPassword !== $confirmPassword) {
        $errors['confirm_password'] = "Passwords do not match.";
    }

    
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($currentPassword, $user['password'])) {
            
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $updateStmt->execute([$hashedPassword, $_SESSION['user_id']]);

            $success = "Password changed successfully!";
            
            
            $_POST = [];
        } else {
            $errors['current_password'] = "Current password is incorrect.";
        }
    }
}

include 'header.php';
?>
<link rel="stylesheet" href="../css/form.css">

<h2>Change Password</h2>

<?php if ($success): ?>
    <p class="success-message"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<?php if (!empty($errors) && !isset($errors['current_password']) && !isset($errors['new_password']) && !isset($errors['confirm_password'])): ?>
    <?php foreach ($errors as $error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endforeach; ?>
<?php endif; ?>

<form method="post" class="form">
    <fieldset>
        <legend>Update Your Password</legend>

        <label>Current Password</label>
        <input type="password" name="current_password" required>
        <?php if (isset($errors['current_password'])): ?>
            <small class="error-text"><?= htmlspecialchars($errors['current_password']) ?></small>
        <?php endif; ?>

        <label>New Password</label>
        <input type="password" name="new_password" required>
        <small class="help-text">Must be at least 6 characters</small>
        <?php if (isset($errors['new_password'])): ?>
            <small class="error-text"><?= htmlspecialchars($errors['new_password']) ?></small>
        <?php endif; ?>

        <label>Confirm New Password</label>
        <input type="password" name="confirm_password" required>
        <?php if (isset($errors['confirm_password'])): ?>
            <small class="error-text"><?= htmlspecialchars($errors['confirm_password']) ?></small>
        <?php endif; ?>

        <button type="submit">Change Password</button>
    </fieldset>
</form>

<p style="text-align: center; margin-top: 1rem;">
    <a href="index.php" class="btn">← Back to Home</a>
</p>

<?php include 'footer.php'; ?>