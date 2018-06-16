<?php

require('./controller/controller.php');

// ACTIVATE SESSION!
Authentification::startSession();

// Check Session
function checkSession()
{
	$auth = new Authentification;
	$sessionResult = $auth->checkSession();

	if ($sessionResult != null)
	{
	    if ($sessionResult == 'admin')
		{
			// Admin connexion
			header('Location: ./admin/index.php');
			exit;  		
		}
		else if ($sessionResult == 'student')
		{
			// Student connexion
			header('Location: ./platform/index.php');	  
			exit;  		
		}
		else if ($sessionResult == 'wrong')
		{
			// Informations de connexion incorrectes
			header('Location: ./index.php');
			exit;	    		
		}
	}
}
checkSession();
//$_SESSION = array();

// Si le message d'alerte n'existe pas, on le crée vide.
$_SESSION['smsAlert'] = !isset($_SESSION['smsAlert']) || empty($_SESSION['smsAlert']) ? '' : $_SESSION['smsAlert'];

// ROUTEUR!
if (isset($_POST))
{
	// Login
	if (isset($_POST['nickname']))
	{
		$filteredInput = filterInputs($_POST['nickname'], 'a-zA-Z0-9@', 4, 20);
		if ($filteredInput)
		{
			$_SESSION['nickname'] = $filteredInput;
			$_SESSION['classroom'] = '';
			// Teacher
			if (strstr($_SESSION['nickname'], 'admin@'))
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
	//Classroom (Student Only)
	else if (isset($_POST['classroom']) && isset($_SESSION['nickname']))
	{
		$filteredInput = filterInputs($_POST['classroom'], 'a-zA-Z0-9À-Ö ._-', 0, 30);
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
	// Password
	else if (isset($_POST['password']) && isset($_SESSION['nickname']))
	{
		$filteredInput = filterInputs($_POST['password'], 'a-zA-Z0-9À-Ö ._@', 0, 30);
		if ($filteredInput)
		{
			if (strstr($_SESSION['nickname'], 'admin@'))
			{
				// Teacher
				$_SESSION['password'] = $filteredInput;
				checkSession();
				pwd();
			}
			else
			{
				// Student
				$_SESSION['password'] = $filteredInput;
				checkSession();
				pwd();
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