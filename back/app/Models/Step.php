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
  public static function addRecipeSteps($steps, $recipeId)
  {
    $pdo = Database::getPDO();
    foreach ($steps as $step) {
      $sql = "INSERT INTO steps (
        text,
        recipe_id
        ) VALUES (
          :text,
          :recipe_id
          )
        ";
      try {
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':text', $step);
        $statement->bindValue(':recipe_id', $recipeId);
        $statement->execute();
      } catch (PDOException $e) {
        return [
          'error' => $e->errorInfo,
          'model' => 'step'
        ];
      }
    }
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
}
