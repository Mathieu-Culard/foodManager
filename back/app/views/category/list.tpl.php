<main class="page">
  <div>
    <h2 class="page__title">Liste des catégories</h2>
    <table class="page__table">
      <thead class="table-head">
        <tr>
          <th class="table-head__item" scope="col">#</th>
          <th class="table-head__item" scope="col">Nom</th>
          <th class="table-head__item" scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($categoriesList as $category) : ?>
          <tr>
            <th scope="row"><?= $category->getId() ?></th>
            <td><?= $category->getName() ?></td>
            <td>
              <a href="<?= $router->generate('admin-categories-list', ['id' => $category->getId()]) ?>">edit</a>
              <a href="<?= $router->generate('admin-categories-delete', ['id' => $category->getId()]) ?>?token=<?= $token ?>">delete</a>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>


  <div  class="page__add">
    <h2><?= empty($currentCategory) ? "Ajouter" : "Modifier" ?> une catégorie</h2>
    <form action="" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="token" value="<?= $token ?>">
      <div class="add-form__item" >
        <label for="name">Nom</label>
        <input  class="add-form__input" type="text" name="name" id="name" placeholder="nom de la categorie" value="<?= empty($currentCategory) ? "" : $currentCategory->getName() ?>">
      </div>
      <button class="add-form__submit" type="submit"><?= empty($currentCategory) ? "Valider" : "Modifier" ?></button>
    </form>
  </div>
</main>