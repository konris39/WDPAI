<?php
namespace App\controllers;

require_once 'AppController.php';
require_once __DIR__.'/../repository/ListRepository.php';
require_once __DIR__.'/../repository/ListItemRepository.php';
require_once __DIR__.'/../repository/UserRepository.php';

class ApiController extends AppController {

    private $listRepo;
    private $listItemRepo;
    private $userRepo;

    public function __construct() {
        parent::__construct();
        $this->listRepo = new \App\repository\ListRepository();
        $this->listItemRepo = new \App\repository\ListItemRepository();
        $this->userRepo = new \App\repository\UserRepository();
    }

    public function lists() {
        header('Content-Type: application/json');
        session_start();
        if (!isset($_SESSION['user'])) {
            echo json_encode(['error' => 'Brak autoryzacji']);
            exit;
        }
        $user = $this->userRepo->getUser($_SESSION['user']);
        if (!$user) {
            echo json_encode(['error' => 'Użytkownik nie znaleziony']);
            exit;
        }

        $lists = $this->listRepo->getListsByUser($user->getId());
        $result = ['pending' => [], 'finalized' => []];
        foreach ($lists as $list) {
            $items = $this->listItemRepo->getItemsByList($list->getId());
            $listData = [
                'id' => $list->getId(),
                'listName' => $list->getListName(),
                'totalCost' => $list->getTotalCost(),
                'items' => []
            ];
            foreach ($items as $item) {
                $listData['items'][] = [
                    'id' => $item->getId(),
                    'itemName' => $item->getItemName(),
                    'quantity' => $item->getQuantity(),
                    'price' => $item->getPrice()
                ];
            }
            if ($list->getStatus() === 'pending') {
                $result['pending'][] = $listData;
            } else {
                $result['finalized'][] = $listData;
            }
        }
        echo json_encode($result);
    }

    public function addItem() {
        header('Content-Type: application/json');
        session_start();
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!isset($data['listId'], $data['itemName'], $data['quantity'], $data['price'])) {
            echo json_encode(['error' => 'Brak wymaganych danych']);
            exit;
        }

        if (!isset($_SESSION['user'])) {
            echo json_encode(['error' => 'Brak autoryzacji']);
            exit;
        }
        $user = $this->userRepo->getUser($_SESSION['user']);
        if (!$user) {
            echo json_encode(['error' => 'Użytkownik nie znaleziony']);
            exit;
        }

        $list = $this->listRepo->getListById((int)$data['listId']);
        if (!$list || $list->getUserId() !== $user->getId()) {
            echo json_encode(['error' => 'Nie masz uprawnień lub lista nie istnieje']);
            exit;
        }

        $this->listItemRepo->addItem(
            (int)$data['listId'],
            htmlspecialchars($data['itemName'], ENT_QUOTES, 'UTF-8'),
            (int)$data['quantity'],
            (float)$data['price']
        );

        echo json_encode(['status' => 'success']);
    }

    public function finalize() {
        header('Content-Type: application/json');
        session_start();
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if (!isset($data['listId'])) {
            echo json_encode(['error' => 'Brak ID listy']);
            exit;
        }

        if (!isset($_SESSION['user'])) {
            echo json_encode(['error' => 'Brak autoryzacji']);
            exit;
        }
        $user = $this->userRepo->getUser($_SESSION['user']);
        if (!$user) {
            echo json_encode(['error' => 'Użytkownik nie znaleziony']);
            exit;
        }

        $list = $this->listRepo->getListById((int)$data['listId']);
        if (!$list || $list->getUserId() !== $user->getId()) {
            echo json_encode(['error' => 'Nie masz uprawnień lub lista nie istnieje']);
            exit;
        }
        $this->listRepo->finalizeList((int)$data['listId']);
        echo json_encode(['status' => 'success']);
    }

    public function deleteList() {
        header('Content-Type: application/json');
        session_start();
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if (!isset($data['listId'])) {
            echo json_encode(['error' => 'Brak ID listy']);
            exit;
        }

        if (!isset($_SESSION['user'])) {
            echo json_encode(['error' => 'Brak autoryzacji']);
            exit;
        }
        $user = $this->userRepo->getUser($_SESSION['user']);
        if (!$user) {
            echo json_encode(['error' => 'Użytkownik nie znaleziony']);
            exit;
        }

        $list = $this->listRepo->getListById((int)$data['listId']);
        if (!$list || $list->getUserId() !== $user->getId()) {
            echo json_encode(['error' => 'Nie masz uprawnień lub lista nie istnieje']);
            exit;
        }

        $this->listRepo->deleteList((int)$data['listId']);
        echo json_encode(['status' => 'success']);
    }

    public function deleteItem() {
        header('Content-Type: application/json');
        session_start();
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if (!isset($data['itemId'])) {
            echo json_encode(['error' => 'Brak ID elementu']);
            exit;
        }

        if (!isset($_SESSION['user'])) {
            echo json_encode(['error' => 'Brak autoryzacji']);
            exit;
        }
        $user = $this->userRepo->getUser($_SESSION['user']);
        if (!$user) {
            echo json_encode(['error' => 'Użytkownik nie znaleziony']);
            exit;
        }

        $item = $this->listItemRepo->getItemById((int)$data['itemId']);
        if (!$item) {
            echo json_encode(['error' => 'Element nie istnieje']);
            exit;
        }

        $list = $this->listRepo->getListById($item->getShoppingListId());
        if (!$list || $list->getUserId() !== $user->getId()) {
            echo json_encode(['error' => 'Nie masz uprawnień do usunięcia tego elementu']);
            exit;
        }

        $this->listItemRepo->deleteItem((int)$data['itemId']);
        echo json_encode(['status' => 'success']);
    }

}
