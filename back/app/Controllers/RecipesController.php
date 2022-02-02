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
    $user = User::checkToken($_SERVER['HTTP_AUTHORIZATION']); // tell the user if the recipes are doable
    if ($user) {
      $newRecipes = [];
      $userStock = Ingredient::getUserStock($user->getId());
      foreach ($recipes as $recipe) {
        $newRecipes[] = Recipe::checkDoable($recipe, $userStock);
      }
      echo json_encode($newRecipes);
    } else {
      echo json_encode($recipes);
    }
  }

  /**
   * return an array including all infos about a recipe
   */
  public function getRecipe($urlParam)
  {
    $recipe = [];
    $id = $urlParam['id'];
    $user = User::checkToken($_SERVER['HTTP_AUTHORIZATION']); // tell the user if the recipes are doable
    if ($user) {
      $recipe['infos'] = Recipe::checkDoable(Recipe::find($id), Ingredient::getUserStock($user->getId()));
    } else {
      $recipe['infos'] = Recipe::find($id);
    }
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



  public function deleteRecipeToBuy($urlParam)
  {
    $user = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($user) {
      Recipe::deleteWantedRecipe($urlParam['id'], $user->getId());
    } else {
      http_response_code(401);
      echo json_encode('vous devez vous reconnecter');
    }
  }

  public function buyLessRecipe($urlParam)
  {
    $user = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($user) {
      $id = $urlParam['id'];
      $wantedRecipe = Recipe::checkExist($id, $user->getId());
      Recipe::removeWantedRecipe($wantedRecipe, $user->getId());
      // echo json_encode($error);
    } else {
      http_response_code(401);
      echo json_encode('vous devez vous reconnecter');
    }
  }

  public function buyRecipe($urlParam)
  {
    $user = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($user) {
      $id = $urlParam['id'];
      Recipe::addWantedRecipe($id, $user->getId());
    } else {
      http_response_code(401);
      echo json_encode('vous devez vous reconnecter');
    }
  }

  public static function sanitizeData()
  {
    $data = [];
    $data['name'] = filter_var($_POST['name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $data['shared'] = filter_var($_POST['shared'], FILTER_VALIDATE_BOOLEAN);
    $steps = json_decode($_POST['steps'], true);
    $filteredSteps = [];
    foreach ($steps as $stepText) {
      $filteredStep = filter_var($stepText, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
      if (trim($filteredStep) !== "") {
        $step = new Step();
        $step->setText($stepText);
        $filteredSteps[] = $step;
      }
    }
    $data['steps'] = $filteredSteps;
    $ingredients = json_decode($_POST['ingredients'], true);
    $args = [
      'id' => FILTER_VALIDATE_INT,
      'quantity' => FILTER_VALIDATE_INT,
    ];
    $filteredIngredients = [];
    foreach ($ingredients as $ingredient) {
      $filteredIngredients[] = filter_var_array($ingredient, $args);
    }
    $data['ingredients'] = $filteredIngredients;
    return $data;
  }

  public static function validateData($data)
  {
    if (trim($data['name']) === "") {
      return "veuillez ajouter un nom à votre recette";
    } else if (empty($data['ingredients'])) {
      return "veuillez ajouter au moins un ingredient à votre recette";
    } else if (empty($data['steps'])) {
      return "veuillez ajouter au moins une étape à votre recette";
    } else {
      return "";
    }
  }
  /**
   * update a recipe
   */
  public function editRecipe($urlParam)
  {
    $id = $urlParam['id'];
    $user = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($user) {
      $recipe = Recipe::find($id); // find the desired recipe
      if (!empty($recipe)) { //if a recipe was found, set the modified values to it
        $data = self::sanitizeData();
        $error = self::validateData($data);
        if (empty($error)) {
          if (!empty($_FILES['picture'])) { // remove the old picture from the folder to add the new one if the picture has been modified
            unlink(__DIR__ . "/../../public/assets/recipes/" . $recipe->getImage());
            $picName = Recipe::createPicture($data['name']);
          } else { // rename the picture to match an eventual new recipe name otherwise
            $oldPicName = explode(".", $recipe->getImage());
            $picName = str_replace(' ', '-', $data['name']) . "-" . $user->getId() . "." . end($oldPicName);
            rename(__DIR__ . "/../../public/assets/recipes/" . $recipe->getImage(), __DIR__ . "/../../public/assets/recipes/" . $picName);
          }
          $recipe->setName($data['name']);
          $recipe->setPublic($data['shared'] ?? false);
          $recipe->setImage($picName);
          $recipe->updateRecipe();
          $recipe->setIngredients($data['ingredients']);
          Step::deleteSteps($id); //remove all steps and ingredients linked to the recipe before adding the new ones
          Ingredient::deleteRecipeIngredients($id);
          foreach ($data['steps'] as $step) {
            $step->setRecipeId($id);
            $step->insert();
          }
          Ingredient::addRecipesIngredients($recipe);
        } else {
          http_response_code(400);
          echo json_encode($error);
        }
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
    $user = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($user) {
      $recipe = Recipe::find($id);
      unlink(__DIR__ . "/../../public/assets/recipes/" . $recipe->getImage());
      $response = $recipe->delete();
      // Step::deleteSteps($id);
      // Ingredient::deleteRecipeIngredients($id);
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
    $user = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($user) { // check auth
      $data = self::sanitizeData();
      $error = self::validateData($data);
      if (empty($_FILES['picture'])) {
        $error = "Veuillez ajouter une photo de votre recette";
      }
      if (empty($error)) {
        $picName = Recipe::createPicture($data['name'] . "-" . $user->getId());
        $recipe = new Recipe();
        $recipe->setName($data['name']);
        $recipe->setPublic($data['shared'] ?? false);
        $recipe->setUserId($user->getId());
        $recipe->setImage($picName);
        $recipe->setIngredients($data['ingredients']);
        $recipeId = $recipe->insert();
        if ($recipeId) {
          $recipe->setId($recipeId);
          $recipe->insertRecipesIngredients();
          foreach ($data['steps'] as $step) {
            $step->setRecipeId($recipeId);
            $step->insert();
          }
          http_response_code(201);
          echo json_encode($recipeId);
        }else{
          http_response_code(500);
          echo json_encode('La recette n\'a pas pû être ajoutée, veuillez reéssayer plus tard' );
        }
      } else {
        http_response_code(400);
        echo json_encode($error);
      }
    } else {
      http_response_code(401);
      echo json_encode('Vous devez vous reconnecter');
    }
  }
  /**
   * Return user's recipes
   */
  public function getMyRecipes()
  {
    $user = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($user) {
      $myRecipes = $user->findUserRecipes();

      // $myRecipes = Recipe::findUserRecipes($granted['userId']);
      echo json_encode($myRecipes);
    } else {
      http_response_code(401);
      echo json_encode('vous devez vous reconnecter');
    }
  }

  public function cookRecipe($urlParam)
  {
    $id = $urlParam['id'];
    $user = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if ($user) {
      Ingredient::removeRecipeFromStock($user, $id);
    } else {
      http_response_code(401);
      echo json_encode('vous devez vous reconnecter');
    }
  }
}
