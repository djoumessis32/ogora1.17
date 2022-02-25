<?php
////	INIT
require "commun.inc.php";
header("Content-type: text/html; charset=UTF-8"); // Mode AJAX Oblige...


////	INFOS + DROIT ACCES + LOGS
////
$fichier_tmp = objet_infos($objet["fichier"],$_REQUEST["id_fichier"]);
$fichier_version_tmp = infos_version_fichier($_REQUEST["id_fichier"]);
droit_acces_controler($objet["fichier"], $fichier_tmp, 1);
add_logs("consult", $objet["fichier"], $_GET["id_fichier"]);
if(!isset($_REQUEST["rotation"]))	$_REQUEST["rotation"] = 0;


////	CHEMIN DU FICHIER
////
$chemin_fichier = PATH_MOD_FICHIER.chemin($objet["fichier_dossier"],$fichier_tmp["id_dossier"],"url").$fichier_version_tmp["nom_reel"];
$chemin_fichier_src = ($_REQUEST["rotation"]>0)  ?  "image_rotation.php?rotation=".$_REQUEST["rotation"]."&chemin_fichier=".urlencode($chemin_fichier)  :  $chemin_fichier;


////	DIMENSION DE L'IMAGE PRECEDANTE  &  ROTATION
////
if(preg_match("/90|270/", $_REQUEST["rotation"]))	{ list($img_hauteur, $img_largeur) = getimagesize($chemin_fichier); }
else												{ list($img_largeur, $img_hauteur) = getimagesize($chemin_fichier); }
$rotation_gauche = ($_REQUEST["rotation"]=="270")  ?  "0"  :  ($_REQUEST["rotation"]+90);
$rotation_droite = ($_REQUEST["rotation"]==0)  ?  "270"  :  ($_REQUEST["rotation"]-90);


////	IMAGE PRECEDANTE / SUIVANTE
////
$cpt_derniere_img = count($_SESSION["cfg"]["espace"]["scroller_images"]) - 1;
foreach($_SESSION["cfg"]["espace"]["scroller_images"] as $cpt_img => $infos_image)
{
	if($infos_image["id_fichier"]==$_REQUEST["id_fichier"]) {
		// Image precedante (dernière de la liste ou n-1)
		if($cpt_img==0)		{ $id_img_pre = $_SESSION["cfg"]["espace"]["scroller_images"][$cpt_derniere_img]["id_fichier"]; }
		else				{ $id_img_pre = $_SESSION["cfg"]["espace"]["scroller_images"][$cpt_img-1]["id_fichier"]; }
		// Image suivante (première de la liste ou n+1)
		if($cpt_img==$cpt_derniere_img)		{ $id_img_suiv = $_SESSION["cfg"]["espace"]["scroller_images"][0]["id_fichier"]; }
		else								{ $id_img_suiv = $_SESSION["cfg"]["espace"]["scroller_images"][$cpt_img+1]["id_fichier"]; }
	}
}


////	AFFICHAGE DE L'IMAGE !
////
echo "<img src=\"".$chemin_fichier_src."\" id='image' style='width:".$img_largeur."px;height:".$img_hauteur."px;' class='lien' onClick=\"affiche_img('".$id_img_suiv."');\" title=\"".$trad["FICHIER_img_suivante"]."\" />";


////	DONNEES DE L'IMAGE EN INPUT (Ajax oblige..)
////
echo "<input type='hidden' id='id_fichier' value=\"".$fichier_tmp["id_fichier"]."\" />";
echo "<input type='hidden' id='image_width_reference' value=\"".$img_largeur."\" />";
echo "<input type='hidden' id='image_height_reference' value=\"".$img_hauteur."\" />";
echo "<input type='hidden' id='id_img_pre' value=\"".$id_img_pre."\" />";
echo "<input type='hidden' id='id_img_suiv' value=\"".$id_img_suiv."\" />";
echo "<input type='hidden' id='rotation_gauche' value=\"".$rotation_gauche."\" />";
echo "<input type='hidden' id='rotation_droite' value=\"".$rotation_droite."\" />";


////	MENU PRINCIPAL DE L'IMAGE
////
echo "<div class='noprint' style='position:fixed;bottom:20px;z-index:1000;width:100%;left:0px;text-align:center;'>";
////	INFOS (parametrage HTTPS+IE) + TELECHARGER + IMPRIMER
if(defined("HOST_DOMAINE") && preg_match("/MSIE/i",$_SERVER["HTTP_USER_AGENT"]))	{echo "<img src=\"".PATH_TPL."divers/info.png\" class='lien icone' ".infobulle($trad["FICHIER_info_https_flash"])." /> &nbsp; ";}
echo "<a href=\"telecharger.php?id_fichier=".$fichier_tmp["id_fichier"]."\"><img src=\"".PATH_TPL."divers/telecharger.png\" class='lien icone' ".infobulle($trad["telecharger"])." /></a> &nbsp; ";
echo "<img src=\"".PATH_TPL."divers/imprimer.png\" class='lien icone' onClick=\"window.print();\" ".infobulle($trad["imprimer"])." /> &nbsp; ";
////	ROTATION  +  IMAGE PRECEDENTE  +  IMAGE SUIVANTE
if(function_exists("imagerotate")){
	echo "<img src=\"".PATH_TPL."module_fichier/rotation_gauche.png\" class='lien icone' onClick=\"affiche_img('".$fichier_tmp["id_fichier"]."', 0, '".$rotation_gauche."');\" ".infobulle($trad["FICHIER_rotation_gauche"])." /> &nbsp; ";
	echo "<img src=\"".PATH_TPL."module_fichier/rotation_droite.png\" class='lien icone' onClick=\"affiche_img('".$fichier_tmp["id_fichier"]."', 0, '".$rotation_droite."');\" ".infobulle($trad["FICHIER_rotation_droite"])." /> &nbsp; ";
}
echo "<img src=\"".PATH_TPL."module_fichier/precedent.png\" class='lien icone' onClick=\"affiche_img('".$id_img_pre."');\" ".infobulle($trad["FICHIER_img_precedante"])." /> &nbsp; ";
echo "<img src=\"".PATH_TPL."module_fichier/suivant.png\" class='lien icone' onClick=\"affiche_img('".$id_img_suiv."');\" ".infobulle($trad["FICHIER_img_suivante"])." /> &nbsp; ";
////	ZOOM / DEZOOM
echo "<img src=\"".PATH_TPL."divers/recherche.png\" class='lien icone' id='icone_zoom' onClick=\"redimentionne_img('zoom');\" ".infobulle($trad["FICHIER_zoom"])." /> &nbsp; ";
////	DIAPORAMA : LANCER / PAUSE
echo "<img src=\"".PATH_TPL."module_fichier/diaporama_lecture.png\" class='lien icone' id='icone_lect_diapo' onClick=\"lance_diaporama(true);\"  ".(@$_REQUEST["diaporama"]=="1"?"style='display:none;'":"")."  ".infobulle($trad["FICHIER_defiler_images"])." /> &nbsp; ";
echo "<img src=\"".PATH_TPL."module_fichier/pause.png\" class='lien icone' id='icone_stop_diapo' onClick=\"lance_diaporama(false);\"  ".(@$_REQUEST["diaporama"]=="1"?"":"style='display:none;'")." /> &nbsp; ";
echo "</div>";
?>