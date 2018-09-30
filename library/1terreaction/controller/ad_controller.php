<?php
require('./model/model.php');
require('./model/ad_model.php');
require('./../../model/1ta_update.php');


// -- ACTIVATE SESSION --
Authentification::startSession();

// -- VIEWS --

function loadMainView()
{
	if (isset($_POST["inputPlanetActivationIdCrList"]) && !empty($_POST["inputPlanetActivationIdCrList"]))
	{
		ManagePlanets::recordPlanetActivationModifications($_POST["inputPlanetActivationIdCrList"], $_POST["inputPlanetActivationStatusList"]);
	}
	if (isset($_POST["inputThemeActivationIdCrList"]) && !empty($_POST["inputThemeActivationIdCrList"]))
	{
		ManagePlanets::recordThemeActivationModifications($_POST["inputThemeActivationIdCrList"], $_POST["inputThemeActivationNameList"], $_POST["inputThemeActivationStatusList"]);
	}
	if (isset($_POST["inputOpenQuestionIdCrList"]) && !empty($_POST["inputOpenQuestionIdCrList"]))
	{
		ManagePlanets::recordOpenQuestionModifications($_POST["inputOpenQuestionIdCrList"], $_POST["inputThemeNameList"], $_POST["inputOpenQuestionContentList"]);
	}
	$planetList = ManagePlanets::callPlanetList($_SESSION['id']);
	$studentsInfos = ManagePlanets::callStudentsList($planetList);
	$freeClassrooms = ManagePlanets::callFreeClassroomsList($_SESSION['id']);
	$themeActivationList = ManagePlanets::callOpenQuestionAndActivationStatusThemes($_SESSION['id']);
	require('./view/ad_managePlanets.php');
}

function createPlanetView($idCr)
{
	if ($idCr >= 0)
	{
		ManagePlanets::create($idCr);
	}
	else
	{
		loadMainView();
	}
}

function deletePlanetView($idCr)
{
	$idStudents = ManagePlanets::getIdStudents($idCr);
	if ($idCr >= 0)
	{
		Update1TerreActionDb::deletePlanet($idCr, $idStudents);
	}
	else
	{
		loadMainView();
	}
}

function disconnect()
{
	$_SESSION = array();
	$_SESSION['smsAlert']['default'] = '<span class="smsInfo">Vous êtes bien déconnecté!</span>';
	header('Location: admin.php');	  
}