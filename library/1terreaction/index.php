<?php
require('./controller/controller.php');

// -- ROUTEUR --
// SESSION
function checkSession()
{
	$auth = new Authentification;
	$sessionResult = $auth->checkSession();
	if ($sessionResult == null || $sessionResult != 'student')
	{
		if ($sessionResult != 'student')
		{
			// Incorrect login information
			header('Location: ../../index.php');
			exit;	    		
		}
	}
	return $sessionResult;
}
$sessionResult = checkSession();
$launchIntroVideo = true;
// -- VIEW --
	// Record Replies
if (isset($_POST['cleanReplies']))
{
	$launchIntroVideo = false;
	loadGameResultView();
}
else
{
	if (isset($_GET['video']) && $_GET['video'] == "false")
	{
		$launchIntroVideo = false;
	}
	if (isset($_GET['action']))
	{
		// Main Menu
		if ($_GET['action'] == 'main')
		{
			// Main Menu with Final Theme Video
			if (isset($_GET['themevideo']))
			{
				if ($_GET['themevideo'] == "alimentation")
				{
					loadMainView("alimentation");
				}
				else
				{
					loadMainView();
				}
			}
			else
			{
				loadMainView();
			}
		}
		// Launch Game
		else if ($_GET['action'] == 'game')
		{
			loadGameView();
		}
		// Disconnect
		else if ($_GET['action'] == 'disco')
		{
			disconnect();
		}
		else
		{
			loadMainView();
		}
	}
	else
	{
		loadMainView();
	}
}
?>