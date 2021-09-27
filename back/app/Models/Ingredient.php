<?php

namespace App\Models;

use App\Utils\Database;
use JsonSerializable;
use PDO;

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

  public static function findRecipeIngredients($recipeId)
  {
    $pdo = Database::getPDO();
    $sql = "SELECT *, ri.quantity, u.unity
          FROM ingredients i 
          INNER JOIN recipe_ingredients ri 
          ON i.id=ri.ingredient_id 
          INNER JOIN unity u
          ON u.id=i.unity_id
          WHERE ri.recipe_id = :id";
    $preparedQuery = $pdo->prepare($sql);
    $preparedQuery->bindValue(':id',$recipeId);
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
