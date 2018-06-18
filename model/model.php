<?php

function filterInputs($input, $regEx, $minLength, $maxLength, $smsTitle)
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
			if ($smsTitle != false)
			{
				$_SESSION['smsAlert'][$smsTitle] = "Ce champ ne peut être composé <span class='smsAlert'>QUE</span>".$validChar;
			}
		}
	}
	else
	{
		if ($smsTitle != false)
		{
			$_SESSION['smsAlert'][$smsTitle] = "Le nombre de caractères pour ce champ doit être compris entre <span class='smsAlert'>".$minLength."</span> et <span class='smsAlert'>".$maxLength."</span>!";
		}
	}
	return false;
}

function loadDb()
{
	try
	{
		$db = new PDO('mysql:host=localhost; dbname=PE_connexion; charset=utf8', "root", "");
		return $db;
	} 
	catch (Exception $e)
	{
	    die('Erreur : ' . $e->getMessage());
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

	public function checkSession()
	{
		$db = loadDB();

		if (strstr($this->_sessionNickname, 'admin@'))
		{
			$req = $db->prepare("SELECT id FROM PE_adminAccounts WHERE nickname = :name AND password = :pwd AND activated = 1");
		}
		else
		{
			$req = $db->prepare("SELECT * FROM `$this->_sessionClassroom` WHERE nickname = :name AND password = :pwd");
		}
		$req->bindValue(':name', $this->_sessionNickname, PDO::PARAM_STR);
		$req->bindValue(':pwd', $this->_sessionPassword, PDO::PARAM_STR);
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
			if ($this->_sessionPassword != '' && $this->_sessionNickname != '')
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

class RecordAccount
{
	private $nickname;
	private $mail;
	private $classroom;
	private $pwd;
	public $alreadyExist;

    public function __construct($nickname, $mail, $classroom, $pwd)
    {
    	$this->hydrate($nickname, $mail, $classroom, $pwd);
    	$this->saveInDb();
    }

	private function hydrate($nickname, $mail, $classroom, $pwd)
  	{
  		$variables = [$nickname, $mail, $classroom, $pwd];
  		$variablesName = ['_nickname', '_mail', '_classroom', '_pwd'];
  		for ($i = count($variables) - 1; $i >= 0; $i--)
  		{
  			$this->setVariable($variables[$i], $variablesName[$i]);
  		}
  	}

  	private function setVariable($input, $name)
	{
		$this->$name = htmlspecialchars($input, ENT_NOQUOTES);
		if ($name == '_pwd')
		{
			$this->_pwd = hash('sha256', $this->_pwd);
		}
		else if ($name == '_nickname')
		{
			$this->_nickname = 'admin@'.$this->_nickname;
		}
	}

	public function getAlreadyExist()
	{
		return $this->_alreadyExist;
	}

	private function saveInDb()
	{
		$db = loadDB();

		// Login or mail already exist ?
		if ($this->_classroom == false)
		{
			$req = $db->prepare("SELECT nickname, mail FROM PE_adminAccounts WHERE mail = :email OR nickname = :name");
			$req->bindValue(':email', $this->_mail, PDO::PARAM_STR);
		}
		else
		{
			$req = $db->prepare("SELECT * FROM `$this->_sessionClassroom` WHERE nickname = :name");
		}
		$req->bindValue(':name', $this->_nickname, PDO::PARAM_STR);
		$req->execute();
		$resultReq = $req->fetchAll(PDO::FETCH_ASSOC);

		$mailExist = false;
		$nameExist = false;
		foreach ($resultReq as $mailsAndNames) 
		{
			if ($mailsAndNames['mail'] == $this->_mail)
			{
				$_SESSION['smsAlert']['email'] = "<span class='smsAlert'>Cette adresse email est déjà prise!</span>";
				$mailExist = true;
			}
			if ($mailsAndNames['nickname'] == $this->_nickname)
			{
				$_SESSION['smsAlert']['nickname'] = "<span class='smsAlert'>Ce nom d'utilisateur existe déjà!</span>";
				$nameExist = true;
			}
			if ($mailExist == true && $nameExist == true)
			{
				break;
			}
		}
		if ($mailExist == true || $nameExist == true)
		{
			$this->_alreadyExist = true;
			$req->closeCursor();
			$req = NULL;	
			return;
		}

		// Insert new account in DB
		if ($this->_classroom == false)
		{
			$req = $db->prepare("INSERT INTO PE_adminAccounts (nickname, mail, password, pwdreset, activated) VALUES (:name, :email, :pwd, :pwdreset, :activated)");
			$req->bindValue(':pwdreset', 0, PDO::PARAM_INT);
			$req->bindValue(':activated', 1, PDO::PARAM_INT);
		}
		else
		{
			$req = $db->prepare("INSERT INTO `$this->_classroom` (nickname, mail, password) VALUES (:name, :email, :pwd)");
		}
		$req->bindValue(':name', $this->_nickname, PDO::PARAM_STR);
		$req->bindValue(':email', $this->_mail, PDO::PARAM_STR);
		$req->bindValue(':pwd', $this->_pwd, PDO::PARAM_STR);

		$req->execute();
		$resultReq = $req->fetch();
		$req->closeCursor();
		$req = NULL;		
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