<?php require_once __DIR__ . '/../includes/header.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $role = $_POST['role'] ?? 'buyer';

  if ($name === '') $errors[] = 'Nombre requerido';
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email inválido';
  if (strlen($password) < 6) $errors[] = 'Contraseña mínimo 6 caracteres';
  if (!in_array($role, ['buyer','seller'])) $errors[] = 'Rol inválido';

  if (!$errors) {
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
      $errors[] = 'Ese email ya existe';
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $mysqli->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?,?,?,?)");
      $stmt->bind_param('ssss', $name, $email, $hash, $role);
      if ($stmt->execute()) {
        $_SESSION['flash'] = 'Registro exitoso. Ahora ingresa.';
        header('Location: /TIENDA/public/login.php');
        exit;
      } else {
        $errors[] = 'Error al registrar';
      }
    }
    $stmt->close();
  }
}
?>
<div class="card">
  <h2>Registro</h2>
  <?php foreach ($errors as $err): ?>
    <p style="color:#b00"><?= e($err) ?></p>
  <?php endforeach; ?>
  <form method="post">
    <label>Nombre</label>
    <input type="text" name="name" value="<?= e($_POST['name'] ?? '') ?>">
    <label>Email</label>
    <input type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>">
    <label>Contraseña</label>
    <input type="password" name="password">
    <label>Rol</label>
    <select name="role">
      <option value="buyer" <?= (($_POST['role'] ?? '')==='buyer')?'selected':''; ?>>Comprador</option>
      <option value="seller" <?= (($_POST['role'] ?? '')==='seller')?'selected':''; ?>>Emprendedor</option>
    </select>
    <button class="btn" type="submit">Crear cuenta</button>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
