<?php

require('./controller/controller_admin.php');

// ROUTER!
// SESSION
function checkSession()
{
	$auth = new Authentification;
	$sessionResult = $auth->checkSession();

	if ($sessionResult != null)
	{
		if ($sessionResult == 'student')
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
$_SESSION['smsAlert'] = !isset($_SESSION['smsAlert']) ? array() : $_SESSION['smsAlert'];
$_SESSION['smsAlert']['default'] = !isset($_SESSION['smsAlert']['default']) ? '' : $_SESSION['smsAlert']['default'];

// CLASSROOMS MANAGEMENT
if (isset($_GET['action']))
{
	// Classrooms management
	if ($_GET['action'] == 'manageClassrooms')
	{
		loadManageClassrooms();
	}
	// Students management
	else if ($_GET['action'] == 'manageStudents')
	{
		loadManageStudents();
	}
	// Student management
	else if ($_GET['action'] == 'manageModifyStudents')
	{
		loadManageModifyStudents();
	}
	else
	{
		loadManageClassrooms();
	}
}
else
{
	loadManageClassrooms();
}

$_SESSION['smsAlert']['default'] = "";
$_SESSION['smsAlert']['nickname'] = "";
$_SESSION['smsAlert']['email'] = "";
$_SESSION['smsAlert']['password'] = "";
$_SESSION['smsAlert']['password2'] = "";