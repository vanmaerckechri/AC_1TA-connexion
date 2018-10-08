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
		//$db = new PDO('mysql:host=localhost; dbname=pe_connexion; charset=utf8', "phpmyadmin", "1234");
		$db = new PDO('mysql:host=localhost; dbname=pe_connexion; charset=utf8', "root", "");
		return $db;
	}
	function getSecretCaptchaKey()
	{
		return "";
	}
}


// FILTRES!

function checkInput($input, $field, $smsTitle)
{
	$regex;
	$smsAlert = "";
	switch ($field)
	{
	    case "nickname":
	    	$regex = "/^[a-z@\d\s]{3,30}$/i";
	    	$smsAlert = "<span class='smsAlert'>Le nom d'utilisateur doit être composé de 3 à 30 caractères! Hormis le \"@\", les caractères spéciaux ne sont pas acceptés!</span>";
	    	break;
	    case "password":
	    	$regex = "/^.{5,30}$/";
	    	$smsAlert = "<span class='smsAlert'>Le mot de passe doit être composé de 5 à 30 caractères!</span>";
	    	break;
	    case "classroom":
	    	$regex = "/^.{5,30}$/";
	    	$smsAlert = "<span class='smsAlert'>Le nom de la classe doit être composé de 5 à 30 caractères!</span>";
	    	break;
	    case "loginRecord":
	    	$regex = "/^[a-z\d\s]{3,24}$/i";
	    	$smsAlert = "<span class='smsAlert'>Le nom d'utilisateur doit être composé de 3 à 30 caractères! Les caractères spéciaux ne sont pas acceptés!</span>";
	    	break;
	    default:
			return false;
	}
	if (preg_match($regex, $input) === 1)
	{
		return $input;
	}
	else
	{
		$_SESSION['smsAlert'][$smsTitle] = $smsAlert;
		return false;
	}
}

function generateCode($codeLength = 10)
{
    $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charLength = strlen($char);
    $randCode = '';
    for ($i = 0; $i < $codeLength; $i++)
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
		$db = connectDb();
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
						$_SESSION['nickname'] = "";
						$_SESSION['password'] = "";
						$_SESSION['classroom'] = "";
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
					$req = $db->prepare("SELECT id, id_classroom FROM pe_students WHERE nickname = :name AND password = :pwd AND id_classroom = :idcr");
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
						$_SESSION['id_classroom'] = $resultReq[0]["id_classroom"];
						return 'student';
					}
				}
				$_SESSION['smsAlert']['default'] = "<span class='smsAlert'>Certaines des informations que vous nous avez transmises sont incorrectes!</span>";
				$_SESSION['nickname'] = "";
				$_SESSION['password'] = "";
				$_SESSION['classroom'] = "";
				return 'wrong';
			}
		}
	}

	/*public function checkAdminNickname()
	{
		if (strpos($_SESSION["nickname"], "admin@") === 0)
		{
			return true;
		}
	}*/
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

		$db = connectDB();

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
		$db = connectDB();

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
			$_SESSION['smsAlert']['default'] = "<p class='smsInfo'>Si votre nom d'utilisateur et votre adresse email correspondent à un compte, un lien pour reinitialiser votre mot de passe vous sera envoyé dans quelques instants!</p>";
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
			$_SESSION['smsAlert']['default'] = "<p class='smsInfo'>Si l'adresse email que vous avez entrée est liée à un compte, votre nom d'utilisateur vous sera envoyé dans quelques instants!</p>";
		}
		$req->closeCursor();
		$req = NULL;
	}
}

class ModifyAdminAccount
{

	static function genCode($length)
	{
		$code = "";
		for ($i = $length - 1; $i >= 0; $i--)
		{
			$randResult;
			$letterOrNum = rand(1, 2);
			if ($letterOrNum == 1)
			{
				$randResult = chr(rand(65, 90));
			}
			else
			{
				$randResult = rand(0, 9);
			}
			$code .= $randResult;
		}
		return $code;
	}

