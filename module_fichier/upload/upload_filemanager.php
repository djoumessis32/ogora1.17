<?php
////	INIT
define("ROOT_PATH","../../");
require_once ROOT_PATH."includes/global.inc.php";

////	AJOUTE LES FICHIERS UPLOADES AVEC PLUPLOAD DANS UN DOSSIER TEMPORAIRE
////
if(@$_SESSION["dossier_tmp_upload"]!="" && isset($_FILES) && count($_FILES)>0)
{
	////	DOSSIER TEMPORAIRE  ("mkdir" + "chmod" -> car "mkdir($save_path,0775)" marche pas!)
	$save_path = PATH_TMP.$_SESSION["dossier_tmp_upload"];
	if(is_dir($save_path)==false)	{ mkdir($save_path);  @chmod($save_path,0775); }

	////	PLACE LE/LES FICHIERS
	foreach($_FILES as $file_tmp)
	{
		if($file_tmp["error"]==0 && is_writable($save_path) && controle_fichier("fichier_interdit",$file_tmp["name"])==false)	{ move_uploaded_file($file_tmp["tmp_name"], $save_path."/".$file_tmp["name"]); }
		elseif($num_erreur==4)																									{ echo "UPLOAD_ERR_NO_FILE"; }
		else																													{ echo $trad["MSG_ALERTE_taille_fichier"]; }
	}
}
?>