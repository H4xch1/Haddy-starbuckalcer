<?php
require_once 'config.php';
require_once 'functions.php';
require_login();

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([current_user()['id']]);
$orders = $stmt->fetchAll();
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Orders</title>
<link rel="stylesheet" href="style.css"></head><body>
<?php include 'nav.php'; ?>
<div class="container">
  <h1>Pesanan Saya</h1>
  <?php foreach($orders as $o): ?>
    <div class="order">
      <h3>Order #<?= $o['id'] ?> — Rp <?= number_format($o['total'],0,',','.') ?> — <?= $o['status'] ?></h3>
      <small><?= $o['created_at'] ?></small>
      <?php
        $stmt = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
        $stmt->execute([$o['id']]);
        $items = $stmt->fetchAll();
      ?>
      <ul>
        <?php foreach($items as $it): ?>
          <li><?= htmlspecialchars($it['name']) ?> — <?= $it['quantity'] ?> x Rp <?= number_format($it['price'],0,',','.') ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endforeach; ?>
</div>
</body></html>
