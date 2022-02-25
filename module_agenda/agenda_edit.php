<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";
if(@$_REQUEST["id_agenda"]>0)	{ $agenda_tmp = objet_infos($objet["agenda"],$_REQUEST["id_agenda"]);   if($AGENDAS_AFFICHES[$_REQUEST["id_agenda"]]["droit"]<3) exit();  }
else							{ $agenda_tmp = array("type"=>"ressource");  if(droit_ajout_agenda_ressource()!=true) exit;   }


////	VALIDATION DU FORMULAIRE
////
if(isset($_POST["id_agenda"]))
{
	////	MODIF / AJOUT : AGENDA DE RESSOURCE
	$corp_sql = " titre=".db_format(@$_POST["titre"]).", description=".db_format(@$_POST["description"]).", evt_affichage_couleur=".db_format(@$_POST["evt_affichage_couleur"]).", plage_horaire=".db_format($_POST["heure_debut"]."-".$_POST["heure_fin"])." ";
	if($_POST["id_agenda"] > 0){
		db_query("UPDATE gt_agenda SET ".$corp_sql." WHERE id_agenda=".db_format($_POST["id_agenda"]));
		add_logs("modif", $objet["agenda"], $_POST["id_agenda"]);
	}
	else{
		db_query("INSERT INTO gt_agenda SET id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."', type=".db_format(@$_POST["type"]).", ".$corp_sql);
		$_POST["id_agenda"] = db_last_id();
		add_logs("ajout", $objet["agenda"], $_POST["id_agenda"]);
	}

	////	AFFECTATION DES DROITS D'ACCÈS  +  AJOUT DE FICHIERS JOINTS  +  FERMETURE
	affecter_droits_acces($objet["agenda"],$_POST["id_agenda"]);
	ajouter_fichiers_joint($objet["agenda"],$_POST["id_agenda"]);
	reload_close();
}
?>


<script type="text/javascript">
////	On redimensionne
resizePopupLightbox(650,500);

////	CONTROLE VALIDATION FINALE
function controle_formulaire()
{
	// Il doit y avoir un titre
	if(get_value("type")=="ressource" && get_value("titre").length==0){
		alert("<?php echo $trad["specifier_titre"]; ?>");
		return false;
	}
	// Controle le nombre de groupes et d'utilisateurs
	if(Controle_Menu_Objet()==false)	return false;
}
</script>


<form class="form_edit_element" action="<?php echo php_self(); ?>" method="post" enctype="multipart/form-data" OnSubmit="return controle_formulaire();">

	<fieldset>
		<?php
		////	TITRE + DESCRIPTION
		if(@$agenda_tmp["type"]=="ressource") {
			echo $trad["titre"]." <input type=\"text\" name=\"titre\" id=\"titre\" value=\"".@$agenda_tmp["titre"]."\" style=\"width:300px\" /><br><br>";
			echo $trad["description"]."<br><textarea name=\"description\" id=\"description\" style=\"width:98%\" rows=\"2\">".@$agenda_tmp["description"]."</textarea><br><br>";
		}
		////	PAGE HORAIRE AGENDA
		$plage_horaire = (@$agenda_tmp["plage_horaire"]!="")  ?  explode("-",$agenda_tmp["plage_horaire"])  :  array("8","21");
		echo $trad["AGENDA_plage_horaire"]." &nbsp; ";
		echo "<select name=\"heure_debut\">";
			for($h=1; $h<24; $h++)	{ echo "<option value=\"".$h."\" ".($plage_horaire[0]==$h?"selected":"").">".$h.":00</option>"; }
		echo "</select> ".$trad["a"];
		echo " <select name=\"heure_fin\">";
			for($h=1; $h<24; $h++)	{ echo "<option value=\"".$h."\" ".($plage_horaire[1]==$h?"selected":"").">".$h.":00</option>"; }
		echo "</select><br><br>";
		////	AFFICHAGE DES EVENEMENTS
		echo $trad["AGENDA_affichage_evt"]." &nbsp; <select name=\"evt_affichage_couleur\">";
			echo "<option value=\"background\">".$trad["AGENDA_affichage_evt_background"]."</option>";
			echo "<option value=\"border\">".$trad["AGENDA_affichage_evt_border"]."</option>";
		echo "</select><script> set_value(\"evt_affichage_couleur\",\"".@$agenda_tmp["evt_affichage_couleur"]."\");</script>";
	
	echo "</fieldset>";

	////	DROITS D'ACCES ET OPTIONS
	$cfg_menu_edit = array("objet"=>$objet["agenda"], "id_objet"=>@$agenda_tmp["id_agenda"], "notif_mail"=>false);
	require_once PATH_INC."element_menu_edit.inc.php";
	?>

	<div class="submit_edit_element">
		<input type="hidden" name="id_agenda" value="<?php echo @$agenda_tmp["id_agenda"]; ?>" />
		<input type="hidden" name="type" id="type" value="<?php echo @$agenda_tmp["type"]; ?>" />
		<input type="submit" value="<?php echo $trad["valider"]; ?>" class="button_big" />
	</div>
</form>


<?php require PATH_INC."footer.inc.php"; ?>
