<?php
////	INIT
define("IS_MAIN_PAGE",true);
require "commun.inc.php";
require PATH_INC."header_menu.inc.php";
$hauteur_maxi_actus = 800;

////	SUPPR ACTUALITE ?
////
if(isset($_GET["action"]) && $_GET["action"]=="suppr_actualite")	suppr_actualite($_GET["id_actualite"]);

////	ACTUALITES A METTRE ONLINE / OFFLINE
////
db_query("UPDATE gt_actualite SET  offline=null  WHERE  UNIX_TIMESTAMP(date_online)>0 AND date_online<='".strftime("%Y-%m-%d 00:00:00")."' AND (date_offline is null or date_offline>='".strftime("%Y-%m-%d 00:00:00")."')");
db_query("UPDATE gt_actualite SET  offline='1'  WHERE  UNIX_TIMESTAMP(date_offline)>0 AND date_offline<='".strftime("%Y-%m-%d 00:00:00")."'");
?>


<script type="text/javascript">
////	DEROULER UNE LONGUE ACTUALITE
function actus_display(id_actu, init)
{
	// Bouton "Tout afficher" ?
	if(element("actualite"+id_actu).scrollHeight > <?php echo $hauteur_maxi_actus; ?>)
	{
		// Positionne et affiche le bouton
		if(init==true){
			tmp_top = $("#actualite"+id_actu).height() - $("#derouler_actualite"+id_actu).height() - 5;	//-padding-top du block
			tmp_left = ($("#actualite"+id_actu).width() / 2) - ($("#derouler_actualite"+id_actu).width() / 2);
			$("#derouler_actualite"+id_actu).css("margin-top", tmp_top+"px");
			$("#derouler_actualite"+id_actu).css("margin-left", tmp_left+"px");
			afficher("derouler_actualite"+id_actu, true);
		}
		// Déroule le contenu et masque le bouton
		else{
			$("#actualite"+id_actu).css("overflow","visible");
			$("#actualite"+id_actu).css("max-height","10000px");
			afficher("derouler_actualite"+id_actu, false);
		}
	}
}
</script>


