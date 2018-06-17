<?php ob_start(); ?>
        <h2>Connexion</h2>
        <form action="index.php" method="post">
            <label for="classroom">Classe</label>
            <input class="formInput" type="text" name="classroom" autofocus required>
            <input class="formButton" type="submit" value="Suivant">
        </form>
<?php $content = ob_get_clean(); ?>

<?php require('./view/template.php'); ?>