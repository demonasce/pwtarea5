<?php
require_once __DIR__ . '/../config.php';
requireRole(['Reader']);

require_once __DIR__ . '/../includes/header.php';

$userId = (int)($_SESSION['user_id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT t.id, b.title, t.date_of_issue, t.date_of_return
    FROM transactions t
    JOIN books b ON b.id = t.book_id
    WHERE t.user_id = :u
    ORDER BY t.date_of_issue DESC, t.id DESC
");
$stmt->execute(['u' => $userId]);
$loans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h1 class="mb-4">Mis Préstamos</h1>

<table class="table table-bordered table-striped align-middle">
    <thead>
        <tr>
            <th>#</th>
            <th>Libro</th>
            <th>Fecha Préstamo</th>
            <th>Fecha Devolución</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($loans as $l): ?>
            <tr>
                <td><?= $l['id'] ?></td>
                <td><?= htmlspecialchars($l['title']) ?></td>
                <td><?= htmlspecialchars($l['date_of_issue']) ?></td>
                <td><?= htmlspecialchars($l['date_of_return'] ?? '') ?></td>
                <td>
                    <?php if ($l['date_of_return'] === null): ?>
                        <span class="badge bg-success">Activo</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Devuelto</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$loans): ?>
            <tr><td colspan="5" class="text-center">No tienes préstamos registrados</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
