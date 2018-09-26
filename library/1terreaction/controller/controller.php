<?php
require('./model/model.php');
require('./model/game.php');
require('../../model/avatar.php');


// -- ACTIVATE SESSION --
Authentification::startSession();

function loadAvatar()
{
	$avatarInfos = Avatar::load();

	ob_start();
	foreach ($avatarInfos[0] as $avatarThemeName => $avatarSrc) 
	{
	    // avatarSrc = 1 for a new account. Student need to create an avatar to first connexion.
	    if ($avatarSrc != 1 && (substr($avatarSrc, -4) == ".svg"))
	    {
	        ?>
	        <img class=<?=$avatarThemeName?> src="../../assets/img/<?=$avatarSrc?>"" alt="">
	        <?php
	    }
	    else if ($avatarSrc == "")
	    {
	        ?>
	        <img class=<?=$avatarThemeName?> src="" alt="">
	        <?php
	    }
	    else
	    {
	        ?>
	        <img class=<?=$avatarThemeName?> src="../../assets/img/<?=$avatarThemeName?>01col01.svg" alt="">
	        <?php
	    }
	}
	$avatarContent = ob_get_clean();

	return $avatarContent;
}

function loadMainView()
{
	$planetStats = GameInfos::getPlanetStatsAverage();
	$avatarContent = loadAvatar();
	require('./view/st_mainmenu.php');
}

function loadGameView($activeScoreTab = false, $statsEnvThemefromLastGame = false, $statsSanThemefromLastGame = false, $statsSoThemefromLastGame = false)
{
	// load game infos (averages player stats, questions and propositions with score, ...)
	// $gamesInfos = object[playerStats: assoc array, questions: assoc array, propositions: assoc array];
	$gameInfos = GameInfos::call();
	// load background images for local impact
	$imgNumber = round($gameInfos->playerStats['stats_envi']);
	$localBgAir_imgSrc = "assets/img/local_0".$imgNumber.".jpg";
	// load themes
	$allThemes = ["alimentation", "Thème 2", "Thème 3", "Thème 4", "Thème 5", "Thème 6"];

	$currentThemeIndex = ord(strtolower($gameInfos->openquestion["serie"])) - 97;
	$currentTheme = $allThemes[$currentThemeIndex];

	$avatarContent = loadAvatar();
	require('./view/st_game.php');
}

function loadGameResultView()
{
	// recordReplies and calcul stats average(this series of player questions, all the themes of this player and this player planet)
	$message = "";

	$statsEnv = json_decode($_POST["statsEnv"]);
	$statsSan = json_decode($_POST["statsSan"]);
	$statsSo = json_decode($_POST["statsSo"]);
	$statsEnvCurrentTheme = 0;
	$statsSanCurrentTheme = 0;
	$statsSoCurrentTheme = 0;
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
					$statsEnvCurrentTheme = $statsEnvCurrentTheme + $statsEnv[$key];
					$statsSanCurrentTheme = $statsSanCurrentTheme + $statsSan[$key];
					$statsSoCurrentTheme = $statsSoCurrentTheme + $statsSo[$key];
				}
				if ($key == 8)
				{
					$statsEnvCurrentTheme = $statsEnvCurrentTheme / 9;
					$statsSanCurrentTheme = $statsSanCurrentTheme / 9;
					$statsSoCurrentTheme = $statsSoCurrentTheme / 9;
				}
			}
		}
		$serie = $_POST["cleanReplies"][10];
		if (strlen($serie) > 20)
		{
			$message = "Une erreur est survenue";
		}
	}
	if ($message == "")
	{
		RecordReplies::start($_POST["cleanReplies"], $statsEnvCurrentTheme, $statsSanCurrentTheme, $statsSoCurrentTheme);
		loadGameView(true, $statsEnvCurrentTheme, $statsSanCurrentTheme, $statsSoCurrentTheme);
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
		echo "<p>santé: ".$statsSanCurrentTheme."</p>";
		echo "<p>social: ".$statsSoCurrentTheme."</p>";

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

function disconnect()
{
	$_SESSION = array();
	$_SESSION['smsAlert']['default'] = '<span class="smsInfo">Vous êtes bien déconnecté!</span>';
	header('Location: index.php');
}