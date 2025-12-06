<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/header.php';

$errors = [];
$flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);

// Si ya está logueado, redirige según rol
if (!empty($_SESSION['user'])) {
  if ($_SESSION['user']['role'] === 'seller') {
    header('Location: /TIENDA/seller/dashboard.php');
  } else {
    header('Location: /TIENDA/public/dashboard.php');
  }
  exit;
}

// Procesar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  $stmt = $mysqli->prepare("SELECT id, name, email, password_hash, role FROM users WHERE email=? LIMIT 1");
  $stmt->bind_param('s', $email);
  $stmt->execute();
  $res = $stmt->get_result();
  $user = $res->fetch_assoc();
  $stmt->close();

  if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['user'] = [
      'id' => $user['id'],
      'name' => $user['name'],
      'email' => $user['email'],
      'role' => $user['role'],
    ];

    // Redirige según rol
    if ($user['role'] === 'seller') {
      header('Location: /TIENDA/seller/dashboard.php');
    } else {
      header('Location: /TIENDA/public/dashboard.php');
    }
    exit;
  } else {
    $errors[] = 'Credenciales inválidas';
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ingreso</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
    body {
      font-family: 'Poppins', sans-serif;
      background:#f0f2f5;
      color:#2c3e50;
    }
    .card {
      background:#fff;
      border-radius:12px;
      box-shadow:0 4px 12px rgba(0,0,0,.08);
      padding:24px;
      margin:40px auto;
      max-width:400px;
    }
    h2 {
      text-align:center;
      color:#1abc9c;
      margin-bottom:20px;
    }
    label {
      font-weight:600;
      margin-top:12px;
      display:block;
    }
    input {
      width:100%;
      padding:10px;
      border:1px solid #ccc;
      border-radius:6px;
      margin-top:6px;
    }
    .btn {
      background:#1abc9c;
      color:#fff;
      padding:10px 18px;
      border-radius:6px;
      text-decoration:none;
      font-weight:600;
      transition:background .3s;
      display:inline-block;
      margin-top:16px;
      width:100%;
      border:none;
      cursor:pointer;
    }
    .btn:hover {
      background:#16a085;
    }
    .msg {
      font-weight:600;
      margin-bottom:12px;
    }
    .msg.success { color:#0a0; }
    .msg.error { color:#b00; }
  </style>
</head>
<body>

<div class="card">
  <h2>Ingreso</h2>
  <?php if ($flash): ?><div class="msg success"><?= e($flash) ?></div><?php endif; ?>
  <?php foreach ($errors as $err): ?><div class="msg error"><?= e($err) ?></div><?php endforeach; ?>
  <form method="post">
    <label>Email</label>
    <input type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" required>
    <label>Contraseña</label>
    <input type="password" name="password" required>
    <button class="btn" type="submit">Ingresar</button>
  </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
