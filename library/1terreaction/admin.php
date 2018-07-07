<?php

require('./controller/ad_controller.php');

// ROUTER!
// SESSION
function checkSession()
{
	$auth = new Authentification;
	$sessionResult = $auth->checkSession();

	if ($sessionResult == null && $sessionResult != 'admin')
	{
		header('Location: ../../index.php');
		exit;
	}

}
checkSession();
$_SESSION['smsAlert'] = !isset($_SESSION['smsAlert']) ? array() : $_SESSION['smsAlert'];
$_SESSION['smsAlert']['default'] = !isset($_SESSION['smsAlert']['default']) ? '' : $_SESSION['smsAlert']['default'];

// -- VIEW --
if (isset($_GET['action']))
{
	// Classrooms management
	if ($_GET['action'] == 'main')
	{
		loadMainView();
	}
}
else
{
	loadMainView();
}

$_SESSION['smsAlert']['default'] = "";
$_SESSION['smsAlert']['nickname'] = "";
$_SESSION['smsAlert']['email'] = "";
$_SESSION['smsAlert']['password'] = "";
$_SESSION['smsAlert']['password2'] = "";