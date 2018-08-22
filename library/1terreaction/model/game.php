<?php
class GameInfos
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
	public static function call()
	{
		$db = self::loadDb();
		// player game infos
		$req = $db->prepare("SELECT unlocked_theme FROM 1ta_populations WHERE id_student = :idSt");
		$req->bindParam(':idSt', $_SESSION['id'], PDO::PARAM_INT);
		$req->execute();
		$playerGameInfos = $req->fetch(PDO::FETCH_ASSOC);
		// player game infos
		$req = $db->prepare("SELECT stats_envi, stats_sante, stats_social FROM 1ta_stats WHERE id_student = :idSt");
		$req->bindParam(':idSt', $_SESSION['id'], PDO::PARAM_INT);
		$req->execute();
		$stats = $req->fetch(PDO::FETCH_ASSOC);
		// open question
		$req = $db->prepare("SELECT serie, question FROM 1ta_openquestions WHERE id_classroom = :idCr");
		$req->bindParam(':idCr', $_SESSION['id_classroom'], PDO::PARAM_INT);
		$req->execute();
		$openquestions = $req->fetch(PDO::FETCH_ASSOC);
		foreach ($openquestions as $key => $openquestion) 
		{
			$openquestions[$key] = htmlspecialchars($openquestion, ENT_QUOTES);
		}
		foreach ($stats as $key => $stat) 
		{
			$stats[$key] = htmlspecialchars($stat, ENT_QUOTES);
		}
		$gameInfos = (object) 
		[
			'playerGameInfos' => $playerGameInfos,
		    'playerStats' => $stats,
		    'openquestion' => $openquestions
		];

		$req->closeCursor();
		$req = NULL;
		return $gameInfos;
	}
}

