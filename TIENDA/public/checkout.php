<?php require_once __DIR__ . '/../includes/header.php'; require_once __DIR__ . '/../includes/auth.php'; require_role('buyer');

$cart = $_SESSION['cart'] ?? [];
if (!$cart) { echo "<div class='card'><p>Carrito vacío.</p></div>"; require_once __DIR__ . '/../includes/footer.php'; exit; }

$total = 0;
foreach ($cart as $item) { $total += $item['price'] * $item['qty']; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Crear orden
  $stmt = $mysqli->prepare("INSERT INTO orders (buyer_id, total, status) VALUES (?,?, 'pending')");
  $stmt->bind_param('id', $_SESSION['user']['id'], $total);
  if ($stmt->execute()) {
    $order_id = $stmt->insert_id;
    $stmt->close();
    // Insertar items y reducir stock
    foreach ($cart as $item) {
      $pid = (int)$item['id'];
      $qty = (int)$item['qty'];
      $price = (float)$item['price'];
      $stmt = $mysqli->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?,?,?,?)");
      $stmt->bind_param('iiid', $order_id, $pid, $qty, $price);
      $stmt->execute();
      $stmt->close();

      // Reducir stock
      $stmt = $mysqli->prepare("UPDATE products SET stock = stock - ? WHERE id=? AND stock >= ?");
      $stmt->bind_param('iii', $qty, $pid, $qty);
      $stmt->execute();
      $stmt->close();
    }
    $_SESSION['cart'] = [];
    $_SESSION['flash'] = 'Orden creada. Estado: pendiente.';
    header('Location: /TIENDA/public/index.php');
    exit;
  } else {
    $error = 'No se pudo crear la orden';
  }
}
?>
<div class="card">
  <h2>Checkout</h2>
  <p><strong>Total:</strong> $<?= number_format($total,2); ?></p>
  <?php if (!empty($error)): ?><p style="color:#b00"><?= e($error); ?></p><?php endif; ?>
  <form method="post">
    <button class="btn" type="submit">Confirmar compra</button>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
