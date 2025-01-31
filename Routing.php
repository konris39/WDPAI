<?php

require_once 'src/controllers/AppController.php';
require_once 'src/controllers/DefaultController.php';
require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/ListController.php';
require_once 'src/controllers/ListItemController.php';

class Routing {

    public static $routes = [];

    public static function get($url, $controller, $method = 'index') {
        self::$routes[$url]['GET'] = ['controller' => $controller, 'method' => $method];
    }

    public static function post($url, $controller, $method) {
        self::$routes[$url]['POST'] = ['controller' => $controller, 'method' => $method];
    }

    public static function run($url) {
        $url = trim(parse_url($url, PHP_URL_PATH), '/');
        $method = $_SERVER['REQUEST_METHOD'];

        if (!isset(self::$routes[$url])) {
            http_response_code(404);
            echo "404 Not Found";
            exit();
        }

        if (!isset(self::$routes[$url][$method])) {
            http_response_code(405);
            echo "405 Method Not Allowed";
            exit();
        }

        $route = self::$routes[$url][$method];
        $controllerName = 'App\\controllers\\' . $route['controller'];
        $action = $route['method'];

        if (!class_exists($controllerName)) {
            http_response_code(500);
            echo "500 Internal Server Error: Controller $controllerName not found.";
            exit();
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $action)) {
            http_response_code(500);
            echo "500 Internal Server Error: Method $action not found in controller $controllerName.";
            exit();
        }

        try {
            $controller->$action();
        } catch (Exception $e) {
            http_response_code(500);
            echo "500 Internal Server Error: " . $e->getMessage();
            exit();
        }
    }
}
