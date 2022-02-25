<?php
////	INIT
define("IS_MAIN_PAGE",true);
require "commun.inc.php";
require PATH_INC."header_menu.inc.php";


////	LISTE DEROULANTE POUR LES FILTRES
////
function getSelect($libelle_champ, $nom_champ, $selected="")
{
	global $trad;
	$filtre_options = db_colonne("SELECT DISTINCT ".$nom_champ." FROM gt_logs L LEFT JOIN gt_espace S ON S.id_espace=L.id_espace  WHERE ".$nom_champ." is not null AND ".$nom_champ."!='' ORDER BY ".$nom_champ." asc");
	echo "<select name=\"search_".$libelle_champ."\" class=\"search_init\">";
	echo "<option value=\"\" ".($selected==''?'selected':'').">".$trad["LOGS_filtre"]." ".$libelle_champ."</option>";
	foreach($filtre_options as $value)
	{
		if(isset($trad["LOGS_".$value]))						{$lib_value = $trad["LOGS_".$value];}
		elseif(isset($trad[strtoupper($value)."_nom_module"]))	{$lib_value = $trad[strtoupper($value)."_nom_module"];}
		else													{$lib_value = $value;}
		echo "<option value=\"".$value."\" ".($selected==$value?'selected':'').">".$lib_value."</option>";
	}
	echo "</select>";
}
?>


<link href="datatables/page.css" rel="stylesheet" type="text/css" />
<link href="datatables/table.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript">
////	PARAMETRAGE DE DataTables
////
$(document).ready(function(){
	////	CONSTRUCTION DU TABLEAU DE DONNEES
	var oTable = $("#tableLogs").dataTable({
        "iDisplayLength": 30,				//nb de lignes par page
        "aLengthMenu": [30,100,300],		//menu de nb de lignes par page
        "aaSorting": [[0,"desc"]],			//indique sur quelle colonne se fait le tri par défaut
        "oLanguage":{						//Traduction diverses dans le menu
            "sLengthMenu": "_MENU_ logs",													//Menu select du nb de lignes par page
            "sZeroRecords": "<?php echo $trad["LOGS_no_logs"]; ?>",							//"aucun logs"
            "sInfo": "_START_-_END_ [_TOTAL_]",												// mettre "_START_-_END_ [_TOTAL_]" si on veut afficher "1-30 [120]"
            "sInfoEmpty": "<?php echo $trad["LOGS_no_logs"]; ?>",							//"aucun logs"
            "sInfoFiltered": "(<?php echo $trad["LOGS_filtre_a_partir"]; ?> _MAX_ logs)",	// Ajouté si on filtre les infos dans une table (pour donner une idée de la force du filtrage)
            "sSearch":"<i><?php echo $trad["LOGS_chercher"]; ?></i>"						//"Rechercher"
        }
    });

	//FILTRE SUR LE INPUT TEXT ET "SELECT" DU FOOTER
	$("tfoot input,  tfoot select").on("keyup change", function(){
		oTable.fnFilter($(this).val(), this.parentNode.cellIndex);
	});
	//FOCUS SUR UN INPUT TEXT : EFFACE LE CONTENU ET ENLÈVE LE STYLE PAR DÉFAUT (TEXTE GRIS)
	$("tfoot input").focus( function (){
		this.value = "";
		this.className = "";
	});
});
</script>

<style>
#tableLogs			{border-spacing:0px;}
#tableLogsContent	{color:#333;}
</style>


<div class="content contenu_principal_centre" style="<?php echo STYLE_SHADOW_FORT; ?>">
	<table id="tableLogs" class="display">
		<thead>
			<tr>
				<th><?php echo $trad["LOGS_date_heure"]; ?></th>
				<th><?php echo $trad["LOGS_espace"]; ?></th>
				<th><?php echo $trad["LOGS_module"]; ?></th>
				<th><?php echo $trad["LOGS_adresse_ip"]; ?></th>
				<th><?php echo $trad["LOGS_utilisateur"]; ?></th>
				<th><?php echo $trad["LOGS_action"]; ?></th>
				<th><?php echo $trad["LOGS_commentaire"]; ?></th>
			</tr>
		</thead>
		<tbody id="tableLogsContent">
			<?php
			////	AFFICHAGE DES LOGS
			foreach(tabLogs() as $logTmp)
			{
				echo "<tr>"
						."<td style='width:100px;'>".strftime("%Y-%m-%d %H:%M",strtotime($logTmp["date"]))."</td>"
						."<td>".$logTmp["nom"]."</td>"
						."<td>".$logTmp["module"]."</td>"
						."<td>".$logTmp["ip"]."</td>"
						."<td>".$logTmp["identifiant"]."</td>"
						."<td>".$logTmp["action"]."</td>"
						."<td style='width:400px;'>".$logTmp["commentaire"]."</td>"
					. "</tr>";
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<th><input type="text" name="search_date" value="<?php echo $trad["LOGS_filtre"]." ".$trad["LOGS_date_heure"]; ?>" class="search_init" /></th>
				<th><?php getSelect($trad["LOGS_espace"],"S.nom"); ?></th>
				<th><?php getSelect($trad["LOGS_module"],"module"); ?></th>
				<th><input type="text" name="search_IP" value="<?php echo $trad["LOGS_filtre"]." ".$trad["LOGS_adresse_ip"]; ?>" class="search_init" /></th>
				<th><input type="text" name="search_user" value="<?php echo $trad["LOGS_filtre"]." ".$trad["LOGS_utilisateur"]; ?>" class="search_init" /></th>
				<th><?php getSelect($trad["LOGS_action"],"action"); ?></th>
				<th><input type="text" name="search_commentaire" value="<?php echo $trad["LOGS_filtre"]." ".$trad["LOGS_commentaire"]; ?>" class="search_init" /></th>
			</tr>
		</tfoot>
	</table>
	<br><br><br>
</div>

<div onClick="redir('logs_csv.php');" class="lien" style="margin-top:-30px;text-align:center;">
	<span style="padding:5px;">
		<img src="<?php echo PATH_TPL; ?>divers/telecharger.png" style="height:18px;" /> <?php echo $trad["telecharger"]; ?>
	</span>
</div>


<?php require PATH_INC."footer.inc.php"; ?>