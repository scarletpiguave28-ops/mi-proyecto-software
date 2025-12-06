<?php
// includes/auth.php
function require_login() {
  if (empty($_SESSION['user'])) {
    header('Location: /TIENDA/public/login.php');
    exit;
  }
}

function require_role($role) {
  require_login();
  if ($_SESSION['user']['role'] !== $role) {
    header('Location: /TIENDA/public/dashboard.php');
    exit;
  }
}
