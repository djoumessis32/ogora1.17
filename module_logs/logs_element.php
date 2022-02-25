<?php
////	INIT
require_once "../".$_REQUEST["module_path"]."/commun.inc.php";
require_once PATH_INC."header.inc.php";
droit_acces_controler($objet[$_REQUEST["type_objet"]], $_REQUEST["id_objet"], 3);
?>


<style>
body		{ font-weight:bold; }
td			{ padding:3px; }
.log_action	{ min-width:90px; }
.log_auteur	{ min-width:90px; }
.log_date	{ width:180px; }
</style>
<script type="text/javascript">
////	Redimensionne
resizePopupLightbox(800,350);
</script>


<?php
echo "<fieldset class='fieldset_titre'>".$trad["historique_element"]."</fieldset>";

////	INIT
$champs_sql = "DISTINCT action, id_utilisateur, DATE_FORMAT(date,'%Y-%m-%d %H:%i') as date, commentaire";
$log_modif = db_tableau("SELECT ".$champs_sql." FROM gt_logs WHERE type_objet='".$_REQUEST["type_objet"]."' AND id_objet='".intval($_REQUEST["id_objet"])."' AND action='modif' ORDER BY date desc");
$log_acces = db_tableau("SELECT ".$champs_sql." FROM gt_logs WHERE type_objet='".$_REQUEST["type_objet"]."' AND id_objet='".intval($_REQUEST["id_objet"])."' AND action like '%consult%' ORDER BY date desc");
////	LOGS DE MODIF
if(count($log_modif)>0)
{
	echo "<div>".$trad["LOGS_modif"]."</div>";
	echo "<table>";
	foreach($log_modif as $cpt => $log)
	{
		echo "<tr><td class='log_action'>".($cpt+1).". ".$trad["LOGS_modif"]."</td>
				<td class='log_auteur'>".auteur($log["id_utilisateur"])."</td>
				<td class='log_date'>".temps($log["date"],"complet")."</td>
				<td>".$log["commentaire"]."</td></tr>";
	}
	echo "</table>";
}
////	LOGS D'ACCES
if(count($log_acces)>0)
{
	echo "<br /><div>".$trad["LOGS_consult"]."</div>";
	echo "<table>";
	foreach($log_acces as $cpt => $log)
	{
		echo "<tr><td class='log_action'>".($cpt+1).". ".$trad["LOGS_".$log["action"]]."</td>
				<td class='log_auteur'>".auteur($log["id_utilisateur"])."</td>
				<td class='log_date'>".temps($log["date"],"complet")."</td></tr>";
	}
	echo "</table>";
}

require PATH_INC."footer.inc.php";
?>