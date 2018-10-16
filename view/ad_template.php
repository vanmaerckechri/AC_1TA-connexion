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
                    <a href="admin.php?action=profil" class="ad_nick"><?=$_SESSION['nickname']?></a>
                    <a href="admin.php?action=disco" class="disconnect">X</a>
                </div>
            </div>
        </div>
    </header>
    <div class="navBar">
        <h2><?=$pageName?></h2>
        <div class="navBar_buttonsContainer">
            <a class="formButton navBar_button" href="admin.php">Classes</a>
            <a class="formButton navBar_button" href="admin.php?action=library">Ludothèque</a>
        </div>
    </div>
    <div id="main">
        <?=$tools?>
        <?=$content?>
        <?php/*
            if (isset($_SESSION["smsAlert"]) && isset($_SESSION["smsAlert"]['default']))
            {
                echo $_SESSION["smsAlert"]["default"];
            }*/
        ?>
    </div>
    <footer>
        <div class="producer">
            <p><a href="http://www.wikicm.be" target="_blank" rel="noopener">WikiCM</a>, centre de connaissances coordonné par <a href="http://www.annoncerlacouleur.be" target="_blank" rel="noopener">Annoncer la Couleur</a>
        </div>
    </footer>
    <?=$script?>
</body>
</html>