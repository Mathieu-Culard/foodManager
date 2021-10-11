<?php

namespace App\Controllers;

use App\Models\User;


class UserController
{
  public function register()
  {
    $data = json_decode(file_get_contents("php://input"));
    $username = htmlspecialchars($data->username) ?? '';
    $password = $data->password ?? '';
    $passwordConf = $data->passwordConf ?? '';
    $email = filter_var($data->email, FILTER_SANITIZE_EMAIL) ?? '';

    $user = new User();
    // $error="";
    $user->setUsername($username);
    if ($data->email == $email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $user->setEmail($email);
    } else {
      $error = "Email incorrect";
    }
    if ($password === $passwordConf && $password != '') {
      $user->setPassword(password_hash($password, PASSWORD_DEFAULT)); //password_verify
    } else {
      $error = "les mots de passe ne correspondent pas";
    }
    $user->setAvatar('avatar.png');
    $user->setRole('ROLE_USER');

    if (!isset($error)) {
      $response = $user->insert();
      if ($response instanceof User) {
        http_response_code(201);
        echo json_encode($response);
      } else {
        // $error=
        http_response_code(400);
        echo json_encode(User::createCustomError($response));
      }
    } else {
      http_response_code(400);
      echo json_encode($error);
    }
  }

  public function login()
  {
    $data = json_decode(file_get_contents("php://input"));
    $username = $data->username ?? '';
    $password = $data->password ?? '';
    $user = User::find($username);
    if (!empty($user)) {
      if (password_verify($password, $user->getPassword())) {
        $loginInfo = $user->getConnectionInfo();
        echo json_encode($loginInfo);
      } else {
        http_response_code(401);
        echo json_encode('wrong password');
      }
    } else {
      http_response_code(401);
      echo json_encode('wrong username');
    }
  }

  public function checkToken()
  {
    $granted = User::checkToken($_SERVER['HTTP_AUTHORIZATION']);
    $message = $granted["message"];
    if ($message === "success") {
      $users = User::findAll();
      echo json_encode($users);
    } else {
      http_response_code(401);
      echo json_encode($granted);
    }
  }
}
