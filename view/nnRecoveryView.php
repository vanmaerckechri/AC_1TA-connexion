<?php ob_start(); ?>
        <h2>Récupération de votre Nom d'Utilisateur</h2>
        <p>Si vous disposez d'un compte étudiant, veuillez faire la demande de récupération auprès de votre administrateur.</p>
        <form action="index.php" method="post">
            <label for="email">Adresse e-mail</label>
            <input class="formInput" type="email" name="email" autofocus required>
            <input class="formButton" type="submit" value="Suivant">
        </form>
<?php $content = ob_get_clean(); ?>

<?php require('./view/template.php'); ?>