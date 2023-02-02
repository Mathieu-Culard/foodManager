<?php

ini_set('display_errors', 0);

// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header('Access-Control-Allow-Methods: GET, POST, PUT');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Credentials:true');
    header('Access-Control-Allow-Origin: http://localhost:8080');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: token, Content-Type, Authorization');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}

header('Access-Control-Allow-Origin: http://localhost:8080');
header('Access-Control-Allow-Credentials:true');

// header('Content-Type: application/json');

// $ret = [
//     'result' => 'OK',
// ];
// print json_encode($ret);


require_once '../vendor/autoload.php';

use App\Controllers\Admin\AdminIngredientController;
use App\Controllers\Admin\AdminUserController;
use App\Controllers\Admin\AdminUnityController;
use App\Controllers\UserController;
use App\Controllers\RecipesController;
use App\Controllers\IngredientsController;
use App\Controllers\Admin\AdminCategoryController;
use App\Controllers\Admin\AdminErrorController;

session_start();


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
//=============ADMIN============//
$router->map(
    'GET',
    '/categories/[i:id]?/[a:order]?',
    [
        'method' => 'list',
        'controller' => AdminCategoryController::class
    ],
    'admin-categories-list'
);



$router->map(
    'POST',
    '/categories/[i:id]?',
    [
        'method' => 'createOrUpdate',
        'controller' => AdminCategoryController::class
    ],
    'admin-categories-add-update'
);

$router->map(
    'GET',
    '/categories/delete/[i:id]',
    [
        'method' => 'delete',
        'controller' => AdminCategoryController::class
    ],
    'admin-categories-delete'
);

$router->map(
    'GET',
    '/units/[i:id]?/[a:order]?',
    [
        'method' => 'list',
        'controller' => AdminUnityController::class
    ],
    'admin-units-list'
);

$router->map(
    'POST',
    '/units/[i:id]?',
    [
        'method' => 'createOrUpdate',
        'controller' => AdminUnityController::class
    ],
    'admin-units-add-update'
);

$router->map(
    'GET',
    '/units/delete/[i:id]',
    [
        'method' => 'delete',
        'controller' => AdminUnityController::class
    ],
    'admin-units-delete'
);




$router->map(
    'GET',
    '/ingredients/[i:id]?/[a:order]?',
    [
        'method' => 'list',
        'controller' => AdminIngredientController::class
    ],
    'admin-ingredients-list'
);

$router->map(
    'POST',
    '/ingredients/[i:id]?',
    [
        'method' => 'createOrUpdate',
        'controller' => AdminIngredientController::class
    ],
    'admin-ingredients-add-update'
);

$router->map(
    'GET',
    '/ingredients/delete/[i:id]',
    [
        'method' => 'delete',
        'controller' => AdminIngredientController::class
    ],
    'admin-ingredients-delete'
);

$router->map(
    'GET',
    '/users',
    [
        'method' => 'list',
        'controller' => AdminUserController::class
    ],
    'admin-user-list'
);

$router->map(
    'GET',
    '/users/[i:id]',
    [
        'method' => 'updateRole',
        'controller' => AdminUserController::class
    ],
    'admin-user-update'
);

$router->map(
    'GET',
    '/login',
    [
        'method' => 'login',
        'controller' => AdminUserController::class
    ],
    'admin-user-login'
);

$router->map(
    'POST',
    '/login',
    [
        'method' => 'authenticate',
        'controller' => AdminUserController::class
    ],
    'admin-user-authenticate'
);

$router->map(
    'GET',
    '/logout',
    [
        'method' => 'logout',
        'controller' => AdminUserController::class
    ],
    'admin-user-logout'
);

$router->map(
    'GET',
    '/error409',
    [
        'method' => 'ckc',
        'controller' => AdminErrorController::class
    ],
    'admin-error-409'
);


//=============CONNECTION============//
$router->map(
    'POST',
    '/api/register',
    [
        'method' => 'register',
        'controller' => UserController::class,
    ],
    'register'
);

