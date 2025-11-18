<?php

define('BASE_URL', 'http://localhost/biblioteca_online');

$host = 'localhost';
$db   = 'biblioteca_online';
$user = 'root';
$pass = ''; 

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---------- Helpers de sesión / rol ----------

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function userRole(): ?string {
    return $_SESSION['role_name'] ?? null;
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/auth/login.php');
        exit;
    }
}


function requireRole(array $roles): void {
    requireLogin();

    $currentRole = userRole();

    if (!in_array($currentRole, $roles)) {
        http_response_code(403);
        echo "<h1>403</h1>";
        echo "<p>No tienes permiso para acceder a esta sección.</p>";

      
        echo "<hr>";
        echo "<p>Rol actual en sesión: <strong>" . htmlspecialchars($currentRole ?? 'NULL') . "</strong></p>";
        echo "<p>Roles permitidos en esta página: <strong>" . implode(', ', $roles) . "</strong></p>";

        echo "<pre>";
        echo '$_SESSION = ' . print_r($_SESSION, true);
        echo "</pre>";

        exit;
    }
}


function redirectHomeByRole(): void {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/auth/login.php');
        exit;
    }

    switch (userRole()) {
        case 'Administrator':
            header('Location: ' . BASE_URL . '/admin/dashboard.php');
            break;

        case 'Librarian':
            header('Location: ' . BASE_URL . '/librarian/dashboard.php');
            break;

        case 'Reader':
        default:
            header('Location: ' . BASE_URL . '/reader/dashboard.php');
            break;
    }

    exit;
}
