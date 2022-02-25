<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";
if(@$_REQUEST["id_sujet"] > 0)	{ $sujet_tmp = objet_infos($objet["sujet"],$_REQUEST["id_sujet"]);	droit_acces_controler($objet["sujet"], $sujet_tmp, 3); }
else							{ if(droit_ajout_sujet()!=true) exit;  }


////	VALIDATION DU FORMULAIRE
////
if(isset($_POST["id_sujet"]))
{
	////	MODIF / AJOUT
	$corp_sql = " titre=".db_format($_POST["titre"]).", description=".db_format($_POST["description"],"editeur").", id_theme=".db_format(@$_POST["id_theme"]).", users_consult_dernier_message='u".$_SESSION["user"]["id_utilisateur"]."u', raccourci=".db_format(@$_POST["raccourci"],"bool")." ";
	if($_POST["id_sujet"] > 0){
		db_query("UPDATE gt_forum_sujet SET ".$corp_sql." WHERE id_sujet=".db_format($_POST["id_sujet"]));
		add_logs("modif", $objet["sujet"], $_POST["id_sujet"]);
	}
	else{
		db_query("INSERT INTO gt_forum_sujet SET date_dernier_message=".db_date_now().", date_crea=".db_date_now().", id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."', invite=".db_format(@$_POST["invite"]).", ".$corp_sql);
		$_POST["id_sujet"] = db_last_id();
		add_logs("ajout", $objet["sujet"], $_POST["id_sujet"]);
	}

	////	AFFECTATION DES DROITS D'ACCÈS  +  AJOUT DE FICHIERS JOINTS
	affecter_droits_acces($objet["sujet"], $_POST["id_sujet"], "1.5"); //sujets créés par des invités : droit "écriture limité" par défaut
	ajouter_fichiers_joint($objet["sujet"],$_POST["id_sujet"]);

	////	ENVOI DE NOTIFICATION PAR MAIL
	if(isset($_POST["notification"]))
	{
		$liste_id_destinataires = users_affectes($objet["sujet"], $_POST["id_sujet"]);
		$objet_mail = $trad["FORUM_mail_nouveau_sujet_cree"]." ".$_SESSION["user"]["nom"]." ".$_SESSION["user"]["prenom"];
		$contenu_mail = $_POST["titre"];
		if($_POST["description"]!="")	{ $contenu_mail .= "<br><br>".$_POST["description"]; }
		envoi_mail($liste_id_destinataires, $objet_mail, texteNotification($contenu_mail), array("notif"=>true));
	}

	////	FERMETURE
	reload_close();
}
?>


<script type="text/javascript">
////	Redimensionne
resizePopupLightbox(800,800);

////	CONTROLE VALIDATION FINALE
function controle_formulaire()
{
	if(get_value("titre").length==0 && tinymce_vide("description")){
		alert("<?php echo $trad["specifier_titre_description"]; ?>");
		return false;
	}
	if(Controle_Menu_Objet()==false)	return false;
}

////	LISTE DES ESPACES OU UN THEME EST AFFECTE
function info_espaces_theme(id_theme)
{
	afficher("themes_espaces",false);
	if(id_theme > 0 && tab_themes_espaces[id_theme]!=""){
		afficher("themes_espaces",true,"block");
		element("themes_espaces").innerHTML = "<img src='<?php echo PATH_TPL; ?>divers/important_small.png' />&nbsp; "+tab_themes_espaces[id_theme];
	}
}
var tab_themes_espaces = new Array();
</script>


<form class="form_edit_element" action="<?php echo php_self(); ?>" method="post" enctype="multipart/form-data" OnSubmit="return controle_formulaire();">

	<?php
	echo "<fieldset>";
		////	TITRE
		echo $trad["titre"]." <input type='text' name='titre' id='titre' value=\"".@$sujet_tmp["titre"]."\" style='width:400px;margin-right:25px;' />";
		////	THEME
		$themes_sujets = themes_sujets("sujet_edit");
		if($_SESSION["cfg"]["espace"]["forum_id_theme"]!="" && @$sujet_tmp["id_sujet"]<1)	$sujet_tmp["id_theme"] = $_SESSION["cfg"]["espace"]["forum_id_theme"];
		if(count($themes_sujets)>0)
		{
			$tab_themes_espaces = "";
			echo "<img src=\"".PATH_TPL."divers/nuancier.png\" /> ".$trad["FORUM_theme_sujet"]."&nbsp;";
			echo "<select name='id_theme' onChange=\"style_select(this.name);info_espaces_theme(this.value);\" style='max-width:180px;'><option></option>";
			foreach($themes_sujets as $theme)
			{
				// Affiche l'option
				echo "<option value=\"".$theme["id_theme"]."\" style='color:#fff;background-color:".$theme["couleur"]."' ".($theme["id_theme"]==@$sujet_tmp["id_theme"]?"selected":"").">".$theme["titre"]."</option>";
				// Thème pas dispo sur tous les espaces : affiche le/les espaces du thème
				if($theme["id_espaces"]!="" && count(text2tab($theme["id_espaces"])) < db_valeur("select count(*) from gt_espace")){
					$theme["libelle_espaces"] = $trad["FORUM_theme_espaces"]." :<br>";
					foreach(text2tab($theme["id_espaces"]) as $id_espace_tmp)	{ $theme["libelle_espaces"] .= db_valeur("SELECT nom FROM gt_espace WHERE id_espace='".intval($id_espace_tmp)."'").", "; }
				}
				$tab_themes_espaces .= "tab_themes_espaces[".$theme["id_theme"]."] = \"".substr(@$theme["libelle_espaces"],0,-2)."\"; ";
			}
			echo "</select>";
			echo "<div id='themes_espaces' class='div_infos' style='display:none;margin-top:5px;margin-bottom:-15px;width:70%;margin-left:auto;margin-right:auto;'></div>";
			echo "<script> style_select('id_theme'); ".$tab_themes_espaces." info_espaces_theme(get_value('id_theme'));</script>";
		}
		////	DESCRIPTION
		echo "<br><br><textarea name='description' id='description' class='tinymce_textarea'>".@$sujet_tmp["description"]."</textarea>";
		init_editeur_tinymce();
	echo "</fieldset>";


	////	DROITS D'ACCES ET OPTIONS
	$cfg_menu_edit = array("objet"=>$objet["sujet"], "id_objet"=>@$sujet_tmp["id_sujet"], "acces_ecriture_obligatoire"=>true, "ecriture_limite_defaut"=>true, "infos_droits_acces"=>$trad["FORUM_infos_droits_acces"]);
	require_once PATH_INC."element_menu_edit.inc.php";
	?>

	<div class="submit_edit_element">
		<input type="hidden" name="id_sujet" value="<?php echo @$sujet_tmp["id_sujet"]; ?>" />
		<input type="submit" value="<?php echo $trad["valider"]; ?>" class="button_big" />
	</div>

</form>


<?php require PATH_INC."footer.inc.php"; ?>