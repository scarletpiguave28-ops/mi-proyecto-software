<?php require_once __DIR__ . '/../includes/header.php'; require_once __DIR__ . '/../includes/auth.php'; require_role('buyer');

$to = (int)($_GET['to'] ?? 0);
$product_id = (int)($_GET['product'] ?? 0);
$errors = [];
$ok = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $msg = trim($_POST['message'] ?? '');
  if ($msg === '') {
    $errors[] = 'El mensaje no puede estar vacío';
  } else {
    $stmt = $mysqli->prepare("INSERT INTO messages (sender_id, receiver_id, product_id, message) VALUES (?,?,?,?)");
    $stmt->bind_param('iiis', $_SESSION['user']['id'], $to, $product_id, $msg);
    if ($stmt->execute()) {
      $ok = '✅ Mensaje enviado al vendedor';
    } else {
      $errors[] = 'Error al enviar el mensaje';
    }
    $stmt->close();
  }
}
?>
<div class="card">
  <h2>Enviar mensaje al vendedor</h2>
  <?php if ($ok): ?><p style="color:#0a0; font-weight:600;"><?= e($ok) ?></p><?php endif; ?>
  <?php foreach ($errors as $err): ?><p style="color:#b00; font-weight:600;"><?= e($err) ?></p><?php endforeach; ?>
  <form method="post">
    <label>Mensaje</label>
    <textarea name="message" rows="4" required></textarea>
    <button class="btn" type="submit">Enviar</button>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
