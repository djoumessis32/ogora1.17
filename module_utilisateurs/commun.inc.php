<?php
////	INIT
@define("MODULE_NOM","utilisateurs");
@define("MODULE_PATH","module_utilisateurs");
require_once "../includes/global.inc.php";
$config["module_espace_options"]["utilisateurs"] = array("ajout_utilisateurs_groupe");
$objet["utilisateur"] = array("type_objet"=>"utilisateur", "cle_id_objet"=>"id_utilisateur", "table_objet"=>"gt_utilisateur");
$objet["utilisateur"]["champs_recherche"] = array("nom","prenom","adresse","codepostal","ville","pays","competences","hobbies","fonction","societe_organisme","commentaire");
$objet["utilisateur"]["tri"] = modif_tri_defaut_personnes(array("nom@@asc","nom@@desc","prenom@@asc","prenom@@desc","civilite@@asc","civilite@@desc","codepostal@@asc","codepostal@@desc","ville@@asc","ville@@desc","pays@@asc","pays@@desc","fonction@@asc","fonction@@desc","societe_organisme@@asc","societe_organisme@@desc"));


////	UTILISATEURS DU SITE / DE L'ESPACE COURANT
////
if(isset($_GET["affichage_users"])){
	if($_GET["affichage_users"]=="site" && $_SESSION["user"]["admin_general"]==1)						{ $_SESSION["cfg"]["espace"]["affichage_users"] = "site"; }
	elseif($_GET["affichage_users"]=="espace" || !isset($_SESSION["cfg"]["espace"]["affichage_users"]))	{ $_SESSION["cfg"]["espace"]["affichage_users"] = "espace"; }
}




////	DROIT DE MODIF D'UN UTILISATEUR (proprio ou admin général)
////
function droit_modif_utilisateur($id_utilisateur, $controle_acces=false)
{
	$droit = 0;
	if($_SESSION["user"]["id_utilisateur"]==$id_utilisateur || $_SESSION["user"]["admin_general"]==1)	$droit = 1;
	if($droit==0 && $controle_acces==true)	exit();
	return $droit;
}


////	DROIT AJOUT GROUPE : UTILISATEUR LAMBDA (si option activé) OU ADMIN DE L'ESPACE COURANT
////
function droit_ajout_groupe($id_espace="")
{
	if(option_module("ajout_utilisateurs_groupe",$id_espace)==true || $_SESSION["espace"]["droit_acces"]==2)  return true;
}


////	UTILISATEURS DE L'ESPACE / DE TOUT LE SITE
////
function sql_utilisateurs_espace()
{
	if(!isset($_SESSION["cfg"]["espace"]["affichage_users"]) || $_SESSION["cfg"]["espace"]["affichage_users"]=="espace"){
		$liste_id_users = "";
		foreach(users_espace($_SESSION["espace"]["id_espace"]) as $id_users)	{ $liste_id_users .= ",".$id_users; }
		return " AND id_utilisateur IN (0".$liste_id_users.") ";
	}
}


////	SUPPRESSION / DESAFFECTATION D'UN UTILISATEUR
////
function suppr_utilisateur($id_utilisateur, $action)
{
	if($id_utilisateur > 0)
	{
		global $trad,$objet;
		////	SUPPRESSION DE L'UTILISATEUR (ADMIN GENERAL)
		if($_SESSION["user"]["admin_general"]==1 && $action=="suppression")
		{
			// Controle si c'est le dernier admin générale que l'on veut supprimer
			if(user_infos($id_utilisateur,"admin_general")==1  &&  db_valeur("SELECT count(*) FROM gt_utilisateur WHERE admin_general=1")==1)	{ alert($trad["UTILISATEURS_pas_suppr_dernier_admin_ge"]); }
			else
			{
				// Ajout dans les logs (avant suppr. en bdd : sinon auteur() ne récupère rien..)
				add_logs("suppr", $objet["utilisateur"], $id_utilisateur, auteur($id_utilisateur));
				// Suppression de la photo de l'utilisateur
				@unlink(PATH_PHOTOS_USER.db_valeur("SELECT photo FROM gt_utilisateur WHERE id_utilisateur=".db_format($id_utilisateur)));
				// Suppression de l'utilisateur, des tables de jointures et tables annexes
				db_query("DELETE FROM gt_utilisateur					WHERE id_utilisateur=".db_format($id_utilisateur));
				db_query("DELETE FROM gt_agenda							WHERE type='utilisateur' AND id_utilisateur=".db_format($id_utilisateur));
				db_query("DELETE FROM gt_jointure_espace_utilisateur	WHERE id_utilisateur=".db_format($id_utilisateur));
				db_query("DELETE FROM gt_jointure_messenger_utilisateur	WHERE id_utilisateur_messenger=".db_format($id_utilisateur)." OR id_utilisateur=".db_format($id_utilisateur));
				db_query("DELETE FROM gt_jointure_objet					WHERE target=".db_format("U".$id_utilisateur));
				db_query("DELETE FROM gt_utilisateur_livecounter		WHERE id_utilisateur=".db_format($id_utilisateur));
				db_query("DELETE FROM gt_utilisateur_messenger			WHERE id_utilisateur_expediteur=".db_format($id_utilisateur));
				db_query("DELETE FROM gt_utilisateur_preferences		WHERE id_utilisateur=".db_format($id_utilisateur));
			}
		}
		////	DESAFFECTATION DE L'UTILISATEUR A L'ESPACE (ADMIN D'ESPACE)
		elseif($_SESSION["espace"]["droit_acces"]==2 && $action=="desaffectation")
		{
			db_query("DELETE FROM gt_jointure_espace_utilisateur WHERE id_espace='".$_SESSION["espace"]["id_espace"]."' AND id_utilisateur=".db_format($id_utilisateur));
			if(db_valeur("SELECT count(*) FROM gt_jointure_espace_utilisateur WHERE id_espace='".$_SESSION["espace"]["id_espace"]."' AND tous_utilisateurs='1'")>0)
				{alert($trad["UTILISATEURS_tous_user_affecte_espace"]);}
		}
	}
}
?>