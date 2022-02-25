<?php
////	INIT
require "commun.inc.php";
if($AGENDAS_AFFICHES[$_REQUEST["id_agenda"]]["droit"]<3)	exit();


////	HEADER & TITRE DU POPUP
////
require_once PATH_INC."header.inc.php";
$titre_popup = $trad["AGENDA_importer_ical"];
titre_popup($titre_popup);


////	IMPORTE LES EVENEMENTS SELECTIONNES
////
if(isset($_POST["champs_evenement"]) && $_POST["evenement_import"])
{
	////	Tableau des evenements à importer (sélectionnés) à partir du tableau général des evenements
	$evenements_import = array();
	foreach($_POST["champs_evenement"] as $evenement_cpt => $evenement) {
		if(in_array($evenement_cpt,$_POST["evenement_import"]))		$evenements_import[] = $_POST["champs_evenement"][$evenement_cpt];
	}

	////	On créé le evenement / l'utilisateur
	foreach($evenements_import as $evenement_tmp)
	{
		db_query("INSERT INTO gt_agenda_evenement SET titre=".db_format(@$evenement_tmp["titre"]).", description=".db_format(@$evenement_tmp["description"]).", date_debut=".db_format(@$evenement_tmp["date_debut"]).", date_fin=".db_format(@$evenement_tmp["date_fin"]).", id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."', date_crea=".db_date_now());
		db_query("INSERT INTO gt_agenda_jointure_evenement SET id_evenement='".db_last_id()."', id_agenda=".db_format($_POST["id_agenda"]).", confirme=1 ");
	}
	////	FERMETURE
	reload_close();
}
?>


<style type="text/css">  body { background-image:url('<?php echo PATH_TPL; ?>module_agenda/fond_popup.png'); font-weight:bold; }  </style>


<script type="text/javascript">
////	Redimensionne la page
<?php echo (empty($_POST)) ? "resizePopupLightbox(500,250);" : "resizePopupLightbox(950,550);"; ?>

////	Contrôle du formulaire du fichier
function controle_formulaire()
{
	// Il doit y avoir un fichier au format ics
	if(get_value("import_fichier")=="")					{ alert("<?php echo $trad["specifier_fichier"]; ?>"); return false; }
	if(extension(get_value("import_fichier"))!="ics")	{ alert("<?php echo $trad["extension_fichier"]; ?> .ICS"); return false; }
}

////	Contrôle du formulaire des evenements
function controle_evenements()
{
	// Au moins un evenement doit être sélectionné
	var nb_evenements_select = 0;
	for(evenement_cpt=0; evenement_cpt < get_value("nb_evenements"); evenement_cpt++)	{ if(element("evenement_import["+evenement_cpt+"]").checked==true)	nb_evenements_select ++; }
	if(nb_evenements_select==0)  { alert("<?php echo $trad["import_alert2"]; ?>"); return false; }
}

////	Selectionne ligne
function select_ligne(evenement_cpt)
{
	color = (element("evenement_import["+evenement_cpt+"]").checked==true)  ?  "<?php echo STYLE_TR_SELECT; ?>"  :  "<?php echo STYLE_TR_DESELECT; ?>";
	element("ligne_"+evenement_cpt).style.backgroundColor = color;
}

////	On coche/décoche tout
function selection_import()
{
	for(evenement_cpt=0; evenement_cpt<get_value("nb_evenements"); evenement_cpt++)
	{
		evenement = element("evenement_import["+evenement_cpt+"]");
		if(evenement.checked==true)	{ evenement.checked = false;  select_ligne(evenement_cpt); }
		else						{ evenement.checked = true;  select_ligne(evenement_cpt); }
	}
}
</script>


<?php
////	SELECTIONNE LE FICHIER ICS
////
if(!isset($_FILES["import_fichier"]) && !isset($_POST["champs_evenement"])) {
?>
	<form action="<?php echo php_self(); ?>" method="post" style="text-align:center;" enctype="multipart/form-data" OnSubmit="return controle_formulaire();">
		<input type="file" name="import_fichier" />
		<div style="margin:30px;">
			<input type="hidden" name="id_agenda" value="<?php echo $_REQUEST["id_agenda"]; ?>" />
			<input type="submit" value="<?php echo $trad["valider"]; ?>" class="button_big" />
		</div>
	</form>
<?php
}
////	AFFICHE LES EVENEMENTS DU FICHIER
////
elseif(isset($_FILES["import_fichier"]))
{
	////	IMPORTATION DU FICHIER ICA
	$import_evenements = array();
	require("SG_iCal/SG_iCal.php");
	$ical = new SG_iCal($_FILES["import_fichier"]["tmp_name"]);

	////	TABLEAU D'IMPORTATION
	////
	echo "<form action=\"".php_self()."\" method=\"post\" OnSubmit=\"return controle_evenements();\">";
		echo "<table cellpadding=\"5px\">";
			////	ENTETE
			echo "<tr style=\"background-color:#aaa\">";
				echo "<td style=\"width:100px;\">".$trad["AGENDA_importer_ical_etat"]."</td>";
				echo "<td class='lien' onClick=\"selection_import();\" ".infobulle($trad["inverser_selection"])."><img src=\"".PATH_TPL."divers/tri_reload.png\" /></td>";
				echo "<td style=\"text-align:center;width:130px;\">".$trad["debut"]." - ".$trad["fin"]."</td>";
				echo "<td style=\"text-align:center;\">".$trad["titre"]."</td>";
				echo "<td style=\"text-align:center;\">".$trad["description"]."</td>";
			echo "</tr>";
			////	EVENEMENTS
			foreach($ical->getEvents() as $evenement_cpt => $event)
			{
				////	Etat de l'événement : importer ? dejà present (ne pas importer) ?
				$evt_is_present = db_valeur("SELECT count(*) FROM gt_agenda_evenement T1, gt_agenda_jointure_evenement T2 WHERE T1.id_evenement=T2.id_evenement AND T2.id_agenda='".intval($_REQUEST["id_agenda"])."' AND T2.confirme='1' AND T1.titre='".addslashes($event->getSummary())."' AND T1.date_debut='".strftime("%Y-%m-%d %H:%M:00",$event->getStart())."' AND T1.date_fin='".strftime("%Y-%m-%d %H:%M:00",$event->getEnd())."'");
				if($evt_is_present>0)	$etat_import = "<img src=\"".PATH_TPL."divers/point_orange.png\" /> ".$trad["AGENDA_importer_ical_deja_present"];
				else					$etat_import = "<img src=\"".PATH_TPL."divers/point_vert.png\" /> ".$trad["AGENDA_importer_ical_a_importer"];
				////	Affichage de l'événement
				echo "<tr id=\"ligne_".$evenement_cpt."\" style=\"background-color:".($evt_is_present>0?STYLE_TR_DESELECT:STYLE_TR_SELECT)."\">";
					echo "<td>".$etat_import."</td>";
					echo "<td><input type=\"checkbox\" name=\"evenement_import[".$evenement_cpt."]\" value=\"".$evenement_cpt."\" onClick=\"select_ligne('".$evenement_cpt."');\" ".($evt_is_present>0?"":"checked")." /></td>";
					echo "<td>".temps($event->getStart(),"normal",$event->getEnd())." <input type=\"hidden\" name=\"champs_evenement[".$evenement_cpt."][date_debut]\" value=\"".strftime("%Y-%m-%d %H:%M:00",$event->getStart())."\" /><input type=\"hidden\" name=\"champs_evenement[".$evenement_cpt."][date_fin]\" value=\"".strftime("%Y-%m-%d %H:%M:00",$event->getEnd())."\" /></td>";
					echo "<td>".$event->getSummary()." <input type=\"hidden\" name=\"champs_evenement[".$evenement_cpt."][titre]\" value=\"".$event->getSummary()."\" /></td>";
					echo "<td>".$event->getDescription()." <input type=\"hidden\" name=\"champs_evenement[".$evenement_cpt."][description]\" value=\"".$event->getDescription()."\" /></td>";
				echo "</tr>";
			}
		echo "</table>";
		//foreach($ical->getEvents() as $event)  {print_r($event);}
		////	INFOS SUR LE ICS + VALIDATION
		echo "<div style=\"text-align:center;margin:20px;\">";
			echo "<input type=\"hidden\" name=\"nb_evenements\" value=\"".count($ical->getEvents())."\" />";
			echo "<input type=\"hidden\" name=\"id_agenda\" value=\"".$_REQUEST["id_agenda"]."\" />";
			echo "<input type=\"submit\" value=\"".$trad["valider"]."\"  class=\"button_big\" />";
		echo "</div>";
	echo "</form>";
}

require PATH_INC."footer.inc.php";
?>