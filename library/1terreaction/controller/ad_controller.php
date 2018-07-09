<?php
require('./model/model.php');
require('./model/ad_model.php');

// -- ACTIVATE SESSION --
Authentification::startSession();

// -- VIEWS --

function loadMainView()
{
	$planetList = ManagePlanets::callPlanetList($_SESSION['id']);
	$studentsList = ManagePlanets::callStudentsList($planetList);
	$freeClassrooms = ManagePlanets::callFreeClassroomsList($_SESSION['id']);
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
	if ($idCr >= 0)
	{
		ManagePlanets::deletePlanet($idCr);
	}
	else
	{
		loadMainView();
	}
}