<?php
	$student = Classrooms::displaySelectedStudents($_GET['idst']);
    $pageName = "Modification de(s) Élève(s) de : <span class='classname'>".$className."</span>";

    ob_start();
	?>
		<div class="tools">
            <div>
                <a href="admin.php" class="formButton">Accueil</a>    
            </div>
            <div>
            </div>
        </div>
	<?php 
	$tools = ob_get_clean();

	ob_start();
	if (isset($student) && !empty($student))
	{
		?>
		<form class="list" action="admin.php" method="post">
        	<label for="nickname">Nom d'Utilisateur</label>
           	<input class="formInput" type="text" name="nickname" value="<?=$student[0]["nickname"]?>" required>
           	<label for="password">Mot de passe</label>
            <input class="formInput" type="text" name="password" value="<?=$student[0]['password']?>" required>
	    </form>
		<p><?=$_SESSION['smsAlert']['default']?></p>
		<?php
	}
	$content = ob_get_clean();

    ob_start();
	?>
    <?php $script = ob_get_clean();?>

<?php require('./view/ad_template.php'); ?>