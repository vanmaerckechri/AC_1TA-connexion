<?php
class ManagePlanets
{
	public static function callPlanetList($idAd)
	{
		$planetsInfo = [];
		try
		{
		    $db = connectDB();
		    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} 
		catch (Exception $e)
		{
		    die('Erreur : ' . $e->getMessage());
		}
		// Planets List
		$req = $db->prepare("SELECT id_classroom FROM 1ta_planets");
		$req->execute();
		$classroomLinkedToPlanet = $req->fetchAll();
		// Planets list exist ?
		if (!empty($classroomLinkedToPlanet))
		{
			// Check planets for this admin account
			$req = $db->prepare("SELECT id, name FROM pe_classrooms WHERE id = :idCr AND id_admin = :idAd");
			$req->bindValue(':idAd', $idAd, PDO::PARAM_INT);
			foreach ($classroomLinkedToPlanet as $classroom)
			{
				$req->bindValue(':idCr', $classroom['id_classroom'], PDO::PARAM_INT);
				$req->execute();
				$result = $req->fetchAll();
				array_push($planetsInfo, $result[0]);
			}
		}
		$req->closeCursor();
		$req = NULL;
		return $planetsInfo;
	}

	public static function callStudentsList($classroomsInfos)
	{
		$studentsNameList = [];
		$studentsList = [];
		try
		{
		    $db = connectDB();
		    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} 
		catch (Exception $e)
		{
		    die('Erreur : ' . $e->getMessage());
		}
		// students name and id
		$req = $db->prepare("SELECT nickname, id FROM pe_students WHERE id_classroom = :idCr AND id_admin = :idAd");
		$req->bindValue(':idAd', $_SESSION['id'], PDO::PARAM_INT);
		foreach ($classroomsInfos as $crInfo)
		{
			$req->bindValue(':idCr', $crInfo['id'], PDO::PARAM_INT);
			$req->execute();
			$result = $req->fetchAll();
			// index of array = id of the classroom
			$studentsNameList[$crInfo['id']] = $result;
		}
		// students stats
		$req = $db->prepare("SELECT stats_water, stats_air, stats_forest, stats_average, openanswer FROM 1ta_populations WHERE id_classroom = :idCr AND id_admin = :idAd");
		$req->bindValue(':idAd', $_SESSION['id'], PDO::PARAM_INT);
		foreach ($classroomsInfos as $crInfo)
		{
			$req->bindValue(':idCr', $crInfo['id'], PDO::PARAM_INT);
			$req->execute();
			$studentsListStats = $req->fetchAll();
			$studentsListTemp = [];
			// merge array(id and name from students) with their stats
			foreach ($studentsListStats as $keyStats => $studentStats)
			{
				array_push($studentsListTemp, array_merge($studentsNameList[$crInfo['id']][$keyStats], $studentStats));
			}
			$studentsList[$crInfo['id']] = $studentsListTemp;
		}
		$req->closeCursor();
		$req = NULL;

		return $studentsList;
	}

	public static function callFreeClassroomsList($idAd)
	{
		$classroomsInfo = [];
		try
		{
		    $db = connectDB();
		    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} 
		catch (Exception $e)
		{
		    die('Erreur : ' . $e->getMessage());
		}
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
	public static function create($idCr)
	{
		$classroomsInfo = [];
		try
		{
		    $db = connectDB();
		    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} 
		catch (Exception $e)
		{
		    die('Erreur : ' . $e->getMessage());
		}
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
			$classroom = $req->fetchAll();
			if (empty($classroom))
			{
				// link classroom to planet
				$req = $db->prepare("INSERT INTO 1ta_planets (id_classroom, id_admin) VALUES (:idCr, :idAd)");
				$req->bindValue(':idCr', $idCr, PDO::PARAM_INT);
				$req->bindValue(':idAd', $_SESSION['id'], PDO::PARAM_INT);
				$req->execute();
				// insert students into populations
				$req = $db->prepare("SELECT id FROM pe_students WHERE id_admin = :idAd AND id_classroom = :idCr");
				$req->bindValue(':idAd', $_SESSION['id'], PDO::PARAM_INT);
				$req->bindValue(':idCr', $idCr, PDO::PARAM_INT);
				$req->execute();

				$req2 = $db->prepare("INSERT INTO 1ta_populations (id_student, id_classroom, id_admin) VALUES (:idSt, :idCr, :idAd)");
				$req2->bindValue(':idCr', $idCr, PDO::PARAM_INT);
				$req2->bindParam(':idAd', $_SESSION['id'], PDO::PARAM_INT);      
				while($row = $req->fetch())
				{
					$req2->bindValue(':idSt', $row['id'], PDO::PARAM_INT);
					$req2->execute();
				}
				$req2->closeCursor();
				$req2 = NULL;
			}
		}
		$req->closeCursor();
		$req = NULL;
	}
	public static function deletePlanet($idCr)
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
		$del = $db->prepare("DELETE FROM 1ta_planets WHERE id_classroom = :idCr AND id_admin = :idAd");
		$del->bindParam(':idCr', $idCr, PDO::PARAM_INT);
		$del->bindParam(':idAd', $_SESSION['id'], PDO::PARAM_INT);      
		$del->execute();
		$del = $db->prepare("DELETE FROM 1ta_populations WHERE id_classroom = :idCr AND id_admin = :idAd");
		$del->bindParam(':idCr', $idCr, PDO::PARAM_INT);
		$del->bindParam(':idAd', $_SESSION['id'], PDO::PARAM_INT);      
		$del->execute();
	}
}