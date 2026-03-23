<?php
class Router {
    private $routes = [];

    
    public function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch($method, $uri) {
        foreach ($this->routes as $route) {
            if ($route['method'] === strtoupper($method) && preg_match($this->convertPathToRegex($route['path']), $uri, $matches)) {
                array_shift($matches); // Remove the full match
                return $this->call_user_func_array($route['handler'], $matches);
            }
        }
        http_response_code(404);
        echo json_encode(["message" => "Endpoint Not Found"]);
    }

    private function convertPathToRegex($path) {
        return '#^' . preg_replace('/\{(\w+)\}/', '(?P<\1>[^/]+)', $path) . '$#';
    }

    private function call_user_func_array ($handler, $params) {
        if (is_array($handler)) {
            $className = $handler[0];
            $methodName = $handler[1];
            if (class_exists($className) && method_exists($className, $methodName)) {
                $controller = new $className();
               // var_dump($params,$controller,$methodName);
                return  $controller->$methodName($params);
            }
        }
        http_response_code(500);
        echo json_encode(["message" => "Handler Not Found"]);
    }
}