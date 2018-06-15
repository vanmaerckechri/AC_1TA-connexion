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
function platform()
{
	header('Location: ./platform/index.php');
	exit;
}
//AUTHENTIFICATION!
