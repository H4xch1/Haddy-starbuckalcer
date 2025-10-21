<?php
require_once 'config.php';
require_once 'functions.php';
require_login();

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header('Location: cart.php'); exit;
}

$total = 0;
foreach($cart as $it) $total += $it['price'] * $it['qty'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
        $stmt->execute([current_user()['id'], $total]);
        $orderId = $pdo->lastInsertId();

        $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmtStock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");

        foreach($cart as $it) {
            $stmtItem->execute([$orderId, $it['product_id'], $it['qty'], $it['price']]);
            $stmtStock->execute([$it['qty'], $it['product_id'], $it['qty']]);
        }

        $pdo->commit();
        $_SESSION['cart'] = [];
        header("Location: orders.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Gagal membuat order: " . $e->getMessage();
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Checkout</title>
<link rel="stylesheet" href="style.css"></head><body>
<?php include 'nav.php'; ?>
<div class="container">
  <h1>Checkout</h1>
  <?php if(!empty($error)) echo "<div class='error'>$error</div>"; ?>
  <p>Total belanja: <strong>Rp <?= number_format($total,0,',','.') ?></strong></p>
  <form method="post">
    <p>Metode pembayaran dummy (simulasi)</p>
    <button>Konfirmasi dan Bayar</button>
  </form>
</div>
</body></html>
