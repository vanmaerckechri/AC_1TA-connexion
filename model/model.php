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

			$validChar = "";
			// Lettres...
			if (strstr($regEx, 'a-z') && strstr($regEx, 'A-Z'))
			{
				$validChar .= " <span class='smsAlert'>de lettres</span>";
				$validChar .= ",";
				$regEx = str_replace("a-z", "", $regEx);
				$regEx = str_replace("A-Z", "", $regEx);
			}
			else if (strstr($regEx, 'a-z') && !strstr($regEx, 'A-Z'))
			{
				$validChar .= " <span class='smsAlert'>de lettres minuscules</span>";
				$validChar .= ",";
				$regEx = str_replace("a-z", "", $regEx);
			}
			else if (strstr($regEx, 'A-Z') && !strstr($regEx, 'a-z'))
			{
				$validChar .= " <span class='smsAlert'>de lettres majuscules</span>";
				$validChar .= ",";
				$regEx = str_replace("A-Z", "", $regEx);
			}
			// Lettres accentuées...
			if (strstr($regEx, 'À-Ö'))
			{
				$validChar .= " <span class='smsAlert'>de lettres accentuées</span>";
				$validChar .= ",";
				$regEx = str_replace("À-Ö", "", $regEx);
			}
			else if (strstr($regEx, 'à-ö') && !strstr($regEx, 'À-Ö'))
			{
				$validChar .= " <span class='smsAlert'>de lettres minuscules pouvant être accentuées</span>";
				$validChar .= ",";
				$regEx = str_replace("à-ö", "", $regEx);
			}
			else if (strstr($regEx, 'À-Ö') && !strstr($regEx, 'à-ö'))
			{
				$validChar .= " <span class='smsAlert'>de lettres majuscules pouvant être accentuées</span>";
				$validChar .= ",";
				$regEx = str_replace("À-Ö", "", $regEx);
			}
			// Chiffres...
			if (strstr($regEx, '0-9'))
			{
				$validChar .= " <span class='smsAlert'>de chiffres</span>";
				$validChar .= ",";
				$regEx = str_replace("0-9", "", $regEx);
			}
			// Espaces...
			if (strstr($regEx, ' '))
			{
				$validChar .= " <span class='smsAlert'>d'espaces</span>";
				$validChar .= ",";
				$regEx = str_replace(" ", "", $regEx);
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
						$regEx = str_replace($regEx[$i], "", $regEx);
					}
				}
				$validChar .= ",";
			}
			// Améliorer la formulation et la ponctuation du message
			for ($validCharLength = strlen($validChar) - 1, $i = $validCharLength; $i >= 0; $i--)
			{
				if ($validChar[$i] == ",")
				{
					if ($i == $validCharLength)
					{
						$validChar = substr_replace($validChar, "!", $i, 1);
					}
					else
					{
						$validChar = substr_replace($validChar, " et", $i, 1);
						break;
					}
				}
			}

			//$_SESSION['smsAlert'] = "Ce champ ne peut être composé <span class='smsAlert'>QUE</span>".$validChar;
			return ["Ce champ ne peut être composé <span class='smsAlert'>QUE</span>".$validChar];
		}
	}
	else
	{
		return ["Le nombre de caractères pour ce champ doit être compris entre <span class='smsAlert'>".$minLength."</span> et <span class='smsAlert'>".$maxLength."</span>!"];
	}
}

class Authentification
{
	private $sessionNickname;
	private $sessionClassroom;
	private $sessionPwd;
	private $sessionStatus;
	private $resultReq;

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

	static function startSession()
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
	        $_SESSION['smsAlert']['default'] = 'Vous avez été déconnecté pour des raisons de sécurité!';
	        header('Location: localhost/AC_1TA-connexion/index.php');
	        exit;
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

