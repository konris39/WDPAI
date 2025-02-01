<?php
namespace App\controllers;

use App\repository\ListItemRepository;
use App\repository\ListRepository;
use App\repository\UserRepository;

require_once __DIR__ . '/../repository/ListItemRepository.php';
require_once __DIR__ . '/../repository/ListRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class ListController extends AppController
{
    private ListRepository $listRepo;
    private ListItemRepository $listItemRepo;
    private UserRepository $userRepo;

    public function __construct()
    {
        parent::__construct();
        // Initialize repositories
        $this->listRepo = new ListRepository();
        $this->listItemRepo = new ListItemRepository();
        $this->userRepo = new UserRepository();
    }

    public function index()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: login");
            exit();
        }

        $user = $this->userRepo->getUser($_SESSION['user']);
        if (!$user) {
            header("Location: login");
            exit();
        }

        $lists = $this->listRepo->getListsByUser($user->getId());

        $listsWithItems = [];

        foreach ($lists as $list) {
            $items = $this->listItemRepo->getItemsByList($list->getId());
            $listsWithItems[] = [
                'list' => $list,
                'items' => $items
            ];
        }

        $this->render('index', ['listsWithItems' => $listsWithItems]);
    }



    public function create()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: login");
            exit();
        }

        if ($this->isPost()) {
            $listName = trim($_POST['listName'] ?? '');

            if (empty($listName)) {
                $_SESSION['error'] = 'Nazwa listy nie może być pusta!';
                header("Location: createList");
                exit();
            }

            $user = $this->userRepo->getUser($_SESSION['user']);
            if (!$user) {
                header("Location: login");
                exit();
            }

            $this->listRepo->createList($user->getId(), $listName);

            $listId = $this->listRepo->getLastInsertId();

            if (!$listId) {
                $_SESSION['error'] = 'Błąd podczas tworzenia listy zakupów.';
                header("Location: createList");
                exit();
            }

            if (isset($_POST['items']) && is_array($_POST['items'])) {
                foreach ($_POST['items'] as $item) {
                    $itemName = trim($item['name'] ?? '');
                    $quantity = (int)($item['quantity'] ?? 1);
                    $price = (float)($item['price'] ?? 0.0);

                    if (!empty($itemName) && $quantity > 0 && $price >= 0) {
                        $this->listItemRepo->addItem(
                            $listId,
                            htmlspecialchars($itemName, ENT_QUOTES, 'UTF-8'),
                            $quantity,
                            $price
                        );
                    }
                }
            }

            header("Location: index");
            exit();
        } else {
            $error = $_SESSION['error'] ?? null;
            unset($_SESSION['error']);
            $this->render('createList', ['error' => $error]);
        }
    }


    public function delete()
    {
        if (!$this->isPost()) {
            http_response_code(405);
            echo "405 Method Not Allowed";
            exit();
        }

        $listId = $_POST['listId'] ?? null;
        if (!$listId) {
            http_response_code(400);
            echo "400 Bad Request: Brak ID listy.";
            exit();
        }

        $list = $this->listRepo->getListById((int)$listId);
        if (!$list) {
            http_response_code(404);
            echo "404 Not Found: Lista nie istnieje.";
            exit();
        }

        $user = $this->userRepo->getUser($_SESSION['user']);
        if ($list->getUserId() !== $user->getId()) {
            http_response_code(403);
            echo "403 Forbidden: Brak uprawnień do usunięcia tej listy.";
            exit();
        }

        $this->listRepo->deleteList((int)$listId);


        header("Location: index");
        exit();
    }

    public function finalize()
    {
        if (!$this->isPost()) {
            http_response_code(405);
            echo "405 Method Not Allowed";
            exit();
        }

        $listId = $_POST['listId'] ?? null;
        if (!$listId) {
            http_response_code(400);
            echo "400 Bad Request: Brak ID listy.";
            exit();
        }

        $list = $this->listRepo->getListById((int)$listId);
        if (!$list) {
            http_response_code(404);
            echo "404 Not Found: Lista nie istnieje.";
            exit();
        }

        $user = $this->userRepo->getUser($_SESSION['user']);
        if ($list->getUserId() !== $user->getId()) {
            http_response_code(403);
            echo "403 Forbidden: Brak uprawnień do finalizacji tej listy.";
            exit();
        }

        $this->listRepo->finalizeList((int)$listId);

        header("Location: index");
        exit();
    }

    public function addItem()
    {
        if (!$this->isPost()) {
            http_response_code(405);
            echo "405 Method Not Allowed";
            exit();
        }

        $listId = $_POST['listId'] ?? null;
        $itemName = trim($_POST['itemName'] ?? '');
        $quantity = $_POST['quantity'] ?? 1;
        $price = $_POST['price'] ?? 0.0;

        if (!$listId || empty($itemName)) {
            $_SESSION['error'] = 'ID listy i nazwa elementu są wymagane.';
            header("Location: index");
            exit();
        }

        if (!is_numeric($quantity) || $quantity < 1) {
            $_SESSION['error'] = 'Ilość musi być liczbą całkowitą większą lub równą 1.';
            header("Location: index");
            exit();
        }

        if (!is_numeric($price) || $price < 0) {
            $_SESSION['error'] = 'Cena musi być liczbą dodatnią.';
            header("Location: index");
            exit();
        }

        $list = $this->listRepo->getListById((int)$listId);
        if (!$list) {
            http_response_code(404);
            echo "404 Not Found: Lista nie istnieje.";
            exit();
        }

        $user = $this->userRepo->getUser($_SESSION['user']);
        if ($list->getUserId() !== $user->getId()) {
            http_response_code(403);
            echo "403 Forbidden: Brak uprawnień do dodawania elementów do tej listy.";
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

    public function deleteItem()
    {
        if (!$this->isPost()) {
            http_response_code(405);
            echo "405 Method Not Allowed";
            exit();
        }

        $itemId = $_POST['itemId'] ?? null;
        if (!$itemId) {
            http_response_code(400);
            echo "400 Bad Request: Brak ID elementu.";
            exit();
        }

        $item = $this->listItemRepo->getItemById((int)$itemId);
        if (!$item) {
            http_response_code(404);
            echo "404 Not Found: Element nie istnieje.";
            exit();
        }

        $listId = $item->getShoppingListId();
        $list = $this->listRepo->getListById($listId);
        if (!$list) {
            http_response_code(404);
            echo "404 Not Found: Lista zakupów nie istnieje.";
            exit();
        }

        $user = $this->userRepo->getUser($_SESSION['user']);
        if ($list->getUserId() !== $user->getId()) {
            http_response_code(403);
            echo "403 Forbidden: Brak uprawnień do usunięcia tego elementu.";
            exit();
        }

        $this->listItemRepo->deleteItem((int)$itemId);

        header("Location: index");
        exit();
    }
}