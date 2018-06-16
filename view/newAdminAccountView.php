<?php ob_start(); ?>
        <h2>Créer un Compte Administrateur</h2>
        <form action="index.php" method="post">
            <label for="nickname">Nom d'utilisateur</label>
            <input class="formInput" type="text" name="nickname" autofocus required>
            <label for="email">Adresse e-mail</label>
            <input class="formInput" type="email" name="email" required>
            <label for="password">Mot de passe</label>
            <input class="formInput" type="password" name="password" required>
            <label for="password2">Confirmer le mot de passe</label>
            <input class="formInput" type="password" name="password2" required>
            <input class="formButton" type="submit" value="Valider">
        </form>
<?php $content = ob_get_clean(); ?>

<?php require('./view/template.php'); ?>