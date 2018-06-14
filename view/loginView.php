<?php ob_start(); ?>
        <form action="index.php" method="post">
            <label for="nickname">Utilisateur</label>
                <input class="formInput" type="text" name="nickname" autofocus required>
            <input class="formButton" type="submit" value="Suivant">
        </form>
<?php $content = ob_get_clean(); ?>

<?php require('./view/template.php'); ?>