<?php
echo "<table class='contenu_principal_centre'><tr>";

	////	MENU DE GAUCHE
	////
	echo "<td id='menu_gauche_block_td'>";
		echo "<div id='menu_gauche_block_flottant'>";

			////	NOUVEAUTES + ELEMENTS COURANTS
			////
			echo "<div class='menu_gauche_block content'>";

				////	DEFINITION DES PERIODES (FORMAT UNIX)
				////	Période par défaut
				pref_user("tdb_periode","tdb_periode","autre_periode");
				if(!isset($_REQUEST["tdb_periode"]))  $_REQUEST["tdb_periode"] = "semaine";
				// depuis la dernière connexion
				$tdb_connexion_debut = @$_SESSION["user"]["precedente_connexion"];
				$tdb_connexion_fin	 = strtotime(strftime("%Y-%m-%d %H:%M:%S"));
				$tdb_connexion_libelle = formatime("%A %d %B %Y",$tdb_connexion_debut)." > ".$trad["aujourdhui"];
				// jour
				$tdb_jour_debut	= strtotime(strftime("%Y-%m-%d 00:00:00"));
				$tdb_jour_fin	= strtotime(strftime("%Y-%m-%d 23:59:59"));
				$tdb_jour_libelle = formatime("%A %d %B",$tdb_jour_debut);
				// cette semaine
				$date_jour_semaine = str_replace("0","7", strftime("%w"));  // Jour de la semaine : 1=lundi,... 7=dimanche
				$tdb_semaine_debut	= strtotime(strftime("%Y-%m-%d 00:00:00")) - (86400*($date_jour_semaine-1));
				$tdb_semaine_fin	= $tdb_semaine_debut + (86400*7)-1;
				$tdb_semaine_libelle = formatime("%A %d %B",$tdb_semaine_debut)." > ".formatime("%A %d %B",$tdb_semaine_fin);
				// ce mois
				$tdb_mois_debut = strtotime(strftime("%Y")."-".strftime("%m")."-01 00:00:00");
				$tdb_mois_fin	= strtotime(strftime("%Y")."-".strftime("%m")."-".date("t",time())." 23:59:59");  // avec nb de jours dans le mois
				$tdb_mois_libelle = formatime("%A %d %B",$tdb_mois_debut)." > ".formatime("%A %d %B",$tdb_mois_fin);
				// autre période
				$tdb_autre_debut = strtotime(@$_REQUEST["tdb_debut"]." 00:00:00");
				$tdb_autre_fin	 = strtotime(@$_REQUEST["tdb_fin"]." 23:59:59");
				////	Dates de début / fin de période de la sélection
				if($_REQUEST["tdb_periode"]=="connexion")		{  $tdb_debut = $tdb_connexion_debut;	$tdb_fin = $tdb_connexion_fin; }
				elseif($_REQUEST["tdb_periode"]=="jour")		{  $tdb_debut = $tdb_jour_debut;		$tdb_fin = $tdb_jour_fin; }
				elseif($_REQUEST["tdb_periode"]=="semaine")		{  $tdb_debut = $tdb_semaine_debut;		$tdb_fin = $tdb_semaine_fin; }
				elseif($_REQUEST["tdb_periode"]=="mois")		{  $tdb_debut = $tdb_mois_debut;		$tdb_fin = $tdb_mois_fin; }
				else											{  $tdb_debut = $tdb_autre_debut;		$tdb_fin = $tdb_autre_fin; }

				////	MENU DE SELECTION DE PERIODE
				echo "<div style='text-align:center;'>";
					// LEGENDE
					echo "<div style='margin-bottom:8px;cursor:help;font-weight:normal;'>";
						$realises_bulle_fin = (preg_match("/jour|connexion/i",$_REQUEST["tdb_periode"])==false)  ?  " > ".formatime("%A %d %B",$tdb_fin)  :  "";
						echo "<span ".infobulle($trad["TABLEAU_BORD_new_elems_bulle"])."><img src=\"".PATH_TPL."module_tableau_bord/new_elems.png\" />&nbsp;".$trad["TABLEAU_BORD_new_elems"]."</span> &nbsp; ";
						echo "<span ".infobulle($trad["TABLEAU_BORD_new_elems_realises_bulle"].$realises_bulle_fin)."><img src=\"".PATH_TPL."module_tableau_bord/new_elems_realises.png\" />&nbsp;".$trad["TABLEAU_BORD_new_elems_realises"]."</span>";
					echo "</div>";
					// INPUT SELECT : CONNEXION / JOUR / SEMAINE / MOIS / AUTRE PERIODE
					echo "<select id='select_tdb_periode' onChange=\"if(this.value=='autre_periode') {afficher('form_autre_periode',true);}  else {redir('index.php?tdb_periode='+this.value);}\" style='text-align:center;'>";
						if($_SESSION["user"]["id_utilisateur"]>0)	{echo "<option value='connexion'  title=\"".$tdb_connexion_libelle."\">..".$trad["TABLEAU_BORD_plugin_connexion"]."</option>";}
						echo "<option value='jour'  title=\"".$tdb_jour_libelle."\">..".$trad["TABLEAU_BORD_plugin_jour"]."</option>";
						echo "<option value='semaine'  title=\"".$tdb_semaine_libelle."\">..".$trad["TABLEAU_BORD_plugin_semaine"]."</option>";
						echo "<option value='mois'  title=\"".$tdb_mois_libelle."\">..".$trad["TABLEAU_BORD_plugin_mois"]."</option>";
						echo "<option value='autre_periode'>".$trad["TABLEAU_BORD_autre_periode"]."</option>";
					echo "</select><br>";
					echo "<script> set_value('select_tdb_periode','".$_REQUEST["tdb_periode"]."',true); </script>";
					// SELECTIONNE DATES "AUTRE PERIODE"
					echo "<form action=\"".php_self()."\" method='get' id='form_autre_periode' style='".($_REQUEST["tdb_periode"]!="autre_periode"?"display:none;":"")."'>";
						echo "<hr style='visibility:hidden;' />";
						echo "<iframe id='calendrier_tdb_debut' class='menu_context calendrier_flottant'></iframe>";
						echo "<input type='text' name='tdb_debut' value=\"".strftime("%Y-%m-%d",$tdb_debut)."\" class='calendrier_input' onClick=\"element('calendrier_tdb_debut').src='".PATH_INC."calendrier.inc.php?champ_modif=tdb_debut&pas_reinit=true&date_affiche=".$tdb_debut."&date_selection=".$tdb_debut."';  afficher_dynamic('calendrier_tdb_debut');\" readonly />";
						echo "&nbsp;<img src=\"".PATH_TPL."divers/fleche_droite.png\" />&nbsp;";
						echo "<iframe id='calendrier_tdb_fin' class='menu_context calendrier_flottant'></iframe>";
						echo "<input type='text' name='tdb_fin' value=\"".strftime("%Y-%m-%d",$tdb_fin)."\" class='calendrier_input' onClick=\"element('calendrier_tdb_fin').src='".PATH_INC."calendrier.inc.php?champ_modif=tdb_fin&pas_reinit=true&date_affiche=".$tdb_fin."&date_selection=".$tdb_fin."';  afficher_dynamic('calendrier_tdb_fin');\" readonly />";
						echo "<input type='hidden' name='tdb_periode' value='autre_periode' />&nbsp; <input type='submit' value='OK' style='width:32px;font-weight:normal;' />";
					echo "</form>";
				echo "</div>";

				////	RECUPERATION DES ELEMENTS AVEC  "plugin.inc.php"
				$cfg_plugin = array("resultats"=>array(), "mode"=>"nouveautes", "debut"=>strftime("%Y-%m-%d %H:%M:%S",$tdb_debut), "fin"=>strftime("%Y-%m-%d %H:%M:%S",$tdb_fin));
				// RECUPERATION
				foreach($_SESSION["espace"]["modules"] as $infos_module)
				{
					$cfg_plugin["module_path"] = $infos_module["module_path"];
					// Récupère le "commun.inc.php" si c'est pas le module courant
					if(defined("MODULE_PATH") && MODULE_PATH!=$cfg_plugin["module_path"])	{include_once ROOT_PATH.$cfg_plugin["module_path"]."/commun.inc.php";}
					// Fichier "plugin.inc.php" pour le module en question ?
					if(is_file(ROOT_PATH.$cfg_plugin["module_path"]."/plugin.inc.php"))		{include ROOT_PATH.$cfg_plugin["module_path"]."/plugin.inc.php";}
				}

				////	AFFICHAGE DES ELEMENTS
				$module_courant = "";
				$liste_elems = array();
				foreach($cfg_plugin["resultats"] as $elem)
				{
					// Affiche l'entête du module ?
					if($module_courant!=$elem["module_path"])
						echo "<div style='margin-top:15px;'><hr /><img src=\"".PATH_TPL.$elem["module_path"]."/plugin.png\" style='float:right;margin-top:-5px;margin-right:-5px;' /></div>";
					// Affiche l'élément (1 fois)
					if(in_array(@$elem["lien_js_libelle"],$liste_elems)==false)
					{
						// icone de l'element
						if($elem["type"]=="dossier")				$icone_plugin_tmp = "divers/dossier_arborescence.png";
						elseif(isset($elem["icone_time_alert"]))	$icone_plugin_tmp = "module_tableau_bord/new_elems_realises.png";
						else										$icone_plugin_tmp = "module_tableau_bord/new_elems.png";
						// Block d'éléments (proposition d'événements..)
						if(isset($elem["block_elements"]))	{ echo $elem["block_elements"]; }
						// Ligne d'un élément
						else
						{
							echo "<table style='cursor:pointer;vertical-align:top;'><tr>";
								echo "<td onClick=\"".$elem["lien_js_icone"]."\" valign='top'><img src=\"".PATH_TPL.$icone_plugin_tmp."\"  /></td>";
								echo "<td onClick=\"".$elem["lien_js_libelle"]."\">".$elem["libelle"]."</td>";
							echo "</tr></table>";
						}
						// Ajoute le module courant et l'element courant
						$module_courant	= $elem["module_path"];
						$liste_elems[]	= @$elem["lien_js_libelle"];
					}
				}
				if(count($cfg_plugin["resultats"])==0)	echo "<div style='padding-top:15px;text-align:center;font-weight:normal;".STYLE_FONT_COLOR_RETRAIT."'>".$trad["TABLEAU_BORD_pas_nouveaux_elements"]."</div>";
			echo "</div>";


			////	MENU DES ACTUALITES
			////
			$liste_actualites = db_tableau("SELECT * FROM gt_actualite WHERE  ".(@$_GET["actu_offline"]==1?"offline=1":"(offline is null OR offline!=1)")." ".sql_affichage($objet["actualite"])." ".tri_sql($objet["actualite"]["tri"],"une desc,"));
			echo "<div class='menu_gauche_block content'>";
				echo "<img src=\"".PATH_TPL."module_tableau_bord/actualite.png\" style='float:right;margin-top:-8px;margin-right:-8px;opacity:0.5;filter:alpha(opacity=50);' />";
					if(droit_ajout_actualite()==true)	echo "<div class='menu_gauche_ligne lien' onClick=\"popupLightbox('actualite_edit.php');\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/ajouter.png\" /></div><div class='menu_gauche_txt'>".$trad["TABLEAU_BORD_ajout_actualite"]."</div></div>";
					echo "<div class='menu_gauche_ligne lien' onClick=\"redir('".php_self()."?actu_offline=".(@$_GET["actu_offline"]==1?"0":"1")."');\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."module_tableau_bord/offline.png\" /></div><div class='menu_gauche_txt ".(@$_GET["actu_offline"]==1?"lien_select":"")."'>".$trad["TABLEAU_BORD_actualites_offline"]."</div></div>";
					echo menu_tri($objet["actualite"]["tri"],"tri_actualite");
					echo "<div class='menu_gauche_ligne'><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/info.png\" /></div><div class='menu_gauche_txt'>".count($liste_actualites)." ".(count($liste_actualites)>1?$trad["TABLEAU_BORD_actualites"]:$trad["TABLEAU_BORD_actualite"])."</div></div>";
			echo "</div>";

		echo "</div>";
	echo "</td>";


	////	ACTUALITES
	////
	echo "<td>";
		foreach($liste_actualites as $actu_tmp)
		{
			// Menu contextuel
			$id_div_actualite = "actualite".$actu_tmp["id_actualite"];
			$cfg_menu_elem = array("objet"=>$objet["actualite"], "objet_infos"=>$actu_tmp, "id_div_element"=>$id_div_actualite);
			if(droit_acces($objet["actualite"],$actu_tmp)>=2){
				$cfg_menu_elem["modif"] = "actualite_edit.php?id_actualite=".$actu_tmp["id_actualite"];
				$cfg_menu_elem["suppr"] = "index.php?action=suppr_actualite&id_actualite=".$actu_tmp["id_actualite"];
			}
			// Div conteneur
			echo "<div class='div_elem_deselect' id='actualite".$actu_tmp["id_actualite"]."' style='float:none;overflow:hidden;max-height:".$hauteur_maxi_actus."px;min-height:60px;margin-bottom:10px;".($actu_tmp["une"]==1?STYLE_SHADOW_FORT:"")."'>";
				// "Tout afficher" ?
				echo "<button id='derouler_actualite".$actu_tmp["id_actualite"]."' class='lien_select' style='display:none;position:absolute;width:220px;' onClick=\"actus_display('".$actu_tmp["id_actualite"]."');\">".$trad["tout_afficher"]." <img src=\"".PATH_TPL."divers/derouler.png\" /></button>";
				echo "<script>  $(document).ready(function(){ actus_display('".$actu_tmp["id_actualite"]."',true); });  </script>";
				require PATH_INC."element_menu_contextuel.inc.php";
				// Date d'affichage :  de mise en ligne  OU  de création
				if($actu_tmp["date_online"]!="" && @$_GET["actu_offline"]==1)	$date_affichage = "<img src=\"".PATH_TPL."divers/point_vert.png\" /> <acronym ".infobulle($trad["TABLEAU_BORD_date_online_auto"]).">".temps($actu_tmp["date_online"],"date")."</acronym>";
				elseif($actu_tmp["date_online"]!="")							$date_affichage = "<acronym ".infobulle($trad["TABLEAU_BORD_date_online_auto"]).">".temps($actu_tmp["date_online"],"date")."</acronym>";
				else															$date_affichage = temps($actu_tmp["date_crea"]);
				//Date de mise Offline
				if($actu_tmp["date_offline"]!="" && @$_GET["actu_offline"]==1)	$date_offline = " &nbsp;&nbsp; <img src=\"".PATH_TPL."divers/point_blanc.png\" /> <acronym ".infobulle($trad["TABLEAU_BORD_date_offline_auto"]).">".temps($actu_tmp["date_offline"],"date")."</acronym>";
				else															$date_offline = "";
				echo "<div style='float:right;padding:3px;font-weight:bold;font-style:italic;".STYLE_FONT_COLOR_RETRAIT."'>".$date_affichage.$date_offline."</div>";
				// Affichage de l'actu
				echo "<div style='padding:20px;padding-top:10px'>".$actu_tmp["description"]."</div>";
				affiche_fichiers_joints($objet["actualite"], $actu_tmp["id_actualite"], "normal");
			echo "</div>";
		}
		////	PAS D'ACTUS
		if(count($liste_actualites)==0)  echo "<div class='div_elem_aucun'>".$trad["TABLEAU_BORD_pas_actualites"]."</div>";
	echo "</td>";
echo "</tr></table>";


require PATH_INC."footer.inc.php";
?>