<?php ob_start(); ?>
        <h2>Créer un Compte Administrateur</h2>
        <form action="index.php?action=newadminaccount" method="post">
            <label for="createAdminAccountnickname">Nom d'utilisateur</label>
            <input class="formInput" type="text" name="createAdminAccountNickname" value=<?=$keepNickname?> autofocus required>
            <p><?=$_SESSION['smsAlert']['nickname']?></p>

            <label for="createAdminAccountEmail">Adresse e-mail</label>
            <input class="formInput" type="email" name="createAdminAccountEmail" value=<?=$keepEmail?> required>
            <p><?=$_SESSION['smsAlert']['email']?></p>

            <label for="createAdminAccountPassword">Mot de passe</label>
            <input class="formInput" type="password" name="createAdminAccountPassword" required>
            <p><?=$_SESSION['smsAlert']['password']?></p>

            <label for="createAdminAccountPassword2">Confirmer le mot de passe</label>
            <input class="formInput" type="password" name="createAdminAccountPassword2" required>
            <p><?=$_SESSION['smsAlert']['password2']?></p>

            <div class="g-recaptcha" data-sitekey="<?=getClientCaptchaKey()?>"></div>
            
            <a href="index.php">Se connecter</a>
            <input class="formButton" type="submit" value="Valider">
        </form>
<?php $content = ob_get_clean(); ?>

<?php require('./view/template.php'); ?>