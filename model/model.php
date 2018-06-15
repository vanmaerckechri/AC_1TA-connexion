<?php

function filterInputs($input, $regEx, $minLength, $maxLength)
{
	$validRegEx = "/^[".$regEx."]*$/i";
	if (strlen($input) >= $minLength && strlen($input) <= $maxLength)
	{
		if (preg_match($validRegEx, $input))
		{
			return $input;
		}
		else
		{
			// Message dynamique en fonction du regEx
			function cutRegex($char, $regEx)
			{
				return str_replace($char, "", $regEx);
			}
			$validChar = "";
			// Lettres...
			if (strstr($regEx, 'a-z') && strstr($regEx, 'A-Z'))
			{
				$validChar .= " <span class='smsAlert'>de lettres</span>";
				$validChar .= ",";
				$regEx = cutRegex("a-z", $regEx);
				$regEx = cutRegex("A-Z", $regEx);
			}
			else if (strstr($regEx, 'a-z') && !strstr($regEx, 'A-Z'))
			{
				$validChar .= " <span class='smsAlert'>de lettres minuscules</span>";
				$validChar .= ",";
				$regEx = cutRegex("a-z", $regEx);
			}
			else if (strstr($regEx, 'A-Z') && !strstr($regEx, 'a-z'))
			{
				$validChar .= " <span class='smsAlert'>de lettres majuscules</span>";
				$validChar .= ",";
				$regEx = cutRegex("A-Z", $regEx);
			}
			// Lettres accentuées...
			if (strstr($regEx, 'À-Ö'))
			{
				$validChar .= " <span class='smsAlert'>de lettres accentuées</span>";
				$validChar .= ",";
				$regEx = cutRegex("À-Ö", $regEx);
			}
			else if (strstr($regEx, 'à-ö') && !strstr($regEx, 'À-Ö'))
			{
				$validChar .= " <span class='smsAlert'>de lettres minuscules pouvant être accentuées</span>";
				$validChar .= ",";
				$regEx = cutRegex("à-ö", $regEx);
			}
			else if (strstr($regEx, 'À-Ö') && !strstr($regEx, 'à-ö'))
			{
				$validChar .= " <span class='smsAlert'>de lettres majuscules pouvant être accentuées</span>";
				$validChar .= ",";
				$regEx = cutRegex("À-Ö", $regEx);
			}
			// Chiffres...
			if (strstr($regEx, '0-9'))
			{
				$validChar .= " <span class='smsAlert'>de chiffres</span>";
				$validChar .= ",";
				$regEx = cutRegex("0-9", $regEx);
			}
			// Espaces...
			if (strstr($regEx, ' '))
			{
				$validChar .= " <span class='smsAlert'>d'espaces</span>";
				$validChar .= ",";
				$regEx = cutRegex(" ", $regEx);
			}

			// Autres caractères...
			$regExLength = strlen($regEx);
			if ($regExLength > 0)
			{
				$validChar .= $regExLength == 1 ? " du caractère suivant:" : " des caractères suivants:";

				for ($i = $regExLength - 1; $i >= 0; $i--)
				{
					if (strstr($regEx, $regEx[$i]))
					{
						$validChar .= " <span class='smsAlert'>".$regEx[$i]."</span> ";
						$regEx = cutRegex($regEx[$i], $regEx);
					}
				}
			}
			// Améliorer la formulation et la ponctuation du message
			for ($i = strlen($validChar) - 1; $i >= 0; $i--)
			{
				if ($validChar[$i] == ",")
				{
					$validChar = substr_replace($validChar, " et", $i, 1);
					break;
				}
			}
			$validChar .= "!";

			$_SESSION['smsAlert'] = "Ce champ ne peut être composé <span class='smsAlert'>QUE</span>".$validChar;
		}
	}
	else
	{
		$_SESSION['smsAlert'] = "Le nombre de caractères pour ce champ doit être compris entre <span class='smsAlert'>".$minLength."</span> et <span class='smsAlert'>".$maxLength."</span>!";
	}
	return FALSE;
}

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
		session_cache_limiter('private_no_expire, must-revalidate');
	    session_start();
	    if(!isset($_SESSION['ip']))
	    {
	        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
	    }
	    if($_SESSION['ip']!=$_SERVER['REMOTE_ADDR'])
	    {
	        $_SESSION = array();
	        $_SESSION['smsAlert'] = 'Vous avez été déconnecté pour des raisons de sécurité!';
	        header('Location: index.php');
	        exit;
	    }
	}

    public function checkSession()
	{

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
			$requete = $db->prepare("SELECT * FROM PE_adminAccounts WHERE nickname = :name AND password = :pwd");
		}
		else
		{
			$requete = $db->prepare("SELECT * FROM `$this->_sessionClassroom` WHERE nickname = :name AND password = :pwd");
		}

		$requete->bindValue(':name', $this->_sessionNickname, PDO::PARAM_STR);
		$requete->bindValue(':pwd', $this->_sessionPwd, PDO::PARAM_STR);

		$requete->execute();

		if ($requete->fetch())
		{
		    $_SESSION['smsAlert'] = "Le client: ".$this->_sessionNickname." existe !";
		}
		else
		{
		    $_SESSION['smsAlert'] = "Le client: ".$this->_sessionNickname." n'existe pas !";			
		}

		$requete->closeCursor();
		$requete = NULL;
	}
}