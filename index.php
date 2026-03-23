
<?php

header("Access-Control-allow-origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

require __DIR__ . '/core/Router.php';
require __DIR__ . '/controller/UserController.php';

$router = new Router();
$router->addRoute('GET', '/api/users', ['UserController', 'getUser']);
$router->addRoute('GET', '/api/users/{id}', ['UserController', 'getUserById']);
$router->addRoute('POST', 'api/users', ['UserController', 'createUser']);
$router->addRoute('PUT', '/api/users/{id}', ['UserController', 'putUser']);
$router->addRoute('PATCH', '/api/users/{id}', ['UserController', 'patchUser']);
$router->addRoute('DELETE', '/api/users/{id}', ['UserController', 'deleteUser']);
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

?>    

