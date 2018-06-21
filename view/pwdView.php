<?php ob_start(); ?>
        <h2>Connexion</h2>
        <form action="index.php" method="post">
            <label for="password">Mot de passe</label>
            <input class="formInput" type="password" name="password" autofocus required>
            <div class="g-recaptcha" data-sitekey="6LcR3F8UAAAAAGR1kYe6ysZSf8nTpYilhJgKFcZz"></div>
            <a class="recovery" href="index.php?action=passwordrecovery">Mot de passe oublié ?</a>
            <input class="formButton" type="submit" value="Connexion">
        </form>
<?php $content = ob_get_clean(); ?>

<?php require('./view/template.php'); ?>