<?php

namespace App\Models;

use App\Utils\Database;
use JsonSerializable;
use PDO;
use \Firebase\JWT\JWT;
use PDOException;

class User extends CoreModel implements JsonSerializable
{


    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $avatar;
    /**
     * @var string
     */
    private $role;



    public static function find($username)
    {
        $pdo = Database::getPDO();
        $sql = "SELECT * FROM `users` WHERE `username` = :username ";
        $preparedQuery = $pdo->prepare($sql);
        $preparedQuery->execute([
            ':username' => $username,
        ]);
        $user = $preparedQuery->fetchObject(static::class);
        if ($user) {
            return $user;
        } else {
            return false;
        }
    }

    public function insert()
    {
        $pdo = Database::getPDO();
        $sql = "
        INSERT INTO `users` (
            `username`,
            `email`,
            `password`,
            `avatar`,
            `role`
            )
        VALUES (
            :username,
            :email,
            :password,
            :avatar,
            :role
            )
        ";
        try {
            $preparedQuery = $pdo->prepare($sql);
            $preparedQuery->execute([
                ':username' => $this->username,
                'email' => $this->email,
                'password' => $this->password,
                'avatar' => $this->avatar,
                'role' => $this->role,
            ]);
            $insertedRows = $preparedQuery->rowCount();
            if ($insertedRows > 0) {
                $this->id = $pdo->lastInsertId();
                return $this;
            }
        } catch (PDOException $e) {
            return $e->errorInfo;
        }
    }

    public function getConnectionInfo()
    {
        $jwt = $this->createToken();
        return ([
            'message' => "success",
            'token' => $jwt,
            'user' => [
                'id' => $this->getId(),
                'email' => $this->getEmail(),
                'username' => $this->getUsername(),
                'role' => $this->getRole(),
                'avatar' => $this->getAvatar(),
            ]
        ]);
    }

    public function createToken()
    {
        $secret_key = "TEST";
        $issuer_claim = "THE_ISSUER"; // this can be the servername
        $audience_claim = "THE_AUDIENCE";
        $issuedat_claim = time(); // issued at
        $notbefore_claim = $issuedat_claim; //not before in seconds
        $expire_claim = $issuedat_claim + 60; // expire time in seconds
        $token = [
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            "nbf" => $notbefore_claim,
            "exp" => $expire_claim,
            "data" => [
                "id" => $this->getId(),
                "username" => $this->getUsername(),
                "email" => $this->getEmail()
            ]
        ];

        $jwt = JWT::encode($token, $secret_key);
        return $jwt;
    }

    public static function checkToken($auth)
    {
        $secret_key = "TEST";
        $arr = explode(" ", $auth);
        $jwt = $arr[1];
        if ($jwt) {
            try {
                $decoded = JWT::decode($jwt, $secret_key, array('HS256'));
                return [
                    "message" => "success",
                ];
            } catch (\Exception $e) {
                return [
                    "message" => "fail",
                    "error" => $e->getMessage()
                ];
            }
        }
    }

    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `users`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, static::class);
        return $results;
    }

    public static function createCustomError($error){
        if($error[0]=="23000"){
            $arr=explode(" ",$error[2]);
            $duplicate=$arr[count($arr)-1];
            // return $duplicate;
            if($duplicate==="'username'"){
                return "Ce nom d'utilisateur est deja utilisé";
            }else{
                return "L'adresse Email est deja utilisée";
            }
        }
    }
    /**
     * Get the value of username
     *
     * @return  string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the value of email
     *
     * @return  string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the value of password
     *
     * @return  string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the value of avatar
     *
     * @return  string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Get the value of role
     *
     * @return  string
     */
    public function getRole()
    {
        return $this->role;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setEmail($email)
    {
        $this->email = htmlspecialchars($email);
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }
}
