<?php ob_start(); ?>
        <form action="index.php" method="post">
            <label for="classroom">Classe</label>
                <input type="text" name="classroom" autofocus required>
            <input type="submit" value="Connexion">
        </form>
<?php $content = ob_get_clean(); ?>

<?php require('./view/template.php'); ?>