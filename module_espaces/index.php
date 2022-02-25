<?php
////	INIT
define("IS_MAIN_PAGE",true);
require "commun.inc.php";
require PATH_INC."header_menu.inc.php";
controle_acces_admin("admin_general");
elements_width_height_type_affichage("large","180px","bloc");


////	SUPPRESSION D'UN ESPACE
////
if(isset($_GET["action"]) && $_GET["action"]=="espace_suppr" && $_GET["id_espace"]>0)
{
	////	SUPPRESSION  =>  S'IL Y A AU MOINS 2 ESPACES SUR LE SITE  &  PAS L'ESPACE COURANT !
	if(db_valeur("SELECT count(*) FROM gt_espace") > 1  &&  $_GET["id_espace"]!=$_SESSION["espace"]["id_espace"])
	{
		////	RECUPERE LES OBJETS AFFECTES UNIQUEMENT A L'ESPACE COURANT -> ET DONC A SUPPRIMER !
		function sql_objets_suppr($id_objet, $type_objet)
		{
			$liste_objets = db_colonne("SELECT DISTINCT id_objet FROM gt_jointure_objet WHERE type_objet='".@$type_objet."' AND id_espace='".intval($_GET["id_espace"])."' AND id_objet NOT IN (select distinct id_objet from gt_jointure_objet where type_objet='".@$type_objet."' and (id_espace!='".intval($_GET["id_espace"])."' or id_espace is null))");
			if(count($liste_objets)>0)	return "(".implode(",",$liste_objets).")";
			else						return "(0)";//pour retourner au moins une valeur..
		}

		////	SUPPRESSION DES ACTUALITES
		include_once "../module_tableau_bord/commun.inc.php";
		$actualites = db_tableau("SELECT * FROM gt_actualite WHERE id_actualite IN ".sql_objets_suppr("id_actualite","actualite"));
		foreach($actualites as $actualite)		{ suppr_actualite($actualite["id_actualite"]); }

		////	SUPPRESSION DES FICHIERS
		include_once "../module_fichier/commun.inc.php";
		$fichiers_dossier	= db_tableau("SELECT * FROM gt_fichier_dossier WHERE id_dossier IN ".sql_objets_suppr("id_dossier","fichier_dossier"));
		$fichiers			= db_tableau("SELECT * FROM gt_fichier WHERE id_fichier IN ".sql_objets_suppr("id_fichier","fichier"));
		foreach($fichiers as $fichier)			{ suppr_fichier($fichier["id_fichier"]); }
		foreach($fichiers_dossier as $dossier)	{ suppr_fichier_dossier($dossier["id_dossier"]); }

		////	SUPPRESSION DES TACHES
		include_once "../module_tache/commun.inc.php";
		$taches_dossier	= db_tableau("SELECT * FROM gt_tache_dossier WHERE id_dossier IN ".sql_objets_suppr("id_dossier","tache_dossier"));
		$taches			= db_tableau("SELECT * FROM gt_tache WHERE id_tache IN ".sql_objets_suppr("id_tache","tache"));
		foreach($taches as $tache)				{ suppr_tache($tache["id_tache"]); }
		foreach($taches_dossier as $dossier)	{ suppr_tache_dossier($dossier["id_dossier"]); }

		////	SUPPRESSION DES LIENS
		include_once "../module_lien/commun.inc.php";
		$liens_dossier	= db_tableau("SELECT * FROM gt_lien_dossier WHERE id_dossier IN ".sql_objets_suppr("id_dossier","lien_dossier"));
		$liens			= db_tableau("SELECT * FROM gt_lien WHERE id_lien IN ".sql_objets_suppr("id_lien","lien"));
		foreach($liens as $lien)				{ suppr_lien($lien["id_lien"]); }
		foreach($liens_dossier as $dossier)		{ suppr_lien_dossier($dossier["id_dossier"]); }

		////	SUPPRESSION DES CONTACTS
		include_once "../module_contact/commun.inc.php";
		$contacts_dossier	= db_tableau("SELECT * FROM gt_contact_dossier WHERE id_dossier IN ".sql_objets_suppr("id_dossier","contact_dossier"));
		$contacts			= db_tableau("SELECT * FROM gt_contact WHERE id_contact IN ".sql_objets_suppr("id_contact","contact"));
		foreach($contacts as $contact)			{ suppr_contact($contact["id_contact"]); }
		foreach($contacts_dossier as $dossier)	{ suppr_contact_dossier($dossier["id_dossier"]); }

		////	SUPPRESSION DES SUJETS DU FORUM
		include_once "../module_forum/commun.inc.php";
		$sujets_forum = db_tableau("SELECT * FROM gt_forum_sujet WHERE id_sujet IN ".sql_objets_suppr("id_sujet","sujet"));
		foreach($sujets_forum as $sujet)	{ suppr_sujet($sujet["id_sujet"]); }

		////	SUPPRESSION DES AGENDAS
		include_once "../module_agenda/commun.inc.php";
		$agendas = db_tableau("SELECT * FROM gt_agenda WHERE id_agenda IN ".sql_objets_suppr("id_agenda","agenda"));
		foreach($agendas as $agenda)		{ suppr_agenda($agenda["id_agenda"]); }

		////	SUPPRESSION DE L'ESPACE ET DES JOINTURES
		db_query("DELETE FROM gt_espace WHERE id_espace=".db_format($_GET["id_espace"]));
		db_query("DELETE FROM gt_jointure_espace_module WHERE id_espace=".db_format($_GET["id_espace"]));
		db_query("DELETE FROM gt_jointure_espace_utilisateur WHERE id_espace=".db_format($_GET["id_espace"]));
		db_query("DELETE FROM gt_jointure_objet WHERE id_espace=".db_format($_GET["id_espace"]));
		db_query("DELETE FROM gt_invitation WHERE id_espace=".db_format($_GET["id_espace"]));
		
		////	RECALCULE  $_SESSION["agora"]["taille_stock_fichier"]
		taille_stock_fichier(true);
	}
	else  { $_GET["msg_alerte"] = "suppr_espace_impossible"; }
}


