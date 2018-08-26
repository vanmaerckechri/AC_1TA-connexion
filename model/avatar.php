<?php
class Avatar
{
	public static function load()
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
		$req = $db->prepare("SELECT avatar_tete, avatar_yeux, avatar_lunettes, avatar_bouche, avatar_cheveux FROM pe_students WHERE id = :idSt AND password = :password");
		$req->bindValue(':idSt', $_SESSION['id'], PDO::PARAM_INT);
		$req->bindValue(':password', $_SESSION['password'], PDO::PARAM_STR);
		$req->execute();
		$avatarInfos = $req->fetchAll(PDO::FETCH_ASSOC);
		$req->closeCursor();
		$req = NULL;
		return $avatarInfos;
	}
}