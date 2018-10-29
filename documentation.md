# Documentation

## Ajouter une Nouvelle Application

1. ajouter le dossier contenant la nouvelle application dans le dossier library.
2. un fichier index.php et cover.jpg doivent être présents à la racine du dossier de l'application.
3. ajouter les informations (nom de l'application, description et dossier) dans la bdd/pe_library.
4. pour activer l'application côté élève, il faut lier l'id de la classroom à l'id de l'application dans la bdd/pe_rel_cr_library. Cela permet à l'admin de choisi quelles seront les applications activées.

## Authentification

Le fichier d'authentification "auth.php" se trouve dans le dossier "model" à la racine du site

	function checkSession()
	{
		$auth = new Authentification;
		$sessionResult = $auth->checkSession();
	    if ($sessionResult == 'admin')
		{
			... 		
		}
		else if ($sessionResult == 'student')
		{
			...
		}
		else
		{
			...
		}
	}
	checkSession();
