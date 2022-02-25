<?php
////	INIT
define("IS_MAIN_PAGE",true);
require "commun.inc.php";
require PATH_INC."header_menu.inc.php";
init_id_dossier();
$droit_acces_dossier = droit_acces_controler($objet["contact_dossier"], $_GET["id_dossier"], 1);
elements_width_height_type_affichage("medium","100px","bloc");


////	CREATION D'UN UTILISATEUR A PARTIR D'UN CONTACT
////
if(isset($_GET["id_contact"]) && @$_GET["action"]=="creer_user" && $_SESSION["user"]["admin_general"]==1)
{
	$contact_tmp = db_ligne("SELECT * FROM gt_contact WHERE id_contact='".intval($_GET["id_contact"])."'");
	($contact_tmp["mail"]!="")  ?  $identifiant=$contact_tmp["mail"] :  $identifiant=substr($contact_tmp["nom"],0,1).substr($contact_tmp["prenom"],0,5);
	$id_user_tmp = creer_utilisateur($identifiant, mt_rand(10000,99999), @$contact_tmp["nom"], @$contact_tmp["prenom"], $contact_tmp["mail"], $_SESSION["espace"]["id_espace"]);
	if($id_user_tmp > 0)
	{
		if($contact_tmp["photo"]!="") {
			$photo_tmp = $id_user_tmp.extension($contact_tmp["photo"]);
			copy(PATH_PHOTOS_CONTACT.$contact_tmp["photo"], PATH_PHOTOS_USER.$photo_tmp);
		}
		db_query("UPDATE  gt_utilisateur  SET  civilite=".db_format($contact_tmp["civilite"]).", photo=".db_format($photo_tmp).", societe_organisme=".db_format($contact_tmp["societe_organisme"]).", fonction=".db_format($contact_tmp["fonction"]).", adresse=".db_format($contact_tmp["adresse"]).", codepostal=".db_format($contact_tmp["codepostal"]).", ville=".db_format($contact_tmp["ville"]).", pays=".db_format($contact_tmp["pays"]).", telephone=".db_format($contact_tmp["telephone"]).", telmobile=".db_format($contact_tmp["telmobile"]).", fax=".db_format($contact_tmp["fax"]).", siteweb=".db_format($contact_tmp["siteweb"]).", competences=".db_format($contact_tmp["competences"]).", hobbies=".db_format($contact_tmp["hobbies"]).", commentaire=".db_format($contact_tmp["commentaire"])."  WHERE  id_utilisateur=".db_format($id_user_tmp));
		alert($trad["CONTACT_creer_user_confirm"]);
	}
}
?>


