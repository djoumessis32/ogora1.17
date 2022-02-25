<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";//pour charger la lib. javascript..

////	ON DEPLACE PLUSIEURS ELEMENTS
foreach(SelectedElemsArray("tache") as $id_tache)			{ deplacer_tache($id_tache, $_POST["id_dossier"]); }
foreach(SelectedElemsArray("tache_dossier") as $id_dossier)	{ deplacer_tache_dossier($id_dossier, $_POST["id_dossier"]); }

////	DECONNEXION À LA BDD & FERMETURE
reload_close();
?>