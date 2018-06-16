<?php
require('./controller/controller.php');

// ACTIVATE SESSION!
Authentification::startSession();

// Check Session
function checkSession()
{
	$auth = new Authentification;
	$sessionResult = $auth->checkSession();

	if ($sessionResult != 'student')
	{
		// Informations de connexion incorrectes
		header('Location: ../index.php');
		exit;	    		
	}
}
checkSession();

// Si le message d'alerte n'existe pas, on le crée vide.
$_SESSION['smsAlert'] = !isset($_SESSION['smsAlert']) || empty($_SESSION['smsAlert']) ? '' : $_SESSION['smsAlert'];

// ROUTEUR!

home();

$_SESSION['smsAlert'] = '';