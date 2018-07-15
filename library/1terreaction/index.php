<?php
require('./controller/controller.php');

// -- ROUTEUR --
// SESSION
function checkSession()
{
	$auth = new Authentification;
	$sessionResult = $auth->checkSession();
	if ($sessionResult == null || $sessionResult != 'student')
	{
		if ($sessionResult != 'student')
		{
			// Incorrect login information
			header('Location: ../../index.php');
			exit;	    		
		}
	}
	return $sessionResult;
}
$sessionResult = checkSession();

// -- VIEW --
if (isset($_GET['action']))
{
	// Planets management
	if ($_GET['action'] == 'main')
	{
		loadMainView();
	}
	else
	{
		loadMainView();
	}
}
else
{
	loadMainView();
}
?>