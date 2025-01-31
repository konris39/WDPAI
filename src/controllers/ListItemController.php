<?php
namespace App\controllers;

use App\repository\ListItemRepository;
use App\repository\ListRepository;
use App\repository\UserRepository;

require_once __DIR__.'/../repository/ListItemRepository.php';
require_once __DIR__.'/../repository/ListRepository.php';
require_once __DIR__.'/../repository/UserRepository.php';

class ListItemController extends AppController {

    private ListItemRepository $listItemRepo;
    private ListRepository $listRepo;
    private UserRepository $userRepo;

    public function __construct()
    {
        parent::__construct();
        $this->listItemRepo = new ListItemRepository();
        $this->listRepo = new ListRepository();
        $this->userRepo = new UserRepository();
    }

    public function add()
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: login");
            exit();
        }

        if (!$this->isPost()) {
            http_response_code(405);
            exit();
        }

        $listId = $_POST['listId'] ?? null;
        $itemName = trim($_POST['itemName'] ?? '');
        $quantity = $_POST['quantity'] ?? 1;
        $price = $_POST['price'] ?? 0.0;

        $errors = [];
        if (!$listId) {
            $errors[] = 'ID listy jest wymagane.';
        }
        if (empty($itemName)) {
            $errors[] = 'Nazwa elementu jest wymagana.';
        }
        if (!filter_var($quantity, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
            $errors[] = 'Ilość musi być liczbą całkowitą większą lub równą 1.';
        }
        if (!filter_var($price, FILTER_VALIDATE_FLOAT) || $price < 0) {
            $errors[] = 'Cena musi być liczbą dodatnią.';
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode(' ', $errors);
            header("Location: index");
            exit();
        }

        $user = $this->userRepo->getUser($_SESSION['user']);
        if (!$user) {
            header("Location: login");
            exit();
        }

        $list = $this->listRepo->getListById((int)$listId);
        if (!$list) {
            $_SESSION['error'] = 'Lista zakupów nie istnieje.';
            header("Location: index");
            exit();
        }

        if ($list->getUserId() !== $user->getId()) {
            http_response_code(403);
            exit();
        }

        $this->listItemRepo->addItem(
            (int)$listId,
            htmlspecialchars($itemName, ENT_QUOTES, 'UTF-8'),
            (int)$quantity,
            (float)$price
        );

        header("Location: index");
        exit();
    }

    public function remove()
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: login");
            exit();
        }

        if (!$this->isPost()) {
            http_response_code(405);
            exit();
        }

        $itemId = $_POST['itemId'] ?? null;

        if (!$itemId || !filter_var($itemId, FILTER_VALIDATE_INT)) {
            $_SESSION['error'] = 'Nieprawidłowe ID elementu.';
            header("Location: index");
            exit();
        }

        $item = $this->listItemRepo->getItemById((int)$itemId);
        if (!$item) {
            $_SESSION['error'] = 'Element nie istnieje.';
            header("Location: index");
            exit();
        }

        $user = $this->userRepo->getUser($_SESSION['user']);
        if (!$user) {
            header("Location: login");
            exit();
        }

        $list = $this->listRepo->getListById($item->getShoppingListId());
        if (!$list) {
            $_SESSION['error'] = 'Lista zakupów nie istnieje.';
            header("Location: index");
            exit();
        }

        if ($list->getUserId() !== $user->getId()) {
            http_response_code(403);
            exit();
        }

        $this->listItemRepo->deleteItem((int)$itemId);

        header("Location: index");
        exit();
    }
}
