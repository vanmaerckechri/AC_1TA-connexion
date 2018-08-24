<?php

require('./model/model.php');
// SESSION
Authentification::startSession();

if (isset($_GET['action'])&& $_GET['action'] == "disco")
{
	$_SESSION = array();
	$_SESSION['smsAlert']['default'] = '<span class="smsInfo">Vous êtes bien déconnecté!</span>';
	header('Location: index.php');
	exit;  
}
function checkSession()
{
	$auth = new Authentification;
	$sessionResult = $auth->checkSession();
	if ($sessionResult != null)
	{
		if ($sessionResult == 'wrong')
		{
			// Incorrect login information
			header('Location: ./index.php');
			exit;	    		
		}
	}
	else
	{
		// Incorrect login information
		header('Location: ./index.php');
		exit;
	}
	return $sessionResult;
}
$sessionResult = checkSession();
$_SESSION['smsAlert'] = !isset($_SESSION['smsAlert']) ? array() : $_SESSION['smsAlert'];
$_SESSION['smsAlert']['default'] = !isset($_SESSION['smsAlert']['default']) ? '' : $_SESSION['smsAlert']['default'];

$libraryList = Library::load();

ob_start();
if ($sessionResult == "admin")
{
	$pageByUserStatus = "admin";
	?>
        <a class="formButton navBar_button" href="admin.php">Classes</a>
        <a class="formButton navBar_button" href="admin.php?action=library">Ludothèque</a>
    <?php
}
else if ($sessionResult == "student")
{
	$pageByUserStatus = "index";
}
$nav = ob_get_clean();


ob_start();
?>
<div class="libElemContainer">
<?php
foreach ($libraryList as $libElem)
{
?>
    <a class="libElemLink" href="library/<?=$libElem['folder']?>/<?=$pageByUserStatus?>.php?action=main">
        <h3 class="libElemTitle">
            <?=$libElem['name']?>
        </h3>
        <img class="libElemCover" src="library/<?=$libElem['folder']?>/cover.jpg" alt="vignette pour <?=$libElem['name']?>">
        <div class="libElemDescription">
            <p><strong>Description: </strong><?=$libElem['description']?></p>
        </div>
    </a>
<?php
}
?>
</div>
<?php
$content = ob_get_clean();

$avatarInfos = Avatar::load();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!--<meta name="description" content="Plateforme Educative Francophone">-->
    <title>Plateforme Éducative</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700%7COswald:400,700" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body class="admin">
    <header>
        <div class="headerContainer">
            <div class="headerTitle">
                <h1>Plateforme Éducative</h1>
            </div>
            <div class="headerProfile">
                <div class="profile">
                    <div id="avatarContainer" class="avatarContainer">
                    </div>
                    <?=$_SESSION['nickname']?>
                    <a href="library.php?action=disco" class="disconnect">X</a>
                </div>
            </div>
        </div>
    </header>
    <div class="navBar">
        <h2>Ludothèque</h2>
        <div class="navBar_buttonsContainer">
        	<?=$nav?>
        </div>
    </div>
    <div id="main">
        <?=$content?>
     	<?=$_SESSION['smsAlert']['default']?>
    </div>
    <footer>
        <div class="producer">
            <a href="http://www.annoncerlacouleur.be/" target="_blank" rel="noopener">Annoncer la Couleur</a>
        </div>
    </footer>
    <script>
        let avatarInfos = <?=json_encode($avatarInfos)?>;
        avatarInfos = avatarInfos[0];
    </script>
    <script type= "text/javascript" src="js/avatar.js"></script>
</body>
</html>

<?php
$_SESSION['smsAlert']['default'] = "";
$_SESSION['smsAlert']['nickname'] = "";
$_SESSION['smsAlert']['email'] = "";
$_SESSION['smsAlert']['password'] = "";
$_SESSION['smsAlert']['password2'] = "";