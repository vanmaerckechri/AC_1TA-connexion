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
		$req = $db->prepare("SELECT stats_water, stats_air, stats_forest, stats_average, unlocked_theme FROM 1ta_populations WHERE id_student = :idSt");
		$req->bindParam(':idSt', $_SESSION['id'], PDO::PARAM_INT);
		$req->execute();
		$stats = $req->fetch(PDO::FETCH_ASSOC);
		return $stats;
	}
}