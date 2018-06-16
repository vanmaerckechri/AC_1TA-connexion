<?php

require('./controller/controller.php');

// ACTIVATE SESSION!
Authentification::startSession();
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

// Si le message d'alerte n'existe pas, on le crée vide.
$_SESSION['smsAlert'] = !isset($_SESSION['smsAlert']) || empty($_SESSION['smsAlert']) ? '' : $_SESSION['smsAlert'];

// ROUTEUR!
if (isset($_POST) && !isset($_GET['action']))
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
	// Classroom (Student Only)
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
			$_SESSION['password'] = $filteredInput;
			checkSession();
			pwd();
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
	if ($_GET['action'] == 'namerecovery')
	{
		nicknameRecovery();
	}
	else if ($_GET['action'] == 'passwordrecovery')
	{
		passwordRecovery();
	}
	else if ($_GET['action'] == 'newadminaccount')
	{
		newAdminAccount();
	}
	else
	{
		home();
	}
}

$_SESSION['smsAlert'] = '';