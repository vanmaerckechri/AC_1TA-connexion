<?php
    $connected = false;
    session_start();
    //SECURISER LA SESSSION!
    //Si la variable 'ip' de session n'existe pas, on la crée.
    if(!isset($_SESSION['ip']))
    {
      $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
    }
    //Si la variable 'ip' de session a changé (il s'agit peut être d'une usurpation d'identité) => déconnection, retour à la page d'authentification.
    if($_SESSION['ip']!=$_SERVER['REMOTE_ADDR'])
    {
      $_SESSION = array();
      $_SESSION['smsAlert'] = 'Vous avez été déconnecté';
      exit;
    }
    /*
    //Verifier si l'utilisateur est déjà log.
    if(isset($_SESSION['login']) && isset($_SESSION['pwd']))
    {
      //Connexion à la base de donnée.
      include 'connectdb.php';
        $login = htmlspecialchars($_SESSION['login']);
        $pwd = htmlspecialchars($_SESSION['pwd']);

        //Chargement de la requête préparée.
        $req = $bdd->prepare('SELECT * FROM membres');
        $req->execute();
        //Pour chaque rangée...
        while ($compare = $req->fetch())
        {
          if ($login == $compare['nick'] && $pwd == $compare['password'])
          {
            $membreLogId = $compare['id_membres'];
            $connected = true;
          }
        }
        //Si la varible 'sessionOk' n'est pas passée à 'true' lors de la vérifiaction précédente, la session est vidée et retour à la page d'authentification.
        if ($connected == false)
        {
          $req->closeCursor();
          $req = NULL;
          $_SESSION = array();
        }
        $req->closeCursor();
        $req = NULL;
      }
    //Déconnection.
    if(isset($_POST['logout']))
    {
      $logout = htmlspecialchars(isset($_POST['logout'])) && htmlspecialchars(!EMPTY($_POST['logout'])) ? htmlspecialchars($_POST['logout']) : '';
      if ($logout != "")
      {
          $_SESSION = array();
          session_destroy();
          $connected = false;
          header('Location: blog.php');
      }
    }
    */
?>