<?php require_once __DIR__ . '/../includes/header.php';

$category = trim($_GET['category'] ?? '');
$q = trim($_GET['q'] ?? '');
$sql = "SELECT p.id, p.title, p.price, p.image_path FROM products p WHERE 1";
$params = []; $types = '';

if ($category !== '') { $sql .= " AND p.category = ?"; $params[] = $category; $types .= 's'; }
if ($q !== '') { $sql .= " AND (p.title LIKE CONCAT('%', ?, '%') OR p.description LIKE CONCAT('%', ?, '%'))"; $params[] = $q; $params[] = $q; $types .= 'ss'; }
$sql .= " ORDER BY p.created_at DESC";

$stmt = $mysqli->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Productos</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      font-family: 'Poppins', 'Segoe UI', sans-serif;
      background:#f0f2f5;
      color:#2c3e50;
      line-height:1.6;
    }

    .card {
      background:#fff;
      border-radius:12px;
      box-shadow:0 4px 12px rgba(0,0,0,.08);
      padding:20px;
      margin-bottom:20px;
      transition:.3s;
    }
    .card:hover {
      transform:translateY(-4px);
      box-shadow:0 6px 16px rgba(0,0,0,.12);
    }

    .grid {
      display:grid;
      grid-template-columns: repeat(auto-fill, minmax(240px,1fr));
      gap:20px;
    }

    .btn {
      background:#1abc9c;
      color:#fff;
      padding:8px 14px;
      border-radius:6px;
      text-decoration:none;
      font-weight:600;
      transition:background .3s;
      display:inline-block;
    }
    .btn:hover { background:#16a085; }

    form.filter-bar {
      display:flex;
      flex-wrap:wrap;
      gap:12px;
      margin-bottom:20px;
      align-items:center;
    }
    form input {
      padding:10px;
      border:1px solid #ccc;
      border-radius:6px;
      font-size:14px;
      flex:1;
    }
    form button {
      background:#1abc9c;
      color:#fff;
      border:none;
      padding:10px 16px;
      border-radius:6px;
      font-weight:600;
      cursor:pointer;
    }
    form button:hover {
      background:#16a085;
    }

    .product-card img {
      width:100%;
      height:180px;
      object-fit:cover;
      border-radius:8px;
      margin-bottom:12px;
    }
    .product-card h3 {
      font-size:18px;
      margin-bottom:6px;
    }
    .product-card p {
      font-size:16px;
      margin-bottom:10px;
      color:#34495e;
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

<div class="card">
  <h2>Productos</h2>
  <form method="get" class="filter-bar">
    <input name="q" placeholder="Buscar por nombre o descripción..." value="<?= e($q) ?>">
    <input name="category" placeholder="Categoría" value="<?= e($category) ?>">
    <button type="submit">Filtrar</button>
  </form>

  <div class="grid">
    <?php while ($p = $res->fetch_assoc()): ?>
      <div class="card product-card">
        <?php if ($p['image_path']): ?>
          <img src="<?= e($p['image_path']); ?>" alt="Imagen de producto">
        <?php endif; ?>
        <h3><?= e($p['title']); ?></h3>
        <p>$<?= number_format($p['price'],2); ?></p>
        <a class="btn" href="/TIENDA/public/product_view.php?id=<?= e($p['id']); ?>">Ver</a>
      </div>
    <?php endwhile; $stmt->close(); ?>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