	static function UpdatePassword($pwd, $id)
	{
		$reset = "0";
		$db = connectDb();
		$req = $db->prepare("UPDATE pe_adminaccounts SET password = :pwd, pwdreset = :reset WHERE id = :idAccount");
		$req->bindValue(':pwd', $pwd, PDO::PARAM_STR);
		$req->bindValue(':idAccount', $id, PDO::PARAM_INT);
		$req->bindValue(':reset', $reset, PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
		$req = NULL;
		$_SESSION['smsAlert']['default'] = "<span class='smsInfo'>Votre mot de passe a bien été modifié!</span>";
	}

	static function getMail()
	{

		$db = connectDb();
		$req = $db->prepare("SELECT mail FROM pe_adminaccounts WHERE id = :id AND nickname = :nick AND password = :pwd");
		$req->bindParam(':id', $_SESSION["id"], PDO::PARAM_INT);
		$req->bindParam(':nick', $_SESSION["nickname"], PDO::PARAM_STR);
		$req->bindParam(':pwd', $_SESSION["password"], PDO::PARAM_STR);
		$req->execute();
		$email = $req->fetch(PDO::FETCH_NUM);
		$req->closeCursor();
		$req = NULL;

		if (!empty($email) && isset($email[0]))
		{
			return $email[0];
		}
	}

	static function updateNewMailCode($newMailCode)
	{
		if (ctype_alnum ($newMailCode) == true && strlen($newMailCode) == 8)
		{
			$db = connectDb();
			$req = $db->prepare("UPDATE pe_adminaccounts SET newMailCode = :newMail WHERE id = :id AND nickname = :nick AND password = :pwd");
			$req->bindParam(':id', $_SESSION["id"], PDO::PARAM_INT);
			$req->bindParam(':nick', $_SESSION["nickname"], PDO::PARAM_STR);
			$req->bindParam(':pwd', $_SESSION["password"], PDO::PARAM_STR);
			$req->bindParam(':newMail', $newMailCode, PDO::PARAM_STR);
			$req->execute();
			$req->closeCursor();
			$req = NULL;
		}
	}

	static function updateDeleteAccountCode($code)
	{
		if (ctype_alnum ($code) == true && strlen($code) == 8)
		{
			$db = connectDb();
			$req = $db->prepare("UPDATE pe_adminaccounts SET deleteAccountCode = :deleteCode WHERE id = :id AND nickname = :nick AND password = :pwd");
			$req->bindParam(':id', $_SESSION["id"], PDO::PARAM_INT);
			$req->bindParam(':nick', $_SESSION["nickname"], PDO::PARAM_STR);
			$req->bindParam(':pwd', $_SESSION["password"], PDO::PARAM_STR);
			$req->bindParam(':deleteCode', $code, PDO::PARAM_STR);
			$req->execute();
			$req->closeCursor();
			$req = NULL;
		}
	}

	static function updateNewMail($newMailCode, $newMail)
	{
		$db = connectDb();
		$req = $db->prepare("UPDATE pe_adminaccounts SET mail = :mail, newMailCode = :resetNewMail WHERE id = :id AND nickname = :nick AND password = :pwd AND newMailCode = :newMailCode");
		$req->bindParam(':id', $_SESSION["id"], PDO::PARAM_INT);
		$req->bindParam(':nick', $_SESSION["nickname"], PDO::PARAM_STR);
		$req->bindParam(':pwd', $_SESSION["password"], PDO::PARAM_STR);
		$req->bindParam(':newMailCode', $newMailCode, PDO::PARAM_STR);
		$req->bindValue(':resetNewMail', 0, PDO::PARAM_INT);
		$req->bindParam(':mail', $newMail, PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
		$req = NULL;
		exit;
	}

	static function deleteAdAccount($code)
	{
		exit;
	}
}

// MAILS!
class SendMail
{
	public function default($mail, $sujet, $message)
	{
		$_headers = "From: \"Plateforme Éducative\"<robot@cvm.one>\n";
		$_headers .= "Content-Type: text/html; charset=\"UTF-8\"\n";
		$_headers .= "Content-Transfer-Encoding: 8bit";

		$_sendMail = mail($mail, $sujet, $message, $_headers);
	}
	public function activeAccount($mail, $code)
	{
		$_sujet = "Lien d'Activation du Compte!";
		$_message = '<p>Bienvenue! Pour activer votre compte veuillez cliquer sur le lien suivant.
		<a href="https://cvm.one/test/index.php?action=activate&code='.$code.'">https://cvm.one/test/index.php?action=activate&code='.$code.'</a></p>';
		$_destinataire = $mail;

		$_headers = "From: \"Plateforme Éducative\"<robot@cvm.one>\n";
		//$_headers .= "Reply-To: admin@cvm.one\n";
		$_headers .= "Content-Type: text/html; charset=\"UTF-8\"\n";
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
		$_headers .= "Content-Type: text/html; charset=\"UTF-8\"\n";
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
		$_headers .= "Content-Type: text/html; charset=\"UTF-8\"\n";
		$_headers .= "Content-Transfer-Encoding: 8bit";
		$_sendMail = mail($_destinataire, $_sujet, $_message, $_headers);
	}
}

// Library

class Library
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
		// admin
		if (strstr($_SESSION['nickname'], 'admin@'))
		{
			$req = $db->prepare("SELECT * FROM pe_library");
			$req->execute();
			$resultReq = $req->fetchAll();
			$req->closeCursor();
			$req = NULL;
			return $resultReq;
		}
		// student
		else
		{
			$req = $db->prepare("SELECT id_classroom FROM pe_students WHERE id = :idSt AND nickname = :nickname AND password = :password");
			$req->bindValue(':idSt', $_SESSION['id'], PDO::PARAM_INT);
			$req->bindValue(':nickname', $_SESSION['nickname'], PDO::PARAM_STR);
			$req->bindValue(':password', $_SESSION['password'], PDO::PARAM_STR);
			$req->execute();
			$resultReq = $req->fetchAll(PDO::FETCH_COLUMN, 0);
			if (!empty($resultReq))
			{
				$req = $db->prepare("SELECT id_library FROM pe_rel_cr_library WHERE id_classroom = :idCr");
				$req->bindValue(':idCr', $resultReq[0], PDO::PARAM_INT);
				$req->execute();
				$resultReq = $req->fetchAll(PDO::FETCH_COLUMN, 0);
				if (!empty($resultReq))
				{
					$libraryList = [];
					$req = $db->prepare("SELECT * FROM pe_library WHERE id = :idLib");
					foreach ($resultReq as $idLib)
					{
						$idLib = intval($idLib);
						$req->bindValue(':idLib', $idLib, PDO::PARAM_INT);
						$req->execute();
					    array_push($libraryList, $req->fetch());
					}
					$req->closeCursor();
					$req = NULL;
					return $libraryList;
				}
				else
				{
					$req->closeCursor();
					$req = NULL;
					$_SESSION['smsAlert']['default'] = "<span class='smsInfo'>Aucune application n'a été activée pour le moment!</span>";
					return array();
				}
			}
			else
			{
				$req->closeCursor();
				$req = NULL;
				return array();
			}
		}
	}
}