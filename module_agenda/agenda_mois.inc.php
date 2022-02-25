<?php
////	INITIALISATION DU TEMPS
////
$annee_mois_affiche	= strftime("%Y-%m",$_SESSION["cfg"]["espace"]["agenda_date"]);
$nb_jours_mois		= date("t",$_SESSION["cfg"]["espace"]["agenda_date"]);
$T_premier_jour_mois = strtotime($annee_mois_affiche."-01");
$T_dernier_jour_mois = strtotime($annee_mois_affiche."-".$nb_jours_mois);
$jour_semaine_premier_jour_mois	= str_replace("0","7", strftime("%w",$T_premier_jour_mois));
$jour_semaine_dernier_jour_mois	= str_replace("0","7", strftime("%w",$T_dernier_jour_mois));
$cpt_semaines = 0;


////	LISTE DES JOURS A AFFICHER (FORMAT Y-m-d)
////
$jours_affiches = array();
// DERNIERS JOURS DU MOIS PRECEDANT (DE LUNDI A ...)
for($cpt_jour=1; $cpt_jour<$jour_semaine_premier_jour_mois; $cpt_jour++){
	$T_separation_jour_debut_mois = 86400 * ($jour_semaine_premier_jour_mois - $cpt_jour);
	$jours_affiches[] = strftime("%Y-%m-%d", ($T_premier_jour_mois-$T_separation_jour_debut_mois));
}
// JOURS DU MOIS AFFICHE !
for($cpt_jour=1; $cpt_jour<=$nb_jours_mois; $cpt_jour++){
	$jours_affiches[] = $annee_mois_affiche."-".num2carac($cpt_jour);
}
// PREMIERS JOURS DU MOIS SUIVANT (DE.. A DIMANCHE)
for($cpt_jour=($jour_semaine_dernier_jour_mois+1); $cpt_jour<=7; $cpt_jour++){
	$T_separation_jour_fin_mois = 86400 * ($cpt_jour - $jour_semaine_dernier_jour_mois);
	$jours_affiches[] = strftime("%Y-%m-%d", ($T_dernier_jour_mois+$T_separation_jour_fin_mois));
}
?>


