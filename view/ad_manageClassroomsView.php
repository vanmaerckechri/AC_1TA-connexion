<?php
	$pageName = "Gestion des Classes";
	ob_start();
?>
    	<button>Ajouter</button>
    	<div class="list">
        	<?=Classrooms::displayRooms(); ?>
        </div>
<?php $content = ob_get_clean(); ?>

<?php require('./view/ad_template.php'); ?>