class RecordReplies
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

	public static function giveAverage($values)
	{
		$stats_enviAverage = 0;
		$stats_santeAverage = 0;
		$stats_socialAverage = 0;
		foreach ($values as $stats)
		{
			$stats_enviAverage = $stats_enviAverage + $stats["stats_envi"];
		}
		foreach ($values as $stats)
		{
			$stats_santeAverage = $stats_santeAverage + $stats["stats_sante"];
		}
		foreach ($values as $stats)
		{
			$stats_socialAverage = $stats_socialAverage + $stats["stats_social"];
		}
		$stats_enviAverage = $stats_enviAverage / count($values);
		$stats_santeAverage = $stats_santeAverage / count($values);
		$stats_socialAverage = $stats_socialAverage / count($values);
		return ["stats_enviAverage" => $stats_enviAverage, "stats_santeAverage" => $stats_santeAverage, "stats_socialAverage" => $stats_socialAverage];
	}

	public static function updateStats($serie, $stats_enviAverage, $stats_santeAverage, $stats_socialAverage)
	{
		$db = self::loadDb();
		// LOCAL STATS
		// check if already exist stats for this serie
		$req = $db->prepare("SELECT id FROM 1ta_stats WHERE id_student = :idSt AND serie = :serie");
		$req->bindParam(':idSt', $_SESSION['id'], PDO::PARAM_INT);
		$req->bindParam(':serie', $serie, PDO::PARAM_STR);
		$req->execute();
		$rowExist = $req->fetch(PDO::FETCH_ASSOC);
		if ($rowExist != false)
		{
			$req = $db->prepare("UPDATE 1ta_stats SET stats_envi = :stats_envi, stats_sante = :stats_sante, stats_social = :stats_social WHERE id_student = :idSt AND serie = :serie");
			$req->bindParam(':idSt', $_SESSION['id'], PDO::PARAM_INT);
			$req->bindParam(':serie', $serie, PDO::PARAM_STR);
			$req->bindParam(':stats_envi', $stats_enviAverage, PDO::PARAM_STR);
			$req->bindParam(':stats_sante', $stats_santeAverage, PDO::PARAM_STR);
			$req->bindParam(':stats_social', $stats_socialAverage, PDO::PARAM_STR);
			$req->execute();			
		}
		else
		{
			$req = $db->prepare("INSERT INTO 1ta_stats (id_student, serie, stats_envi, stats_sante, stats_social) VALUES (:idSt, :serie, :stats_envi, :stats_sante, :stats_social)");
			$req->bindParam(':idSt', $_SESSION['id'], PDO::PARAM_INT);
			$req->bindValue(':serie', $serie, PDO::PARAM_INT);
			$req->bindParam(':stats_envi', $stats_enviAverage, PDO::PARAM_STR);
			$req->bindParam(':stats_sante', $stats_santeAverage, PDO::PARAM_STR);
			$req->bindParam(':stats_social', $stats_socialAverage, PDO::PARAM_STR);
			$req->execute();			
		}

		// update average serie stats
		$req = $db->prepare("SELECT stats_envi, stats_sante, stats_social FROM 1ta_stats WHERE id_student = :idSt AND serie != :serie");
		$req->bindParam(':idSt', $_SESSION['id'], PDO::PARAM_INT);
		$req->bindValue(':serie', "average", PDO::PARAM_STR);
		$req->execute();
		$statsAverageFromSeries = $req->fetchAll();

		$average = self::giveAverage($statsAverageFromSeries);

		$req = $db->prepare("UPDATE 1ta_stats SET stats_envi = :stats_envi, stats_sante = :stats_sante, stats_social = :stats_social WHERE id_student = :idSt AND serie = :serie");
		$req->bindParam(':idSt', $_SESSION['id'], PDO::PARAM_INT);
		$req->bindValue(':serie', "average", PDO::PARAM_STR);
		$req->bindParam(':stats_envi', $average["stats_enviAverage"], PDO::PARAM_STR);
		$req->bindParam(':stats_sante', $average["stats_santeAverage"], PDO::PARAM_STR);
		$req->bindParam(':stats_social', $average["stats_socialAverage"], PDO::PARAM_STR);
		$req->execute();		

		// GLOBAL STATS
		// get stats from others players
		$req = $db->prepare("SELECT id FROM pe_students WHERE id_classroom = :idCr");
		$req->bindParam(':idCr', $_SESSION['id_classroom'], PDO::PARAM_INT);
		$req->execute();
		$idStudents = $req->fetchAll(PDO::FETCH_ASSOC);

		$req = $db->prepare("SELECT stats_envi, stats_sante, stats_social FROM 1ta_stats WHERE id_student = :idSt AND serie = :serie");
		$statsAverageFromStudents = [];
		foreach ($idStudents as $idSt)
		{
			$req->bindParam(':idSt', $idSt['id'], PDO::PARAM_INT);
			$req->bindValue(':serie', "average", PDO::PARAM_STR);
			$req->execute();
			array_push($statsAverageFromStudents, $req->fetch(PDO::FETCH_ASSOC));
		}
		// update average serie stats
		$average = self::giveAverage($statsAverageFromStudents);
		$req = $db->prepare("UPDATE 1ta_planets SET stats_environnement = :stats_envi, stats_sante = :stats_sante, stats_social = :stats_social WHERE id_classroom = :idCr");
		$req->bindParam(':idCr', $_SESSION['id_classroom'], PDO::PARAM_INT);
		$req->bindParam(':stats_envi', $stats_enviAverage, PDO::PARAM_INT);
		$req->bindParam(':stats_sante', $stats_santeAverage, PDO::PARAM_INT);
		$req->bindParam(':stats_social', $stats_socialAverage, PDO::PARAM_INT);
		$req->execute();

		$req->closeCursor();
		$req = NULL;
	}

	public static function start($replies, $statsEnviAverage, $statsSanAverage, $statsSoAverage)
	{
		$db = self::loadDb();
		// check if already exist replies for this serie
		$req = $db->prepare("SELECT id FROM 1ta_replies WHERE id_student = :idSt AND serie = :serie");
		$req->bindParam(':idSt', $_SESSION['id'], PDO::PARAM_INT);
		$req->bindParam(':serie', $replies[10], PDO::PARAM_STR);
		$req->execute();
		$idReplies = $req->fetch(PDO::FETCH_ASSOC);
		// Replies row already exist -> update
		if ($idReplies != false)
		{
			$req = $db->prepare("UPDATE 1ta_replies SET reply1 = :reply1, reply2 = :reply2, reply3 = :reply3, reply4 = :reply4, reply5 = :reply5, reply6 = :reply6, reply7 = :reply7, reply8 = :reply8, reply9 = :reply9, open_reply = :open_reply WHERE id = :idReplies");
			$req->bindValue(':idReplies', $idReplies["id"], PDO::PARAM_INT);
			$req->bindParam(':open_reply', $replies[9], PDO::PARAM_STR);
			for ($i = 0; $i < 9; $i++)
			{
				$replyCol = ":reply".($i+1);
				$req->bindParam($replyCol, $replies[$i], PDO::PARAM_INT);
			}
			$req->execute();
			self::updateStats($replies[10], $statsEnviAverage, $statsSanAverage, $statsSoAverage);
		}
		// Replies row doesn't exist -> create
		else
		{
			// check if the planet still exists and if player still exists on it
			$req = $db->prepare("SELECT 1ta_planets.id FROM 1ta_planets, 1ta_populations WHERE 1ta_planets.id_classroom = :idCr AND 1ta_populations.id_student = :idSt");
			$req->bindParam(':idSt', $_SESSION['id'], PDO::PARAM_INT);
			$req->bindParam(':idCr', $_SESSION['id_classroom'], PDO::PARAM_INT);
			$req->execute();
			$rowExist = $req->fetch(PDO::FETCH_ASSOC);
			if ($rowExist == false)
			{
				$req->closeCursor();
				$req = NULL;
				return "Vous avez été exclu de la planète ou celle-ci n'existe plus!";
			}
			else
			{
				$req = $db->prepare("INSERT INTO 1ta_replies (id_student, serie, reply1, reply2, reply3, reply4, reply5, reply6, reply7, reply8, reply9, open_reply) VALUES (:idSt, :serie, :reply1, :reply2, :reply3, :reply4, :reply5, :reply6, :reply7, :reply8, :reply9, :open_reply)");
				$req->bindParam(':idSt', $_SESSION['id'], PDO::PARAM_INT);
				$req->bindParam(':serie', $replies[10], PDO::PARAM_STR);
				$req->bindParam(':open_reply', $replies[9], PDO::PARAM_STR);
				for ($i = 0; $i < 9; $i++)
				{
					$replyCol = ":reply".($i+1);
					$req->bindParam($replyCol, $replies[$i], PDO::PARAM_INT);
				}
				$req->execute();
				self::updateStats($replies[10], $statsEnviAverage, $statsSanAverage, $statsSoAverage);
			}
		}
		$req->closeCursor();
		$req = NULL;
	}
}