<?php
require_once 'config.php';
require_once 'functions.php';

$catId = isset($_GET['cat']) ? (int)$_GET['cat'] : null;
if ($catId) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? AND stock > 0");
    $stmt->execute([$catId]);
} else {
    $stmt = $pdo->query("SELECT * FROM products WHERE stock > 0 ORDER BY created_at DESC");
}
$products = $stmt->fetchAll();

$cats = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Produk</title>
<link rel="stylesheet" href="style.css"></head><body>
<?php include 'nav.php'; ?>
<div class="container">
  <h1>Produk</h1>
  <div class="filters">
    <a href="products.php">Semua</a>
    <?php foreach($cats as $c): ?>
      <a href="?cat=<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></a>
    <?php endforeach; ?>
  </div>

  <div class="grid">
    <?php foreach($products as $p): ?>
      <div class="card">
        <img src="<?= htmlspecialchars($p['image']?:'images/no-image.png') ?>" alt="">
        <h3><?= htmlspecialchars($p['name']) ?></h3>
        <p class="price">Rp <?= number_format($p['price'],0,',','.') ?></p>
        <p><a class="btn" href="product.php?id=<?= $p['id'] ?>">Detail</a></p>
      </div>
    <?php endforeach; ?>
  </div>
</div>
</body></html>
