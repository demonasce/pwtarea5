<?php
require_once __DIR__ . '/../config.php';
requireRole(['Reader']);

require_once __DIR__ . '/../includes/header.php';

$userId = (int)($_SESSION['user_id'] ?? 0);

$totalLoans = (int)$pdo->prepare("SELECT COUNT(*) FROM transactions WHERE user_id = :u")
    ->execute(['u' => $userId]) ?: 0;

$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM transactions 
    WHERE user_id = :u AND date_of_return IS NULL
");
$stmt->execute(['u' => $userId]);
$openLoans = (int)$stmt->fetchColumn();
?>
<h1 class="mb-4">Panel de Lector</h1>

<div class="row">
    <div class="col-md-6 mb-3">
        <div class="card text-bg-primary h-100">
            <div class="card-body">
                <h5 class="card-title">Préstamos totales</h5>
                <p class="card-text display-6"><?= $totalLoans ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card text-bg-success h-100">
            <div class="card-body">
                <h5 class="card-title">Préstamos activos</h5>
                <p class="card-text display-6"><?= $openLoans ?></p>
            </div>
        </div>
    </div>
</div>

<p class="mt-4">
    Desde aquí puedes ir al <a href="<?= BASE_URL ?>/reader/catalog.php">catálogo</a> o revisar
    <a href="<?= BASE_URL ?>/reader/my_loans.php">tus préstamos</a>.
</p>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
