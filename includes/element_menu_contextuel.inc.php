<?php
////	INIT
////
if(!isset($cfg_menu_elem["objet_infos"]))	{$cfg_menu_elem["objet_infos"] = objet_infos($cfg_menu_elem["objet"], $cfg_menu_elem["id_objet"]);}
else										{$cfg_menu_elem["id_objet"] = $cfg_menu_elem["objet_infos"][$cfg_menu_elem["objet"]["cle_id_objet"]];}
$id_menu_contextuel = "menu_context_".rand(1,99)."_".$cfg_menu_elem["objet"]["type_objet"]."_".$cfg_menu_elem["id_objet"];// "rand()" pour afficher 2 menus contextuels du même elem (ex: menu d'agendas)
$objet_independant = objet_independant($cfg_menu_elem["objet"],$cfg_menu_elem["objet_infos"]);
$dossier_racine = is_dossier_racine($cfg_menu_elem["objet"],$cfg_menu_elem["id_objet"]);
$txt_lecture = $txt_ecriture_limit = $txt_ecriture = "";
$icone_element_individuel = true;

////	POSITIONNEMENT DU CONTENEUR
////
if(@$cfg_menu_elem["taille_icone"]=="big")				{$div_elem_style = "float:left;margin:-4px;margin-right:15px;";}	//menu d'édition de l'agenda
elseif(@$cfg_menu_elem["taille_icone"]=="small_inline")	{$div_elem_style = "display:inline;margin-left:5px;";}				//Menu "Inline"
else													{$div_elem_style = "float:left;margin:0px;";}						//Menu "bloc"


////	DROITS D'ACCES / VISIBILITE (OBJETS INDEPENDENT)
////
if($_SESSION["user"]["id_utilisateur"]>0 && $objet_independant==true)
{
	////	AFFECTATION AUX INVITES
	foreach(objet_affectations($cfg_menu_elem["objet"],$cfg_menu_elem["id_objet"],"invites") as $affect_tmp){
		txt_affectations($affect_tmp["droit"], $affect_tmp["nom"]." (".$trad["invites"].")<br>");
		$icone_element_individuel = false;
	}
	////	AFFECTATION A TOUS LES UTILISATEURS
	foreach(objet_affectations($cfg_menu_elem["objet"],$cfg_menu_elem["id_objet"],"espaces") as $affect_tmp){
		txt_affectations($affect_tmp["droit"], $affect_tmp["nom"]." (".$trad["tous"].")<br>");
		$icone_element_individuel = false;
	}
	////	AFFECTATION AUX GROUPES
	foreach(objet_affectations($cfg_menu_elem["objet"],$cfg_menu_elem["id_objet"],"groupes") as $affect_tmp){
		txt_affectations($affect_tmp["droit"], $affect_tmp["titre"]."<br>");
		$icone_element_individuel = false;
	}
	////	AFFECTATION AUX UTILISATEURS
	foreach(objet_affectations($cfg_menu_elem["objet"],$cfg_menu_elem["id_objet"],"users") as $affect_tmp){
		txt_affectations($affect_tmp["droit"], auteur($affect_tmp).", ");
		if($affect_tmp["id_utilisateur"]!=$_SESSION["user"]["id_utilisateur"])	{$icone_element_individuel = false;}
	}
	////	AFFECTATION A TOUS LES ESPACES
	foreach(objet_affectations($cfg_menu_elem["objet"],$cfg_menu_elem["id_objet"],"tous_espaces") as $affect_tmp){
		txt_affectations($affect_tmp["droit"], $trad["EDIT_OBJET_tous_utilisateurs_espaces"]."<br>");
		$icone_element_individuel = false;
	}

	////	ENLEVE LES VIRGULES DANS LES TEXTES
	$txt_lecture = trim($txt_lecture, ", ");
	$txt_ecriture_limit = trim($txt_ecriture_limit, ", ");
	$txt_ecriture = trim($txt_ecriture, ", ");

	////	INFOS COMPLEMENTAIRES SUR LES DROITS D'ECRITURE
	$txt_ecriture_limit_info = $txt_ecriture_info = "";
	// DROIT D'ECRITURE AUTEUR / ADMIN GENERAL
	if(isset($cfg_menu_elem["objet"]["type_contenu"]))	{$acces_ecriture_auteur = "<div style='margin-top:10px;'>".change_libelles_objets($cfg_menu_elem["objet"],$trad["ecriture_auteur_admin"])."</div>";}
	// ECRITURE LIMITE
	if($txt_ecriture_limit!="")		{$txt_ecriture_limit_info = change_libelles_objets($cfg_menu_elem["objet"],$trad["ecriture_limit_infos"]).$acces_ecriture_auteur;}
	// ECRITURE (accès à l'élément / au conteneur)
	if($txt_ecriture!="" && !isset($cfg_menu_elem["objet"]["type_contenu"]))	{$txt_ecriture_info = $trad["ecriture_infos"];}
	elseif($txt_ecriture!="")													{$txt_ecriture_info = change_libelles_objets($cfg_menu_elem["objet"],$trad["ecriture_infos_conteneur"]).$acces_ecriture_auteur;}
	// DOSSIER RACINE : ACCES ECRITURE PAR DEFAUT
	if($dossier_racine==true && $txt_lecture=="" && $txt_ecriture_limit=="" && $txt_ecriture=="")	{$txt_ecriture = $trad["ecriture_racine_defaut"];}
}



