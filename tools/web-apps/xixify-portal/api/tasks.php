<?php
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $projectId = $_GET['project_id'] ?? null;
    echo json_encode([
        'status' => 'success',
        'tasks' => [
            ['id' => 't1', 'title' => 'Project Kickoff', 'status' => 'Completed'],
            ['id' => 't2', 'title' => 'Milestone 1 Delivery', 'status' => 'In Progress']
        ]
    ]);
    exit;
}
