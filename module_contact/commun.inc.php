<?php
////	INIT
@define("MODULE_NOM","contact");
@define("MODULE_PATH","module_contact");
require_once "../includes/global.inc.php";
$objet["contact_dossier"]	= array("type_objet"=>"contact_dossier", "cle_id_objet"=>"id_dossier", "type_contenu"=>"contact", "cle_id_contenu"=>"id_contact", "table_objet"=>"gt_contact_dossier");
$objet["contact"]			= array("type_objet"=>"contact", "cle_id_objet"=>"id_contact", "type_conteneur"=>"contact_dossier", "cle_id_conteneur"=>"id_dossier", "table_objet"=>"gt_contact");
$objet["contact_dossier"]["champs_recherche"]	= array("nom","description");
$objet["contact"]["champs_recherche"]			= array("nom","prenom","adresse","codepostal","ville","pays","competences","hobbies","fonction","societe_organisme","commentaire");
$objet["contact"]["tri"] = modif_tri_defaut_personnes(array("nom@@asc","nom@@desc","prenom@@asc","prenom@@desc","civilite@@asc","civilite@@desc","date_crea@@desc","date_crea@@asc","date_modif@@desc","date_modif@@asc","codepostal@@asc","codepostal@@desc","ville@@asc","ville@@desc","pays@@asc","pays@@desc","fonction@@asc","fonction@@desc","societe_organisme@@asc","societe_organisme@@desc","id_utilisateur@@asc","id_utilisateur@@desc"));
patch_dossier_racine($objet["contact_dossier"]);


////	SUPPRESSION D'UN CONTACT
////
function suppr_contact($id_contact)
{
	global $objet;
	// On supprime l'objet en question
	if(droit_acces($objet["contact"],$id_contact) >= 2)
	{
		$photo = db_valeur("SELECT photo FROM gt_contact WHERE id_contact='".intval($id_contact)."'");
		@unlink(PATH_PHOTOS_CONTACT.$photo);
		suppr_objet($objet["contact"],$id_contact);
	}
}


////	SUPPRESSION D'UN DOSSIER
////
function suppr_contact_dossier($id_dossier)
{
	global $objet;
	if(droit_acces($objet["contact_dossier"],$id_dossier)==3 && $id_dossier > 1)
	{
		// on créé la liste des dossiers & on supprime chaque dossier
		$liste_dossiers_suppr = arborescence($objet["contact_dossier"],$id_dossier,"tous");
		foreach($liste_dossiers_suppr as $infos_dossier)
		{
			// On supprime chaque contact du dossier puis le dossier en question
			$liste_contacts = db_tableau("SELECT * FROM gt_contact WHERE id_dossier='".$infos_dossier["id_dossier"]."'");
			foreach($liste_contacts as $infos_contact)	{ suppr_contact($infos_contact["id_contact"]); }
			suppr_objet($objet["contact_dossier"], $infos_dossier["id_dossier"]);
		}
	}
}


////	DEPLACEMENT D'UN CONTACT
////
function deplacer_contact($id_contact, $id_dossier_destination)
{
	global $objet;
	////	Accès en écriture au contact et au dossier de destination
	if(droit_acces($objet["contact"],$id_contact)>=2  &&  droit_acces($objet["contact_dossier"],$id_dossier_destination)>=2)
	{
		////	Deplace à la racine : donne les droits d'accès de l'ancien dossier
		racine_copie_droits_acces($objet["contact"], $id_contact, $objet["contact_dossier"], $id_dossier_destination);
		////	On déplace le contact
		db_query("UPDATE gt_contact SET id_dossier=".intval($id_dossier_destination)." WHERE id_contact=".db_format($id_contact));
	}
	////	Logs
	add_logs("modif", $objet["contact"], $id_contact);
}


////	DEPLACEMENT D'UN DOSSIER
////
function deplacer_contact_dossier($id_dossier, $id_dossier_destination)
{
	global $objet;
	////	Accès total au dossier en question  &  accès en écriture au dossier destination  &  controle du déplacement du dossier
	if(droit_acces($objet["contact_dossier"],$id_dossier)==3  &&  droit_acces($objet["contact_dossier"],$id_dossier_destination)>=2  &&  controle_deplacement_dossier($objet["contact_dossier"],$id_dossier,$id_dossier_destination)==1) {
		db_query("UPDATE gt_contact_dossier SET id_dossier_parent=".db_format($id_dossier_destination)." WHERE id_dossier=".intval($id_dossier));
	}
	////	Logs
	add_logs("modif", $objet["contact_dossier"], $id_dossier);
}
?>