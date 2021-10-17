<?php

namespace App\Models;

use App\Utils\Database;
use App\Models\Ingredient;
use App\Models\Step;
use JsonSerializable;
use PDO;
use PDOException;

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
  private $user_id;


  /**
   * retrive all public recipes
   */
  public static function findAll()
  {
    $pdo = Database::getPDO();
    $sql = "SELECT * FROM recipes WHERE public=1";
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
    $sql = "SELECT * FROM recipes WHERE id= :id";
    $preparedQuery = $pdo->prepare($sql);
    $preparedQuery->bindValue(':id', $id);
    $preparedQuery->execute();
    $recipe = $preparedQuery->fetchObject(static::class);
    return $recipe;
  }

  /**
   *retrieve all recipes owned by a particular user
   */
  public static function findUserRecipes($id)
  {
    $pdo = Database::getPDO();
    $sql = "SELECT * FROM recipes WHERE user_id= :user_id";
    $preparedQuery = $pdo->prepare($sql);
    $preparedQuery->bindValue(':user_id', $id);
    $preparedQuery->execute();
    $recipes = $preparedQuery->fetchAll(PDO::FETCH_CLASS, static::class);
    return $recipes;
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

  /**
   * insert à new recipe
   */
  public static function addRecipe($name, $shared, $ingredients, $steps, $userId, $picName)
  {

    $pdo = Database::getPDO();
    $error = [];
    // $shared = $data['shared'];
    // $name = $data['name'];
    // $ingredients = json_decode($data['ingredients'], true);
    // $steps = json_decode($data['steps'], true);
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
    try {
      //insert the new recipe
      $statement = $pdo->prepare($sql);
      $statement->bindValue(':name', $name);
      $statement->bindValue(':image', $picName);
      $statement->bindValue(':public', $shared ? 1 : 0);
      $statement->bindValue(':reported', 0);
      $statement->bindValue(':user_id', $userId);
      $statement->execute();
      $insertedRows = $statement->rowCount();
      if ($insertedRows > 0) { // if it worked, insert steps and ingredients linked to that recipe
        $recipeId = $pdo->lastInsertId();
        Ingredient::addRecipesIngredients($ingredients, $recipeId);
        Step::addRecipeSteps($steps, $recipeId);
        return $recipeId;
        // if (!empty($error)) {
        //   return empty([]);
        //   // return $error;
        // } else {
        //   return 'shhheeeeeeeeeehhhshhheeeeeeeeeehhh';
        //   // return $recipeId;
        // }
      } else {
        return 'ckc';
      }
    } catch (PDOException $e) {
      return [
        'error' => $e->errorInfo,
        'model' => 'recipe'
      ];
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
    // On retourne VRAI, si au moins une ligne supprimée
    return ($statement->rowCount() > 0);
  }
  /**
   * update recipe infos
   */
  public function updateRecipe($picName)
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
    $statement->bindValue(':image', $picName);
    $statement->bindValue(':id', $this->id);
    $statement->execute();
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
}
