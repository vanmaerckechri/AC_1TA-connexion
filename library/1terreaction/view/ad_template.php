<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!--<meta name="description" content="">-->
    <title><?=$title?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="admin">
  	<div id="container">
    </div>
    <div class="headerProfile">
        <div class="appTitle">1TerreAction</div>
        <div class="profile">
            <?=$_SESSION['nickname']?>
            <a href="admin.php?action=disco" class="disconnect">X</a>
        </div>
    </div>
  	<?=$content?>
    <?=$script?>
</body>
</html>