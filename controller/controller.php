<?php

require('./model/model.php');

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
$_SESSION['smsAlert'] = !isset($_SESSION['smsAlert']) ? array() : $_SESSION['smsAlert'];
$_SESSION['smsAlert']['default'] = !isset($_SESSION['smsAlert']['default']) ? '' : $_SESSION['smsAlert']['default'];


//VIEWS!
function loadHomeView()
{
    require('./view/loginView.php');
}
function loadClassroomView()
{
    require('./view/classroomView.php');
}
function loadPwdView()
{
    require('./view/pwdView.php');
}
function loadNicknameRecoveryView()
{
	require('./view/nnRecoveryView.php');
}
function loadPwdRecoveryView()
{
	require('./view/passwordRecoveryView.php');
}
function loadCreateAdminAccountView()
{
	$keepNickname = '""';
	$keepEmail = '""';
	// Check if all inputs are corrects
	if (isset($_POST['createAdminAccountNickname']) && isset($_POST['createAdminAccountEmail']) && isset($_POST['createAdminAccountPassword']) && isset($_POST['createAdminAccountPassword2']))
	{
		$filteredNickname = filterInputs($_POST['createAdminAccountNickname'], 'a-zA-Z0-9', 4, 20, 'nickname');
		$filteredPwd = filterInputs($_POST['createAdminAccountPassword'], 'a-zA-Z0-9À-Ö ._@', 0, 30, 'password');
		$filteredPwd2 = filterInputs($_POST['createAdminAccountPassword2'], 'a-zA-Z0-9À-Ö ._@', 0, 30, false);
		$is_email = filter_var($_POST['createAdminAccountEmail'], FILTER_VALIDATE_EMAIL);
		// If at least one of them is not correct
		if ($filteredNickname == false || $filteredPwd == false || $filteredPwd2 == false || $is_email == false || $filteredPwd != $filteredPwd2)
		{
			if ($filteredNickname != false)
			{
				$keepNickname = htmlspecialchars($filteredNickname, ENT_NOQUOTES);
			}

			if ($is_email == false)
			{
				$_SESSION['smsAlert']['email'] = "<span class='smsAlert'>Veuillez entrer une adresse email valide!</span>";
			}
			else
			{
				$keepEmail = htmlspecialchars($_POST['createAdminAccountEmail'], ENT_NOQUOTES);
			}

			if ($filteredPwd != $filteredPwd2)
			{
				$_SESSION['smsAlert']['password2'] = "<span class='smsAlert'>Les mots de passe ne correspondent pas!</span>";
			}
		}
		// Every inputs are corrects
		else
		{
			$keepNickname = htmlspecialchars($filteredNickname, ENT_NOQUOTES);
			$keepEmail = htmlspecialchars($_POST['createAdminAccountEmail'], ENT_NOQUOTES);
			$newAdminAccount = new RecordAccount($filteredNickname, $_POST['createAdminAccountEmail'], false, $filteredPwd);
			$alreadyExist = $newAdminAccount->getAlreadyExist();
			if ($alreadyExist != true)
			{
				header('Location: ./admin/index.php');
				exit;
			}
		}
	}
	require('./view/newAdminAccountView.php');
}