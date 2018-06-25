<?php

// CHARGE LES DONNEES DE LA DB ET LA CLEF CAPTCHA EN FONCTION DU CONTEXTE LOCAL/DISTANT!
if (file_exists('./model/hum.php'))
{
	require('./model/hum.php');
}
else
{
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

// FILTRES!
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

function generateCode($codeLength = 10)
{
    $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charLength = strlen($char);
    $randCode = '';
    for ($i = 0; $i < $length; $i++)
    {
        $randCode .= $char[rand(0, $charLength - 1)];
    }
    return $randCode;
}

// SESSION!
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
		if (strstr($this->_sessionNickname, 'admin@'))
		{
			$req = $db->prepare("SELECT id, activated, mail FROM pe_adminaccounts WHERE nickname = :name AND password = :pwd");
		}
		else
		{
			$req = $db->prepare("SELECT * FROM `$this->_sessionClassroom` WHERE nickname = :name AND password = :pwd");
		}
		// Try au cas où la classe en entrée n'existerait pas
		try 
		{
			$req->bindValue(':name', $this->_sessionNickname, PDO::PARAM_STR);
			$req->bindValue(':pwd', $this->_sessionPassword, PDO::PARAM_STR);
			$req->execute();
			$resultReq = $req->fetchAll();
		}
		catch (Exception $e)
		{
		    $resultReq = false;
		}
		// Les données de connexion sont bonnes et le compte a été activé
		if ($resultReq != false && $resultReq[0]["activated"] == 1)
		{
			$_SESSION['id'] = $resultReq[0]["id"];
		    if (strstr($this->_sessionNickname, 'admin@'))
		    {
		    	// Admin
		    	// Si une demande de reinitialisation du pwd a été faite mais qu'on se connecte entre temps => expiration du lien de reinitialisation
		    	$req = $db->prepare("UPDATE pe_adminaccounts SET pwdreset = 0 WHERE id = :idAccount");
				$req->bindValue(':idAccount', $resultReq[0]['id'], PDO::PARAM_INT);
				$req->execute();
				$req->closeCursor();
				$req = NULL;
		    	return 'admin';
		    }
		    else
		    {
		    	// Student
		    	$req->closeCursor();
				$req = NULL;
		    	return 'student';
		    }
		}
		// Le compte n'a pas encore été activé OU les données sont mauvaises
		else
		{
			if ($this->_sessionPassword != '' && $this->_sessionNickname != '')
			{
				// Compte pas encore activé
				if ($resultReq != false && $resultReq[0]["activated"] != 1)
				{
					$_SESSION['smsAlert']['default'] = "<span class='smsAlert'>Vous n'avez pas activé votre compte suite à votre inscription. Un nouveau lien d'activation vient de vous être envoyé par mail!</span>";
					$sendActiveCode = new SendMail();
					$sendActiveCode->activeAccount($resultReq[0]["mail"], $resultReq[0]["activated"]);
					$return = 'needActivation';
				}
				// Données sont mauvaises
				else if ($resultReq == false)
				{
					$_SESSION['smsAlert']['default'] = "<span class='smsAlert'>Certaines des informations que vous nous avez transmises sont incorrectes!</span>";
					$return = 'wrong';
				}
				$_SESSION['nickname'] = '';
				$_SESSION['classroom'] = '';
				$_SESSION['password'] = '';
				return $return;
			}
		}  
	}
}

