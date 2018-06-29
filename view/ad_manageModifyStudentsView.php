<?php
	$student = Classrooms::displaySelectedStudents($_GET['idst']);
	$className = htmlspecialchars($className, ENT_QUOTES);
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
		$nickname = htmlspecialchars($student[0]["nickname"], ENT_QUOTES);
		$password = htmlspecialchars($student[0]["password"], ENT_QUOTES);
		?>
		<form class="list" action="admin.php" method="post">
        	<label for="nickname">Nom d'Utilisateur</label>
           	<input class="formInput" type="text" name="nickname" value="<?=$nickname?>" required>
           	<label for="password">Mot de passe</label>
            <input class="formInput" type="text" name="password" value="<?=$password?>" required>
	    </form>
		<p><?=$_SESSION['smsAlert']['default']?></p>
		<?php
	}
	$content = ob_get_clean();

    ob_start();
	?>
    <?php $script = ob_get_clean();?>

<?php require('./view/ad_template.php'); ?>