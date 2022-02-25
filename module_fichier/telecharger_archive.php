<?php
////	INIT
require "commun.inc.php";
require_once "../divers/ziplib.php";
ini_set("max_execution_time","600");
$tab_id_fichiers = $tab_fichiers_zip = array();
$id_dossier_from = $nom_archive = null;


////	AJOUTE TOUS LES FICHIERS A TELECHARGER
////
//Parcourt chaque dossier sélectionné (recursivement) et ajoute les fichiers contenus
if(count(SelectedElemsArray("fichier_dossier"))>0)
{
	foreach(SelectedElemsArray("fichier_dossier") as $id_dossier)
	{
		//controle que le dossier ne soit pas trop volumineux
		controle_big_download(PATH_MOD_FICHIER.chemin($objet["fichier_dossier"],$id_dossier,"url"));
		//ajoute les fichiers du dossier courant
		foreach(arborescence($objet["fichier_dossier"],$id_dossier) as $dossier_tmp){
			$ids_fichier_dossier_tmp = db_colonne("SELECT id_fichier FROM gt_fichier WHERE id_dossier=".intval($dossier_tmp["id_dossier"]));
			$tab_id_fichiers = array_merge($tab_id_fichiers, $ids_fichier_dossier_tmp);
		}
		//Ajoute le dossier depuis lequel le script a été lancé?
		if(empty($id_dossier_from))	{$id_dossier_from=objet_infos($objet["fichier_dossier"],$id_dossier,"id_dossier_parent");}
	}
}
//Ajoute les fichiers sélectionnés
if(count(SelectedElemsArray("fichier"))>0)
{
	//Ajoute le dossier depuis lequel le script a été lancé?
	if(empty($id_dossier_from))	{
		foreach(SelectedElemsArray("fichier") as $id_fichier)	{$id_dossier_from=objet_infos($objet["fichier"],$id_fichier,"id_dossier");}
	}
	$tab_id_fichiers = array_merge($tab_id_fichiers, SelectedElemsArray("fichier"));
}


////	PREPARE CHAQUE FICHIER A L'AJOUT DANS L'ARCHIVE
////
foreach($tab_id_fichiers as $id_fichier)
{
	$fichier_tmp = objet_infos($objet["fichier"],$id_fichier);
	if(droit_acces($objet["fichier"],$fichier_tmp) > 0)
	{
		// Chemin réel
		$dernier_fichier = infos_version_fichier($id_fichier);
		$fichier_tmp["path_source"] = PATH_MOD_FICHIER.chemin($objet["fichier_dossier"],$fichier_tmp["id_dossier"],"url").$dernier_fichier["nom_reel"];
		// chemin du fichier dans le zip (on supprime l'arborescence "primaire" & le premier "\" ou "/")
		$chemin_zip = "";
		if(!empty($id_dossier_from))
		{
			$chemin_zip_parent = chemin($objet["fichier_dossier"],$id_dossier_from,"url_zip");
			$chemin_zip = chemin($objet["fichier_dossier"],$fichier_tmp["id_dossier"],"url_zip");
			$chemin_zip = substr($chemin_zip, strlen($chemin_zip_parent)-1);
			if(substr($chemin_zip,0,1)=="\\" || substr($chemin_zip,0,1)=="/")	{$chemin_zip = substr($chemin_zip,1);}
		}
		$fichier_tmp["path_zip"] = $chemin_zip.suppr_carac_spe($fichier_tmp["nom"],"normale");
		// Nom de l'archive = nom du dossier depuis lequel le script a été lancé
		if(!is_dossier_racine($objet["fichier_dossier"],$id_dossier_from) && empty($nom_archive))	{$nom_archive=objet_infos($objet["fichier_dossier"],$id_dossier_from,"nom").".zip";}
		// Ajoute le fichier
		$tab_fichiers_zip[] = $fichier_tmp;
		//Logs
		add_logs("consult2", $objet["fichier"], $id_fichier);
	}
}


////	CREATION DE L'ARCHIVE
////
if(empty($nom_archive))	{$nom_archive="archive.zip";}
if(count($tab_fichiers_zip)>0)	{creer_envoyer_archive($tab_fichiers_zip,$nom_archive);}
else							{echo "<script> alert(\"dossier vide / empty folder\"); window.close(); </script>";}