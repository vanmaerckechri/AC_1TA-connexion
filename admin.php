<?php

require('./controller/ad_controller.php');

// ROUTER!
// SESSION
function checkSession()
{
	$auth = new Authentification;
	$sessionResult = $auth->checkSession();

	if ($sessionResult != null)
	{
		if ($sessionResult == 'student')
		{
			// Student connexion
			header('Location: ./library.php');	  
			exit;  		
		}
		else if ($sessionResult == 'wrong')
		{
			// Incorrect login information
			header('Location: ./index.php');
			exit;	    		
		}
	}
	else
	{
		// Incorrect login information
		header('Location: ./index.php');
		exit;
	}

}
checkSession();
$_SESSION['smsAlert'] = !isset($_SESSION['smsAlert']) ? array() : $_SESSION['smsAlert'];
$_SESSION['smsAlert']['default'] = !isset($_SESSION['smsAlert']['default']) ? '' : $_SESSION['smsAlert']['default'];

// CLASSROOMS MANAGEMENT
if (isset($_GET['action']))
{
	// Classrooms management
	if ($_GET['action'] == 'manageClassrooms')
	{
		loadManageClassrooms();
	}
	// Classroom management
	else if ($_GET['action'] == 'manageThisClassroom')
	{
		loadManageThisClassroom();
	}
	// Create new classroom
	else if ($_GET['action'] == 'createClassroom')
	{
		createClassrooms();		
	}
	// Rename classroom
	else if ($_GET['action'] == 'renameClassroom')
	{
		renameClassroom();	
	}
	// Delete classrooms
	else if ($_GET['action'] == 'deleteClassrooms')
	{
		deleteClassrooms();
	}
	// Create new students
	else if ($_GET['action'] == 'addStudents')
	{
		createStudents();		
	}
	// Student management
	else if ($_GET['action'] == 'editStudent')
	{
		editStudent();
	}
	// Delete students
	else if ($_GET['action'] == 'deleteStudents')
	{
		deleteStudents();
	}
	// Profil
	else if ($_GET['action'] == 'profil')
	{
		loadProfilInfos();
	}
	// Profil, change password
	else if ($_GET['action'] == 'changePwd')
	{
		if (isset($_POST['oldPwd']) && hash('sha256', $_POST['oldPwd']) === $_SESSION['password'])
		{
			if (isset($_POST['newPassword']) && isset($_POST['newPassword2']) && strlen($_POST['newPassword']) >= 5 && strlen($_POST['newPassword']) <= 30 && $_POST['newPassword'] === $_POST['newPassword2'])
			{
				newPasswordView(true);
			}
			else
			{
				$_SESSION['smsAlert']['default'] = '<span class="smsAlert">Une erreur est survenue!</span>';
			}
		}
		else
		{
			$_SESSION['smsAlert']['default'] = '<span class="smsAlert">Mot de passe incorrect!</span>';
		}
		loadProfilInfos();
	}
	// Profil, change mail
	else if ($_GET['action'] == 'changeMail')
	{
		if (isset($_POST['mailCode']) && !empty($_POST['mailCode']) && isset($_POST['pwd']) && !empty($_POST['pwd']) && isset($_POST['newMail']) && !empty($_POST['newMail']))
		{
			$hashPwd = hash('sha256', $_POST['pwd']);
			if (filter_var($_POST['newMail'], FILTER_VALIDATE_EMAIL) && ctype_alnum($_POST['mailCode']) && strlen($_POST['mailCode']) == 8 && strlen($_POST['newMail']) >= 5 && strlen($_POST['newMail']) <= 78) 
			{
				$testCode = ModifyAdminAccount::checkCode("changeMail", $_POST['mailCode']);
				if ($testCode === true)
				{
					if ($hashPwd === $_SESSION["password"])
					{
						ModifyAdminAccount::updateNewMail($_POST['mailCode'], $_POST['newMail']);
						$_SESSION['smsAlert']['default'] = '<span class="smsInfo">Votre adresse mail vient d\'être modifiée!</span>';
					}
					else
					{
						$_SESSION['smsAlert']['default'] = '<span class="smsAlert">Mot de passe incorrect!</span>';	
					}
				}
				else
				{
					$_SESSION['smsAlert']['default'] = '<span class="smsAlert">Code incorrect!</span>';
				}
			}
			else
			{
				$_SESSION['smsAlert']['default'] = '<span class="smsAlert">Une erreur est survenue!</span>';
			}
			loadProfilInfos();
		}
		else
		{
			$adAccountState = "changeMailWaitingCode";
			$sendBol = sendValidationCodeToChangeMail();
			if ($sendBol == false)
			{
				$adAccountState = false;
			}
			loadProfilInfos($adAccountState);
		}
	}
	// Profil, delete admin account
	else if ($_GET['action'] == 'deleteAccount')
	{
		if (isset($_POST['code']) && !empty($_POST['code']) && isset($_POST['pwd']) && !empty($_POST['pwd']))
		{
			$hashPwd = hash('sha256', $_POST['pwd']);
			if (ctype_alnum($_POST['code']) && strlen($_POST['code']) == 8) 
			{
				$testCode = ModifyAdminAccount::checkCode("deleteAccount", $_POST['code']);
				if ($testCode === true)
				{
					if ($hashPwd === $_SESSION["password"])
					{
						deleteAccount();
						$_SESSION['smsAlert']['default'] = '<span class="smsInfo">Compte supprimé!</span>';
					}
					else
					{
						$_SESSION['smsAlert']['default'] = '<span class="smsAlert">Mot de passe incorrect!</span>';	
					}
				}
				else
				{
					$_SESSION['smsAlert']['default'] = '<span class="smsAlert">Code incorrect!</span>';
				}
			}
			else
			{
				$_SESSION['smsAlert']['default'] = '<span class="smsAlert">Une erreur est survenue!</span>';
			}
			loadProfilInfos();
		}
		else
		{
			$adAccountState = "deleteAccountWaitingCode";
			$sendBol = sendValidationCodeToDeleteAccount();
			if ($sendBol == false)
			{
				$adAccountState = false;
			}
			loadProfilInfos($adAccountState);
		}
	}
	// -- LIBRARY --
	else if ($_GET['action'] == 'library')
	{
		loadLibrary();
	}
	else if ($_GET['action'] == 'disco')
	{
		disconnect();
	}
	else
	{
		loadManageClassrooms();
	}
}
else
{
	loadManageClassrooms();
}

$_SESSION['smsAlert']['default'] = "";
$_SESSION['smsAlert']['nickname'] = "";
$_SESSION['smsAlert']['email'] = "";
$_SESSION['smsAlert']['password'] = "";
$_SESSION['smsAlert']['password2'] = "";