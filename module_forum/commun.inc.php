<?php
////	INIT
@define("MODULE_NOM","forum");
@define("MODULE_PATH","module_forum");
require_once "../includes/global.inc.php";
$config["module_espace_options"]["forum"] = array("ajout_sujet_admin","ajout_sujet_theme");
$objet["sujet"]		= array("type_objet"=>"sujet", "cle_id_objet"=>"id_sujet", "type_contenu"=>"message", "cle_id_contenu"=>"id_message", "table_objet"=>"gt_forum_sujet");
$objet["message"]	= array("type_objet"=>"message", "cle_id_objet"=>"id_message", "type_conteneur"=>"sujet", "cle_id_conteneur"=>"id_sujet", "table_objet"=>"gt_forum_message");
$objet["sujet"]["champs_recherche"]		= array("titre","description");
$objet["message"]["champs_recherche"]	= array("titre","description");
$objet["sujet"]["tri"]		= array("date_dernier_message@@desc","date_dernier_message@@asc","date_crea@@desc","date_crea@@asc","date_modif@@desc","date_modif@@asc","id_utilisateur@@asc","id_utilisateur@@desc","titre@@asc","titre@@desc","description@@asc","description@@desc");
$objet["message"]["tri"]	= array("date_crea@@asc","date_crea@@desc","date_modif@@desc","date_modif@@asc","id_utilisateur@@asc","id_utilisateur@@desc","titre@@asc","titre@@desc","description@@asc","description@@desc");
if(isset($_GET["theme"]))										{ $_SESSION["cfg"]["espace"]["forum_id_theme"] = $_GET["theme"]; }
elseif(!isset($_SESSION["cfg"]["espace"]["forum_id_theme"]))	{ $_SESSION["cfg"]["espace"]["forum_id_theme"] = ""; }


////	SUPPRESSION D'UN MESSAGE ET DES SOUS MESSAGES ASSOCIES
////
function suppr_message($id_message)
{
	global $objet;
	// Supprime si on a accès en écriture
	if(droit_acces($objet["message"],$id_message)==3)
	{
		$message_tmp = objet_infos($objet["message"],$id_message);
		// On monte d'un niveau les messages enfant : on leur donne l'id_message_parent du message supprimé
		db_query("UPDATE gt_forum_message SET id_message_parent=".db_format($message_tmp["id_message_parent"])." WHERE id_message_parent=".db_format($message_tmp["id_message"]));
		// Supprime le message en question et lance  maj_dernier_message()
		suppr_objet($objet["message"], $id_message);
		maj_dernier_message($message_tmp["id_sujet"]);
	}
}


////	SUPPRESSION DU SUJET ET DES MESSAGES ASSOCIES
////
function suppr_sujet($id_sujet)
{
	global $objet;
	if(droit_acces($objet["sujet"],$id_sujet)==3)
	{
		suppr_objet($objet["sujet"], $id_sujet);
		foreach(db_tableau("SELECT * FROM gt_forum_message WHERE id_sujet='".intval($id_sujet)."'") as $message_tmp){
			suppr_objet($objet["message"], $message_tmp["id_message"]);
		}
	}
}


////	DROIT AJOUT SUJET
////
function droit_ajout_sujet()
{
	if(option_module("ajout_sujet_admin")!=true || $_SESSION["espace"]["droit_acces"]==2)
		return true;
}


////	DROIT GESTION DES THEMES (ADMIN + USERS SI AUTORISE)
////
function droit_gestion_themes()
{
	if($_SESSION["espace"]["droit_acces"]==2 || (option_module("ajout_sujet_theme")==true && $_SESSION["user"]["id_utilisateur"]>0))
		return true;
}


////	THEMES DES SUJETS & LEUR DROIT D'ACCES ($mode = lecture / sujet_edit / theme_edit)
////
function themes_sujets($mode="lecture")
{
	// Tous les thèmes du site (gestion pour l'admin général)
	if($mode=="theme_edit" && $_SESSION["user"]["admin_general"]==1)	{ $filtre_espace = ""; }
	// Sélectionne/filtre les thèmes
	else
	{
		// Thèmes de l'espace courant
		$filtre_espace = "WHERE id_espaces is null OR id_espaces LIKE '%@@".$_SESSION["espace"]["id_espace"]."@@%'";
		// thèmes ayant des sujets dans l'espace courant (si affectation du sujet à l'espace, alors que le thème n'est pas affecté à l'espace...)
		if($mode=="lecture"){
			global $objet;
			$filtre_espace .= " OR id_theme IN (SELECT DISTINCT id_theme FROM gt_forum_sujet WHERE 1 ".sql_affichage($objet["sujet"]).")"; 
		}
	}
	$liste_themes = db_tableau("SELECT * FROM gt_forum_theme ".$filtre_espace." ORDER BY titre", "id_theme");
	// Ajoute le droit d'accès  (écriture si auteur ou admin général)
	foreach($liste_themes as $cle => $theme){
		$liste_themes[$cle]["droit"] = (is_auteur($theme["id_utilisateur"])==true || $_SESSION["user"]["admin_general"]==1)  ?  2  :  1;
	}
	return $liste_themes;
}


