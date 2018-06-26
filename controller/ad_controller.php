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
		if (isset($filteredInput) && !empty($filteredInput))
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
function loadManageModifyStudents()
{

	require('./view/ad_manageModifyStudentsView.php');	
}