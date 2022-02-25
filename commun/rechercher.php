<?php
////	INIT
require_once "../includes/global.inc.php";
require_once PATH_INC."header.inc.php";
titre_popup($trad["rechercher_espace"]);
?>


<script type="text/javascript">
////	Redimensionne
resizePopupLightbox(800,<?php echo (isset($_POST["texte_recherche"]))?650:500; ?>);
////	On contrôle les champs
function controle_formulaire()
{
	if(get_value("texte_recherche").length < 3){
		alert("<?php echo $trad["preciser_text"]; ?>");
		return false;
	}
}
////	Recherche avancee
function affiche_recherche_avancee()
{
	afficher('div_recherche_avancee','bascule','block');
	if(get_value('recherche_avancee')=="1")	{set_value('recherche_avancee','');}
	else									{set_value('recherche_avancee','1');}
}
</script>


<style>
table			{ margin:5px; }
.option_libelle	{ font-weight:bold; width:100px; vertical-align:top; }
.option_valeur	{ font-weight:bold; }
.div_checkbox	{ float:left; width:32%; }
</style>


<?php
////	FORMULAIRE DE RECHERCHE
////
echo "<form action=\"".php_self()."\" method='post' style='padding:10px;text-align:left;' OnSubmit=\"return controle_formulaire();\">";
	
	////	MOTS CLE (+ validation  + recherche avancéee?)
	echo "<table><tr>";
		echo "<td class='option_libelle'>".$trad["mots_cles"]."</td>";
		echo "<td class='option_valeur'>";
			echo "<input type='text' name='texte_recherche' id='texte_recherche' value=\"".@$_POST["texte_recherche"]."\" style='width:250px' /> &nbsp; ";
			echo "<input type='submit' value=\"".$trad["valider"]."\" class='button' />";
			echo "<span onClick=\"affiche_recherche_avancee();\" class='lien' style='margin-left:20px;'>".$trad["recherche_avancee"]." &nbsp;<img src='".PATH_TPL."divers/plus.png' /></span>";
			echo "<input type='hidden' name='recherche_avancee' value='".@$_POST["recherche_avancee"]."' />";
		echo "</td>";
	echo "</tr></table>";

	////	RECHERCHE AVANCEE
	echo "<div id='div_recherche_avancee' ".(!empty($_POST["recherche_avancee"])?"":"style='display:none;'").">";
		////	MODE DE RECHERCHE
		echo "<table><tr>";
			echo "<td class='option_libelle'>".$trad["rechercher"]."</td>";
			echo "<td class='option_valeur'>";
				echo "<select name='mode_recherche'>";
					echo "<option value='mots_certains'>".$trad["recherche_avancee_mots_certains"]."</option>";
					echo "<option value='mots_tous'>".$trad["recherche_avancee_mots_tous"]."</option>";
					echo "<option value='expression_exacte'>".$trad["recherche_avancee_expression_exacte"]."</option>";
				echo "</select>";
				if(!empty($_POST["mode_recherche"]))	{echo set_value("mode_recherche",$_POST["mode_recherche"]);}
			echo "</td>";
		echo "</tr></table>";
		////	DATE DE CREATION
		echo "<table><tr>";
			echo "<td class='option_libelle'>".$trad["rechercher_date_crea"]."</td>";
			echo "<td class='option_valeur'>";
				echo "<select name='date_crea'>";
					echo "<option value='tous'>".$trad["tous"]."</option>";
					echo "<option value='jour'>".$trad["rechercher_date_crea_jour"]."</option>";
					echo "<option value='semaine'>".$trad["rechercher_date_crea_semaine"]."</option>";
					echo "<option value='mois'>".$trad["rechercher_date_crea_mois"]."</option>";
					echo "<option value='annee'>".$trad["rechercher_date_crea_annee"]."</option>";
				echo "</select>";
				if(!empty($_POST["date_crea"]))	{echo set_value("date_crea",$_POST["date_crea"]);}
			echo "</td>";
		echo "</tr></table>";
		////	SELECTION DE MODULES
		echo "<table><tr>";
			echo "<td class='option_libelle'>".$trad["liste_modules"]."</td>";
			echo "<td class='option_valeur'>";
			foreach($_SESSION["espace"]["modules"] as $id_module => $module_tmp)
			{
				if(is_file(ROOT_PATH.$module_tmp["module_path"]."/plugin.inc.php"))
				{
					$check_tmp = (!isset($_POST["modules_recherche"]) || in_array($id_module,$_POST["modules_recherche"]))  ?  "checked=\"checked\""  :  "";
					$nom_module = ($module_tmp["module_path"]=="module_tableau_bord")  ?  $trad["TABLEAU_BORD_actualites"]  :  $trad[strtoupper($module_tmp["nom"])."_nom_module"];
					echo "<div class='div_checkbox'><input type='checkbox' name=\"modules_recherche[]\" value=\"".$id_module."\" ".$check_tmp." /> ".ucfirst($nom_module)."</div>";
				}
			}
			echo "</td>";
		echo "</tr></table>";
		////	SELECTION DE CHAMPS
		echo "<table><tr>";
		echo "<td class='option_libelle'>".$trad["liste_champs"]."</td>";
		echo "<td class='option_valeur'>";
		// Récupère les champs interrogeables (+ les objets qui les contiennent)
		$champs_recherche = array();
		foreach($_SESSION["espace"]["modules"] as $id_module => $module_tmp)	{ include_once ROOT_PATH.$module_tmp["module_path"]."/commun.inc.php"; }
		foreach($objet as $type_objet => $objet_tmp)
		{
			if(isset($objet_tmp["champs_recherche"])){
				foreach($objet_tmp["champs_recherche"] as $champ)	{ $champs_recherche[$champ][] = $type_objet; }
			}
		}
		// Affiche les champs interrogeables sur chaque objet
		foreach($champs_recherche as $champ => $types_objet)
		{
			$check_tmp = $libelle_modules = "";
			if(!isset($_POST["champs_recherche"]) || in_array($champ,$_POST["champs_recherche"]))	{$check_tmp = "checked=\"checked\"";}
			foreach($types_objet as $type_objet)
			{
				if(preg_match("/dossier/i",$type_objet) && preg_match("/dossier/i",$libelle_modules))	{continue;}
				elseif(preg_match("/dossier/i",$type_objet))			{$libelle_modules .= "<div>- ".$trad["libelles_objets"]["dossier"]."</div>";}
				elseif(isset($trad["libelles_objets"][$type_objet]))	{$libelle_modules .= "<div>- ".$trad["libelles_objets"][$type_objet]."</div>";}
				else													{$libelle_modules .= "<div>- ".$type_objet."</div>";}
			}
			echo "<div class='div_checkbox' ".infobulle($trad["liste_champs_elements"]." :<br>".$libelle_modules)."><input type='checkbox' name=\"champs_recherche[]\" value=\"".$champ."\" ".$check_tmp." /> ".ucfirst($trad[$champ])."</div>";
		}
		echo "</td>";
		echo "</tr></table>";
	echo "</div>";
