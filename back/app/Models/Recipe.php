<?php

namespace App\Models;

use App\Utils\Database;
use App\Models\Ingredient;
use App\Models\Step;
use JsonSerializable;
use PDO;
use PDOException;
use Symfony\Component\VarDumper\Cloner\Data;

class Recipe extends CoreModel  implements JsonSerializable
{
  /**
   * @var string
   */
  private $name;

  /**
   * @var string
   */
  private $image;

  /**
   * @var bool
   */
  private $public;

  /**
   * @var bool
   */
  private $reported;

  /**
   * @var int
   */
  private $userId;

  /**
   * @var array
   */
  private $ingredients;
  /**
   * @var array
   */
  private $steps;



  /**
   * retrive all public recipes
   */
  public static function findAll()
  {
    $pdo = Database::getPDO();
    $sql = "SELECT `id`, `name`, `image`, `public`, `reported`, `user_id` as userId FROM recipes WHERE public=1";
    $statement = $pdo->query($sql);
    $recipes = $statement->fetchAll(PDO::FETCH_CLASS, static::class);
    return $recipes;
  }
  /**
   * retrive desired recipe infos
   */
  public static function find($id)
  {
    $pdo = Database::getPDO();
    $sql = "SELECT  `id`, `name`, `image`, `public`, `reported`, `user_id` as userId FROM recipes WHERE id= :id";
    $preparedQuery = $pdo->prepare($sql);
    $preparedQuery->bindValue(':id', $id);
    $preparedQuery->execute();
    $recipe = $preparedQuery->fetchObject(static::class);
    return $recipe;
  }

  // /**
  //  *retrieve all recipes owned by a particular user
  //  */
  // public static function findUserRecipes($id)
  // {
  //   $pdo = Database::getPDO();
  //   $sql = "SELECT * FROM recipes WHERE user_id= :user_id";
  //   $preparedQuery = $pdo->prepare($sql);
  //   $preparedQuery->bindValue(':user_id', $id);
  //   $preparedQuery->execute();
  //   $recipes = $preparedQuery->fetchAll(PDO::FETCH_CLASS, static::class);
  //   $result = [];
  //   $userStock = Ingredient::getUserStock($id);
  //   foreach ($recipes as $recipe) {
  //     $result[] = self::checkDoable($recipe, $userStock);
  //   }
  //   return $result;
  // }

  public static function checkDoable($recipe, $userStock)
  {

    // foreach ($recipes as $recipe) {
    $isDoable = true;
    $ingredients = Ingredient::findRecipeIngredients($recipe->getId());
    foreach ($ingredients as $ingredient) {
      if (!$ingredient->hasEnought($userStock)) {
        $isDoable = false;
      }
    }
    $recipe = get_object_vars($recipe);
    $recipe['isDoable'] = $isDoable;
    // $newRecipes[] = $recipe;
    // }
    return $recipe;
  }
  // public static function findRecipeSteps($id)
  // {
  //   $pdo = Database::getPDO();
  //   $sql = "SELECT * FROM steps WHERE recipe_id= :id";
  //   $preparedQuery = $pdo->prepare($sql);
  //   $preparedQuery->bindValue(':id', $id);
  //   $preparedQuery->execute();
  //   $steps = $preparedQuery->fetchAll();
  //   return $steps;
  // }

  public static function checkExist($id, $userId)
  {
    $pdo = Database::getPDO();
    $sql = "SELECT *
            FROM wanted_recipes
            WHERE user_id = :user_id
            AND recipe_id=:recipe_id";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $statement->bindValue(':recipe_id', $id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
  }

