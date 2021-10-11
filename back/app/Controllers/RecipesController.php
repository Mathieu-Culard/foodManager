<?php

namespace App\Controllers;

use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Step;
use App\Models\User;

class RecipesController
{
  public function list()
  {
    $recipes = Recipe::findAll();
    echo json_encode($recipes);
  }

  public function getRecipe($urlParam)
  {
    $recipe=[];
    $id=$urlParam['id'];
    $recipe['infos'] = Recipe::find($id);
    $recipe['ingredients']= Ingredient::findRecipeIngredients($id);
    $recipe['steps']=Step::findRecipeSteps($id);
    $recipe['owner']=User::findRecipeOwner($id);
    echo json_encode($recipe);
  }
}
