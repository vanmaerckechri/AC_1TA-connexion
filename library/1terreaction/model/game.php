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
		$req = $db->prepare("SELECT stats_water, stats_air, stats_forest, stats_average, unlocked_theme FROM 1ta_populations WHERE id_student = :idSt");
		$req->bindParam(':idSt', $_SESSION['id'], PDO::PARAM_INT);
		$req->execute();
		$stats = $req->fetch(PDO::FETCH_ASSOC);
		// questions
		$req = $db->prepare("SELECT * FROM 1ta_theme_questions WHERE id <= :idUnlocked");
		$req->bindParam(':idUnlocked', $stats['unlocked_theme'], PDO::PARAM_INT);
		$req->execute();
		$questions = $req->fetch(PDO::FETCH_ASSOC);
		// propositions
		$req = $db->prepare("SELECT * FROM 1ta_theme_propositions INNER JOIN 1ta_rel_questions_propositions ON 1ta_theme_propositions.id = 1ta_rel_questions_propositions.id_proposition AND 1ta_rel_questions_propositions.id_question <= :idUnlocked");
		$req->bindParam(':idUnlocked', $stats['unlocked_theme'], PDO::PARAM_INT);
		$req->execute();
		$propositions = $req->fetchAll(PDO::FETCH_ASSOC);


		$gameInfos = (object) 
		[
		    'playerStats' => $stats,
		    'questions' => $questions,
		   	'propositions' => $propositions
		];

		$req->closeCursor();
		$req = NULL;
		return $gameInfos;
	}
}