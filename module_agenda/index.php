<?php
////	INIT
define("IS_MAIN_PAGE",true);
require "commun.inc.php";
require PATH_INC."header_menu.inc.php";
$cle_pref_db = "type_affichage_".MODULE_NOM;
if(isset($_GET["id_dossier"]))	{$cle_pref_db .= "_".$_GET["id_dossier"];}
pref_user("agendas_details");

////	INTEGRER UN EVENEMENT PROPOSE DANS UN AGENDA
////
if((isset($_GET["id_evt_confirm"]) || isset($_GET["id_evt_noconfirm"]))  &&  $AGENDAS_AFFICHES[$_GET["id_agenda"]]["droit"]>=2){
	if(isset($_GET["id_evt_confirm"]))	{ db_query("UPDATE gt_agenda_jointure_evenement SET confirme='1' WHERE id_evenement=".db_format($_GET["id_evt_confirm"])." AND id_agenda=".db_format($_GET["id_agenda"])); }
	else								{ db_query("DELETE FROM gt_agenda_jointure_evenement WHERE id_evenement=".db_format($_GET["id_evt_noconfirm"])." AND id_agenda=".db_format($_GET["id_agenda"])); }
}

////	SUPPR ANCIENS EVENEMENTS D'UN AGENDA
////
if(@$_GET["action"]=="suppr_anciens_evt" && $AGENDAS_AFFICHES[$_GET["id_agenda"]]["droit"]>=2){
	foreach(liste_evenements($_GET["id_agenda"],0,$_GET["time_unix_limit"]) as $id_evt)		{ suppr_evenement($id_evt["id_evenement"],$_GET["id_agenda"]); }
}

