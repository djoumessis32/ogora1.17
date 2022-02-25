<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";

////	ON SUPPRIME UN EVENEMENT
if(isset($_GET["action"]) && $_GET["action"]=="suppr")		suppr_evenement($_GET["id_evenement"],"tous");

////	EVENEMENTS QUE J'AI CREE, MAIS DONT JE N'AI PAS ACCES
$agenda_ecriture = "id_agenda='0' OR ";
foreach($AGENDAS_AFFECTATIONS as $infos_agenda){
	if($infos_agenda["droit"]>=2)  $agenda_ecriture .= "id_agenda='".$infos_agenda["id_agenda"]."' OR ";
}
$liste_evenements_inaccessibles = db_tableau("SELECT * FROM gt_agenda_evenement WHERE id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."' AND id_evenement NOT IN (SELECT DISTINCT id_evenement FROM gt_agenda_jointure_evenement WHERE ".trim($agenda_ecriture,"OR ").")  ORDER BY date_crea desc");

////	TOUS LES EVENEMENTS QUE J'AI CREE
$liste_evenements = (@$_REQUEST["filtre"]=="inaccessibles")  ?  $liste_evenements_inaccessibles  :  db_tableau("SELECT * FROM gt_agenda_evenement WHERE id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."' ORDER BY date_debut desc, titre asc");
?>


<script type="text/javascript">resizePopupLightbox(550,500);</script>
<style type="text/css">  body { background-image:url('<?php echo PATH_TPL; ?>module_agenda/fond_popup.png'); }  </style>


<?php
echo "<fieldset class='fieldset_titre'>".$trad["AGENDA_evt_proprio"]."</fieldset>";

////	EVENEMENTS
if(count($liste_evenements)==0)		{echo "<h3>".$trad["AGENDA_aucun_evt"]."</h3>";}
else
{
	echo "<ul style='list-style-type:circle;'>";
	foreach($liste_evenements as $evt_tmp)
	{
		$infobulle = infobulle(temps($evt_tmp["date_debut"],"normal",$evt_tmp["date_fin"])."<br>".$evt_tmp["description"]);
		echo "<li>".temps($evt_tmp["date_debut"],"plugin",$evt_tmp["date_fin"])." : <span onClick=\"popupLightbox('evenement.php?id_evenement=".$evt_tmp["id_evenement"]."');\" class='lien' ".$infobulle." >".$evt_tmp["titre"]."</span> &nbsp; <img src=\"".PATH_TPL."divers/crayon.png\" title=\"".$trad["modifier"]."\" class='lien' onclick=\"popupLightbox('evenement_edit.php?id_evenement=".$evt_tmp["id_evenement"]."',true);\" /> ".icone_suppr("evenements_proprietaires.php?action=suppr&id_evenement=".$evt_tmp["id_evenement"])."</li><br>";
	}
	echo "</ul>";
}


////	Affiche le lien "Afficher uniquement ceux que j'ai créés..." ?
if(count($liste_evenements_inaccessibles)>0 && !isset($_REQUEST["filtre"]))
	echo "<br><div class='lien' style=\"margin:10px\" onClick=\"redir('".php_self()."?filtre=inaccessibles');\"><img src=\"".PATH_TPL."divers/oeil.png\" /> &nbsp; ".$trad["AGENDA_evt_proprio_inaccessibles"]."</div>";

require PATH_INC."footer.inc.php";
?>