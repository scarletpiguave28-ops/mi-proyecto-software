<?php require_once __DIR__ . '/../includes/header.php'; require_once __DIR__ . '/../includes/auth.php'; require_role('seller');

$errors = [];
$ok = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $price = (float)($_POST['price'] ?? 0);
  $stock = (int)($_POST['stock'] ?? 0);
  $category = trim($_POST['category'] ?? '');
  $image_path = null;

  if ($title === '' || $price <= 0 || $stock < 0) $errors[] = 'Datos inválidos';

  // Validación y subida de imagen
  if (!empty($_FILES['image']['name'])) {
    $allowed = ['jpg','jpeg','png','webp'];
    $maxSize = 2 * 1024 * 1024;
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $size = $_FILES['image']['size'];

    if (!in_array($ext, $allowed)) {
      $errors[] = 'Formato de imagen no permitido (JPG, PNG, WEBP)';
    } elseif ($size > $maxSize) {
      $errors[] = 'La imagen excede el tamaño máximo de 2MB';
    } else {
      $uploadDir = realpath(__DIR__ . '/../assets/img/uploads/');
      if (!$uploadDir) {
        mkdir(__DIR__ . '/../assets/img/uploads/', 0777, true);
        $uploadDir = realpath(__DIR__ . '/../assets/img/uploads/');
      }

      $fname = 'p_' . time() . '_' . rand(1000,9999) . '.' . $ext;
      $target = $uploadDir . DIRECTORY_SEPARATOR . $fname;

      if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $image_path = '/TIENDA/assets/img/uploads/' . $fname;
      } else {
        $errors[] = 'No se pudo subir la imagen';
      }
    }
  }

  if (!$errors) {
    $stmt = $mysqli->prepare("INSERT INTO products (seller_id, title, description, price, stock, category, image_path) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param('issdiis', $_SESSION['user']['id'], $title, $description, $price, $stock, $category, $image_path);
    if ($stmt->execute()) {
      $ok = '✅ Producto creado exitosamente';
    } else {
      $errors[] = 'Error al guardar en la base de datos';
    }
    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar producto</title>
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
      max-width:600px;
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
    input, textarea {
      width:100%;
      padding:10px;
      border:1px solid #ccc;
      border-radius:6px;
      margin-top:6px;
      font-size:14px;
    }
    textarea { resize:vertical; }
    .btn {
      background:#1abc9c;
      color:#fff;
      padding:10px 18px;
      border-radius:6px;
      font-weight:600;
      transition:background .3s;
      display:inline-block;
      margin-top:20px;
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
  <h2>Agregar producto</h2>
  <?php if ($ok): ?><div class="msg success"><?= e($ok) ?></div><?php endif; ?>
  <?php foreach ($errors as $err): ?><div class="msg error"><?= e($err) ?></div><?php endforeach; ?>
  <form method="post" enctype="multipart/form-data">
    <label>Título</label>
    <input name="title" required>
    <label>Descripción</label>
    <textarea name="description" rows="4"></textarea>
    <label>Precio</label>
    <input type="number" step="0.01" name="price" required>
    <label>Stock</label>
    <input type="number" name="stock" required>
    <label>Categoría</label>
    <input name="category">
    <label>Imagen (JPG, PNG, WEBP – máx 2MB)</label>
    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
    <button class="btn" type="submit">Guardar</button>
  </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
