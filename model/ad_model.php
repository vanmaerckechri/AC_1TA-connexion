<?php
class Classrooms
{
	private function connect()
	{
		try
		{
		    $db = connectDB();
		    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} 
		catch (Exception $e)
		{
		    die('Erreur : ' . $e->getMessage());
		}
		return $db;
	}
	public static function displayRooms()
	{
		$db = self::connect();
		$req = $db->prepare("SELECT id, name FROM pe_classrooms WHERE id_admin = :idadmin");
		$req->bindValue(':idadmin', $_SESSION['id'], PDO::PARAM_INT);
		$req->execute();
		$resultReq = $req->fetchAll();
		$req->closeCursor();
		$req = NULL;
		foreach ($resultReq  as $row)
		{
			echo "<a href='admin.php?action=manageStudents&idcr=".$row['id']."'>".$row['name']."</a>";
		}
	}
	public static function displayStudents($id_cr)
	{
		if (filter_var($id_cr, FILTER_VALIDATE_INT) && $id_cr < 100000)
		{
			$db = self::connect();
			$req = $db->prepare("SELECT id_admin, name FROM pe_classrooms WHERE id = :idClassroom");
			$req->bindValue(':idClassroom', $id_cr, PDO::PARAM_INT);
			$req->execute();
			$resultReq = $req->fetchAll();
			// Verifier si l'admin execute bien une requete sur l'une de ses classes
			if ($resultReq[0]['id_admin'] == $_SESSION['id'])
			{
				// Afficher tous les élèves de la classe
				$req = $db->prepare("SELECT id, nickname FROM pe_students WHERE id_classroom = :idClassroom");
				$req->bindValue(':idClassroom', $id_cr, PDO::PARAM_INT);
				$req->execute();
				$resultReq = $req->fetchAll();
				foreach ($resultReq  as $row)
				{
					echo "<a href='admin.php?action=manageModifyStudents&idst=".$row['id']."'>".$row['nickname']."</a></br>";
				}
			}
			$req->closeCursor();
			$req = NULL;
		}
	}
	public static function displaySelectedStudents($id_st)
	{
		if (filter_var($id_st, FILTER_VALIDATE_INT) && $id_st < 100000)
		{
			$db = self::connect();
			$req = $db->prepare("SELECT id_admin, nickname, password FROM pe_students WHERE id = :idStudent");
			$req->bindValue(':idStudent', $id_st, PDO::PARAM_INT);
			$req->execute();
			$resultReq = $req->fetchAll();
			$req->closeCursor();
			$req = NULL;
			// Verifier si l'admin execute bien une requete sur l'un de ses élèves
			if ($resultReq[0]['id_admin'] == $_SESSION['id'])
			{
				ob_start();?>
	        	<form action="admin.php" method="post">
	        	<label for="nickname">Nom d'Utilisateur</label>
	           	<input class="formInput" type="text" name="nickname" value="<?=$resultReq[0]["nickname"]?>" required>
	           	<label for="password">Mot de passe</label>
	            <input class="formInput" type="text" name="password" value="<?=$resultReq[0]['password']?>" required>
				<?php $studentForm = ob_get_clean();
				return $studentForm;
			}
		}
	}
}