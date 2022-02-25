<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";
$droit_acces_objet = droit_acces_controler($objet["fichier"], $_REQUEST["id_fichier"], 1);

////	MODIF
if(isset($_REQUEST["action"]) && $_REQUEST["action"]=="modif_description" && $droit_acces_objet > 1) {
	db_query("UPDATE gt_fichier_version SET description=".db_format($_REQUEST["description"])." WHERE id_fichier=".db_format($_REQUEST["id_fichier"])." AND date_crea='".strftime("%Y-%m-%d %H:%M:%S",$_REQUEST["date"])."'");
}
////	SUPPR UNE VERSION
if(isset($_REQUEST["action"]) && $_REQUEST["action"]=="suppr_version" && $droit_acces_objet > 1) {
	suppr_fichier($_REQUEST["id_fichier"],strftime("%Y-%m-%d %H:%M:%S",$_REQUEST["date"]));
	taille_stock_fichier(true);//Recalcule $_SESSION["agora"]["taille_stock_fichier"]
	reload_close();
}

////	INFOS SUR LE FICHIER
$fichier_tmp = objet_infos($objet["fichier"],$_REQUEST["id_fichier"],"*");
$versions_fichier_tmp = infos_version_fichier($_REQUEST["id_fichier"],"toutes","date_crea asc");
if(count($versions_fichier_tmp)<2)	reload_close();
?>


<script type="text/javascript">resizePopupLightbox(500,300);</script>
<style type="text/css"> body { font-weight:bold; background-image:url('<?php echo PATH_TPL; ?>module_fichier/fond_popup.png'); } </style>


<?php
echo "<fieldset class='fieldset_titre'>".$trad["FICHIER_versions_de"]." ".$fichier_tmp["nom"]."</fieldset>";
echo "<ul style='margin:0px;'>";
////	AFFICHE LES DIFFERENTES VERSIONS
foreach($versions_fichier_tmp as $compteur => $version_tmp)
{
	echo "<li style=\"padding:5px;list-style-image:url(".PATH_TPL."divers/tri_desc.png);\">";
		////	NOM + LIEN
		echo "<a href=\"telecharger.php?id_fichier=".$_REQUEST["id_fichier"]."&date=".urlencode($version_tmp["date_crea"])."\"  ".infobulle($trad["telecharger"]." <img src='".PATH_TPL."divers/telecharger.png' />")." >";
			echo "<span style='font-size:13px;line-height:18px;'>".$version_tmp["nom"]."</span><br>";
			echo temps($version_tmp["date_crea"])." &nbsp; >> &nbsp; ".auteur(user_infos($version_tmp["id_utilisateur"]),$version_tmp["invite"])."  &nbsp; (".afficher_taille($version_tmp["taille_octet"]).")";
		echo "</a> &nbsp; ";
		////	SUPPRESSION
		if($droit_acces_objet>=2)	echo icone_suppr("versions_fichier.php?id_fichier=".$_REQUEST["id_fichier"]."&action=suppr_version&date=".strtotime($version_tmp["date_crea"]), 1, $trad["FICHIER_confirmer_suppression_version"])." &nbsp; ";
		////	DESCRIPTION
		echo "<img src=\"".PATH_TPL."divers/description.png\" OnClick=\"afficher_dynamic('description_".$compteur."');\" class='lien' title=\"".$version_tmp["description"]."\" />";
		echo "<div id=\"description_".$compteur."\" style='display:none;'>";
			echo "<form action=\"".php_self()."\" method='post'>";
				echo "<textarea name='description' style='width:98%;margin:5px;' rows='2'>".$version_tmp["description"]."</textarea>";
				echo "<input type='hidden' name='id_fichier' value='".$_REQUEST["id_fichier"]."' />";
				echo "<input type='hidden' name='date' value=\"".strtotime($version_tmp["date_crea"])."\" />";
				echo "<input type='hidden' name='action' value='modif_description' />";
				if($droit_acces_objet>=2)	 echo "<div style='text-align:right;'><input type='submit' value=\"".$trad["modifier"]."\" class='button' /></div>";
			echo "</form>";
		echo "</div>";
	echo "</li>";
}
echo "</ul>";

////	FOOTER
require PATH_INC."footer.inc.php";
?>