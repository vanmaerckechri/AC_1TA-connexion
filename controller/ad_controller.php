<?php
require('./model/model.php');
require('./model/ad_model.php');
require('./model/1ta_update.php');

// ACTIVATE SESSION!
Authentification::startSession();

// TOOLS!
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
		$filteredNickname = checkInput($_POST['newStudentNickname'], 'nickname', 'nickname');
		$filteredPassword = checkInput($_POST['newStudentPassword'], 'password', 'password');
		if ($filteredNickname != false && $filteredPassword != false)
		{
			$idSt = Classrooms::createStudent($_SESSION['id'], $filteredNickname, $filteredPassword, $_GET['idcr']);
			if ($idSt != false)
			{
				Update1TerreActionDb::createPop($idSt);
			}
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

// VIEWS!
function loadManageClassrooms()
{
	require('./view/ad_manageClassroomsView.php');
}
function loadManageThisClassroom()
{
	require('./view/ad_manageThisClassroomView.php');	
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