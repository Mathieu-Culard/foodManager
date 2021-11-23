<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Unity extends CoreModel
{

  /**
   * @var string
   */
  private $unity;
  
  public static function find($id){
    $pdo = Database::getPDO();
    $sql="SELECT * FROM unity WHERE id = :id";
    $statement=$pdo->prepare($sql);
    $statement->bindValue(':id',$id, PDO::PARAM_INT);
    $statement->execute();
    $unity=$statement->fetchObject(Unity::class);
    return $unity;
  }

  public static function findAll(){
    $pdo=Database::getPDO();
    $sql="SELECT * FROM unity";
    $statement=$pdo->query($sql);
    $units=$statement->fetchAll(PDO::FETCH_CLASS, Unity::class);
    return $units;
  }

  public function update()
  {
    $pdo = Database::getPDO();
    $sql = "UPDATE unity
          SET
            unity = :unity
          WHERE id=:id
      ";

    $statement = $pdo->prepare($sql);
    $statement->bindValue(':id', $this->id, PDO::PARAM_INT);
    $statement->bindValue(':unity', $this->unity, PDO::PARAM_STR);
    $statement->execute();

    return ($statement->rowCount() > 0);
  }

  public function insert()
  {
    $pdo = Database::getPDO();
    $sql = "INSERT INTO unity (
      unity
      ) 
      VALUES 
      (
      :unity
      )
    ";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':unity', $this->unity, PDO::PARAM_STR);
    $statement->execute();
    return ($statement->rowCount() > 0);
  }


  public function delete()
  {
    $pdo = Database::getPDO();
    $sql = "DELETE FROM unity WHERE id = :id";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':id', $this->id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->rowCount()>0;
  }
  /**
   * Get the value of unity
   *
   * @return  string
   */ 
  public function getUnity()
  {
    return $this->unity;
  }

  /**
   * Set the value of unity
   *
   * @param  string  $unity
   *
   * @return  self
   */ 
  public function setUnity(string $unity)
  {
    $this->unity = $unity;

    return $this;
  }
}