<?php require_once __DIR__ . '/../includes/header.php'; require_once __DIR__ . '/../includes/auth.php'; require_role('seller'); ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel del vendedor</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

    body {
      font-family: 'Poppins', sans-serif;
      background:#f0f2f5;
      color:#2c3e50;
    }

    .dashboard {
      background:#fff;
      border-radius:12px;
      box-shadow:0 4px 12px rgba(0,0,0,.08);
      padding:24px;
      margin:40px auto;
      max-width:600px;
      text-align:center;
    }

    .dashboard h2 {
      font-size:28px;
      margin-bottom:12px;
      color:#1abc9c;
    }

    .dashboard p {
      font-size:16px;
      margin-bottom:24px;
    }

    .btn-group {
      display:flex;
      flex-direction:column;
      gap:12px;
      align-items:center;
    }

    .btn {
      background:#1abc9c;
      color:#fff;
      padding:12px 20px;
      border-radius:8px;
      text-decoration:none;
      font-weight:600;
      transition:background .3s;
      width:80%;
      max-width:300px;
    }

    .btn:hover {
      background:#16a085;
    }

    footer {
      background:#1c2833;
      color:#ecf0f1;
      text-align:center;
      padding:16px;
      margin-top:40px;
    }
  </style>
</head>
<body>

<div class="dashboard">
  <h2>👋 Hola <?= e($_SESSION['user']['name']); ?> (vendedor)</h2>
  <p>Bienvenido a tu panel. Aquí puedes gestionar tus productos, revisar mensajes y controlar tu tienda.</p>

  <div class="btn-group">
    <a class="btn" href="/TIENDA/seller/add_product.php">➕ Agregar producto</a>
    <a class="btn" href="/TIENDA/seller/my_products.php">📦 Mis productos</a>
    <a class="btn" href="/TIENDA/seller/messages.php">💬 Mensajes recibidos</a>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
