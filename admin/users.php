<?php
require_once __DIR__ . '/../config.php';
requireRole(['Administrator']);

require_once __DIR__ . '/../includes/header.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role_id  = (int)($_POST['role_id'] ?? 3);

    if ($username && $email && $password) {
        $stmt = $pdo->prepare("
            INSERT INTO users (username, email, password, role_id)
            VALUES (:u, :e, :p, :r)
        ");
        $stmt->execute([
            'u' => $username,
            'e' => $email,
            'p' => $password, 
            'r' => $role_id,
        ]);
        echo '<div class="alert alert-success">Usuario creado.</div>';
    } else {
        echo '<div class="alert alert-danger">Todos los campos son obligatorios.</div>';
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        echo '<div class="alert alert-warning">Usuario eliminado.</div>';
    }
}

$users = $pdo->query("
    SELECT u.id, u.username, u.email, r.name AS role_name
    FROM users u
    JOIN roles r ON r.id = u.role_id
    ORDER BY u.id DESC
")->fetchAll(PDO::FETCH_ASSOC);

$roles = $pdo->query("SELECT id, name FROM roles")->fetchAll(PDO::FETCH_ASSOC);
?>
<h1 class="mb-4">Administración de Usuarios</h1>

<div class="row">
    <div class="col-md-4">
        <h2 class="h5">Nuevo usuario</h2>
        <form method="post" class="card card-body mb-4">
            <input type="hidden" name="action" value="create">
            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Correo</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="text" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Rol</label>
                <select name="role_id" class="form-select">
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['id'] ?>"><?= htmlspecialchars($role['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button class="btn btn-success w-100">Crear</button>
        </form>
    </div>

    <div class="col-md-8">
        <h2 class="h5">Listado de usuarios</h2>
        <table class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th style="width:120px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['username']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= htmlspecialchars($u['role_name']) ?></td>
                        <td>
                            <?php if ($u['id'] !== ($_SESSION['user_id'] ?? 0)): ?>
                                <form method="post" onsubmit="return confirm('¿Eliminar usuario?');" class="d-inline">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                    <button class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted small">Tú</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$users): ?>
                    <tr><td colspan="5" class="text-center">Sin usuarios</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
