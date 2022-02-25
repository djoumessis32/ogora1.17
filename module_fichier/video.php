<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";

////	INFOS + DROIT ACCES + LOGS
$fichier_tmp = objet_infos($objet["fichier"],$_REQUEST["id_fichier"]);
$fichier_version_tmp = infos_version_fichier($_REQUEST["id_fichier"]);
droit_acces_controler($objet["fichier"], $fichier_tmp, 1);
$chemin_fichier = PATH_MOD_FICHIER.chemin($objet["fichier_dossier"],$fichier_tmp["id_dossier"],"url").$fichier_version_tmp["nom_reel"];
add_logs("consult", $objet["fichier"], $_GET["id_fichier"]);
?>


<style type="text/css">  body { background-color:transparent; }  </style>


<div style="position:absolute;width:100%;height:100%;">
	<table class='table_nospace' cellpadding='0' cellspacing='0' style="width:100%;height:100%;text-align:center;">
		<tr>
			<td style="vertical-align:middle;text-align:center;" id="cadre_image">
			<?php
			////	AFFICHE LA VIDEO VIA UN PLAYER (jwplayer, etc)
			echo afficher_video($chemin_fichier);

			////	TELECHARGER / VIDEO PRECEDENTE / SUIVANTE
			////
			$cpt_derniere_img = count($_SESSION["cfg"]["espace"]["scroller_videos"]) - 1;
			foreach($_SESSION["cfg"]["espace"]["scroller_videos"] as $cpt_img => $infos_image)
			{
				if($infos_image["id_fichier"]==$_REQUEST["id_fichier"]) {
					// Video precedente (dernière de la liste ou n-1)
					$id_fichier_pre = ($cpt_img==0)  ?  $cpt_derniere_img  :  ($cpt_img-1);
					$id_fichier_pre = $_SESSION["cfg"]["espace"]["scroller_videos"][$id_fichier_pre]["id_fichier"];
					// Video suivante (première de la liste ou n+1)
					$id_fichier_suiv = ($cpt_img==$cpt_derniere_img)  ?  0  :  ($cpt_img+1);
					$id_fichier_suiv = $_SESSION["cfg"]["espace"]["scroller_videos"][$id_fichier_suiv]["id_fichier"];
				}
			}
			echo "<div style=\"margin-top:20px;font-weight:bold;\">";
			////	INFOS (parametrage HTTPS+IE) + TELECHARGER
			if(defined("HOST_DOMAINE") && preg_match("/MSIE/i",$_SERVER["HTTP_USER_AGENT"]))	{echo "<img src=\"".PATH_TPL."divers/info.png\" class=\"lien icone\" ".infobulle($trad["FICHIER_info_https_flash"])." /> &nbsp;";}
			echo "<a href=\"telecharger.php?id_fichier=".$fichier_tmp["id_fichier"]."\"><img src=\"".PATH_TPL."divers/telecharger.png\" class=\"icone\" /> ".$trad["telecharger"]."</a> &nbsp; &nbsp; ";
			////	VIDEO PRECEDENTE  +  VIDEO SUIVANTE
			echo "<img src=\"".PATH_TPL."module_fichier/precedent.png\" onClick=\"redir('".php_self()."?id_fichier=".$id_fichier_pre."');\" class=\"lien icone\" ".infobulle($trad["FICHIER_video_precedante"])." /> &nbsp;";
			echo "<img src=\"".PATH_TPL."module_fichier/suivant.png\" onClick=\"redir('".php_self()."?id_fichier=".$id_fichier_suiv."');\" class=\"lien icone\" ".infobulle($trad["FICHIER_video_suivante"])." /> &nbsp;";
			////	DESCRIPTION
			if($fichier_tmp["description"]!="")		echo "<div style=\"margin-top:20px;\"><i>".$fichier_tmp["description"]."</i></div>";
			echo "</div>";
			?>
			</td>
		</tr>
	</table>
</div>