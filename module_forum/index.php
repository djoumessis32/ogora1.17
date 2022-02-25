<?php
////	INIT
define("IS_MAIN_PAGE",true);
require "commun.inc.php";
require PATH_INC."header_menu.inc.php";
elements_width_height_type_affichage("100%","55px","liste",true);


////	LISTE DES SUJETS
////
$tri_sujets = tri_sql($objet["sujet"]["tri"]);
$sql_affichage = sql_affichage($objet["sujet"]);
if(preg_match("/dernier_message/",$tri_sujets))  $tri_sujets = str_replace("date_dernier_message", "id_sujet, date_dernier_message", $tri_sujets);
$sujets_total = db_tableau("SELECT * FROM gt_forum_sujet WHERE 1 ".$sql_affichage." ".sql_themes()." ".tri_sql($objet["sujet"]["tri"]));


////	INITIALISATION DES THEMES
////
$themes_sujets = themes_sujets();
$themes_sujets_bis = array_merge($themes_sujets, array(array("id_theme"=>"sans", "titre"=>$trad["FORUM_sans_theme"])));
// Affiche la liste des thèmes si aucun thème sélectionné et qu'il existe au moins un thème
$affiche_liste_themes = (count($themes_sujets)>0 && $_SESSION["cfg"]["espace"]["forum_id_theme"]=="")  ?  true  :  false;
// Affiche le menu de gestion des thèmes si on a le droit et :  avec la liste des themes OU si ya pas encore de theme..
$menu_gestion_themes  = (droit_gestion_themes()==true  &&  ($affiche_liste_themes==true || count($themes_sujets)==0))  ?  true  :  false;
?>


