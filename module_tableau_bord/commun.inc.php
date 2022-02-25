<?php
////	INIT
@define("MODULE_NOM","tableau_bord");
@define("MODULE_PATH","module_tableau_bord");
require_once "../includes/global.inc.php";
$config["module_espace_options"]["tableau_bord"] = array("ajout_actualite_admin");
$objet["actualite"] = array("type_objet"=>"actualite", "cle_id_objet"=>"id_actualite", "table_objet"=>"gt_actualite");
$objet["actualite"]["champs_recherche"] = array("description");
$objet["actualite"]["tri"] = array("date_crea@@desc","date_crea@@asc","date_modif@@desc","date_modif@@asc","id_utilisateur@@asc","id_utilisateur@@desc","description@@asc","description@@desc");


////	SUPPRESSION D'UNE ACTUALITE
////
function suppr_actualite($id_actualite)
{
	global $objet;
	if(droit_acces($objet["actualite"],$id_actualite)>=2)	suppr_objet($objet["actualite"], $id_actualite);
}


////	DROIT AJOUT ACTUALITE
////
function droit_ajout_actualite()
{
	if($_SESSION["user"]["id_utilisateur"]>0  &&  (option_module("ajout_actualite_admin")!=true || $_SESSION["espace"]["droit_acces"]==2))  return true;
}
?>
