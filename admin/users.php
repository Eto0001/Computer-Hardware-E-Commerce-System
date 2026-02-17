<?php
require_once 'header.php';
$users = $pdo->query("SELECT id, name, email, role, created_at FROM users ORDER BY id DESC")->fetchAll();
?>
<h2>Users</h2>
<link rel="stylesheet" href="../css/users.css">
<table>
<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Date</th></tr>
<?php foreach($users as $u): ?>
<tr>
  <td><?php echo $u['id']; ?></td>
  <td><?php echo htmlspecialchars($u['name']); ?></td>
  <td><?php echo htmlspecialchars($u['email']); ?></td>
  <td><?php echo htmlspecialchars($u['role']); ?></td>
  <td><?php echo $u['created_at']; ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php require 'footer.php'; ?>
