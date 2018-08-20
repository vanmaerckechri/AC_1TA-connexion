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