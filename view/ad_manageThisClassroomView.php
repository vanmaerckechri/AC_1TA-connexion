<?php ob_start(); ?>
        <h2>Gestion de la Classe</h2>
        <button>Ajouter des élèves</button>
        <?=Classrooms::displayThisRoom($_GET['idcr']);?>
<?php $content = ob_get_clean(); ?>

<?php require('./view/ad_template.php'); ?>