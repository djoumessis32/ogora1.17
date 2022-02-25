<?php 
////	AFFICHAGE DES DOSSIERS
////
$tri_dossiers = array("date_crea@@desc","date_crea@@asc","date_modif@@desc","date_modif@@asc","id_utilisateur@@asc","id_utilisateur@@desc","nom@@asc","nom@@desc");
$liste_dossiers	= db_tableau("SELECT * FROM ".$cfg_dossiers["objet"]["table_objet"]." WHERE id_dossier_parent='".$cfg_dossiers["id_objet"]."' ".sql_affichage($cfg_dossiers["objet"])."  ".tri_sql($tri_dossiers)." ");
foreach($liste_dossiers as $dossier_tmp)
{
	////	INIT + JAVASCRIPT REDIRECTION
	$dossier_tmp["droit_acces"]	= droit_acces($cfg_dossiers["objet"],$dossier_tmp);
	$dossier_racine = is_dossier_racine($cfg_dossiers["objet"],$dossier_tmp["id_dossier"]);
	$js_redir =  " class='lien' onClick=\"redir('".php_self()."?id_dossier=".$dossier_tmp["id_dossier"]."');\" ";
	////	MENU ELEMENT  &  MODIFIER / DEPLACER / SUPPRIMER
	$cfg_menu_elem = array("objet"=>$cfg_dossiers["objet"], "objet_infos"=>$dossier_tmp);
	if($dossier_tmp["droit_acces"]==3)
	{
		$cfg_menu_elem["modif"] = PATH_DIVERS."dossier_edit.php?module_path=".MODULE_PATH."&type_objet_dossier=".$cfg_dossiers["objet"]["type_objet"]."&id_dossier=".$dossier_tmp["id_dossier"];
		if($dossier_racine==false){
			$cfg_menu_elem["deplacer"] = PATH_DIVERS."deplacer.php?module_path=".MODULE_PATH."&type_objet_dossier=".$cfg_dossiers["objet"]["type_objet"]."&id_dossier_parent=".$dossier_tmp["id_dossier_parent"]."&SelectedElems[".$cfg_dossiers["objet"]["type_objet"]."]=".$dossier_tmp["id_dossier"];
			$cfg_menu_elem["suppr"] = "elements_suppr.php?id_dossier=".$dossier_tmp["id_dossier"]."&id_dossier_retour=".$dossier_tmp["id_dossier_parent"];
			$cfg_menu_elem["suppr_text_confirm"] = $trad["confirmer_suppr_dossier"];
		}
	}
	////	TELECHARGER TOUT UN DOSSIER  (GEST. FICHIERS + PAS DOSSIER RACINE + USER + ACCES EN LECTURE)
	if($cfg_dossiers["objet"]["type_objet"]=="fichier_dossier"  &&  $dossier_racine==false  &&  $_SESSION["user"]["id_utilisateur"]>0  &&  $dossier_tmp["droit_acces"]>=1)
		{$cfg_menu_elem["options_divers"][] = array("icone_src"=>PATH_TPL."divers/telecharger.png", "text"=>$trad["telecharger_dossier"], "action_js"=>"window.open('telecharger_archive.php?SelectedElems[fichier_dossier]=".$dossier_tmp["id_dossier"]."');");}

	////	DIV SELECTIONNABLE + OPTIONS
	$cfg_menu_elem["id_div_element"] = div_element($cfg_dossiers["objet"], $dossier_tmp["id_dossier"]);
	require PATH_INC."element_menu_contextuel.inc.php";
		////	AFFICHAGE BLOCK
		if($_REQUEST["type_affichage"]=="bloc")
		{
			echo "<div class='div_elem_contenu' ".infobulle(nl2br($dossier_tmp["description"]))." >";
				echo "<table class='div_elem_table div_elem_infos'>";
					// Affichage : 2 lignes / 2 colonnes
					if(str_replace("px","",$width_element)<=200) {
						echo "<tr><td style='vertical-align:middle;' ><img src=\"".PATH_TPL."divers/dossier.png\" ".$js_redir." /></td></tr>";
						echo "<tr><td ".$js_redir." > ".$dossier_tmp["nom"]."</td></tr>";
					}
					else {
						echo "<tr><td style='vertical-align:middle;text-align:center;width:80px'><img src=\"".PATH_TPL."divers/dossier.png\" ".$js_redir." /></td>";
						echo "<td style='vertical-align:middle;text-align:left;'><div ".$js_redir." style='line-height:15px;'>".$dossier_tmp["nom"]."</div></td></tr>";
					}
				echo "</table>";
			echo "</div>";
		}
		////	AFFICHAGE LISTE
		else
		{
			// Complement d'info sur le dossier  (barre d'avancement pour les taches, vignettes pour le gestionnaire de fichiers, etc)
			$affichage_liste_tmp = (isset($cfg_dossiers["fonction_affichage_liste"]) && $_REQUEST["type_affichage"]=="liste")  ?  $cfg_dossiers["fonction_affichage_liste"]($dossier_tmp)  :  "";
			// affichage du contenu
			echo "<div class='div_elem_contenu' >";
				echo "<table class='div_elem_table'><tr>";
				echo "<td class='div_elem_td' style='width:".(isset($cfg_dossiers["largeur_icone"])?$cfg_dossiers["largeur_icone"]:"70px").";text-align:center;'><img src=\"".PATH_TPL."divers/dossier.png\" ".$js_redir." style='height:42px;' /></td>";
				echo "<td class='div_elem_td'><span class='lien' ".$js_redir.">".$dossier_tmp["nom"]."</span></td>";
				$cfg_dossiers["auteur_date"] = (preg_match("/tache/i",MODULE_NOM))  ?  ""  :  "<img src=\"".PATH_TPL."divers/separateur.gif\" /> ".$cfg_menu_elem["auteur_tmp"]." <img src=\"".PATH_TPL."divers/separateur.gif\" /> ".temps($dossier_tmp["date_crea"],"date");
				echo "<td class='div_elem_td div_elem_td_right'>".$affichage_liste_tmp." ".contenu_dossier($cfg_dossiers["objet"],$dossier_tmp["id_dossier"],"menu_element")." &nbsp; ".$cfg_dossiers["auteur_date"]."</td>";
				echo "</tr></table>";
			echo "</div>";
		}
		echo "</div>";
	echo "</div>";
}