<?php

$example = "admin@Chri1er";
$result = check($example, "login");
var_dump($result);

function checkInput($input, $field, $smsTitle)
{
	$input = htmlspecialchars($input, ENT_QUOTES);
	$regex;
	$smsAlert = "";
	switch ($field)
	{
	    case "login":
	    	$regex = "/^[a-z@\d_]{3,30}$/i";
	    	$smsAlert = "<span class='smsAlert'>Ce champ doit être composé de 3 à 30 caractères. Hormis le \"@\", les caractères spéciaux ne sont pas acceptés!</span>";
	    	break;
	    case "password":
	    	$regex = "/(.){8,30}/";
	    	$smsAlert = "<span class='smsAlert'>Ce champ doit être composé de 8 à 30 caractères!</span>";
	    	break;
	    case "classroom":
	    	$regex = "/(.){8,30}/";
	    	$smsAlert = "<span class='smsAlert'>Ce champ doit être composé de 8 à 30 caractères!</span>";
	    	break;
	    case "loginRecord":
	    	$regex = "/^[a-z\d_]{3,24}$/i";
	    	$smsAlert = "<span class='smsAlert'>Ce champ doit être composé de 3 à 30 caractères. Les caractères spéciaux ne sont pas acceptés!</span>";
	    	break;
	    default:
			return false;
	}
	if (preg_match($regex, $input) === 1)
	{
		return $input;
	}
	else
	{
		$_SESSION['smsAlert'][$smsTitle] = $smsAlert;
	}
}
