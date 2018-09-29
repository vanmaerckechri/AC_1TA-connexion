<?php
class ManagePlanets
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

	public static function callPlanetList($idAd)
	{
		$planetsInfo = [];

		$db = self::loadDb();

		// Planets List
		$req = $db->prepare("SELECT activation, id_classroom, stats_environnement, stats_sante, stats_social FROM 1ta_planets WHERE id_admin = :idAd ");
		$req->bindValue(':idAd', $idAd, PDO::PARAM_INT);
		$req->execute();
		$planetsStats = $req->fetchAll(PDO::FETCH_ASSOC);
		// Planets list exist ?
		if (!empty($planetsStats))
		{
			// Check planets for this admin account
			$req = $db->prepare("SELECT name FROM pe_classrooms WHERE id = :idCr AND id_admin = :idAd");
			$req->bindValue(':idAd', $idAd, PDO::PARAM_INT);
			foreach ($planetsStats as $planet)
			{
				$req->bindValue(':idCr', $planet['id_classroom'], PDO::PARAM_INT);
				$req->execute();
				$planetName = $req->fetchAll(PDO::FETCH_ASSOC);
				if (!empty($planetName))
				{
					$planet = array_merge($planetName[0], $planet);
					array_push($planetsInfo, $planet);
				}
			}
		}
		$req->closeCursor();
		$req = NULL;
		return $planetsInfo;
	}

	public static function callStudentsList($classroomsInfos)
	{
		$studentsInfos = [];

		$db = self::loadDb();

		// students name and id
		$studentsBasicInfos = [];
		$req = $db->prepare("SELECT nickname, id, id_classroom FROM pe_students WHERE id_classroom = :idCr AND id_admin = :idAd");
		$req->bindValue(':idAd', $_SESSION['id'], PDO::PARAM_INT);
		foreach ($classroomsInfos as $crInfo)
		{
			$req->bindValue(':idCr', $crInfo['id_classroom'], PDO::PARAM_INT);
			$req->execute();
			array_push($studentsBasicInfos, $req->fetchAll(PDO::FETCH_ASSOC));
		}
		// students stats and replies
		if (!empty($studentsBasicInfos));
		{
			$req = $db->prepare("SELECT serie, stats_envi, stats_sante, stats_social FROM 1ta_stats WHERE id_student = :idSt");
			$req2 = $db->prepare("SELECT serie, reply1, reply2, reply3, reply4, reply5, reply6, reply7, reply8, reply9, open_reply FROM 1ta_replies WHERE id_student = :idSt");
			foreach ($studentsBasicInfos as $classroom)
			{
				foreach ($classroom as $student)
				{
					$idCr = $student["id_classroom"];
					$req->bindParam(':idSt', $student['id'], PDO::PARAM_INT);
					$req->execute();
					$stats = $req->fetchAll(PDO::FETCH_ASSOC);

					$req2->bindParam(':idSt', $student['id'], PDO::PARAM_INT);
					$req2->execute();
					$replies = $req2->fetchAll(PDO::FETCH_ASSOC);

					if (!isset($studentsInfos[$idCr]))
					{
						$studentsInfos[$idCr] = [];
					}
					$rebuildStats = [];
					$mergeStatsAndReplies = [];

					foreach ($stats as $key => $serie)
					{
						$rebuildStats[$serie["serie"]] = ["stats_envi" => $serie["stats_envi"], "stats_sante" => $serie["stats_sante"], "stats_social" => $serie["stats_social"]];
					}
					foreach ($replies as $serie)
					{
						$rebuildStats[$serie["serie"]] += ["replies" => $serie];
						unset($rebuildStats[$serie["serie"]]["replies"]["serie"]);
					}
					array_push($studentsInfos[$idCr], ["id" => $student["id"], "idCr" => $idCr, "nickname" => $student["nickname"], "theme" => $rebuildStats]);
				}
			}
		}
		$req->closeCursor();
		$req = NULL;


		return $studentsInfos;
	}

	public static function callFreeClassroomsList($idAd)
	{
		$classroomsInfo = [];

		$db = self::loadDb();

		// Planets list for this admin
		$req = $db->prepare("SELECT id_classroom FROM 1ta_planets WHERE id_admin = :idAd");
		$req->bindValue(':idAd', $idAd, PDO::PARAM_INT);
		$req->execute();
		$classroomsBusy = $req->fetchAll();
		// Free Classroom for futur planet
			// if at least one of classrooms is used for a planet
		if (!empty($classroomsBusy))
		{
			$req = $db->prepare("SELECT id, name FROM pe_classrooms WHERE id_admin = :idAd");
			$req->bindValue(':idAd', $idAd, PDO::PARAM_INT);
			$req->execute();
			$classrooms = $req->fetchAll();
			foreach ($classrooms as $classroom)
			{
				$classroomInfo;
				$alreadyPlanet = false;
				foreach ($classroomsBusy as $classroomBusy)
				{
					if ($classroomBusy['id_classroom'] == $classroom['id'])
					{
						$alreadyPlanet = true;
					}
				}
				if ($alreadyPlanet == false)
				{
					array_push($classroomsInfo, $classroom);
				}
			}
		}
			// if no one of classrooms is used for planets
		else
		{
			$req = $db->prepare("SELECT id, name FROM pe_classrooms WHERE id_admin = :idAd");
			$req->bindValue(':idAd', $idAd, PDO::PARAM_INT);
			$req->execute();
			$classroomsInfo = $req->fetchAll();
		}
		$req->closeCursor();
		$req = NULL;
		return $classroomsInfo;
	}

	public static function callOpenQuestionAndActivationStatusThemes($idAd)
	{
		$db = self::loadDb();

		// Planets list for this admin
		$req = $db->prepare("SELECT id_classroom, openquestion, theme, activation FROM 1ta_themes WHERE id_admin = :idAd");
		$req->bindValue(':idAd', $idAd, PDO::PARAM_INT);
		$req->execute();
		$activationStatusThemesList = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		$req = NULL;

		foreach ($activationStatusThemesList as $key => $value)
		{
			$activationStatusThemesList[$key]["id_classroom"] = htmlspecialchars($activationStatusThemesList[$key]["id_classroom"], ENT_NOQUOTES);
			$activationStatusThemesList[$key]["openquestion"] = htmlspecialchars($activationStatusThemesList[$key]["openquestion"], ENT_NOQUOTES);
			$activationStatusThemesList[$key]["theme"] = htmlspecialchars($activationStatusThemesList[$key]["theme"], ENT_NOQUOTES);
			$activationStatusThemesList[$key]["activation"] = htmlspecialchars($activationStatusThemesList[$key]["activation"], ENT_NOQUOTES);
		}
		return $activationStatusThemesList;
	}

	public static function recordPlanetActivationModifications($idCrs, $activationStatus)
	{
		$idsCr = [];

		$db = self::loadDb();

		$req = $db->prepare("UPDATE 1ta_planets SET activation = :activation WHERE id_classroom = :idCr AND id_admin = :idAd");
		$req->bindValue(':idAd', $_SESSION['id'], PDO::PARAM_INT);
		foreach ($idCrs as $index => $idCr)
		{
			$req->bindValue(':idCr', intval($idCr), PDO::PARAM_INT);
			$req->bindValue(':activation', intval($activationStatus[$index]), PDO::PARAM_INT);
			$req->execute();
		}
		$req->closeCursor();
		$req = NULL;
	}

	public static function recordThemeActivationModifications($idCrs, $themes, $activationStatus)
	{
		$idsCr = [];

		$db = self::loadDb();

		// Check if classroom belongs to this admin
		$req = $db->prepare("SELECT id FROM 1ta_themes WHERE id_admin = :idAd AND id_classroom = :idCr AND theme = :theme");
		$updt = $db->prepare("UPDATE 1ta_themes SET activation = :activation WHERE id = :id AND id_admin = :idAd");
		$rec = $db->prepare("INSERT INTO 1ta_themes (id_admin, id_classroom, theme, activation) VALUES (:idAd, :idCr, :theme, :activation)");

		$req->bindValue(':idAd', $_SESSION['id'], PDO::PARAM_INT);
		$updt->bindValue(':idAd', $_SESSION['id'], PDO::PARAM_INT);
		$rec->bindValue(':idAd', $_SESSION['id'], PDO::PARAM_INT);

		foreach ($idCrs as $key => $idCr)
		{
			$req->bindValue(':idCr', $idCr, PDO::PARAM_INT);
			$rec->bindValue(':idCr', $idCr, PDO::PARAM_INT);

			$req->bindValue(':theme', $themes[$key], PDO::PARAM_STR);
			$rec->bindValue(':theme', $themes[$key], PDO::PARAM_STR);

			$req->execute();
			$idRow = $req->fetch();

			if (isset($idRow) && !empty($idRow))
			{
				$updt->bindValue(':activation', intval($activationStatus[$key]), PDO::PARAM_INT);
				$updt->bindValue(':id', $idRow["id"], PDO::PARAM_INT);
				$updt->execute();
			}
			else
			{
				$rec->bindValue(':activation', intval($activationStatus[$key]), PDO::PARAM_INT);
				$rec->execute();
			}
		}
		$req->closeCursor();
		$req = NULL;
		$updt->closeCursor();
		$updt = NULL;
		$rec->closeCursor();
		$rec = NULL;
	}

	public static function create($idCr)
	{
		//$classroomsInfo = [];

		$db = self::loadDb();

		// Check if classroom belongs to this admin
		$req = $db->prepare("SELECT id FROM pe_classrooms WHERE id_admin = :idAd AND id = :idCr");
		$req->bindValue(':idAd', $_SESSION['id'], PDO::PARAM_INT);
		$req->bindValue(':idCr', $idCr, PDO::PARAM_INT);
		$req->execute();
		$classroom = $req->fetchAll();
		if (!empty($classroom))
		{
			// Check classroom doesn t already have a planet
			$req = $db->prepare("SELECT id FROM 1ta_planets WHERE id_admin = :idAd AND id_classroom = :idCr");
			$req->bindValue(':idAd', $_SESSION['id'], PDO::PARAM_INT);
			$req->bindValue(':idCr', $idCr, PDO::PARAM_INT);
			$req->execute();
			$idPlanet = $req->fetchAll();
			if (empty($idPlanet))
			{
				// take id of this application
				$req = $db->prepare("SELECT id FROM pe_library WHERE name = :name");
				$name = "1TerreAction";
				$req->bindParam(':name', $name, PDO::PARAM_INT);
				$req->execute();
				$idLib = $req->fetch(PDO::FETCH_COLUMN, 0);
				// link classroom to application
				$req = $db->prepare("INSERT INTO pe_rel_cr_library (id_classroom, id_library) VALUES (:idCr, :idLib)");
				$req->bindParam(':idCr', $idCr, PDO::PARAM_INT);
				$req->bindParam(':idLib', $idLib, PDO::PARAM_INT);
				$req->execute();
				// link classroom to planet
				$req = $db->prepare("INSERT INTO 1ta_planets (id_classroom, id_admin) VALUES (:idCr, :idAd)");
				$req->bindParam(':idCr', $idCr, PDO::PARAM_INT);
				$req->bindParam(':idAd', $_SESSION['id'], PDO::PARAM_INT);
				$req->execute();
				// link population to students
				$req = $db->prepare("SELECT id FROM pe_students WHERE id_admin = :idAd AND id_classroom = :idCr");
				$req->bindValue(':idAd', $_SESSION['id'], PDO::PARAM_INT);
				$req->bindValue(':idCr', $idCr, PDO::PARAM_INT);
				$req->execute();
				$studentsId = $req->fetchAll(PDO::FETCH_COLUMN);

				$req = $db->prepare("INSERT INTO 1ta_populations (id_student) VALUES (:idSt)");
				foreach ($studentsId as $idSt)
				{
					$req->bindParam(':idSt', $idSt, PDO::PARAM_INT);
					$req->execute();	
				}
				// link population stats
				/*$req = $db->prepare("INSERT INTO 1ta_stats (id_student, serie) VALUES (:idSt, :serie)");
				foreach ($studentsId as $idSt)
				{
					$req->bindParam(':idSt', $idSt, PDO::PARAM_INT);
					$req->bindValue(':serie', "average", PDO::PARAM_STR);
					$req->execute();	
				}*/
			}
		}
		$req->closeCursor();
		$req = NULL;
	}

	public static function getIdStudents($idCr)
	{
		$db = self::loadDb();

		$req = $db->prepare("SELECT id FROM pe_students WHERE id_admin = :idAd AND id_classroom = :idCr");
		$req->bindParam(':idCr', $idCr, PDO::PARAM_INT);
		$req->bindParam(':idAd', $_SESSION['id'], PDO::PARAM_INT);
		$req->execute();
		$idStudents = $req->fetchAll(PDO::FETCH_COLUMN);

		$req->closeCursor();
		$req = NULL;
		return $idStudents;
	}
}