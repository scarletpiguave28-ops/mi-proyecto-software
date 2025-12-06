<?php require_once __DIR__ . '/../includes/header.php'; require_once __DIR__ . '/../includes/auth.php'; require_role('buyer');

$stmt = $mysqli->prepare("
  SELECT DISTINCT m.receiver_id, u.name AS seller_name, p.title AS product_title, p.id AS product_id
  FROM messages m
  JOIN users u ON m.receiver_id = u.id
  LEFT JOIN products p ON m.product_id = p.id
  WHERE m.sender_id = ?
  ORDER BY m.created_at DESC
");
$stmt->bind_param('i', $_SESSION['user']['id']);
$stmt->execute();
$res = $stmt->get_result();
?>

<div class="card">
  <h2>💬 Mis conversaciones</h2>
  <?php while ($chat = $res->fetch_assoc()): ?>
    <div class="card" style="margin-bottom:12px;">
      <p><strong>Vendedor:</strong> <?= e($chat['seller_name']); ?></p>
      <p><strong>Producto:</strong> <?= e($chat['product_title'] ?? 'Sin referencia'); ?></p>
      <a class="btn" href="/TIENDA/public/chat.php?with=<?= e($chat['receiver_id']); ?>&product=<?= e($chat['product_id']); ?>">Abrir chat</a>
    </div>
  <?php endwhile; $stmt->close(); ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
