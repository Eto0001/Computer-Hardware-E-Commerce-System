<?php
require_once 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])) {
  $name = sanitize($_POST['name']);
  $pdo->prepare("INSERT INTO categories (name) VALUES (?)")->execute([$name]);
  header('Location: categories.php');
  exit;
}

if (isset($_GET['del'])) {
  $id = intval($_GET['del']);
  $pdo->prepare("DELETE FROM categories WHERE id=?")->execute([$id]);
  header('Location: categories.php');
  exit;
}

$cats = $pdo->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll();
?>
<h2>Categories</h2>
<link rel="stylesheet" href="../css/admin-categories.css">
<form method="post" class="form-inline">
  <input name="name" placeholder="New category" required>
  <button type="submit">Add</button>
</form>
<table>
<tr><th>ID</th><th>Name</th><th>Action</th></tr>
<?php foreach($cats as $c): ?>
<tr>
  <td><?php echo $c['id']; ?></td>
  <td><?php echo htmlspecialchars($c['name']); ?></td>
  <td><a href="?del=<?php echo $c['id']; ?>" onclick="return confirm('Delete category?')">Delete</a></td>
</tr>
<?php endforeach; ?>
</table>
<?php require 'footer.php'; ?>
