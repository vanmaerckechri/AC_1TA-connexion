<?php

function filterInputs($input, $regEx, $minLength, $maxLength)
{
	$filteredInput = htmlspecialchars($input, ENT_NOQUOTES);
	$validRegEx = "/^[".$regEx."]*$/i";
	if (strlen($filteredInput) >= $minLength && strlen($filteredInput) <= $maxLength)
	{
		if (preg_match($validRegEx, $filteredInput))
		{
			return $filteredInput;
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
	private $sessionLogin;
	private $sessionClasse;
	private $sessionPwd;
	private $sessionStatus;

    public function __construct()
    {
    	$this->startSession();
    	$this->hydrate();
    }
	private function hydrate()
  	{
  		if (isset($_SESSION['login']))
  		{
      		$this->setSessionLogin($_SESSION['login']);
  		}
  		else
		{
			$this->_sessionLogin = '';
		}
  		if (isset($_SESSION['password']))
  		{
      		$this->setSessionPwd($_SESSION['password']);
  		}
  		else
		{
			$this->_sessionPwd = '';
		}
		$this->setSessionSms();
  	}
	private function setSessionLogin($sessionLogin)
	{
		if (is_string($sessionLogin))
		{
			$this->_sessionLogin = htmlspecialchars($sessionLogin);
		}
	}
	private function setSessionPwd($sessionPwd)
	{
		if (is_string($sessionPwd))
		{
			$this->_sessionPwd = htmlspecialchars($sessionPwd);
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
            header('Location: index.php?sms=Vous avez été déconnecté pour des raisons de sécurité!');
            $_SESSION = array();
            exit;
        }
    }
    private function checkSession()
	{

	}
}