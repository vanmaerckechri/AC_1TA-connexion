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
		$req = $db->prepare("SELECT id, name FROM pe_classrooms WHERE id_admin = :idadmin ORDER BY id DESC");
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
				$_SESSION['idcr'] = $id_cr;
				// Afficher tous les élèves de la classe
				$req = $db->prepare("SELECT id, nickname, password FROM pe_students WHERE id_classroom = :idClassroom ORDER BY id DESC");
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
			$req = $db->prepare("SELECT id_classroom, id_admin, nickname, password FROM pe_students WHERE id = :idStudent");
			$req->bindValue(':idStudent', $id_st, PDO::PARAM_INT);
			$req->execute();
			$resultReq = $req->fetchAll();
			$req->closeCursor();
			$req = NULL;
			// Verifier si l'admin execute bien une requete sur l'un de ses élèves
			if ($resultReq[0]['id_admin'] == $_SESSION['id'])
			{
				return $resultReq;
			}
		}
	}

	public static function createClassroom($mySessionId, $newName)
	{
		// Vérifier que le nom de la classe n'est pas encore pris!
		$db = (new self)->connect();
		$req = $db->prepare("SELECT name FROM pe_classrooms WHERE id_admin = :idad AND name = :name");	
		$req->bindValue(':idad', $mySessionId, PDO::PARAM_INT);
		$req->bindValue(':name', $newName, PDO::PARAM_STR);
		$req->execute();
		$resultReq = $req->fetchAll();
		if (empty($resultReq))
		{
			$req = $db->prepare("INSERT INTO pe_classrooms (name, id_admin) VALUES (:name, :idad)");
			$req->bindValue(':name', $newName, PDO::PARAM_STR);
			$req->bindValue(':idad', $mySessionId, PDO::PARAM_INT);
			$req->execute();
			$_SESSION['smsAlert']['default'] = "<span class='smsInfo'>Classe créée avec succes!</span>";
		}
		else
		{
			$_SESSION['smsAlert']['default'] = "<span class='smsAlert'>Vous possédez déjà une classe portant ce nom!</span>";
		}
		$req->closeCursor();
		$req = NULL;
	}

	public static function renameClassroom($mySessionId, $newName, $idcr)
	{
		if (filter_var($idcr, FILTER_VALIDATE_INT) && $idcr < 100000)
		{
			// Vérifier que le nom de la classe n'est pas encore pris!
			$db = (new self)->connect();
			$req = $db->prepare("SELECT name FROM pe_classrooms WHERE id_admin = :idad AND name = :name");	
			$req->bindValue(':idad', $mySessionId, PDO::PARAM_INT);
			$req->bindValue(':name', $newName, PDO::PARAM_STR);
			$req->execute();
			$resultReq = $req->fetchAll();
			if (empty($resultReq))
			{
				$req = $db->prepare("UPDATE pe_classrooms SET name = :newName WHERE id = :idcr");
				$req->bindValue(':newName', $newName, PDO::PARAM_STR);
				$req->bindValue(':idcr', $idcr, PDO::PARAM_INT);
				$req->execute();
				$_SESSION['smsAlert']['default'] = "<span class='smsInfo'>Classe renommée avec succès!</span>";
			}
			else
			{
				$_SESSION['smsAlert']['default'] = "<span class='smsAlert'>Vous possédez déjà une classe portant ce nom!</span>";
			}
			$req->closeCursor();
			$req = NULL;
		}
	}

	public static function deleteClassrooms($mySessionId, $classroomsId)
	{
		// Mon id de session est-elle valide ?
		if (filter_var($mySessionId, FILTER_VALIDATE_INT) && $mySessionId >=0 && $mySessionId < 100000)
		{
			$sms = "";
			// Effacer les classes sélectionnées si possible
			$db = (new self)->connect();
			$req = $db->prepare("SELECT id, name FROM pe_classrooms WHERE id = :idclass AND id_admin = :idAd");
			foreach ($classroomsId as $value)
			{
				// Vérifier qu'il s'agit d'un ID valide
				if (filter_var($value, FILTER_VALIDATE_INT) && $value >= 0 && $value < 100000)
				{
					// Vérifier que la classe appartient à l'admin
					$req->bindValue(':idclass', $value, PDO::PARAM_INT);
					$req->bindValue(':idAd', $mySessionId, PDO::PARAM_INT);
					$req->execute();
					$resultReq = $req->fetchAll();
					// Si la classe appartient bien à l'admin => DELETE
					$del = $db->prepare("DELETE FROM pe_classrooms WHERE id = :idclass");
					if (isset($resultReq) && !empty($resultReq))
					{
						// Effacer les classes
						$del->bindParam(':idclass', $value, PDO::PARAM_INT);   
						$del->execute();
						// Effacer le élèves
						$del = $db->prepare("DELETE FROM pe_students WHERE id_classroom = :idclass");
						$del->bindParam(':idclass', $value, PDO::PARAM_INT);   
						$del->execute();
						$sms = $sms == "" ? "Le(s) compte(s) suivant(s) a/ont été supprimé(s) <span class='smsAlert'>".$resultReq[0]['name']."</span>" : $sms.", <span class='smsAlert'>".$resultReq[0]['name']."</span>";
					}
					$del->closeCursor();
					$del = NULL;
				}
			}
			$req->closeCursor();
			$req = NULL;
			$_SESSION['smsAlert']['default'] = $sms;
		}
		header('Location: admin.php?action=manageThisClassroom');	  
		exit;  	
	}

	public static function createStudent($mySessionId, $nickname, $pwd, $idcr)
	{

		// Mon id de session et l'id de la classe sont-elles valides ?
		if (filter_var($mySessionId, FILTER_VALIDATE_INT) && $mySessionId >=0 && $mySessionId < 100000 && filter_var($idcr, FILTER_VALIDATE_INT) && $idcr >=0 && $idcr < 100000)
		{
			$sms = "";
			// Ajouter un nouvel étudiant si possible
			$db = (new self)->connect();
			$req = $db->prepare("SELECT id FROM pe_classrooms WHERE id = :idcr AND id_admin = :idAd");
			$req->bindValue(':idcr', $idcr, PDO::PARAM_INT);
			$req->bindValue(':idAd', $mySessionId, PDO::PARAM_INT);
			$req->execute();
			$resultReq = $req->fetchAll();
			// Si la classe appartient à l'admin faisant la requete
			if (isset($resultReq) && !empty($resultReq))
			{
				$req = $db->prepare("SELECT id FROM pe_students WHERE nickname = :nick AND id_classroom = :idcr");
				$req->bindValue(':idcr', $idcr, PDO::PARAM_INT);
				$req->bindValue(':nick', $nickname, PDO::PARAM_STR);
				$req->execute();
				$resultReq = $req->fetchAll();
				// Si le nom d'utilisateur n'est pas encore occupé dans cette classe
				if (!isset($resultReq) || empty($resultReq))
				{
					$req = $db->prepare("INSERT INTO pe_students (id_admin, id_classroom, nickname, password) VALUES (:idad, :idcr, :nick, :pwd)");
					$req->bindValue(':idad', $mySessionId, PDO::PARAM_INT);
					$req->bindValue(':idcr', $idcr, PDO::PARAM_INT);
					$req->bindValue(':nick', $nickname, PDO::PARAM_STR);
					$req->bindValue(':pwd', $pwd, PDO::PARAM_STR);
					$req->execute();
					$_SESSION['smsAlert']['default'] = "<span class='smsInfo'>Élève créé avec succès!</span>";
				}
				else
				{
					$_SESSION['smsAlert']['default'] = "<span class='smsAlert'>Ce nom d'utilisateur est déjà pris dans cette classe!</span>";
				}
			}
			$req->closeCursor();
			$req = NULL;
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
					$del = $db->prepare("DELETE FROM pe_students WHERE id = :idStudent");
					if (isset($resultReq) && !empty($resultReq))
					{
						$del->bindParam(':idStudent', $value, PDO::PARAM_INT);   
						$del->execute();
						$sms = $sms == "" ? "Le(s) compte(s) suivant(s) a/ont été supprimé(s) <span class='smsAlert'>".$resultReq[0]['nickname']."</span>" : $sms.", <span class='smsAlert'>".$resultReq[0]['nickname']."</span>";
					}
					$del->closeCursor();
					$del = NULL;
				}
			}
			$req->closeCursor();
			$req = NULL;
			$_SESSION['smsAlert']['default'] = $sms;
		}
		header('Location: admin.php?action=manageThisClassroom&idcr='.$idcr);	  
		exit;  	
	}
}