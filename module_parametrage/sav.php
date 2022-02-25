<?php
////	INIT
require "commun.inc.php";
ini_set("max_execution_time","1800");//30mn
nettoyer_tmp();
if(!isset($_GET["savbdd"]))  {controle_big_download(PATH_STOCK_FICHIERS);}


////	BACKUP BDD  /  BACKUP BDD + STOCK_FICHIERS
////
$fichier_dump = db_sauvegarde();
$tableau_fichier = array();
if(isset($_GET["savbdd"]))
{
	$tableau_fichier[] = array("path_source"=>$fichier_dump, "path_zip"=>str_replace(PATH_STOCK_FICHIERS,"",$fichier_dump));
	creer_envoyer_archive($tableau_fichier, "BackupAgoraBdd_".strftime("%d%m%Y").".zip");
}
else
{
	$nom_archive = "BackupAgora_".strftime("%d%m%Y");
	$archive_cree=false;
	////	SAUVEGARDE EN LIGNE DE COMMANDE (Linux + 100Mo minimum (100*1048576))
	if(function_exists("shell_exec") && taille_stock_fichier() > (100*1048576))
	{
		// Créé la sauvegarde puis l'envoi
		$chemin_archive  = PATH_TMP.$nom_archive.".tar";
		$chemin_archive2 = "./tmp/".$nom_archive.".tar";
		shell_exec("cd ".PATH_STOCK_FICHIERS."; tar --exclude='tmp' -cf ".$chemin_archive2." *");  // -c=creation -f=nom du fichier
		telecharger($nom_archive.".tar", $chemin_archive);
		// vérifie que le fichier de sauvegarde existe, puis le supprime
		$archive_cree = is_file($chemin_archive);
		if($archive_cree==true)	{unlink($chemin_archive);}
	}
	////	SINON SAUVEGARDE EN PHP
	if($archive_cree==false)
	{
		foreach(dossiers_fichiers_sav(PATH_STOCK_FICHIERS) as $chemin_elem)
		{
			$fichier_tmp["path_source"] = $chemin_elem;
			if($chemin_elem==$fichier_dump)		{$chemin_elem = str_replace(PATH_STOCK_FICHIERS, "", $chemin_elem);}  // Place le fichier dump SQl à la racine de l'archive
			$fichier_tmp["path_zip"]  = str_replace(ROOT_PATH, "", $chemin_elem);
			$tableau_fichier[] = $fichier_tmp;
		}
		creer_envoyer_archive($tableau_fichier, $nom_archive.".zip");
	}
}


////	SUPPR DES FICHIERS TMP & FERME MYSL
////
unlink($fichier_dump);
db_close();
?>