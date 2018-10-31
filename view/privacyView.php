<?php
ob_start();
?>
<div id="privacy" class="privacy">
	<p>Ce site utilise des cookies pour vous assurer d'obtenir la meilleure expérience sur notre site <a href="http://www.allaboutcookies.org/fr/" target="_blank" rel="noopener">Plus d'infos</a></p>
	<button id="privacyClose" class="formButton privacyClose">Compris!</button>
</div>
<?php
$privacy = ob_get_clean();