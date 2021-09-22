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
  protected $created_at;
  /**
   * @var string
   */
  protected $updated_at;


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
    return $this->created_at;
  }

  /**
   * Get the value of updated_at
   *
   * @return  string
   */
  public function getUpdatedAt(): string
  {
    return $this->updated_at;
  }
}