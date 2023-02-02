<?php

namespace App\Controllers;

use App\Models\User;


class UserController
{

  public function register()
  {
    $data = json_decode(file_get_contents("php://input"));
    //retrive and sanitize data
    $username = trim(htmlspecialchars($data->username));
    $password = $data->password;
    $passwordConf = $data->passwordConf;
    $email = filter_var($data->email, FILTER_SANITIZE_EMAIL);
    //validate data
    if ($data->username !== $username || strlen($username) === 0) {
      $error = "Veuillez renseigner un nom d'utilisateur valide";
    }
    if ($data->email !== $email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = "Veuillez renseigner une adresse email valide";
    }
    if ($password !== $passwordConf) {
      $error = "Les mots de passe ne correspondent pas";
    } else if (strlen($password) < 8) {
      $error = "Votre mot de passe doit faire au moins 8 caractères";
    }
    if (!isset($error)) {
      $user = new User();
      $user->setUsername($username);
      $user->setEmail($email);
      $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
      $user->setAvatar('avatar.png');
      $user->setRole('ROLE_USER');
      $response = $user->insert();
      if ($response instanceof User) {
        http_response_code(201);
        echo json_encode('Bienvenue dans food manager');
      } else {
        http_response_code(409);
        echo json_encode($response); // in this case response is an error from PDO
      }
    } else {
      http_response_code(400);
      echo json_encode($error);
    }
  }


  public function login()
  {
    $data = json_decode(file_get_contents("php://input"));
    $username = $data->username;
    $password = $data->password;
    $user = User::find($username);
    if (!empty($user)) {
      if (password_verify($password, $user->getPassword())) {
        $loginInfo = $user->getConnectionInfo();
        header('Set-Cookie: token=' . $loginInfo['refreshToken'] . '; Path=/');
        unset($loginInfo['refreshToken']);
        echo json_encode($loginInfo);
      } else {
        http_response_code(401);
        echo json_encode('Mot de passe incorect');
      }
    } else {
      http_response_code(401);
      echo json_encode('Cet utilisateur n\'existe pas');
    }
  }


  public function refreshToken()
  {
    $user = User::checkRefreshToken($_COOKIE['token']);
    unset($_COOKIE['token']);
    if ($user) {
      $loginInfo = $user->getConnectionInfo();
      header('Set-Cookie: token=' . $loginInfo['refreshToken'] . '; Path=/');
      unset($loginInfo['refreshToken']);
      echo json_encode($loginInfo);
    }
  }

  public function logout()
  {
      unset($_COOKIE['token']);
      header('Set-Cookie: token=;Path=/');
      echo json_encode('oui');
  }

  public function sendShoppingList(){
    $user = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    if($user){
      $headers="Content-Type: text/plain; charset=utf-8\r\n";
      $headers ="From: projetfoodmanager@gmail.com";
      $data = json_decode(file_get_contents("php://input"));
      $ingredientsList=[];
      foreach($data->recipesShop as $recipe){
        foreach($recipe->ingredients as $ingredient){
          $ingredientsList[]=$ingredient->name." - ".$ingredient->quantity." ".$ingredient->unity;
        }
      }
      foreach($data->shop as $category){
        foreach($category->ingredients as $ingredient){
          $ingredientsList[]=$ingredient->name." - ".$ingredient->quantity." ".$ingredient->unity;
        }
      }

      if(mail($user->getEmail(),'Liste de courses',implode("\r\n",$ingredientsList),$headers)){
        echo json_encode('yep');
      }else{
        echo json_encode('nop');
      }
    }
  }
}
