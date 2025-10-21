<?php
require_once 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
        try {
            $stmt->execute([$name,$email,$hash]);
            header('Location: login.php');
            exit;
        } catch (PDOException $e) {
            $error = 'Email mungkin sudah terdaftar.';
        }
    } else {
        $error = 'Isi semua field dengan benar.';
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Register</title>
<link rel="stylesheet" href="style.css"></head><body>
<div class="container">
  <h1>Register</h1>
  <?php if(!empty($error)) echo "<div class='error'>$error</div>"; ?>
  <form method="post">
    <label>Nama</label><input name="name" required>
    <label>Email</label><input name="email" type="email" required>
    <label>Password</label><input name="password" type="password" required>
    <button>Register</button>
  </form>
  <p>Sudah punya akun? <a href="login.php">Login</a></p>
</div>
</body></html>
