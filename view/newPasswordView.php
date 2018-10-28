<?php ob_start(); ?>
        <h2>Nouveau Mot de Passe</h2>
        <form action="index.php" method="post">

            <label for="newPassword">Mot de passe</label>
            <input class="formInput" type="password" name="newPassword" required>
            <p><?=$_SESSION['smsAlert']['password']?></p>

            <label for="newPassword2">Confirmer le mot de passe</label>
            <input class="formInput" type="password" name="newPassword2" required>
            <p><?=$_SESSION['smsAlert']['password2']?></p>

            <div class="g-recaptcha" data-sitekey="<?=getClientCaptchaKey()?>"></div>
            <a href="index.php">Se connecter</a>
            <input class="formButton" type="submit" value="Suivant">
        </form>
<?php $content = ob_get_clean(); ?>

<?php require('./view/template.php'); ?>