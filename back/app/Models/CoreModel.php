<?php

namespace App\Models;

class CoreModel
{
  /**
   * @var int
   */
  protected $id;
  /**
   * @var string
   */
  protected $createdAt;
  /**
   * @var string
   */
  protected $updatedAt;


  // public function save() {
  //     // Si l'objet a déjà un identifiant
  //     // C'est qu'ilexiste déjà dans la table
  //     // Donc on met à jour
  //     if ($this->id > 0) {
  //         // On retourne le retour de la méthode update()
  //         return $this->update();
  //     }
  //     // Sinon, on peut ajouter
  //     else {
  //         // On retourne le retour de la méthode insert()
  //         return $this->insert();
  //     }
  // }


  public static function createPicture($name, $folder = 'recipes')
  {
    $arr = explode(".", $_FILES['picture']['name']);
    $ext = end($arr);
    $picName = str_replace(' ','-',$name) . "." . $ext;
    move_uploaded_file($_FILES['picture']['tmp_name'], __DIR__ . "/../../public/assets/".$folder."/" . $picName);
    return $picName;
  }
  /**
   * Get the value of id
   *
   * @return  int
   */
  public function getId(): int
  {
    return $this->id;
  }

  /**
   * Get the value of created_at
   *
   * @return  string
   */
  public function getCreatedAt(): string
  {
    return $this->createdAt;
  }

  /**
   * Get the value of updated_at
   *
   * @return  string
   */
  public function getUpdatedAt(): string
  {
    return $this->updatedAt;
  }

  /**
   * Set the value of id
   *
   * @param  int  $id
   *
   * @return  self
   */ 
  public function setId(int $id)
  {
    $this->id = $id;

    return $this;
  }
}
