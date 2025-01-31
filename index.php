<?php
require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);


Routing::get('', 'ListController', 'index');
Routing::get('createList', 'ListController', 'create');
Routing::post('create', 'ListController', 'create');
Routing::post('delete', 'ListController', 'delete');
Routing::post('finalize', 'ListController', 'finalize');
Routing::post('addItem', 'ListController', 'addItem');
Routing::post('deleteItem', 'ListController', 'deleteItem');
Routing::post('add', 'ListItemController', 'add');
Routing::post('remove', 'ListItemController', 'remove');

Routing::get('index', 'DefaultController', 'index');
Routing::get('login', 'DefaultController', 'login');
Routing::post('login', 'SecurityController', 'login');
Routing::get('profil', 'DefaultController', 'profil');
Routing::get('register', 'DefaultController', 'register');
Routing::post('register', 'SecurityController', 'register');

Routing::run($path);
