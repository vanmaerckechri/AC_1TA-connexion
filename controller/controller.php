<?php

require('./model/model.php');

// ACTIVATE SESSION!
Authentification::startSession();

function checkSession()
{
	$auth = new Authentification;
	$sessionResult = $auth->checkSession();

	if ($sessionResult != null)
	{
	    if ($sessionResult == 'admin')
		{
			// Admin connexion
			header('Location: ./admin/index.php');
			exit;  		
		}
		else if ($sessionResult == 'student')
		{
			// Student connexion
			header('Location: ./platform/index.php');	  
			exit;  		
		}
		else if ($sessionResult == 'wrong')
		{
			// Informations de connexion incorrectes
			header('Location: ./index.php');
			exit;	    		
		}
	}
}
checkSession();

//VIEWS!
function loadHomeView()
{
    require('./view/loginView.php');
}
function loadClassroomView()
{
    require('./view/classroomView.php');
}
function loadPwdView()
{
    require('./view/pwdView.php');
}
function loadNicknameRecoveryView()
{
	require('./view/nnRecoveryView.php');
}
function loadPwdRecoveryView()
{
	require('./view/passwordRecoveryView.php');
}
function loadCreateAdminAccountView()
{
	require('./view/newAdminAccountView.php');
}