<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";

////	INFOS + DROIT ACCES + LOGS + MASQUE LES DETAILS ?
$evt_tmp = objet_infos($objet["evenement"],$_GET["id_evenement"]);
$evt_tmp["droit_acces"] = droit_acces($objet["evenement"],$_GET["id_evenement"],false);
if($evt_tmp["droit_acces"]==0)	{exit();}
add_logs("consult", $objet["evenement"], $_GET["id_evenement"]);
$evt_tmp = masque_details_evt($evt_tmp);

////	ENTETE DU POPUP  (DATE + PERIODICITE + CATEGORIE + MODIF + ...)
$titre_evenement = "<table class='table_nospace' cellpadding='0' cellspacing='0' style='width:100%;font-size:12px;line-height:15px;'><tr ".infobulle(txt_affections_evt($evt_tmp))." >";
	$titre_evenement .= "<td style='text-align:left;'>";
		////	DATE DE L'EVENEMENT
		$titre_evenement .= "<div>".temps($evt_tmp["date_debut"],"complet",$evt_tmp["date_fin"])."</div>";
		////	PERIODICITE
		$titre_evenement .= periodicite_evt($evt_tmp);
		if($evt_tmp["period_date_fin"]!="")		{$titre_evenement .= "<br>".$trad["AGENDA_period_date_fin"]." : ".temps($evt_tmp["period_date_fin"],"date");}
		////	CATEGORIE
		if($evt_tmp["id_categorie"] > 0){
			$infos_cat = db_ligne("SELECT * FROM gt_agenda_categorie WHERE id_categorie='".$evt_tmp["id_categorie"]."'");
			$titre_evenement .= "<div style='color:".$infos_cat["couleur"].";'>".$trad["AGENDA_categorie"]." : ".$infos_cat["titre"]."</div>";
		}
		////	VISIBILITE
		if($evt_tmp["visibilite_contenu"]=="public_cache")	{ $text_visibilite = $trad["AGENDA_visibilite_public_cache"]; }
		elseif($evt_tmp["visibilite_contenu"]=="prive")		{ $text_visibilite = $trad["AGENDA_visibilite_prive"]; }
		if(@$text_visibilite!="")	{$titre_evenement .= "<div>".$trad["AGENDA_visibilite"]." : ".$text_visibilite."</div>";}
	$titre_evenement .= "</td>";
	$titre_evenement .= "<td style='text-align:right;padding-right:10px;'>";
		////	MODIFIER
		if($evt_tmp["droit_acces"]>=2)	{$titre_evenement .= "<div class='lien' style='".STYLE_SELECT_RED."' onClick=\"popupLightbox('evenement_edit.php?id_evenement=".$evt_tmp["id_evenement"]."');\">".$trad["modifier"]." <img src=\"".PATH_TPL."divers/crayon.png\" style='height:16px;' /></div>";}
	$titre_evenement .= "</td>";
$titre_evenement .= "</tr></table>";
?>


<script type="text/javascript">resizePopupLightbox(500,350);</script>
<style type="text/css">  body { background-image:url('<?php echo PATH_TPL; ?>module_agenda/fond_popup.png'); }  </style>


<?php
////	ENTETE
echo "<fieldset class='fieldset_titre'>".$titre_evenement."</fieldset>";

////	TITRE & DESCRIPTION
echo "<div style='margin-top:20px;padding:10px;font-weight:bold;height:140px;' ".infobulle(txt_affections_evt($evt_tmp)).">";
	////	IMPORTANT
	if($evt_tmp["important"] > 0)	{echo "<img src=\"".PATH_TPL."divers/important_small.png\" style='margin-right:5px;' />";}
	////	PLAGE HORAIRE + DESCRIPTION
	echo $evt_tmp["titre"]."<div style='margin-top:10px;font-weight:normal;'>".nl2br($evt_tmp["description"])."</div>";
echo "</div>";

////	FICHIERS JOINTS + FOOTER
affiche_fichiers_joints($objet["evenement"], $_GET["id_evenement"], "popup");
require PATH_INC."footer.inc.php";
?>