////	FILTRE SQL DES THEMES
////
function sql_themes()
{
	if($_SESSION["cfg"]["espace"]["forum_id_theme"]=="sans")	return " and (id_theme is null or id_theme='') ";
	elseif($_SESSION["cfg"]["espace"]["forum_id_theme"]>0)		return " and id_theme='".$_SESSION["cfg"]["espace"]["forum_id_theme"]."'";
}


////	PUCE DE COULEUR DU THEME
////
function puce_theme($themes_sujets, $id_theme="")
{
	if($id_theme=="")	$id_theme = $_SESSION["cfg"]["espace"]["forum_id_theme"];
	$couleur = ($id_theme<1) ? "#000;" : $themes_sujets[$id_theme]["couleur"];
	return "<span style='background-color:".$couleur.";border:solid 1px #777;border-radius:3px;'>&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp; ";
}


////	CHEMIN : "THEME >> SUJET"
////
function chemin_theme_sujet($themes_sujets, $titre_sujet="")
{
	// sujet.php  OU  index.php + id_theme
	if(preg_match("/sujet.php/i",$_SERVER["PHP_SELF"])  ||  $_SESSION["cfg"]["espace"]["forum_id_theme"]!="")
	{
		// Init
		global $trad;
		$separateur = " &nbsp;&nbsp; <img src=\"".PATH_TPL."divers/suivant.png\" style=\"opacity:0.70;filter:alpha(opacity=70);\" /> &nbsp;&nbsp; ";
		// affichage
		$chemin_theme_sujet = "<div class='div_menu_horizontal pas_selection' style='padding:6px;font-weight:bold;'>";
			////	ACCUEIL
			$chemin_theme_sujet .= "<img src=\"".PATH_TPL."module_forum/plugin.png\" style=\"height:22px;\" /> &nbsp; <a href=\"index.php?theme=\" class='lien'>".$trad["FORUM_accueil_forum"]."</a>";
			////	"sans theme" / thème selectionné  (ajoute la page, pour revenir après avoir vu un sujet)
			if($_SESSION["cfg"]["espace"]["forum_id_theme"]!=""){
				$libelle = ($_SESSION["cfg"]["espace"]["forum_id_theme"]=="sans")  ?  $trad["FORUM_sans_theme"]  :  db_valeur("SELECT titre FROM gt_forum_theme WHERE id_theme='".$_SESSION["cfg"]["espace"]["forum_id_theme"]."'");
				$chemin_theme_sujet .=  $separateur.puce_theme($themes_sujets)."<a href=\"index.php?num_page=".@$_SESSION["cfg"]["espace"]["num_page"]."\" class='lien'>".$libelle."</a>";
			}
			////	Titre du sujet ?
			if($titre_sujet!="")	$chemin_theme_sujet .=  $separateur.$titre_sujet;
		// Retour
		return $chemin_theme_sujet."</div><hr style='clear:both;visibility:hidden;' />";
	}
}


////	MAJ DE LA DATE ET AUTEUR DU DERNIER MESSAGE  +  REINITIALISATION DES USERS QUI L'ONT CONSULTE
////
function maj_dernier_message($id_sujet)
{
	$dernier_message = db_ligne("SELECT * FROM gt_forum_message WHERE id_sujet='".intval($id_sujet)."' ORDER BY date_crea desc");
	if(count($dernier_message)>0) {
		$auteur_dernier_message = ($dernier_message["id_utilisateur"]>0)  ?  $dernier_message["id_utilisateur"]  :  $dernier_message["invite"];
		db_query("UPDATE gt_forum_sujet SET date_dernier_message='".$dernier_message["date_crea"]."', auteur_dernier_message=".db_format($auteur_dernier_message).", users_consult_dernier_message=null WHERE id_sujet=".db_format($id_sujet));
	}
}


////	LE SUJET A-T-IL DEJA ETE CONSULTE AVEC LE DERNIER MESSAGE?
////
function is_dernier_message_consulte($users_consult_dernier_message)
{
	if($_SESSION["user"]["id_utilisateur"]>0 && preg_match("/u".$_SESSION["user"]["id_utilisateur"]."u/i",$users_consult_dernier_message)==false)
		return false;
	else
		return true;
}
?>