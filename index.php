<?php
require 'Routing.php';

Routing::get('', 'ListController', 'index');
Routing::get('index', 'ListController', 'index');


Routing::get('createList', 'ListController', 'create');
Routing::post('create', 'ListController', 'create');
Routing::post('delete', 'ListController', 'delete');
Routing::post('finalize', 'ListController', 'finalize');
Routing::post('addItem', 'ListController', 'addItem');
Routing::post('deleteItem', 'ListController', 'deleteItem');
Routing::post('add', 'ListItemController', 'add');
Routing::post('remove', 'ListItemController', 'remove');

Routing::get('login', 'DefaultController', 'login');
Routing::post('login', 'SecurityController', 'login');
Routing::get('register', 'DefaultController', 'register');
Routing::post('register', 'SecurityController', 'register');

Routing::get('profil', 'ProfileController', 'index');

Routing::post('changePassword', 'SecurityController', 'changePassword');
Routing::post('deleteAccount', 'SecurityController', 'deleteAccount');

Routing::get('logout', 'SecurityController', 'logout');

Routing::get('api/lists', 'ApiController', 'lists');
Routing::post('api/addItem', 'ApiController', 'addItem');
Routing::post('api/finalize', 'ApiController', 'finalize');
Routing::post('api/deleteList', 'ApiController', 'deleteList');
Routing::post('api/deleteItem', 'ApiController', 'deleteItem');

Routing::run($_SERVER['REQUEST_URI']);
