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
		$filteredInput = filterInputs($_POST['newClassName'], 'a-zA-Z0-9À-Ö ._-', 0, 30, 'default');
		if (isset($filteredInput) && !empty($filteredInput) && $filteredInput != "null")
		{
			Classrooms::createClassroom($_SESSION['id'], $_POST['newClassName']);
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
		$filteredNickname = filterInputs($_POST['newStudentNickname'], 'a-zA-Z0-9 @', 4, 30, 'nickname');
		$filteredPassword = filterInputs($_POST['newStudentPassword'], 'a-zA-Z0-9À-Ö ._@', 0, 30, 'password');
		if (isset($filteredNickname) && !empty($filteredNickname) && isset($filteredPassword) && !empty($filteredPassword))
		{
			Classrooms::createStudent($_SESSION['id'], $_POST['newStudentNickname'], $_POST['newStudentPassword'], $_GET['idcr']);
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
	$filteredInput = filterInputs($_GET['cn'], 'a-zA-Z0-9À-Ö ._-', 0, 30, false);
	if ($filteredInput)
	{
		$className = $_GET['cn'];
	}
	else
	{
		$className = "classe inconnue?";
	}
	require('./view/ad_manageModifyStudentsView.php');	
}