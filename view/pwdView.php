<?php ob_start(); ?>
        <form action="index.php" method="post">
            <label for="password">Mot de Passe</label>
               	<input class="formInput" type="password" name="password" autofocus required>
            <input class="formButton" type="submit" value="Connexion">
        </form>
<?php $content = ob_get_clean(); ?>

<?php require('./view/template.php'); ?>