  public static function addWantedRecipe($id, $userId)
  {
    $pdo = Database::getPDO();
    $wantedRecipe = self::checkExist($id, $userId);
    if (!$wantedRecipe) {
      $quantity = 1;
      $sql = "INSERT INTO wanted_recipes (
        user_id,
        recipe_id,
        quantity
        )
        VALUES (
        :user_id,
        :recipe_id,
        :quantity
        )";
    } else {
      $quantity = $wantedRecipe['quantity'] + 1;
      $sql = "UPDATE wanted_recipes
      SET quantity= :quantity
      WHERE recipe_id= :recipe_id
      AND user_id= :user_id";
    }
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $statement->bindValue(':recipe_id', $id, PDO::PARAM_INT);
    $statement->bindValue(':quantity', $quantity, PDO::PARAM_INT);
    $statement->execute();
  }

  public static function removeWantedRecipe($recipe, $userId)
  {
    $quantity = $recipe['quantity'] - 1;
    if ($quantity == 0) {
      self::deleteWantedRecipe($recipe['recipe_id'], $userId);
    } else {
      echo $recipe['id'];
      $pdo = Database::getPDO();
      $sql = "UPDATE wanted_recipes
      SET quantity= :quantity
      WHERE recipe_id= :recipe_id
      AND user_id= :user_id";
      $statement = $pdo->prepare($sql);
      $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
      $statement->bindValue(':recipe_id', $recipe['recipe_id'], PDO::PARAM_INT);
      $statement->bindValue(':quantity', $quantity, PDO::PARAM_INT);
      $statement->execute();
    }
  }

  public static function deleteWantedRecipe($id, $userId)
  {
    $pdo = Database::getPDO();
    $sql = "DELETE FROM wanted_recipes
            WHERE recipe_id= :recipe_id
            AND user_id= :user_id
            ";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':recipe_id', $id, PDO::PARAM_INT);
    $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $statement->execute();
  }

  public static function deleteAllWantedRecipe($id)
  {
    $pdo = Database::getPDO();
    $sql = "DELETE FROM wanted_recipes
            WHERE recipe_id= :recipe_id
            ";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':recipe_id', $id, PDO::PARAM_INT);
    $statement->execute();
  }

  // public static function getWantedRecipes($userId)
  // {
  //   $pdo = Database::getPDO();  //fetch wanted recipes
  //   $sql = "SELECT * 
  //           FROM wanted_recipes
  //           WHERE user_id = :user_id";
  //   $statement = $pdo->prepare($sql);
  //   $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
  //   $statement->execute();
  //   $wantedRecipe = $statement->fetchAll(PDO::FETCH_ASSOC);
  //   // Recipe::getWantedRecipes($granted['userId']);
  //   $userStock = Ingredient::getUserIngredients($userId); // fetch user stock
  //   // echo json_encode($userStock);
  //   $wantedRecipesIngredients = [];
  //   foreach ($wantedRecipe as $recipe) {  //add the needed ingredients to the wanted recipes
  //     $ingredients = Ingredient::findRecipeIngredients($recipe['recipe_id']);
  //     // $processedIngredients=
  //     $wantedRecipesIngredients[] = [ //create data to return 
  //       'id' => $recipe['recipe_id'],
  //       'name' => Recipe::find($recipe['recipe_id'])->getName(),
  //       'quantity' => $recipe['quantity'],
  //       'ingredients' =>  Ingredient::getRightAmountOfIngredients($ingredients, $recipe['quantity']),
  //     ];
  //   }
  //   // echo json_encode(empty($wantedRecipesIngredients));
  //   if (!empty($wantedRecipesIngredients)) {
  //     $test = Ingredient::reduceIngredients($wantedRecipesIngredients, []);
  //     return Ingredient::getIngredientsToBuy($test, $userStock);
  //   }
  //   return [];
  //   // self::getRightAmountOfIngredients($ingredients, $recipe['quantity']
  // }


  public static function checkRecipeData($name, $filteredIngredients, $steps)
  {
    if (trim($name) === "") {
      return "veuillez ajouter un nom a votre recette";
    }
    if (empty($filteredIngredients)) {
      // echo $ingredients;
      echo 'oui';
      return "veuillez ajouter au moins un ingredient à votre recette";
    }
    if (empty($steps)) {
      return "veuillez ajouter au moins une étape à votre recette";
    }
    if (empty($_FILES['picture'])) {
      return "veuillez ajouter une photo de votre recette";
    }
  }

  /**
   * insert à new recipe
   */
  public function insert()
  {
    $pdo = Database::getPDO();
    $sql = "INSERT INTO recipes (
      name,
      image,
      public,
      reported,
      user_id
      )
      VALUES (
      :name,
      :image,
      :public,
      :reported,
      :user_id
      )";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':name', $this->name);
    $statement->bindValue(':image', $this->image);
    $statement->bindValue(':public', $this->public ? 1 : 0);
    $statement->bindValue(':reported', 0);
    $statement->bindValue(':user_id', $this->userId);
    $statement->execute();
    $insertedRows = $statement->rowCount();
    if ($insertedRows > 0) {
      $recipeId = $pdo->lastInsertId();
      return $recipeId;
    } else {
      return false;
    }
  }

  public function delete()
  {
    $pdo = Database::getPDO();

    // Ecriture de la requête
    $sql = 'DELETE FROM `recipes`
                WHERE id = :id';

    $statement = $pdo->prepare($sql);
    $statement->bindValue(':id', $this->id, PDO::PARAM_INT);
    $statement->execute();
    if ($statement->rowCount() > 0) {
      unlink(__DIR__ . "/../../public/assets/recipes/" . $this->image);
    }
    return ($statement->rowCount() > 0);
    // self::deleteAllWantedRecipe($this->id);
    // On retourne VRAI, si au moins une ligne supprimée
  }
  /**
   * update recipe infos
   */
  public function updateRecipe()
  {
    $pdo = Database::getPDO();
    $sql = "UPDATE recipes
    SET
    name = :name,
    public = :public,
    image = :image
    WHERE id = :id
    ";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':name', $this->name);
    $statement->bindValue(':public', $this->public ? 1 : 0);
    $statement->bindValue(':image', $this->image);
    $statement->bindValue(':id', $this->id);
    $statement->execute();
  }

  /**
   * add various ingredients to a recipe
   */
  public function insertRecipesIngredients()
  {
    $pdo = Database::getPDO();
    foreach ($this->ingredients as $ingredient) {
      $sql = "INSERT INTO recipe_ingredients (
          ingredient_id,
          recipe_id,
          quantity
        ) VALUES (
          :ingredient_id,
          :recipe_id,
          :quantity
          )
        ";
      $statement = $pdo->prepare($sql);
      $statement->bindValue(':ingredient_id', $ingredient['id']);
      $statement->bindValue(':recipe_id', $this->id);
      $statement->bindValue(':quantity', $ingredient['quantity']);
      $statement->execute();
      if ($statement->rowCount() == 0) {
        return false;
      }
    }
    return true;
  }
  /**
   * Get the value of name
   *
   * @return  string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Set the value of name
   *
   * @param  string  $name
   *
   * @return  self
   */
  public function setName(string $name)
  {
    $this->name = $name;

    return $this;
  }

  /**
   * Get the value of image
   *
   * @return  string
   */
  public function getImage()
  {
    return $this->image;
  }

  /**
   * Set the value of image
   *
   * @param  string  $image
   *
   * @return  self
   */
  public function setImage(string $image)
  {
    $this->image = $image;

    return $this;
  }

  /**
   * Get the value of public
   *
   * @return  bool
   */
  public function getPublic()
  {
    return $this->public;
  }

  /**
   * Set the value of public
   *
   * @param  bool  $public
   *
   * @return  self
   */
  public function setPublic(bool $public)
  {
    $this->public = $public;

    return $this;
  }

  /**
   * Get the value of reported
   *
   * @return  bool
   */
  public function getReported()
  {
    return $this->reported;
  }

  /**
   * Set the value of reported
   *
   * @param  bool  $reported
   *
   * @return  self
   */
  public function setReported(bool $reported)
  {
    $this->reported = $reported;

    return $this;
  }

  /**
   * Get the value of userId
   *
   * @return  int
   */
  public function getUserId()
  {
    return $this->userId;
  }

  /**
   * Set the value of userId
   *
   * @param  int  $userId
   *
   * @return  self
   */
  public function setUserId(int $userId)
  {
    $this->userId = $userId;

    return $this;
  }
  public function jsonSerialize()
  {
    $vars = get_object_vars($this);

    return $vars;
  }

  /**
   * Get the value of ingredients
   *
   * @return  array
   */
  public function getIngredients()
  {
    return $this->ingredients;
  }

  /**
   * Set the value of ingredients
   *
   * @param  array  $ingredients
   *
   * @return  self
   */
  public function setIngredients(array $ingredients)
  {
    $this->ingredients = $ingredients;

    return $this;
  }

  /**
   * Get the value of steps
   *
   * @return  array
   */
  public function getSteps()
  {
    return $this->steps;
  }

  /**
   * Set the value of steps
   *
   * @param  array  $steps
   *
   * @return  self
   */
  public function setSteps(string $step)
  {
    $this->steps[] = $step;

    return $this;
  }
}
