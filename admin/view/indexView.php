<?php ob_start(); ?>
        <h1>Page d'Administration</h1>
<?php $content = ob_get_clean(); ?>

<?php require('./view/template.php'); ?>