<?php
require('./model/model.php');
require('./model/ad_model.php');

// ACTIVATE SESSION!
Authentification::startSession();

// VIEWS!
function loadManageClassrooms()
{
	require('./view/ad_manageClassroomsView.php');
}
function loadManageStudents()
{

	require('./view/ad_manageStudentsView.php');	
}
function loadManageModifyStudents()
{

	require('./view/ad_manageModifyStudentsView.php');	
}