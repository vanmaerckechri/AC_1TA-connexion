<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!--<meta name="description" content="Plateforme Educative Francophone">-->
    <title>Plateforme Educative</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700%7COswald:400,700" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div id="main">
        <div class="connexion">
            <h1>Plateforme Éducative</h1>
            <h2>Connexion</h2>
    		<?=$content?>
        </div>
        <p class="smsAlert"><?=$_SESSION['smsAlert']?></p>
    </div>
    <script defer src="assets/js/fontawesome-all.min.js"></script>
</body>
</html>