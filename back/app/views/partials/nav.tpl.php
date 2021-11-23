<nav class="nav">
  <h1 class="nav__title">food manager Admin</h1>
  <ul class="nav__list">
    <li class="nav__item">
      <a class="nav__link" href="<?= $router->generate('admin-user-list') ?>">Utilisateurs</a>
    </li>
    <li class="nav__item">
      <a class="nav__link" href="<?= $router->generate('admin-ingredients-list') ?>">Ingredients</a>
    </li>
    <li class="nav__item">
      <a class="nav__link" href="<?= $router->generate('admin-categories-list') ?>">Categories</a>
    </li>
    <li class="nav__item">
      <a class="nav__link" href="<?= $router->generate('admin-units-list') ?>">Unités</a>
    </li>
    <li class="nav__item">
      <a class="nav__link" href="<?= $router->generate('admin-user-logout') ?>">Se deconnecter</a>
    </li>
  </ul>
</nav>