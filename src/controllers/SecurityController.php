<?php
session_start(); // To musi być pierwsza linijka!

use App\repository\UserRepository;

require_once 'AppController.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../repository/UserRepository.php';

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
}
