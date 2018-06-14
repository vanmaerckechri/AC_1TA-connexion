<?php ob_start(); ?>
        <form action="index.php" method="post">
            <label for="nickname">Utilisateur</label>
                <input type="text" name="nickname" autofocus required>
            <input type="submit" value="Connexion">
        </form>
<?php $content = ob_get_clean(); ?>

<?php require('./view/template.php'); ?>