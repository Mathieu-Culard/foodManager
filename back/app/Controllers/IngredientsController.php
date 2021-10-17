<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Ingredient;
use Symfony\Component\VarDumper\Caster\ArgsStub;

class IngredientsController
{
  /**
   * Return array of all possible ingredients
   */
  public function list()
  {
    $ingredients = Ingredient::findAll();
    echo json_encode($ingredients);
  }

  /**
   * update quantity (newValue) of given ingredient in the user stock
   */

  public function updateStock($urlParam)
  {
    $granted = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($granted['message'] === "success") {
      $data = json_decode(file_get_contents("php://input"));
      $newValue = filter_var($data->newValue,FILTER_VALIDATE_INT);
      $response = Ingredient::updateStockIngredient($urlParam['id'], $granted['userId'], $newValue);
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
    $granted = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($granted['message'] === "success") {
      Ingredient::deleteStockIngredient($urlParam['id'], $granted['userId']);
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
   * delete add in the user stock
   */
  public function createStockIngredient()
  {
    $granted = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($granted['message'] === "success") {
      $data = json_decode(file_get_contents("php://input"));
      $ingredients=[];
      $args = [
        'id' => FILTER_VALIDATE_INT,
        'quantity' => FILTER_VALIDATE_INT,
      ];
      foreach($data->addStock as $ingredient){
        $ingredients[]=filter_var_array(get_object_vars($ingredient),$args);
      }
      // $ingredients = $data->addStock;
      $response = Ingredient::addToStock($ingredients, $granted['userId']);
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
    $granted = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($granted['message'] === "success") {
      $ingredients = Ingredient::findUserIngredients($granted['userId']);
      echo json_encode($ingredients);
    } else {
      http_response_code(401);
      echo json_encode('vous devez vous reconnecter');
    }
  }
}
