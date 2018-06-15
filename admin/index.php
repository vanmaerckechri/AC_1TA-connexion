<?php
require('./controller/controller.php');

Authentification::startSession();

// Si le message d'alerte n'existe pas, on le crée vide.
$_SESSION['smsAlert'] = !isset($_SESSION['smsAlert']) || empty($_SESSION['smsAlert']) ? '' : $_SESSION['smsAlert'];

// ROUTEUR!

home();

$_SESSION['smsAlert'] = '';