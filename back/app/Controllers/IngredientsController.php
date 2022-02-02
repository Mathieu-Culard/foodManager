<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Ingredient;
use App\Models\Recipe;
use Symfony\Component\VarDumper\Caster\ArgsStub;

class IngredientsController
{
  /**
   * Return array of all possible ingredients
   */
  public function list()
  {
    $ingredients = Ingredient::findAllByCategories();
    echo json_encode($ingredients);
  }

  /**
   * update quantity (newValue) of given ingredient in the user stock
   */

  public function updateStock($urlParam)
  {
    $user = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($user) {
      $data = json_decode(file_get_contents("php://input"));
      $newValue = filter_var($data->newValue, FILTER_VALIDATE_INT);
      $identifier = filter_var($data->identifier, FILTER_SANITIZE_STRING);
      $needed = $identifier === 'shop' ? 1 : 0;
      $response = Ingredient::updateStockIngredient($urlParam['id'], $user->getId(), $newValue, $needed);
      if (!empty($response)) {
        http_response_code(500);
        echo json_encode('serveur en pls');
      }
    } else {
      http_response_code(401);
      echo json_encode('vous devez vous reconnecter');
    }
  }

  /**
   * 
   * delete ingredient in the user stock
   *
   *
   * @param array $urlParam
   * @return void
   */
  public function deleteFromStock($urlParam)
  {
    $user = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($user) {
      $identifier = filter_var($_GET['identifier'], FILTER_SANITIZE_STRING);
      $needed = $identifier === 'shop' ? 1 : 0;
      Ingredient::deleteStockIngredient($urlParam['id'], $user->getId(), $needed);
      if (!empty($response)) {
        http_response_code(500);
        echo json_encode('serveur en pls');
      }
    } else {
      http_response_code(401);
      echo json_encode('vous devez vous reconnecter');
    }
  }

  /**
   * add ingredients to user stock or user shopping list
   */
  public function createStockIngredient()
  {
    $user = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($user) {
      $data = json_decode(file_get_contents("php://input"));
      $ingredients = [];
      $identifier = filter_var($data->identifier, FILTER_SANITIZE_STRING);
      $needed = $identifier == 'shop' ? 1 : 0;
      $args = [
        'id' => FILTER_VALIDATE_INT,
        'quantity' => FILTER_VALIDATE_INT,
      ];
      foreach ($data->addStock as $ingredient) {
        $ingredients[] = filter_var_array(get_object_vars($ingredient), $args);
      }
      // $ingredients = $data->addStock;
      $response = Ingredient::addToStock($ingredients, $user->getId(), $needed);
      echo json_encode($response);
    } else {
      http_response_code(401);
      echo json_encode('vous devez vous reconnecter');
    }
  }

  /**
   * Return user stock 
   */
  public function listUserIngredients()
  {
    $user = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($user) {
      $identifier = filter_var($_GET['identifier'], FILTER_SANITIZE_STRING);
      $ingredients = $user->findUserIngredients($identifier);
      if ($identifier == 'shop') {
        $result = $user->getWantedRecipes();
        echo json_encode([
          'recipes' => $result,
          'ingredients' => $ingredients
        ]);
      } else {
        echo json_encode($ingredients);
      }
    } else {
      http_response_code(401);
      echo json_encode('vous devez vous reconnecter');
    }
  }

  /**
   * add shoppingList elements into user stock and clear it
   */
  public function validateShoppingList()
  {
    $user = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($user) {
      $data = json_decode(file_get_contents("php://input"));
      $recipes = $data->recipesShop;
      $ingredients = [];
      $recipesIds = [];
      $args = [
        'id' => FILTER_VALIDATE_INT,
        'quantity' => FILTER_VALIDATE_INT,
      ];
      foreach ($recipes as $recipe) {
        foreach ($recipe->ingredients as $ingredient) {
          $ingredients[] = filter_var_array(get_object_vars($ingredient), $args);
        }
        $recipesIds[] = filter_var($recipe->id, FILTER_VALIDATE_INT);
      }
      $shoppingList = Ingredient::getShoppingList($user->getId());
      Ingredient::addToStock($shoppingList, $user->getId(), 0);
      Ingredient::addToStock($ingredients, $user->getId(), 0);
      foreach ($shoppingList as $ingredient) {
        Ingredient::deleteStockIngredient($ingredient['id'], $user->getId(), 1);
      }
      foreach ($recipesIds as $id) {
        Recipe::deleteWantedRecipe($id, $user->getId());
      }
      // echo json_encode($shoppingList);
    } else {
      http_response_code(401);
      echo json_encode('vous devez vous reconnecter');
    }
  }
}
