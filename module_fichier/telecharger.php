<?php
////	INIT
require "commun.inc.php";

////	INFOS + DROIT ACCES + LOGS
$infos_fichier = objet_infos($objet["fichier"],$_GET["id_fichier"]);
droit_acces_controler($objet["fichier"], $infos_fichier, 1);
if(!isset($_GET["date"]))	{$infos_version_fichier=infos_version_fichier($_GET["id_fichier"]);}
else{
	$infos_version_fichier = infos_version_fichier($_GET["id_fichier"],urldecode($_GET["date"]));
	$infos_fichier["nom"] = $infos_version_fichier["nom"];
}
$chemin_fichier = PATH_MOD_FICHIER.chemin($objet["fichier_dossier"],$infos_fichier["id_dossier"],"url").$infos_version_fichier["nom_reel"];
add_logs("consult2", $objet["fichier"], $_GET["id_fichier"]);

////	FICHIER ACCESSIBLE ?
if(!is_file($chemin_fichier)){
	alert($trad["MSG_ALERTE_acces_fichier"]." : ".$infos_fichier["nom"]);
	redir("index.php?id_dossier=".$infos_fichier["id_dossier"]);
}

////	LANCEMENT DU TELECHARGEMENT
telecharger($infos_fichier["nom"], $chemin_fichier);
?>