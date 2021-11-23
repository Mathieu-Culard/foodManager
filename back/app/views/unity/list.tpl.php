<main class="page">
  <div>
    <h2>Liste des unités</h2>
    <table>
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Nom</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($unitsList as $unity) : ?>
          <tr>
            <th scope="row"><?= $unity->getId() ?></th>
            <td><?= $unity->getUnity() ?></td>
            <td>
              <a href="<?= $router->generate('admin-units-list', ['id' => $unity->getId()]) ?>">edit</a>
              <a href="<?= $router->generate('admin-units-delete', ['id' => $unity->getId()]) ?>?token=<?= $token ?>">delete</a>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>

  <div class="page__add">
    <h2><?= empty($currentUnity) ? "Ajouter" : "Modifier" ?> une unité</h2>
    <form action="" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="token" value="<?= $token ?>">
      <div class="add-form__item">
        <label for="name">Nom</label>
        <input  class="add-form__input" type="text" name="name" id="name" placeholder="nom de l'unité" value="<?= empty($currentUnity) ? "" : $currentUnity->getUnity() ?>">
      </div>
      <button class="add-form__submit" type="submit"><?= empty($currentUnity) ? "Valider" : "Modifier" ?></button>
    </form>
  </div>
</main>