////	SUPPR UN AGENDA DE RESSOURCE
////
if(@$_GET["action"]=="suppr_agenda_ressource")
{
	suppr_agenda($_GET["id_agenda"]);
	unset($_SESSION["cfg"]["espace"]["agendas_affiches"], $AGENDAS_AFFICHES, $AGENDAS_AFFECTATIONS);
	$redir_agenda =  db_valeur("SELECT id_agenda FROM gt_agenda WHERE id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."' AND type='utilisateur'");
	redir(php_self()."?agendas_demandes[]=".$redir_agenda);
}

////	SUPPR UN EVENEMENT DANS UN AGENDA
////
if(@$_GET["action"]=="suppr_evt"){
	suppr_evenement($_GET["id_evenement"], $_GET["id_agenda"], @$_GET["date_suppr"]);
	if(@$_GET["date_suppr"]!="")	{redir(php_self());}  // uniquement si modif d'evt
}
?>


<script type="text/javascript">
////	Fonction pour cocher/décocher les agendas
////
function CheckAgendas(type)
{
	// Coche tout / Decoche la sélection ?
	coche_selection = false;
	if((type=="all" && $("input[name='agendas_demandes[]']:checked").length!=$("input[name='agendas_demandes[]']").length)  ||  (type=="utilisateur" && $(".type_utilisateur:checked").length!=$(".type_utilisateur").length)  ||  (type=="ressource" && $(".type_ressource:checked").length!=$(".type_ressource").length))
		coche_selection = true;

	// On parcour toutes les checkbox
	for(var i=0; i < $("input[name='agendas_demandes[]']").length; i++)
	{
		// Init les ID
		id_box = document.getElementsByName("agendas_demandes[]")[i].id;
		id_txt = id_box.replace("box_","txt_");
		// Coche / Decoche l'agenda courant ?
		if(coche_selection==true && (type=="all" || (type=="utilisateur" && element(id_box).className=="type_utilisateur") || (type=="ressource" && element(id_box).className=="type_ressource"))){
			element(id_box).checked = true;
			element(id_txt).className = "lien_select2";
		}else{
			element(id_box).checked = false;
			element(id_txt).className = "lien";
		}
	}
}
</script>


<style>
.agenda_conteneur	{ margin-bottom:25px; width:99.5%; <?php echo STYLE_BLOCK; ?> }
.libelle_agenda		{ text-align:center; }
.menu_agenda:hover	{ opacity:0.9; filter:alpha(opacity=90); cursor:pointer; }
.div_evt_contenu	{ padding:3px; cursor:pointer; text-align:left; line-height:12px; font-size:11px; }
/* IMPRESSION */
@media print {
	@page {size:landscape;}
	.agenda_conteneur	{ margin-top:-80px; page-break-after:always; page-break-inside:avoid; }
	.libelle_agenda		{ padding:5px; }
	.print_titre		{ font-size:18px; padding-bottom:20px; }
}
</style>


<table id="contenu_principal_table"><tr>
	<td id="menu_gauche_block_td" class="noprint">
		<div id="menu_gauche_block_flottant">
			<?php
			////	EVENEMENTS A CONFIRMER
			////
			$menu_proposition_evt = menu_proposition_evt();
			if($menu_proposition_evt!="")	{echo "<div class='menu_gauche_block content' style='border:solid 2px #fff;'>".$menu_proposition_evt."</div>";}

			////	CALENDRIER
			////
			echo "<div class='menu_gauche_block content' style='font-weight:normal;'>";
				$_REQUEST["date_selection_debut"] = $config["agenda_debut"];
				$_REQUEST["date_selection_fin"] = $config["agenda_fin"];
				$_REQUEST["date_affiche"] = strtotime(strftime("%Y-%m-15",$config["agenda_debut"]));
				require PATH_INC."calendrier.inc.php";
			echo "</div>";

			/////	AFFICHER LES CATEGORIES D'EVT  /  GERER LES CATEGORIES
			////
			echo "<div class='menu_gauche_block content'>";
				echo menu_evt_filtre_categorie();
				if(droit_gestion_categories()==true)	{echo "<div class='menu_gauche_ligne lien' onClick=\"popupLightbox('categories.php');\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/nuancier.png\" /></div><div class='menu_gauche_txt'>".$trad["AGENDA_gerer_categories"]."</div></div>";}
			echo "</div>";

			////	AGENDAS DISPO
			////
			if(count($AGENDAS_AFFICHES)>0)
			{
				////	LISTE DES AGENDAS VISIBLES
				////
				echo "<form id='liste_agendas' action='index.php' method='get' class='menu_gauche_block content'>";
					////	MENU D'AFFICHAGE DES AGENDAS
					echo "<div class='menu_context' style='width:200px;line-height:15px;' id='menu_agendas'>";
						////	Cocher / Décocher : tous les agendas / les agendas d'users / les agendas de ressource
						echo "<div class='lien' onClick=\"CheckAgendas('all');\">".$trad["AGENDA_cocher_tous_agendas"]."</div>";
						echo "<div class='lien' onClick=\"CheckAgendas('utilisateur');\">".$trad["AGENDA_cocher_agendas_users"]."</div>";
						echo "<div class='lien' onClick=\"CheckAgendas('ressource');\">".$trad["AGENDA_cocher_agendas_ressources"]."</div>";
						////	"Afficher tous les agendas" ?
						if($_SESSION["user"]["admin_general"]==1){
							if(@$_SESSION["cfg"]["espace"]["afficher_tous_agendas"]==1)		{ $tous_get = 0;  $tous_libelle = $trad["AGENDA_masquer_tous_agendas"]; }
							else															{ $tous_get = 1;  $tous_libelle = $trad["AGENDA_afficher_tous_agendas"]; }
							echo "<hr style='width:200px;' /><div class='lien' onClick=\"redir('index.php?afficher_tous_agendas=".$tous_get."');\" ".infobulle($trad["admin_only"]).">".$tous_libelle."</div>";
						}
					echo "</div>";
					echo "<span style='float:right;' id='icone_menu_agendas'><img src=\"".PATH_TPL."divers/check_inverser.png\" /></span>";
					echo  "<script type='text/javascript'> menu_contextuel('menu_agendas'); </script>";
					////	LISTE DES AGENDAS
					$cpt_agenda = 0;
					foreach($AGENDAS_AFFICHES as $cle_tmp => $agenda_tmp)
					{
						////	Menu contextuel de l'element (General, Modif, Suppr, export)
						$cfg_menu_elem = array("objet"=>$objet["agenda"], "objet_infos"=>$agenda_tmp, "taille_icone"=>"small_inline");
						if($agenda_tmp["droit"]==3)
						{
							$cfg_menu_elem["modif"] = "agenda_edit.php?id_agenda=".$agenda_tmp["id_agenda"];
							if($agenda_tmp["type"]=="ressource")	{$cfg_menu_elem["suppr"] = "index.php?action=suppr_agenda_ressource&id_agenda=".$agenda_tmp["id_agenda"];}
							$libelle_export_mail = "<span ".infobulle($trad["AGENDA_exporter_ical_mail2"]."<br>".$trad["envoyer_a"]." ".$_SESSION["user"]["mail"]).">".$trad["AGENDA_exporter_ical_mail"]."</span>";
							$cfg_menu_elem["options_divers"][] = array("icone_src"=>PATH_TPL."divers/export.png", "text"=>$trad["AGENDA_exporter_ical"], "action_js"=>"redir('agenda_export.php?id_agenda=".$agenda_tmp["id_agenda"]."');");
							$cfg_menu_elem["options_divers"][] = array("icone_src"=>PATH_TPL."module_utilisateurs/user_telmobile.png", "text"=>$libelle_export_mail, "action_js"=>"redir('agenda_export.php?id_agenda=".$agenda_tmp["id_agenda"]."&envoi_mail=1');");
							if(version_compare(PHP_VERSION,'5.2','>=')==true)	{$cfg_menu_elem["options_divers"][] = array("icone_src"=>PATH_TPL."divers/import.png", "text"=>$trad["AGENDA_importer_ical"], "action_js"=>"popupLightbox('agenda_import.php?id_agenda=".$agenda_tmp["id_agenda"]."',true);");}
						}
						// Suppr anciens evt ?
						if($agenda_tmp["droit"]>=2)	{$cfg_menu_elem["options_divers"][] = array("icone_src"=>PATH_TPL."module_agenda/suppr_anciens_evt.png", "text"=>$trad["AGENDA_suppr_anciens_evt"], "action_js"=>"confirmer('".addslashes($trad["AGENDA_confirm_suppr_anciens_evt"])."','index.php?action=suppr_anciens_evt&id_agenda=".$agenda_tmp["id_agenda"]."&time_unix_limit=".$config["agenda_debut"]."');");}
						// Enregistre la configuration
						$AGENDAS_AFFICHES[$cle_tmp]["cfg_menu_elem"] = $cfg_menu_elem;
						////	Affichage de l'agenda
						$check_tmp		= (@in_array($agenda_tmp["id_agenda"],$_SESSION["cfg"]["espace"]["agendas_affiches"]))  ?  "checked"  :  "";
						$lecture_tmp	= ($agenda_tmp["droit"]==1)  ?  " (".$trad["lecture"].") "  :  "";
						$libelle_class	= (!empty($check_tmp))  ?  "lien_select2"  :  "lien";
						$libelle_style	= ($agenda_tmp["type"]=="ressource")  ?  "style='font-style:italic;'"  :  "";
						echo "<div class='ligne_survol' ".infobulle(nl2br($agenda_tmp["description"])).">";
							echo "<input type='checkbox' name='agendas_demandes[]' value='".$agenda_tmp["id_agenda"]."' id='box_agenda_demande".$cpt_agenda."' onClick=\"checkbox_text(this,'lien_select2');\" ".$check_tmp." class='type_".$agenda_tmp["type"]."' />";// Class "type agenda" pour cocher/decocher
							echo "<span class='".$libelle_class."' '".$libelle_style."' id='txt_agenda_demande".$cpt_agenda."' onClick=\"checkbox_text(this,'lien_select2');\"> ".$agenda_tmp["titre"]." ".$lecture_tmp." </span>";
							require PATH_INC."element_menu_contextuel.inc.php";
						echo "</div>";
						$cpt_agenda++;
					}
					////	Bouton de validation
					echo "<input type='submit' value=\"".$trad["afficher"]."\" class='button' style='margin-top:10px;width:80px;' />";
				echo "</form>";
			}

			/////	IMPRIMER L'AGENDA  /  AGENDA QUE L'AUTEUR A CREE / AJOUTER UN AGENDA DE RESSOURCE
			////
			if(count($AGENDAS_AFFICHES)>0 || droit_ajout_agenda_ressource())
			{
				echo "<div class='menu_gauche_block content'>";
					if(count($AGENDAS_AFFICHES)>0){
						echo "<div class='menu_gauche_ligne lien' onclick=\"redir('".php_self()."?printmode=1');\" title=\"".$trad["AGENDA_imprimer_agendas_infos"]."\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/imprimer.png\" /></div><div class='menu_gauche_txt'>".$trad["AGENDA_imprimer_agendas"]."</div></div>";
						if($_SESSION["user"]["id_utilisateur"]>0)	{echo "<div class='menu_gauche_ligne lien' onClick=\"popupLightbox('evenements_proprietaires.php');\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."module_agenda/evt_proprietaire.png\" /></div><div class='menu_gauche_txt'>".$trad["AGENDA_evt_proprio"]."</div></div>";}
					}
					if(droit_ajout_agenda_ressource())	{echo "<div class='menu_gauche_ligne lien' onClick=\"popupLightbox('agenda_edit.php');\" ".infobulle($trad["AGENDA_ajouter_agenda_ressource_bis"])."><div class='menu_gauche_img'><img src=\"".PATH_TPL."module_agenda/ajouter_ressource.png\" /></div><div class='menu_gauche_txt'>".$trad["AGENDA_ajouter_agenda_ressource"]."</div></div>";}
				echo "</div>";
			}
			?>
		</div>
	</td>
	<td>
		<?php
		////	INIT L'AFFICHAGE DES AGENDAS
		if($_SESSION["cfg"]["espace"]["agenda_affichage"]=="jour")				{ $url_pre = 86400;		$url_suiv = 86400; }
		elseif($_SESSION["cfg"]["espace"]["agenda_affichage"]=="semaine_w")		{ $url_pre = 86400*3;	$url_suiv = 86400*8; }
		elseif($_SESSION["cfg"]["espace"]["agenda_affichage"]=="semaine")		{ $url_pre = 86400*3;	$url_suiv = 86400*8; }
		elseif($_SESSION["cfg"]["espace"]["agenda_affichage"]=="mois")			{ $url_pre = 86400*15;	$url_suiv = 86400*40; }
		$tab_jours_feries = jours_feries(strftime("%Y",$_SESSION["cfg"]["espace"]["agenda_date"]));
		
		////	AUCUN AGENDA / TABLEAU DE SYNTHESE DES AGENDAS
		if(count($_SESSION["cfg"]["espace"]["agendas_affiches"])>1)			{require "agendas.inc.php";}
		elseif(count($_SESSION["cfg"]["espace"]["agendas_affiches"])==0)	{echo "<div class='div_elem_aucun'>".$trad["AGENDA_aucun_agenda_visible"]."</div>";}

		////	AFFICHAGE DE CHAQUE AGENDA
		////
		if(count($_SESSION["cfg"]["espace"]["agendas_affiches"])==1 || @$_REQUEST["agendas_details"]==1)
		{
			foreach($_SESSION["cfg"]["espace"]["agendas_affiches"] as $id_agenda)
			{
				echo "<a id='div_agenda_".$id_agenda."' style='position:absolute;margin-top:-60px;'></a>";
				echo "<div id='agenda".$id_agenda."_conteneur' class='agenda_conteneur'>";
					////	HEADER DE L'AGENDA
					////
					echo "<table style='width:100%;font-weight:bold;' cellpadding='2px'><tr>";
						////	MENU CONTEXTUEL + TITRE
						echo "<td class='print_titre'>";
							$agenda_tmp = $AGENDAS_AFFICHES[$id_agenda];
							$cfg_menu_elem = $AGENDAS_AFFICHES[$id_agenda]["cfg_menu_elem"];
							$cfg_menu_elem["taille_icone"] = "big";
							require PATH_INC."element_menu_contextuel.inc.php";
							echo majuscule($agenda_tmp["titre"])." &nbsp; <span style='font-size:10px;font-weight:normal;'>".text_reduit($agenda_tmp["description"],100)."</span>";
						echo "</td>";
						echo "<td class='print_titre libelle_agenda'>";
							////	PRECEDANT
							echo "<img src=\"".PATH_TPL."divers/precedent.png\" onClick=\"redir('index.php?date_affiche=".($config["agenda_debut"]-$url_pre)."');\" ".infobulle($trad["AGENDA_periode_precedante"])." class='menu_agenda noprint' style='margin-right:10px;' />&nbsp; ";
							////	AFFICHAGE JOUR / SEMAINE  (exple : "01 novembre - 07 novembre (semaine 4)")
							if(preg_match("/semaine|jour/i",$_SESSION["cfg"]["espace"]["agenda_affichage"]))
							{
								echo formatime("%d %B",$config["agenda_debut"]);
								if(preg_match("/semaine/i",$_SESSION["cfg"]["espace"]["agenda_affichage"]))		{echo " - ".formatime("%d %B",$config["agenda_fin"]); }
								echo " &nbsp; (".$trad["AGENDA_num_semaine"]." ".date("W",$config["agenda_debut"]).")";//(ne pas utiliser strftime("%W"): car pas le même résultat)
							}
							////	AFFICHAGE MOIS  (exple : "novembre 2017")
							else
							{
								// Mois (avec menu)
								echo "<div class='menu_context' id='menu_mois_cal".$id_agenda."'><div style='float:left;'>";
								for($cpt_mois=1; $cpt_mois<=12; $cpt_mois++) {
									$mois_unix = strtotime($cal_annee."-".$cpt_mois."-01");
									$class = ($cpt_mois==$cal_mois)  ?  "class='lien_select'"  :  "";
									if($cpt_mois==7)	{echo "</div><div style='float:left;margin-left:10px;'>";} // affiche 2ème colonne
									echo "<a href=\"javascript:redir('".php_self()."?date_affiche=".$mois_unix."');\" ".$class.">".formatime("%B",$mois_unix)."</a><br>";
								}
								echo "</div></div>";
								echo "<span id='icone_menu_mois_cal".$id_agenda."'>".ucfirst(formatime("%B",$_REQUEST["date_affiche"]))." <img src=\"".PATH_TPL."divers/derouler.png\" class='noprint' /></span>";
								echo "<script type='text/javascript'> menu_contextuel('menu_mois_cal".$id_agenda."'); </script> &nbsp;&nbsp;";
								// Année (avec menu)
								echo "<div class='menu_context' id='menu_year_cal".$id_agenda."'><div style='float:left;'>";
								for($cpt_annee=($cal_annee-6); $cpt_annee<=($cal_annee+5); $cpt_annee++) {
									$annee_unix = strtotime($cpt_annee."-".$cal_mois."-01");
									$class = ($cpt_annee==$cal_annee)  ?  "class='lien_select'"  :  "";
									if($cpt_annee==$cal_annee)	{echo "</div><div style='float:left;margin-left:10px;'>";} // affiche 2ème colonne
									echo "<a href=\"javascript:redir('".php_self()."?date_affiche=".$annee_unix."');\" ".$class.">".strftime("%Y",$annee_unix)."</a><br>";
								}
								echo "</div></div>";
								echo "<span id='icone_menu_year_cal".$id_agenda."'>".strftime("%Y",$_REQUEST["date_affiche"])." <img src=\"".PATH_TPL."divers/derouler.png\" class='noprint' /></span>";
								echo "<script type='text/javascript'> menu_contextuel('menu_year_cal".$id_agenda."'); </script>";
							}
							////	SUIVANT
							echo "&nbsp; <img src=\"".PATH_TPL."divers/suivant.png\" onClick=\"redir('index.php?date_affiche=".($config["agenda_debut"]+$url_suiv)."');\" ".infobulle($trad["AGENDA_periode_suivante"])." class='menu_agenda noprint' style='margin-left:10px;margin-right:50px;' />";
						echo "</td>";
						////	AFFICHAGE : JOUR/SEMAINE/MOIS
						echo "<td style='text-align:right' class='noprint'>";
							echo "<img src=\"".PATH_TPL."module_agenda/aujourdhui.gif\" onClick=\"redir('index.php?date_affiche=".time()."');\" ".infobulle($trad["aff_aujourdhui"])." class='menu_agenda' />&nbsp; &nbsp;";
							echo "<img src=\"".PATH_TPL."module_agenda/jour.gif\" onClick=\"redir('index.php?affichage_demande=jour');\" ".infobulle($trad["AGENDA_evt_jour"])." class='menu_agenda' />&nbsp;";
							echo "<img src=\"".PATH_TPL."module_agenda/semaine_w.gif\" onClick=\"redir('index.php?affichage_demande=semaine_w');\" ".infobulle($trad["AGENDA_evt_semaine_w"])." class='menu_agenda' />&nbsp;";
							echo "<img src=\"".PATH_TPL."module_agenda/semaine.gif\" onClick=\"redir('index.php?affichage_demande=semaine');\" ".infobulle($trad["AGENDA_evt_semaine"])." class='menu_agenda' />&nbsp;";
							echo "<img src=\"".PATH_TPL."module_agenda/mois.gif\" onClick=\"redir('index.php?affichage_demande=mois');\" ".infobulle($trad["AGENDA_evt_mois"])." class='menu_agenda' />&nbsp;";
						echo "</td>";
					echo "</tr></table>";
					////	AGENDA DE LA SEMAINE / DU MOIS
					////
					if(preg_match("/jour|semaine/i",$_SESSION["cfg"]["espace"]["agenda_affichage"]))	{require "agenda_semaine.inc.php";}
					else																				{require "agenda_mois.inc.php";}
				echo "</div>";
			}
		}


		////	PROPOSE D'AFFICHER LE DETAIL DE CHAQUE AGENDA
		////
		if(count($_SESSION["cfg"]["espace"]["agendas_affiches"])>1){
			if(@$_REQUEST["agendas_details"]==1)	{ $agendas_details = 0;  $libelle_agendas_details = $trad["AGENDA_agendas_details_masquer"]; }
			else									{ $agendas_details = 1;  $libelle_agendas_details = $trad["AGENDA_agendas_details"]; }
			echo "<div style='text-align:center;'><button class='button_big' style='width:230px;' onClick=\"redir('".php_self()."?agendas_details=".$agendas_details."');\">".$libelle_agendas_details."</button></div>";
		}
		?>
	</td>
</tr></table>


<?php
// Impression de la page ?
if(isset($_GET["printmode"]))	{echo "<script type='text/javascript'>  $(window).load(function(){ print(); });  </script>";}
// Footer
require PATH_INC."footer.inc.php";
?>