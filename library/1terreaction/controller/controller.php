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
	// load game infos (averages player stats, questions and propositions with score, ...)
	// $gamesInfos = object[playerStats: assoc array, questions: assoc array, propositions: assoc array];
	$gameInfos = GameInfos::call();
	// load background images for local impact
	$localBgAir_imgSrc = "assets/img/local_background_air0".$gameInfos->playerStats['stats_air'].".png";
	$localBgAirFilter_imgSrc = "assets/img/local_background_airFilter0".$gameInfos->playerStats['stats_air'].".png";
	$localBgWater_imgSrc = "assets/img/local_background_water0".$gameInfos->playerStats['stats_water'].".png";
	$localBgForest_imgSrc = "assets/img/local_background_forest0".$gameInfos->playerStats['stats_forest'].".png";
	// load themes
	$allThemes = ["Repas", "Thème 2", "Thème 3", "Thème 4", "Thème 5", "Thème 6"];
	require('./view/st_game.php');
}