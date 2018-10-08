<?php
    $pageName = "Informations Personnelles";
    // -- PROFIL INFOS --
    $nickname = str_replace("admin@", "", $_SESSION["nickname"]);
    ob_start();
	?>
		<div class="profilInfos">
			<p class="accountName">Compte: <span class="adminAtColor">admin@</span><?=$nickname?></p>
			<p><a href="admin.php?action=changeMail" id="changeMail" class="formButton">Modifier votre Adresse Mail</a></p>
			<p><button id="changePassword" class="formButton">Modifier votre Mot de Passe</button></p>
			<p><button id="deleteAccount" class="formButton deleteSomething">Effacer ce Compte</button></p>
	    </div>
	<?php 
	$tools = "";
	$content = ob_get_clean();

    ob_start();
    $adAccountState = isset($adAccountState) ? $adAccountState : false;
	?>
		<script>
	        let adAccountState = <?=json_encode($adAccountState)?>;
	    </script>
        <script src="assets/js/admin_tools.js"></script>
    <?php $script = ob_get_clean();?>

<?php require('./view/ad_template.php'); ?>