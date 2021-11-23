<?php

namespace App\Controllers\Admin;

use App\Models\Ingredient;
use App\Models\Unity;
use App\Models\Category;

class AdminIngredientController extends CoreController
{
  public function list($urlParam)
  {
    // $this->checkAuthorization();
    $currentIngredient = [];
    if (!empty($urlParam['id'])) {
      $currentIngredient = Ingredient::find($urlParam['id']);
    }
    // $id = $urlParam['id'];
    $ingredients = Ingredient::findAll();
    $categories = Category::findAll();
    $units = Unity::findAll();
    $token = $this->generateCsrfToken();
    $this->show(
      'ingredient/list',
      [
        'ingredientsList' => $ingredients,
        'categories' => $categories,
        'units' => $units,
        'currentIngredient' => $currentIngredient,
        'token'=>$token,
      ]
    );
  }

  public function createOrUpdate($urlParam)
  {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $minBuy = filter_input(INPUT_POST, 'min-buy', FILTER_VALIDATE_INT);
    $category = filter_input(INPUT_POST, 'category', FILTER_VALIDATE_INT);
    $unity = filter_input(INPUT_POST, 'unity', FILTER_VALIDATE_INT);
    if (!empty($urlParam['id'])) {
      $ingredient = Ingredient::find($urlParam['id']);
    } else {
      $ingredient = new Ingredient();
    }
    if (!empty($ingredient)) {
      $ingredient->setName($name);
      $ingredient->setMinBuy($minBuy);
      $ingredient->setCategoryId($category);
      $ingredient->setUnityId($unity);
    }
    if (!empty($urlParam['id'])) { // update
      if (!empty($_FILES['picture']['name'])) { // remove the old picture from the folder to add the new one if the picture has been modified
        unlink(__DIR__ . "/../../../public/assets/ingredients/" . $ingredient->getImage());
        $picName = Ingredient::createPicture($name, 'ingredients');
      } else { // rename the picture to match an eventual new recipe name otherwise
        $oldPicName = explode(".", $ingredient->getImage());
        $picName = str_replace(' ','-',$name) . "." . end($oldPicName);

        rename(__DIR__ . "/../../../public/assets/ingredients/" . $ingredient->getImage(), __DIR__ . "/../../../public/assets/ingredients/" . $picName);
      }
      $ingredient->setImage($picName);
      if ($ingredient->update()) {
        $this->redirectToRoute('admin-ingredients-list');
      } else {
        echo 'nanupdate';
      }
    } else { //create
      $picName = Ingredient::createPicture($name, 'ingredients');
      $ingredient->setImage($picName);
      if ($ingredient->insert()) {
        $this->redirectToRoute('admin-ingredients-list');
      } else {
        echo 'nanadd';
      }
    }
  }

  public function delete($urlParam){
    $id=$urlParam['id'];
    $ingredient=Ingredient::find($id);
    if($ingredient->delete()){
      unlink(__DIR__ . "/../../../public/assets/ingredients/" . $ingredient->getImage());
    }
    $this->redirectToRoute('admin-ingredients-list');
  }
}