$router->map(
    'POST',
    '/api/login',
    [
        'method' => 'login',
        'controller' => UserController::class,
    ],
    'login'
);

$router->map(
    'GET',
    '/api/logout',
    [
        'method' => 'logout',
        'controller' => UserController::class,
    ],
    'logout'
);

$router->map(
    'GET',
    '/api/refreshtoken',
    [
        'method' => 'refreshToken',
        'controller' => UserController::class,
    ],
    'refresh-token'
);

//=============RECIPES============//
$router->map(
    'GET',
    '/api/recipes',
    [
        'method' => 'list',
        'controller' => RecipesController::class,
    ],
    'recipes-list'
);

$router->map(
    'GET',
    '/api/recipe/[edit|get:action]/[i:id]',
    [
        'method' => 'getRecipe',
        'controller' => RecipesController::class,
    ],
    'get-recipe',
);

$router->map(
    'GET',
    '/api/recipe/my-recipes',
    [
        'method' => 'getMyRecipes',
        'controller' => RecipesController::class,
    ],
    'get-my-recipes'
);

$router->map(
    'POST',
    '/api/recipe/add',
    [
        'method' => 'createRecipe',
        'controller' => RecipesController::class,
    ],
    'create-recipe',
);

$router->map(
    'POST',
    '/api/recipe/edit/[i:id]',
    [
        'method' => 'editRecipe',
        'controller' => RecipesController::class,
    ],
    'edit-recipe',
);

$router->map(
    'DELETE',
    '/api/recipe/delete/[i:id]',
    [
        'method' => 'deleteRecipe',
        'controller' => RecipesController::class,
    ],
    'delete-recipe',
);

$router->map(
    'POST',
    '/api/recipe/buy/[i:id]',
    [
        'method' => 'buyRecipe',
        'controller' => RecipesController::class,
    ],
    'buy-recipe',
);

$router->map(
    'POST',
    '/api/recipe/buyless/[i:id]',
    [
        'method' => 'buyLessRecipe',
        'controller' => RecipesController::class,
    ],
    'buy-less-recipe',
);

$router->map(
    'DELETE',
    '/api/recipe/buy/delete/[i:id]',
    [
        'method' => 'deleteRecipeToBuy',
        'controller' => RecipesController::class,
    ],
    'buy-recipe-delete',
);

$router->map(
    'POST',
    '/api/recipe/cook/[i:id]',
    [
        'method' => 'cookRecipe',
        'controller' => RecipesController::class,
    ],
    'cook-recipe',
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
    'POST',
    '/api/send/shopping',
    [
        'method' => 'sendShoppingList',
        'controller' => UserController::class,
    ],
    'send-shopping-list',
);

$router->map(
    'GET',
    '/api/ingredients',
    [
        'method' => 'list',
        'controller' => IngredientsController::class,
    ],
    'ingredients-list'
);

$router->map(
    'GET',
    '/api/stock/list',
    [
        'method' => 'listUserIngredients',
        'controller' => IngredientsController::class,
    ],
    'stock-list'
);

$router->map(
    'POST',
    '/api/stock/add',
    [
        'method' => 'createStockIngredient',
        'controller' => IngredientsController::class,
    ],
    'stock-add',
);

$router->map(
    'POST',
    '/api/stock/edit/[i:id]',
    [
        'method' => 'updateStock',
        'controller' => IngredientsController::class,
    ],
    'stock-update'
);

$router->map(
    'DELETE',
    '/api/stock/delete/[i:id]',
    [
        'method' => 'deleteFromStock',
        'controller' => IngredientsController::class,
    ],
    'delete-from-stock'
);

$router->map(
    'POST',
    '/api/shop/validate',
    [
        'method' => 'validateShoppingList',
        'controller' => IngredientsController::class,
    ],
    'validate-shopping-list'
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
    $controller = new $controllerToUse($router, $match['name']);
    $controller->$methodToUse($urlParams);
} else {
    http_response_code(400);
    exit;
}


// $dispatcher = new Dispatcher($match, '\App\Controllers\ErrorController::err404');

// $dispatcher->dispatch();
