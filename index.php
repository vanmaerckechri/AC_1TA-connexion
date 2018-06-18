<?php

require('./controller/controller.php');

// ROUTEUR!
if (isset($_POST) && !isset($_GET['action']))
{
	// LOGIN
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
	// CREATE NEW ACCOUNT
	else if (isset($_POST['createAdminAccountNickname']) && isset($_POST['createAdminAccountEmail']) && isset($_POST['createAdminAccountPassword']) && isset($_POST['createAdminAccountPassword2']))
	{
		$filteredNickname = filterInputs($_POST['createAdminAccountNickname'], 'a-zA-Z0-9', 4, 20);
		$is_email = filter_var($_POST['createAdminAccountEmail'], FILTER_VALIDATE_EMAIL);
		$filteredPwd = filterInputs($_POST['createAdminAccountPassword'], 'a-zA-Z0-9À-Ö ._@', 0, 30);
		$filteredPwd2 = filterInputs($_POST['createAdminAccountPassword2'], 'a-zA-Z0-9À-Ö ._@', 0, 30);

		if (is_array($filteredNickname) || is_array($filteredPwd) || is_array($filteredPwd2) || $is_email == false || $filteredPwd != $filteredPwd2)
		{

			if (is_array($filteredNickname))
			{
				$_SESSION['smsAlert']['nickname'] = $filteredNickname[0];
			}

			if ($is_email == false)
			{
				$_SESSION['smsAlert']['email'] = "<span class='smsAlert'>Veuillez entrer une adresse email valide!</span>";
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
		loadCreateAdminAccountView();
	}
	else
	{
		loadHomeView();
	}
}
else
{
	// Nickname recovery
	if ($_GET['action'] == 'namerecovery')
	{
		loadNicknameRecoveryView();
	}
	// Password recovery
	else if ($_GET['action'] == 'passwordrecovery')
	{
		loadPwdRecoveryView();
	}
	// Create admin account
	else if ($_GET['action'] == 'newadminaccount')
	{
		loadCreateAdminAccountView();
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