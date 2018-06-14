<?php ob_start(); ?>
        <form action="index.php" method="post">
            <label for="password">Mot de Passe</label>
               	<input type="password" name="password" autofocus required>
            <input type="submit" value="Connexion">
        </form>
<?php $content = ob_get_clean(); ?>

<?php require('./view/template.php'); ?>