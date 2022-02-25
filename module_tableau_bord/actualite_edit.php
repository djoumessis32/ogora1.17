<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";
if(@$_REQUEST["id_actualite"]>0)	{ $actu_tmp = objet_infos($objet["actualite"],$_REQUEST["id_actualite"]);	droit_acces_controler($objet["actualite"], $actu_tmp, 2); }
else								{ if(droit_ajout_actualite()!=true)  exit; }


////	VALIDATION DU FORMULAIRE
////
if(isset($_POST["id_actualite"]))
{
	////	MODIF / AJOUT
	$corp_sql = " description=".db_format($_POST["description"],"editeur").", une=".db_format(@$_POST["une"],'bool').", offline=".db_format(@$_POST["offline"],'bool').", date_online=".db_format($_POST["date_online"]).", date_offline=".db_format($_POST["date_offline"]);
	if($_POST["id_actualite"] > 0){
		db_query("UPDATE gt_actualite SET ".$corp_sql." WHERE id_actualite=".db_format($_POST["id_actualite"]));
		add_logs("modif", $objet["actualite"], $_POST["id_actualite"]);
	}
	else{
		db_query("INSERT INTO gt_actualite SET date_crea=".db_date_now().", id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."', ".$corp_sql);
		$_POST["id_actualite"] = db_last_id();
		add_logs("ajout", $objet["actualite"], $_POST["id_actualite"]);
	}

	////	AFFECTATION DES DROITS D'ACCÈS  +  AJOUT FICHIERS JOINTS
	affecter_droits_acces($objet["actualite"], $_POST["id_actualite"]);
	ajouter_fichiers_joint($objet["actualite"], $_POST["id_actualite"]);

	////	ENVOI DE NOTIFICATION PAR MAIL
	if(isset($_POST["notification"]))
	{
		$liste_id_destinataires = users_affectes($objet["actualite"], $_POST["id_actualite"]);
		$objet_mail = $trad["TABLEAU_BORD_mail_nouvelle_actualite_cree"]." ".$_SESSION["user"]["nom"]." ".$_SESSION["user"]["prenom"];
		$contenu_mail = $_POST["description"];
		envoi_mail($liste_id_destinataires, $objet_mail, texteNotification($contenu_mail), array("notif"=>true));
	}
	////	FERMETURE
	reload_close();
}
?>


<script type="text/javascript">
////	Redimensionne
resizePopupLightbox(770,750);

////	CONTROLE VALIDATION FINALE
function controle_formulaire()
{
	if(get_value("date_online")!="" && is_checked("offline")==false){
		alert("<?php echo $trad["TABLEAU_BORD_date_online_auto_alerte"]; ?>");
		set_check("offline",true);
	}
	if(tinymce_vide("description")){
		alert("<?php echo $trad["specifier_description"]; ?>");
		return false;
	}
	if(Controle_Menu_Objet()==false)	return false;
}
</script>


<form class="form_edit_element" action="<?php echo php_self(); ?>" method="post" enctype="multipart/form-data" OnSubmit="return controle_formulaire();">

	<textarea name="description" id="description" style="width:100%;height:250px;"><?php echo @$actu_tmp["description"]; ?></textarea>
	<?php init_editeur_tinymce(); ?>

	<fieldset style="text-align:center;">
		<?php
		////	"A la une"
		echo "<input type='checkbox' name='une' value='1' id='box_une' style='visibility:hidden;'  ".(@$actu_tmp["une"]=="1"?"checked":"")." />";
		echo "<span id='txt_une' onClick=\"checkbox_text(this);\" class='".(@$actu_tmp["une"]=="1"?"lien_select":"lien")."' ".infobulle($trad["TABLEAU_BORD_ala_une_info"])." >".$trad["TABLEAU_BORD_ala_une"]." <img src=\"".PATH_TPL."module_tableau_bord/une.png\" /></span>";
		echo "&nbsp; &nbsp; &nbsp; ";

		////	Init Online / Offline
		$date_online = $date_offline = "";
		$date_calendrier_online = $date_calendrier_offline = time();
		////	Date Online
		echo $trad["TABLEAU_BORD_date_online_auto"]."&nbsp;";
		if(@$actu_tmp["date_online"]!="")	{ $date_calendrier_online = strtotime($actu_tmp["date_online"]);  $date_online = strftime("%Y-%m-%d",$date_calendrier_online); }
		echo "<iframe id='calendrier_date_online' class='menu_context calendrier_flottant' src=\"".PATH_INC."calendrier.inc.php?date_affiche=".$date_calendrier_online."&date_selection=".$date_calendrier_online."&champ_modif=date_online\"></iframe>";
		echo "<input type='text' name='date_online' value=\"".$date_online."\" class='calendrier_input' onClick=\"afficher_dynamic('calendrier_date_online');\" readonly />";
		////	Date Offline
		echo "&nbsp; &nbsp;".$trad["TABLEAU_BORD_date_offline_auto"]."&nbsp;";
		if(@$actu_tmp["date_offline"]!="")	{ $date_calendrier_offline = strtotime($actu_tmp["date_offline"]);  $date_offline = strftime("%Y-%m-%d",$date_calendrier_offline); }
		echo "<iframe id='calendrier_date_offline' class='menu_context calendrier_flottant' src=\"".PATH_INC."calendrier.inc.php?date_affiche=".$date_calendrier_offline."&date_selection=".$date_calendrier_offline."&champ_modif=date_offline\"></iframe>";
		echo "<input type='text' name='date_offline' value=\"".$date_offline."\" class='calendrier_input' onClick=\"afficher_dynamic('calendrier_date_offline');\" readonly />";

		////	Archivé
		echo "<input type='checkbox' name='offline' value='1' id='box_offline' style='visibility:hidden;'  ".(@$actu_tmp["offline"]=="1"?"checked":"")." />";
		echo "<span id='txt_offline' onClick=\"checkbox_text(this);\" class='".(@$actu_tmp["offline"]=="1"?"lien_select":"lien")."' >".$trad["TABLEAU_BORD_offline"]." <img src=\"".PATH_TPL."module_tableau_bord/offline.png\" height='20px;' /></span>";
		?>
	</fieldset>

	<?php
	////	DROITS D'ACCES ET OPTIONS
	$cfg_menu_edit = array("objet"=>$objet["actualite"], "id_objet"=>@$actu_tmp["id_actualite"]);
	require_once PATH_INC."element_menu_edit.inc.php";
	?>

	<div class="submit_edit_element">
		<input type="hidden" name="id_actualite" value="<?php echo @$actu_tmp["id_actualite"]; ?>" />
		<input type="submit" value="<?php echo $trad["valider"]; ?>" class="button_big" />
	</div>

</form>


<?php require PATH_INC."footer.inc.php"; ?>