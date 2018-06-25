<?php
	$pageName = "Gestion de la Classe";
	ob_start();
?>
        <button>Ajouter des élèves</button>
        <?=Classrooms::displayThisRoom($_GET['idcr']);?>
<?php $content = ob_get_clean(); ?>

<?php require('./view/ad_template.php'); ?>