<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";
if(droit_gestion_categories()!=true)  exit;
$categories = categories_evt("edition");

////	ON AJOUTE / MODIFIE UNE CATEGORIE
if(isset($_POST["action"]) && $_POST["action"]=="edit")
{
	$id_espaces_tmp = (in_array("tous",$_POST["id_espaces"]))  ?  ""  :  tab2text($_POST["id_espaces"]);
	$corps_sql = " id_espaces=".db_format($id_espaces_tmp).", titre=".db_format($_POST["titre"]).", description=".db_format($_POST["description"]).", couleur=".db_format($_POST["couleur"]);
	if($_POST["id_categorie"]>0 && $categories[$_POST["id_categorie"]]["droit"]==2)		db_query("UPDATE gt_agenda_categorie SET ".$corps_sql." WHERE id_categorie=".db_format($_POST["id_categorie"]));
	else																				db_query("INSERT INTO gt_agenda_categorie SET id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."', ".$corps_sql);
	reload_close();
}

////	ON SUPPRIME UNE CATEGORIE (SI AUTEUR OU L'ADMIN GENERAL)
if(isset($_GET["action"]) && $_GET["action"]=="suppr" && $categories[$_GET["id_categorie"]]["droit"]==2){
	db_query("DELETE FROM gt_agenda_categorie WHERE id_categorie='".intval($_GET["id_categorie"])."'");
	db_query("UPDATE gt_agenda_evenement SET id_categorie=null WHERE id_categorie='".intval($_GET["id_categorie"])."'");
	reload_close();
}
?>


<script type="text/javascript">
////    On redimensionne
resizePopupLightbox(600,600);

////	On contrôle les champs
function controle_formulaire(id_categorie)
{
	// titre obligatoire
	if(get_value("categorie"+id_categorie+"_titre").length==0)	{ alert("<?php echo $trad["specifier_titre"]; ?>");  return false; }
	// Au moins un espace de sélectionné : "C" => catégorie (cf. menu_affect_espaces(); )
	espaces_selected = false;
	for(id_espace_tmp in users_ensembles)	{ if(is_checked("E"+id_espace_tmp+"_C"+id_categorie)==true)  espaces_selected = true; }
	if(espaces_selected==false)		{ alert("<?php echo $trad["selectionner_espace"]; ?>"); return false; }
}
</script>


<?php
////	TITRE DU FORMULAIRE
echo "<fieldset class='fieldset_titre'>".$trad["AGENDA_categories_evt"]."<div style='margin-top:5px;font-weight:normal;'>".$trad["AGENDA_droit_gestion_categories"]."</div></fieldset>";

////	FORMULAIRE "AJOUTER"
echo "<button onClick=\"afficher_dynamic('categorie0_form');this.style.display='none';\" class='button_big'>".$trad["ajouter"]." &nbsp;<img src=\"".PATH_TPL."divers/plus.png\" /></button>";

////	CATEGORIES D'EVENEMENT
$categories = array_merge(array(array("id_categorie"=>0,"id_utilisateur"=>$_SESSION["user"]["id_utilisateur"],"couleur"=>"#222","id_espaces"=>"","droit"=>2)), $categories);
foreach($categories as $categorie)
{
	echo "<fieldset id='categorie".$categorie["id_categorie"]."_form' style='font-weight:bold;".($categorie["id_categorie"]==0?"display:none;":"")."'>";
	echo "<form action=\"".php_self()."\" method='post' onSubmit=\"return controle_formulaire('".$categorie["id_categorie"]."');\">";
		////	AUTEUR + TITRE + COULEURS + DESCRIPTION + AFFECTATION AUX ESPACES
		echo "<div style='text-align:right;font-style:italic;font-weight:normal;'>".$trad["cree_par"]." : ".auteur($categorie["id_utilisateur"])."</div>";
		echo $trad["titre"]." <input type='text' name='titre' value=\"".@$categorie["titre"]."\" id='categorie".$categorie["id_categorie"]."_titre' style='width:60%;height:18px;color:#fff;background-color:".$categorie["couleur"]."' /> &nbsp; ";
		echo select_couleur("categorie".$categorie["id_categorie"]."_titre","categorie".$categorie["id_categorie"]."_couleur");
		echo "<div class='lien' style='margin-top:10px;' onClick=\"afficher_dynamic('description".$categorie["id_categorie"]."');\">".$trad["description"]." <img src=\"".PATH_TPL."divers/derouler.png\" /></div>";
		echo "<textarea name='description' id='description".$categorie["id_categorie"]."' style='".(empty($categorie["description"])?"display:none;":"")."width:95%;height:30px;'>".@$categorie["description"]."</textarea>";
		echo menu_affect_espaces($categorie["id_categorie"], "C", $categorie["id_espaces"]);
		////	VALIDATION + SUPPRESSION?
		if($categorie["droit"]==2)
		{
			echo "<div style='text-align:right;'>";
				echo "<input type='hidden' name='couleur' id='categorie".$categorie["id_categorie"]."_couleur' value=\"".$categorie["couleur"]."\" />";
				echo "<input type='hidden' name='action' value='edit' />";
				echo "<input type='hidden' name='id_categorie' value='".$categorie["id_categorie"]."' />";
				if($categorie["id_categorie"]==0)	echo "<input type='submit' value=\"".$trad["ajouter"]."\" class='button' />";
				else								echo "<input type='submit' value=\"".$trad["modifier"]."\" class='button' /> &nbsp; ".icone_suppr(php_self()."?action=suppr&id_categorie=".$categorie["id_categorie"]);
			echo "</div>";
		}
	echo "</form></fieldset>";
}

require PATH_INC."footer.inc.php";
?>