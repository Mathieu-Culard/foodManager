<?php

ini_set('display_errors', 0);

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
use App\Controllers\RecipesController;
use App\Controllers\IngredientsController;
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
//=============CONNECTION============//
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

//=============RECIPES============//
$router->map(
    'GET',
    '/recipes',
    [
        'method' => 'list',
        'controller' => RecipesController::class,
    ],
    'recipes-list'
);

$router->map(
    'GET',
    '/recipe/[i:id]',
    [
        'method' => 'getRecipe',
        'controller' => RecipesController::class,
    ],
    'get-recipe',
);

$router->map(
    'GET',
    '/recipe/my-recipes',
    [
        'method'=>'getMyRecipes',
        'controller'=>RecipesController::class,
    ],
    'get-my-recipes'
);

$router->map(
    'POST',
    '/recipe/add',
    [
        'method' => 'createRecipe',
        'controller' => RecipesController::class,
    ],
    'create-recipe',
);

$router->map(
    'POST',
    '/recipe/edit/[i:id]',
    [
        'method'=>'editRecipe',
        'controller'=>RecipesController::class,
    ],
    'edit-recipe',
);

$router->map(
    'DELETE',
    '/recipe/delete/[i:id]',
    [
        'method'=>'deleteRecipe',
        'controller'=>RecipesController::class,
    ],
    'delete-recipe',
);
// $router->map(
//     'GET',
//     '/recipe/[i:id]',
//     [
//         'method' => 'getRecipe',
//         'controller' => RecipesController::class,
//     ],
//     'get-recipe',
// );
// $router->map(
//     'GET',
//     '/recipe/[i:id]',
//     [
//         'method' => 'getRecipe',
//         'controller' => RecipesController::class,
//     ],
//     'get-recipe',
// );


//=============INGREDIENTS============//
$router->map(
    'GET',
    '/ingredients',
    [
        'method' => 'list',
        'controller' => IngredientsController::class,
    ],
    'ingredients-list'
);

$router->map(
    'GET',
    '/stock/list',
    [
        'method' => 'listUserIngredients',
        'controller' => IngredientsController::class,
    ],
    'stock-list'
);

$router->map(
    'POST',
    '/stock/add',
    [
        'method' => 'createStockIngredient',
        'controller' => IngredientsController::class,
    ],
    'stock-add',
);

$router->map(
    'POST',
    '/stock/edit/[i:id]',
    [
        'method' => 'updateStock',
        'controller' => IngredientsController::class,
    ],
    'stock-update'
);

$router->map(
    'DELETE',
    '/stock/delete/[i:id]',
    [
        'method' => 'deleteFromStock',
        'controller' => IngredientsController::class,
    ],
    'delete-from-stock'
);

// $router->map(
//     'GET',
//     '/checktoken',
//     [
//         'method' => 'checkToken',
//         'controller' => UserController::class,
//     ],
//     'checkToken'
// );

/* -------------
--- DISPATCH ---
--------------*/

$match = $router->match();

if ($match) {
    $controllerToUse = $match['target']['controller'];
    $methodToUse =  $match['target']['method'];
    $urlParams = $match['params'];
    $controller = new $controllerToUse();
    $controller->$methodToUse($urlParams);
} else {
    http_response_code(404);
    exit;
}


// $dispatcher = new Dispatcher($match, '\App\Controllers\ErrorController::err404');

// $dispatcher->dispatch();
