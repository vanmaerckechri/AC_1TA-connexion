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
	$localBgAir_imgSrc = "assets/img/local_0".$gameInfos->playerStats['stats_environnement'].".jpg";
	// load themes
	$allThemes = ["Repas", "Thème 2", "Thème 3", "Thème 4", "Thème 5", "Thème 6"];
	require('./view/st_game.php');
}

function loadGameResultView()
{
	// recordReplies
	$message = "";

	$statsEnv = json_decode($_POST["statsEnv"]);
	$statsSan = json_decode($_POST["statsSan"]);
	$statsSo = json_decode($_POST["statsSo"]);

	$_POST["cleanReplies"] = json_decode($_POST["cleanReplies"]);
	if (count($_POST["cleanReplies"]) == 11)
	{
		foreach ($_POST["cleanReplies"] as $key => $reply) 
		{
			if ($key < 9)
			{
				if (!is_numeric($reply) || $reply < 1 || $reply > 3 || !is_numeric($statsEnv[$key]) || $statsEnv[$key] < 1 || $statsEnv[$key] > 3 || !is_numeric($statsSan[$key]) || $statsSan[$key] < 1 || $statsSan[$key] > 3 || !is_numeric($statsSo[$key]) || $statsSo[$key] < 1 || $statsSo[$key] > 3 )
				{
					$message = "Une erreur est survenue";
					break;
				}
			}
			$serie = $_POST["cleanReplies"][10];
			if (strlen($serie) != 1)
			{
				$message = "Une erreur est survenue";
			}
		}
	}
	if ($message == "")
	{
		RecordReplies::start($_POST["cleanReplies"]);
	}
	else
	{
		echo $message;
		return;
	}
	// updateStats
}