<?php
require('./model/model.php');
require('./model/game.php');

// -- ACTIVATE SESSION --
Authentification::startSession();

function loadMainView()
{
	require('./view/st_mainmenu.php');
}

function loadGameView()
{
	// load averages player stats
	$stats = GameInfos::call();
	// load background images for local impact
	$localBgWater_imgSrc = "assets/img/local_background_water0".$stats['stats_water'].".png";
	$localBgAir_imgSrc = "assets/img/local_background_air0".$stats['stats_air'].".png";
	$localBgForest_imgSrc = "assets/img/local_background_forest0".$stats['stats_forest'].".png";
	require('./view/st_game.php');
}