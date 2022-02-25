<?php
////	INIT
require "commun.inc.php";


////	INFOS + DROIT ACCES + LOGS
////
$fichier_tmp = objet_infos($objet["fichier"],$_GET["id_fichier"]);
$droit_acces = droit_acces_controler($objet["fichier"], $fichier_tmp, 1);
$fichier_derniere_version = infos_version_fichier($fichier_tmp["id_fichier"]);
$chemin_fichier = PATH_MOD_FICHIER.chemin($objet["fichier_dossier"],$fichier_tmp["id_dossier"],"url").$fichier_derniere_version["nom_reel"];
add_logs("consult", $objet["fichier"], $_GET["id_fichier"]);


////	TEXTE
////
if(controle_fichier("text",$chemin_fichier) || controle_fichier("web",$chemin_fichier))
{
	require_once PATH_INC."header.inc.php";
	echo "<script> resizePopupLightbox('50%','50%'); </script>";
	echo "<div style='font-weight:bold;padding:8px;'>";
		echo "<fieldset class='fieldset_titre'>".$fichier_tmp["nom"]."</fieldset>";
		echo nl2br(implode("",file($chemin_fichier)));
	echo "</div>";
	require PATH_INC."footer.inc.php";
}

////	PDF
////
elseif(controle_fichier("pdf",$chemin_fichier))
{
	// Headers et envoi
	header("Accept-Ranges: bytes");
	header("Content-Type: application/pdf");
	header("Content-Length: ".filesize($chemin_fichier));
	header("Content-Disposition: inline; filename=\"".$fichier_tmp["nom"]."\"");
	readfile($chemin_fichier);
}
?>