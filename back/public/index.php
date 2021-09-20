<?php

ini_set('display_errors', true);

// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header('Access-Control-Allow-Methods: GET, POST, PUT');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: token, Content-Type, Authorization');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// $ret = [
//     'result' => 'OK',
// ];
// print json_encode($ret);


require_once '../vendor/autoload.php';

use App\Controllers\MainController;
use App\Controllers\UserController;

/* ------------
--- ROUTAGE ---
-------------*/

$router = new AltoRouter();
// le répertoire (après le nom de domaine) dans lequel on travaille est celui-ci
// Mais on pourrait travailler sans sous-répertoire
// Si il y a un sous-répertoire
if (array_key_exists('BASE_URI', $_SERVER)) {
    // Alors on définit le basePath d'AltoRouter
    $router->setBasePath($_SERVER['BASE_URI']);
    // ainsi, nos routes correspondront à l'URL, après la suite de sous-répertoire
}
// sinon_
else {
    // On donne une valeur par défaut à $_SERVER['BASE_URI'] car c'est utilisé dans le CoreController
    $_SERVER['BASE_URI'] = '';
}

// $router->map(
//     'GET',
//     '/',
//     [
//         'method' => 'home',
//         'controller' => MainController::class
//     ],
//     'main-home'
// );

$router->map(
    'POST',
    '/register',
    [
        'method' => 'register',
        'controller' => UserController::class,
    ],
    'register'
);

$router->map(
    'POST',
    '/login',
    [
        'method' => 'login',
        'controller' => UserController::class,
    ],
    'login'
);

$router->map(
    'GET',
    '/checktoken',
    [
        'method'=>'checkToken',
        'controller'=>UserController::class,
    ],
    'checkToken'
);
/* -------------
--- DISPATCH ---
--------------*/

$match = $router->match();

if ($match) {
    $controllerToUse = $match['target']['controller'];
    $methodToUse =  $match['target']['method'];
    $controller = new $controllerToUse();
    $controller->$methodToUse();
} else {
    http_response_code(404);
    echo 'mange ta maman';
    exit;
}


// $dispatcher = new Dispatcher($match, '\App\Controllers\ErrorController::err404');

// $dispatcher->dispatch();
