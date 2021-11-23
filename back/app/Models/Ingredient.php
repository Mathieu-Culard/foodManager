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
use App\Models\Category;
use App\Models\Unity;


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

  public static function find($id)
  {
    $pdo = Database::getPDO();
    $sql = "SELECT * FROM ingredients WHERE id = :id";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(":id", $id, PDO::PARAM_INT);
    $statement->execute();
    $ingredient = $statement->fetchObject(Ingredient::class);
    return $ingredient;
  }

  public static function findAll()
  {
    $pdo = Database::getPDO();
    $sql = "SELECT * FROM ingredients";
    $statement = $pdo->query($sql);
    $ingredients = $statement->fetchAll(PDO::FETCH_CLASS, static::class);
    return $ingredients;
  }

  public function findCategory()
  {
    $id = $this->category_id;
    $category = Category::find($id);
    if (!$category) {
      return new Category();
    }
    return $category;
  }

  public function findUnity()
  {
    $id = $this->unity_id;
    $unity = Unity::find($id);
    if (!$unity) {
      return new Unity();
    }
    return $unity;
  }

  public function update()
  {
    $pdo = Database::getPDO();
    $sql = "UPDATE ingredients
          SET
            name = :name,
            image = :image,
            min_buy = :min_buy,
            category_id = :category_id,
            unity_id = :unity_id
          WHERE id=:id
      ";

    $statement = $pdo->prepare($sql);
    $statement->bindValue(':id', $this->id, PDO::PARAM_INT);
    $statement->bindValue(':name', $this->name, PDO::PARAM_STR);
    $statement->bindValue(':image', $this->image, PDO::PARAM_STR);
    $statement->bindValue(':min_buy',  $this->min_buy == 0 || $this->min_buy == 1 ? null :  $this->min_buy, PDO::PARAM_INT);
    $statement->bindValue(':category_id', $this->category_id, PDO::PARAM_INT);
    $statement->bindValue(':unity_id',  $this->unity_id == -1 ? null : $this->unity_id, PDO::PARAM_INT);
    $statement->execute();

    return ($statement->rowCount() > 0);
  }

  public function insert()
  {
    $pdo = Database::getPDO();
    $sql = "INSERT INTO ingredients (
      name,
      image,
      min_buy,
      category_id,
      unity_id
      ) 
      VALUES 
      (
      :name,
      :image,
      :min_buy,
      :category_id,
      :unity_id
      )
    ";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':name', $this->name, PDO::PARAM_STR);
    $statement->bindValue(':image', $this->image, PDO::PARAM_STR);
    $statement->bindValue(':min_buy', $this->min_buy == 0 || $this->min_buy == 1 ? null :  $this->min_buy, PDO::PARAM_INT);
    $statement->bindValue(':category_id', $this->category_id, PDO::PARAM_INT);
    $statement->bindValue(':unity_id', $this->unity_id == -1 ? null : $this->unity_id, PDO::PARAM_INT);
    $statement->execute();
    return ($statement->rowCount() > 0);
  }


  public function delete()
  {
    $pdo = Database::getPDO();
    $sql = "DELETE FROM ingredients WHERE id = :id";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':id', $this->id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->rowCount() > 0;
  }
  /**
   * return all possible ingredients sorted by category
   */
  public static function findAllByCategories()
  {
    $categories = self::getCategories();
    $ingredients = [];
    $pdo = Database::getPDO();
    foreach ($categories as $category) {
      $sql = "SELECT i.id, i.name,i.image,i.min_buy as minBuy, u.unity FROM ingredients i
      LEFT JOIN unity u ON u.id = i.unity_id
      WHERE i.category_id =" . $category['id'];
      $statement = $pdo->query($sql);
      $categoryIngredients = $statement->fetchAll(PDO::FETCH_CLASS, static::class);
      array_push($ingredients, ['name' => $category['name'], 'ingredients' => $categoryIngredients]);
    }
    return $ingredients;
  }

  public static function getShoppingList($userId)
  {
    $pdo = Database::getPDO();
    $sql = "SELECT ingredient_id as id, quantity 
          FROM users_ingredients 
          WHERE user_id = :user_id
          AND needed =1";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':user_id', $userId);
    $statement->execute();
    $shoppingList = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $shoppingList;
  }

  /**
   * return users's ingredients sorted by food category
   */
  public static function findUserIngredients($userId, $identifier = 'stock')
  {
    $categories = self::getCategories();
    $ingredients = [];
    $needed = $identifier == 'shop' ? 1 : 0;
    foreach ($categories as $category) {

      $pdo = Database::getPDO();
      $sql = "SELECT i.id, i.name,i.image, ui.quantity, u.unity, i.min_buy as minBuy
        FROM ingredients i
        INNER JOIN users_ingredients ui 
        ON ui.ingredient_id = i.id
        LEFT JOIN unity u 
        ON u.id= i.unity_id
        WHERE ui.user_id = :userId 
        AND ui.needed= :needed
        AND i.category_id= :categoryId";
      $preparedQuery = $pdo->prepare($sql);
      $preparedQuery->bindValue(':userId', $userId);
      $preparedQuery->bindValue(':categoryId', $category['id']);
      $preparedQuery->bindValue(':needed', $needed);
      $preparedQuery->execute();
      $categoryIngredients = $preparedQuery->fetchAll(PDO::FETCH_CLASS, static::class);
      array_push($ingredients, ['name' => $category['name'], 'ingredients' => $categoryIngredients]);
    }
    return $ingredients;
  }

  /**
   * Combine ingredients from user stock and shopping list 
   */
  public static function getUserIngredients($userId)
  {
    $stock = self::getUserStock($userId);
    $shoppingList = self::getUserShoppingList($userId);
    $userStock = [];
    foreach ($stock as $stockIngredient) {
      $found = false;
      for ($i = 0; $i < count($shoppingList); $i++) {
        if ($stockIngredient['ingredient_id'] === $shoppingList[$i]['ingredient_id']) {
          $found = true;
          $userStock[] = [
            'ingredient_id' => $stockIngredient['ingredient_id'],
            'quantity' => $stockIngredient['quantity'] + $shoppingList[$i]['quantity']
          ];
          $shoppingList[$i]['ingredient_id'] = -1;
        }
      }
      if (!$found) {
        $userStock[] = [
          'ingredient_id' => $stockIngredient['ingredient_id'],
          'quantity' => $stockIngredient['quantity'],
        ];
      }
    }
    foreach ($shoppingList as $shopIngredient) {
      if ($shopIngredient['ingredient_id'] != -1) {
        $userStock[] = [
          'ingredient_id' => $shopIngredient['ingredient_id'],
          'quantity' => $shopIngredient['quantity'],
        ];
      }
    }
    return $userStock;
  }

  /**
   * compare the ingredients that the user owns in his stock and in his shopping list to the ingredients needed
   * by the recipes he wants to add the right amount of ingredients in his shopping list
   */
  public static function getIngredientsToBuy($wantedIngredients, $userStock)
  {
    $result = [];
    foreach ($wantedIngredients as $recipe) { //for each desired recipe
      $ingredientsToBuy = [];
      // echo json_encode($recipe);
      foreach ($recipe['ingredients'] as $ingredient) { // for each ingredient needed by the recipe
        $found = false;
        // $ingredient = get_object_vars($ingredient);
        foreach ($userStock as $userIngredient) { // for each ingredients that the user owns in his stock and shopping list
          if ($ingredient['id'] == $userIngredient['ingredient_id']) { //add the desired ingredient only if the user doesn't already owns it in sufficient quantity
            $found = true;
            if ($ingredient['quantity'] > $userIngredient['quantity']) { // if the recipe require more than what the user owns
              // if ($ingredient['quantity'] - $userIngredient['quantity'])
              if(!$ingredient['min_buy']){
                $ingredient['min_buy']=1;
              }
              $ingredient['quantity'] = $ingredient['min_buy'] * ceil(($ingredient['quantity'] - $userIngredient['quantity']) / $ingredient['min_buy']);
              $ingredientsToBuy[] = $ingredient;
            }
          }
        }
        if (!$found) {
          $ingredientsToBuy[] = $ingredient;
        }
      }
      $result[] = [
        'id' => $recipe['id'],
        'name' => $recipe['name'],
        'quantity' => $recipe['quantity'],
        'ingredients' => $ingredientsToBuy,
      ];
    }
    return $result;
  }

  public static function reduceIngredients($ingredients, $result)
  {
    $item = $ingredients[0];
    $reduced = [];
    array_splice($ingredients, 0, 1);
    foreach ($item['ingredients'] as $ingredient) {  // ingredients 1er elem
      for ($i = 0; $i < count($ingredients); $i++) { //recette
        for ($j = 0; $j < count($ingredients[$i]['ingredients']); $j++) { //ingredients recette
          if ($ingredient['id'] == $ingredients[$i]['ingredients'][$j]['id']) {
            $ingredient['quantity'] += $ingredients[$i]['ingredients'][$j]['quantity'];
            array_splice($ingredients[$i]['ingredients'], $j, 1);
          }
        }
      }
      $reduced[] = $ingredient;
    }
    $result[] = [
      'id' => $item['id'],
      'name' => $item['name'],
      'quantity' => $item['quantity'],
      'ingredients' => $reduced,
    ];
    if (count($ingredients) == 0) {
      return $result;
    } else {
      return self::reduceIngredients($ingredients, $result);
    }
  }

  public static function getRightAmountOfIngredients($ingredients, $multiplier)
  {
    $arr = [];
    foreach ($ingredients as $ingredient) {
      $ingredientArray = get_object_vars($ingredient);
      if (!empty($ingredientArray['min_buy'])) {
        $ingredientArray['quantity'] = $ingredientArray['min_buy'] * ceil(($ingredientArray['quantity'] * $multiplier) / $ingredientArray['min_buy']);
      } else {
        $ingredientArray['quantity'] *= $multiplier;
      }
      $arr[] = $ingredientArray;
    }
    // echo json_encode($test);
    return $arr;
  }

  public static function getUserStock($userId)
  {
    $pdo = Database::getPDO();
    $sql = "SELECT ingredient_id,quantity
            FROM users_ingredients 
            WHERE user_id = :user_id
            AND needed=0";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':user_id', $userId);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function getUserShoppingList($userId)
  {
    $pdo = Database::getPDO();
    $sql = "SELECT ingredient_id,quantity
            FROM users_ingredients 
            WHERE user_id = :user_id
            AND needed=1";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':user_id', $userId);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * return all food category
   */
  public static function getCategories()
  {
    $pdo = Database::getPDO();
    $sql = "SELECT id, name from categories";
    $statement = $pdo->query($sql);
    $categories = $statement->fetchAll();
    return $categories;
  }

  /**
   * add various ingredients to user's stock or users's shopping list
   */
  public static function addToStock($ingredients, $userId, $needed)
  {
    $pdo = Database::getPDO();
    // $needed = $identifier == 'shop' ? 1 : 0;
    foreach ($ingredients as $ingredient) {
      // update the desired ingredient if it already exist in the user's stock
      if (self::existInStock($ingredient['id'], $userId, $needed)) {
        $sql = "SELECT quantity
          FROM users_ingredients
          WHERE user_id = :user_id
          AND ingredient_id=:ingredient_id
          AND needed = :needed";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':ingredient_id', $ingredient['id'], PDO::PARAM_INT);
        $statement->bindValue(':needed', $needed, PDO::PARAM_INT);
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $quantity = $result['quantity'] + $ingredient['quantity'];
        $response = self::updateStockIngredient($ingredient['id'], $userId, $quantity, $needed);
        if ($response) {
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
          :needed
          )
        ";
        try {
          $statement = $pdo->prepare($sql);
          $statement->bindValue(':quantity', $ingredient['quantity'], PDO::PARAM_INT);
          $statement->bindValue(':ingredient_id', $ingredient['id'], PDO::PARAM_INT);
          $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
          $statement->bindValue(':needed', $needed, PDO::PARAM_INT);
          $statement->execute();
        } catch (PDOException $e) {
          return $e->errorInfo;
        }
      }
    }
  }

  /**
   * add various ingredients to a recipe
   */
  public static function addRecipesIngredients($ingredients, $recipeId)
  {
    $pdo = Database::getPDO();
    foreach ($ingredients as $ingredient) {
      // return $ingredient['quantity'];
      // die;
      $sql = "INSERT INTO recipe_ingredients (
        ingredient_id,
        recipe_id,
        quantity
        ) VALUES (
          :ingredient_id,
          :recipe_id,
          :quantity
          )
        ";
      try {
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':ingredient_id', $ingredient['id']);
        $statement->bindValue(':recipe_id', $recipeId);
        $statement->bindValue(':quantity', $ingredient['quantity']);
        $statement->execute();
      } catch (PDOException $e) {
        return [
          'error' => $e->errorInfo,
        ];
      }
    }
  }

  /**
   * delete all ingredients linked to the desired recipe
   */
  public static function deleteRecipeIngredients($recipeId)
  {
    $pdo = Database::getPDO();
    $sql = "DELETE FROM recipe_ingredients WHERE recipe_id= :recipe_id";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':recipe_id', $recipeId);
    $statement->execute();
  }

  /**
   *check if an ingredients is already present in user's stock
   */
  public static function existInStock($id, $userId, $needed)
  {
    $pdo = Database::getPDO();
    $sql = " SELECT COUNT(*)
            FROM users_ingredients
            WHERE user_id = :user_id
            AND ingredient_id=:ingredient_id
            AND needed= :needed";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':ingredient_id', $id, PDO::PARAM_INT);
    $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $statement->bindValue(':needed', $needed, PDO::PARAM_INT);
    $statement->execute();
    $count = $statement->fetchColumn();
    if ($count > 0) {
      return true;
    }
    return false;
  }

  /**
   * update quantity of an ingredient in user's stock
   */
  public static function updateStockIngredient($id, $userId, $quantity, $needed)
  {
    // echo $id . "/";
    // echo $userId. "/";
    // echo $quantity. "/";
    // echo $needed. "/";
    if ($quantity > 0) {
      try {
        $pdo = Database::getPDO();
        $sql = "
          UPDATE users_ingredients
          SET quantity= :quantity
          WHERE ingredient_id= :id
          AND user_id= :userId
          AND needed = :needed
        ";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':quantity', $quantity, PDO::PARAM_INT);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':userId', $userId, PDO::PARAM_INT);
        $statement->bindValue(':needed', $needed, PDO::PARAM_INT);
        $statement->execute();
      } catch (PDOException $e) {
        return $e->errorInfo;
      }
      // return 'manh';
    } else {
      self::deleteStockIngredient($id, $userId, $needed);
      // return 'bler';
    }
  }

  /**
   * delete ingredient in user's stock
   */
  public static function deleteStockIngredient($id, $userId, $needed)
  {
    try {
      $pdo = Database::getPDO();
      $sql = "DELETE FROM users_ingredients
              WHERE ingredient_id= :id
              AND user_id= :userId
              AND needed = :needed
    ";
      $statement = $pdo->prepare($sql);
      $statement->bindValue(':id', $id, PDO::PARAM_INT);
      $statement->bindValue(':needed', $needed, PDO::PARAM_INT);
      $statement->bindValue(':userId', $userId, PDO::PARAM_INT);
      $statement->execute();
    } catch (PDOException $e) {
      return $e->errorInfo;
    }
  }

  /**
   * Remove recipe's ingredients that has been cook from user stock
   */
  public static function removeRecipeFromStock($userId, $recipeId)
  {
    $ingredients = Ingredient::findRecipeIngredients($recipeId);
    $userStock = Ingredient::findUserIngredients($userId);
    foreach ($ingredients as $ingredient) {
      foreach ($userStock as $cat) {
        foreach ($cat['ingredients'] as $stockIngredient) {
          if ($stockIngredient->getId() == $ingredient->getId()) {
            $quantity = get_object_vars($stockIngredient)['quantity'] - get_object_vars($ingredient)['quantity'];
            self::updateStockIngredient($stockIngredient->getId(), $userId, $quantity, "0");
          }
        }
      }
    }
  }


  /**
   *  retrieve all the ingredients linked to a particular recipe
   */
  public static function findRecipeIngredients($recipeId)
  {
    $pdo = Database::getPDO();
    $sql = "SELECT i.id, i.name, i.image, ri.quantity, u.unity, i.min_buy
          FROM ingredients i 
          LEFT JOIN recipe_ingredients ri 
          ON i.id=ri.ingredient_id 
          LEFT JOIN unity u
          ON u.id=i.unity_id
          WHERE ri.recipe_id = :id";
    $preparedQuery = $pdo->prepare($sql);
    $preparedQuery->bindValue(':id', $recipeId, PDO::PARAM_INT);
    $preparedQuery->execute();
    $ingredients = $preparedQuery->fetchAll(PDO::FETCH_CLASS, static::class);
    return $ingredients;
  }

  public function hasEnought($userStock)
  {
    $enought = false;
    $ingredient = get_object_vars($this);
    foreach ($userStock as $stockElem) {
      if ($stockElem['ingredient_id'] == $ingredient['id'] && $stockElem['quantity'] >= $ingredient['quantity']) {
        $enought = true;
      }
    }
    return $enought;
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
