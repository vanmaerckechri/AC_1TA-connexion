<?php ob_start(); ?>
        <h2>Connexion</h2>
        <form action="index.php" method="post">
            <label for="nickname">Nom d'utilisateur</label>
            <input class="formInput" type="text" name="nickname" autofocus required>
        	<a class="recovery" href="index.php?action=namerecovery">Nom d'utilisateur oublié ?</a>
        	<a href="index.php?action=newadminaccount">Créer un compte</a>
            <input class="formButton" type="submit" value="Suivant">
        </form>
<?php $content = ob_get_clean(); ?>

<?php require('./view/template.php'); ?>