<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";


////	INFOS & DROIT D'ACCES
////
if(@$_REQUEST["id_tache"]<1)	{ $tache_tmp["id_dossier"] = $_REQUEST["id_dossier"]; }
else							{ $tache_tmp = objet_infos($objet["tache"],$_REQUEST["id_tache"]);	droit_acces_controler($objet["tache"], $tache_tmp, 1.5); }


////	VALIDATION DU FORMULAIRE
////
if(isset($_POST["id_tache"]))
{
	////	DEVISES
	if(@$_POST["devise2"]!="")	$_POST["devise"] = @$_POST["devise2"];
	if(@$_POST["budget_engage"]=="" && @$_POST["budget_disponible"]=="")	$_POST["devise"] = "";
	////	DATE DEBUT / FIN
	$date_debut	= $_POST["date_debut"];
	$date_fin	= $_POST["date_fin"];
	if(@$_POST["date_debut"]!="" && isset($_POST["heure_debut"]))		$date_debut	.= " ".$_POST["heure_debut"];
	if(@$_POST["date_fin"]!="" && isset($_POST["heure_fin"]))			$date_fin	.= " ".$_POST["heure_fin"];
	if(@$_POST["minute_debut"]!="" && isset($_POST["minute_debut"]))	$date_debut	.= ":".$_POST["minute_debut"];
	if(@$_POST["minute_fin"]!="" && isset($_POST["minute_fin"]))		$date_fin	.= ":".$_POST["minute_fin"];

	////	MODIF / AJOUT
	$corps_sql = "titre=".db_format($_POST["titre"]).", description=".db_format($_POST["description"],"editeur").", priorite=".db_format(@$_POST["priorite"]).", avancement=".db_format(@$_POST["avancement"]).", budget_disponible=".db_format(@$_POST["budget_disponible"]).", budget_engage=".db_format(@$_POST["budget_engage"]).", devise=".db_format(@$_POST["devise"]).", charge_jour_homme=".db_format(@$_POST["charge_jour_homme"],"float").", date_debut=".db_format($date_debut).", date_fin=".db_format($date_fin).", raccourci=".db_format(@$_POST["raccourci"],"bool");
	if($_POST["id_tache"] > 0){
		db_query("UPDATE gt_tache SET ".$corps_sql." WHERE id_tache=".db_format($_POST["id_tache"]));
		add_logs("modif", $objet["tache"], $_POST["id_tache"]);
	}
	else{
		db_query("INSERT INTO gt_tache SET id_dossier=".intval($_POST["id_dossier"]).", date_crea=".db_date_now().", id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."', invite=".db_format(@$_POST["invite"]).", ".$corps_sql);
		$_POST["id_tache"] = db_last_id();
		add_logs("ajout", $objet["tache"], $_POST["id_tache"]);
	}

	////	RESPONSABLES
	db_query("DELETE FROM gt_tache_responsable WHERE id_tache=".db_format($_POST["id_tache"]));
	if(isset($_POST["responsables"])){
		foreach($_POST["responsables"] as $id_user)   { db_query("INSERT INTO gt_tache_responsable SET id_tache=".db_format($_POST["id_tache"]).", id_utilisateur=".db_format($id_user)); }
	}

	////	AFFECTATION DES DROITS D'ACCÈS  +  AJOUT FICHIERS JOINTS
	affecter_droits_acces($objet["tache"],$_POST["id_tache"]);
	ajouter_fichiers_joint($objet["tache"],$_POST["id_tache"]);

	////	ENVOI DE NOTIFICATION PAR MAIL
	if(isset($_POST["notification"]))
	{
		$liste_id_destinataires = users_affectes($objet["tache"], $_POST["id_tache"]);
		$objet_mail = $trad["TACHE_mail_nouvelle_tache_cree"]." ".$_SESSION["user"]["nom"]." ".$_SESSION["user"]["prenom"];
		$contenu_mail = $_POST["titre"];
		if($_POST["description"]!="")	$contenu_mail .= "<br><br>".$_POST["description"];
		envoi_mail($liste_id_destinataires, $objet_mail, texteNotification($contenu_mail), array("notif"=>true));
	}

	////	FERMETURE
	reload_close();
}
?>


<script type="text/javascript">
////	Redimensionne
resizePopupLightbox(830,<?php echo ($tache_tmp["id_dossier"]==1)?"720":"520"; ?>);

////	Affiche de la priorité et de son icone
function affiche_priorite(valeur)
{
	set_value("priorite",valeur);
	element("img_priorite").src = "<?php echo PATH_TPL; ?>module_tache/priorite"+valeur+".png";
}

////	CONTROLE VALIDATION FINALE
function controle_formulaire()
{
	if(get_value("titre").length==0  && tinymce_vide("description")){
		alert("<?php echo $trad["specifier_titre_description"]; ?>");
		return false;
	}
	if(Controle_Menu_Objet()==false)	return false;
}

////	GESTION DE LA DEVISE
function gestion_budget(devise_modif)
{
	var dispo  = element("budget_disponible");
	var engage = element("budget_engage");
	// Vérifie s'il s'agit bien d'un nombre
	if(dispo.value.length>0 && isNaN(dispo.value)==true)	{ alert("<?php echo $trad["MSG_ALERTE_specifier_nombre"]; ?>"); dispo.value=""; }
	if(engage.value.length>0 && isNaN(engage.value)==true)	{ alert("<?php echo $trad["MSG_ALERTE_specifier_nombre"]; ?>"); engage.value=""; }
	// Affiche / ferme l'input des devises
	(dispo.value>0)		?	afficher("devise",true)		:	afficher("devise",false);
	(engage.value>0)	?	afficher("devise2",true)	:	afficher("devise2",false);
	// Les 2 types de devise doivent être identiques
	if(devise_modif=="devise")			set_value("devise2", get_value("devise"));
	else if(devise_modif=="devise2")	set_value("devise", get_value("devise2"));
}

////	AFFICHE L'HEURE - MINUTE DE DEBUT / FIN ?
function heure_minute_debutfin()
{
	// Heure - minutes du début
	if(get_value("date_debut").length==0)	{ afficher("heure_debut",false); afficher("minute_debut",false); }
	else									{ afficher("heure_debut",true);  afficher("minute_debut",true); }
	// Heure - minutes de fin
	if(get_value("date_fin").length==0)		{ afficher("heure_fin",false); afficher("minute_fin",false); }
	else									{ afficher("heure_fin",true);  afficher("minute_fin",true); }
}
$(document).ready(function(){ heure_minute_debutfin(); });
</script>


<form class="form_edit_element" action="<?php echo php_self(); ?>" method="post" enctype="multipart/form-data" OnSubmit="return controle_formulaire();">

	<?php
	////	TITRE & DESCRIPTION
	////
	echo "<fieldset style='text-align:center;'>";
		echo $trad["titre"]." &nbsp;<input type='text' name='titre' id='titre' value=\"".@$tache_tmp["titre"]."\" style='width:65%' /> &nbsp; &nbsp; ";
		echo "<span onClick=\"afficher_dynamic('block_description');afficher_tinymce();\" class='lien'>".$trad["description"]." <img src=\"".PATH_TPL."divers/derouler.png\" /></span>";
		echo "<span id='block_description'><br><br><textarea name='description' id='description' class='tinymce_textarea'>".@$tache_tmp["description"]."</textarea></span>";
		init_editeur_tinymce("description","block_description");
	echo "</fieldset>";

	////	OPTIONS
	echo "<fieldset style='text-align:center;'>";

		////	DATE DEBUT
		$debut_unix = (@$tache_tmp["date_debut"]!="")  ?  strtotime($tache_tmp["date_debut"])  :  time();
		echo $trad["debut"]." <iframe id='calendrier_date_debut' class='menu_context calendrier_flottant' src=\"".PATH_INC."calendrier.inc.php?date_affiche=".$debut_unix."&date_selection=".$debut_unix."&champ_modif=date_debut\"></iframe>";
		echo "<input type='text' name='date_debut' value=\"".(@$tache_tmp["date_debut"]!=""?strftime("%Y-%m-%d",$debut_unix):"")."\" class='calendrier_input' onClick=\"afficher_dynamic('calendrier_date_debut');\" readonly />&nbsp;";
		////	HEURE DEBUT
		$debut_heure = (@$tache_tmp["date_debut"]!="" && strftime("%H:%M",$debut_unix)!="00:00")  ?  strftime("%H",$debut_unix)  :  "";
		echo "<select name='heure_debut' id='heure_debut'><option value=''></option>";
		for($H=0; $H<=23; $H++)		{ echo "<option value=\"".num2carac($H)."\" ".(num2carac($H)==$debut_heure?"selected":"").">".num2carac($H)."</option>"; }
		echo "</select>&nbsp;";
		////	MINUTE DEBUT
		$debut_minute = (@$tache_tmp["date_debut"]!="" && strftime("%H:%M",$debut_unix)!="00:00")  ?  strftime("%M",$debut_unix)  :  "";
		echo "<select name='minute_debut' id='minute_debut'><option value=''></option>";
		for($M=0; $M<=55; $M+=5)	{ echo "<option value=\"".num2carac($M)."\" ".(num2carac($M)==$debut_minute?"selected":"").">".num2carac($M)."</option>"; }
		echo "</select>&nbsp; &nbsp; &nbsp;";

		////	DATE FIN
		$fin_unix = (@$tache_tmp["date_fin"]!="")  ?  strtotime($tache_tmp["date_fin"])  :  time();
		echo $trad["fin"]." <iframe id='calendrier_date_fin' class='menu_context calendrier_flottant' src=\"".PATH_INC."calendrier.inc.php?date_affiche=".$fin_unix."&date_selection=".$fin_unix."&champ_modif=date_fin\"></iframe>";
		echo "<input type='text' name='date_fin' value=\"".(@$tache_tmp["date_fin"]!=""?strftime("%Y-%m-%d",$fin_unix):"")."\" class='calendrier_input' onClick=\"afficher_dynamic('calendrier_date_fin');\" readonly />&nbsp;";
		////	HEURE FIN
		$fin_heure = (@$tache_tmp["date_fin"]!="" && strftime("%H:%M",$fin_unix)!="00:00")  ?  strftime("%H",$fin_unix)  :  "";
		echo "<select name='heure_fin' id='heure_fin'><option value=''></option>";
		for($H=0; $H<=23; $H++)		{ echo "<option value=\"".num2carac($H)."\" ".(num2carac($H)==$fin_heure?"selected":"").">".num2carac($H)."</option>"; }
		echo "</select>&nbsp;";
		////	MINUTE FIN
		$fin_minute = (@$tache_tmp["date_fin"]!="" && strftime("%H:%M",$fin_unix)!="00:00")  ?  strftime("%M",$fin_unix)  :  "";
		echo "<select name='minute_fin' id='minute_fin'><option value=''></option>";
		for($M=0; $M<=55; $M+=5)	{ echo "<option value=\"".num2carac($M)."\" ".(num2carac($M)==$fin_minute?"selected":"").">".num2carac($M)."</option>"; }
		echo "</select>";

		////	AVANCEMENT
		////
		echo "&nbsp; &nbsp; &nbsp; ".$trad["TACHE_avancement"];
		echo "&nbsp;<select name='avancement' id='avancement'>";
			echo "<option value=''>&nbsp;</option>";
			for($i=5; $i<=100; $i+=5)  { echo "<option value='".$i."'>".$i."%</option>"; }
		echo "</select>";
		echo "<script> set_value(\"avancement\",\"".@$tache_tmp["avancement"]."\"); </script>";

		////	PRIORITE
		////
		echo "&nbsp; &nbsp; &nbsp; <img src='' id='img_priorite' /> ".$trad["TACHE_priorite"];
		echo "&nbsp;<select name='priorite' id='priorite' onChange=\"affiche_priorite(this.value);\">";
			echo "<option value=''>&nbsp;</option>";
			for($i=1; $i<=4; $i++)	{ echo "<option value='".$i."'>".$trad["TACHE_priorite".$i]."</option>"; }
		echo "</select>";
		echo "<script> affiche_priorite('".@$tache_tmp["priorite"]."'); </script>";
		echo "<hr style='margin:10px;' />";

		////	BUDGET DISPONIBLE
		////
		if(@$tache_tmp["devise"]=="")	$tache_tmp["devise"] = "euros";
		echo "<img src=\"".PATH_TPL."module_tache/budget_disponible.png\" /> ".$trad["TACHE_budget_disponible"];
		echo "&nbsp;<input type='text' name='budget_disponible' value=\"".@$tache_tmp["budget_disponible"]."\" style='width:50px;' onkeyup=\"gestion_budget();\" />";
		echo "&nbsp;<input type='text' name='devise' value=\"".$tache_tmp["devise"]."\" style='display:none;width:30px;font-size:9px;' onkeyup=\"gestion_budget('devise');\" /> &nbsp; &nbsp; ";
		////	BUDGET ENGAGE
		////
		echo "<img src=\"".PATH_TPL."module_tache/budget_engage.png\" /> ".$trad["TACHE_budget_engage"];
		echo "&nbsp;<input type='text' name='budget_engage' value=\"".@$tache_tmp["budget_engage"]."\" style='width:50px;' onkeyup=\"gestion_budget();\" />";
		echo "&nbsp;<input type='text' name='devise2' value=\"".$tache_tmp["devise"]."\" style='display:none;width:30px;font-size:9px;' onkeyup=\"gestion_budget('devise2');\" /> &nbsp; &nbsp; ";
		echo "<script type='text/javascript'>  gestion_budget(); </script>";

		////	CHARGE JOURS/HOMME
		////
		echo "<img src=\"".PATH_TPL."module_tache/charge_jour_homme.png\" /> <acronym ".infobulle($trad["TACHE_charge_jour_homme_info"]).">".$trad["TACHE_charge_jour_homme"]."</acronym>";
		echo "&nbsp;<input type='text' name='charge_jour_homme' value=\"".@$tache_tmp["charge_jour_homme"]."\" style='width:50px;' /> &nbsp; &nbsp; ";

		////	RESPONSABLES
		////
		$responsables = db_colonne("SELECT id_utilisateur FROM gt_tache_responsable WHERE id_tache='".@$tache_tmp["id_tache"]."'");
		echo "<span onclick=\"afficher('div_responsables');\" class='lien'><img src=\"".PATH_TPL."module_utilisateurs/utilisateurs_small.png\" /> ".$trad["TACHE_responsables"]." <img src=\"".PATH_TPL."divers/derouler.png\" /></span>";
		echo "<div id='div_responsables' style='". ((count($responsables)==0)?"display:none;":"") ."float:right;width:85%;margin:10px;padding:5px;border:#333 solid 1px;'>";
		foreach(users_espace($_SESSION["espace"]["id_espace"],"complet") as $user_tmp)
		{
			if((in_array($user_tmp["id_utilisateur"],$responsables)))	{ $check_user = "checked";	$style_user = "lien_select"; }
			else														{ $check_user = "";			$style_user = "lien"; }
			$id_tmp = "user".$user_tmp["id_utilisateur"];
			echo "<div style='float:left;width:24%;text-align:left;'>";
				echo "<input type='checkbox' name='responsables[]' value='".$user_tmp["id_utilisateur"]."'  id='box_".$id_tmp."' onClick=\"checkbox_text(this);\" ".$check_user." /> &nbsp; ";
				echo "<span class='".$style_user."' id='txt_".$id_tmp."' onClick=\"checkbox_text(this);\">".$user_tmp["prenom"]." ".$user_tmp["nom"]."</span>";
			echo "</div>";
		}
		echo "</div><a name='bottom'></a>";
	echo "</fieldset>";


	////	DROITS D'ACCES ET OPTIONS
	////
	$cfg_menu_edit = array("objet"=>$objet["tache"], "id_objet"=>@$tache_tmp["id_tache"]);
	require_once PATH_INC."element_menu_edit.inc.php";
	?>

	<div class="submit_edit_element">
		<input type="hidden" name="id_tache" value="<?php echo @$tache_tmp["id_tache"]; ?>" />
		<input type="hidden" name="id_dossier" value="<?php echo $tache_tmp["id_dossier"]; ?>" />
		<input type="submit" value="<?php echo $trad["valider"]; ?>" class="button_big" />
	</div>

</form>


<?php require PATH_INC."footer.inc.php"; ?>