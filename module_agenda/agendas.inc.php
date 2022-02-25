<style>
.synth_tab_agendas			{ width:99.5%; margin-bottom:40px; page-break-after:always; font-weight:bold; font-size:13px; <?php  echo STYLE_BLOCK; ?> }
.synth_ligne_agenda			{ height:22px; }
.synth_tab_evts_jour		{ height:22px; width:100%; }
.synth_ligne_agenda:hover	{ <?php  echo STYLE_SELECT_RED; ?> }
.synth_lib_agenda			{ padding-left:5px; text-align:left; vertical-align:middle; font-size:13px; cursor:pointer; }
.synth_jour_semaine			{ background-color:#ddd; }
.synth_jour_we				{ background-color:#ccc; }
.synth_jour_occupe			{ background-color:#555; }
</style>


<?php
////	TABLEAU DES AGENDAS
////
echo "<table class='synth_tab_agendas' cellspacing='1px'>";
	////	INITIALISATION DU TABLEAU  &  ENTETE DES JOURS
	/////
	$nb_jours = round(($config["agenda_fin"]-$config["agenda_debut"])/86400);
	$tab_jours_synthese = array();
	echo "<tr>";
		echo "<td style='min-width:150px'>&nbsp;</td>";
		for($cpt_jour=0; $cpt_jour < $nb_jours; $cpt_jour++)
		{
			// Initialisation du tableau
			$jour_unix = $config["agenda_debut"]+(86400*$cpt_jour)+43200; // 43200 pour se situer à 12h00
			$jour_unix_debut = strtotime(strftime("%Y-%m-%d 00:00",$jour_unix));
			$jour_unix_fin   = strtotime(strftime("%Y-%m-%d 23:59",$jour_unix));
			$jour_libelle = "<u>".formatime("%A %d %B %Y",$jour_unix)."</u>";
			$jour_we = (str_replace("0","7",strftime("%w",$jour_unix_debut))>5)  ?  true  :  false;
			$tab_jours_synthese[$cpt_jour] = array("jour_unix_debut"=>$jour_unix_debut, "jour_unix_fin"=>$jour_unix_fin, "jour_libelle"=>$jour_libelle, "jour_we"=>$jour_we, "nb_agendas_occupes"=>0);
			// Entête du jour
			$style_jour_tmp =  (strftime("%Y-%m-%d",$jour_unix)==strftime("%Y-%m-%d",time()))  ?  STYLE_SELECT_RED  :  "";
			echo "<td style='cursor:help;text-align:center;width:".floor(100/$nb_jours)."%;".$style_jour_tmp."'>".intval(strftime("%d",$jour_unix))."</td>";
		}
	echo "</tr>";


	/////	AFFICHE CHAQUE AGENDA
	/////
	foreach($_SESSION["cfg"]["espace"]["agendas_affiches"] as $id_agenda)
	{
		////	DEBUT DE LIGNE D'AGENDA + TITRE
		echo "<tr onClick=\"goToByScroll('div_agenda_".$id_agenda."');\" class='synth_ligne_agenda'>";
			echo "<td class='synth_lib_agenda'>".$AGENDAS_AFFICHES[$id_agenda]["titre"]."</td>";
			////	LISTE CHAQUE JOUR
			foreach($tab_jours_synthese as $cpt_jour =>$jour_tmp)
			{
				////	EVENEMENTS DU JOUR
				////
				$tab_evts_jour = "";
				$tab_evts_jour_infobulle = "<acronym>".$AGENDAS_AFFICHES[$id_agenda]["titre"]." : ".formatime("%A %d %B %Y",$jour_tmp["jour_unix_debut"])."</acronym>";
				$class_jour = ($jour_tmp["jour_we"]==true)  ?  "synth_jour_we"  :  "synth_jour_semaine";
				$liste_evenements_tmp = liste_evenements($id_agenda,$jour_tmp["jour_unix_debut"],$jour_tmp["jour_unix_fin"]);
				if(count($liste_evenements_tmp)>0)
				{
					$class_jour = "synth_jour_occupe";
					$tab_evts_jour = "<table class='synth_tab_evts_jour' class='table_nospace' cellpadding='0' cellspacing='0'><tr>";
					$tab_evts_jour_infobulle .= "<ul style='margin:2px;padding:2px;padding-top:5px;padding-left:5px;'>";
					// Affichage des evts du jour de l'agenda
					foreach($liste_evenements_tmp as $evt_tmp)
					{
						$evt_tmp["important"] = ($evt_tmp["important"]=="1")  ?  " &nbsp;<img src='".PATH_TPL."divers/important_small.png' />&nbsp; "  :  "";
						// CATEGORIE
						$categorie = array();
						if($evt_tmp["id_categorie"] > 0){
							$categorie = db_ligne("SELECT * FROM gt_agenda_categorie WHERE id_categorie='".$evt_tmp["id_categorie"]."'");
							$evt_tmp["categorie_info"] = " &nbsp; <span style='padding:1px;background-color:".$categorie["couleur"].";'>".$categorie["titre"]."</span>";
						}
						// Tableau de Evt : ajoute la cellule de l'evt + ajoute l'evt dans l'infobulle
						$tab_evts_jour .= "<td style='background-color:".@$categorie["couleur"].";'>&nbsp;</td>";
						$tab_evts_jour_infobulle .= "<li><b>".temps($evt_tmp["date_debut"],"mini",$evt_tmp["date_fin"])."</b>".@$evt_tmp["categorie_info"]."<br>".$evt_tmp["titre"].$evt_tmp["important"]."</li>";
					}
					$tab_evts_jour .= "</tr></table>";
					$tab_evts_jour_infobulle .= "</ul>";
					// Ajoute au tableau de synthèse (dernière ligne)
					$tab_jours_synthese[$cpt_jour]["nb_agendas_occupes"] = $tab_jours_synthese[$cpt_jour]["nb_agendas_occupes"] + 1;
				}
				// Affiche la cellule du jour
				echo "<td ".infobulle($tab_evts_jour_infobulle)." class='".$class_jour."'>".$tab_evts_jour."</td>";
			}
		////	FIN DE LIGNE D'AGENDA
		echo "</tr>";
	}


	////	LIGNE DE SYNTHESE DES AGENDAS
	////
	echo "<tr class='synth_ligne_agenda'>";
		echo "<td class='synth_lib_agenda'><i>".$trad["AGENDA_synthese"]."</i></td>";
		$nb_agendas_affiches = count($_SESSION["cfg"]["espace"]["agendas_affiches"]);
		foreach($tab_jours_synthese as $jour_tmp)
		{
			// init
			$class_jour = ($jour_tmp["jour_we"]==true)  ?  "synth_jour_we"  :  "synth_jour_semaine";
			$libelle_synthese  = "<u>".formatime("%A %d %B %Y",$jour_tmp["jour_unix_debut"])."</u>";
			// Journée occupée ?
			if($jour_tmp["nb_agendas_occupes"]>0){
				$class_jour = "synth_jour_occupe";
				$libelle_synthese  .= "<br>".$trad["AGENDA_pourcent_agendas_occupes"]." : <span style='margin-top:5px;'>".$jour_tmp["nb_agendas_occupes"]." / ".$nb_agendas_affiches."</span>";
			}
			echo "<td ".infobulle(@$libelle_synthese)." class='".$class_jour."'>&nbsp;</td>";
		}
	echo "</tr>";


echo "</table>";
?>