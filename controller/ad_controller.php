<?php
require('./model/model.php');
require('./model/ad_model.php');

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
	Classrooms::deleteClassrooms($_SESSION['id'], $_POST['classrooms']);
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
			Classrooms::createStudent($_SESSION['id'], $filteredNickname, $filteredPassword, $_GET['idcr']);
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
	Classrooms::deleteStudents($_SESSION['id'], $_POST['students'], $_GET['idcr']);
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