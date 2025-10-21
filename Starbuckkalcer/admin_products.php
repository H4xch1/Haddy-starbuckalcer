<?php
require_once 'config.php';
require_once 'functions.php';
if (!is_admin()) { header('Location: products.php'); exit; }

$action = $_GET['action'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name']; $price = (int)$_POST['price']; $stock = (int)$_POST['stock'];
    $desc = $_POST['description']; $cat = $_POST['category_id'] ?: null;
    $image = $_POST['image'] ?: null;

    if (!empty($_POST['id'])) {
        $stmt = $pdo->prepare("UPDATE products SET category_id=?, name=?, description=?, price=?, stock=?, image=? WHERE id=?");
        $stmt->execute([$cat,$name,$desc,$price,$stock,$image,$_POST['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO products (category_id,name,description,price,stock,image) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$cat,$name,$desc,$price,$stock,$image]);
    }
    header('Location: admin_products.php');
    exit;
}

if ($action === 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([(int)$_GET['id']]);
    header('Location: admin_products.php'); exit;
}

$products = $pdo->query("SELECT p.*, c.name as catname FROM products p LEFT JOIN categories c ON p.category_id=c.id")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Admin Produk</title>
<link rel="stylesheet" href="style.css"></head><body>
<?php include 'nav.php'; ?>
<div class="container">
  <h1>Admin - Produk</h1>
  <h2>Tambah / Edit</h2>
  <form method="post">
    <input type="hidden" name="id" id="prod_id">
    <label>Nama</label><input name="name" id="prod_name" required>
    <label>Kategori</label>
    <select name="category_id" id="prod_cat">
      <option value="">--None--</option>
      <?php foreach($categories as $c): ?><option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option><?php endforeach; ?>
    </select>
    <label>Harga</label><input name="price" id="prod_price" type="number" required>
    <label>Stok</label><input name="stock" id="prod_stock" type="number" required>
    <label>Image (path)</label><input name="image" id="prod_image">
    <label>Deskripsi</label><textarea name="description" id="prod_desc"></textarea>
    <button>Save</button>
  </form>

  <h2>Daftar Produk</h2>
  <table class="carttable">
    <thead><tr><th>ID</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php foreach($products as $p): ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td><?= htmlspecialchars($p['name']) ?></td>
          <td><?= htmlspecialchars($p['catname']) ?></td>
          <td>Rp <?= number_format($p['price'],0,',','.') ?></td>
          <td><?= $p['stock'] ?></td>
          <td>
            <a href="#" onclick="editProd(<?= htmlspecialchars(json_encode($p)) ?>);return false;">Edit</a> |
            <a href="?action=delete&id=<?= $p['id'] ?>" onclick="return confirm('Hapus?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script>
function editProd(obj) {
  document.getElementById('prod_id').value = obj.id;
  document.getElementById('prod_name').value = obj.name;
  document.getElementById('prod_price').value = obj.price;
  document.getElementById('prod_stock').value = obj.stock;
  document.getElementById('prod_desc').value = obj.description;
  document.getElementById('prod_cat').value = obj.category_id;
  document.getElementById('prod_image').value = obj.image;
}
</script>
</body></html>