// ENREGISTREMENT!
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
		if ($name == '_nickname')
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
		try
		{
		    $db = connectDB();
		    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} 
		catch (Exception $e)
		{
		    die('Erreur : ' . $e->getMessage());
		}
		// Login or mail already exist ?
		if ($this->_classroom == false)
		{
			$req = $db->prepare("SELECT nickname, mail FROM pe_adminaccounts WHERE mail = :email OR nickname = :name");
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
		$code = generateCode(20);
		$activationCode = hash('sha256', $code);
		if ($this->_classroom == false)
		{
			$req = $db->prepare("INSERT INTO pe_adminaccounts (nickname, mail, password, activated) VALUES (:name, :email, :pwd, :activeCode)");
			$req->bindValue(':email', $this->_mail, PDO::PARAM_STR);
			$req->bindValue(':activeCode', $activationCode, PDO::PARAM_STR);
		}
		else
		{
			$req = $db->prepare("INSERT INTO `$this->_classroom` (nickname, password) VALUES (:name, :pwd)");
		}
		$req->bindValue(':name', $this->_nickname, PDO::PARAM_STR);
		$req->bindValue(':pwd', $this->_pwd, PDO::PARAM_STR);

		$req->execute();
		$req->closeCursor();
		$req = NULL;

		if ($this->_classroom == false)
		{
			$_SESSION['smsAlert']['default'] = "<span class='smsInfo'>Vous venez de recevoir un lien de validation dans votre boîte mail! Votre nom d'utilisateur est le suivant ".$this->_nickname."!</span>";
			$sendActiveCode = new SendMail();
			$sendActiveCode->activeAccount($this->_mail, $activationCode);
		}
	}
}

class checkCode
{
	static function start($code, $type)
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
		if ($type == 'resetpwd')
		{
			$req = $db->prepare("SELECT id FROM pe_adminaccounts WHERE pwdreset = :code");		
		}
		else if ($type == 'activate')
		{
			$req = $db->prepare("SELECT id FROM pe_adminaccounts WHERE activated = :code");
		}
		$req->bindValue(':code', $code, PDO::PARAM_INT);
		$req->execute();
		$resultReq = $req->fetchAll();
		// Le code permettant de changer de pwd est-il valide ?
		if ($type == 'resetpwd')
		{
			if (isset($resultReq[0]['id']) && !empty($resultReq[0]['id']))
			{
				return $resultReq[0]['id'];		
			}
			else
			{
				return false;
				$_SESSION['smsAlert']['default'] = "<span class='smsAlert'>Le lien a expiré!</span>";
			}
		}
		// Le code d'activation du compte est-il valide ?
		else if ($type == 'activate')
		{
			if (isset($resultReq[0]['id']) && !empty($resultReq[0]['id']))
			{
				$req = $db->prepare("UPDATE pe_adminaccounts SET activated = 1 WHERE id = :idAccount");
				$req->bindValue(':idAccount', $resultReq[0]['id'], PDO::PARAM_INT);
				$req->execute();
				$_SESSION['smsAlert']['default'] = "<span class='smsInfo'>Votre compte vient d'être activé</span>";
			}
			else
			{
				$_SESSION['smsAlert']['default'] = "<span class='smsAlert'>Le lien a expiré!</span>";
			}
		}
		$req->closeCursor();
		$req = NULL;
	}
}

class Recover
{

	static function start($email, $type)
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

		// Envoyer un lien de réinitialisation pour le mot de passe si le nom d'utilisateur et l'adresse mail correspondent
		if ($type == 'pwd')
		{
			$req = $db->prepare("SELECT id FROM pe_adminaccounts WHERE nickname = :username AND mail = :email");
			$req->bindValue(':email', $email, PDO::PARAM_STR);
			$req->bindValue(':username', $_SESSION['nickname'], PDO::PARAM_STR);
			$req->execute();
			$resultReq = $req->fetchAll();
			if (isset($resultReq[0]['id']) && !empty($resultReq[0]['id']))
			{
				$code = generateCode(20);
				$resetLink = hash('sha256', $code);
				$id = $resultReq[0]['id'];
				$req = $db->prepare("UPDATE pe_adminaccounts SET pwdreset = :resetLink WHERE id = :idAccount");
				$req->bindValue(':idAccount', $id, PDO::PARAM_INT);
				$req->bindValue(':resetLink', $resetLink, PDO::PARAM_STR);
				$req->execute();
				$sendLogin = new SendMail();
				$sendLogin->resetPwd($email, $resetLink);
			}
			$_SESSION['smsAlert']['default'] = "<p class='smsInfo'>Si votre nom d'utilisateur et votre adresse email correspondent à un compte, un lien pour reinitialiser votre mot de passe vient de vous être envoyé!</p>";
		}
		// Envoyer le nom d'utilisateur si celui-ci existe
		if ($type == 'nickname')
		{
			$req = $db->prepare("SELECT nickname FROM pe_adminaccounts WHERE mail = :email");
			$req->bindValue(':email', $email, PDO::PARAM_STR);
			$req->execute();
			$resultReq = $req->fetchAll();
			if (isset($resultReq[0]['nickname']) && !empty($resultReq[0]['nickname']))
			{
				$sendLogin = new SendMail();
				$sendLogin->callNickname($email, $resultReq[0]['nickname']);
			}
			$_SESSION['smsAlert']['default'] = "<p class='smsInfo'>Si l'adresse email que vous avez entrée est liée à un compte, votre nom d'utilisateur vient de vous être envoyé!</p>";
		}
		$req->closeCursor();
		$req = NULL;
	}
}

