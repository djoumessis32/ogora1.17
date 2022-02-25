<?php
////	INIT
define("IS_MAIN_PAGE",true);
require "commun.inc.php";
require PATH_INC."header_menu.inc.php";
elements_width_height_type_affichage("100%","100px","liste");


////	INFOS + DROIT ACCES + LOGS
////
$droit_acces_sujet = droit_acces_controler($objet["sujet"], $_GET["id_sujet"], 1);
$sujet_tmp	= db_ligne("SELECT * FROM gt_forum_sujet WHERE id_sujet='".intval($_GET["id_sujet"])."'");
$auteur_tmp	= user_infos($sujet_tmp["id_utilisateur"]);
add_logs("consult", $objet["sujet"], $_GET["id_sujet"]);


////	LISTE DES MESSAGES : linéaire / Arborescence (messages d'un sujet/sous-messages)
////
if($_REQUEST["type_affichage"]=="liste")	{ $liste_messages = db_tableau("SELECT * FROM gt_forum_message WHERE id_sujet='".intval($_GET["id_sujet"])."' ".tri_sql($objet["message"]["tri"])); }
else
{
	function arborescence_message($id_message_parent=0, $niveau=0, $init=true)
	{
		global $objet, $liste_messages;
		if($init==true)  $liste_messages = array();
		foreach(db_tableau("SELECT * FROM gt_forum_message WHERE id_sujet='".intval($_GET["id_sujet"])."' AND  ".($id_message_parent>0?"id_message_parent='".intval($id_message_parent)."'":"(id_message_parent=0 or id_message_parent is null)")."  ORDER BY id_message asc") as $message_tmp){
			$liste_messages[] = array_merge($message_tmp, array("niveau"=>$niveau));
			arborescence_message($message_tmp["id_message"], $niveau+1, false);  // lancement récursif de la fonction
		}
	}
	arborescence_message();
}


////	DERNIER MESSAGE PAS ENCORE CONSULTE ? AJOUTE L'USER COURANT
////
if(is_dernier_message_consulte($sujet_tmp["users_consult_dernier_message"])==false){
	$new_users_consult = $sujet_tmp["users_consult_dernier_message"]."u".$_SESSION["user"]["id_utilisateur"]."u";
	db_query("UPDATE gt_forum_sujet SET users_consult_dernier_message=".db_format($new_users_consult)." WHERE id_sujet=".db_format($_GET["id_sujet"]));
}

////	MODIFIE L'OPTION DE NOTIF PAR MAIL ?
////
if(isset($_GET["modif_notifier_dernier_message"])){
	if($_GET["modif_notifier_dernier_message"]==1)	$sujet_tmp["users_notifier_dernier_message"] = $sujet_tmp["users_notifier_dernier_message"]."u".$_SESSION["user"]["id_utilisateur"]."u";
	else											$sujet_tmp["users_notifier_dernier_message"] = str_replace("u".$_SESSION["user"]["id_utilisateur"]."u", "", $sujet_tmp["users_notifier_dernier_message"]);
	db_query("UPDATE gt_forum_sujet SET users_notifier_dernier_message=".db_format($sujet_tmp["users_notifier_dernier_message"])." WHERE id_sujet=".db_format($_GET["id_sujet"]));
}
?>


<style>
.contenu_sujet_message	{ padding:10px; padding-left:20px; }
.forum_hr				{ height:1px; opacity:0.2; filter:alpha(opacity=20); margin-top:5px; margin-bottom:10px; }
</style>


