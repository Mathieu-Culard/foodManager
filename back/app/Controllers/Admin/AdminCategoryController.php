<?php

namespace App\Controllers\Admin;

use App\Models\Category;

class AdminCategoryController extends CoreController
{
  public function list($urlParam)
  {
    // $this->checkAuthorization();
    $currentCategory = [];
    if (!empty($urlParam['id'])) {
      $currentCategory = Category::find($urlParam['id']);
    }
    // $id = $urlParam['id'];
    $categories = Category::findAll();
    // $categories = Category::findAll();
    // $units = Unity::findAll();
    $token = $this->generateCsrfToken();
    $this->show(
      'category/list',
      [
        'categoriesList' => $categories,
        'currentCategory' => $currentCategory,
        'token' => $token,
      ]
    );
  }

  public function createOrUpdate($urlParam)
  {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    if (!empty($urlParam['id'])) { // update
      $category = Category::find($urlParam['id']);
      if (!empty($category)) {
        $category->setName($name);
        if ($category->update()) {
          $this->redirectToRoute('admin-categories-list');
        } else {
          echo 'nanupdate';
        }
      }
    } else { //create
      $category=new Category();
      $category->setName($name);
      if ($category->insert()) {
        $this->redirectToRoute('admin-categories-list');
      } else {
        echo 'nanadd';
      }
    }
  }

  public function delete($urlParam)
  {
    $id = $urlParam['id'];
    $category = Category::find($id);
    $category->delete();
    $this->redirectToRoute('admin-categories-list');
  }
}
