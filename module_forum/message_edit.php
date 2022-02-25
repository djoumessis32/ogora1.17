<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";
if(@$_REQUEST["id_message"]>0)	{ $message_tmp = objet_infos($objet["message"],$_REQUEST["id_message"]);										droit_acces_controler($objet["message"], $message_tmp, 3); }
else							{ $message_tmp = array("id_message_parent"=>$_REQUEST["id_message_parent"],"id_sujet"=>$_REQUEST["id_sujet"]);	droit_acces_controler($objet["sujet"], $_REQUEST["id_sujet"], 1.5); }


////	VALIDATION DU FORMULAIRE
////
if(isset($_POST["id_message"]))
{
	////	MODIF / AJOUT
	$corp_sql = " titre=".db_format($_POST["titre"]).", description=".db_format($_POST["description"],"editeur")." ";
	if($_POST["id_message"] > 0){
		db_query("UPDATE gt_forum_message SET ".$corp_sql." WHERE id_message=".db_format($_POST["id_message"]));
		add_logs("modif", $objet["message"], $_POST["id_message"]);
	}
	else{
		db_query("INSERT INTO gt_forum_message SET id_message_parent=".db_format($_POST["id_message_parent"],"numerique").", id_sujet=".db_format($_POST["id_sujet"]).", date_crea=".db_date_now().", id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."', invite=".db_format(@$_POST["invite"]).", ".$corp_sql);
		$_POST["id_message"] = db_last_id();
		add_logs("ajout", $objet["message"], $_POST["id_message"]);
	}
	maj_dernier_message($_POST["id_sujet"]);

	////	AJOUTER FICHIERS JOINTS
	ajouter_fichiers_joint($objet["message"],$_POST["id_message"]);

	////	ENVOI DE NOTIFICATION PAR MAIL
	$objet_mail = $trad["FORUM_mail_nouveau_message_cree"]." ".auteur($_SESSION["user"]["id_utilisateur"],@$_POST["invite"]);
	$contenu_mail = $_POST["titre"];
	if($_POST["description"]!="")	{$contenu_mail .= "<br><br>".$_POST["description"];}
	// Notif pour les destinataires qui sont affectés au sujet (ou destinataires spécifiés dans les box)
	if(isset($_POST["notification"])){
		$liste_id_destinataires = users_affectes($objet["sujet"], $_POST["id_sujet"]);
		envoi_mail($liste_id_destinataires, $objet_mail, texteNotification($contenu_mail), array("notif"=>true));
	}
	// Envoi aux personnes "abonnées" aux notifs (n'envoie pas 2 fois le mail!)
	$users_notifier_dernier_message = objet_infos($objet["sujet"], $_POST["id_sujet"], "users_notifier_dernier_message");
	if($users_notifier_dernier_message!="")
	{
		$liste_id_destinataires2 = array();
		foreach(explode("u",$users_notifier_dernier_message) as $det_tmp){
			if(is_numeric($det_tmp) && in_array($det_tmp,$liste_id_destinataires)==false)	{$liste_id_destinataires2[] = $det_tmp;}
		}
		if(count($liste_id_destinataires2)>0)	{envoi_mail($liste_id_destinataires2, $objet_mail, texteNotification($contenu_mail), array("message_alert"=>false));}
	}

	////	FERMETURE
	reload_close();
}
?>

<style type="text/css">  body {  background-image:url('<?php echo PATH_TPL; ?>module_forum/fond_popup.png');  }  </style>
<script type="text/javascript">
////	Redimensionne
resizePopupLightbox(770,520);

////	CONTROLE VALIDATION FINALE
function controle_formulaire()
{
	if(get_value("titre").length==0 && tinymce_vide("description")){
		alert("<?php echo $trad["specifier_titre_description"]; ?>");
		return false;
	}
	if(Controle_Menu_Objet()==false)	return false;
}
</script>


<form class="form_edit_element" action="<?php echo php_self(); ?>" method="post" enctype="multipart/form-data" OnSubmit="return controle_formulaire();">

	<?php
	echo "<fieldset>";
		////	MESSAGE CITE
		if(@$_REQUEST["id_message_parent"]>0){
			$message_parent = db_ligne("SELECT * FROM gt_forum_message WHERE id_message='".intval($_REQUEST["id_message_parent"])."'");
			if($message_parent["description"]=="")	{$message_parent["description"] = $message_parent["titre"];}
			echo "<div class='forum_citation' style='margin-top:0px;margin-bottom:25px;'><img src=\"".PATH_TPL."module_forum/quote2.png\" class='forum_citation_ico' /><i>".auteur($message_parent["id_utilisateur"],$message_parent["invite"])."</i> :<br>".$message_parent["description"]."</div>";
		}
		////	TITRE
		echo $trad["titre"]." <input type='text' name='titre' id='titre' value=\"".@$message_tmp["titre"]."\" style='width:400px;' />";
		////	DESCRIPTION
		echo "<br><br><textarea name='description' id='description' class='tinymce_textarea'>".@$message_tmp["description"]."</textarea>";
		init_editeur_tinymce();
	echo "</fieldset>";

	////	DROITS D'ACCES ET OPTIONS
	$cfg_menu_edit = array("objet"=>$objet["message"], "id_objet"=>@$message_tmp["id_message"]);
	require_once PATH_INC."element_menu_edit.inc.php";
	?>

	<div class="submit_edit_element">
		<input type="hidden" name="id_message" value="<?php echo @$message_tmp["id_message"]; ?>" />
		<input type="hidden" name="id_message_parent" value="<?php echo $message_tmp["id_message_parent"]; ?>" />
		<input type="hidden" name="id_sujet" value="<?php echo $message_tmp["id_sujet"]; ?>" />
		<input type="submit" value="<?php echo $trad["valider"]; ?>" class="button_big" />
	</div>

</form>


<?php require PATH_INC."footer.inc.php"; ?>