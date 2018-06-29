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
	require('./view/ad_manageClassroomsView.php');	
}

function renameClassroom()
{
	if (isset($_POST['renameClassroom']) && isset($_POST['idClassroom']))
	{
		$filteredClassroom = checkInput($_POST['renameClassroom'], 'classroom', 'default');
		if ($filteredClassroom != false)
		{
			Classrooms::renameClassroom($_SESSION['id'], $filteredClassroom, $_POST['idClassroom']);
		}
	}
	require('./view/ad_manageClassroomsView.php');	
}

function deleteClassrooms()
{
	Classrooms::deleteClassrooms($_SESSION['id'], $_POST['classrooms']);
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
	$createStudent = true;
	require('./view/ad_manageThisClassroomView.php');	
}

function deleteStudents()
{
	Classrooms::deleteStudents($_SESSION['id'], $_POST['students'], $_GET['idcr']);
}

function disconnect()
{
	$_SESSION = array();
	$_SESSION['smsAlert']['default'] = '<span class="smsInfo">Vous êtes bien déconnecté!</span>';
	header('Location: index.php');	  
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
function loadManageModifyStudents()
{
	$filteredClassroom = checkInput($_GET['cn'], 'classroom', false);
	if ($filteredClassroom != false)
	{
		$className = $filteredClassroom;
	}
	else
	{
		$className = "classe inconnue?";
	}
	require('./view/ad_manageModifyStudentsView.php');	
}