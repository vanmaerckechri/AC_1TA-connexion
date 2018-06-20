<?php ob_start(); ?>
        <h2>Récupération de votre Nom d'Utilisateur</h2>
        <p>Si vous disposez d'un compte étudiant, veuillez faire la demande de récupération auprès de votre administrateur.</p>
        <form action="index.php?action=namerecovery" method="post">
            <label for="nameRecovery">Adresse e-mail</label>
            <input class="formInput" type="email" name="nameRecovery" autofocus required>
            <div class="g-recaptcha" data-sitekey="6LcR3F8UAAAAAGR1kYe6ysZSf8nTpYilhJgKFcZz"></div>
            <a href="index.php">Se connecter</a>
            <input class="formButton" type="submit" value="Suivant">
        </form>
<?php $content = ob_get_clean(); ?>

<?php require('./view/template.php'); ?>