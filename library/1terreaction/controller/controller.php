<?php
require('./model/model.php');
require('./model/game.php');

// -- ACTIVATE SESSION --
Authentification::startSession();

function loadMainView()
{
	require('./view/st_mainmenu.php');
}

function loadGameView($activeScoreTab = false, $statsEnvAverage = false, $statsSanAverage = false, $statsSoAverage = false)
{
	// load game infos (averages player stats, questions and propositions with score, ...)
	// $gamesInfos = object[playerStats: assoc array, questions: assoc array, propositions: assoc array];
	$gameInfos = GameInfos::call();
	// load background images for local impact
	$imgNumber = round($gameInfos->playerStats['stats_envi']);
	$localBgAir_imgSrc = "assets/img/local_0".$imgNumber.".jpg";
	// load themes
	$allThemes = ["Repas", "Thème 2", "Thème 3", "Thème 4", "Thème 5", "Thème 6"];
	require('./view/st_game.php');
}

function loadGameResultView()
{
	// recordReplies and calcul stats average(this series of player questions, all the themes of this player and this player planet)
	$message = "";

	$statsEnv = json_decode($_POST["statsEnv"]);
	$statsSan = json_decode($_POST["statsSan"]);
	$statsSo = json_decode($_POST["statsSo"]);
	$statsEnvAverage = 0;
	$statsSanAverage = 0;
	$statsSoAverage = 0;

	$_POST["cleanReplies"] = json_decode($_POST["cleanReplies"]);
	if (count($_POST["cleanReplies"]) == 11)
	{
		foreach ($_POST["cleanReplies"] as $key => $reply) 
		{
			if ($key < 9)
			{
				if (!is_numeric($reply) || $reply < 1 || $reply > 3 || !is_numeric($statsEnv[$key]) || $statsEnv[$key] < 0 || $statsEnv[$key] > 2 || !is_numeric($statsSan[$key]) || $statsSan[$key] < 0 || $statsSan[$key] > 2 || !is_numeric($statsSo[$key]) || $statsSo[$key] < 0 || $statsSo[$key] > 2 )
				{
					$message = "Une erreur est survenue";
					break;
				}
				else
				{
					$statsEnvAverage = $statsEnvAverage + $statsEnv[$key];
					$statsSanAverage = $statsSanAverage + $statsSan[$key];
					$statsSoAverage = $statsSoAverage + $statsSo[$key];
				}
				if ($key == 8)
				{
					$statsEnvAverage = $statsEnvAverage / 9;
					$statsSanAverage = $statsSanAverage / 9;
					$statsSoAverage = $statsSoAverage / 9;
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
		RecordReplies::start($_POST["cleanReplies"], $statsEnvAverage, $statsSanAverage, $statsSoAverage);
		loadGameView(true, $statsEnvAverage, $statsSanAverage, $statsSoAverage);
	}
	else
	{
		echo $message;
		return;
	}
	/*
		// Display Stats
		echo "<h2>Moyennes pour ce thème</h2>";
		echo "<p>environnement: ".$statsEnvAverage."</p>";
		echo "<p>santé: ".$statsSanAverage."</p>";
		echo "<p>social: ".$statsSoAverage."</p>";

		echo "<h2>Moyennes de tous les thèmes</h2>";
		echo "<p>environnement: ".$averages["averagePlayer"]["stats_enviAverage"]."</p>";
		echo "<p>santé: ".$averages["averagePlayer"]["stats_santeAverage"]."</p>";
		echo "<p>social: ".$averages["averagePlayer"]["stats_socialAverage"]."</p>";

		echo "<h2>Moyennes de la planète</h2>";
		echo "<p>environnement: ".$averages["averagePlanet"]["stats_enviAverage"]."</p>";
		echo "<p>santé: ".$averages["averagePlanet"]["stats_santeAverage"]."</p>";
		echo "<p>social: ".$averages["averagePlanet"]["stats_socialAverage"]."</p>";
	*/
}