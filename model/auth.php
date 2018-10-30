<?php

if (file_exists('model/hum.php'))
{
	require('model/hum.php');
}
else if (file_exists('../../model/hum.php'))
{
	require('../../model/hum.php');
}
else
{
	// to work in local without "hum.php" file
	function connectDB()
	{
		$db = new PDO('mysql:host=localhost; dbname=pe_connexion; charset=utf8', "phpmyadmin", "1234");
		//$db = new PDO('mysql:host=localhost; dbname=pe_connexion; charset=utf8', "root", "");
		return $db;
	}
	function getSecretCaptchaKey()
	{
		return "";
	}
}

class Authentification
{
	private $sessionNickname;
	private $sessionClassroom;
	private $sessionPassword;
	private $resultReq;

    public function __construct()
    {
    	$this->hydrate();
    }

	private function hydrate()
  	{
  		$sessionVarNames = ['nickname', 'classroom', 'password'];
  		for ($i = count($sessionVarNames) - 1; $i >= 0; $i--)
  		{
  			$sessionVarName = ucfirst($sessionVarNames[$i]);
  			$sessionVarName = '_session'.$sessionVarName;
	  		if (isset($_SESSION[$sessionVarNames[$i]]))
	  		{
	      		$this->setSession($_SESSION[$sessionVarNames[$i]], $sessionVarName);
	  		}
	  		else
	  		{
				$this->$sessionVarName = "";
	  		}
	  	}
  	}

	private function setSession($sessionVar, $sessionVarName)
	{
		if (is_string($sessionVar))
		{
			$this->$sessionVarName = htmlspecialchars($sessionVar, ENT_NOQUOTES);
		}
	}

	static function startSession()
	{
		// Initialiser
		session_cache_limiter('private_no_expire, must-revalidate');
	    session_start();
	    // Petite protection contre l'usurpation d'identité
	    if (!isset($_SESSION['ip']))
	    {
	        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
	    }
	    if ($_SESSION['ip'] != $_SERVER['REMOTE_ADDR'])
	    {
	        $_SESSION = array();
	        $_SESSION['smsAlert']['default'] = '<span class="smsAlert">Vous avez été déconnecté pour des raisons de sécurité!</span>';
	        header('Location: localhost/AC_1TA-connexion/index.php');
	        exit;
	    }
	}

	public function checkSession()
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
		// admin
		if (strstr($this->_sessionNickname, 'admin@'))
		{
			if (!empty($this->_sessionNickname) && !empty($this->_sessionPassword))
			{
				$req = $db->prepare("SELECT id, activated, mail FROM pe_adminaccounts WHERE nickname = :name AND password = :pwd");
				$req->bindValue(':name', $this->_sessionNickname, PDO::PARAM_STR);
				$req->bindValue(':pwd', $this->_sessionPassword, PDO::PARAM_STR);
				$req->execute();
				$resultReq = $req->fetchAll();
				$req->closeCursor();
				$req = NULL;
				// Les données de connexion sont bonnes et le compte a été activé
				if (isset($resultReq) && !empty($resultReq) && $resultReq[0]["activated"] == 1)
				{
					$_SESSION['id'] = $resultReq[0]["id"];
			    	// Si une demande de reinitialisation du pwd a été faite mais qu'on se connecte entre temps => expiration du lien de reinitialisation
			    	$req = $db->prepare("UPDATE pe_adminaccounts SET pwdreset = 0 WHERE id = :idAccount");
					$req->bindValue(':idAccount', $resultReq[0]['id'], PDO::PARAM_INT);
					$req->execute();
					$req->closeCursor();
					$req = NULL;
			    	return 'admin';
				}
				// Le compte n'a pas encore été activé OU les données sont mauvaises
				else
				{
					if ($this->_sessionPassword != '' && $this->_sessionNickname != '')
					{
						// Compte pas encore activé
						if (isset($resultReq) && !empty($resultReq) && $resultReq[0]["activated"] != 1)
						{
							$_SESSION['smsAlert']['default'] = "<span class='smsAlert'>Vous n'avez pas activé votre compte suite à votre inscription. Un nouveau lien d'activation vient de vous être envoyé par mail!</span>";
							$sendActiveCode = new SendMail();
							$sendActiveCode->activeAccount($resultReq[0]["mail"], $resultReq[0]["activated"]);
							$return = 'needActivation';
						}
						// Données sont mauvaises
						else if (!isset($resultReq)|| empty($resultReq))
						{
							$_SESSION['smsAlert']['default'] = "<span class='smsAlert'>Certaines des informations que vous nous avez transmises sont incorrectes!</span>";
							$return = 'wrong';
						}
						return $return;
					}
				}
			}
		}  
		// student
		else
		{
			if (!empty($this->_sessionNickname) && !empty($this->_sessionPassword) && !empty($this->_sessionClassroom))
			{
				// Récuperer l'id de la classe
				$req = $db->prepare("SELECT id FROM pe_classrooms WHERE name = :name");
				$req->bindValue(':name', $this->_sessionClassroom, PDO::PARAM_STR);
				$req->execute();
				$resultReq = $req->fetchAll();
				$req->closeCursor();
				$req = NULL;
				// Si la classe existe, vérifier si les données entrées par l'étudiant sont correctes
				if (!empty($resultReq))
				{
					$_SESSION['id_classroom'] = $resultReq[0]["id"];
					$req = $db->prepare("SELECT id FROM pe_students WHERE nickname = :name AND password = :pwd AND id_classroom = :idcr");
					$req->bindValue(':name', $this->_sessionNickname, PDO::PARAM_STR);
					$req->bindValue(':pwd', $this->_sessionPassword, PDO::PARAM_STR);
					$req->bindValue(':idcr', $resultReq[0]['id'], PDO::PARAM_INT);
					$req->execute();
					$resultReq = $req->fetchAll();
					$req->closeCursor();
					$req = NULL;
					if (!empty($resultReq))
					{
						$_SESSION['id'] = $resultReq[0]["id"];
						return 'student';
					}
				}
				$_SESSION['smsAlert']['default'] = "<span class='smsAlert'>Certaines des informations que vous nous avez transmises sont incorrectes!</span>";
				$_SESSION['id'] = "";
				$_SESSION['nickname'] = "";
				$_SESSION['password'] = "";
				$_SESSION['classroom'] = "";
				$_SESSION['id_classroom'] = "";
				return 'wrong';
			}
		}
	}
}