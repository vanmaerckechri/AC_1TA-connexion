<?php

require('./controller/controller.php');

// ROUTEUR!
// SESSION
// TEMPORAIRE POUR LES TESTS =>
// ---------------------------
$_SESSION['nickname'] = "admin@Chri";
$_SESSION['classroom'] = "";
$_SESSION['password'] = '9adfb0a6d03beb7141d8ec2708d6d9fef9259d12cd230d50f70fb221ae6cabd5';
$_SESSION['id'] = 31;


// ---------------------------
function checkSession()
{
	$auth = new Authentification;
	$sessionResult = $auth->checkSession();

	if ($sessionResult != null)
	{
	    if ($sessionResult == 'admin')
		{
			// Admin connexion
			header('Location: ./admin.php');
			exit;  		
		}
		else if ($sessionResult == 'student')
		{
			// Student connexion
			header('Location: ./platform/index.php');	  
			exit;  		
		}
	}
}
checkSession();
$_SESSION['smsAlert'] = !isset($_SESSION['smsAlert']) ? array() : $_SESSION['smsAlert'];
$_SESSION['smsAlert']['default'] = !isset($_SESSION['smsAlert']['default']) ? '' : $_SESSION['smsAlert']['default'];

// LOGIN
if (isset($_POST) && !isset($_GET['action']))
{
	// Nickname
	if (isset($_POST['nickname']))
	{
		$filteredInput = filterInputs($_POST['nickname'], 'a-zA-Z0-9@', 4, 30, 'default');
		if ($filteredInput)
		{
			$_SESSION['nickname'] = $filteredInput;
			$_SESSION['classroom'] = '';
			// Teacher
			if (strstr($_SESSION['nickname'], 'admin@'))
			{
				loadPwdView();
			}
			// Student
			else
			{
				loadClassroomView();
			}
		}
		else
		{
			loadHomeView();
		}
	}
	// Classroom (Student Only)
	else if (isset($_POST['classroom']) && isset($_SESSION['nickname']))
	{
		$filteredInput = filterInputs($_POST['classroom'], 'a-zA-Z0-9À-Ö ._-', 0, 30, 'default');
		if ($filteredInput)
		{
			$_SESSION['classroom'] = $filteredInput;
			loadPwdView();
		}
		else 
		{
			loadClassroomView();
		}
	}
	// Password
	else if (isset($_POST['password']) && isset($_SESSION['nickname']))
	{
		$filteredInput = filterInputs($_POST['password'], 'a-zA-Z0-9À-Ö ._@', 0, 30, 'default');
		if ($filteredInput)
		{
			loadPwdView($filteredInput, true);
		}
		else
		{
			loadPwdView();
		}
	}
	// New Password
	else if (isset($_POST['newPassword']) && isset($_POST['newPassword2']))
	{
		newPasswordView(true);
	}
	else
	{
		loadHomeView();
	}
}
// CREATE ADMIN ACCOUNT AND RECOVERIES
else
{
	// Create admin account
	if ($_GET['action'] == 'newadminaccount')
	{
		loadCreateAdminAccountView();
	}
	// Nickname recovery
	else if ($_GET['action'] == 'namerecovery')
	{
		loadNicknameRecoveryView();
	}
	// Password recovery
	else if ($_GET['action'] == 'passwordrecovery')
	{
		loadPwdRecoveryView();
	}
	// Check password recovery code
	else if ($_GET['action'] == 'resetpwd' && isset($_GET['code']) && !empty($_GET['code']))
	{
		checkCodeView('resetpwd');
	}
	// Check account activation code
	else if ($_GET['action'] == 'activate' && isset($_GET['code']) && !empty($_GET['code']))
	{
		checkCodeView('activate');
	}
	else
	{
		loadHomeView();
	}
}

$_SESSION['smsAlert']['default'] = "";
$_SESSION['smsAlert']['nickname'] = "";
$_SESSION['smsAlert']['email'] = "";
$_SESSION['smsAlert']['password'] = "";
$_SESSION['smsAlert']['password2'] = "";