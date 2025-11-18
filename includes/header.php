<?php
require_once __DIR__ . '/../config.php';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Biblioteca Online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/styles.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= BASE_URL ?>/index.php">Biblioteca</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Alternar navegación">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if (isLoggedIn()): ?>
            <?php if (userRole() === 'Administrator'): ?>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/dashboard.php">Panel Admin</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/users.php">Usuarios</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/transactions.php">Transacciones</a></li>
            <?php elseif (userRole() === 'Librarian'): ?>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/librarian/dashboard.php">Panel Bibliotecario</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/librarian/books.php">Libros</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/librarian/loans.php">Préstamos</a></li>
            <?php elseif (userRole() === 'Reader'): ?>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/reader/dashboard.php">Panel Lector</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/reader/catalog.php">Catálogo</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/reader/my_loans.php">Mis préstamos</a></li>
            <?php endif; ?>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if (isLoggedIn()): ?>
            <li class="nav-item">
                <span class="navbar-text me-3">
                    <?= htmlspecialchars($_SESSION['username'] ?? '') ?> (<?= htmlspecialchars(userRole() ?? '') ?>)
                </span>
            </li>
            <li class="nav-item">
                <a class="btn btn-outline-light btn-sm" href="<?= BASE_URL ?>/auth/logout.php">Salir</a>
            </li>
        <?php else: ?>
            <li class="nav-item">
                <a class="btn btn-outline-light btn-sm" href="<?= BASE_URL ?>/auth/login.php">Ingresar</a>
            </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container py-4">
