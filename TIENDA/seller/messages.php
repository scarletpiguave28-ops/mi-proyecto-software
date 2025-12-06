<?php require_once __DIR__ . '/../includes/header.php'; require_once __DIR__ . '/../includes/auth.php'; require_role('seller');

$conversation = [];
$errors = [];
$ok = null;

// Si se recibe respuesta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $receiver_id = (int)($_POST['receiver_id'] ?? 0);
  $product_id = (int)($_POST['product_id'] ?? 0);
  $msg = trim($_POST['message'] ?? '');

  if ($msg !== '') {
    $stmt = $mysqli->prepare("INSERT INTO messages (sender_id, receiver_id, product_id, message) VALUES (?,?,?,?)");
    $stmt->bind_param('iiis', $_SESSION['user']['id'], $receiver_id, $product_id, $msg);
    if ($stmt->execute()) {
      $ok = '✅ Respuesta enviada';
    } else {
      $errors[] = 'Error al enviar respuesta';
    }
    $stmt->close();
  }
}

// Obtener todas las conversaciones
$stmt = $mysqli->prepare("
  SELECT m.*, u.name AS sender_name, p.title AS product_title
  FROM messages m
  JOIN users u ON m.sender_id = u.id
  LEFT JOIN products p ON m.product_id = p.id
  WHERE m.receiver_id = ?
  ORDER BY m.product_id, m.created_at ASC
");
$stmt->bind_param('i', $_SESSION['user']['id']);
$stmt->execute();
$res = $stmt->get_result();

while ($msg = $res->fetch_assoc()) {
  $pid = $msg['product_id'];
  $sid = $msg['sender_id'];
  $key = $pid . '_' . $sid;
  if (!isset($conversation[$key])) {
    $conversation[$key] = [
      'product_title' => $msg['product_title'] ?? 'Sin producto',
      'sender_id' => $sid,
      'sender_name' => $msg['sender_name'],
      'messages' => []
    ];
  }
  $conversation[$key]['messages'][] = $msg;
}
$stmt->close();
?>

<style>
.chat-box {
  background:#fff;
  border:1px solid #ccc;
  border-radius:12px;
  padding:16px;
  margin-bottom:24px;
  box-shadow:0 4px 12px rgba(0,0,0,.08);
}
.chat-header {
  font-weight:600;
  margin-bottom:12px;
  color:#1abc9c;
}
.chat-message {
  margin-bottom:10px;
  padding:10px;
  border-radius:8px;
  max-width:80%;
}
.from-buyer {
  background:#ecf0f1;
  align-self:flex-start;
}
.from-seller {
  background:#d1f2eb;
  align-self:flex-end;
  margin-left:auto;
}
.chat-meta {
  font-size:12px;
  color:#888;
}
.chat-form textarea {
  width:100%;
  padding:10px;
  border:1px solid #ccc;
  border-radius:6px;
  margin-top:12px;
}
</style>

<div class="card">
  <h2>📨 Conversaciones con compradores</h2>
  <?php if ($ok): ?><p style="color:#0a0; font-weight:600;"><?= e($ok) ?></p><?php endif; ?>
  <?php foreach ($conversation as $conv): ?>
    <div class="chat-box">
      <div class="chat-header">
        Producto: <?= e($conv['product_title']); ?>  
        <br>Comprador: <?= e($conv['sender_name']); ?>
      </div>
      <div style="display:flex; flex-direction:column;">
        <?php foreach ($conv['messages'] as $m): ?>
          <div class="chat-message <?= $m['sender_id'] === $_SESSION['user']['id'] ? 'from-seller' : 'from-buyer' ?>">
            <?= nl2br(e($m['message'])); ?>
            <div class="chat-meta"><?= e($m['created_at']); ?></div>
          </div>
        <?php endforeach; ?>
      </div>
      <form method="post" class="chat-form">
        <input type="hidden" name="receiver_id" value="<?= e($conv['sender_id']); ?>">
        <input type="hidden" name="product_id" value="<?= e($conv['messages'][0]['product_id']); ?>">
        <textarea name="message" rows="3" placeholder="Escribe tu respuesta..." required></textarea>
        <button class="btn" type="submit">Responder</button>
      </form>
    </div>
  <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
