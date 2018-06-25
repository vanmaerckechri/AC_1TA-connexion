<?php ob_start(); ?>
        <h2>Gestion des Élèves</h2>
        <button>Ajouter</button>
        <?php Classrooms::displayStudents($_GET['idcr']); ?>
<?php $content = ob_get_clean(); ?>

<?php require('./view/ad_template.php'); ?>