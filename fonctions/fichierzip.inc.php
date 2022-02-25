<?php
////	CREATION D'ARCHIVE VIA ZIPARCHIVE
////	>>>>  include de "creer_envoyer_archive()" car PHP4 pose des erreurs avec les "static" de la POO!

////	Création de l'archive
$path_archive = (defined("HOST_DOMAINE") ? sys_get_temp_dir()."/" : PATH_TMP).uniqid(mt_rand()).".zip";// sys_get_temp_dir() = PHP5.2+
$zip = new ZipArchive();
$zip->open($path_archive, ZipArchive::CREATE);

////	Ajout de chaque dossier / fichier à l'archive
foreach($tab_fichiers as $elem){
	if(is_dir($elem["path_source"]))		{$zip->addFile($fichier_vide, $elem["path_zip"]."/.void");}
	elseif(is_file($elem["path_source"]))	{$zip->addFile($elem["path_source"], $elem["path_zip"]);}
}
$zip->close();

////	Envoi du zip, puis suppression
@chmod($path_archive,0775);
telecharger($nom_archive, $path_archive, false);
unlink($path_archive);
?>