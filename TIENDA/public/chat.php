<?php require_once __DIR__ . '/../includes/header.php'; require_once __DIR__ . '/../includes/auth.php'; require_login();

$with = (int)($_GET['with'] ?? 0);
$product_id = (int)($_GET['product'] ?? 0);
$user_id = $_SESSION['user']['id'];
$errors = [];
$ok = null;

// Enviar mensaje
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $msg = trim($_POST['message'] ?? '');
  if ($msg !== '') {
    $stmt = $mysqli->prepare("INSERT INTO messages (sender_id, receiver_id, product_id, message) VALUES (?,?,?,?)");
    $stmt->bind_param('iiis', $user_id, $with, $product_id, $msg);
    $stmt->execute();
    $stmt->close();
  }
}

// Obtener mensajes entre ambos
$stmt = $mysqli->prepare("
  SELECT m.*, u.name AS sender_name
  FROM messages m
  JOIN users u ON m.sender_id = u.id
  WHERE ((m.sender_id=? AND m.receiver_id=?) OR (m.sender_id=? AND m.receiver_id=?))
    AND m.product_id=?
  ORDER BY m.created_at ASC
");
$stmt->bind_param('iiiii', $user_id, $with, $with, $user_id, $product_id);
$stmt->execute();
$res = $stmt->get_result();
?>
<div class="card">
  <h2>Conversación</h2>
  <div style="max-height:400px; overflow-y:auto; margin-bottom:20px; border:1px solid #ccc; padding:12px; border-radius:8px; background:#fff;">
    <?php while ($msg = $res->fetch_assoc()): ?>
      <div style="margin-bottom:12px;">
        <strong><?= e($msg['sender_name']); ?>:</strong>
        <p style="margin:4px 0;"><?= nl2br(e($msg['message'])); ?></p>
        <small style="color:#888;"><?= e($msg['created_at']); ?></small>
      </div>
    <?php endwhile; $stmt->close(); ?>
  </div>
  <form method="post">
    <textarea name="message" rows="3" placeholder="Escribe tu mensaje..." required></textarea>
    <button class="btn" type="submit">Enviar</button>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
