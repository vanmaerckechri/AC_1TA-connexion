<?php
require('./controller/controller.php');

if (!empty($_POST))
{
	//LOGIN
	if (isset($_POST['nickname']))
	{
		$_SESSION['nickname'] = htmlspecialchars($_POST['nickname'], ENT_NOQUOTES);
		//Teacher
		if ($test = strstr($_SESSION['nickname'], 'admin@'))
		{
			$_SESSION['classroom'] = '';
			pwd();
		}
		//Student
		else
		{
			classroom();
		}
	}
	//CLASSROOM (Student Only)
	else if (isset($_POST['classroom']))
	{
		$_SESSION['classroom'] = htmlspecialchars($_POST['classroom'], ENT_NOQUOTES);
		pwd();	
	}
	//PASSWORD
	else if (isset($_POST['password']))
	{
		$_SESSION['password'] = htmlspecialchars($_POST['password'], ENT_NOQUOTES);
		//checkAccountDB();	
	}
}
else
{
	home();
}