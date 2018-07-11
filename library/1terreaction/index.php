<?php
require('./model/model.php');
Authentification::startSession();

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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!--<meta name="description" content="">-->
    <title>1TerreAction</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans|Orbitron" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="justify-content: center; align-items: center;">
    <h1 style="font-family: 'Orbitron', sans-serif; font-size: 1.5rem;">1TerreAction</h1>
    <h2 style="font-family: 'Open Sans', sans-serif; font-size: 1rem;">En cours de développement!</h2>
</body>
</html>