	public function checkSession()
	{
		$db = $this->loadDB();

		if (strstr($this->_sessionNickname, 'admin@'))
		{
			$req = $db->prepare("SELECT id FROM PE_adminAccounts WHERE nickname = :name AND password = :pwd AND activated = 1");
		}
		else
		{
			$req = $db->prepare("SELECT * FROM `$this->_sessionClassroom` WHERE nickname = :name AND password = :pwd");
		}
		$req->bindValue(':name', $this->_sessionNickname, PDO::PARAM_STR);
		$req->bindValue(':pwd', $this->_sessionPwd, PDO::PARAM_STR);
		$req->execute();
		$resultReq = $req->fetch();
		$req->closeCursor();
		$req = NULL;

		// Les données de connexion sont bonnes
		if ($resultReq != false)
		{
		    if (strstr($this->_sessionNickname, 'admin@'))
		    {
		    	// Admin
		    	return 'admin';
		    }
		    else
		    {
		    	// Student
		    	return 'student';
		    }
		}
		// Les données de connexion sont mauvaises
		else
		{
			if ($this->_sessionPwd != '' && $this->_sessionNickname != '')
			{
				$_SESSION['smsAlert']['default'] = "<span class='smsAlert'>Certaines des informations que vous nous avez transmises sont incorrectes!</span>";
				$_SESSION['nickname'] = '';
				$_SESSION['classroom'] = '';
				$_SESSION['password'] = '';
				return 'wrong';
			}
		}  
	}
}

class SendMail
{
	public function activeAccount($mail, $id, $code)
	{
		$_SESSION['smsAlert']['default'] = "Vous venez de recevoir un lien de validation dans votre boîte mail!";
		$_sujet = "Lien d'Activation du Compte!";
		$_message = '<p>Bienvenue! Pour activer votre compte veuillez cliquer sur le lien suivant.
		<a href="https://cvm.one/test/index.php?action=activate&id='.$id.'&code='.$code.'">https://cvm.one/test/index.php?action=activate&id='.$id.'&code='.$code.'</a></p>';
		$_destinataire = $mail;

		$_headers = "From: \"Plateforme Éducative\"<robot@cvm.one>\n";
		$_headers .= "Reply-To: admin@cvm.one\n";
		$_headers .= "Content-Type: text/html; charset=\"ISO-8859-1\"\n";
		$_headers .= "Content-Transfer-Encoding: 8bit";
		$_sendMail = mail($_destinataire, $_sujet, $_message, $_headers);
	}
	public function resetPwd($mail, $id, $rstpwd)
	{
		$_SESSION['smsAlert']['default'] = "<p class='sms'>Un mail pour reinitialiser votre password vient de vous être envoyé!</p>";
		$_sujet = "Réinitialisation du Mot de Passe";
		$_message = '<p>Bienvenue! Cliquer sur le lien suivant pour reinitialiser votre password.
		<a href="https://cvm.one/test/index.php?action=resetpwd&id='.$id.'&rstpwd='.$rstpwd.'">https://cvm.one/test/index.php?action=activate&resetpwd='.$id.'&rstpwd='.$rstpwd.'</a></p>';
		$_destinataire = $mail;

		$_headers = "From: \"Plateforme Éducative\"<robot@cvm.one>\n";
		$_headers .= "Reply-To: admin@cvm.one\n";
		$_headers .= "Content-Type: text/html; charset=\"ISO-8859-1\"\n";
		$_headers .= "Content-Transfer-Encoding: 8bit";
		$_sendMail = mail($_destinataire, $_sujet, $_message, $_headers);
	}
	public function callNickname($mail, $nickname)
	{
		$_SESSION['smsAlert']['default'] = "<p class='sms'>Un mail pour reinitialiser votre password vient de vous être envoyé!</p>";
		$_sujet = "Votre Nom d'Utilisateur";
		$_message = "<p>Bienvenue! Votre nom d'utilisateur est le suivant: ".$nickname.".</p>";
		$_destinataire = $mail;

		$_headers = "From: \"Plateforme Éducative\"<robot@cvm.one>\n";
		$_headers .= "Reply-To: admin@cvm.one\n";
		$_headers .= "Content-Type: text/html; charset=\"ISO-8859-1\"\n";
		$_headers .= "Content-Transfer-Encoding: 8bit";
		$_sendMail = mail($_destinataire, $_sujet, $_message, $_headers);
	}
}