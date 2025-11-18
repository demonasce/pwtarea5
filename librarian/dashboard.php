<?php
require_once __DIR__ . '/../config.php';
requireRole(['Librarian', 'Administrator']);

require_once __DIR__ . '/../includes/header.php';

$totalBooks = (int)$pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
$availableBooks = (int)$pdo->query("SELECT COALESCE(SUM(quantity),0) FROM books")->fetchColumn();
$openLoans = (int)$pdo->query("SELECT COUNT(*) FROM transactions WHERE date_of_return IS NULL")->fetchColumn();
?>
<h1 class="mb-4">Panel de Bibliotecario</h1>

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card text-bg-primary h-100">
            <div class="card-body">
                <h5 class="card-title">Libros registrados</h5>
                <p class="card-text display-6"><?= $totalBooks ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-bg-success h-100">
            <div class="card-body">
                <h5 class="card-title">Ejemplares disponibles</h5>
                <p class="card-text display-6"><?= $availableBooks ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-bg-warning h-100">
            <div class="card-body">
                <h5 class="card-title">Préstamos abiertos</h5>
                <p class="card-text display-6"><?= $openLoans ?></p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
