<?php
require_once __DIR__ . '/../config.php';
requireRole(['Administrator']);

require_once __DIR__ . '/../includes/header.php';


$totalUsers = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalBooks = (int)$pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
$totalTransactions = (int)$pdo->query("SELECT COUNT(*) FROM transactions")->fetchColumn();
?>
<h1 class="mb-4">Panel de Administrador</h1>

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card text-bg-primary h-100">
            <div class="card-body">
                <h5 class="card-title">Usuarios</h5>
                <p class="card-text display-6"><?= $totalUsers ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-bg-success h-100">
            <div class="card-body">
                <h5 class="card-title">Libros</h5>
                <p class="card-text display-6"><?= $totalBooks ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-bg-secondary h-100">
            <div class="card-body">
                <h5 class="card-title">Transacciones</h5>
                <p class="card-text display-6"><?= $totalTransactions ?></p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
