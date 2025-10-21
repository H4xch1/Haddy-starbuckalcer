<?php
require_once 'config.php';
require_once 'functions.php';

$cart = &$_SESSION['cart'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        foreach($_POST['qty'] as $pid => $q) {
            $pid = (int)$pid; $q = max(0, (int)$q);
            if ($q === 0) {
                unset($cart[$pid]);
            } else {
                $cart[$pid]['qty'] = $q;
            }
        }
    }
    header('Location: cart.php');
    exit;
}

$total = 0;
foreach($cart ?? [] as $item) {
    $total += $item['price'] * $item['qty'];
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Keranjang</title>
<link rel="stylesheet" href="style.css"></head><body>
<?php include 'nav.php'; ?>
<div class="container">
  <h1>Keranjang</h1>
  <?php if(empty($cart)): ?>
    <p>Keranjang kosong. <a href="products.php">Belanja sekarang</a></p>
  <?php else: ?>
    <form method="post">
      <table class="carttable">
        <thead><tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th></tr></thead>
        <tbody>
        <?php foreach($cart as $pid => $it): ?>
          <tr>
            <td><?= htmlspecialchars($it['name']) ?></td>
            <td>Rp <?= number_format($it['price'],0,',','.') ?></td>
            <td><input type="number" name="qty[<?= $pid ?>]" value="<?= $it['qty'] ?>" min="0"></td>
            <td>Rp <?= number_format($it['price']*$it['qty'],0,',','.') ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <div class="cart-actions">
        <button name="update" value="1">Update Keranjang</button>
        <a class="btn" href="checkout.php">Checkout — Rp <?= number_format($total,0,',','.') ?></a>
      </div>
    </form>
  <?php endif; ?>
</div>
</body></html>
