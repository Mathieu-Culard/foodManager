<main class="page">
  <div>
    <h2>Liste des ingredients</h2>
    <table>
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Image</th>
          <th scope="col">Nom</th>
          <th scope="col">Categorie</th>
          <th scope="col">Minimum achetable</th>
          <th scope="col">Unité</th>
          <th scope="col">Suivi</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($ingredientsList as $ingredient) : ?>
          <tr>
            <th scope="row"><?= $ingredient->getId() ?></th>
            <td><img class="table__img" src=<?= "/../assets/ingredients/" . $ingredient->getImage() ?> alt=<?= $ingredient->getName() ?> /></td>
            <td><?= $ingredient->getName() ?></td>
            <td><?= $ingredient->findCategory()->getName() ?></td>
            <td><?= $ingredient->getMinBuy() ?></td>
            <td><?= $ingredient->findUnity()->getUnity() ?></td>
            <td><?= $ingredient->getIsTracked() == 1 ? 'oui' : 'non' ?></td>
            <td>
              <a class="edit" href="<?= $router->generate('admin-ingredients-list', ['id' => $ingredient->getId()]) ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
              <a class="remove" href="<?= $router->generate('admin-ingredients-delete', ['id' => $ingredient->getId()]) ?>?token=<?= $token ?>">&times;</a>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
  <div class="page__add">
    <h2><?= empty($currentIngredient) ? "Ajouter" : "Modifier" ?> un ingrédient</h2>
    <form action="" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="token" value="<?= $token ?>">
      <div class="add-form__item">
        <label for="name">Nom</label>
        <input class="add-form__input" type="text" name="name" id="name" placeholder="nom de l'ingrédient" value="<?= empty($currentIngredient) ? "" : $currentIngredient->getName() ?>">
      </div>
      <div class="add-form__item">
        <label for="picture">Image</label>
        <input type="file" name="picture" id="picture">
      </div>
      <div class="add-form__item">
        <label for="min-buy">Minimum achetable</label>
        <input class="add-form__input" type="number" name="min-buy" id="min-buy" placeholder="quantité minimum achetable" value="<?= empty($currentIngredient) ? "" : $currentIngredient->getMinBuy() ?>">
      </div>
      <div class="add-form__item">
        <label for="category">Categorie</label>
        <select name="category" id="category" class="add-form__input">
          <?php foreach ($categories as $category) : ?>
            <?php if (!empty($currentIngredient) && $currentIngredient->getCategoryId() == $category->getId()) : ?>
              <option selected value="<?= $category->getId() ?>"><?= $category->getName() ?></option>
            <?php else : ?>
              <option value="<?= $category->getId() ?>"><?= $category->getName() ?></option>
            <?php endif ?>
          <?php endforeach ?>
        </select>
      </div>
      <div class="add-form__item">
        <label for="unity">Unité</label>
        <select name="unity" id="unity" class="add-form__input">
          <option value="-1">Sans unité</option>
          <?php foreach ($units as $unity) : ?>
            <?php if (!empty($currentIngredient) && $currentIngredient->getUnityId() == $unity->getId()) : ?>
              <option selected value="<?= $unity->getId() ?>"><?= $unity->getUnity() ?></option>
            <?php else : ?>
              <option value="<?= $unity->getId() ?>"><?= $unity->getUnity() ?></option>
            <?php endif ?>
          <?php endforeach ?>
        </select>
      </div>
      <div class="add-form__item">
        <label for="tracked">Suivi</label>
        <div class="track-check">
          <input name="tracked" type="checkbox" <?= (!empty($currentIngredient) && $currentIngredient->getIsTracked()) || empty($currentIngredient) ? '' : 'checked' ?>>
          <p>sortir l'ingredient du suivi</p>
        </div>
      </div>
      <button type="submit" class="add-form__submit"><?= empty($currentIngredient) ? "Valider" : "Modifier" ?></button>
    </form>
  </div>
</main>