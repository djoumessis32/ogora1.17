<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";
if(droit_gestion_themes()!=true)  exit;
$themes = themes_sujets("theme_edit");


////	ON AJOUTE / MODIFIE UN THEME
////
if(isset($_POST["action"]) && $_POST["action"]=="edit")
{
	$id_espaces_tmp = (in_array("tous",$_POST["id_espaces"]))  ?  ""  :  tab2text($_POST["id_espaces"]);
	$corps_sql = " id_espaces=".db_format($id_espaces_tmp).", titre=".db_format($_POST["titre"]).", description=".db_format($_POST["description"]).", couleur='".$_POST["couleur"]."' ";
	if($_POST["id_theme"]>0 && $themes[$_POST["id_theme"]]["droit"]==2)		db_query("UPDATE gt_forum_theme SET ".$corps_sql." WHERE id_theme=".db_format($_POST["id_theme"]));
	else																	db_query("INSERT INTO gt_forum_theme SET id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."', ".$corps_sql);
	reload_close();
}

////	ON SUPPRIME UN THEME (SI ON EST L'AUTEUR OU L'ADMIN GENERAL)
////
if(isset($_GET["action"]) && $_GET["action"]=="suppr" && $themes[$_GET["id_theme"]]["droit"]==2){
	db_query("DELETE FROM gt_forum_theme WHERE id_theme=".db_format($_GET["id_theme"]));
	db_query("UPDATE gt_forum_sujet SET id_theme=null WHERE id_theme=".db_format($_GET["id_theme"]));
	reload_close();
}
?>


<script type="text/javascript">
////	On redimensionne
resizePopupLightbox(600,600);

////	On contrôle les champs
function controle_formulaire(id_theme)
{
	// titre obligatoire
	if(get_value("theme"+id_theme+"_titre").length==0)	{ alert("<?php echo $trad["specifier_titre"]; ?>");  return false; }
	// Au moins un espace de sélectionné : "T" => thème (cf. menu_affect_espaces(); )
	espaces_selected = false;
	for(id_espace_tmp in users_ensembles)	{ if(is_checked("E"+id_espace_tmp+"_T"+id_theme)==true)  espaces_selected = true; }
	if(espaces_selected==false)		{ alert("<?php echo $trad["selectionner_espace"]; ?>"); return false; }
}
</script>


<?php
////	TITRE DU FORMULAIRE
echo "<fieldset class='fieldset_titre'>".$trad["FORUM_themes_gestion"]."<div style='margin-top:5px;font-weight:normal;'>".$trad["FORUM_droit_gestion_themes"]."</div></fieldset>";

////	FORMULAIRE "AJOUTER"
echo "<button onClick=\"afficher_dynamic('theme0_form');this.style.display='none';\" class='button_big'>".$trad["ajouter"]." &nbsp;<img src=\"".PATH_TPL."divers/plus.png\" /></button>";

////	THEMES DES SUJETS
$themes = array_merge(array(array("id_theme"=>0,"id_utilisateur"=>$_SESSION["user"]["id_utilisateur"],"couleur"=>"#222","id_espaces"=>"","droit"=>2)), $themes);
foreach($themes as $theme)
{
	echo "<fieldset id=\"theme".$theme["id_theme"]."_form\" style='font-weight:bold;".($theme["id_theme"]==0?"display:none;":"")."'>";
	echo "<form action=\"".php_self()."\" method='post' onSubmit=\"return controle_formulaire('".$theme["id_theme"]."');\">";
		////	AUTEUR + TITRE + COULEURS + DESCRIPTION + AFFECTATION AUX ESPACES
		echo "<div style='float:right;font-style:italic;font-weight:normal;'>".$trad["cree_par"]." : ".auteur($theme["id_utilisateur"])."</div><br><br>";
		echo $trad["titre"]." <input type='text' name='titre' value=\"".@$theme["titre"]."\" id=\"theme".$theme["id_theme"]."_titre\" style='width:60%;height:18px;color:#fff;background-color:".$theme["couleur"]."' /> &nbsp; ";
		echo select_couleur("theme".$theme["id_theme"]."_titre","theme".$theme["id_theme"]."_couleur");
		echo "<div style='margin-top:10px;'>".$trad["description"]."</div><textarea name='description' style='width:99%;height:35px;'>".@$theme["description"]."</textarea>";
		echo menu_affect_espaces($theme["id_theme"], "T", $theme["id_espaces"]);

		////	VALIDATION + SUPPRESSION?
		if($theme["droit"]==2)
		{
			echo "<div style='text-align:right;'>";
				echo "<input type='hidden' name='couleur' id='theme".$theme["id_theme"]."_couleur' value=\"".$theme["couleur"]."\" />";
				echo "<input type='hidden' name='action' value='edit' />";
				echo "<input type='hidden' name='id_theme' value='".$theme["id_theme"]."' />";
				if($theme["id_theme"]==0)	{ echo "<input type='submit' value=\"".$trad["ajouter"]."\" class='button_big' />"; }
				else						{ echo "<input type='submit' value=\"".$trad["modifier"]."\" class='button' /> &nbsp; ".icone_suppr("themes.php?action=suppr&id_theme=".$theme["id_theme"],1,$trad["FORUM_confirm_suppr_theme"]); }
			echo "</div>";
		}
	echo "</form></fieldset>";
}

require PATH_INC."footer.inc.php";
?>