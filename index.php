<?php
require_once __DIR__ . '/config.php';

if (!isLoggedIn()) {
    header('Location: ' . BASE_URL . '/auth/login.php');
    exit;
}

redirectHomeByRole();
