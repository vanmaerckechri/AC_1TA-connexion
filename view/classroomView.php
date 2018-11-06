<?php ob_start(); ?>
        <h2>Connexion</h2>
        <a class="userInfosLink" href="index.php"><?=htmlspecialchars($_SESSION["nickname"], ENT_NOQUOTES)?></a>
        <form action="index.php" method="post">
            <label for="classroom">Classe</label>
            <input class="formInput" type="text" name="classroom" autofocus required value=<?=htmlspecialchars($_SESSION["classroomSave"], ENT_NOQUOTES)?>>
            <input class="formButton" type="submit" value="Suivant">
        </form>
<?php $content = ob_get_clean(); ?>

<?php require('./view/template.php'); ?>