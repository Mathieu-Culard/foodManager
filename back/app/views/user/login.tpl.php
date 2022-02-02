<div class="login-form">
    <form action="" method="POST" class="login-form__form">
        <h2 class="login-form__title">food manager Admin</h2>
        <div class="login-form__item">
            <label for="username">Pseudo</label>
            <input class="login-form__input" type="text" id="username" name="username" placeholder="username">
        </div>
        <div class="login-form__item">
            <label for="pass">Mot de passe</label>
            <input class="login-form__input" type="password" id="pass" name="pass" placeholder="Mot de passe">
        </div>
        <?php if (isset($error)) : ?>
            <p><?= $error ?></p>
        <?php endif ?>
        <button class="login-form__submit" type="submit">Se connecter</button>
    </form>

</div>