<?php

require_once 'AppController.php';
class DefaultController extends AppController {

    public function index() {
        $this->render('index');
    }
    public function login() {
        $this->render('login');
    }

    public function profil(){
        $this->render('profil');
    }
    public function register() {
        $this->render('register');
    }
}