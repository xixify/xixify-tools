<?php
require_once 'config.php';

session_start();

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $input['username'] ?? '';
    $password = $input['password'] ?? '';

    // Hardcoded initial admin auth (or connect to DB)
    if ($username === 'admin' && $password === 'xixify2026') {
        $_SESSION['authenticated'] = true;
        $_SESSION['role'] = 'admin';
        echo json_encode(['status' => 'success', 'message' => 'Authenticated', 'role' => 'admin']);
    } else {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode([
        'authenticated' => !empty($_SESSION['authenticated']),
        'role' => $_SESSION['role'] ?? 'client'
    ]);
    exit;
}
