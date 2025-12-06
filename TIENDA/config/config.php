<?php
// config/config.php
session_start();

$DB_HOST = 'localhost';
$DB_USER = 'root';      // ajusta si tienes otro usuario
$DB_PASS = '';          // coloca tu contraseña si la tienes
$DB_NAME = 'tienda_db';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    die('Error de conexión: ' . $mysqli->connect_error);
}

// Función helper para sanitizar
function e($str) { return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8'); }
