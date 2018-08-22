<?php

require('./controller/controller.php');

// ROUTEUR!
// SESSION

//TEMP POUR TESTS
$_SESSION['nickname'] = "admin@Chri";
$_SESSION['password'] = 'f3029a66c61b61b41b428963a2fc134154a5383096c776f3b4064733c5463d90';
$_SESSION['classroom'] = "";
$_SESSION['id'] = 31;

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
			header('Location: ./library.php');	  
			exit;  		
		}
	}
}
checkSession();
$_SESSION['smsAlert'] = !isset($_SESSION['smsAlert']) ? array() : $_SESSION['smsAlert'];
$_SESSION['smsAlert']['default'] = !isset($_SESSION['smsAlert']['default']) ? '' : $_SESSION['smsAlert']['default'];

// -- LOGIN --
if (isset($_POST) && !isset($_GET['action']))
{
	// Nickname
	if (isset($_POST['nickname']))
	{
		$filteredInput = checkInput($_POST['nickname'], 'nickname', 'default');;
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
		$filteredInput = checkInput($_POST['classroom'], 'classroom', 'default');
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
		$filteredInput = checkInput($_POST['password'], 'password', 'default');
		if ($filteredInput)
		{
			loadPwdView($filteredInput, true);
		}
		else
		{
			loadPwdView();
		}
	}
// -- CREATE ADMIN ACCOUNT AND RECOVERIES --
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