<?php
require_once __DIR__ . '/../config.php';
requireRole(['Administrator']);

require_once __DIR__ . '/../includes/header.php';

$sql = "
    SELECT t.id, u.username, b.title, t.date_of_issue, t.date_of_return
    FROM transactions t
    JOIN users u ON u.id = t.user_id
    JOIN books b ON b.id = t.book_id
    ORDER BY t.id DESC
";
$transactions = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<h1 class="mb-4">Transacciones</h1>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Lector</th>
            <th>Libro</th>
            <th>Fecha Préstamo</th>
            <th>Fecha Devolución</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($transactions as $t): ?>
            <tr>
                <td><?= $t['id'] ?></td>
                <td><?= htmlspecialchars($t['username']) ?></td>
                <td><?= htmlspecialchars($t['title']) ?></td>
                <td><?= htmlspecialchars($t['date_of_issue']) ?></td>
                <td><?= htmlspecialchars($t['date_of_return'] ?? '') ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$transactions): ?>
            <tr><td colspan="5" class="text-center">Sin transacciones</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
