<?php

require('./controller/controller.php');

// ROUTEUR!
// LOGIN
if (isset($_POST) && !isset($_GET['action']))
{
	// Nickname
	if (isset($_POST['nickname']))
	{
		$filteredInput = filterInputs($_POST['nickname'], 'a-zA-Z0-9@', 4, 20);
		if (!is_array($filteredInput))
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
			$_SESSION['smsAlert']['default'] = $filteredInput[0];
			loadHomeView();
		}
	}
	// Classroom (Student Only)
	else if (isset($_POST['classroom']) && isset($_SESSION['nickname']))
	{
		$filteredInput = filterInputs($_POST['classroom'], 'a-zA-Z0-9À-Ö ._-', 0, 30);
		if (!is_array($filteredInput))
		{
			$_SESSION['classroom'] = $filteredInput;
			loadPwdView();
		}
		else 
		{
			$_SESSION['smsAlert']['default'] = $filteredInput[0];
			loadClassroomView();
		}
	}
	// Password
	else if (isset($_POST['password']) && isset($_SESSION['nickname']))
	{
		$filteredInput = filterInputs($_POST['password'], 'a-zA-Z0-9À-Ö ._@', 0, 30);
		if (!is_array($filteredInput))
		{
			$_SESSION['password'] = $filteredInput;
			checkSession();
			loadPwdView();
		}
		else
		{
			$_SESSION['smsAlert']['default'] = $filteredInput[0];
			loadPwdView();
		}
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
		$nickname = '""';
		$email = '""';
		// Check if all inputs are corrects
		if (isset($_POST['createAdminAccountNickname']) && isset($_POST['createAdminAccountEmail']) && isset($_POST['createAdminAccountPassword']) && isset($_POST['createAdminAccountPassword2']))
		{
			$filteredNickname = filterInputs($_POST['createAdminAccountNickname'], 'a-zA-Z0-9', 4, 20);
			$is_email = filter_var($_POST['createAdminAccountEmail'], FILTER_VALIDATE_EMAIL);
			$filteredPwd = filterInputs($_POST['createAdminAccountPassword'], 'a-zA-Z0-9À-Ö ._@', 0, 30);
			$filteredPwd2 = filterInputs($_POST['createAdminAccountPassword2'], 'a-zA-Z0-9À-Ö ._@', 0, 30);
			// If at least one of them is not correct
			if (is_array($filteredNickname) || is_array($filteredPwd) || is_array($filteredPwd2) || $is_email == false || $filteredPwd != $filteredPwd2)
			{

				if (is_array($filteredNickname))
				{
					$_SESSION['smsAlert']['nickname'] = $filteredNickname[0];
				}
				else
				{
					$nickname = $_POST['createAdminAccountNickname'];
				}

				if ($is_email == false)
				{
					$_SESSION['smsAlert']['email'] = "<span class='smsAlert'>Veuillez entrer une adresse email valide!</span>";
				}
				else
				{
					$email = $_POST['createAdminAccountEmail'];
				}

				if (is_array($filteredPwd))
				{
					$_SESSION['smsAlert']['password'] = $filteredPwd[0];
				}

				if ($filteredPwd != $filteredPwd2)
				{
					$_SESSION['smsAlert']['password2'] = "<span class='smsAlert'>Les mots de passe ne correspondent pas!</span>";
				}
			}
			// Every inputs are corrects
			else
			{
			}
		}
		loadCreateAdminAccountView($nickname, $email);
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