<table id="contenu_principal_table"><tr>
	<td id="menu_gauche_block_td">
		<div id="menu_gauche_block_flottant">
			<?php
			////	AJOUTER SUJET  &  MENU DE TRI  (ON EST DANS UN THEME)
			if($affiche_liste_themes==false)
			{
				echo "<div class='menu_gauche_block content'>";
					if(droit_ajout_sujet()==true)	echo "<div class='menu_gauche_ligne lien' onclick=\"popupLightbox('sujet_edit.php');\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/ajouter.png\" /></div><div class='menu_gauche_txt'>".$trad["FORUM_ajouter_sujet"]."</div></div><hr />";
					echo menu_tri($objet["sujet"]["tri"],"tri_sujet");
					echo "<div class='menu_gauche_ligne'><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/info.png\" /></div><div class='menu_gauche_txt'>".count($sujets_total)." ".(count($sujets_total)>1?$trad["FORUM_sujets"]:$trad["FORUM_sujet"])."</div></div>";
				echo "</div>";
			}
			////	GESTION DES THEMES ?
			if($menu_gestion_themes==true)
				echo "<div class='menu_gauche_block content'><div class='menu_gauche_ligne lien' onClick=\"popupLightbox('themes.php');\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/nuancier.png\" /></div><div class='menu_gauche_txt'>".$trad["FORUM_themes_gestion"]."</div></div></div>";
			?>
		</div>
	</td>
	<td id="forum_td">
		<?php
		////	LISTE DES THEMES
		////
		if($affiche_liste_themes==true)
		{
			foreach($themes_sujets_bis as $theme_tmp)
			{
				////	NB DE SUJETS ET NOM/DATE DU DERNIER
				$sujets_tmp = db_tableau("SELECT * FROM gt_forum_sujet WHERE 1 ".$sql_affichage." and id_theme  ".($theme_tmp["id_theme"]=="sans"?"is null":"='".$theme_tmp["id_theme"]."'")."  ORDER BY date_crea desc");
				////	AFFICHE ?  (affiche pas le thème "sans" s'il est vide...)
				if($theme_tmp["id_theme"]>0 || count($sujets_tmp)>0)
				{
					////	INFOS SUR LE DERNIER MESSAGE &  SUJET
					$tab_nb_sujets_messages = $ids_sujets = "";
					if(count($sujets_tmp)>0)	$tab_nb_sujets_messages .= "<tr><td style='width:80px'>".count($sujets_tmp)." ".(count($sujets_tmp)>1?$trad["FORUM_sujets"]:$trad["FORUM_sujet"])."</td><td><img src=\"".PATH_TPL."divers/fleche_droite.png\" /> ".$trad["FORUM_dernier_message"]." ".auteur($sujets_tmp[0]["id_utilisateur"],$sujets_tmp[0]["invite"]).",&nbsp;".temps($sujets_tmp[0]["date_crea"])."</td></tr>";
					foreach($sujets_tmp as $sujet_tmp)	{ $ids_sujets .= ",".$sujet_tmp["id_sujet"]; }
					$messages_tmp = db_tableau("SELECT * FROM gt_forum_message WHERE id_sujet IN (0".$ids_sujets.") ORDER BY date_crea desc");
					if(count($messages_tmp)>0)	$tab_nb_sujets_messages .= "<tr><td style='width:80px'>".count($messages_tmp)." ".(count($messages_tmp)>1?$trad["FORUM_messages"]:$trad["FORUM_message"])."</td><td><img src=\"".PATH_TPL."divers/fleche_droite.png\" /> ".$trad["FORUM_dernier_message"]." ".auteur($messages_tmp[0]["id_utilisateur"],$messages_tmp[0]["invite"]).",&nbsp;".temps($messages_tmp[0]["date_crea"])."</td></tr>";
					if($tab_nb_sujets_messages!="" || $tab_nb_sujets_messages!="")	$tab_nb_sujets_messages = "<table style='width:100%'>".$tab_nb_sujets_messages."</table>";
					////	AFFICHAGE
					echo "<div class='div_elem_deselect' style='".STYLE_ELEMENT_LISTE."height:80;padding:3px;margin-bottom:10px;line-height:16px;'>";
						echo "<table class='div_elem_table lien' cellpadding='5px' onClick=\"redir('".php_self()."?theme=".$theme_tmp["id_theme"]."');\"><tr>";
							echo "<td style='vertical-align:middle;'>".puce_theme($themes_sujets,$theme_tmp["id_theme"])." ".majuscule($theme_tmp["titre"])."<div style='font-weight:normal;margin:5px;'>".@$theme_tmp["description"]."</div></td>";
							echo "<td style='vertical-align:middle;width:380px;'>".$tab_nb_sujets_messages."</td>";
						echo "</tr></table>";
					echo"</div>";
				}
			}
			////	MASQUE LE MENU DE GAUCHE ET RECENTRE LA LISTE DES THEMES
			if($menu_gestion_themes==false)	echo "<script> afficher('menu_gauche_block_td',false);  element('forum_td').style.padding='0px 10% 0px 10%'; </script>";
		}
		////	LISTE DES SUJETS
		////
		else
		{
			////	CHEMIN : "THEME >> SUJET"
			if(count($themes_sujets)>0)	echo chemin_theme_sujet($themes_sujets);

			////	SUJETS
			foreach(tableau_elements_page($sujets_total) as $sujet_tmp)
			{
				////	MODIF / SUPPR / INFOS
				$cfg_menu_elem = array("objet"=>$objet["sujet"], "objet_infos"=>$sujet_tmp, "action_click_block"=>"redir('sujet.php?id_sujet=".$sujet_tmp["id_sujet"]."');");
				if(droit_acces($objet["sujet"],$sujet_tmp)==3){
					$cfg_menu_elem["modif"] = "sujet_edit.php?id_sujet=".$sujet_tmp["id_sujet"];
					$cfg_menu_elem["suppr"] = "elements_suppr.php?id_sujet=".$sujet_tmp["id_sujet"]."&num_page=".$_SESSION["cfg"]["espace"]["num_page"];
				}
				$auteur_tmp = user_infos($sujet_tmp["id_utilisateur"]);
				$cfg_menu_elem["id_div_element"] = div_element($objet["sujet"], $sujet_tmp["id_sujet"]);
					require PATH_INC."element_menu_contextuel.inc.php";
					////	PHOTO
					echo "<div style='float:right;width:70px;text-align:right'>".photo_user($auteur_tmp,abs($height_element))."</div>";
					////	TABLEAU DETAILS
					echo "<div class='div_elem_contenu'>";
						echo "<table class='table_nospace div_elem_table lien'><tr>";
							////	AUTEUR / DATE / TITRE ou DESCRIPTION (début)
							$style_pas_consult =  (is_dernier_message_consulte($sujet_tmp["users_consult_dernier_message"])==false)  ?  "text-decoration:underline;"  :  "";
							echo "<td style='padding:10px;' ".infobulle($trad["FORUM_voir_sujet"]).">";
								echo "<div style='margin-bottom:7px;".$style_pas_consult."'>".($sujet_tmp["titre"]==""?strip_tags(text_reduit($sujet_tmp["description"])):$sujet_tmp["titre"])."</div>";
								echo "<div style='font-weight:normal;'>".auteur($auteur_tmp,$sujet_tmp["invite"]).", &nbsp;".temps($sujet_tmp["date_crea"])."</div>";
							echo "</td>";
							////	NB DE MESSAGES  /  DERNIER POST (?)
							echo "<td style='padding:10px;text-align:right;width:400px;'>";
								$nbmessages = db_valeur("SELECT count(*) FROM gt_forum_message WHERE id_sujet=".$sujet_tmp["id_sujet"]." ");
								echo "<span ".($nbmessages>0?"class='lien_select2'":"").">".$nbmessages." ".($nbmessages>1?$trad["FORUM_messages"]:$trad["FORUM_message"])."</span>";
								if($sujet_tmp["auteur_dernier_message"]!=""){
									$message_id_utilisateur = $message_invité = "";
									if($sujet_tmp["auteur_dernier_message"]>0)	$message_id_utilisateur = $sujet_tmp["auteur_dernier_message"];
									else										$message_invité = $sujet_tmp["auteur_dernier_message"];
									echo " &nbsp; - &nbsp; ".$trad["FORUM_dernier_message"]." ".auteur($message_id_utilisateur,$message_invité).", &nbsp;".temps($sujet_tmp["date_dernier_message"]);
								}
							echo "</td>";
						echo "</tr></table>";
					echo "</div>";
				echo "</div>";
			}

			////	AUCUN SUJET
			if(count($sujets_total)==0)  echo "<div class='div_elem_aucun'>".$trad["FORUM_aucun_sujet"]."</div>";
			////	PAGER
			echo menu_pager(count($sujets_total));
		}
		?>
	</td>
</tr></table>


<?php require PATH_INC."footer.inc.php"; ?>