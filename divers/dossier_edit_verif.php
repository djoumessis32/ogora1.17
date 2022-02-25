<?php
////	VERIFIE S'IL N'Y A PAS DEJA UN DOSSIER DANS LE MEME REPERTOIRE, LE MEME ESPACE ET PORTANT LE MEME NOM
////
require_once "../".$_REQUEST["module_path"]."/commun.inc.php";
$objet_dossier = $objet[$_REQUEST["type_objet_dossier"]];
$nb_dossiers_identiques = db_valeur("SELECT count(*) FROM ".$objet_dossier["table_objet"]." WHERE id_dossier_parent='".intval($_REQUEST["id_dossier_parent"])."' AND id_dossier!='".intval($_REQUEST["id_dossier"])."' AND nom='".$_REQUEST["nom"]."' AND id_dossier in (select id_objet as id_dossier from gt_jointure_objet where type_objet='".$objet_dossier["type_objet"]."' and id_espace='".$_SESSION["espace"]["id_espace"]."') ");
if($nb_dossiers_identiques > 0)		echo "oui";
?>