echo "</form>";


////	RESULTATS DE LA RECHERCHE
////
if(isset($_POST["texte_recherche"]) && count($_POST["modules_recherche"])>0)
{
	////	RECUPERATION DES ELEMENTS VIA plugin.inc.php
	$cfg_plugin = array("resultats"=>array(),"mode"=>"recherche");
	foreach($_POST["modules_recherche"] as $id_module)
	{
		$cfg_plugin["module_path"] = $_SESSION["espace"]["modules"][$id_module]["module_path"];
		include_once ROOT_PATH.$cfg_plugin["module_path"]."/commun.inc.php";
		include_once ROOT_PATH.$cfg_plugin["module_path"]."/plugin.inc.php";
	}
	////	AFFICHAGE DES ELEMENTS
	$module_courant = "";
	foreach($cfg_plugin["resultats"] as $elem)
	{
		// On change de module ?
		if($module_courant!=$elem["module_path"])	{echo "<div style='margin:10px;margin-top:15px;'><hr /><img src=\"".PATH_TPL.$elem["module_path"]."/plugin.png\" style='float:right;' /></div>";}
		$module_courant = $elem["module_path"];
		// Prépare l'affichage du libellé
		if(is_array($_SESSION["texte_recherche"])==false)	{ $elem["libelle"] = preg_replace("/".$_SESSION["texte_recherche"]."/i", "<span class='mot_search_result'>".$_SESSION["texte_recherche"]."</span>", $elem["libelle"]); }
		else{
			foreach($_SESSION["texte_recherche"] as $mot)	{ $elem["libelle"] = preg_replace("/".$mot."/i", "<span class='mot_search_result'>".$mot."</span>", $elem["libelle"]); }
		}
		// Affiche chaque element du module
		echo "<table style=\"cursor:pointer;\"><tr>";
			echo "<td onClick=\"".$elem["lien_js_icone"]."\" valign=\"top\"><img src=\"".PATH_TPL."divers/".($elem["type"]=="dossier"?"dossier_arborescence":"point_jaune").".png\"  /></td>";
			echo "<td onClick=\"".$elem["lien_js_libelle"]."\"><b>".$elem["libelle"]."</b></td>";
		echo "</tr></table>";
	}

	////	RECHERCHE INFRUCTUEUSE
	if(count($cfg_plugin["resultats"])==0)	{echo "<div class='div_infos'>".$trad["aucun_resultat"]."</div>";}
	////	LES CHAMPS SELECTIONNES NE CORRESPONDENTS PAS AUX MODULES SELECTIONNES
	if(!isset($_POST["concordance_modules_champs"]))	{alert($trad["recherche_avancee_pas_concordance"]);}
}


////	FOOTER
require PATH_INC."footer.inc.php";
?>