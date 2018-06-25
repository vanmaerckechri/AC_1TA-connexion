<?php
require('./model/model.php');
require('./model/ad_model.php');

// ACTIVATE SESSION!
Authentification::startSession();

// TOOLS!
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