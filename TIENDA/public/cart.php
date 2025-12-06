<?php require_once __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
$cart = $_SESSION['cart'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['update'])) {
    foreach ($cart as $pid => $item) {
      $newQty = max(1, (int)($_POST['qty'][$pid] ?? $item['qty']));
      $cart[$pid]['qty'] = $newQty;
    }
    $_SESSION['cart'] = $cart;
    $flash = 'Carrito actualizado';
  }
  if (isset($_POST['remove'])) {
    $pid = (int)($_POST['pid'] ?? 0);
    unset($cart[$pid]);
    $_SESSION['cart'] = $cart;
    $flash = 'Producto eliminado';
  }
}
$total = 0;
foreach ($cart as $item) { $total += $item['price'] * $item['qty']; }
?>
<div class="card">
  <h2>Carrito</h2>
  <?php if ($flash): ?><p style="color:#0a0"><?= e($flash) ?></p><?php endif; ?>
  <?php if (!$cart): ?>
    <p>Tu carrito está vacío.</p>
  <?php else: ?>
    <form method="post">
      <?php foreach ($cart as $pid => $item): ?>
        <div class="card" style="margin-bottom:8px;">
          <h3><?= e($item['title']); ?></h3>
          <p>$<?= number_format($item['price'],2); ?></p>
          <label>Cantidad</label>
          <input type="number" name="qty[<?= e($pid); ?>]" value="<?= e($item['qty']); ?>" min="1">
          <button class="btn" name="remove" value="1" onclick="this.form.pid.value='<?= e($pid); ?>'">Eliminar</button>
        </div>
      <?php endforeach; ?>
      <input type="hidden" name="pid" value="">
      <button class="btn" name="update" value="1" type="submit">Actualizar cantidades</button>
    </form>
    <p><strong>Total:</strong> $<?= number_format($total,2); ?></p>
    <a class="btn" href="/TIENDA/public/checkout.php">Proceder al pago</a>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
