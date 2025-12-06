<?php require_once __DIR__ . '/../includes/header.php'; require_once __DIR__ . '/../includes/auth.php'; require_role('seller');

$stmt = $mysqli->prepare("SELECT id, title, price, stock, image_path FROM products WHERE seller_id=? ORDER BY created_at DESC");
$stmt->bind_param('i', $_SESSION['user']['id']);
$stmt->execute();
$res = $stmt->get_result();
?>
<div class="card">
  <h2>Mis productos</h2>
  <div class="grid">
    <?php while ($p = $res->fetch_assoc()): ?>
      <div class="card">
        <?php if ($p['image_path']): ?><img src="<?= e($p['image_path']); ?>" style="width:100%; height:160px; object-fit:cover"><?php endif; ?>
        <h3><?= e($p['title']); ?></h3>
        <p>$<?= number_format($p['price'], 2); ?> — Stock: <?= e($p['stock']); ?></p>
        <a class="btn" href="/TIENDA/public/product_view.php?id=<?= e($p['id']); ?>">Ver</a>
      </div>
    <?php endwhile; $stmt->close(); ?>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
<a class="btn" href="/TIENDA/public/chat.php?with=<?= e($msg['sender_id']); ?>&product=<?= e($msg['product_id']); ?>">Responder</a>
