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


  public function login()
  {
    $this->show(
      'user/login'
    );
  }

  public function authenticate()
  {
    // dump($_POST);
    // On récupère les données du formulaire
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'pass');

    $user = User::find($username);
    // dump($appUser);

    // si l'email existe bien
    if (!empty($user)) {
      if (password_verify($password, $user->getPassword())) {
        $_SESSION['userId'] = $user->getId();
        $_SESSION['userRole'] = $user->getRole();
        $this->redirectToRoute('admin-ingredients-list');
      } else {
        echo 'Password incorrect';
      }
    } else {
      echo 'Email inconnu';
    }
  }

  public function logout()
  {
    unset($_SESSION['userId']); // On supprime la clé => donc l'élément du tableau
    unset($_SESSION['userRole']); // On supprime la clé => donc l'élément du tableau
    $this->redirectToRoute('admin-user-login');
  }
}
