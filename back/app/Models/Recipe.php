<?php

namespace App\Models;

use App\Utils\Database;
use JsonSerializable;
use PDO;

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



  public static function findAll()
  {
    $pdo = Database::getPDO();
    $sql = "SELECT * FROM recipes";
    $statement = $pdo->query($sql);
    $recipes = $statement->fetchAll(PDO::FETCH_CLASS, static::class);
    return $recipes;
  }

  public static function find($id)
  {
    $pdo = Database::getPDO();
    $sql = "SELECT * FROM recipes WHERE id= :id";
    $preparedQuery = $pdo->prepare($sql);
    $preparedQuery->bindValue(':id',$id);
    $preparedQuery->execute();
    $recipe = $preparedQuery->fetchObject(static::class);

    
    return $recipe;
  }

  public static function findRecipeSteps($id){
    $pdo = Database::getPDO();
    $sql = "SELECT * FROM steps WHERE recipe_id= :id";
    $preparedQuery = $pdo->prepare($sql);
    $preparedQuery->bindValue(':id',$id);
    $preparedQuery->execute();
    $steps = $preparedQuery->fetchAll();
    return $steps;
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
