<?php

class Authentification
{
	private $sessionNickname;
	private $sessionClassroom;
	private $sessionPwd;
	private $sessionStatus;

    public function __construct()
    {
    	$this->hydrate();
    }

	private function hydrate()
  	{
  		if (isset($_SESSION['nickname']))
  		{
      		$this->setSessionNickname($_SESSION['nickname']);
  		}
  		else
		{
			$this->_sessionNickname = '';
		}
		if (isset($_SESSION['classroom']))
  		{
      		$this->setSessionClassroom($_SESSION['classroom']);
  		}
  		else
		{
			$this->_sessionClassroom = '';
		}
  		if (isset($_SESSION['password']))
  		{
      		$this->setSessionPwd($_SESSION['password']);
  		}
  		else
		{
			$this->_sessionPwd = '';
		}
		if (isset($_SESSION['sessionStatus']))
  		{
      		$this->setSessionStatus($_SESSION['sessionStatus']);
  		}
  		else
		{
			$this->_sessionStatus = '';
		}
  	}

	private function setSessionNickname($sessionNickname)
	{
		if (is_string($sessionNickname))
		{
			$this->_sessionNickname = htmlspecialchars($sessionNickname, ENT_NOQUOTES);
		}
	}
	private function setSessionClassroom($sessionClassroom)
	{
		if (is_string($sessionClassroom))
		{
			$this->_sessionClassroom = htmlspecialchars($sessionClassroom, ENT_NOQUOTES);
		}	
	}
	private function setSessionPwd($sessionPwd)
	{
		if (is_string($sessionPwd))
		{
			$this->_sessionPwd = htmlspecialchars($sessionPwd, ENT_NOQUOTES);
		}
	}
	private function setSessionStatus($sessionStatus)
	{
		if (is_string($sessionStatus))
		{
			$this->_sessionStatus = htmlspecialchars($sessionStatus, ENT_NOQUOTES);
		}	
	}

	public function startSession()
	{
		// Initialiser
		session_cache_limiter('private_no_expire, must-revalidate');
	    session_start();
	    // Petite protection contre l'usurpation d'identité
	    if(!isset($_SESSION['ip']))
	    {
	        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
	    }
	    if($_SESSION['ip']!=$_SERVER['REMOTE_ADDR'])
	    {
	        $_SESSION = array();
	        $_SESSION['smsAlert'] = 'Vous avez été déconnecté pour des raisons de sécurité!';
	        header('Location: http://localhost/AC_1TA-connexion/index.php');
	        exit;
	    }
	    // Vérifier si l'utilisateur est déjà connecté
	    if (isset($_SESSION['connexion']) && isset($_SESSION['userStatus']) && $_SESSION['connexion'] == TRUE)
	    {
	    	if ($_SESSION['userStatus'] == 0)
	    	{
	    		header('Location: http://localhost/AC_1TA-connexion/platform/index.php');	    		
	    	}
	    }
	}

	private function loadDB()
	{
		try
		{
    		$db = new PDO('mysql:host=localhost; dbname=PE_connexion; charset=utf8', "phpmyadmin", "1234");
    		return $db;
		} 
		catch (Exception $e)
		{
		    die('Erreur : ' . $e->getMessage());
		}
	}

	public function connexion()
	{
		$db = $this->loadDB();

		if (strstr($this->_sessionNickname, 'admin@'))
		{
			$requete = $db->prepare("SELECT id FROM PE_adminAccounts WHERE nickname = :name AND password = :pwd");
			// userStatus 1 = admin
			$_SESSION['userStatus'] = 1;
		}
		else
		{
			$requete = $db->prepare("SELECT * FROM `$this->_sessionClassroom` WHERE nickname = :name AND password = :pwd");
			// userStatus 0 = user
			$_SESSION['userStatus'] = 0;
		}

		$requete->bindValue(':name', $this->_sessionNickname, PDO::PARAM_STR);
		$requete->bindValue(':pwd', $this->_sessionPwd, PDO::PARAM_STR);
		$requete->execute();

		if ($requete->fetch())
		{
		    $_SESSION['smsAlert'] = "Connexion réussie!";
		    $_SESSION['connexion'] = TRUE;
		}
		else
		{
		    $_SESSION['smsAlert'] = "<span class='smsAlert'>Certaines des informations que vous nous avez transmises sont incorrectes!</span>";
		    $_SESSION['connexion'] = FALSE;
		}

		$requete->closeCursor();
		$requete = NULL;
	}
}