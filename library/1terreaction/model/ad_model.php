<?php
class ManagePlanets
{
	public static function callList($idAd)
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
	public static function create($idAd, $idCr)
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
		$req->bindValue(':idAd', $idAd, PDO::PARAM_INT);
		$req->bindValue(':idCr', $idCr, PDO::PARAM_INT);
		$req->execute();
		$classroom = $req->fetchAll();
		if (!empty($classroom))
		{
			// Check classroom doesn t already have a planet
			$req = $db->prepare("SELECT id FROM 1ta_planets WHERE id_admin = :idAd AND id_classroom = :idCr");
			$req->bindValue(':idAd', $idAd, PDO::PARAM_INT);
			$req->bindValue(':idCr', $idCr, PDO::PARAM_INT);
			$req->execute();
			$classroom = $req->fetchAll();
			if (empty($classroom))
			{
				$req = $db->prepare("INSERT INTO 1ta_planets (id_classroom, id_admin) VALUES (:idCr, :idAd)");
				$req->bindValue(':idCr', $idCr, PDO::PARAM_INT);
				$req->bindValue(':idAd', $idAd, PDO::PARAM_INT);
				$req->execute();
			}
		}
		$req->closeCursor();
		$req = NULL;
	}
}