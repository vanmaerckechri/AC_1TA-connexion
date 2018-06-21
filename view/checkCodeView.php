<?php ob_start(); ?>
		<h2>Vérification de la Validité du Lien</h2>
        <form action="index.php?action=<?=$type?>&code=<?=$code?>" method="post">
            <div class="g-recaptcha" data-sitekey="6LcR3F8UAAAAAGR1kYe6ysZSf8nTpYilhJgKFcZz"></div>
            <a href="index.php">Se connecter</a>
            <input class="formButton" type="submit" value="Valider">
        </form>
<?php $content = ob_get_clean(); ?>

<?php require('./view/template.php'); ?>