<?php

namespace App\Controllers\Admin;

use App\Models\Unity;

class AdminUnityController extends CoreController
{
  public function list($urlParam)
  {
    $currentUnity = [];
    if (!empty($urlParam['id'])) {
      $currentUnity = Unity::find($urlParam['id']);
    }
    // $id = $urlParam['id'];
    $units = Unity::findAll();
    // $categories = Category::findAll();
    // $units = Unity::findAll();
    $token = $this->generateCsrfToken();
    $this->show(
      'unity/list',
      [
        'unitsList' => $units,
        'currentUnity' => $currentUnity,
        'token' => $token,
      ]
    );
  }

  public function createOrUpdate($urlParam)
  {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    if (!empty($urlParam['id'])) { // update
      $unity = Unity::find($urlParam['id']);
      if (!empty($unity)) {
        $unity->setUnity($name);
        if ($unity->update()) {
          $this->redirectToRoute('admin-units-list');
        } else {
          echo 'nanupdate';
        }
      }
    } else { //create
      $unity=new Unity();
      $unity->setUnity($name);
      if ($unity->insert()) {
        $this->redirectToRoute('admin-units-list');
      } else {
        echo 'nanadd';
      }
    }
  }

  public function delete($urlParam)
  {
    $id = $urlParam['id'];
    $unity = Unity::find($id);
    $unity->delete();
    $this->redirectToRoute('admin-units-list');
  }
}
