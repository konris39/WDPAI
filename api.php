<?php

session_start();
header('Content-Type: application/json');

require 'src/controllers/ApiController.php';

$action = $_GET['action'] ?? '';

$apiController = new \App\controllers\ApiController();

switch ($action) {
    case 'lists':
        $apiController->lists();
        break;
    case 'addItem':
        $apiController->addItem();
        break;
    case 'finalize':
        $apiController->finalize();
        break;
    case 'deleteList':
        $apiController->deleteList();
        break;
    case 'deleteItem':
        $apiController->deleteItem();
        break;
    default:
        echo json_encode(['error' => 'Nieznana akcja']);
        break;
}
