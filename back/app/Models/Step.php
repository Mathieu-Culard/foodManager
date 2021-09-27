<?php

namespace App\Models;

use App\Utils\Database;
use JsonSerializable;
use PDO;

class Step extends CoreModel implements JsonSerializable
{
  /**
   * @var string
   */
  private $text;


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

  public function jsonSerialize()
  {
    $vars = get_object_vars($this);

    return $vars;
  }
}
