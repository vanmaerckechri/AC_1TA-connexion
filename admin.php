<?php

require('./controller/ad_controller.php');

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
	else
	{
		// Informations de connexion incorrectes
		header('Location: ./index.php');
		exit;
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
	// Classroom management
	else if ($_GET['action'] == 'manageThisClassroom')
	{
		loadManageThisClassroom();
	}
	// Create new classroom
	else if ($_GET['action'] == 'createClassroom')
	{
		createClassrooms();		
	}
	// Rename classroom
	else if ($_GET['action'] == 'renameClassroom')
	{
		renameClassroom();	
	}
	// Delete classrooms
	else if ($_GET['action'] == 'deleteClassrooms')
	{
		deleteClassrooms();
	}
	// Create new students
	else if ($_GET['action'] == 'addStudents')
	{
		createStudents();		
	}
	// Student management
	else if ($_GET['action'] == 'editStudent')
	{
		editStudent();
	}
	// Delete students
	else if ($_GET['action'] == 'deleteStudents')
	{
		deleteStudents();
	}
	else if ($_GET['action'] == 'disco')
	{
		disconnect();
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