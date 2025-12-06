<?php require_once __DIR__ . '/../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $mysqli->prepare("SELECT p.*, u.name AS seller_name FROM products p JOIN users u ON p.seller_id=u.id WHERE p.id=? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$p = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$p) { echo "<div class='card'><p>Producto no encontrado</p></div>"; require_once __DIR__ . '/../includes/footer.php'; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $qty = max(1, (int)($_POST['qty'] ?? 1));
  $_SESSION['cart'] = $_SESSION['cart'] ?? [];
  $_SESSION['cart'][$id] = [
    'id' => $p['id'],
    'title' => $p['title'],
    'price' => (float)$p['price'],
    'qty' => $qty,
  ];
  $_SESSION['flash'] = 'Producto agregado al carrito';
  header('Location: /TIENDA/public/cart.php');
  exit;
}
?>
<div class="card">
  <?php if ($p['image_path']): ?><img src="<?= e($p['image_path']); ?>" style="width:100%; max-height:320px; object-fit:cover"><?php endif; ?>
  <h2><?= e($p['title']); ?></h2>
  <p><strong>Vendedor:</strong> <?= e($p['seller_name']); ?></p>
  <p>$<?= number_format($p['price'],2); ?> — Stock: <?= e($p['stock']); ?></p>
  <p><?= nl2br(e($p['description'])); ?></p>
  <form method="post">
    <label>Cantidad</label>
    <input type="number" name="qty" value="1" min="1" max="<?= e($p['stock']); ?>">
    <button class="btn" type="submit">Agregar al carrito</button>
<?php if ($_SESSION['user']['role'] === 'buyer'): ?>
  <a class="btn" href="/TIENDA/public/chat.php?with=<?= e($p['seller_id']); ?>&product=<?= e($p['id']); ?>">Chat con el vendedor</a>
<?php endif; ?>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