<style type="text/css">
.jour_header			{ font-weight:bold; text-align:center; border-bottom:solid #eee 1px; }
.num_semaine			{ vertical-align:middle; color:#999; text-align:left; cursor:pointer; padding:1px; border-right:solid #eee 1px; }
.num_semaine:hover		{ background-color:#ccc; }
.cell_jour				{ background-color:#fff; width:14.5%; border-width:2px 1px 1px 2px; border-color:#fff #ccc #ddd #fff; border-style:solid; } /* haut-droite-bas-hauche*/
.cell_jour_ancien		{ background-color:#f5f5f5; }
.cell_jour_mois_pre_suiv{ background-color:#e1e1e1; border-width:2px 1px 1px 2px; border-color:#f5f5f5 #ddd #ddd #f5f5f5; border-style:solid; }
.libjour				{ padding:4px; font-size:13px; font-weight:bold; color:#555; text-align:right; border-bottom:dotted #ddd 1px; }
.libjour:hover			{ background-color:#eaeaea; }
.libjour_aujourdhui		{ color:#f00; }
.libjour_ferie			{ color:#070; font-style:italic; float:left; }
/* IMPRESSION */
@media print {
	.cell_jour			{ border:1px #ddd solid; height:55px; }
	.cell_jour_mois_pre_suiv{ border:1px #ddd solid; opacity:0.4; filter:alpha(opacity=40); }
	.libjour			{ font-size:10px; }
	.libjour_ferie		{ font-size:10px; }
	.div_evt_contenu	{ font-size:10px; }
}
</style>


<table style="width:100%;" cellpadding="0px" cellspacing="0px">
	<?php
	////	ENTETE : JOURS DE LA SEMAINE
	echo "<tr>";
		echo "<td><!--N° DE SEMAINE--></td>";
		for($j=1; $j<=7; $j++)	{ echo "<td class='jour_header'>".$trad["jour_".$j]."</td>"; }
	echo "</tr>";


	////	AFFICHAGE DE CHAQUE CELLULE DU MOIS
	////
	foreach($jours_affiches as $jour_ymd)
	{
		////	INIT
		$agenda_proposer_affecter_evt = agenda_proposer_affecter_evt($agenda_tmp);
		$T_debut = strtotime($jour_ymd." 00:00:00");
		$T_fin	 = strtotime($jour_ymd." 23:59:59");
		$jour_semaine = str_replace("0","7", strftime("%w",$T_debut));
		$cpt_jour = abs(strftime("%d",$T_debut));

		////	JOUR PASSE  /  JOUR D'AUJOURD'HUI  /  JOUR FERIE  /  JOUR D'UN MOIS PRECEDANT OU SUIVANT
		$class_cellule_jour = $class_aujourdhui = $libe_jour_ferie = "";
		if($annee_mois_affiche!=strftime("%Y-%m",$T_debut))		{$class_cellule_jour = "cell_jour_mois_pre_suiv";}
		elseif($T_fin<time())									{$class_cellule_jour = "cell_jour_ancien";}
		if($jour_ymd==strftime("%Y-%m-%d",time()))				{$class_aujourdhui = "libjour_aujourdhui";}
		if(array_key_exists($jour_ymd,$tab_jours_feries))		{$libe_jour_ferie = "<span class='libjour_ferie' title=\"".$tab_jours_feries[$jour_ymd]."\">".text_reduit($tab_jours_feries[$jour_ymd],30)."</span>";}

		////	LIBELLE JOUR  /  AJOUT EVT
		$lien_ajouter = $icone_ajouter = $libelle_proposer = "";
		if($agenda_proposer_affecter_evt!="")
		{
			$date_evt_edit = strtotime($jour_ymd." ".strftime("%H").":00:00");
			if($agenda_proposer_affecter_evt=="proposer")	{$libelle_proposer = " (".$trad["AGENDA_proposer"].")";}
			$id_txt_ajouter = "icone_ajout_".$agenda_tmp["id_agenda"]."_".$jour_ymd;
			$lien_ajouter = "style='cursor:pointer;'  onClick=\"popupLightbox('evenement_edit.php?date=".$date_evt_edit."&id_agenda=".$id_agenda."');\" onMouseOver=\"afficher('".$id_txt_ajouter."',true);bulle('".addslashes($trad["AGENDA_ajouter_evt_jour"].$libelle_proposer)."');\"  onMouseOut=\"afficher('".$id_txt_ajouter."',false);bullefin();\" ";
			$icone_ajouter = "&nbsp;<img src='".PATH_TPL."divers/plus.png' id='".$id_txt_ajouter."' style='display:none;' />";
		}

		////	AFFICHAGE
		////
		// Debut de la ligne de semaine : numero de semaine  (ne pas utiliser strftime("%W"): car pas le même résultat)
		if($jour_semaine==1)	{echo "<tr><td class='num_semaine' onClick=\"redir('index.php?affichage_demande=semaine&date_affiche=".$T_debut."');\" title=\"".$trad["AGENDA_voir_num_semaine"]." ".date("W",$T_debut)."\">".date("W",$T_debut)."</td>";}
		// Cellule du jour
		echo "<td class='cell_jour ".$class_cellule_jour."'>";
			////	LIBELLE JOUR + JOUR FERIE + AJOUT EVT
			echo "<div class='libjour ".$class_aujourdhui."' ".$lien_ajouter.">".$libe_jour_ferie.$cpt_jour.$icone_ajouter."</div>";
			////	EVENEMENTS DU JOUR
			foreach(liste_evenements($agenda_tmp["id_agenda"],$T_debut,$T_fin) as $evt_tmp)
			{
				// COULEUR CATEGORIE  /  IMPORTANT  /  INFOBULLE (TITRE + DESCRIPTION)
				$evt_tmp["important"]	= ($evt_tmp["important"]>0)		?  "&nbsp;<img src=\"".PATH_TPL."divers/important_small.png\" />"  :  "";
				$evt_tmp["couleur_cat"]	= ($evt_tmp["id_categorie"]>0)	?  db_valeur("SELECT couleur FROM gt_agenda_categorie WHERE id_categorie='".$evt_tmp["id_categorie"]."'")  :  "#333";
				$infobulle = $evt_tmp["titre"]."<br><span style='font-weight:normal'>".text_reduit(strip_tags($evt_tmp["description"]),300)."</span>";
				// MENU CONTEXTUEL
				$id_div_evt = "div_agenda".$agenda_tmp["id_agenda"]."_evt".$evt_tmp["id_evenement"];
				$cfg_menu_elem = evt_cfg_menu_elem($evt_tmp, $agenda_tmp, $jour_ymd);
				$cfg_menu_elem["id_div_element"] = $id_div_evt;
				$cfg_menu_elem["action_click_block"] = "popupLightbox('evenement.php?id_evenement=".$evt_tmp["id_evenement"]."');";
				////	AFFICHAGE
				echo "<div id='".$id_div_evt."' style=\"".style_evt($agenda_tmp,$evt_tmp)."\">";
					require PATH_INC."element_menu_contextuel.inc.php";
					echo "<div class='div_evt_contenu' ".infobulle($infobulle)." >";
						echo "<b>".temps($evt_tmp["date_debut"],"mini",$evt_tmp["date_fin"])."</b>&nbsp; ".text_reduit($evt_tmp["titre"],55).$evt_tmp["important"];
					echo "</div>";
				echo "</div>";
			}
		echo "</td>";

		// Fin de la ligne de semaine
		if($jour_semaine==7)	{ echo "</tr>";  $cpt_semaines++; }
	}
	?>
</table>


<script type="text/javascript">
function calendarSize()
{
	var cellJourHeight=<?php echo (isset($_GET["printmode"])) ? "100" : "Math.floor(($(window).height()-190)/".(int)$cpt_semaines.")"; ?>;//190=barre menu + entête agenda + footer
	$(".cell_jour").css("height",cellJourHeight+"px");
}
$(function(){ calendarSize(); });
$(window).resize(function(){ calendarSize(); });
</script>