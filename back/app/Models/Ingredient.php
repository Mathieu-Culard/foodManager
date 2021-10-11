<?php


/**
 * SELECT i.name,i.image,i.category_id, ui.quantity FROM ingredients i
 *INNER JOIN users_ingredients ui ON ui.ingredient_id = i.id
 *WHERE ui.user_id = 16 AND ui.needed=0
 */

namespace App\Models;

use App\Utils\Database;
use JsonSerializable;
use PDO;
use PDOException;

class Ingredient extends CoreModel  implements JsonSerializable
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
   * @var int
   */
  private $min_buy;
  /**
   * @var int
   */
  private $category_id;
  /**
   * @var int
   */
  private $unity_id;

  public static function findAll()
  {
    $categories = self::getCategories();
    $ingredients = [];
    foreach ($categories as $category) {
      $pdo = Database::getPDO();
      $sql = "SELECT i.id, i.name,i.image,i.min_buy, u.unity FROM ingredients i
      LEFT JOIN unity u ON u.id = i.unity_id
      WHERE i.category_id =" . $category['id'];
      $statement = $pdo->query($sql);
      $categoryIngredients = $statement->fetchAll(PDO::FETCH_CLASS, static::class);
      array_push($ingredients, ['name' => $category['name'], 'ingredients' => $categoryIngredients]);
    }
    return $ingredients;
  }

  public static function findUserIngredients($userId)
  {
    $categories = self::getCategories();
    $ingredients = [];
    foreach ($categories as $category) {

      $pdo = Database::getPDO();
      $sql = "SELECT i.id, i.name,i.image, ui.quantity, u.unity
        FROM ingredients i
        INNER JOIN users_ingredients ui 
        ON ui.ingredient_id = i.id
        LEFT JOIN unity u 
        ON u.id= i.unity_id
        WHERE ui.user_id = :userId 
        AND ui.needed=0
        AND i.category_id= :categoryId";
      $preparedQuery = $pdo->prepare($sql);
      $preparedQuery->bindValue(':userId', $userId);
      $preparedQuery->bindValue(':categoryId', $category['id']);
      $preparedQuery->execute();
      $categoryIngredients = $preparedQuery->fetchAll(PDO::FETCH_CLASS, static::class);
      array_push($ingredients, ['name' => $category['name'], 'ingredients' => $categoryIngredients]);
    }
    return $ingredients;
  }

  public static function getCategories()
  {
    $pdo = Database::getPDO();
    $sql = "SELECT id, name from categories";
    $statement = $pdo->query($sql);
    $categories = $statement->fetchAll();
    return $categories;
  }

  public static function addToStock($ingredients, $userId)
  {
    $pdo = Database::getPDO();
    foreach ($ingredients as $ingredient) {
      if (self::existInStock($ingredient->id, $userId)) {
        $sql = "SELECT quantity
          FROM users_ingredients
          WHERE user_id = :user_id
          AND ingredient_id=:ingredient_id";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':ingredient_id', $ingredient->id, PDO::PARAM_INT);
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $quantity = $result['quantity'] + $ingredient->quantity;
        $response=self::updateStockIngredient($ingredient->id, $userId, $quantity);
        if($response){
          return $response;
        }
        // self::updateStockIngredient($ingredient->id, $userId)
      } else {
        $sql = "INSERT INTO users_ingredients (
        user_id,
        ingredient_id,
        quantity,
        needed
        )
        VALUES (
          :user_id,
          :ingredient_id,
          :quantity,
          0
          )
        ";
        try {
          $statement = $pdo->prepare($sql);
          $statement->bindValue(':quantity', $ingredient->quantity, PDO::PARAM_INT);
          $statement->bindValue(':ingredient_id', $ingredient->id, PDO::PARAM_INT);
          $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
          $statement->execute();
        } catch (PDOException $e) {
          return $e->errorInfo;
        }
      }
    }
  }

  public static function existInStock($id, $userId)
  {
    $pdo = Database::getPDO();
    $sql = " SELECT COUNT(*)
            FROM users_ingredients
            WHERE user_id = :user_id
            AND ingredient_id=:ingredient_id";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':ingredient_id', $id, PDO::PARAM_INT);
    $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $statement->execute();
    $count = $statement->fetchColumn();
    if ($count > 0) {
      return true;
    }
    return false;
  }

  public static function updateStockIngredient($id, $userId, $quantity)
  {
    try {
      $pdo = Database::getPDO();
      $sql = "
    UPDATE users_ingredients
    SET quantity= :quantity
    WHERE ingredient_id= :id
    AND user_id= :userId
    ";
      $statement = $pdo->prepare($sql);
      $statement->bindValue(':quantity', $quantity, PDO::PARAM_INT);
      $statement->bindValue(':id', $id, PDO::PARAM_INT);
      $statement->bindValue(':userId', $userId, PDO::PARAM_INT);
      $statement->execute();
    } catch (PDOException $e) {
      return $e->errorInfo;
    }
  }

  public static function deleteStockIngredient($id, $userId)
  {
    try {
      $pdo = Database::getPDO();
      $sql = "
    DELETE FROM users_ingredients
    WHERE ingredient_id= :id
    AND user_id= :userId
    ";
      $statement = $pdo->prepare($sql);
      $statement->bindValue(':id', $id, PDO::PARAM_INT);
      $statement->bindValue(':userId', $userId, PDO::PARAM_INT);
      $statement->execute();
    } catch (PDOException $e) {
      return $e->errorInfo;
    }
  }

  public static function findRecipeIngredients($recipeId)
  {
    $pdo = Database::getPDO();
    $sql = "SELECT i.id, i.name, i.image, ri.quantity, u.unity
          FROM ingredients i 
          INNER JOIN recipe_ingredients ri 
          ON i.id=ri.ingredient_id 
          INNER JOIN unity u
          ON u.id=i.unity_id
          WHERE ri.recipe_id = :id";
    $preparedQuery = $pdo->prepare($sql);
    $preparedQuery->bindValue(':id', $recipeId);
    $preparedQuery->execute();
    $ingredients = $preparedQuery->fetchAll(PDO::FETCH_CLASS, static::class);
    return $ingredients;
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
   * Get the value of min_buy
   *
   * @return  int
   */
  public function getMinBuy()
  {
    return $this->min_buy;
  }

  /**
   * Set the value of min_buy
   *
   * @param  int  $min_buy
   *
   * @return  self
   */
  public function setMinBuy(int $min_buy)
  {
    $this->min_buy = $min_buy;

    return $this;
  }

  /**
   * Get the value of category_id
   *
   * @return  int
   */
  public function getCategoryId()
  {
    return $this->category_id;
  }

  /**
   * Set the value of category_id
   *
   * @param  int  $category_id
   *
   * @return  self
   */
  public function setCategoryId(int $category_id)
  {
    $this->category_id = $category_id;

    return $this;
  }

  /**
   * Get the value of unity_id
   *
   * @return  int
   */
  public function getUnityId()
  {
    return $this->unity_id;
  }

  /**
   * Set the value of unity_id
   *
   * @param  int  $unity_id
   *
   * @return  self
   */
  public function setUnityId(int $unity_id)
  {
    $this->unity_id = $unity_id;

    return $this;
  }
  public function jsonSerialize()
  {
    $vars = get_object_vars($this);

    return $vars;
  }
}
