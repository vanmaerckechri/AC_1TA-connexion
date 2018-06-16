<?php
require('./model/model.php');
//VIEWS!
function home()
{
    require('./view/loginView.php');
}
function classroom()
{
    require('./view/classroomView.php');
}
function pwd()
{
    require('./view/pwdView.php');
}
function nicknameRecovery()
{
	require('./view/nnRecoveryView.php');
	exit;
}
function passwordRecovery()
{
	require('./view/passwordRecoveryView.php');
	exit;
}
function newAdminAccount()
{
	require('./view/newAdminAccountView.php');
	exit;
}