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
    $email = $data->email ?? '';


    $user = new User();

    $user->setUsername($username);
    $user->setEmail(filter_var($email, FILTER_SANITIZE_EMAIL));
    if ($password === $passwordConf && $password != '') {
      $user->setPassword(password_hash($password, PASSWORD_DEFAULT)); //password_verify
    } else {
      echo 'erreur';
    }
    $user->setAvatar('blabla');
    $user->setRole('ROLE_USER');

    $response = $user->insert();
    // echo 'yes'.$test;
    if ($response instanceof User) {
      http_response_code(201);
      echo json_encode($response);
    } else {
      // $error=
      http_response_code(400);
      echo json_encode(User::createCustomError($response));
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
      echo 'wrong username';
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
