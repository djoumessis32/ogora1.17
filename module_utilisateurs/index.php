<?php
////	INIT
define("IS_MAIN_PAGE",true);
require "commun.inc.php";
require PATH_INC."header_menu.inc.php";
$affichage_users = @$_SESSION["cfg"]["espace"]["affichage_users"];
$users_total = db_tableau("SELECT * FROM gt_utilisateur WHERE 1 ".sql_utilisateurs_espace()." ".alphabet_sql("nom")." ".tri_sql($objet["utilisateur"]["tri"]));
elements_width_height_type_affichage("medium","100px","bloc");
?>


<table id="contenu_principal_table"><tr>
	<td id="menu_gauche_block_td">
		<div id="menu_gauche_block_flottant">
			<div class="menu_gauche_block content">
				<?php
				////	AFFICHAGE DES USERS DU SITE / DE L'ESPACE
				////
				if($_SESSION["user"]["admin_general"]==1 && ($affichage_users=="site" || count(espaces_affectes_user())>1))
				{
					$select_users_site = $style_users_site = "";
					if($affichage_users=="site")	{ $select_users_site = "selected";  $style_users_site = "font-weight:bold;".STYLE_SELECT_RED;   }
					echo "<div class='menu_gauche_ligne'>";
						echo "<div class='menu_gauche_img'><img src=\"".PATH_TPL."module_utilisateurs/utilisateurs.png\" /></div>";
						echo "<div class='menu_gauche_txt'>";
							echo "<select id='select_affichage_users' onChange=\"redir('index.php?affichage_users='+this.value);\" style='text-align:center;".$style_users_site."'>";
								echo "<option value='espace'>".$trad["UTILISATEURS_utilisateurs_espace"]."</option>";
								echo "<option value='site' ".infobulle($trad["UTILISATEURS_utilisateurs_site_infos"])." ".$select_users_site.">".$trad["UTILISATEURS_utilisateurs_site"]."</option>";
							echo "</select>";
						echo "</div>";
					echo "</div>";
					echo "<hr />";
				}
				////	MENU ADMINISTRATEUR D'ESPACE
				////
				if($_SESSION["espace"]["droit_acces"]==2)
				{
					////	AJOUT NEW USER
					if($affichage_users=="site")	{ $add_user_infobulle = $trad["UTILISATEURS_ajouter_utilisateur_site"];		$add_user_link = ""; }
					else							{ $add_user_infobulle = $trad["UTILISATEURS_ajouter_utilisateur_espace"];	$add_user_link = "?add_espace_courant=1"; }
					echo "<div class='menu_gauche_ligne lien' onclick=\"popupLightbox('utilisateur_edit.php".$add_user_link."');\" ".infobulle($add_user_infobulle)."><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/ajouter.png\" /></div><div class='menu_gauche_txt'>".$trad["UTILISATEURS_ajouter_utilisateur"]."</div></div>";
					////	AFFECTER USER EXISTANT A L'ESPACE (S'IL NE SONT PAS DEJA TOUS AFFECTES)
					if(db_valeur("SELECT count(*) FROM gt_utilisateur")!=count($users_total))
						{echo "<div class='menu_gauche_ligne lien' onclick=\"popupLightbox('utilisateur_affecter.php',true);\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/ajouter.png\" /></div><div class='menu_gauche_txt'>".$trad["UTILISATEURS_affecter_utilisateur"]."</div></div>";}
				}
				////	EXPORTER/IMPORTER DES CONTACTS
				if($_SESSION["user"]["id_utilisateur"]>0)
					{echo "<div class='menu_gauche_ligne lien' onclick=\"popupLightbox('".PATH_DIVERS."contact_export.php?type_export_import=users',true);\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/export_import.png\" /></div><div class='menu_gauche_txt'>".$trad["exporter"]." / ".$trad["importer"]." ".$trad["export_import_users"]."</div></div>";}
				////	ENVOI DES COORDONNEES DE CONNEXION PAR MAIL (REGENERE LE PASSWORD !!)
				if($_SESSION["user"]["admin_general"]==1)
					{echo "<div class='menu_gauche_ligne lien' onclick=\"popupLightbox('envoi_coordonnees.php');\" ".infobulle($trad["UTILISATEURS_envoi_coordonnees_infos"])."><div class='menu_gauche_img'><img src=\"".PATH_TPL."module_utilisateurs/envoi_coordonnees.png\" /></div><div class='menu_gauche_txt'>".$trad["UTILISATEURS_envoi_coordonnees"]."</div></div>";}
				////	ENVOI INVITATION PAR MAIL
				if(@$_SESSION["user"]["envoi_invitation"]=="1")
					{echo "<div class='menu_gauche_ligne lien' onclick=\"popupLightbox('invitation.php');\" ".infobulle($trad["UTILISATEURS_envoi_invitation_info"])."><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/envoi_mail.png\" /></div><div class='menu_gauche_txt'>".$trad["UTILISATEURS_envoi_invitation"]."</div></div>";}
				////	GESTION DES GROUPES D'UTILISATEUR
				if(droit_ajout_groupe()==true)
				{
					echo "<hr />";
					echo "<div class='menu_gauche_ligne'>";
						echo "<div class='menu_gauche_img'><img src=\"".PATH_TPL."module_utilisateurs/utilisateurs_groupe.png\" /></div>";
						echo "<div class='menu_gauche_txt'>";
							echo "<div class='lien' onclick=\"popupLightbox('groupes.php');\" ".infobulle($trad["UTILISATEURS_groupe_infos"]).">".($affichage_users=="espace"?$trad["UTILISATEURS_groupe_espace"]:$trad["UTILISATEURS_groupe_site"])."</div>";
							$groupes_users = ($affichage_users=="site")  ?  groupes_users()  :  groupes_users($_SESSION["espace"]["id_espace"]);
							foreach($groupes_users as $groupe_tmp)	{ echo "<div ".infobulle($groupe_tmp["users_title"])." style='margin-top:8px;cursor:help;'><img src=\"".PATH_TPL."divers/point_blanc.png\" style='height:10px;' /> ".$groupe_tmp["titre"]."</div>"; }
						echo "</div>";
					echo "</div>";
				}
				echo "<hr />";
				////	MENU ELEMENTS
				$cfg_menu_elements = array("objet"=>$objet["utilisateur"], "confirmer_suppr_bis"=>true);
				require PATH_INC."elements_menu_selection.inc.php";
				////	MENU ALPHABET / D'AFFICHAGE / DE TRI
				echo menu_alphabet("gt_utilisateur", "nom", sql_utilisateurs_espace(), php_self());
				echo menu_type_affichage();
				echo menu_tri($objet["utilisateur"]["tri"],"tri_utilisateur");
				echo "<div class='menu_gauche_ligne'><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/info.png\" /></div><div class='menu_gauche_txt'>".count($users_total)." ".(count($users_total)>1?$trad["UTILISATEURS_utilisateurs"]:$trad["UTILISATEURS_utilisateur"])."</div></div>";
				?>
			</div>
		</div>
	</td>
	<td>
		<?php
		////	AFFICHAGE DES UTILISATEURS
		////
		foreach(tableau_elements_page($users_total) as $user_tmp)
		{
			////	INIT
			$lien_popup = popup_user($user_tmp["id_utilisateur"]);
			$droit_modif_utilisateur = droit_modif_utilisateur($user_tmp["id_utilisateur"]);
			$id_menu_contextuel = "menu_context_user_".$user_tmp["id_utilisateur"];
			// Statut user
			if($user_tmp["admin_general"]==1)											{ $statut_user = "admin_general"; }
			elseif(droit_acces_espace($_SESSION["espace"]["id_espace"],$user_tmp)==2)	{ $statut_user = "admin_espace"; }
			elseif($affichage_users=="site")											{ $statut_user = "user_site"; }
			else																		{ $statut_user = "user_espace"; }

			////	NOM-PRENOM / DETAILS (adresse, tel, etc)
			$nom_prenom_user = $user_tmp["civilite"]." ".$user_tmp["prenom"]." ".$user_tmp["nom"];
			$separateur = ($_REQUEST["type_affichage"]=="bloc")  ?  "<br>"  :  "<img src=\"".PATH_TPL."divers/separateur.gif\" />";
			$details_user = "";
			if($user_tmp["adresse"]!="" || $user_tmp["codepostal"]!="" || $user_tmp["ville"]!="")	{$details_user .= $user_tmp["adresse"]." ".$user_tmp["codepostal"]." ".$user_tmp["ville"]." ".carte_localisation($user_tmp).$separateur;}
			if($user_tmp["telephone"]!="")			{$details_user .= $user_tmp["telephone"].$separateur;}
			if($user_tmp["telmobile"]!="")			{$details_user .= $user_tmp["telmobile"].$separateur;}
			if($user_tmp["mail"]!="")				{$details_user .= "<a href=\"mailto:".$user_tmp["mail"]."\" class='lien'>".$user_tmp["mail"]."</a>".$separateur;}
			if($user_tmp["societe_organisme"]!="")	{$details_user .= $user_tmp["societe_organisme"].$separateur;}
			if($user_tmp["fonction"]!="")			{$details_user .= $user_tmp["fonction"].$separateur;}
			$details_user = substr($details_user, 0, strrpos($details_user,$separateur));//Enleve le dernier séparateur

			////	DIV CONTENEUR
			$id_div_user = div_element($objet["utilisateur"], $user_tmp["id_utilisateur"]);
				// MENU CONTEXTUEL
				echo "<div class='noprint' style='float:left;margin:0px;'>";
					echo "<div class='menu_context' id='".$id_menu_contextuel."'>";
						//	STATUS DE L'UTILISATEUR
						echo "<div style='text-align:center;".(preg_match("/admin/i",$statut_user)?STYLE_SELECT_YELLOW:"")."'>".$trad["UTILISATEURS_".$statut_user]."</div>";
						//	MODIFIER  +  DESAFFECTER DE L'ESPACE  +  SUPPRIME DEFINITIVEMENT  +  MESSENGER
						$menu_edit = "";
						if($droit_modif_utilisateur==1)											{$menu_edit .= "<div class='menu_context_ligne lien' onclick=\"popupLightbox('utilisateur_edit.php?id_utilisateur=".$user_tmp["id_utilisateur"]."');\"><div class='menu_context_img'><img src=\"".PATH_TPL."divers/crayon.png\" /></div><div class='menu_context_txt'>".$trad["UTILISATEURS_modifier"]."</div></div>";}
						if($affichage_users=="espace" && $_SESSION["espace"]["droit_acces"]==2)	{$menu_edit .= "<div class='menu_context_ligne lien' onclick=\"confirmer('".addslashes($trad["UTILISATEURS_confirm_desaffecter_utilisateur"])."','elements_suppr.php?action=desaffectation&id_utilisateur=".$user_tmp["id_utilisateur"]."');\"><div class='menu_context_img'><img src=\"".PATH_TPL."divers/supprimer.png\" /></div><div class='menu_context_txt'>".$trad["UTILISATEURS_desaffecter"]."</div></div>";}
						if($_SESSION["user"]["admin_general"]==1)								{$menu_edit .= "<div class='menu_context_ligne lien' onclick=\"confirmer('".addslashes($trad["UTILISATEURS_confirm_suppr_utilisateur"])."','elements_suppr.php?action=suppression&id_utilisateur=".$user_tmp["id_utilisateur"]."');\"><div class='menu_context_img'><img src=\"".PATH_TPL."divers/supprimer.png\" /></div><div class='menu_context_txt'>".$trad["UTILISATEURS_suppr_definitivement"]."</div></div>";}
						if($droit_modif_utilisateur==1)											{$menu_edit .= "<div class='menu_context_ligne lien' onclick=\"popupLightbox('utilisateur_messenger.php?id_utilisateur=".$user_tmp["id_utilisateur"]."');\"><div class='menu_context_img'><img src=\"".PATH_TPL."divers/messenger_small.png\" /></div><div class='menu_context_txt'>".$trad["UTILISATEURS_gestion_messenger_livecounter"]."</div></div>";}
						//	ESPACES DE L'UTILISATEUR
						if($affichage_users=="site")
						{
							$liste_espaces = espaces_affectes_user($user_tmp,true);
							$text_espaces = $trad["UTILISATEURS_liste_espaces"]." :";
							if(count($liste_espaces)==0)	{ $text_espaces .= "<br>".$trad["UTILISATEURS_aucun_espace"]; }
							else							{    foreach($liste_espaces as $espace_tmp)  { $text_espaces .= "<br>".$espace_tmp["nom"]; }    }
							$menu_edit .= "<div class='menu_context_ligne'><div class='menu_context_img' style='vertical-align:top'><img src=\"".PATH_TPL."divers/espaces.png\" /></div><div class='menu_context_txt'>".$text_espaces."</div></div>";
						}
						if($menu_edit!="")	{echo "<hr />".$menu_edit;}
					echo "</div>";
					// ICONE "PLUS" & AFFICHAGE DU MENU CONTEXTUEL
					echo "<img src=\"".PATH_TPL."divers/options.png\" style='height:26px;' id='icone_".$id_menu_contextuel."' />";
					echo "<script type='text/javascript'> menu_contextuel('".$id_menu_contextuel."','".$id_div_user."'); </script>";
				echo "</div>";

				// SIMPLE CLICK SUR LE BLOCK -> SELECTION DU BLOCK  /  DOUBLE CLICK SUR LE BLOCK -> MODIFIER
				$dblclickBlock = ($droit_modif_utilisateur==1)  ?  "popupLightbox('utilisateur_edit.php?id_utilisateur=".$user_tmp["id_utilisateur"]."');"  :  "";
				echo "<script type='text/javascript'>  click_dblclick(\"".$id_div_user."\", \"selection_element('".$id_div_user."');\", \"".$dblclickBlock."\");  </script>";

				//	STATUT DE L'UTILISATEUR FLOTTANT (affichage block)
				$img_statut_user = (preg_match("/admin/i",$statut_user))  ?  "&nbsp; <img src=\"".PATH_TPL."module_utilisateurs/acces_".$statut_user.".png\" style='cursor:help;height:20px;' ".infobulle($trad["UTILISATEURS_".$statut_user])." />"  :  "";
				if($_REQUEST["type_affichage"]=="bloc" && $img_statut_user!="")	{echo "<div style='float:right;margin:3px;'>".$img_statut_user."</div>";}

				////	AFFICHAGE BLOCK / LISTE
				echo "<div class='div_elem_contenu'>";
					echo "<table class='div_elem_table'><tr>";
					if($_REQUEST["type_affichage"]=="bloc")
					{
						echo "<td class='div_elem_image lien' ".$lien_popup.">".photo_user($user_tmp,80)."</td>";
						echo "<td style='vertical-align:middle'>";
							echo "<div style='font-size:12px;' ".$lien_popup.">".$nom_prenom_user."</div>";
							echo "<span style='margin-bottom:5px;font-size:11px;line-height:15px;' >".$details_user."</span>";
						echo "</td>";
					}
					else
					{
						echo "<td class='div_elem_td lien' style='width:60px;text-align:center;'>".photo_user($user_tmp,38)."</td>";
						echo "<td class='div_elem_td'><span ".$lien_popup.">".$nom_prenom_user.$img_statut_user."</span></td>";
						echo "<td class='div_elem_td div_elem_td_right'>".$details_user."</td>";
					}
					echo "</tr></table>";
				echo "</div>";
			////	FIN DIV USER
			echo "</div>";
		}
		////	PAS D'UTILISATEUR
		if(count($users_total)==0)	{echo "<div class='div_elem_aucun'>".$trad["UTILISATEURS_aucun_utilisateur"]."</div>";}

		////	PAGER
		echo menu_pager(count($users_total));
		?>
	</td>
</tr></table>


<?php require PATH_INC."footer.inc.php"; ?>