<?php

require_once 'src/controllers/AppController.php';
require_once 'src/controllers/DefaultController.php';
require_once 'src/controllers/SecurityController.php';

class Routing {

    public static $routes = [];

    public static function get($url, $controller) {
        self::$routes[$url]['GET'] = $controller;
    }

    public static function post($url, $controller) {
        self::$routes[$url]['POST'] = $controller;
    }

    public static function run($url) {
        $url = trim(parse_url($url, PHP_URL_PATH), '/');
        $method = $_SERVER['REQUEST_METHOD'];

        // DEBUG: Wyłącz wyświetlanie URL i metody (to mogło powodować błąd headers already sent)
        // echo "<pre>DEBUG: Przetwarzany URL: $url (Metoda: $method)</pre>";

        if (!isset(self::$routes[$url])) {
            // die("<pre>ERROR: Nieznana ścieżka: '$url'</pre>");
            http_response_code(404);
            exit();
        }

        if (!isset(self::$routes[$url][$method])) {
            // die("<pre>ERROR: Niewłaściwa metoda żądania dla '$url'! Oczekiwano " . implode(", ", array_keys(self::$routes[$url])) . ", otrzymano $method.</pre>");
            http_response_code(405);
            exit();
        }

        $controllerName = self::$routes[$url][$method];

        if (!class_exists($controllerName)) {
            // die("<pre>ERROR: Kontroler '$controllerName' nie istnieje!</pre>");
            http_response_code(500);
            exit();
        }

        $controller = new $controllerName();
        $action = $url ?: 'index';

        if (!method_exists($controller, $action)) {
            // die("<pre>ERROR: Metoda '$action' nie istnieje w '$controllerName'!</pre>");
            http_response_code(500);
            exit();
        }

        $controller->$action();
    }
}
