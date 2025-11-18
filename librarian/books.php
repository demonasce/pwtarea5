<?php
require_once __DIR__ . '/../config.php';
requireRole(['Librarian', 'Administrator']);

require_once __DIR__ . '/../includes/header.php';

// Crear libro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
    $title    = trim($_POST['title'] ?? '');
    $author   = trim($_POST['author'] ?? '');
    $year     = (int)($_POST['year'] ?? 0);
    $genre    = trim($_POST['genre'] ?? '');
    $quantity = (int)($_POST['quantity'] ?? 0);

    if ($title && $author && $quantity >= 0) {
        $stmt = $pdo->prepare("
            INSERT INTO books (title, author, year, genre, quantity)
            VALUES (:t, :a, :y, :g, :q)
        ");
        $stmt->execute([
            't' => $title,
            'a' => $author,
            'y' => $year ?: null,
            'g' => $genre,
            'q' => $quantity,
        ]);
        echo '<div class="alert alert-success">Libro creado.</div>';
    } else {
        echo '<div class="alert alert-danger">Título, autor y cantidad son obligatorios.</div>';
    }
}

// Eliminar libro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $stmt = $pdo->prepare("DELETE FROM books WHERE id = :id");
        $stmt->execute(['id' => $id]);
        echo '<div class="alert alert-warning">Libro eliminado.</div>';
    }
}

$books = $pdo->query("SELECT * FROM books ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<h1 class="mb-4">Gestión de Libros</h1>

<div class="row">
    <div class="col-md-4">
        <h2 class="h5">Nuevo libro</h2>
        <form method="post" class="card card-body mb-4">
            <input type="hidden" name="action" value="create">
            <div class="mb-3">
                <label class="form-label">Título</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Autor</label>
                <input type="text" name="author" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Año</label>
                <input type="number" name="year" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Género</label>
                <input type="text" name="genre" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Cantidad</label>
                <input type="number" name="quantity" class="form-control" required>
            </div>
            <button class="btn btn-success w-100">Guardar</button>
        </form>
    </div>

    <div class="col-md-8">
        <h2 class="h5">Catálogo</h2>
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Año</th>
                    <th>Género</th>
                    <th>Cantidad</th>
                    <th style="width:120px;">Acciones</th>
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
                        <td>
                            <form method="post" onsubmit="return confirm('¿Eliminar libro?');" class="d-inline">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                <button class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$books): ?>
                    <tr><td colspan="7" class="text-center">Sin libros</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