class UpdatePassword
{
	static function start($pwd, $id)
	{
		$reset = "0";
		try
		{
		    $db = connectDB();
		    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} 
		catch (Exception $e)
		{
		    die('Erreur : ' . $e->getMessage());
		}
		$req = $db->prepare("UPDATE pe_adminaccounts SET password = :pwd, pwdreset = :reset WHERE id = :idAccount");
		$req->bindValue(':pwd', $pwd, PDO::PARAM_STR);
		$req->bindValue(':idAccount', $id, PDO::PARAM_INT);
		$req->bindValue(':reset', $reset, PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
		$req = NULL;
		$_SESSION['smsAlert']['default'] = "<span class='smsInfo'>Votre mot de passe a bien été modifié!</span>";
	}
}

// MAILS!
class SendMail
{
	public function activeAccount($mail, $code)
	{
		$_sujet = "Lien d'Activation du Compte!";
		$_message = '<p>Bienvenue! Pour activer votre compte veuillez cliquer sur le lien suivant.
		<a href="https://cvm.one/test/index.php?action=activate&code='.$code.'">https://cvm.one/test/index.php?action=activate&code='.$code.'</a></p>';
		$_destinataire = $mail;

		$_headers = "From: \"Plateforme Éducative\"<robot@cvm.one>\n";
		//$_headers .= "Reply-To: admin@cvm.one\n";
		$_headers .= "Content-Type: text/html; charset=\"ISO-8859-1\"\n";
		$_headers .= "Content-Transfer-Encoding: 8bit";
		$_sendMail = mail($_destinataire, $_sujet, $_message, $_headers);
	}
	public function resetPwd($mail, $rstpwd)
	{
		$_sujet = "Réinitialisation du Mot de Passe";
		$_message = '<p>Bienvenue! Cliquer sur le lien suivant pour reinitialiser votre password.
		<a href="https://cvm.one/test/index.php?action=resetpwd&code='.$rstpwd.'">https://cvm.one/test/index.php?action=resetpwd&code='.$rstpwd.'</a></p>';
		$_destinataire = $mail;

		$_headers = "From: \"Plateforme Éducative\"<robot@cvm.one>\n";
		//$_headers .= "Reply-To: admin@cvm.one\n";
		$_headers .= "Content-Type: text/html; charset=\"ISO-8859-1\"\n";
		$_headers .= "Content-Transfer-Encoding: 8bit";
		$_sendMail = mail($_destinataire, $_sujet, $_message, $_headers);
	}
	public function callNickname($mail, $nickname)
	{
		$_sujet = "Votre Nom d'Utilisateur";
		$_message = "<p>Bienvenue! Votre nom d'utilisateur est le suivant: ".$nickname."!</p>";
		$_destinataire = $mail;

		$_headers = "From: \"Plateforme Éducative\"<robot@cvm.one>\n";
		//$_headers .= "Reply-To: admin@cvm.one\n";
		$_headers .= "Content-Type: text/html; charset=\"ISO-8859-1\"\n";
		$_headers .= "Content-Transfer-Encoding: 8bit";
		$_sendMail = mail($_destinataire, $_sujet, $_message, $_headers);
	}
}