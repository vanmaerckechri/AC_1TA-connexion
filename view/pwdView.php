<?php ob_start(); ?>
        <h2>Connexion</h2>
        <a class="userInfosLink" href="index.php"><?=$_SESSION["nickname"]?>
        <?php 
        	if (isset($_SESSION["classroom"]) && !empty($_SESSION["classroom"]))
        	{
        		?>
        		<span href="index.php"> | <?=$_SESSION["classroom"]?></span>
        		<?php
        	}
        ?>
        </a>
        <form action="index.php" method="post">
            <label for="password">Mot de passe</label>
            <input class="formInput" type="password" name="password" autofocus required>
            <div class="g-recaptcha" data-sitekey="<?=getClientCaptchaKey()?>"></div>
            <a class="recovery" href="index.php?action=passwordrecovery">Mot de passe oublié ?</a>
            <input class="formButton" type="submit" value="Connexion">
        </form>
<?php $content = ob_get_clean(); ?>

<?php require('./view/template.php'); ?>