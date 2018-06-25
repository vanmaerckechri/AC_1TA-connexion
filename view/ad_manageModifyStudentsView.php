<?php ob_start(); ?>
        <h2>Gestion de l'Élève</h2>
        <?=Classrooms::displaySelectedStudents($_GET['idst']); ?>
<?php $content = ob_get_clean(); ?>

<?php require('./view/ad_template.php'); ?>