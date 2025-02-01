<?php

namespace App\controllers;
session_start();

use App\repository\UserRepository;

require_once 'AppController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class SecurityController extends AppController {

    public function login() {
        $userRepository = new UserRepository();

        if (!$this->isPost()) {
            return $this->render('login');
        }

        $email = $_POST["email"];
        $password = $_POST["password"];

        $user = $userRepository->getUser($email);

        if (!$user) {
            return $this->render('login', ['messages' => ['Nie znaleziono użytkownika!']]);
        }

        if (!password_verify($password, $user->getPassword())) {
            return $this->render('login', ['messages' => ['Błędne hasło!']]);
        }

        $_SESSION['user'] = $user->getEmail();

        if (ob_get_length()) {
            ob_end_clean();
        }

        header("Location: index");
        exit();
    }

    public function register() {
        $userRepository = new UserRepository();

        if (!$this->isPost()) {
            return $this->render('register');
        }

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($password !== $confirmPassword) {
            return $this->render('register', ['messages' => ['Hasła nie są identyczne!']]);
        }

        if ($userRepository->getUser($email)) {
            return $this->render('register', ['messages' => ['Użytkownik o tym e-mailu już istnieje!']]);
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $userRepository->createUser($username, $email, $hashedPassword);

        if (ob_get_length()) {
            ob_end_clean();
        }

        header("Location: login");
        exit();
    }

    public function changePassword() {
        if (!$this->isPost()) {
            return $this->render('profil', ['messages' => ['Metoda POST wymagana.']]);
        }

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

        $current_password     = trim($_POST['current_password'] ?? '');
        $new_password         = trim($_POST['new_password'] ?? '');
        $confirm_new_password = trim($_POST['confirm_new_password'] ?? '');

        if (empty($current_password) || empty($new_password) || empty($confirm_new_password)) {
            return $this->render('profil', [
                'user'     => $user,
                'stats'    => $stats,
                'messages' => ['Wszystkie pola są wymagane!']
            ]);
        }

        if ($new_password !== $confirm_new_password) {
            return $this->render('profil', [
                'user'     => $user,
                'stats'    => $stats,
                'messages' => ['Nowe hasła nie są identyczne!']
            ]);
        }

        if (!password_verify($current_password, $user->getPassword())) {
            return $this->render('profil', [
                'user'     => $user,
                'stats'    => $stats,
                'messages' => ['Błędne obecne hasło!']
            ]);
        }

        $newHashedPassword = password_hash($new_password, PASSWORD_BCRYPT);
        $userRepository->updatePassword($user->getId(), $newHashedPassword);

        header("Location: profil?message=Hasło%20zostało%20zmienione");
        exit();
    }

    public function deleteAccount() {
        if (!$this->isPost()) {
            http_response_code(405);
            echo "405 Method Not Allowed";
            exit();
        }

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

        $userRepository->deleteUser($user->getId());
        session_destroy();
        header("Location: login?message=Konto%20zostało%20usunięte");
        exit();
    }

    public function logout() {
        session_destroy();
        header("Location: login");
        exit();
    }

}
