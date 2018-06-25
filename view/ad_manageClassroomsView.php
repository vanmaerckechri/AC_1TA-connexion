<?php ob_start(); ?>
        <h2>Gestion des Classes</h2>
        <button>Ajouter</button>
        <?php Classrooms::displayRooms(); ?>
<?php $content = ob_get_clean(); ?>

<?php require('./view/ad_template.php'); ?>