<?php
	$pageName = "Gestion de l'Élève";
	ob_start();
?>
        <?=Classrooms::displaySelectedStudents($_GET['idst']); ?>
<?php $content = ob_get_clean(); ?>

<?php require('./view/ad_template.php'); ?>