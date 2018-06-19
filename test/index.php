<?php
    $dbCoordinates = ["dbHost" => "cvm.one.mysql", "dbPort" => "22", "dbName" => "cvm_one", "dbCharset" => "utf8", "dbLogin" => "cvm_one", "dbPwd" => "miwhJEuV6DN7Ysr5YW3DtJEs", "table" => "gen_code_can_members"];

    try
    {
        $db = new PDO('mysql:host=cvm.one.mysql; dbname=cvm_one; charset=utf8', "cvm_one", "miwhJEuV6DN7Ysr5YW3DtJEs");
    } 
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

    $req = $db->prepare("SELECT id FROM PE_adminAccounts WHERE nickname = :name AND password = :pwd");

    $nom = "test";
    $pwd2 = "123456";

    $req->bindValue(':name', $nom, PDO::PARAM_STR);
    $req->bindValue(':pwd', $pwd2, PDO::PARAM_STR);
    $req->execute();
    $resultReq = $req->fetch();
    $req->closeCursor();
    $req = NULL;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Plateforme Éducative</title>
</head>
<body>
    <p>test:</p>
    <?php
        var_dump($resultReq);
    ?>
</body>
</html>