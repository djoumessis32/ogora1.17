<?php
////	INIT
require_once "../".$_REQUEST["module_path"]."/commun.inc.php";
require_once PATH_INC."header.inc.php";
$objet_dossier = $objet[$_REQUEST["type_objet_dossier"]];
if(@$_REQUEST["id_dossier"]>0)	{ $dossier_tmp = objet_infos($objet_dossier,$_REQUEST["id_dossier"]);   droit_acces_controler($objet_dossier, $dossier_tmp, 3); }
else							{ $dossier_tmp["id_dossier_parent"] = $_REQUEST["id_dossier_parent"]; }


////	VALIDATION DU FORMULAIRE
////
if(isset($_POST["id_dossier"]))
{
	////	INIT
	$corps_sql = " nom=".db_format($_POST["nom"]).", description=".db_format($_POST["description"]).", raccourci=".db_format(@$_POST["raccourci"],"bool");
	////	MODIFIER
	if($_POST["id_dossier"] > 0)
	{
		db_query("UPDATE ".$objet_dossier["table_objet"]." SET ".$corps_sql." WHERE id_dossier=".intval($_POST["id_dossier"]));
		add_logs("modif", $objet_dossier, $_POST["id_dossier"]);
		// On renome le dossier sur le disque (Gestionnaire de fichiers + pas dossier racine)
		if(is_dossier_racine($objet_dossier,$_POST["id_dossier"])==false && $objet_dossier["type_objet"]=="fichier_dossier")
		{
			$chemin_dossier_parent = PATH_MOD_FICHIER.chemin($objet_dossier,$_POST["id_dossier_parent"],"url");
			$chemin_ancien_dossier = $chemin_dossier_parent.objet_infos($objet_dossier,$_POST["id_dossier"],"nom_reel")."/";
			if(is_writable($chemin_ancien_dossier) && rename($chemin_ancien_dossier, $chemin_dossier_parent.$_POST["id_dossier"]."/")) {
				db_query("UPDATE ".$objet_dossier["table_objet"]." SET nom_reel=".db_format($_POST["id_dossier"])." WHERE id_dossier=".intval($_POST["id_dossier"]));
			}
		}
	}
	////	AJOUTER
	else
	{
		db_query("INSERT INTO ".$objet_dossier["table_objet"]." SET id_dossier_parent='".intval($_POST["id_dossier_parent"])."', date_crea=".db_date_now().", id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."', invite=".db_format(@$_POST["invite"]).", ".$corps_sql);
		$_POST["id_dossier"] = db_last_id();
		add_logs("ajout", $objet_dossier, $_POST["id_dossier"]);
		// Gestionnaire de fichiers : On créé le dossier sur le disque
		if($objet_dossier["type_objet"]=="fichier_dossier")
		{
			$chemin_dossier = PATH_MOD_FICHIER.chemin($objet_dossier,$_POST["id_dossier_parent"],"url").$_POST["id_dossier"];
			@mkdir($chemin_dossier);
			if(is_writable($chemin_dossier))	{ db_query("UPDATE ".$objet_dossier["table_objet"]." SET nom_reel=".db_format($_POST["id_dossier"])." WHERE id_dossier=".intval($_POST["id_dossier"]));  @chmod($chemin_dossier,0775); }
			else								{ alert($trad["MSG_ALERTE_acces_dossier"]); }
		}
	}

	////	AFFECTATION DES DROITS D'ACCÈS
	affecter_droits_acces($objet_dossier, $_POST["id_dossier"]);

	////	ON AFFECTE LES MEMES DROITS D'ACCÈS AUX SOUS DOSSIERS ?
	if(isset($_POST["droits_ss_dossiers"]))
	{
		foreach(arborescence($objet_dossier,$_POST["id_dossier"],"tous") as $sous_dossier_tmp){
			affecter_droits_acces($objet_dossier,$sous_dossier_tmp["id_dossier"]);
		}
	}

	////	FERMETURE
	reload_close();
}
?>


<style type="text/css">  body { background-image:url('<?php echo PATH_TPL; ?>divers/fond_dossier_edit.png'); }  </style>
<script type="text/javascript">
////	Redimensionne
resizePopupLightbox(650,550);

////	CONTROLE VALIDATION FINALE
function controle_formulaire()
{
	// Il doit y avoir un nom (sauf pour le dossier racine)
	if("<?php echo @$dossier_tmp["id_dossier"]; ?>"!="1" && get_value("nom").length==0){
		alert("<?php echo $trad["specifier_nom"]; ?>");
		return false;
	}
	// Controle le nombre de groupes et d'utilisateurs
	if(Controle_Menu_Objet()==false)	{return false;}
	// nom du dossier existe déja ?
	requete_ajax("dossier_edit_verif.php?nom="+urlencode(get_value("nom"))+"&id_dossier=<?php echo @$dossier_tmp["id_dossier"]; ?>&id_dossier_parent=<?php echo @$dossier_tmp["id_dossier_parent"]; ?>&type_objet_dossier=<?php echo @$_REQUEST["type_objet_dossier"]; ?>&module_path=<?php echo @$_REQUEST["module_path"]; ?>");
	if(trouver("oui",retour_ajax))	{return confirm("<?php echo $trad["MSG_ALERTE_nom_dossier"]; ?>");}
}
</script>


<form class="form_edit_element" action="<?php echo php_self(); ?>" method="post" OnSubmit="return controle_formulaire();">
	<fieldset style="text-align:center;<?php if(@$dossier_tmp["id_dossier"]==1){echo "display:none;";} ?>" >
		<?php echo $trad["nom"]; ?>
		<input type="text" name="nom" value="<?php echo @$dossier_tmp["nom"]; ?>" style="width:400px;margin-top:5px;" />
		<img src="<?php echo PATH_TPL; ?>divers/description.png" OnClick="afficher_dynamic('div_description');" <?php echo infobulle($trad["description"]); ?> class="lien" style="vertical-align:top;" /><br><br>
		<textarea name="description" id="div_description" style="<?php if(@$dossier_tmp["description"]=="") echo "display:none;"; ?>width:99%;height:30px;"><?php echo @$dossier_tmp["description"]; ?></textarea>
	</fieldset>

	<?php
	////	DROITS D'ACCES ET OPTIONS
	$cfg_menu_edit = array("objet"=>$objet_dossier, "id_objet"=>@$dossier_tmp["id_dossier"], "notif_mail"=>false, "fichiers_joint"=>false);
	if(@$dossier_tmp["id_dossier"]<1 && $dossier_tmp["id_dossier_parent"]>1)	{$cfg_menu_edit["id_parent_recup_droits"] = $dossier_tmp["id_dossier_parent"];}  // New dossier : récup les droits d'accès du dossier parent (sauf dossier racine)
	require_once PATH_INC."element_menu_edit.inc.php";
	?>

	<div class="submit_edit_element">
		<input type="hidden" name="id_dossier" value="<?php echo @$dossier_tmp["id_dossier"]; ?>" />
		<input type="hidden" name="id_dossier_parent" value="<?php echo $dossier_tmp["id_dossier_parent"]; ?>" />
		<input type="hidden" name="module_path" value="<?php echo $_REQUEST["module_path"]; ?>" />
		<input type="hidden" name="type_objet_dossier" value="<?php echo $_REQUEST["type_objet_dossier"]; ?>" />
		<input type="submit" value="<?php echo $trad["valider"]; ?>" class="button_big" />
	</div>
</form>


<?php require PATH_INC."footer.inc.php"; ?>