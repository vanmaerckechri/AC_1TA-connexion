<?php
require('./model/model.php');
require('./model/ad_model.php');

// -- ACTIVATE SESSION --
Authentification::startSession();

// -- VIEWS --

function loadMainView()
{
	$planetList = ManagePlanets::callList($_SESSION['id']);
	$freeClassrooms = ManagePlanets::callFreeClassroomsList($_SESSION['id']);
	require('./view/ad_managePlanets.php');
}