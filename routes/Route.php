<?php 

namespace App\Routes;


class Route {
    private static $routes = [];

    public static function get($url, $controller) {
        self::$routes[] = ['url' => $url, 'controller' => $controller, 'method' => 'GET'];
    }

    public static function post($url, $controller) {
        self::$routes[] = ['url' => $url, 'controller' => $controller, 'method' => 'POST'];
    }

    public static function dispatch() {
        $url = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $urlSegments = explode('?', $url);
        $urlPath = trim($urlSegments[0], '/'); 

        foreach (self::$routes as $route) {
            $routePath = trim(BASE . $route['url'], '/');

            if (self::matchRoute($routePath, $urlPath, $params) && $route['method'] == $method) {
                $controllerSegments = explode('@', $route['controller']);
                $controllerName = "App\\Controllers\\" . $controllerSegments[0];
                $methodName = $controllerSegments[1];

                // Vérifie si le contrôleur existe
                if (!class_exists($controllerName)) {
                    http_response_code(500);
                    echo "Erreur: Contrôleur '$controllerName' introuvable.";
                    return;
                }

                $controllerInstance = new $controllerName();

                // Vérifie si la méthode existe
                if (!method_exists($controllerInstance, $methodName)) {
                    http_response_code(500);
                    echo "Erreur: Méthode '$methodName' introuvable dans le contrôleur '$controllerName'.";
                    return;
                }

                // Gestion des paramètres GET et POST
                if ($method == 'GET') {
                    $queryParams = isset($urlSegments[1]) ? parse_str($urlSegments[1], $queryParams) : [];
                    $controllerInstance->$methodName(array_merge($queryParams, $params));
                } elseif ($method == 'POST') {
                    $postData = $_POST;
                    
                    // Vérifie si le corps de la requête est en JSON
                    if (empty($postData)) {
                        $rawInput = file_get_contents("php://input");
                        $decodedJson = json_decode($rawInput, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $postData = $decodedJson;
                        }
                    }

                    $controllerInstance->$methodName($postData, $params);
                }
                return;
            }
        }

        // Page non trouvée
        http_response_code(404);
        echo "404 Page not found!";
    }

    /**
     * Vérifie si une URL correspond à une route définie, en gérant les paramètres dynamiques {id}, {slug}, etc.
     */
    private static function matchRoute($routePath, $urlPath, &$params) {
        $routeParts = explode('/', $routePath);
        $urlParts = explode('/', $urlPath);

        if (count($routeParts) !== count($urlParts)) {
            return false;
        }

        $params = [];
        foreach ($routeParts as $index => $part) {
            if (preg_match('/\{([a-zA-Z0-9_]+)\}/', $part, $matches)) {
                $params[$matches[1]] = $urlParts[$index]; // Stocke le paramètre dynamique
            } elseif ($part !== $urlParts[$index]) {
                return false;
            }
        }

        return true;
    }
}
