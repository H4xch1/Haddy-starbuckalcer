<?php
require_once 'functions.php';
?>
<nav class="topnav">
  <div class="wrap">
    <a href="products.php" class="brand">starbuckkalcer</a>
    <div class="right">
      <a href="cart.php">Keranjang (<?= count($_SESSION['cart'] ?? []) ?>)</a>
      <?php if(is_logged_in()): ?>
        <span>Hello, <?= htmlspecialchars(current_user()['name']) ?></span>
        <?php if(is_admin()): ?><a href="admin_products.php">Admin</a><?php endif; ?>
        <a href="orders.php">Orders</a>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