////	AFFICHE LE MENU CONTEXTUEL
////
echo "<div class='noprint' style='".$div_elem_style."'>";

	////	AFFICHAGE DU MENU CONTEXTUEL DE L'ELEMENT
	////
	echo "<div class='menu_context' id='".$id_menu_contextuel."'>"; 

		////	FICHIER : NOMBRE DE TELECHARGEMENTS + SELECTION (images occupent tout le bloc..)
		if($cfg_menu_elem["objet"]["type_objet"]=="fichier"){
			echo "<div class='menu_context_ligne lien' onClick=\"selection_element('".$cfg_menu_elem["id_div_element"]."');\"><div class='menu_context_img'><img src=\"".PATH_TPL."divers/check.png\" /></div><div class='menu_context_txt'>".$trad["select_deselect"]."</div></div>";
			echo "<div class='menu_context_ligne lien' onClick=\"window.open('telecharger.php?id_fichier=".$cfg_menu_elem["id_objet"]."');\"><div class='menu_context_img'><img src=\"".PATH_TPL."divers/telecharger.png\" /></div><div class='menu_context_txt'>".$trad["telecharge_nb"]."  ".$cfg_menu_elem["objet_infos"]["nb_downloads"]."  ".$trad["telecharge_nb_bis"]."</div></div><hr class='menu_context_hr' />";
		}

		////	MODIFIER / DEPLACER
		if(isset($cfg_menu_elem["modif"]))		{echo "<div class='menu_context_ligne lien' onclick=\"popupLightbox('".$cfg_menu_elem["modif"]."');\"><div class='menu_context_img'><img src=\"".PATH_TPL."divers/crayon.png\" /></div><div class='menu_context_txt'>".($objet_independant==true?$trad["modifier_et_acces"]:$trad["modifier"])."</div></div>";}
		if(isset($cfg_menu_elem["deplacer"]))	{echo "<div class='menu_context_ligne lien' onclick=\"popupLightbox('".$cfg_menu_elem["deplacer"]."');\"><div class='menu_context_img'><img src=\"".PATH_TPL."divers/dossier_deplacer.png\" /></div><div class='menu_context_txt'>".$trad["deplacer_elements"]."</div></div>";}

		////	SUPPRIMER
		if(isset($cfg_menu_elem["suppr"])){
			$suppr_text		 = (isset($cfg_menu_elem["suppr_text"]))  ?  $cfg_menu_elem["suppr_text"]  :  $trad["supprimer"];
			$suppr_text_confirm = (isset($cfg_menu_elem["suppr_text_confirm"]))  ?  $cfg_menu_elem["suppr_text_confirm"]  :  $trad["confirmer_suppr"];
			echo "<div class='menu_context_ligne lien' onclick=\"confirmer('".str_replace("<br>","\\n",addslashes($suppr_text_confirm))."','".$cfg_menu_elem["suppr"]."');\"><div class='menu_context_img'><img src=\"".PATH_TPL."divers/supprimer.png\" /></div><div class='menu_context_txt'>".$suppr_text."</div></div>";
		}

		////	OPTIONS DIVERSES
		if(isset($cfg_menu_elem["options_divers"])){
			foreach($cfg_menu_elem["options_divers"] as $option_tmp){
				echo "<div class='menu_context_ligne lien' onclick=\"".$option_tmp["action_js"]."\"><div class='menu_context_img'><img src=\"".$option_tmp["icone_src"]."\" /></div><div class='menu_context_txt'>".$option_tmp["text"]."</div></div>";
			}
		}

		// séparation <hr> ?
		if(isset($cfg_menu_elem["modif"]) || isset($cfg_menu_elem["deplacer"]) || isset($cfg_menu_elem["options_divers"]) || isset($cfg_menu_elem["suppr"]))
			{echo "<hr class='menu_context_hr' />";}

		////	NOMBRE D'ELEMENTS + TAILLE DU DOSSIER (module fichiers)  =>  SI ON AFFICHE UN DOSSIER (sauf dossier courant : info déjà affiché dans le block de gauche..)
		if(preg_match("/dossier/i",$cfg_menu_elem["objet"]["type_objet"]) && $cfg_menu_elem["id_objet"]!=@$_GET["id_dossier"])
			{echo "<div class='menu_context_ligne'><div class='menu_context_txt_left'>".$trad["contenu_dossier"]." :</div><div class='menu_context_txt'>".contenu_dossier($cfg_menu_elem["objet"],$cfg_menu_elem["id_objet"],"menu_element")."</div></div><hr class='menu_context_hr' />";}

		////	INFOS SUR LES DROITS D'ACCES  =>  LECTURE / ECRITURE LIMITE / ECRITURE / VISIBILITE SPECIFIQUE
		if($txt_lecture!="")								{echo "<div class='menu_context_ligne' style='".STYLE_SELECT_YELLOW."cursor:help;' ".infobulle($trad["lecture_infos"])."><div class='menu_context_txt_left'>".$trad["lecture"]." : </div><div class='menu_context_txt'>".$txt_lecture."</div></div>";}
		if($txt_ecriture_limit!="")							{echo "<div class='menu_context_ligne' style='".STYLE_SELECT_ORANGE."cursor:help;' ".infobulle(@$txt_ecriture_limit_info)."><div class='menu_context_txt_left' style='width:85px;'>".$trad["ecriture_limit"]." : </div><div class='menu_context_txt'>".$txt_ecriture_limit."</div></div>";}
		if($txt_ecriture!="")								{echo "<div class='menu_context_ligne' style='".STYLE_SELECT_RED."cursor:help;' ".infobulle(@$txt_ecriture_info)."><div class='menu_context_txt_left'>".$trad["ecriture"]." : </div><div class='menu_context_txt'>".$txt_ecriture."</div></div>";}
		if(isset($cfg_menu_elem["visibilite_specifique"]))	{echo "<span>".$cfg_menu_elem["visibilite_specifique"]."</span>";}

		////	AUTEUR/DATE CREATION  &  AUTEUR/DATE MODIF  &  HISTORIQUE DES LOGS
		if($dossier_racine==false)
		{
			if($txt_ecriture!="" || $txt_ecriture_limit!="" || $txt_lecture!="" || isset($cfg_menu_elem["visibilite_specifique"]))	{echo "<hr class='menu_context_hr' />";}
			// Création (date facultative)
			$cfg_menu_elem["auteur_tmp"] = "<span ".popup_user($cfg_menu_elem["objet_infos"]["id_utilisateur"]).">".auteur($cfg_menu_elem["objet_infos"]["id_utilisateur"],@$cfg_menu_elem["objet_infos"]["invite"])."</span>";//!!! RE-UTILISE DANS LES DETAILS DES AFFICHAGES EN MODE "LISTE" !!!
			if(@$cfg_menu_elem["objet_infos"]["date_crea"]!="")		{$cfg_menu_elem["date_crea"] = "<br>".temps($cfg_menu_elem["objet_infos"]["date_crea"],"complet");}
			echo "<div class='menu_context_ligne lien' ".popup_user($cfg_menu_elem["objet_infos"]["id_utilisateur"])."><div class='menu_context_txt_left'>".$trad["cree_par"]." : </div><div class='menu_context_txt'>".$cfg_menu_elem["auteur_tmp"].@$cfg_menu_elem["date_crea"]."</div></div>";
			// Modification
			if(@$cfg_menu_elem["objet_infos"]["date_modif"]!="")
				{echo "<div class='menu_context_ligne lien' ".popup_user($cfg_menu_elem["objet_infos"]["id_utilisateur_modif"])."><div class='menu_context_txt_left'>".$trad["modif_par"]." : </div><div class='menu_context_txt'>".auteur($cfg_menu_elem["objet_infos"]["id_utilisateur_modif"])."<br>".temps($cfg_menu_elem["objet_infos"]["date_modif"],"complet")."</div></div>";}
			// Historique des logs de mofif & accès : auteur / admin espace
			if(is_auteur($cfg_menu_elem["objet_infos"]["id_utilisateur"]) || $_SESSION["espace"]["droit_acces"]==2)
				{echo "<div class='menu_context_ligne lien' onClick=\"popupLightbox('".ROOT_PATH."module_logs/logs_element.php?module_path=".MODULE_PATH."&type_objet=".$cfg_menu_elem["objet"]["type_objet"]."&id_objet=".$cfg_menu_elem["id_objet"]."');\"><div class='menu_context_img'><img src=\"".PATH_TPL."divers/logs.png\" /></div><div class='menu_context_txt'>".$trad["historique_element"]."</div></div>";}
		}

		////	FICHIERS JOINTS
		if(!isset($cfg_menu_elem["fichiers_joint"]) || $cfg_menu_elem["fichiers_joint"]==true)
			{affiche_fichiers_joints($cfg_menu_elem["objet"],$cfg_menu_elem["id_objet"],"menu_element");}
	echo "</div>";


	////	ELEMENT PERSO : ICONE D'UTILISATEUR (placer avant l'icone d'affichage du menu, car ce denier peut être en display "float" ou "inline"..)
	////
	if($_SESSION["user"]["id_utilisateur"]>0 && $icone_element_individuel==true && $objet_independant==true && !isset($cfg_menu_elem["taille_icone"]))
		{echo "<img src=\"".PATH_TPL."module_utilisateurs/acces_utilisateur.png\" style='position:absolute;z-index:100;margin-top:25px;width:14px;cursor:help;' ".infobulle($trad["acces_perso"])." />";}


	////	ICONE D'AFFICHAGE DU MENU (TRIANGLE DANS LE COIN GAUCHE)  &&  AFFICHAGE DU MENU CONTEXTUEL  (position absolute pour que l'icone "plus" soit au dessus du contenu du bloc -> exemple des images du gestionnaire de fichier qui occupent tout le bloc)
	////
	if(empty($cfg_menu_elem["taille_icone"]))		{$icone_height_tmp = "height:24px;";}
	elseif($cfg_menu_elem["taille_icone"]=="small")	{$icone_height_tmp = "height:17px;";}
	elseif($cfg_menu_elem["taille_icone"]=="big")	{$icone_height_tmp = "height:30px;";}
	else											{$icone_height_tmp = "";}
	$icone_pos_absolute	= (@$cfg_menu_elem["icone_plus_position_absolute"]==true)  ?  "position:absolute;"  :  "";
	$icone_src_tmp		= ($_SESSION["user"]["id_utilisateur"] > 0 && @strtotime($cfg_menu_elem["objet_infos"]["date_crea"]) > @$_SESSION["user"]["precedente_connexion"])  ?  "options_new"  :  "options";
	if(@$cfg_menu_elem["taille_icone"]=="small_inline")		{$icone_src_tmp .= "_inline";}
	echo "<img src=\"".PATH_TPL."divers/".$icone_src_tmp.".png\" class='noprint' style='".$icone_height_tmp.$icone_pos_absolute."' id='icone_".$id_menu_contextuel."' />";
	echo "<script type='text/javascript'>  menu_contextuel('".$id_menu_contextuel."','".@$cfg_menu_elem["id_div_element"]."');  </script>";


	////	SIMPLE CLICK SUR LE BLOCK -> FONCTION SPECIFIQUE OU SELECTION DU BLOCK  /  DOUBLE CLICK SUR LE BLOCK -> MODIFICATION DE L'ELEMENT
	if(isset($cfg_menu_elem["id_div_element"])){
		$clickBlock		= (isset($cfg_menu_elem["action_click_block"]))	?  $cfg_menu_elem["action_click_block"]  :  "if(typeof(selection_element)!='undefined') selection_element('".$cfg_menu_elem["id_div_element"]."');";
		$dblclickBlock	= (isset($cfg_menu_elem["modif"]))				?  "popupLightbox('".$cfg_menu_elem["modif"]."');"  :  "";
		echo "<script type='text/javascript'>  click_dblclick(\"".$cfg_menu_elem["id_div_element"]."\", \"".$clickBlock."\", \"".$dblclickBlock."\");  </script>";
	}

echo "</div>";