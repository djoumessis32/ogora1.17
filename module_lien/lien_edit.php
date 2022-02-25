<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";
if(@$_REQUEST["id_lien"]>0)		{ $lien_tmp = objet_infos($objet["lien"],$_REQUEST["id_lien"]);  droit_acces_controler($objet["lien"], $lien_tmp, 1.5); }
else							{ $lien_tmp = array("id_dossier"=> $_REQUEST["id_dossier"], "adresse"=>"http://"); }


////	VALIDATION DU FORMULAIRE
////
if(isset($_POST["id_lien"]))
{
	////	MODIF / AJOUT
	$corps_sql = " adresse=".db_format($_POST["adresse"]).", description=".db_format($_POST["description"]).", raccourci=".db_format(@$_POST["raccourci"],"bool")." "; 
	if($_POST["id_lien"] > 0){
		db_query("UPDATE gt_lien SET ".$corps_sql." WHERE id_lien=".db_format($_POST["id_lien"]));
		add_logs("modif", $objet["lien"], $_POST["id_lien"]);
	}
	else{
		db_query("INSERT INTO gt_lien SET id_dossier=".db_format($_POST["id_dossier"]).", date_crea=".db_date_now().", id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."', invite=".db_format(@$_POST["invite"]).", ".$corps_sql);
		$_POST["id_lien"] = db_last_id();
		add_logs("ajout", $objet["lien"], $_POST["id_lien"]);
	}

	////	AFFECTATION DES DROITS D'ACCÈS  +  AJOUT FICHIERS JOINTS
	affecter_droits_acces($objet["lien"],$_POST["id_lien"]);
	ajouter_fichiers_joint($objet["lien"],$_POST["id_lien"]);

	////	ENVOI DE NOTIFICATION PAR MAIL
	if(isset($_POST["notification"]))
	{
		$liste_id_destinataires = users_affectes($objet["lien"], $_POST["id_lien"]);
		$objet_mail = $trad["LIEN_mail_nouveau_lien_cree"]." ".$_SESSION["user"]["nom"]." ".$_SESSION["user"]["prenom"];
		$contenu_mail = "<a href=\"".$_POST["adresse"]."\" target='_blank'>".$_POST["adresse"]."</a>";
		if($_POST["description"]!="")	{$contenu_mail .= "<br><br>".$_POST["description"];}
		envoi_mail($liste_id_destinataires, $objet_mail, texteNotification($contenu_mail), array("notif"=>true));
	}

	////	FERMETURE
	reload_close();
}
?>

<style type="text/css">  body {  background-image:url('<?php echo PATH_TPL; ?>module_lien/fond_popup.png');  }  </style>
<script type="text/javascript">
////	Redimensionne
resizePopupLightbox(650,<?php echo ($lien_tmp["id_dossier"]==1)?"500":"300"; ?>);

////	CONTROLE VALIDATION FINALE
function controle_formulaire()
{
	// Il doit y avoir une adresse & une description
	if(get_value("adresse").length==0){
		alert("<?php echo $trad["LIEN_specifier_adresse"]; ?>");
		return false;
	}
	// Controle le nombre de groupes et d'utilisateurs
	if(Controle_Menu_Objet()==false)	return false;
}
</script>


<form class="form_edit_element" action="<?php echo php_self(); ?>" method="post" enctype="multipart/form-data" OnSubmit="return controle_formulaire();">

	<?php
	////	URL & DESCRIPTION
	echo "<fieldset>";
		echo $trad["LIEN_adresse"]." <input type='text' name='adresse' id='adresse' value=\"".$lien_tmp["adresse"]."\" style='width:400px' /><br><br>";
		echo $trad["description"]."<br><textarea name='description' id='description' style='width:98%;height:40px;'>".@$lien_tmp["description"]."</textarea>";
	echo "</fieldset>";

	////	DROITS D'ACCES ET OPTIONS
	$cfg_menu_edit = array("objet"=>$objet["lien"], "id_objet"=>@$lien_tmp["id_lien"]);
	require_once PATH_INC."element_menu_edit.inc.php";
	?>

	<div class="submit_edit_element">
		<input type="hidden" name="id_lien" value="<?php echo @$lien_tmp["id_lien"]; ?>" />
		<input type="hidden" name="id_dossier" value="<?php echo $lien_tmp["id_dossier"]; ?>" />
		<input type="submit" value="<?php echo $trad["valider"]; ?>" class="button_big" />
	</div>

</form>


<?php require PATH_INC."footer.inc.php"; ?>
