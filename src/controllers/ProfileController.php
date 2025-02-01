<?php
namespace App\controllers;
session_start();

use App\repository\UserRepository;

require_once 'AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class ProfileController extends AppController {

    public function index() {
        if (!isset($_SESSION['user'])) {
            header("Location: login");
            exit();
        }

        $userRepository = new UserRepository();
        $email = $_SESSION['user'];
        $user = $userRepository->getUser($email);
        if (!$user) {
            header("Location: login");
            exit();
        }

        $stats = $userRepository->getUserStats($user->getId());

        $this->render('profil', [
            'user'  => $user,
            'stats' => $stats
        ]);
    }
}
