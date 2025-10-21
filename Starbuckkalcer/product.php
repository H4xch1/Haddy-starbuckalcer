<?php
require_once 'config.php';
require_once 'functions.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) {
    exit('Produk tidak ditemukan');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qty = max(1, (int)$_POST['quantity']);
    if ($qty > $product['stock']) $qty = $product['stock'];
    $item = [
        'product_id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'qty' => $qty
    ];
    
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (isset($_SESSION['cart'][$product['id']])) {
        $_SESSION['cart'][$product['id']]['qty'] += $qty;
    } else {
        $_SESSION['cart'][$product['id']] = $item;
    }
    header('Location: cart.php');
    exit;
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title><?= htmlspecialchars($product['name']) ?></title>
<link rel="stylesheet" href="style.css"></head><body>
<?php include 'nav.php'; ?>
<div class="container">
  <div class="product-detail">
    <img src="<?= htmlspecialchars($product['image'] ?: 'images/no-image.jpg') ?>" alt="">
    <div class="info">
      <h1><?= htmlspecialchars($product['name']) ?></h1>
      <p class="category"><?= htmlspecialchars($product['category_name']) ?></p>
      <p class="price">Rp <?= number_format($product['price'],0,',','.') ?></p>
      <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
      <p>Stok: <?= $product['stock'] ?></p>
      <form method="post">
        <label>Jumlah</label>
        <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>">
        <button>Tambah ke Keranjang</button>
      </form>
    </div>
  </div>
</div>
</body></html>
