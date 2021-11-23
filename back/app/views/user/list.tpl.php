<main class="page">
  <div>
    <h2>Liste des utilisateurs</h2>
    <table>
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Avatar</th>
          <th scope="col">Pseudo</th>
          <th scope="col">Email</th>
          <th scope="col">Role</th>
          <!-- <th scope="col"></th> -->
        </tr>
      </thead>
      <tbody>
        <?php foreach ($usersList as $user) : ?>
          <tr>
            <th scope="row"><?= $user->getId() ?></th>
            <td><img class="table__img" src=<?= "/../assets/avatars/" . $user->getAvatar() ?> alt=<?= $user->getUsername() ?> /></td>
            <td><?= $user->getUsername() ?></td>
            <td><?= $user->getEmail() ?></td>
            <td><a href="<?= $router->generate('admin-user-update', ['id' => $user->getId()]) ?>?token=<?= $token ?>"><?= $user->getRole() ?></a></td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <div>
</main>