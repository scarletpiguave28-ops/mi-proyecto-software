<?php require_once __DIR__ . '/../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tienda TITI </title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      font-family: 'Poppins', 'Segoe UI', sans-serif;
      background:#f0f2f5;
      color:#2c3e50;
      line-height:1.6;
    }

    header { background:#1c2833; padding:12px 24px; }
    header nav { display:flex; justify-content:space-between; align-items:center; }
    header nav a {
      color:#ecf0f1;
      text-decoration:none;
      margin:0 12px;
      font-weight:500;
      transition:color .3s;
    }
    header nav a:hover { color:#1abc9c; }

    .btn {
      background:#1abc9c;
      color:#fff;
      padding:10px 18px;
      border-radius:6px;
      text-decoration:none;
      font-weight:600;
      transition:background .3s;
      display:inline-block;
    }
    .btn:hover { background:#16a085; }

    .hero {
      background: linear-gradient(120deg, #1c2833, #34495e);
      color: #ecf0f1;
      padding: 80px 24px;
      text-align: center;
    }
    .hero h1 {
      font-size: 48px;
      margin-bottom: 16px;
    }
    .hero p {
      font-size: 18px;
      margin-bottom: 24px;
    }
    .hero .btn {
      background: #1abc9c;
      color: #fff;
    }

    .features {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 24px;
      padding: 40px 24px;
    }
    .feature-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,.08);
      padding: 24px;
      text-align: center;
      transition: .3s;
    }
    .feature-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 6px 16px rgba(0,0,0,.12);
    }
    .feature-card h3 {
      font-size: 20px;
      margin-bottom: 12px;
      color:#1c2833;
    }

    .call-to-action {
      background: #1c2833;
      color: #ecf0f1;
      text-align: center;
      padding: 60px 24px;
    }
    .call-to-action h2 {
      font-size: 32px;
      margin-bottom: 12px;
    }
    .call-to-action p {
      font-size: 18px;
      margin-bottom: 20px;
    }
    .call-to-action .btn {
      background: #1abc9c;
      color: #fff;
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

<div class="hero">
  <div class="hero-content">
    <h1>Tienda de TITI</h1>
    <p>Explora productos de emprendedores locales con estilo, confianza y calidad garantizada.</p>
    <a class="btn" href="/TIENDA/public/products.php">Ver productos</a>
  </div>
</div>

<div class="features">
  <div class="feature-card">
    <h3>👗 Variedad de estilos</h3>
    <p>Desde ropa casual hasta elegante, encuentra lo que se adapta a tu personalidad.</p>
  </div>
  <div class="feature-card">
    <h3>🛍️ Compra segura</h3>
    <p>Pagos protegidos y seguimiento de pedidos en tiempo real.</p>
  </div>
  <div class="feature-card">
    <h3>🤝 Apoya emprendedores</h3>
    <p>Cada compra impulsa negocios locales y sueños reales.</p>
  </div>
</div>

<div class="call-to-action">
  <h2>¿Eres emprendedor?</h2>
  <p>Regístrate como vendedor y empieza a mostrar tus productos al mundo.</p>
  <a class="btn" href="/TIENDA/public/register.php">Crear cuenta de vendedor</a>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
