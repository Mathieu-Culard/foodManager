<?php

namespace App\Controllers;

use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Step;
use App\Models\User;

class RecipesController
{
  /**
   * return array of all public recipes
   */
  public function list()
  {
    $recipes = Recipe::findAll();
    echo json_encode($recipes);
  }

  /**
   * return an array including all infos about a recipe
   */
  public function getRecipe($urlParam)
  {
    $recipe = [];
    $id = $urlParam['id'];
    $recipe['infos'] = Recipe::find($id);
    if ($recipe['infos']) {
      $recipe['ingredients'] = Ingredient::findRecipeIngredients($id);
      $recipe['steps'] = Step::findRecipeSteps($id);
      $recipe['owner'] = User::findRecipeOwner($id);
      echo json_encode($recipe);
    } else {
      http_response_code(404);
      echo json_encode("cette recette n'existe pas");
    }
  }

  /**
   * update a recipe
   */
  public function editRecipe($urlParam)
  {
    $granted = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($granted['message'] === "success") {
      //data validation
      $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
      $shared = filter_var($_POST['shared'], FILTER_VALIDATE_BOOLEAN);
      $steps = json_decode($_POST['steps'], true);
      $filteredSteps = [];
      foreach ($steps as $step) {
        $filteredSteps[] = filter_var($step, FILTER_SANITIZE_STRING);
      }
      $ingredients = json_decode($_POST['ingredients'], true);
      $args = [
        'id' => FILTER_VALIDATE_INT,
        'quantity' => FILTER_VALIDATE_INT,
      ];
      $filteredIngredients = [];
      foreach ($ingredients as $ingredient) {
        $filteredIngredients[] = filter_var_array($ingredient, $args);
      }
      $id = $urlParam['id'];
      $recipe = Recipe::find($id); // find the desired recipe 
      if (!empty($_FILES['picture'])) { // remove the old picture from the folder to add the new one if the picture has been modified
        unlink(__DIR__ . "/../../public/assets/recipes/" . $recipe->getImage());
        $picName = Recipe::createPicture($name);
      } else { // rename the picture to match an eventual new recipe name otherwise
        $oldPicName = explode(".", $recipe->getImage());
        $picName = $name . "." . end($oldPicName);
        rename(__DIR__ . "/../../public/assets/recipes/" . $recipe->getImage(), __DIR__ . "/../../public/assets/recipes/" . $picName);
      }
      // foreach ($filteredIngredients as $key => $value) {
      //   echo json_encode($key);
      //   echo json_encode($value);
      // }
      // echo json_encode($filteredIngredients[0]['quantity']);
      // echo json_encode($ingredients);
      if (!empty($recipe)) { //if a recipe was found, set the modified values to it
        $recipe->setName($name);
        $recipe->setPublic($shared);
        $recipe->updateRecipe($picName);
        Step::deleteSteps($id); //remove all steps and ingredients linked to the recipe before adding the new ones
        Ingredient::deleteRecipeIngredients($id);
        Step::addRecipeSteps($filteredSteps, $id);
        Ingredient::addRecipesIngredients($filteredIngredients, $id);
      } else {
        http_response_code(404);
        echo json_encode("cette recette n'existe pas");
      }
    } else {
      http_response_code(401);
      echo json_encode('vous devez vous reconnecter');
    }
  }

  public function deleteRecipe($urlParam)
  {
    $id = $urlParam['id'];
    $granted = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($granted['message'] === "success") {
      $recipe = Recipe::find($id);
      $response = $recipe->delete();
      Step::deleteSteps($id);
      Ingredient::deleteRecipeIngredients($id);
      if ($response) {
        http_response_code(204);
      } else {
        http_response_code(404);
      }
    }
  }
  /**
   * add a new recipe
   */
  public function createRecipe()
  {
    $granted = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($granted['message'] === "success") {
      $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
      $shared = filter_var($_POST['shared'], FILTER_VALIDATE_BOOLEAN);
      $steps = json_decode($_POST['steps'], true);
      $filteredSteps = [];
      foreach ($steps as $step) {
        $filteredSteps[] = filter_var($step, FILTER_SANITIZE_STRING);
      }
      $ingredients = json_decode($_POST['ingredients'], true);
      $args = [
        'id' => FILTER_VALIDATE_INT,
        'quantity' => FILTER_VALIDATE_INT,
      ];
      $filteredIngredients = [];
      foreach ($ingredients as $ingredient) {
        $filteredIngredients[] = filter_var_array($ingredient, $args);
      }
      $picName = Recipe::createPicture($name);
      $response = Recipe::addRecipe($name, $shared, $filteredIngredients,  $filteredSteps, $granted['userId'], $picName);
      if (gettype($response) !== "string") {
        http_response_code(500);
        echo json_encode($response);
      } else {
        echo json_encode($response);
      }
      // $yup = json_decode($_POST['ingredients'], true);
      // echo json_encode($yup[0]);
    } else {
      http_response_code(401);
      echo json_encode('vous devez vous reconnecter');
    }
  }
  /**
   * Return user's recipes
   */
  public function getMyRecipes()
  {
    $granted = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($granted['message'] === "success") {
      $myRecipes = Recipe::findUserRecipes($granted['userId']);
      echo json_encode($myRecipes);
    } else {
      http_response_code(401);
      echo json_encode('vous devez vous reconnecter');
    }
  }
}
