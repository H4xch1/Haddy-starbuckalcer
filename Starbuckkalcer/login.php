<?php
require_once 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        unset($user['password']);
        $_SESSION['user'] = $user;
        header('Location: products.php');
        exit;
    } else {
        $error = "Email atau password salah.";
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Login</title>
<link rel="stylesheet" href="style.css"></head><body>
<div class="container">
  <h1>Login</h1>
  <?php if(!empty($error)) echo "<div class='error'>$error</div>"; ?>
  <form method="post">
    <label>Email</label><input name="email" type="email" required>
    <label>Password</label><input name="password" type="password" required>
    <button>Login</button>
  </form>
  <p>Belum punya akun? <a href="register.php">Register</a></p>
</div>
</body></html>
