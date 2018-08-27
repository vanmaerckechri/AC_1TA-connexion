<?php

function loadAvatar($relativePath)
{
	$avatarInfos = Avatar::load();

	ob_start();
	foreach ($avatarInfos[0] as $avatarThemeName => $avatarSrc) 
	{
	    // avatarSrc = 1 for a new account. Student need to create an avatar to first connexion.
	    if ($avatarSrc != 1 && (substr($avatarSrc, -4) == ".svg"))
	    {
	        ?>
	        <img class=<?=$avatarThemeName?> src="<?=$relativePath?><?=$avatarSrc?>"" alt="">
	        <?php
	    }
	    else if ($avatarSrc == "")
	    {
	        ?>
	        <img class=<?=$avatarThemeName?> src="" alt="">
	        <?php
	    }
	    else
	    {
	        ?>
	        <img class=<?=$avatarThemeName?> src="<?=$relativePath?><?=$avatarThemeName?>01col01.svg" alt="">
	        <?php
	    }
	}
	$avatarContent = ob_get_clean();

	return $avatarContent;
}