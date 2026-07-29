<?php
require_once 'config.php';

$dataFile = __DIR__ . '/storage.json';

function get_projects_data($dataFile) {
    if (file_exists($dataFile)) {
        return json_decode(file_get_contents($dataFile), true);
    }
    return [];
}

function save_projects_data($dataFile, $data) {
    file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $projects = get_projects_data($dataFile);
    $isClientView = isset($_GET['client']);

    if ($isClientView) {
        // Sanitize out internal financial splits for public client views
        $projects = array_map(function($p) {
            if (!empty($p['clientVisible'])) {
                unset($p['expenses']);
                unset($p['distributed']);
            } else {
                return null;
            }
            return $p;
        }, $projects);

        $projects = array_values(array_filter($projects));
    }

    echo json_encode(['status' => 'success', 'data' => $projects]);
    exit;
}

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $projects = get_projects_data($dataFile);

    $input['id'] = 'proj-' . time();
    array_unshift($projects, $input);

    save_projects_data($dataFile, $projects);
    echo json_encode(['status' => 'success', 'data' => $input]);
    exit;
}
