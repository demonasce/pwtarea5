<?php
require_once __DIR__ . '/../config.php';
requireRole(['Librarian', 'Administrator']);

require_once __DIR__ . '/../includes/header.php';

// Crear préstamo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'loan') {
    $user_id = (int)($_POST['user_id'] ?? 0);
    $book_id = (int)($_POST['book_id'] ?? 0);

    if ($user_id > 0 && $book_id > 0) {
        // Verificar cantidad
        $stmt = $pdo->prepare("SELECT quantity FROM books WHERE id = :id");
        $stmt->execute(['id' => $book_id]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($book && $book['quantity'] > 0) {
            $pdo->beginTransaction();
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO transactions (user_id, book_id, date_of_issue, date_of_return)
                    VALUES (:u, :b, CURDATE(), NULL)
                ");
                $stmt->execute(['u' => $user_id, 'b' => $book_id]);

                $stmt2 = $pdo->prepare("UPDATE books SET quantity = quantity - 1 WHERE id = :id");
                $stmt2->execute(['id' => $book_id]);

                $pdo->commit();
                echo '<div class="alert alert-success">Préstamo registrado.</div>';
            } catch (Exception $e) {
                $pdo->rollBack();
                echo '<div class="alert alert-danger">Error al registrar el préstamo.</div>';
            }
        } else {
            echo '<div class="alert alert-warning">No hay ejemplares disponibles.</div>';
        }
    } else {
        echo '<div class="alert alert-danger">Debes seleccionar lector y libro.</div>';
    }
}

// Registrar devolución
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'return') {
    $transaction_id = (int)($_POST['transaction_id'] ?? 0);
    if ($transaction_id > 0) {
        $pdo->beginTransaction();
        try {
            // Obtener transacción y libro
            $stmt = $pdo->prepare("SELECT book_id FROM transactions WHERE id = :id AND date_of_return IS NULL");
            $stmt->execute(['id' => $transaction_id]);
            $t = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($t) {
                $stmt2 = $pdo->prepare("UPDATE transactions SET date_of_return = CURDATE() WHERE id = :id");
                $stmt2->execute(['id' => $transaction_id]);

                $stmt3 = $pdo->prepare("UPDATE books SET quantity = quantity + 1 WHERE id = :book_id");
                $stmt3->execute(['book_id' => $t['book_id']]);

                $pdo->commit();
                echo '<div class="alert alert-success">Devolución registrada.</div>';
            } else {
                $pdo->rollBack();
                echo '<div class="alert alert-warning">Transacción no encontrada o ya devuelta.</div>';
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            echo '<div class="alert alert-danger">Error al registrar la devolución.</div>';
        }
    }
}

// Datos para selects
$readers = $pdo->query("
    SELECT u.id, u.username
    FROM users u
    JOIN roles r ON r.id = u.role_id
    WHERE r.name = 'Reader'
    ORDER BY u.username
")->fetchAll(PDO::FETCH_ASSOC);

$books = $pdo->query("
    SELECT id, title, quantity
    FROM books
    ORDER BY title
")->fetchAll(PDO::FETCH_ASSOC);

$openLoans = $pdo->query("
    SELECT t.id, u.username, b.title, t.date_of_issue
    FROM transactions t
    JOIN users u ON u.id = t.user_id
    JOIN books b ON b.id = t.book_id
    WHERE t.date_of_return IS NULL
    ORDER BY t.date_of_issue DESC, t.id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<h1 class="mb-4">Préstamos</h1>

<div class="row">
    <div class="col-md-4">
        <h2 class="h5">Nuevo préstamo</h2>
        <form method="post" class="card card-body mb-4">
            <input type="hidden" name="action" value="loan">
            <div class="mb-3">
                <label class="form-label">Lector</label>
                <select name="user_id" class="form-select" required>
                    <option value="">Seleccionar...</option>
                    <?php foreach ($readers as $r): ?>
                        <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['username']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Libro</label>
                <select name="book_id" class="form-select" required>
                    <option value="">Seleccionar...</option>
                    <?php foreach ($books as $b): ?>
                        <option value="<?= $b['id'] ?>">
                            <?= htmlspecialchars($b['title']) ?> (Disp: <?= $b['quantity'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button class="btn btn-success w-100">Registrar préstamo</button>
        </form>
    </div>

    <div class="col-md-8">
        <h2 class="h5">Préstamos abiertos</h2>
        <table class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Lector</th>
                    <th>Libro</th>
                    <th>Fecha préstamo</th>
                    <th style="width:140px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($openLoans as $o): ?>
                    <tr>
                        <td><?= $o['id'] ?></td>
                        <td><?= htmlspecialchars($o['username']) ?></td>
                        <td><?= htmlspecialchars($o['title']) ?></td>
                        <td><?= htmlspecialchars($o['date_of_issue']) ?></td>
                        <td>
                            <form method="post" onsubmit="return confirm('¿Registrar devolución?');" class="d-inline">
                                <input type="hidden" name="action" value="return">
                                <input type="hidden" name="transaction_id" value="<?= $o['id'] ?>">
                                <button class="btn btn-sm btn-primary">Devolver</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$openLoans): ?>
                    <tr><td colspan="5" class="text-center">No hay préstamos abiertos</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
