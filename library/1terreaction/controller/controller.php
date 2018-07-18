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
	// load averages player stats and unlocked themes
	$gameInfos = GameInfos::call();
	// load background images for local impact
	$localBgWater_imgSrc = "assets/img/local_background_water0".$gameInfos['stats_water'].".png";
	$localBgAir_imgSrc = "assets/img/local_background_air0".$gameInfos['stats_air'].".png";
	$localBgForest_imgSrc = "assets/img/local_background_forest0".$gameInfos['stats_forest'].".png";
	// load unlocked themes
	$allThemes = ["Repas", "Thème 2", "Thème 3", "Thème 4", "Thème 5"];
	require('./view/st_game.php');
}