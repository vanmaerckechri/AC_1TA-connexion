<?php
class Update1TerreActionDb
{
	public static function loadDb()
	{
		try
		{
			$db = connectDB();
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $db;
		} 
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
	}

	public static function createPop($idSt)
	{
		$db = self::loadDb();
		$req = $db->prepare("INSERT INTO 1ta_populations (id_student) VALUES (:idSt)");
		$req->bindParam(':idSt', $idSt, PDO::PARAM_INT);
		$req->execute();

		$req = $db->prepare("INSERT INTO 1ta_stats (id_student, serie) VALUES (:idSt, :serie)");
		$req->bindParam(':idSt', $idSt, PDO::PARAM_INT);
		$req->bindValue(':serie', "average", PDO::PARAM_STR);
		$req->execute();
	}

	public static function deletePopulation($idCr, $idsSt)
	{
		$db = self::loadDb();

		if (is_array($idsSt) == false)
		{
			$idsSt = [$idsSt];
		}

		$delPop = $db->prepare("DELETE FROM 1ta_populations WHERE id_student = :idSt");
		$delReplies = $db->prepare("DELETE FROM 1ta_replies WHERE id_student = :idSt");
		$delStats = $db->prepare("DELETE FROM 1ta_stats WHERE id_student = :idSt");
		foreach ($idsSt as $idSt)
		{		
			$delPop->bindParam(':idSt', $idSt, PDO::PARAM_INT);
			$delPop->execute();

			$delReplies->bindParam(':idSt', $idSt, PDO::PARAM_INT);
			$delReplies->execute();

			$delStats->bindParam(':idSt', $idSt, PDO::PARAM_INT);
			$delStats->execute();
		}
		$delPop->closeCursor();
		$delPop = NULL;	
		$delReplies->closeCursor();
		$delReplies = NULL;		
		$delStats->closeCursor();
		$delStats = NULL;

		$del = $db->prepare("SELECT id FROM pe_library WHERE name = :name");
		$name = "1TerreAction";
		$del->bindValue(':name', $name, PDO::PARAM_INT);
		$del->execute();
		$idLib = $del->fetch(PDO::FETCH_COLUMN);

		$del = $db->prepare("DELETE FROM pe_rel_cr_library WHERE id_classroom = :idCr AND id_library = :idLib");
		$del->bindParam(':idCr', $idCr, PDO::PARAM_INT);
		$del->bindParam(':idLib', $idLib, PDO::PARAM_INT);   
		$del->execute();
		$del->closeCursor();
		$del = NULL;
	}

	public static function deletePlanet($idsCr, $idsSt)
	{
		$db = self::loadDb();

		if (is_array($idsCr) == false)
		{
			$idsCr = [$idsCr];
		}
		foreach ($idsCr as $idCr)
		{
			// Y a-t-il une planète liée à la classe ?
			$check = $db->prepare("SELECT id FROM 1ta_planets WHERE id_admin = :idAd AND id_classroom = :idCr");
			$check->bindParam(':idCr', $idCr, PDO::PARAM_INT);
			$check->bindParam(':idAd', $_SESSION['id'], PDO::PARAM_INT);
			$check->execute();
			$planetExist = $check->fetchAll(PDO::FETCH_COLUMN);
			$check->closeCursor();
			$check = NULL;
			// Si oui l'effacer avec tout ce qui lui appartient.
			if (!empty($planetExist))
			{
				$del = $db->prepare("DELETE FROM 1ta_planets WHERE id_classroom = :idCr AND id_admin = :idAd");
				$del->bindParam(':idCr', $idCr, PDO::PARAM_INT);
				$del->bindParam(':idAd', $_SESSION['id'], PDO::PARAM_INT);      
				$del->execute();

				self::deletePopulation($idCr, $idsSt);

				$del->closeCursor();
				$del = NULL;
			}
		}
	}
}