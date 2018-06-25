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
        <?=$_SESSION['nickname']?>
    </header>
    <div id="main">
        <div class="pageName">
            <h1>Plateforme Éducative</h1>
            <h2><?=$pageName?></h2>
        </div>
    	<div class="container">
            <?=$content?>
        </div>
        <p><?=$_SESSION['smsAlert']['default']?></p>
    </div>
    <footer>
        <div class="producer">
            <a href="http://www.annoncerlacouleur.be/" target="_blank" rel="noopener">Annoncer la Couleur</a>
        </div>
    </footer>
    <script defer src="assets/js/fontawesome-all.min.js"></script>
</body>
</html>