<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";
$fichier_tmp = objet_infos($objet["fichier"], $_REQUEST["id_fichier"]);
$fichier_derniere_version = infos_version_fichier($fichier_tmp["id_fichier"]);
$chemin_dossier = PATH_MOD_FICHIER.chemin($objet["fichier_dossier"],$fichier_tmp["id_dossier"],"url");
droit_acces_controler($objet["fichier"], $fichier_tmp, 2);


////	VALIDATION DU FORMULAIRE
////
if(isset($_POST["id_fichier"]))
{
	////	MODIF CONTENU DU FICHIER TEXTE
	if(!empty($_POST["contenu_fichier"]) && $_POST["contenu_fichier"]!=$_POST["contenu_fichier_old"])
	{
		$nom_reel_fichier  = $_POST["id_fichier"]."_".time().$_POST["extension"];
		$fp = fopen($chemin_dossier.$nom_reel_fichier, "w");
		fwrite($fp, stripslashes($_POST["contenu_fichier"]));//au cas ou "magic_quote_gpc" est activé..
		fclose($fp);
		db_query("INSERT INTO  gt_fichier_version  SET  id_fichier='".intval($_POST["id_fichier"])."', nom_reel=".db_format($nom_reel_fichier).", description=".db_format($_POST["description"]).", taille_octet='".filesize($chemin_dossier.$nom_reel_fichier)."', date_crea=".db_date_now().", id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."', invite=".db_format(@$_POST["invite"]));
	}

	////	MODIF DU FICHIERS + AFFECTATION DES DROITS D'ACCÈS
	db_query("UPDATE gt_fichier SET nom=".db_format($_POST["nom"].$_POST["extension"]).", description=".db_format($_POST["description"]).", raccourci=".db_format(@$_POST["raccourci"],"bool")." WHERE id_fichier=".db_format($_POST["id_fichier"]));
	affecter_droits_acces($objet["fichier"],$_POST["id_fichier"]);

	////	ENVOI DE NOTIFICATION PAR MAIL
	if(isset($_POST["notification"]))
	{
		$liste_id_destinataires = users_affectes($objet["fichier"], $_POST["id_fichier"]);
		$objet_mail = $trad["FICHIER_mail_fichier_modifie"]." ".$_SESSION["user"]["nom"]." ".$_SESSION["user"]["prenom"];
		$contenu_mail = $_POST["nom"].$_POST["extension"];
		if($_POST["description"]!="")	$contenu_mail .= "<br><br>".$_POST["description"];
		envoi_mail($liste_id_destinataires, $objet_mail, texteNotification($contenu_mail), array("notif"=>true));
	}

	////	AJOUT DANS LES LOGS  +  FERMETURE
	add_logs("modif", $objet["fichier"], $_POST["id_fichier"]);
	reload_close();
}
?>



<style type="text/css">  body {  background-image:url('<?php echo PATH_TPL; ?>module_fichier/fond_popup.png');  } </style>
<script type="text/javascript">
////	Redimensionne
resizePopupLightbox(770,<?php echo ($fichier_tmp["id_dossier"]==1)?"500":"250"; ?>);

////	CONTROLE VALIDATION FINALE
function controle_formulaire()
{
	// Il doit y avoir un nom
	if(get_value("nom").length==0){
		alert("<?php echo $trad["specifier_nom"]; ?>");
		return false;
	}
	// Controle le nombre de groupes et d'utilisateurs
	if(Controle_Menu_Objet()==false)	return false;
}
</script>


<form class="form_edit_element" action="<?php echo php_self(); ?>" method="post" OnSubmit="return controle_formulaire();">
	<?php
	////	NOM  &  DESCRIPTION  &  CONTENU DU FICHIER (text ou html)
	////
	echo "<fieldset>";
		echo "<input type='text' name='nom' value=\"".substr($fichier_tmp["nom"],0,-strlen(strrchr($fichier_tmp["nom"],".")))."\" style='width:320px;' /><input type='text' value=\"".strrchr($fichier_tmp["nom"],".")."\" style='width:30px;background-color:#ccc' disabled=disabled /> &nbsp; &nbsp; ";
		echo "<span onClick=\"afficher_dynamic('block_description');\" class='lien'>".$trad["description"]." <img src=\"".PATH_TPL."divers/derouler.png\" /></span>";
		echo "<textarea name='description' id='block_description' style='width:90%;height:50px;margin-top:10px;".($fichier_tmp["description"]==""?"display:none;":"")."'>".$fichier_tmp["description"]."</textarea>";
		// CONTENU DU FICHIER MODIFIABLE DIRECTEMENT (WEB ou TXT)
		if(controle_fichier("text",$fichier_tmp["nom"]) || controle_fichier("web", $fichier_tmp["nom"]))
		{
			$contenu_fichier = implode("", file($chemin_dossier.$fichier_derniere_version["nom_reel"]));
			echo "<br><br>".$trad["FICHIER_contenu"]."<br>";
			echo "<textarea name='contenu_fichier_old' style='display:none;'>".$contenu_fichier."</textarea>";
			echo "<textarea name='contenu_fichier' style='width:98%;height:350px;'>".$contenu_fichier."</textarea>";
			if(controle_fichier("web",$fichier_tmp["nom"]))  init_editeur_tinymce("contenu_fichier");//fichier html : affiche l'éditeur tinyMCE
		}
	echo "</fieldset>";

	////	DROITS D'ACCES ET OPTIONS
	$cfg_menu_edit = array("objet"=>$objet["fichier"], "id_objet"=>$fichier_tmp["id_fichier"], "fichiers_joint"=>false);
	require_once PATH_INC."element_menu_edit.inc.php";
	?>

	<div class="submit_edit_element">
		<input type="hidden" name="id_fichier" value="<?php echo $fichier_tmp["id_fichier"]; ?>" />
		<input type="hidden" name="extension" value="<?php echo strrchr($fichier_tmp["nom"],"."); ?>" />
		<input type="hidden" name="id_dossier" value="<?php echo $fichier_tmp["id_dossier"]; ?>" />
		<input type="submit" value="<?php echo $trad["valider"]; ?>" class="button_big" />
	</div>

</form>


<?php require PATH_INC."footer.inc.php"; ?>