////	LISTE DES ESPACES
////
$liste_espaces = db_tableau("SELECT * FROM gt_espace ".tri_sql($objet["espace"]["tri"]));
?>


<style>
.affectation_espace		{ float:left; width:32%; margin:3px; }
.affectation_espace img	{ max-height:16px; }
</style>


<table id="contenu_principal_table"><tr>
	<td id="menu_gauche_block_td">
		<div id="menu_gauche_block_flottant">
			<div class="menu_gauche_block content">
				<?php
				echo "<div class='menu_gauche_ligne lien' onclick=\"popupLightbox('espace_edit.php');\" ".infobulle($trad["ESPACES_description_module_infos"])."><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/ajouter.png\" /></div><div class='menu_gauche_txt'>".$trad["ESPACES_ajouter_espace"]."</div></div><hr />";
				echo menu_tri($objet["espace"]["tri"],"tri_espace");
				echo "<div class='menu_gauche_ligne'><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/info.png\" /></div><div class='menu_gauche_txt'>".count($liste_espaces)." ".(count($liste_espaces)>1?$trad["ESPACES_espaces"]:$trad["ESPACES_espace"])."</div></div>";
				?>
			</div>
		</div>
	</td>
	<td>
	<?php
	////	LISTE DES ESPACES
	////
	foreach($liste_espaces as $compteur => $espace_tmp)
	{
		echo "<table class='div_elem_deselect div_elem_contenu div_elem_table' style='width:".$width_element.";height:".$height_element.";'>";
			echo "<tr>";
				echo "<td>";
					////	TITRE & DESCRIPTION & MODULES DE L'ESPACE
					echo "<div style='font-weight:bold;margin:5px;font-size:14px;'>".$espace_tmp["nom"]."</div>";
					if($espace_tmp["description"]!="")	echo "<div>".$espace_tmp["description"]."</div>";
					foreach(modules_espace($espace_tmp["id_espace"],false) as $module_tmp){
						echo "<img src=\"".PATH_TPL.$module_tmp["module_path"]."/menu.png\" ".infobulle($trad[strtoupper($module_tmp["nom"])."_description_module"])." style='height:30px;margin-top:8px;margin-right:3px;cursor:help;' /> ";
					}
				echo "</td>";
				echo "<td style='width:30px;text-align:right;vertical-align:top;cursor:pointer;'>";
					////	GESTION DES ACCES / MODIFIER  &  SUPPRIMER
					echo "<img src=\"".PATH_TPL."divers/parametrage.png\" onclick=\"popupLightbox('espace_edit.php?id_espace=".$espace_tmp["id_espace"]."');\" ".infobulle($trad["ESPACES_parametrage_infos"])." /><br>";
					$action_onclick = ($espace_tmp["id_espace"]==$_SESSION["espace"]["id_espace"])  ?  "alert('".addslashes($trad["MSG_ALERTE_suppr_espace_impossible"])."');"  :  "confirmer('".addslashes($trad["ESPACES_confirm_suppr_espace"])."','index.php?action=espace_suppr&id_espace=".$espace_tmp["id_espace"]."');";
					echo "<img src=\"".PATH_TPL."divers/supprimer.png\" style='margin-top:5px;' onclick=\"".$action_onclick."\" ".infobulle($trad["ESPACES_supprimer_espace"])." />";
				echo "</td>";
			echo "</tr>";
			echo "<tr><td colspan='2'><hr /></td></tr>";
			echo "<tr>";
				echo "<td colspan='2'>";
					////	DROITS D'ACCES
					echo "<div style='height:80px;overflow-y:auto;font-weight:bold;'>";
						echo "<div style='margin-bottom:5px;font-style:italic;'>".$trad["EDIT_OBJET_droit_acces"]." :</div>";
						// ESPACE PUBLIC ?
						if($espace_tmp["public"]==1)	echo "<div class='affectation_espace'><img src=\"".PATH_TPL."divers/planete.png\" /> ".$trad["espace_public"]."</div>";
						// TOUS LES UTILISATEURS DU SITE
						$tous_users_site = db_valeur("SELECT count(*) FROM gt_jointure_espace_utilisateur WHERE id_espace='".$espace_tmp["id_espace"]."' AND tous_utilisateurs > 0");
						if($tous_users_site > 0)
							echo "<div class='affectation_espace' style='width:200px;'><img src=\"".PATH_TPL."module_utilisateurs/utilisateurs_small.png\" /> ".$trad["ESPACES_tous_utilisateurs"]."</div>";
						// LISTE DES UTILISATEURS
						foreach(users_espace($espace_tmp["id_espace"],"tableau") as $user_tmp){
							$icone_statut_user = (droit_acces_espace($espace_tmp["id_espace"],$user_tmp)==2)  ?  "acces_admin_espace"  :  "acces_utilisateur";
							if($tous_users_site > 0 && $icone_statut_user=="acces_utilisateur")		continue;  //affiche que les admin. si "tous les utilisateurs" sont sélectionnés
							echo "<div class='lien affectation_espace' ".popup_user($user_tmp["id_utilisateur"])."><img src=\"".PATH_TPL."module_utilisateurs/".$icone_statut_user.".png\" /> ".$user_tmp["prenom"]." ".$user_tmp["nom"]."</div>";
						}
						// DEFINIR LES DROITS D'ACCES...
						if(db_valeur("SELECT count(*) FROM gt_jointure_espace_utilisateur WHERE id_espace='".$espace_tmp["id_espace"]."'")==0)
							echo "<div style='margin:5px;font-weight:bold;".STYLE_SELECT_RED."'>".$trad["ESPACES_definir_acces"]."</div>";
					echo "</div>";
				echo "</td>";
			echo "</tr>";
		echo "</table>";
	}
	?>
	</td>
</tr></table>


<?php require PATH_INC."footer.inc.php"; ?>