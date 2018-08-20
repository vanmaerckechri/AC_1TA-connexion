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
			$delInPop = $db->prepare("DELETE FROM 1ta_populations WHERE id_classroom = :idCr AND id_admin = :idAd");
			$delInPop->bindParam(':idAd', $_SESSION['id'], PDO::PARAM_INT);
			$delInPlanet = $db->prepare("DELETE FROM 1ta_planets WHERE id_classroom = :idCr AND id_admin = :idAd");
			$delInPlanet->bindParam(':idAd', $_SESSION['id'], PDO::PARAM_INT);
			foreach ($classroomLinkedToPlanet as $classroom)
			{
				$req->bindValue(':idCr', $classroom['id_classroom'], PDO::PARAM_INT);
				$req->execute();
				$result = $req->fetchAll();
				if (!empty($result[0]))
				{
					array_push($planetsInfo, $result[0]);
				}
				// Classroom no longer exists
				else
				{
					$delInPop->bindParam(':idCr', $classroom['id_classroom'], PDO::PARAM_INT);
					$delInPop->execute();
					$delInPlanet->bindParam(':idCr', $classroom['id_classroom'], PDO::PARAM_INT);
					$delInPlanet->execute();
				}
			}
			$delInPop->closeCursor();
			$delInPop = NULL;
			$delInPlanet->closeCursor();
			$delInPlanet = NULL;
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
		$req = $db->prepare("SELECT stats_environnement, stats_sante, stats_social, stats_average FROM 1ta_populations WHERE id_classroom = :idCr AND id_admin = :idAd");
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
				if (!empty($studentsNameList[$crInfo['id']][$keyStats]))
				{
					array_push($studentsListTemp, array_merge($studentsNameList[$crInfo['id']][$keyStats], $studentStats));
				}
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
			}
		}
		$req->closeCursor();
		$req = NULL;
	}
	public static function refreshPopulationWithClassroom()
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
		// Check planets exist for this admin
		$req = $db->prepare("SELECT id_classroom FROM 1ta_planets WHERE id_admin = :idAd");
		$req->bindValue(':idAd', $_SESSION['id'], PDO::PARAM_INT);
		$req->execute();
		$classroomsId = $req->fetchAll();

		if (!empty($classroomsId))
		{
			$classroomsStudents = [];
			// Student from classroom
			$req = $db->prepare("SELECT id FROM pe_students WHERE id_admin = :idAd AND id_classroom = :idCr");
			foreach ($classroomsId as $cr)
			{
				$req->bindValue(':idAd', $_SESSION['id'], PDO::PARAM_INT);
				$req->bindValue(':idCr', $cr['id_classroom'], PDO::PARAM_INT);
			}
			$req->execute();
			$students = $req->fetchAll(PDO::FETCH_COLUMN, 0);
			$classroomsStudents[$cr['id_classroom']] = $students;
			$PlanetStudents = [];
			foreach ($classroomsId as $cr)
			{
				// Students from admin planets
				$req = $db->prepare("SELECT id_student FROM 1ta_populations WHERE id_admin = :idAd AND id_classroom = :idCr");
				$req->bindValue(':idAd', $_SESSION['id'], PDO::PARAM_INT);
				$req->bindValue(':idCr', $cr['id_classroom'], PDO::PARAM_INT);
				$req->execute();
				$students = $req->fetchAll(PDO::FETCH_COLUMN, 0);
				$PlanetStudents[$cr['id_classroom']] = $students;
			}
			$req->closeCursor();
			$req = NULL;
			// Remove students who alreay have a planet
			$newStudents = [];
			foreach ($PlanetStudents as $key => $value) 
			{
				$newStudents[$key] = array_diff($classroomsStudents[$key], $PlanetStudents[$key]);
				$studentsDeletedFromClassroom[$key] = array_diff($PlanetStudents[$key], $classroomsStudents[$key]);
			}
			// Erase students who are no longer in the classroom
			$del = $db->prepare("DELETE FROM 1ta_populations WHERE id_student = :idSt AND id_admin = :idAd");
			$del2 = $db->prepare("DELETE FROM 1ta_replies WHERE id_student = :idSt AND id_admin = :idAd");
			$del->bindParam(':idAd', $_SESSION['id'], PDO::PARAM_INT);
			$del2->bindParam(':idAd', $_SESSION['id'], PDO::PARAM_INT);           
			foreach ($studentsDeletedFromClassroom as $idStudents)
			{
				foreach ($idStudents as $idSt)
				{	
					$del->bindParam(':idSt', $idSt, PDO::PARAM_INT);
					$del->execute();
					$del2->bindParam(':idSt', $idSt, PDO::PARAM_INT);
					$del2->execute();
				}
			}
			$del->closeCursor();
			$del = NULL;
			$del2->closeCursor();
			$del2 = NULL;
			// Record new students into planet
			$req2 = $db->prepare("INSERT INTO 1ta_populations (id_student, id_classroom, id_admin) VALUES (:idSt, :idCr, :idAd)");
			$req3 = $db->prepare("INSERT INTO 1ta_replies (id_student, id_classroom, id_admin) VALUES (:idSt, :idCr, :idAd)");
			$req2->bindValue(':idAd', $_SESSION['id'], PDO::PARAM_INT);
			$req3->bindValue(':idAd', $_SESSION['id'], PDO::PARAM_INT);
			foreach ($newStudents as $idCr => $idStudents)
			{
				foreach ($idStudents as $idSt)
				{	
					$req2->bindParam(':idCr', $idCr, PDO::PARAM_INT);
					$req2->bindParam(':idSt', $idSt, PDO::PARAM_INT); 
					$req2->execute();
					$req3->bindParam(':idCr', $idCr, PDO::PARAM_INT);
					$req3->bindParam(':idSt', $idSt, PDO::PARAM_INT); 
					$req3->execute();
				}
			}
			$req2->closeCursor();
			$req2 = NULL;
			$req3->closeCursor();
			$req3 = NULL;

		}
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

		$del = $db->prepare("DELETE FROM 1ta_replies WHERE id_classroom = :idCr AND id_admin = :idAd");
		$del->bindParam(':idCr', $idCr, PDO::PARAM_INT);
		$del->bindParam(':idAd', $_SESSION['id'], PDO::PARAM_INT);      
		$del->execute();

		$del = $db->prepare("SELECT id FROM pe_library WHERE name = :name");
		$name = "1TerreAction";
		$del->bindValue(':name', $name, PDO::PARAM_INT);
		$del->execute();
		$idLib = $del->fetch(PDO::FETCH_COLUMN, 0);

		$del = $db->prepare("DELETE FROM pe_rel_cr_library WHERE id_classroom = :idCr AND id_library = :idLib");
		$del->bindParam(':idCr', $idCr, PDO::PARAM_INT);
		$del->bindParam(':idLib', $idLib, PDO::PARAM_INT);   
		$del->execute();
		$del->closeCursor();
		$del = NULL;
	}
}