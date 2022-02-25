<?php
////	INITIALISE TOUS LES AGENDAS -> JAVASCRIPT DE MISE EN FORME, DE PLACEMENT DES EVENEMENTS, ETC
////
if(empty($isInitAgendas))
{
	////	VARIABLES DE BASE
	$cptEvts = 0;
	$isInitAgendas = true;
	$jour_secondes = 86400;
	$nb_jours_affiches = round(($config["agenda_fin"]-$config["agenda_debut"]) / $jour_secondes);

	////	INIT LA PLAGE HORAIRE  &  ON ÉTEND LA PLAGE HORAIRE SI YA DES EVTS +TOT OU +TARD  (ON PRENDS LE PREMIER AGENDA EN RÉFÉRENCE)
	$plage_horaire = (!empty($agenda_tmp["plage_horaire"]))  ?  explode("-",$agenda_tmp["plage_horaire"])  :  array("8","21");
	foreach(liste_evenements($id_agenda,$config["agenda_debut"],$config["agenda_fin"]) as $evt_tmp){
		$heure_debut = abs(strftime("%H",strtotime($evt_tmp["date_debut"])));
		if($heure_debut < $plage_horaire[0])	{$plage_horaire[0]=$heure_debut;}
		if($plage_horaire[1] <= $heure_debut)	{$plage_horaire[1]=$heure_debut++;}
	}

	////	DIMENSIONS DES AGENDAS
	$height_heures = round((@$_SESSION["cfg"]["resolution_height"]-170) / ($plage_horaire[1]-$plage_horaire[0]));//170=barre menu + entête agenda + footer
	$scroll_top_agenda = $height_heures * $plage_horaire[0];
	//Hauteur de base d'un Evt : 1/4 de la cellule d'heure, avec un minimum de 20px
	$height_min_evt = round($height_heures/4);
	if($height_min_evt < 22)	{$height_min_evt=22;}
?>

	<script type="text/javascript">
	////	CONSTRUCTION D'UN AGENDA
	////
	tab_evt = new Array();
	function agenda_semaine_construct(id_agenda, scroll_top)
	{
		////	ID DE L'AGENDA & DIMENSIONS DE BASE (CORPS, ENTETE, ETC)
		id_agenda_bis = "agenda"+id_agenda;
		$(".agenda_conteneur2,.agenda_contenu").css("height", height_agenda+"px");
		largeur_agenda = $(".agenda_conteneur2").css("width").replace("px","");
		$(".agenda_contenu").css("width", largeur_agenda+"px");//pour forcer la largeur.. sur IE

		////	CADRE DE L'AGENDA : LARGEUR DE CHAQUE JOUR (FONCTION DE L'ENTETE)  &  LARGEUR DU LIBELLE DES HEURES &  LARGEUR DE LA CELLULE D'ASCENSSEUR DANS L'ENTETE
		width_jour = Math.round((largeur_agenda - width_colonne_heure - width_ascenseur) / nb_jours_affiches);
		$(".cellule_heure_entete,.cellule_heure_libelle").css("width", width_colonne_heure+"px");
		$(".ascenseur_entete").css("width", width_ascenseur+"px");

		////	DEFINIE LA LARGEUR DU JOUR & RE-DEFINIE LA LARGEUR DES CELLULES D'HEURES (LIBELLE+PREMIERE LIGNE D'HEURE)
		width_cells_jours = (navigateur()!="ie" || version_ie()>8)  ?  (width_jour-0.5)  :  width_jour;
		for(var i=0; i < nb_jours_affiches; i++){
			$("#"+id_agenda_bis+"_libjour"+i).css("width", width_jour+"px");
			$("#"+id_agenda_bis+"_jour"+i+"_heure<?php echo $plage_horaire[0]; ?>").css("width", width_jour+"px");
		}

		////	BLOCK D'EVENEMENT : PLACEMENT A GAUCHE + LARGEUR DU BLOCK
		for(var i=0; i < tab_evt.length; ++i)
		{
			// evenement du même agenda?
			if(tab_evt[i]["id_agenda"]==id_agenda)
			{
				// D'autre evt sur le même crenaux horaire ?
				decalage_evt = 0;
				// Début de l'evt courant dans le créneau de l'evt_tmp ? => on ajoute 20 pixels de décalage en plus
				for(var j=0; j < tab_evt.length; ++j){
					if(tab_evt[j]["id_agenda"]==id_agenda  &&  tab_evt[i]["id_evenement"]!=tab_evt[j]["id_evenement"]  &&  tab_evt[i]["T_debut"]>=tab_evt[j]["T_debut"]  &&  tab_evt[i]["T_debut"]<tab_evt[j]["T_fin"]  &&  typeof tab_evt[j]["decalage_evt"]=="number")
						decalage_evt = tab_evt[j]["decalage_evt"] + 20;
				}
				// Enregistre le décalage  +  Placement horizontal evt  +  largeur evt  +  bordure evt?
				tab_evt[i]["decalage_evt"] = decalage_evt;
				var margin_left_tmp = width_colonne_heure;
				for(var k=0; k < tab_evt[i]["jour"]; k++){
					margin_left_tmp += width_cells_jours;
				}
				$("#"+tab_evt[i]["id_div_evt"]).css("marginLeft", (margin_left_tmp+decalage_evt)+"px");
				$("#"+tab_evt[i]["id_div_evt"]).css("width", (width_cells_jours-decalage_evt-1)+"px");//-1 pour le shadow
				if(decalage_evt>0 && $("#"+tab_evt[i]["id_div_evt"]).css("border")=="")		{$("#"+tab_evt[i]["id_div_evt"]).css("border","1px solid #999");}
			}
		}

		////	AFFICHAGE DE L'AGENDA  +  SCROLL A L'HEURE DEMANDEE
		$(".agenda_contenu").css("visibility","visible");
		$("#"+id_agenda_bis+"_contenu").scrollTop(scroll_top);
	}


	////	SELECTION D'HEURES
	////
	function select_creneau_horaire(id_agenda, creneau_jour, creneau_heure, creneau_minute, T_creneau_debut, T_creneau_fin)
	{
		// (re-)Initialise la sélection
		if(isMouseDown==false)
		{
			// Réinitialise le css des cellules (si l'heure n'est pas initialisée)
			if(T_selection_debut!=null)
			{
				for(var jour_tmp=0; jour_tmp<nb_jours_affiches; jour_tmp++){
					for(var heure_tmp=0; heure_tmp<24; heure_tmp++){
						for(var minute_tmp=0; minute_tmp<=45; minute_tmp+=15){
							$("#agenda"+id_agenda+"_jour"+jour_tmp+"_heure"+heure_tmp+"_minute"+minute_tmp).removeClass("cellule_heure_quart_selected");
						}
					}
				}
			}
			// Réinitialise les variables
			jour_selection = T_selection_debut = T_selection_fin = null;
		}
		// Sélection d'heures
		else
		{
			// Init
			if(jour_selection==null)	jour_selection = creneau_jour;
			// Etend la plage horaire si on reste sur le même jour
			if(jour_selection==creneau_jour)
			{
				// Initialise le début & définit la fin
				if(T_selection_debut==null)										{ T_selection_debut = T_creneau_debut;	selection_heure_debut = creneau_heure;	selection_minute_debut = creneau_minute; }
				if(T_selection_fin==null || T_selection_debut < T_creneau_fin)	{ T_selection_fin = T_creneau_fin;		selection_heure_fin = creneau_heure;	selection_minute_fin = creneau_minute; }
				// Sélectionne / déselectionne la cellule en CSS
				for(var heure_tmp=0; heure_tmp<24; heure_tmp++)
				{
					for(var minute_tmp=0; minute_tmp<=45; minute_tmp+=15)
					{
						heure_minute_tmp = (heure_tmp*100) + minute_tmp;
						id_agenda_jour_heure_minute = "#agenda"+id_agenda+"_jour"+creneau_jour+"_heure"+heure_tmp+"_minute"+minute_tmp;
						if(((selection_heure_debut*100)+selection_minute_debut) <= heure_minute_tmp && heure_minute_tmp <= ((selection_heure_fin*100)+selection_minute_fin))	$(id_agenda_jour_heure_minute).addClass("cellule_heure_quart_selected");
						else																																					$(id_agenda_jour_heure_minute).removeClass("cellule_heure_quart_selected");
					}
				}
			}
		}
	}

	////	Initialise les agendas
	////
	$(window).load(function(){
		//Dimensions de base & co
		height_agenda = Math.floor($(window).height()-170);//170=barre menu + entête agenda + footer
		nb_jours_affiches = <?php echo $nb_jours_affiches; ?>;
		width_colonne_heure = 40;
		width_ascenseur = 17;
		//Détection de Mousedown/Mouseup pour l'ajout d'événement
		isMouseDown = false;
		jour_selection = T_selection_debut = T_selection_fin = null;
		$(".cellule_heure").mousedown(function(){ isMouseDown=true; });
		$("body").mouseup(function(){ isMouseDown=false; });
	});
	</script>

	<style type="text/css">
	.agenda_contenu			{ margin:0px; padding:0px; <?php if(!isset($_GET["printmode"])) echo "position:absolute;overflow:auto;visibility:hidden;"; ?> } /*PAS DE SCROLL EN PRINTMODE*/
	.ligne_heure			{ background-color:#fff; }
	.ligne_heure_creuse		{ background-color:#f3f3f3; }
	.cellule_heure_libelle	{ background-color:#ddd; border-width:0px 1px 1px 0px; border-color:#fff #fff #fff #fff; border-style:solid; text-align:center; vertical-align:top; color:#000; font-weight:bold; }
	.cellule_heure			{ border-width:1px 1px 1px 1px; border-color:#fff #ccc #ddd #fff; border-style:solid; } /*haut-droite-bas-gauche*/
	.cellule_heure_old		{ background-color:#eee; }
	.cellule_heure_courante	{ border-top:#f33 solid 1px; }
	.cellule_heure_quart:hover		{ background-color:#888; }
	.cellule_heure_quart_selected	{ background-color:#333; }
	/* IMPRESSION */
	@media print {
		.block_evt	{ color:#000; }
	}
	</style>
<?php
}


////	ID DE L'AGENDA  &  DROIT DE PROPOSITION/AFFECTATION D'ÉVÉNEMENT  &  LANCE LA CONSTRUCTION DE L'AGENDA (APRES SON AFFICHAGE..)
////
$id_agenda_bis = "agenda".$id_agenda;
$agenda_proposer_affecter_evt = agenda_proposer_affecter_evt($agenda_tmp);
echo "<script type='text/javascript'>  $(window).load(function(){ agenda_semaine_construct('".$id_agenda."',".$scroll_top_agenda."); });  </script>";


////	ENTETE
////
echo "<table id='".$id_agenda_bis."_entete' class='table_nospace' cellpadding='0' cellspacing='0' style='width:100%;text-align:center;font-weight:bold;'><tr>";
	echo "<td class='cellule_heure_entete'>&nbsp;</td>";
	////	JOURS DE LA SEMAINE
	for($jour_tmp=0; $jour_tmp < $nb_jours_affiches; $jour_tmp++)
	{
		$jour_T = $config["agenda_debut"] + ($jour_secondes*$jour_tmp);
		$style_jour_tmp = (strftime("%Y-%m-%d",$jour_T)==strftime("%Y-%m-%d",time()))  ?  STYLE_SELECT_RED  :  "";
		if(array_key_exists(strftime("%Y-%m-%d",$jour_T),$tab_jours_feries))   $jour_ferie =  "&nbsp; <img src=\"".PATH_TPL."module_agenda/ferie.png\" ".infobulle($tab_jours_feries[strftime("%Y-%m-%d",$jour_T)])." />";   else   $jour_ferie = "";
		echo "<td id='".$id_agenda_bis."_libjour".$jour_tmp."' style='".$style_jour_tmp.";'> ".formatime("%A %d/%m",$jour_T).$jour_ferie."</td>";
	}
	echo "<td  class='ascenseur_entete'>&nbsp;</td>";
echo "</tr></table>";


////	MATRICE/TABLEAU + EVENEMENTS
////
echo "<div id='".$id_agenda_bis."_conteneur2' class='agenda_conteneur2'>";
	echo "<div id='".$id_agenda_bis."_contenu' class='agenda_contenu pas_selection'>";

		////	AFFICHE CHAQUE JOUR
		for($jour_tmp=0; $jour_tmp < $nb_jours_affiches; $jour_tmp++)
		{
			////	EVENEMENTS DU JOUR
			////
			$T_jour_debut = $config["agenda_debut"] + ($jour_secondes*$jour_tmp);
			$T_jour_fin   = $T_jour_debut + $jour_secondes -1;
			$jour_ymd = strftime("%Y-%m-%d", $T_jour_debut);
			foreach(liste_evenements($id_agenda,$T_jour_debut,$T_jour_fin) as $evt_tmp)
			{
				////	INIT
				$T_evt_debut = strtotime($evt_tmp["date_debut"]);
				$T_evt_fin   = strtotime($evt_tmp["date_fin"]);
				$temps_evt = temps($evt_tmp["date_debut"],"mini",$evt_tmp["date_fin"]);
				$infobulle = $temps_evt." ".$evt_tmp["titre"]."<br><span style='font-weight:normal'>".text_reduit(strip_tags($evt_tmp["description"]),300)."</span>";
				$evt_tmp["important"]	= ($evt_tmp["important"]>0)		?  "&nbsp;<img src=\"".PATH_TPL."divers/important_small.png\" />"  :  "";
				$evt_tmp["couleur_cat"]	= ($evt_tmp["id_categorie"]>0)	?  db_valeur("SELECT couleur FROM gt_agenda_categorie WHERE id_categorie='".$evt_tmp["id_categorie"]."'")  :  "#333";

				////	MENU CONTEXTUEL  &  PLACEMENT TOP DE L'EVT  &  SCROOLTOP DE L'AGENDA
				$id_div_evt = "div_evt".$cptEvts;
				$cfg_menu_elem = evt_cfg_menu_elem($evt_tmp, $agenda_tmp, $jour_ymd);
				$cfg_menu_elem["id_div_element"] = $id_div_evt;
				$cfg_menu_elem["action_click_block"] = "popupLightbox('evenement.php?id_evenement=".$evt_tmp["id_evenement"]."');";
				if($T_evt_debut <= $T_jour_debut && $T_jour_debut < $T_evt_fin)		$evt_position_top = 0;
				else																$evt_position_top = ($height_heures *  (abs(strftime("%H",$T_evt_debut)) + abs(strftime("%M",$T_evt_debut)/60)));
				$scroll_top_evt = $height_heures * ((strftime("%H",$T_evt_debut)) + round(strftime("%M",$T_evt_debut)/60,2));
				if($scroll_top_evt < $scroll_top_agenda)	$scroll_top_agenda = $scroll_top_evt-20;
				// Mode Impression : on remonte les evenements (margin-top), car on masque les heures précédant la plage horaire
				if(isset($_GET["printmode"]))	$evt_position_top = $evt_position_top - round($plage_horaire[0]*$height_heures);

				////	HAUTEUR DE L'EVT
				// Evt simple  ||  Evt debut < periode < Evt fin  ||  Evt debut avant la periode + Evt fin dans la période  ||  Evt debut dans la periode + Evt fin après la période  ||  Evt dans la période (debut+fin)
				if($evt_tmp["date_debut"]==$evt_tmp["date_fin"])					$evt_height = $height_min_evt;
				elseif($T_evt_debut <= $T_jour_debut && $T_jour_fin <= $T_evt_fin)	$evt_height = $height_heures * 24;
				elseif($T_evt_debut < $T_jour_debut && $T_evt_fin <= $T_jour_fin)	$evt_height = $height_heures * (($T_evt_fin-$T_jour_debut)/3600);
				elseif($T_jour_debut <= $T_evt_debut && $T_jour_fin < $T_evt_fin)	$evt_height = $height_heures * (($T_jour_fin-$T_evt_debut)/3600);
				elseif($T_jour_debut <= $T_evt_debut && $T_evt_fin <= $T_jour_fin)	$evt_height = $height_heures * (($T_evt_fin-$T_evt_debut)/3600);
				// Hauteur inférieur à la taille minimum (& evenement périodique commencé avant la période affichée?)
				if($evt_height < $height_min_evt){
					$evt_height = ($evt_height<0)  ?  ($height_heures * (($T_evt_fin-$T_evt_debut)/3600))  :  $height_min_evt;
				}
				// Hauteur arrondie
				$evt_height = round($evt_height,1);

				////	TABLEAU JAVASCRIPT
				echo "<script type='text/javascript'>";
					echo "tab_evt[".$cptEvts."] = new Array();";
					echo "tab_evt[".$cptEvts."]['id_div_evt'] = '".$id_div_evt."';";
					echo "tab_evt[".$cptEvts."]['id_evenement'] = ".$evt_tmp["id_evenement"].";";
					echo "tab_evt[".$cptEvts."]['id_agenda'] = ".$id_agenda.";";
					echo "tab_evt[".$cptEvts."]['jour'] = '".$jour_tmp."';";
					echo "tab_evt[".$cptEvts."]['T_debut'] = ".$T_evt_debut.";";
					echo "tab_evt[".$cptEvts."]['T_fin'] = ".$T_evt_fin.";";
				echo "</script>";

				////	AFFICHAGE
				echo "<div id='".$id_div_evt."' class='block_evt' style='position:absolute;margin-top:".$evt_position_top."px;height:".$evt_height."px;".style_evt($agenda_tmp,$evt_tmp)."'>";
					require PATH_INC."element_menu_contextuel.inc.php";
					echo "<div class='div_evt_contenu' style='height:85%;overflow:hidden;' ".infobulle($infobulle)." >";
						echo "<b>".$temps_evt."</b> &nbsp; ".$evt_tmp["titre"].$evt_tmp["important"];
					echo "</div>";
				echo "</div>";
				$cptEvts ++;
			}
		}

		////	CORPS DE L'AGENDA (MATRICE DES JOURS / HEURES)
		////
		echo "<table id='".$id_agenda_bis."_tableau' class='table_nospace' cellpadding='0' cellspacing='0'>";
		for($heure_tmp=0; $heure_tmp < 24; $heure_tmp++)
		{
			////	IMPRESSION : ON AFFICHE PAS LES HEURES EN DEHORS DE LA PLAGE HORAIRE
			if(isset($_GET["printmode"]) && ($plage_horaire[0] > $heure_tmp ||  $heure_tmp > $plage_horaire[1]))	continue;
			////	STYLE DE LA LIGNE DE L'HEURE & AFFICHAGE A L'IMPRESSION (si demandé, on masque les heures qui ne sont pas dans la plage horaire)
			$style_ligne_heure =  (($plage_horaire[0]<=$heure_tmp && $heure_tmp<12) || ($heure_tmp>=14 && $heure_tmp<$plage_horaire[1]))  ?  "ligne_heure"  :  "ligne_heure_creuse";
			echo "<tr class='".$style_ligne_heure."' style='height:".$height_heures."px;'>";
				echo "<td class='cellule_heure_libelle'>".$heure_tmp.":00</td>";
				////	AFFICHE LA CELLULE DE L'HEURE  &  LES CELLULES DES CRENEAUX DE QUARTS D'HEURE (AJOUT D'EVENEMENT)
				////
				for($jour_tmp=0; $jour_tmp < $nb_jours_affiches; $jour_tmp++)
				{
					$id_agenda_bis_jour_heure = $id_agenda_bis."_jour".$jour_tmp."_heure".$heure_tmp;
					echo "<td id='".$id_agenda_bis_jour_heure."' class='cellule_heure'>";
						echo "<table class='table_nospace' cellpadding='0' cellspacing='0' style='width:100%;height:".($height_heures-2)."px;line-height:25%;'>"; //GARDER "line-height" POUR NE PAS FAIRE EXPLOSER LE TABLEAU EN HAUTEUR !!  /  "-2" -> différence de hauteur entre la cellule de l'heure et le tableau en question
						for($minutes_tmp=0; $minutes_tmp <=45; $minutes_tmp+=15)
						{
							// Debut et fin de la cellule "15mn" en temps UNIX
							$T_creneau_debut = $config["agenda_debut"] + ($jour_tmp*$jour_secondes) + ($heure_tmp*3600) + ($minutes_tmp*60);
							$T_creneau_fin = $T_creneau_debut + 900;//900sec=15mn
							// Style de la cellule
							if($T_creneau_fin < time())										$style_cellule = "cellule_heure_old";
							elseif($T_creneau_debut < time() && $T_creneau_fin > time())	$style_cellule = "cellule_heure_courante";
							else															$style_cellule = "";
							// Infobulle et lien pour ajouter un evenement
							$lien_proposer_affecter = "";
							if(!empty($agenda_proposer_affecter_evt))
							{
								if($agenda_proposer_affecter_evt=="proposer")	$lien_proposer_affecter .= "(".$trad["AGENDA_proposer"].")";
								$lien_proposer_affecter  = infobulle("<img src='".PATH_TPL."divers/plus.png' /> ".$trad["AGENDA_ajouter_evt_heure"]." ".$heure_tmp.$trad["separateur_horaire"].($minutes_tmp>0?$minutes_tmp:"")." ".$lien_proposer_affecter);
								$lien_proposer_affecter .= " onMouseMove=\"select_creneau_horaire(".$id_agenda.",".$jour_tmp.",".$heure_tmp.",".$minutes_tmp.",".$T_creneau_debut.",".$T_creneau_fin.");\" onMouseUp=\"popupLightbox('evenement_edit.php?id_agenda=".$id_agenda."&date=".$T_creneau_debut."&T_selection_debut='+T_selection_debut+'&T_selection_fin='+T_selection_fin);\"";
							}
							// Affichage de la cellule
							echo "<tr><td id='".$id_agenda_bis_jour_heure."_minute".$minutes_tmp."' class='cellule_heure_quart ".$style_cellule."' ".$lien_proposer_affecter.">&nbsp;</td></tr>";
						}
						echo "</table>";
					echo "</td>";
				}
			echo "</tr>";
		}
		echo "</table>";

	echo "</div>";
echo "</div>";