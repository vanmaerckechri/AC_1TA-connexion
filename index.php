<?php
require('./controller/controller.php');

$_SESSION["id"] = 19;
$_SESSION["nickname"] = "Martin";
$_SESSION["password"] = "12345678";
$_SESSION["id_classroom"] = 5;
$_SESSION["classroom"] = "Classe de Test 01";
/*
$_SESSION["id"] = 31;
$_SESSION["nickname"] = "admin@Chri";
$_SESSION["password"] = "ed968e840d10d2d313a870bc131a4e2c311d7ad09bdf32b3418147221f51a6e2";
$_SESSION["classroom"] = "";
*/

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
		else
		{
			// Student connexion
			header('Location: ./index.php');	  
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
		$filteredInput = checkInput($_POST['nickname'], 'nickname', 'default');
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