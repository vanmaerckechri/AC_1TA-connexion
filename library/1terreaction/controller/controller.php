<?php
require('./model/model.php');

// -- ACTIVATE SESSION --
Authentification::startSession();

function loadMainView()
{
	require('./view/st_mainmenu.php');
}