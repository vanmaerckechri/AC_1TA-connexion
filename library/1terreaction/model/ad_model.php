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
				if (!empty($result[0]))
				{
					array_push($planetsInfo, $result[0]);
				}
			}
		}
		$req->closeCursor();
		$req = NULL;
		return $planetsInfo;
	}

	public static function callStudentsList($classroomsInfos)
	{
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
		$studentsInfos = [];
		$req = $db->prepare("SELECT nickname, id, id_classroom FROM pe_students WHERE id_classroom = :idCr AND id_admin = :idAd");
		$req->bindValue(':idAd', $_SESSION['id'], PDO::PARAM_INT);
		foreach ($classroomsInfos as $crInfo)
		{
			$req->bindValue(':idCr', $crInfo['id'], PDO::PARAM_INT);
			$req->execute();
			array_push($studentsInfos, $req->fetchAll(PDO::FETCH_ASSOC));
		}
		// students stats
		if (!empty($studentsInfos));
		{
			$req = $db->prepare("SELECT stats_envi, stats_sante, stats_social FROM 1ta_stats WHERE id_student = :idSt AND serie = :serie");
			foreach ($studentsInfos as $classroom)
			{
				foreach ($classroom as $student)
				{
					$classroomId = $student["id_classroom"];
					$req->bindParam(':idSt', $student['id'], PDO::PARAM_INT);
					$req->bindValue(':serie', "average", PDO::PARAM_STR);
					$req->execute();
					$studentsListStats = $req->fetch(PDO::FETCH_ASSOC);
					$studentInfos = $student;
					if (isset($studentsList[$student["id_classroom"]]))
					{
						array_push($studentsList[$student["id_classroom"]], array_merge($studentInfos, $studentsListStats));
					}
					else
					{
						$studentsList[$student["id_classroom"]] = [];
						array_push($studentsList[$student["id_classroom"]], array_merge($studentInfos, $studentsListStats));
					}
				}
			}
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
				$req = $db->prepare("INSERT INTO 1ta_stats (id_student, serie) VALUES (:idSt, :serie)");
				foreach ($studentsId as $idSt)
				{
					$req->bindParam(':idSt', $idSt, PDO::PARAM_INT);
					$req->bindValue(':serie', "average", PDO::PARAM_INT);
					$req->execute();	
				}
			}
		}
		$req->closeCursor();
		$req = NULL;
	}
}