<table id="contenu_principal_table"><tr>
	<td id="menu_gauche_block_td">
		<div id="menu_gauche_block_flottant">
			<div class="menu_gauche_block content">
				<?php
				////	AJOUTER MESSAGE
				if($droit_acces_sujet>1)	echo "<div class='menu_gauche_ligne lien' onclick=\"popupLightbox('message_edit.php?id_sujet=".$sujet_tmp["id_sujet"]."&amp;id_message_parent=0');\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/ajouter.png\" /></div><div class='menu_gauche_txt'>".$trad["FORUM_repondre"]."</div></div>";
				////	ME NOTIFIER PAR MAIL
				if(@$_SESSION["user"]["mail"]!=""){
					if(preg_match("/u".$_SESSION["user"]["id_utilisateur"]."u/i",$sujet_tmp["users_notifier_dernier_message"])==false)	{ $style_notifier = "lien";			$var_modif_notifier = "1"; }
					else																												{ $style_notifier = "lien_select";	$var_modif_notifier = "0"; }
					echo "<div class='menu_gauche_ligne' onclick=\"redir('".php_self()."?id_sujet=".$sujet_tmp["id_sujet"]."&amp;modif_notifier_dernier_message=".$var_modif_notifier."');\" ".infobulle($trad["FORUM_notifier_dernier_message_info"])."><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/envoi_mail.png\" /></div><div class='menu_gauche_txt ".$style_notifier."'>".$trad["FORUM_notifier_dernier_message"]."</div></div>";
				}
				echo "<hr />";
				////	AFFICHAGE + INFOS
				echo menu_type_affichage("liste,arbo");
				if($_REQUEST["type_affichage"]=="liste")	echo menu_tri($objet["message"]["tri"],"tri_message");
				echo "<div class='menu_gauche_ligne'><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/info.png\" /></div><div class='menu_gauche_txt'>".count($liste_messages)." ".(count($liste_messages)>1?$trad["FORUM_messages"]:$trad["FORUM_message"])."</div></div>";
				?>
			</div>
		</div>
	</td>
	<td>
		<?php
		////	CHEMIN : "THEME >> SUJET"
		echo chemin_theme_sujet(themes_sujets(),$sujet_tmp["titre"]);

		////	SUJET
		////
		$id_div_element = "div_sujet_".$sujet_tmp["id_sujet"];//utile pour clic droit
		$cfg_menu_elem = array("objet"=>$objet["sujet"], "objet_infos"=>$sujet_tmp, "id_div_element"=>$id_div_element);
		if($droit_acces_sujet==3){
			$cfg_menu_elem["modif"] = "sujet_edit.php?id_sujet=".$sujet_tmp["id_sujet"];
			$cfg_menu_elem["suppr"] = "elements_suppr.php?id_sujet=".$sujet_tmp["id_sujet"]."&num_page=".@$_SESSION["cfg"]["espace"]["num_page"];
		}
		echo "<div id='".$id_div_element."' class='div_elem_deselect' style='width:99.5%;".STYLE_SHADOW_FORT."'>";
			require PATH_INC."element_menu_contextuel.inc.php";
			echo "<div style='float:right;'>".photo_user($auteur_tmp,60)."</div>";
			echo "<div class='div_elem_contenu contenu_sujet_message'>";
				if($sujet_tmp["titre"]!="")  echo "<b>".$sujet_tmp["titre"]."</b> &nbsp;&nbsp; ";
				echo "<i>".auteur($auteur_tmp,$sujet_tmp["invite"]).", ".temps($sujet_tmp["date_crea"])."</i>";
				echo "<hr class='forum_hr' />".$sujet_tmp["description"];
				affiche_fichiers_joints($objet["sujet"], $sujet_tmp["id_sujet"], "normal");
			echo "</div>";
		echo "</div>";


		////	AFFICHAGE DES MESSAGES
		////
		foreach($liste_messages as $message_tmp)
		{
			// MODIF / SUPPR / INFOS
			$id_div_element = "div_message_".$message_tmp["id_message"];//utile pour clic droit & div_bas_conteneur()
			$cfg_menu_elem = array("objet"=>$objet["message"], "objet_infos"=>$message_tmp, "id_div_element"=>$id_div_element);
			if(droit_acces($objet["message"],$message_tmp)==3){
				$cfg_menu_elem["modif"] = "message_edit.php?id_message=".$message_tmp["id_message"];
				$cfg_menu_elem["suppr"] = "elements_suppr.php?id_message=".$message_tmp["id_message"]."&id_sujet_retour=".$_GET["id_sujet"];
				$cfg_menu_elem["suppr_text_confirm"] = $trad["FORUM_confirme_suppr_message"];
			}
			// AUTEUR  /  MARGE GAUCHE D'ARBORESCENCE
			$auteur_tmp = user_infos($message_tmp["id_utilisateur"]);
			$marge_gauche = ($_REQUEST["type_affichage"]=="arbo")  ?  "style='padding-left:".($message_tmp["niveau"]*20)."px;'"  :  "";
			// ICONE DE REPONSE
			$div_repondre = "";
			if($droit_acces_sujet>1){
				if($_REQUEST["type_affichage"]=="liste")	{ $texte_repondre = $trad["FORUM_repondre_message_citer"];	$icone_repondre = "quote.png"; }
				else										{ $texte_repondre = $trad["FORUM_repondre_message"];		$icone_repondre = "repondre.png"; }
				$div_repondre = "<div id='repondre_".$message_tmp["id_message"]."' class='lien' style='position:absolute;margin:2px;'><img src=\"".PATH_TPL."module_forum/".$icone_repondre."\" onclick=\"popupLightbox('message_edit.php?id_sujet=".$sujet_tmp["id_sujet"]."&amp;id_message_parent=".$message_tmp["id_message"]."');\" ".infobulle($texte_repondre)." /></div>";
			}
			// CITE UN MESSAGE (AFFICHAGE LISTE) ?
			if($_REQUEST["type_affichage"]=="liste" && $message_tmp["id_message_parent"]>0){
				$message_parent = db_ligne("SELECT * FROM gt_forum_message WHERE id_message='".$message_tmp["id_message_parent"]."'");
				if($message_parent["description"]=="")	$message_parent["description"] = $message_parent["titre"];
				$message_tmp["description"] = "<div class='forum_citation'><img src=\"".PATH_TPL."module_forum/quote2.png\" class='forum_citation_ico' /><i>".auteur($message_parent["id_utilisateur"],$message_parent["invite"])."</i> &nbsp;:&nbsp; ".$message_parent["description"]."</div>".$message_tmp["description"];
			}
			// AFFICHE
			echo "<div ".$marge_gauche.">";
				echo "<div id='".$id_div_element."' class='div_elem_deselect' style='width:99.5%;margin:0px;margin-top:".(@$message_tmp["niveau"]>0?"4":"12")."px'>";
					require PATH_INC."element_menu_contextuel.inc.php";
					echo $div_repondre;
					echo "<div style='float:right;width:70px;text-align:right'>".photo_user($auteur_tmp,60)."</div>";
					echo "<div class='div_elem_contenu contenu_sujet_message'>";
						if($message_tmp["titre"]!="")	echo "<b>".$message_tmp["titre"]."</b> &nbsp;&nbsp; ";
						echo "<i>".auteur($auteur_tmp,$message_tmp["invite"]).", ".temps($message_tmp["date_crea"])."</i>";
						echo "<hr class='forum_hr' />".$message_tmp["description"];
						affiche_fichiers_joints($objet["message"], $message_tmp["id_message"], "normal");
					echo "</div>";
				echo "</div>";
			echo "</div>";
			echo "<script> div_bas_conteneur('".$id_div_element."','repondre_".$message_tmp["id_message"]."'); </script>";
		}

		////	AUCUN MESSAGE
		if(count($liste_messages)==0)	echo "<div class='div_elem_aucun'>".$trad["FORUM_pas_message"]."</div>";
		?>
	</td>
</tr></table>


<?php require PATH_INC."footer.inc.php"; ?>