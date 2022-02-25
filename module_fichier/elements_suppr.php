<?php
////	INIT
require "commun.inc.php";
ini_set("max_execution_time","600");

////	SUPPRESSION DE CHAQUE FICHIER ET/OU DOSSIER
if(isset($_GET["id_dossier"]))		{ suppr_fichier_dossier($_GET["id_dossier"]); }
elseif(isset($_GET["id_fichier"]))	{ suppr_fichier($_GET["id_fichier"]); }
elseif(isset($_GET["SelectedElems"]))
{
	foreach(SelectedElemsArray("fichier") as $id_fichier)			{ suppr_fichier($id_fichier); }
	foreach(SelectedElemsArray("fichier_dossier") as $id_dossier)	{ suppr_fichier_dossier($id_dossier); }
}

////	Recalcule  $_SESSION["agora"]["taille_stock_fichier"]
taille_stock_fichier(true);
////	Redirection
redir("index.php?id_dossier=".$_GET["id_dossier_retour"]);
?>