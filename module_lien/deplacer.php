<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";//pour charger la lib. javascript..

////	ON DEPLACE PLUSIEURS ELEMENTS
foreach(SelectedElemsArray("lien") as $id_lien)				{ deplacer_lien($id_lien, $_POST["id_dossier"]); }
foreach(SelectedElemsArray("lien_dossier") as $id_dossier)	{ deplacer_lien_dossier($id_dossier, $_POST["id_dossier"]); }

////		DECONNEXION À LA BDD & FERMETURE
reload_close();
?>