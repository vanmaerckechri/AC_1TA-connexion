<?php
class Avatar
{
	public static function loadDb()
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
		return $db;		
	}
	public static function load()
	{
		$db = self::loadDb();
		$req = $db->prepare("SELECT avatar_tete, avatar_yeux, avatar_lunettes, avatar_bouche, avatar_cheveux FROM pe_students WHERE id = :idSt AND password = :password");
		$req->bindValue(':idSt', $_SESSION['id'], PDO::PARAM_INT);
		$req->bindValue(':password', $_SESSION['password'], PDO::PARAM_STR);
		$req->execute();
		$avatarInfos = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		$req = NULL;
		return $avatarInfos;
	}
	public static function update($tete, $yeux, $lunettes, $bouche, $cheveux)
	{
		$db = self::loadDb();
	    $req = $db->prepare("UPDATE pe_students SET avatar_tete = :avatar_tete, avatar_yeux = :avatar_yeux, avatar_lunettes = :avatar_lunettes, avatar_bouche = :avatar_bouche, avatar_cheveux = :avatar_cheveux WHERE id = :idSt AND password = :password");
		$req->bindValue(':idSt', $_SESSION['id'], PDO::PARAM_INT);
		$req->bindValue(':password', $_SESSION['password'], PDO::PARAM_STR);
		$req->bindValue(':avatar_tete', $tete, PDO::PARAM_STR);
		$req->bindValue(':avatar_yeux', $yeux, PDO::PARAM_STR);
		$req->bindValue(':avatar_lunettes', $lunettes, PDO::PARAM_STR);
		$req->bindValue(':avatar_bouche', $bouche, PDO::PARAM_STR);
		$req->bindValue(':avatar_cheveux', $cheveux, PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
		$req = NULL;
	}
}