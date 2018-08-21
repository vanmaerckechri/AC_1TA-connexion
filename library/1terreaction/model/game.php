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
		// player stats
		$req = $db->prepare("SELECT stats_environnement, stats_sante, stats_social, stats_average, unlocked_theme FROM 1ta_populations WHERE id_student = :idSt");
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
	public static function start($replies)
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
			$req->bindValue(':idReplies', $idReplies, PDO::PARAM_INT);
			$req->bindParam(':open_reply', $replies[9], PDO::PARAM_STR);
			for ($i = 0; $i < 9; $i++)
			{
				$replyCol = ":reply".($i+1);
				$req->bindParam($replyCol, $replies[$i], PDO::PARAM_INT);
			}
			$req->execute();
		}
		// Replies row doesn't exist -> create
		else
		{
			// take id admin
			$req = $db->prepare("SELECT id_admin FROM 1ta_populations WHERE id_classroom = :idCr AND id_student = :idSt");
			$req->bindParam(':idSt', $_SESSION['id'], PDO::PARAM_INT);
			$req->bindParam(':idCr', $_SESSION['id_classroom'], PDO::PARAM_INT);
			$req->execute();
			$idAdmin = $req->fetch(PDO::FETCH_ASSOC);
			if ($idAdmin == false)
			{
				$req->closeCursor();
				$req = NULL;
				return "Vous avez été exclu de la planète ou celle-ci n'existe plus!";
			}
			else
			{
				$req = $db->prepare("INSERT INTO 1ta_replies (id_admin, id_classroom, id_student, serie, reply1, reply2, reply3, reply4, reply5, reply6, reply7, reply8, reply9, open_reply) VALUES (:idAd, :idCr, :idSt, :serie, :reply1, :reply2, :reply3, :reply4, :reply5, :reply6, :reply7, :reply8, :reply9, :open_reply)");
				$req->bindParam(':idAd', $idAdmin, PDO::PARAM_INT);
				$req->bindParam(':idCr', $_SESSION['id_classroom'], PDO::PARAM_INT);
				$req->bindParam(':idSt', $_SESSION['id'], PDO::PARAM_INT);
				$req->bindParam(':serie', $replies[10], PDO::PARAM_STR);
				$req->bindParam(':open_reply', $replies[9], PDO::PARAM_STR);
				for ($i = 0; $i < 9; $i++)
				{
					$replyCol = ":reply".($i+1);
					$req->bindParam($replyCol, $replies[$i], PDO::PARAM_INT);
				}
				$req->execute();
			}
		}
		$req->closeCursor();
		$req = NULL;
		// create if doesn t exist
		/*if ($idReplies) 
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
		    'playerStats' => $stats,
		    'openquestion' => $openquestions
		];

		$req->closeCursor();
		$req = NULL;
		return $gameInfos;*/
	}
}