<?php
session_start();
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
if (isset($_GET['restore'])) {
    $id = intval($_GET['restore']);
    $pdo->prepare("UPDATE products SET status=1 WHERE id=?")->execute([$id]);
    $_SESSION['success'] = "Product restored successfully!";
    header("Location: products.php");
    exit;
}

if (isset($_GET['del'])) {

    $id = intval($_GET['del']);

    $check = $pdo->prepare("SELECT COUNT(*) FROM order_items WHERE product_id=?");
    $check->execute([$id]);
    $ordered = $check->fetchColumn();

    if ($ordered > 0) {

        $pdo->prepare("UPDATE products SET status=0 WHERE id=?")->execute([$id]);

    } else {

        $stmt = $pdo->prepare("SELECT image FROM products WHERE id=?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if ($row && $row['image'] && file_exists(__DIR__ . "/../uploads/" . $row['image'])) {
            unlink(__DIR__ . "/../uploads/" . $row['image']);
        }

        $pdo->prepare("DELETE FROM products WHERE id=?")->execute([$id]);
    }

    header("Location: products.php");
    exit;
}

$q = trim($_GET['q'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 8; 
$offset = ($page - 1) * $perPage;

$whereSql = '';
$params = [];

if ($q !== '') {
    $whereSql = "WHERE p.name LIKE :q OR c.name LIKE :q";
    $params[':q'] = "%{$q}%";
}

$countSql = "SELECT COUNT(*) FROM products p LEFT JOIN categories c ON p.category_id = c.id $whereSql";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$totalItems = (int)$countStmt->fetchColumn();
$totalPages = $totalItems > 0 ? (int)ceil($totalItems / $perPage) : 1;

$sql = "
    SELECT p.*, c.name AS category
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    $whereSql
    ORDER BY p.id DESC
    LIMIT :limit OFFSET :offset
";
$stmt = $pdo->prepare($sql);

if ($q !== '') {
    $stmt->bindValue(':q', $params[':q'], PDO::PARAM_STR);
}
$stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll();

include 'header.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Manage Products - CHES Admin</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/admin-product.css">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    .top-actions { display:flex; justify-content:space-between; margin-bottom:1rem; align-items:center; gap:10px; flex-wrap:wrap; }
    .thumb { width:80px; height:60px; object-fit:cover; border-radius:4px; }
    .search-box { display:flex; gap:6px; align-items:center; }
    .pagination { display:flex; gap:8px; justify-content:center; margin-top:12px; flex-wrap:wrap; }
    .page-current { padding:6px 10px; background:#eee; border-radius:4px; }
    .small-muted { color:#666; font-size:0.9rem; text-align:center; margin-top:8px; }
  </style>
</head>
<body>
  <main class="container">
    <h2>Products</h2>

    <div class="top-actions">
      <div>
        <a class="btn" href="product_form.php">+ Add New Product</a>
        <a href="categories.php" class="btn">Manage Categories</a>
      </div>

      <form method="get" class="search-box" action="products.php">
        <input type="search" name="q" placeholder="Search products or categories..." value="<?php echo htmlspecialchars($q); ?>" style="padding:6px 8px; border-radius:4px; border:1px solid #ccc;">
        <button class="btn" type="submit">Search</button>
      </form>
    </div>

    <?php if (empty($products)): ?>
      <p>No products found<?php echo $q ? ' for "' . htmlspecialchars($q) . '"' : ''; ?>.</p>
    <?php else: ?>
      <table>
        <tr>
          <th>ID</th><th>Image</th><th>Name</th><th>Category</th>
          <th>Price</th><th>Stock</th><th>Created</th><th>Action</th>
        </tr>
        <?php foreach($products as $p): ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td>
            <?php if ($p['image'] && file_exists(__DIR__ . "/../uploads/" . $p['image'])): ?>
              <img class="thumb" src="../uploads/<?= htmlspecialchars($p['image']) ?>" alt="">
            <?php else: ?>
              <img class="thumb" src="../images/no-image.png" alt="">
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($p['name']) ?></td>
          <td><?= htmlspecialchars($p['category'] ?? 'Uncategorized') ?></td>
          <td>Rs. <?= number_format($p['price'],2) ?></td>
          <td><?= intval($p['stock']) ?></td>
          <td><?= $p['created_at'] ?></td>
          <td>
            <a href="product_form.php?id=<?= $p['id'] ?>">Edit</a> |
            <a href="?del=<?= $p['id'] ?>&<?php if ($q !== '') echo 'q=' . urlencode($q) . '&'; ?>page=<?= $page ?>" onclick="return confirm('Delete this product?')">Delete</a>
          </td>
          <td><?= $p['status'] ? 'Active' : 'Inactive' ?></td>
          <td>
            <?php if (!$p['status']): ?>
          <a href="?restore=<?= $p['id'] ?>" class="action-btn restore-btn" onclick="return confirm('Restore this product?')" title="Restore">Restore</a>
            <?php endif; ?>
          </td>

        </tr>
        <?php endforeach; ?>
      </table>

      <div class="pagination" aria-label="Pagination">
        <?php
          $baseUrl = 'products.php?';
          if ($q !== '') $baseUrl .= 'q=' . urlencode($q) . '&';
        ?>

        <?php if ($page > 1): ?>
          <a class="btn" href="<?php echo $baseUrl . 'page=' . ($page - 1); ?>">Prev</a>
        <?php endif; ?>

        <?php
          $maxLinks = 7;
          $start = max(1, $page - intval($maxLinks/2));
          $end = min($totalPages, $start + $maxLinks - 1);
          if ($end - $start + 1 < $maxLinks) {
            $start = max(1, $end - $maxLinks + 1);
          }
          for ($pageno = $start; $pageno <= $end; $pageno++):
        ?>
          <?php if ($pageno == $page): ?>
            <span class="page-current"><?php echo $pageno; ?></span>
          <?php else: ?>
            <a class="btn" href="<?php echo $baseUrl . 'page=' . $pageno; ?>"><?php echo $pageno; ?></a>
          <?php endif; ?>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
          <a class="btn" href="<?php echo $baseUrl . 'page=' . ($page + 1); ?>">Next</a>
        <?php endif; ?>
      </div>

      <p class="small-muted">
        Showing <?php echo min($offset+1, $totalItems); ?> - <?php echo min($offset + count($products), $totalItems); ?> of <?php echo $totalItems; ?> products
      </p>

    <?php endif; ?>
  </main>

<?php include 'footer.php'; ?>