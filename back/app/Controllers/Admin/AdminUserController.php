<?php

namespace App\Controllers\Admin;

use App\Models\User;

class AdminUserController extends CoreController
{
  public function list()
  {
    $users = User::findAll();
    $token = $this->generateCsrfToken();
    $this->show(
      'user/list',
      [
        'usersList' => $users,
        'token' => $token,
      ]
    );
  }

  public function updateRole($urlParam)
  {
    $id = $urlParam['id'];
    $user = User::findById($id);
    if ($user->getRole() == 'ROLE_USER') {
      $user->setRole('ROLE_ADMIN');
    } else {
      $user->setRole('ROLE_USER');
    }
    $user->changeRole();
    $this->redirectToRoute('admin-user-list');
  }


  public function login($urlParam)
  {
    // $error="";
    // if(isset($urlParam['e'])){
    //   $error=$urlParam['e'];
    // }
    $this->show(
      'user/login',
      // [
      //   'error'=>$error,
      // ]
    );
  }

  public function authenticate()
  {
    $username = filter_input(INPUT_POST, 'username');
    $password = filter_input(INPUT_POST, 'pass');
    $user = User::find($username);
    if (!empty($user)) {
      if (password_verify($password, $user->getPassword())) {
        $_SESSION['userId'] = $user->getId();
        $_SESSION['userRole'] = $user->getRole();
        $this->redirectToRoute('admin-ingredients-list');
      } else {
        $error = "Mot de passe incorect";
      }
    } else {
      $error = "Ce nom d'utilisateur n'existe pas";
    }
    $this->show(
      'user/login',
      [
        'error' => $error
      ]
    );
  }

  public function logout()
  {
    unset($_SESSION['userId']); // On supprime la clé => donc l'élément du tableau
    unset($_SESSION['userRole']); // On supprime la clé => donc l'élément du tableau
    $this->redirectToRoute('admin-user-login');
  }
}
