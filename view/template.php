<?php require('./view/privacyView.php');?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!--<meta name="description" content="Plateforme Educative Francophone">-->
    <title>Plateforme Éducative</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
    <div id="main">
        <p><?=$_SESSION['smsAlert']['default']?></p>
        <div class="connexion">
            <h1>Plateforme Éducative</h1>
    		<?=$content?>
        </div>
        <footer>
            <div class="producer">
                <p><a href="http://www.wikicm.be" target="_blank" rel="noopener">WikiCM</a>, centre de connaissances en citoyenneté mondiale coordonné par <a href="http://www.annoncerlacouleur.be" target="_blank" rel="noopener">Annoncer la Couleur</a></p>
            </div>
        </footer>
        <?=$privacy?>
    </div>
    <script type="text/javascript" src="assets/js/privacy.js"></script>
</body>
</html>