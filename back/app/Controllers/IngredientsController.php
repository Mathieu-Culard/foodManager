<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Ingredient;

class IngredientsController
{
  public function list()
  {
    $ingredients = Ingredient::findAll();
    echo json_encode($ingredients);
  }

  public function updateStock($urlParam)
  {
    $granted = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($granted['message'] === "success") {
      $data = json_decode(file_get_contents("php://input"));
      $newValue = $data->newValue;
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

  public function createStockIngredient()
  {
    $granted = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($granted['message'] === "success") {
      $data = json_decode(file_get_contents("php://input"));
      $ingredients = $data->addStock;
      $response = Ingredient::addToStock($ingredients, $granted['userId']);
      echo json_encode($response);
    }
  }

  public function listUserIngredients()
  {
    $granted = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($granted['message'] === "success") {
      $ingredients = Ingredient::findUserIngredients($granted['userId']);
      echo json_encode($ingredients);
    }
  }
}
