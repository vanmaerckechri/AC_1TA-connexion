<?php
require('./model/model.php');
require('./model/ad_model.php');
require('./model/1ta_update.php');

// -- ACTIVATE SESSION! --
Authentification::startSession();

// -- TEST SPAM --
function testSpam()
{
	if (!isset($_SESSION["lastRequest"]))
	{
		$_SESSION["lastRequest"] = microtime(true);
		return true;
	}
	else
	{
		if (microtime(true) - $_SESSION["lastRequest"] > 120)
		{
			$_SESSION["lastRequest"] = microtime(true);
			return true;
		}
		else
		{
			$_SESSION['smsAlert']['default'] = '<span class="smsAlert">Vous venez d\'effectuer une action similaire, veuillez attendre avant de la réitérer!</span>';
			return false;
		}
	}
}

// -- TOOLS! --
function createClassrooms()
{
	if (isset($_POST['newClassName']))
	{
		$filteredClassroom = checkInput($_POST['newClassName'], 'classroom', 'default');
		if ($filteredClassroom != false)
		{
			Classrooms::createClassroom($_SESSION['id'], $filteredClassroom);
		}
	}
	// form_creatOpen = true to leave the creation menu open after adding a new class
	$form_createOpen = true;
	require('./view/ad_manageClassroomsView.php');	
}

function renameClassroom()
{
	if (isset($_POST['rename']) && isset($_POST['idElem']))
	{
		$filteredClassroom = checkInput($_POST['rename'], 'classroom', 'default');
		if ($filteredClassroom != false)
		{
			Classrooms::renameClassroom($_SESSION['id'], $filteredClassroom, $_POST['idElem']);
		}
	}
	require('./view/ad_manageClassroomsView.php');	
}

function deleteClassrooms()
{
	if (isset($_POST['classrooms']) && !empty('classrooms'))
	{
		$idsSt = Classrooms::deleteClassrooms($_SESSION['id'], $_POST['classrooms']);
		if (!empty($idsSt))
		{
			Update1TerreActionDb::deletePlanet($_POST['classrooms'], $idsSt);
		}
	}
	require('./view/ad_manageClassroomsView.php');	
}

function createStudents()
{
	if (isset($_POST['newStudentNickname']) && isset($_POST['newStudentPassword']) && isset($_GET['idcr']))
	{
		$filteredNickname = checkInput($_POST['newStudentNickname'], 'loginRecord', 'nickname');
		$filteredPassword = checkInput($_POST['newStudentPassword'], 'password', 'password');
		if ($filteredNickname != false && $filteredPassword != false)
		{
			$idSt = Classrooms::createStudent($_SESSION['id'], $filteredNickname, $filteredPassword, $_GET['idcr']);
			/*if ($idSt != false)
			{
				Update1TerreActionDb::createPop($idSt);
			}*/
		}
	}
	$form_createOpen = true;
	require('./view/ad_manageThisClassroomView.php');	
}

function editStudent()
{
	if (isset($_POST['rename']) && isset($_POST['newPassword']) && isset($_POST['idElem']))
	{
		$filteredNickname = checkInput($_POST['rename'], 'nickname', 'default');
		$filteredPassword = checkInput($_POST['newPassword'], 'password', 'default');
		if ($filteredNickname != false && $filteredPassword != false)
		{
			Classrooms::editStudent($_SESSION['id'], $filteredNickname, $filteredPassword, $_POST['idElem']);
		}
	}
	require('./view/ad_manageThisClassroomView.php');	
}

function deleteStudents()
{
	$idsSt = Classrooms::deleteStudents($_SESSION['id'], $_POST['students'], $_GET['idcr']);
	if (!empty($idsSt))
	{
		Update1TerreActionDb::deletePopulation($idsSt, $_POST['students']);
	}
}

// -- PROFILS OPTIONS --
function newPasswordView($isPost = false)
{
	if ($isPost == true)
	{
		$pwd1 = checkInput($_POST['newPassword'], 'password', 'default');
		$pwd2 = checkInput($_POST['newPassword2'], 'password', 'default');
		if ($pwd1 && $pwd2)
		{
			if ($pwd1 === $pwd2)
			{
				$pwd1 = hash('sha256', $pwd1);
				ModifyAdminAccount::UpdatePassword($pwd1, $_SESSION['id']);
				$_SESSION["password"] = $pwd1;
			}
		}
	}
}

function sendValidationCodeToChangeMail()
{
	$testRequestTime = testSpam();
	$testRequestTime = true;//temp
	if ($testRequestTime == true)
	{
		$mail = ModifyAdminAccount::getMail();
		$code = generateCode(8);
		ModifyAdminAccount::updateNewMailCode($code);
		if (isset($mail) && !empty($mail))
		{
			$sujet = "Demande de Changement d'Adresse eMail";
			$message = '<p>Bonjour, vous venez de faire une demande pour changer votre adresse Mail. Voici le code de sécurité pour finaliser la procédure: '.$code.'</p>';
			$sendLogin = new SendMail();
			$sendLogin->default($mail, $sujet, $message);
			$_SESSION['smsAlert']['default'] = '<span class="smsInfo">Le code de validation vient de vous être envoyé par mail!</span>';
		}
		return true;
	}
	else
	{
		return false;
	}
}

function sendValidationCodeToDeleteAccount()
{
	$testRequestTime = testSpam();
	$testRequestTime = true;//temp
	if ($testRequestTime == true)
	{
		$mail = ModifyAdminAccount::getMail();
		$code = generateCode(8);
		ModifyAdminAccount::updateDeleteAccountCode($code);
		if (isset($mail) && !empty($mail))
		{
			$sujet = "Demande de Suppression de votre Compte";
			$message = '<p>Bonjour, vous venez de faire une demande pour supprimer votre compte. Voici le code de sécurité pour finaliser la procédure: '.$code.'</p>';
			$sendLogin = new SendMail();
			$sendLogin->default($mail, $sujet, $message);
			$_SESSION['smsAlert']['default'] = '<span class="smsInfo">Le code de validation vient de vous être envoyé par mail!</span>';
		}
		return true;
	}
	else
	{
		return false;
	}
}

function deleteAccount()
{
	$idCr = Classrooms::displayRooms();
	$idCrList = [];
	foreach ($idCr as $idCrInfo)
	{
		array_push($idCrList, $idCrInfo["id"]);
	}
	$idStudents = Classrooms::deleteClassrooms($_SESSION["id"], $idCrList);
	Update1TerreActionDb::deletePlanet($idCrList, $idStudents);
	// manque la suppression du compte admin dans la db
}

// VIEWS!
function loadManageClassrooms()
{
	require('./view/ad_manageClassroomsView.php');
}
function loadManageThisClassroom()
{
	require('./view/ad_manageThisClassroomView.php');	
}
function loadProfilInfos($adAccountState = false)
{
	require('./view/ad_manageProfilView.php');
}
function loadLibrary()
{
	header('Location: ./library.php');	
}
function disconnect()
{
	$_SESSION = array();
	$_SESSION['smsAlert']['default'] = '<span class="smsInfo">Vous êtes bien déconnecté!</span>';
	header('Location: index.php');	  
}