<table id="contenu_principal_table"><tr>
	<td id="menu_gauche_block_td">
		<div id="menu_gauche_block_flottant">
			<div class="menu_gauche_block content">
				<?php
				////	MENU D'ARBORESCENCE
				$cfg_menu_arbo = array("objet"=>$objet["contact_dossier"], "id_objet"=>$_GET["id_dossier"], "ajouter_dossier"=>true, "droit_acces_dossier"=>$droit_acces_dossier);
				require_once PATH_INC."menu_arborescence.inc.php";
				?>
			</div>
			<div class="menu_gauche_block content">
				<?php
				////	AJOUTER CONTACT
				if($droit_acces_dossier>=1.5)	{echo "<div class='menu_gauche_ligne lien' onclick=\"popupLightbox('contact_edit.php?id_dossier=".$_GET["id_dossier"]."');\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/ajouter.png\" /></div><div class='menu_gauche_txt'>".$trad["CONTACT_ajouter_contact"]."</div></div>";}
				////	EXPORTER / IMPORTER LES CONTACTS
				if($droit_acces_dossier>=2 && $_SESSION["user"]["id_utilisateur"]>0)
					{echo "<div class='menu_gauche_ligne lien' onclick=\"popupLightbox('".PATH_DIVERS."contact_export.php?type_export_import=contacts&id_dossier=".$_GET["id_dossier"]."',true);\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/export_import.png\" /></div><div class='menu_gauche_txt'>".$trad["exporter"]." / ".$trad["importer"]." ".$trad["export_import_contacts"]."</div></div>";}
				echo "<hr />";
				////	MENU ELEMENTS
				$cfg_menu_elements = array("objet"=>$objet["contact"], "objet_dossier"=>$objet["contact_dossier"], "id_objet_dossier"=>$_GET["id_dossier"], "droit_acces_dossier"=>$droit_acces_dossier);
				require PATH_INC."elements_menu_selection.inc.php";
				////	MENU D'AFFICHAGE  &  DE TRI  &  CONTENU DU DOSSIER
				echo menu_type_affichage();
				echo menu_tri($objet["contact"]["tri"]);
				echo contenu_dossier($objet["contact_dossier"],$_GET["id_dossier"]);
				?>
			</div>
		</div>
	</td>
	<td>
		<?php
		////	MENU CHEMIN + OBJETS_DOSSIERS
		////
		echo menu_chemin($objet["contact_dossier"], $_GET["id_dossier"]);
		$cfg_dossiers = array("objet"=>$objet["contact_dossier"], "id_objet"=>$_GET["id_dossier"]);
		require_once PATH_INC."dossiers.inc.php";

		////	LISTE DES CONTACTS
		////
		$liste_contacts = db_tableau("SELECT * FROM gt_contact WHERE id_dossier='".intval($_GET["id_dossier"])."' ".sql_affichage($objet["contact"],$_GET["id_dossier"])." ".tri_sql($objet["contact"]["tri"]));
		foreach($liste_contacts as $contact_tmp)
		{
			////	INIT + LIEN POPUP
			$lien_popup = "onclick=\"popupLightbox('contact.php?id_contact=".$contact_tmp["id_contact"]."');stopPropager(event);\"";
			$src_photo  =  ($contact_tmp["photo"]=="")  ?  PATH_TPL."module_utilisateurs/user.png"  :  PATH_PHOTOS_CONTACT.$contact_tmp["photo"];
			$contact_nom_prenom = $contact_tmp["civilite"]." ".$contact_tmp["prenom"]." ".$contact_tmp["nom"];

			////	DETAILS D'UN CONTACT
			$details_contact = "";
			$separateur = ($_REQUEST["type_affichage"]=="bloc")  ?  "<br>"  :  "<img src=\"".PATH_TPL."divers/separateur.gif\" />";
			if($contact_tmp["adresse"]!="" || $contact_tmp["codepostal"]!="" || $contact_tmp["ville"]!="")	{$details_contact .= $contact_tmp["adresse"]." ".$contact_tmp["codepostal"]." ".$contact_tmp["ville"]." ".carte_localisation($contact_tmp).$separateur;}
			if($contact_tmp["telephone"]!="")			{$details_contact .= $contact_tmp["telephone"].$separateur;}
			if($contact_tmp["telmobile"]!="")			{$details_contact .= $contact_tmp["telmobile"].$separateur;}
			if($contact_tmp["mail"]!="")				{$details_contact .= "<a href=\"mailto:".$contact_tmp["mail"]."\" class='lien'>".$contact_tmp["mail"]."</a>".$separateur;}
			if($contact_tmp["societe_organisme"]!="")	{$details_contact .= $contact_tmp["societe_organisme"].$separateur;}
			if($contact_tmp["fonction"]!="")			{$details_contact .= $contact_tmp["fonction"].$separateur;}
			$details_contact = substr($details_contact,0,strrpos($details_contact,$separateur));//Enleve le dernier séparateur

			////	MODIF / SUPPR / INFOS / CREATION USER (admin général)
			$cfg_menu_elem = array("objet"=>$objet["contact"], "objet_infos"=>$contact_tmp);
			$contact_tmp["droit_acces"] = droit_acces($objet["contact"],$contact_tmp);//ne jamais prendre les droits du dossier!
			if($contact_tmp["droit_acces"]>=2)	{
				$cfg_menu_elem["modif"] = "contact_edit.php?id_contact=".$contact_tmp["id_contact"];
				$cfg_menu_elem["deplacer"] = PATH_DIVERS."deplacer.php?module_path=".MODULE_PATH."&type_objet_dossier=contact_dossier&id_dossier_parent=".$_GET["id_dossier"]."&SelectedElems[contact]=".$contact_tmp["id_contact"];
				$cfg_menu_elem["suppr"] = "elements_suppr.php?id_contact=".$contact_tmp["id_contact"]."&id_dossier_retour=".$_GET["id_dossier"];
			}
			if($_SESSION["user"]["admin_general"]==1)	{$cfg_menu_elem["options_divers"][] = array("icone_src"=>PATH_TPL."divers/ajouter.png", "text"=>$trad["CONTACT_creer_user"], "action_js"=>"confirmer('".addslashes($trad["CONTACT_creer_user_infos"])."','index.php?action=creer_user&id_contact=".$contact_tmp["id_contact"]."');");}

			////	DIV SELECTIONNABLE + OPTIONS
			$cfg_menu_elem["id_div_element"] = div_element($objet["contact"], $contact_tmp["id_contact"]);
			require PATH_INC."element_menu_contextuel.inc.php";
				////	CONTENU
				echo "<div class='div_elem_contenu'>";
					echo "<table class='div_elem_table'><tr>";
					////	AFFICHAGE BLOCK
					if($_REQUEST["type_affichage"]=="bloc")
					{
						echo "<td class='div_elem_image lien' ".$lien_popup." ><img src=\"".$src_photo."\" style='max-width:80px;max-height:80px;' /></td>";
						echo "<td style='vertical-align:middle'>";
							echo "<div style='font-size:12px;' class='lien' ".$lien_popup.">".$contact_nom_prenom."</div>";
							echo "<span style='margin-bottom:5px;font-size:11px;line-height:15px;' >".$details_contact."</span>";
						echo "</td>";
					}
					////	AFFICHAGE LISTE
					else
					{
						echo "<td class='div_elem_td lien' style='width:70px;text-align:center;' ".$lien_popup." ><img src=\"".$src_photo."\" style='max-width:70px;max-height:35px;' /></td>";
						echo "<td class='div_elem_td'><span class='lien' ".$lien_popup.">".$contact_nom_prenom."</span></td>";
						echo "<td class='div_elem_td div_elem_td_right'>".$details_contact."</td>";
					}
					echo "</tr></table>";
				echo "</div>";
			echo "</div>";
		}
		////	AUCUN CONTACT
		if(@$cpt_div_element<1)  {echo "<div class='div_elem_aucun'>".$trad["CONTACT_aucun_contact"]."</div>";}
		?>
	</td>
</tr></table>


<?php require PATH_INC."footer.inc.php"; ?>