<?php
////	INIT
@define("MODULE_NOM","lien");
@define("MODULE_PATH","module_lien");
require_once "../includes/global.inc.php";
$config["module_espace_options"]["lien"] = array();
$objet["lien_dossier"]	= array("type_objet"=>"lien_dossier", "cle_id_objet"=>"id_dossier", "type_contenu"=>"lien", "cle_id_contenu"=>"id_lien", "table_objet"=>"gt_lien_dossier");
$objet["lien"]			= array("type_objet"=>"lien", "cle_id_objet"=>"id_lien", "type_conteneur"=>"lien_dossier", "cle_id_conteneur"=>"id_dossier", "table_objet"=>"gt_lien");
$objet["lien_dossier"]["champs_recherche"]	= array("nom","description");
$objet["lien"]["champs_recherche"]			= array("adresse","description");
$objet["lien"]["tri"]		= array("date_crea@@desc","date_crea@@asc","date_modif@@desc","date_modif@@asc","id_utilisateur@@asc","id_utilisateur@@desc","description@@asc","description@@desc","adresse@@asc","adresse@@desc");
patch_dossier_racine($objet["lien_dossier"]);


////	SUPPRESSION D'UN LIEN
////
function suppr_lien($id_lien)
{
	global $objet;
	if(droit_acces($objet["lien"],$id_lien) >= 2)	suppr_objet($objet["lien"], $id_lien);
}


////	SUPPRESSION D'UN DOSSIER
////
function suppr_lien_dossier($id_dossier)
{
	global $objet;
	if(droit_acces($objet["lien_dossier"],$id_dossier)==3 && $id_dossier>1)
	{
		// on créé la liste des dossiers & on supprime chaque dossier
		$liste_dossiers_suppr = arborescence($objet["lien_dossier"], $id_dossier, "tous");
		foreach($liste_dossiers_suppr as $dossier_tmp)
		{
			// On supprime chaque lien du dossier puis le dossier en question
			$liste_liens = db_tableau("SELECT * FROM gt_lien WHERE id_dossier=".intval($dossier_tmp["id_dossier"]));
			foreach($liste_liens as $infos_lien)	{ suppr_lien($infos_lien["id_lien"]); }
			suppr_objet($objet["lien_dossier"], $dossier_tmp["id_dossier"]);
		}
	}
}


////	DEPLACEMENT D'UN LIEN
////
function deplacer_lien($id_lien, $id_dossier_destination)
{
	global $objet;
	////	Accès en écriture au lien et au dossier de destination
	if(droit_acces($objet["lien"],$id_lien)>=2  &&  droit_acces($objet["lien_dossier"],$id_dossier_destination)>=2)
	{
		////	Si on deplace à la racine, on donne les droits d'accès de l'ancien dossier
		racine_copie_droits_acces($objet["lien"], $id_lien, $objet["lien_dossier"], $id_dossier_destination);
		////	On déplace le lien
		db_query("UPDATE gt_lien SET id_dossier=".db_format($id_dossier_destination)." WHERE id_lien=".db_format($id_lien));
	}
	////	Logs
	add_logs("modif", $objet["lien"], $id_lien);
}


////	DEPLACEMENT D'UN DOSSIER
////
function deplacer_lien_dossier($id_dossier, $id_dossier_destination)
{
	global $objet;
	////	Accès total au dossier en question  &  accès en écriture au dossier destination  &  controle du déplacement du dossier
	if(droit_acces($objet["lien_dossier"],$id_dossier)==3  &&  droit_acces($objet["lien_dossier"],$id_dossier_destination)>=2  &&  controle_deplacement_dossier($objet["lien_dossier"],$id_dossier,$id_dossier_destination)==1) {
		db_query("UPDATE gt_lien_dossier SET id_dossier_parent=".db_format($id_dossier_destination)." WHERE id_dossier=".db_format($id_dossier));
	}
	////	Logs
	add_logs("modif", $objet["lien_dossier"], $id_dossier);
}
?>