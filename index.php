<?php
session_cache_limiter('private_no_expire, must-revalidate');
session_start();


require('./controller/controller.php');
// Si le message d'alerte n'existe pas, on le crée vide.
$_SESSION['smsAlert'] = !isset($_SESSION['smsAlert']) || empty($_SESSION['smsAlert']) ? '' : $_SESSION['smsAlert'];

// OUTILS!
function filterInputs($input, $regEx)
{
	$filteredInput = htmlspecialchars($input);
	$validRegEx = "/^[".$regEx."]*$/i";

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
		return FALSE;
	}
}

// ROUTEUR!
if (isset($_POST))
{
	//LOGIN
	if (isset($_POST['nickname']))
	{
		$filteredInput = filterInputs($_POST['nickname'], 'a-zA-Z0-9@');
		if ($filteredInput)
		{
			$_SESSION['nickname'] = $filteredInput;
			$_SESSION['classroom'] = '';
			// Teacher
			if ($test = strstr($_SESSION['nickname'], 'admin@'))
			{
				pwd();
			}
			// Student
			else
			{
				classroom();
			}
		}
		else
		{
			home();
		}
	}
	//CLASSROOM (Student Only)
	else if (isset($_POST['classroom']) && isset($_SESSION['nickname']))
	{
		$filteredInput = filterInputs($_POST['classroom'], 'a-zA-Z0-9À-Ö ._-');
		if ($filteredInput)
		{
			$_SESSION['classroom'] = $filteredInput;
			pwd();
		}
		else 
		{
			classroom();
		}
	}
	//PASSWORD
	else if (isset($_POST['password']) && isset($_SESSION['nickname']))
	{
		$filteredInput = filterInputs($_POST['password'], 'a-zA-Z0-9À-Ö .-_@#');
		if ($filteredInput)
		{
			if (!isset($_SESSION['classroom']))
			{
				// Teacher
				$_SESSION['password'] = htmlspecialchars($_POST['password']);
			}
			else
			{
				// Student
				$_SESSION['password'] = htmlspecialchars($_POST['password']);
			}
			//checkAccountDB();	
		}
		else
		{
			pwd();
		}
	}
	else
	{
		home();
	}
}
else
{
	home();
}

$_SESSION['smsAlert'] = '';