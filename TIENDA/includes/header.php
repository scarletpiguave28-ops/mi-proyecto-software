<?php require_once __DIR__ . '/../config/config.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tienda</title>
  <link rel="stylesheet" href="/TIENDA/assets/css/styles.css">
</head>
<body>
<header>
  <nav>
    <a href="/TIENDA/public/index.php">Inicio</a>
    <a href="/TIENDA/public/products.php">Productos</a>
    <?php if (!empty($_SESSION['user'])): ?>
      <a href="/TIENDA/public/dashboard.php">Panel</a>
      <a href="/TIENDA/public/logout.php">Salir</a>
    <?php else: ?>
      <a href="/TIENDA/public/login.php">Ingresar</a>
      <a href="/TIENDA/public/register.php">Registro</a>
    <?php endif; ?>
    <a href="/TIENDA/public/cart.php">Carrito</a>
  </nav>
</header>
<main>
