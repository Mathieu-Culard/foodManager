<?php

namespace App\Models;

use App\Utils\Database;
use JsonSerializable;
use PDO;
use PDOException;

class Step extends CoreModel implements JsonSerializable
{
  /**
   * @var string
   */
  private $text;
  /**
   * @var int
   */
  private $recipeId;

  /**
   * retrieve all steps linked to a recipe
   */
  public static function findRecipeSteps($id)
  {
    $pdo = Database::getPDO();
    $sql = "SELECT * FROM steps WHERE recipe_id= :id";
    $preparedQuery = $pdo->prepare($sql);
    $preparedQuery->bindValue(':id', $id);
    $preparedQuery->execute();
    $steps = $preparedQuery->fetchAll(PDO::FETCH_CLASS, static::class);
    return $steps;
  }


  /**
   * add new steps to a recipe
   */
  public function insert()
  {
      $pdo = Database::getPDO();
      $sql = "INSERT INTO steps (
        text,
        recipe_id
      ) VALUES (
        :text,
        :recipe_id
        )
      ";
      $statement = $pdo->prepare($sql);
      $statement->bindValue(':text', $this->text);
      $statement->bindValue(':recipe_id', $this->recipeId);
      $statement->execute();
      $insertedRows = $statement->rowCount();
      return $insertedRows > 0 ;
  }
  /**
   *delete all step of a recipe 
   */
  public static function deleteSteps($recipeId)
  {
    $pdo = Database::getPDO();
    $sql = "DELETE FROM steps WHERE recipe_id = :recipeId";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':recipeId', $recipeId);
    $statement->execute();
  }

  public function jsonSerialize()
  {
    $vars = get_object_vars($this);

    return $vars;
  }

  /**
   * Get the value of text
   *
   * @return  string
   */
  public function getText()
  {
    return $this->text;
  }

  /**
   * Set the value of text
   *
   * @param  string  $text
   *
   * @return  self
   */
  public function setText(string $text)
  {
    $this->text = $text;

    return $this;
  }

  /**
   * Get the value of recipeId
   *
   * @return  int
   */
  public function getRecipeId()
  {
    return $this->recipeId;
  }

  /**
   * Set the value of recipeId
   *
   * @param  int  $recipeId
   *
   * @return  self
   */
  public function setRecipeId(int $recipeId)
  {
    $this->recipeId = $recipeId;

    return $this;
  }
}
