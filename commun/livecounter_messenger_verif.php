<?php
////	INIT
define("GLOBAL_EXPRESS",true);
require_once "../includes/global.inc.php";

////	USER IDENTIFIE?
if(!empty($_SESSION["user"]["id_utilisateur"]) && $_SESSION["user"]["id_utilisateur"] > 0)
{
	////	INITIALISE LA DATE D'AFFICHAGE DU LIVECOUNTER  &  SUPPRIME LES ANCIENS LIVECOUNTERS  =>  TOUJOUR PLACER AVANT L'UPDATE DU LIVECOUNTER !
	if(empty($_SESSION["cfg"]["espace"]["messenger_dernier_affichage"])){
		db_query("DELETE FROM gt_utilisateur_livecounter WHERE date_verif < '".(time()-duree_livecounter)."'");
		$_SESSION["cfg"]["espace"]["messenger_dernier_affichage"] = time();
	}

	////	ON MET À JOUR LE LIVECOUNTER DE L'UTILISATEUR
	$update_ok = db_valeur("SELECT count(*) FROM gt_utilisateur_livecounter WHERE id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."'");
	if($update_ok>0)	{db_query("UPDATE gt_utilisateur_livecounter SET date_verif='".time()."' WHERE id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."'");}
	else				{db_query("INSERT INTO gt_utilisateur_livecounter SET id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."', adresse_ip='".$_SERVER["REMOTE_ADDR"]."', date_verif='".time()."'");}

	////	LE LIVECOUNTER AFFICHÉ EST DIFFÉRENT DU NOUVEAU : RELOAD !  (ya eu de nouvelles connexions/déconnexions)
	if(isset($_SESSION["cfg"]["espace"]["users_connectes"]) && users_connectes()!=$_SESSION["cfg"]["espace"]["users_connectes"]){
		echo "maj_textes_livecounters();";
	}

	////	YA DE NOUVEAU MESSAGES ?
	if(db_valeur("SELECT count(*) FROM gt_utilisateur_messenger WHERE id_utilisateur_destinataires LIKE '%@@".$_SESSION["user"]["id_utilisateur"]."@@%' AND date > '".$_SESSION["cfg"]["espace"]["messenger_dernier_affichage"]."'") > 0){
		$_SESSION["cfg"]["espace"]["messenger_alerte"]=1;
	}

	////	ALERTE D'UN NOUVEAU MESSAGE  =>  NOUVELLE OU ANCIENNE!
	if(@$_SESSION["cfg"]["espace"]["messenger_alerte"]==1){
		echo "messenger_nouveau_message();";
	}

	////	DÉCONNEXION À LA BDD
	db_close();
}
?>