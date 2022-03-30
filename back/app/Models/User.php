<?php

namespace App\Models;

use App\Utils\Database;
use App\Models\Ingredient;
use App\Models\Recipe;
use PDO;
use \Firebase\JWT\JWT;
use PDOException;
use JsonSerializable;

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

    /**
     * @var array
     */
    private $stock;
    /**
     * @var array
     */
    private $shop;
    /**
     * @var array
     */
    private $recipesShop;
    /**
     * @var array
     */
    private $recipes;

    /**
     * retrive a user by its username
     */
    public static function find($username)
    {
        $pdo = Database::getPDO();
        $sql = "SELECT * FROM `users` WHERE `username` = :username ";
        $preparedQuery = $pdo->prepare($sql);
        $preparedQuery->execute([
            ':username' => $username,
        ]);
        $user = $preparedQuery->fetchObject(static::class);
        return $user;
    }

    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `users`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, static::class);
        return $results;
    }

    public static function findbyId($id)
    {
        $pdo = Database::getPDO();
        $sql = "SELECT username, avatar, email, id, role FROM `users` WHERE id = :id ";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        $user = $statement->fetchObject(static::class);
        return $user;
    }


    // /**
    //  * retrive the infos of the user that own a particular recipe
    //  */
    // public static function findRecipeOwner($recipeId)
    // {
    //     $pdo = Database::getPDO();
    //     $sql = "SELECT u.avatar, u.email, u.username, u.id
    //             FROM users u
    //             INNER JOIN recipes r
    //             ON r.user_id=u.id
    //             WHERE r.id= :recipeId";

    //     $preparedQuery = $pdo->prepare($sql);
    //     $preparedQuery->bindValue(':recipeId', $recipeId);
    //     $preparedQuery->execute();
    //     $steps = $preparedQuery->fetchObject(static::class);
    //     return $steps;
    // }




    public function changeRole()
    {
        $pdo = Database::getPDO();
        $sql = "UPDATE users
                SET 
                role= :role
                WHERE id = :id
        ";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':role', $this->role, PDO::PARAM_STR);
        $statement->bindValue(':id', $this->id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->rowCount() > 0;
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
            $arr = explode(" ", $e->errorInfo[2]);
            $duplicate = $arr[count($arr) - 1];
            if ($duplicate === "'username'") {
                return "Ce nom d'utilisateur est deja utilisé";
            } else {
                return "L'adresse e-mail est deja utilisée";
            }
        }
    }

    /**
     * retrive all the data that app needs when a user connects
     */
    public function getConnectionInfo()
    {
        $jwt = $this->createToken();
        $refreshToken = $this->createToken();
        $this->stock = $this->findUserIngredients();
        $this->shop = $this->findUserIngredients('shop');
        $this->recipesShop = $this->getWantedRecipes();
        $this->recipes = $this->findUserRecipes();
        return ([
            'token' => $jwt,
            'user' => $this,
            'refreshToken' => $refreshToken,
        ]);
    }

    /**
     * create a JWT token that the user will use to access the API
     */
    public function createToken()
    {
        $configData = parse_ini_file(__DIR__ . '/../config.ini');
        $issuer_claim = "localhost:8000";
        $audience_claim = "localhost:8080";
        $issuedat_claim = time();
        $notbefore_claim = $issuedat_claim;
        $expire_claim = $issuedat_claim + 3600;
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

        $jwt = JWT::encode($token, $configData['JWT_KEY']);
        return $jwt;
    }


    public static function checkToken($auth)
    {
        $configData = parse_ini_file(__DIR__ . '/../config.ini');
        $arr = explode(" ", $auth);
        $jwt = $arr[1];
        if ($jwt) {
            try {
                $decoded = JWT::decode($jwt, $configData['JWT_KEY'], array('HS256'));
                return self::findbyId($decoded->data->id);
            } catch (\Exception $e) {
                return false;
            }
        }
    }
    public static function checkRefreshToken($auth)
    {
        $configData = parse_ini_file(__DIR__ . '/../config.ini');
        try {
            $decoded = JWT::decode($auth, $configData['JWT_KEY'], array('HS256'));
            return self::findbyId($decoded->data->id);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * return users's ingredients sorted by food category
     */
    public function findUserIngredients($identifier = 'stock')
    {
        $categories = Ingredient::getCategories();
        $ingredients = [];
        $needed = $identifier == 'shop' ? 1 : 0;
        foreach ($categories as $category) {
            $pdo = Database::getPDO();
            $sql = "SELECT i.id, i.name,i.image, ui.quantity, u.unity, i.min_buy as minBuy
        FROM ingredients i
        INNER JOIN users_ingredients ui 
        ON ui.ingredient_id = i.id
        LEFT JOIN unity u 
        ON u.id= i.unity_id
        WHERE ui.user_id = :userId 
        AND ui.needed= :needed
        AND i.category_id= :categoryId";
            $preparedQuery = $pdo->prepare($sql);
            $preparedQuery->bindValue(':userId', $this->id);
            $preparedQuery->bindValue(':categoryId', $category['id']);
            $preparedQuery->bindValue(':needed', $needed);
            $preparedQuery->execute();
            $categoryIngredients = $preparedQuery->fetchAll(PDO::FETCH_CLASS, Ingredient::class);
            array_push($ingredients, ['name' => $category['name'], 'ingredients' => $categoryIngredients]);
        }
        return $ingredients;
    }

    public function getWantedRecipes()
    {
        $pdo = Database::getPDO();  //fetch wanted recipes
        $sql = "SELECT * 
            FROM wanted_recipes
            WHERE user_id = :user_id";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':user_id', $this->id, PDO::PARAM_INT);
        $statement->execute();
        $wantedRecipe = $statement->fetchAll(PDO::FETCH_ASSOC);
        // Recipe::getWantedRecipes($granted['userId']);
        $userStock = Ingredient::getUserIngredients($this->id); // fetch user stock
        // echo json_encode($userStock);
        $wantedRecipesIngredients = [];
        foreach ($wantedRecipe as $recipe) {  //add the needed ingredients to the wanted recipes
            $ingredients = Ingredient::findRecipeIngredients($recipe['recipe_id']);
            // $processedIngredients=
            $wantedRecipesIngredients[] = [ //create data to return 
                'id' => $recipe['recipe_id'],
                'name' => Recipe::find($recipe['recipe_id'])->getName(),
                'quantity' => $recipe['quantity'],
                'ingredients' =>  Ingredient::getRightAmountOfIngredients($ingredients, $recipe['quantity']),
            ];
        }
        // echo json_encode(empty($wantedRecipesIngredients));
        if (!empty($wantedRecipesIngredients)) {
            $reducedIngredient = Ingredient::reduceIngredients($wantedRecipesIngredients, []);
            return Ingredient::getIngredientsToBuy($reducedIngredient, $userStock);
        }
        return [];
        // self::getRightAmountOfIngredients($ingredients, $recipe['quantity']
    }

    /**
     *retrieve all recipes owned by a particular user
     */
    public function findUserRecipes()
    {
        $pdo = Database::getPDO();
        $sql = "SELECT * FROM recipes WHERE user_id= :user_id";
        $preparedQuery = $pdo->prepare($sql);
        $preparedQuery->bindValue(':user_id', $this->id);
        $preparedQuery->execute();
        $recipes = $preparedQuery->fetchAll(PDO::FETCH_CLASS, static::class);
        $result = [];
        $userStock = Ingredient::getUserStock($this->id);
        foreach ($recipes as $recipe) {
            $result[] = Recipe::checkDoable($recipe, $userStock);
        }
        return $result;
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

 // public static function createCustomError($error)
    // {
    //     if ($error[0] == "23000") {
    //         $arr = explode(" ", $error[2]);
    //         $duplicate = $arr[count($arr) - 1];
    //         // return $duplicate;
    //         if ($duplicate === "'username'") {
    //             return "Ce nom d'utilisateur est deja utilisé";
    //         } else {
    //             return "L'adresse Email est deja utilisée";
    //         }
    //     }
    // }
