<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Category extends CoreModel
{

  /**
   * @var string
   */
  private $name;
  
  public static function find($id){
    $pdo = Database::getPDO();
    $sql="SELECT * FROM categories WHERE id = :id";
    $statement=$pdo->prepare($sql);
    $statement->bindValue(':id',$id, PDO::PARAM_INT);
    $statement->execute();
    $category=$statement->fetchObject(Category::class);
    return $category;
  }

  public static function findAll(){
    $pdo=Database::getPDO();
    $sql="SELECT * FROM categories";
    $statement=$pdo->query($sql);
    $categories=$statement->fetchAll(PDO::FETCH_CLASS, Category::class);
    return $categories;
  }

  public function update()
  {
    $pdo = Database::getPDO();
    $sql = "UPDATE categories
          SET
            name = :name
          WHERE id=:id
      ";

    $statement = $pdo->prepare($sql);
    $statement->bindValue(':id', $this->id, PDO::PARAM_INT);
    $statement->bindValue(':name', $this->name, PDO::PARAM_STR);
    $statement->execute();

    return ($statement->rowCount() > 0);
  }

  public function insert()
  {
    $pdo = Database::getPDO();
    $sql = "INSERT INTO categories (
      name
      ) 
      VALUES 
      (
      :name
      )
    ";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':name', $this->name, PDO::PARAM_STR);
    $statement->execute();
    return ($statement->rowCount() > 0);
  }


  public function delete()
  {
    $pdo = Database::getPDO();
    $sql = "DELETE FROM categories WHERE id = :id";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':id', $this->id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->rowCount()>0;
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
}