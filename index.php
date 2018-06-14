<?php

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
		if (strstr($regEx, 'a-z') && strstr($regEx, 'A-Z'))
		{
			$validChar .= " de lettres,";
			$regEx = cutRegex("a-z", $regEx);
			$regEx = cutRegex("A-Z", $regEx);
		}
		else if (strstr($regEx, 'a-z') && !strstr($regEx, 'A-Z'))
		{
			$validChar .= " de lettres minuscules,";
			$regEx = cutRegex("a-z", $regEx);
		}
		else if (strstr($regEx, 'A-Z') && !strstr($regEx, 'a-z'))
		{
			$validChar .= " de lettres majuscules,";
			$regEx = cutRegex("A-Z", $regEx);
		}

		if (strstr($regEx, 'À-Ö'))
		{
			$validChar .= " de lettres accentuées,";
			$regEx = cutRegex("À-Ö", $regEx);
		}
		else if (strstr($regEx, 'à-ö') && !strstr($regEx, 'À-Ö'))
		{
			$validChar .= " de lettres minuscules pouvant être accentuées,";
			$regEx = cutRegex("à-ö", $regEx);
		}
		else if (strstr($regEx, 'À-Ö') && !strstr($regEx, 'à-ö'))
		{
			$validChar .= " de lettres majuscules pouvant être accentuées,";
			$regEx = cutRegex("À-Ö", $regEx);
		}

		if (strstr($regEx, '0-9'))
		{
			$validChar .= " de chiffres,";
			$regEx = cutRegex("0-9", $regEx);
		}

		// Pour une meilleure formulation, remplacer la dernière virgule du message par "et"
		for ($i = strlen($validChar) - 1, $lastComma = TRUE; $i >= 0; $i--)
		{
			if ($validChar[$i] == "," && $lastComma == TRUE)
			{
				$validChar = substr_replace($validChar, "!", $i, 1);
				$lastComma = FALSE;
			}
			else if ($validChar[$i] == "," && $lastComma == FALSE)
			{
				$validChar = substr_replace($validChar, " et", $i, 1);
				break;
			}
		}

		$_SESSION['smsAlert'] = "Ce champ ne peut être composé que".$validChar;
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
			//Teacher
			if ($test = strstr($_SESSION['nickname'], 'admin@'))
			{
				$_SESSION['classroom'] = '';
				pwd();
			}
			//Student
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
	else if (isset($_POST['classroom']))
	{
		$filteredInput = filterInputs($_POST['classroom'], 'a-zA-Z0-9À-ÖØ-öø-ÿœŒ .-');
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
	else if (isset($_POST['password']))
	{
		$_SESSION['password'] = htmlspecialchars($_POST['password']);
		//checkAccountDB();	
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