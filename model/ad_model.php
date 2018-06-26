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
		$db = (new self)->connect();
		$req = $db->prepare("SELECT id, name FROM pe_classrooms WHERE id_admin = :idadmin");
		$req->bindValue(':idadmin', $_SESSION['id'], PDO::PARAM_INT);
		$req->execute();
		$resultReq = $req->fetchAll();
		$req->closeCursor();
		$req = NULL;

		return $resultReq;
	}
	public static function displayThisRoom($id_cr)
	{
		if (filter_var($id_cr, FILTER_VALIDATE_INT) && $id_cr < 100000)
		{
			$db = (new self)->connect();
			$req = $db->prepare("SELECT id_admin, name FROM pe_classrooms WHERE id = :idClassroom");
			$req->bindValue(':idClassroom', $id_cr, PDO::PARAM_INT);
			$req->execute();
			$resultReq = $req->fetchAll();
			$name = $resultReq[0]['name'];
			// Verifier si l'admin execute bien une requete sur l'une de ses classes
			if (isset($resultReq[0]['id_admin']) && $resultReq[0]['id_admin'] == $_SESSION['id'])
			{
				// Afficher tous les élèves de la classe
				$req = $db->prepare("SELECT id, nickname FROM pe_students WHERE id_classroom = :idClassroom");
				$req->bindValue(':idClassroom', $id_cr, PDO::PARAM_INT);
				$req->execute();
				$resultReq = $req->fetchAll();

				$return = [$resultReq, $name];
				return $return;
			}
			else
			{
				header('Location: admin.php');	  
			}
			$req->closeCursor();
			$req = NULL;
		}
		else
		{
			header('Location: admin.php');	  
		}
	}
	public static function displaySelectedStudents($id_st)
	{
		if (filter_var($id_st, FILTER_VALIDATE_INT) && $id_st < 100000)
		{
			$db = (new self)->connect();
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
	        	<form class="list" action="admin.php" method="post">
	        		<div>
			        	<label for="nickname">Nom d'Utilisateur</label>
			           	<input class="formInput" type="text" name="nickname" value="<?=$resultReq[0]["nickname"]?>" required>
			           	<label for="password">Mot de passe</label>
			            <input class="formInput" type="text" name="password" value="<?=$resultReq[0]['password']?>" required>
			        </div>
		        </form>
				<?php $modifyStudendsForm = ob_get_clean();
				return $modifyStudendsForm;
			}
		}
	}
	public static function deleteStudents($mySessionId, $studentsId, $idcr)
	{
		// Mon id de session et l'id de la classe sont-elles valides ?
		if (filter_var($mySessionId, FILTER_VALIDATE_INT) && $mySessionId >=0 && $mySessionId < 100000 && filter_var($idcr, FILTER_VALIDATE_INT) && $idcr >=0 && $idcr < 100000)
		{
			$sms = "";
			// Effacer les étudiants sélectionnés si possible
			$db = (new self)->connect();
			$req = $db->prepare("SELECT nickname, id_classroom FROM pe_students WHERE id = :idStudent AND id_admin = :idAd");
			foreach ($studentsId as $value)
			{
				// Si l'id de l'étudiant est valides => Vérification élève appartient à l'admin faisant le requete
				if (filter_var($value, FILTER_VALIDATE_INT) && $value >= 0 && $value < 100000)
				{
					$req->bindValue(':idStudent', $value, PDO::PARAM_INT);
					$req->bindValue(':idAd', $mySessionId, PDO::PARAM_INT);
					$req->execute();
					$resultReq = $req->fetchAll();
					// Si l'élèves appartient bien à l'admin => DELETE
					if (isset($resultReq) && !empty($resultReq))
					{
						$del = $db->prepare("DELETE FROM pe_students WHERE id = :idStudent");
						$del->bindParam(':idStudent', $value, PDO::PARAM_INT);   
						$del->execute();
						$sms = $sms == "" ? "Le(s) compte(s) suivant(s) a/ont été supprimé(s) <span class='smsAlert'>".$resultReq[0]['nickname']."</span>" : $sms.", <span class='smsAlert'>".$resultReq[0]['nickname']."</span>";
					}
				}
			}
			$_SESSION['smsAlert']['default'] = $sms;
		}
		header('Location: admin.php?action=manageThisClassroom&idcr='.$idcr);	  
		exit;  	
	}

	public static function deleteClassrooms($mySessionId, $classroomsId)
	{
		// Mon id de session est-elle valide ?
		if (filter_var($mySessionId, FILTER_VALIDATE_INT) && $mySessionId >=0 && $mySessionId < 100000)
		{
			$sms = "";
			foreach ($classroomsId as $value)
			{
				$db = (new self)->connect();
				// Vérifier que la classe appartient à l'admin
				$req = $db->prepare("SELECT id, name FROM pe_classrooms WHERE id = :idclass AND id_admin = :idAd");
				$req->bindValue(':idclass', $value, PDO::PARAM_INT);
				$req->bindValue(':idAd', $mySessionId, PDO::PARAM_INT);
				$req->execute();
				$resultReq = $req->fetchAll();
				// Si la classe appartient à l'admin faisant le requete
				if (isset($resultReq) && !empty($resultReq))
				{
					// Effacer les classes
					$del = $db->prepare("DELETE FROM pe_classrooms WHERE id = :idclass");
					$del->bindParam(':idclass', $value, PDO::PARAM_INT);   
					$del->execute();
					// Effacer le élèves
					$del = $db->prepare("DELETE FROM pe_students WHERE id_classroom = :idclass");
					$del->bindParam(':idclass', $value, PDO::PARAM_INT);   
					$del->execute();
					$sms = $sms == "" ? "Le(s) compte(s) suivant(s) a/ont été supprimé(s) <span class='smsAlert'>".$resultReq[0]['name']."</span>" : $sms.", <span class='smsAlert'>".$resultReq[0]['name']."</span>";
				}
			}
			$_SESSION['smsAlert']['default'] = $sms;
		}
		header('Location: admin.php?action=manageThisClassroom');	  
		exit;  	
	}
}