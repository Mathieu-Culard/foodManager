<?php

namespace App\Controllers;

use App\Models\Recipe;

class RecipesController
{
  public function list()
  {
    $recipes = Recipe::findAll();
    echo json_encode($recipes);
  }
}
