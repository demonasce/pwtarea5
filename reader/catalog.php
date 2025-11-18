<?php
require_once __DIR__ . '/../config.php';
requireRole(['Reader']);

require_once __DIR__ . '/../includes/header.php';

$search = trim($_GET['q'] ?? '');

if ($search !== '') {
    $stmt = $pdo->prepare("
        SELECT * FROM books
        WHERE title LIKE :q OR author LIKE :q OR genre LIKE :q
        ORDER BY title
    ");
    $stmt->execute(['q' => '%' . $search . '%']);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $books = $pdo->query("SELECT * FROM books ORDER BY title")->fetchAll(PDO::FETCH_ASSOC);
}
?>
<h1 class="mb-4">Catálogo de Libros</h1>

<form method="get" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="q" class="form-control" placeholder="Buscar por título, autor o género" value="<?= htmlspecialchars($search) ?>">
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary w-100">Buscar</button>
    </div>
    <div class="col-md-2">
        <a href="<?= BASE_URL ?>/reader/catalog.php" class="btn btn-outline-secondary w-100">Limpiar</a>
    </div>
</form>

<table class="table table-bordered table-striped align-middle">
    <thead>
        <tr>
            <th>#</th>
            <th>Título</th>
            <th>Autor</th>
            <th>Año</th>
            <th>Género</th>
            <th>Cantidad</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($books as $b): ?>
            <tr>
                <td><?= $b['id'] ?></td>
                <td><?= htmlspecialchars($b['title']) ?></td>
                <td><?= htmlspecialchars($b['author']) ?></td>
                <td><?= htmlspecialchars($b['year']) ?></td>
                <td><?= htmlspecialchars($b['genre']) ?></td>
                <td><?= $b['quantity'] ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$books): ?>
            <tr><td colspan="6" class="text-center">No se encontraron libros</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<p class="text-muted small">
    * En esta versión, los préstamos los registra el bibliotecario. Este catálogo es sólo de consulta para el lector.
</p>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
