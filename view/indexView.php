<?php $title = 'Plateforme Educative'; ?>

<?php ob_start(); ?>
    <div class="connexion">
        <h1>Plateforme Educative</h1>
        <h2>Connexion</h2>
        <form action="index.php" method="post">
            <label for="nickname">Utilisateur</label>
                <input type="text" name="login" autofocus required>
            <input class="formDefault_submit" type="submit" value="Connexion">
        </form>
    </div>
<?php $content = ob_get_clean(); ?>

<?php require('./view/template.php'); ?>