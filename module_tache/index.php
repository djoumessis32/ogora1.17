<?php
////	INIT
define("IS_MAIN_PAGE",true);
require "commun.inc.php";
require PATH_INC."header_menu.inc.php";
init_id_dossier();
elements_width_height_type_affichage("medium","70px","liste");
$droit_acces_dossier = droit_acces_controler($objet["tache_dossier"],$_GET["id_dossier"],1);
?>


<style>
.gant_contenu				{ position:absolute; <?php if(preg_match("/MSIE 7/i",$_SERVER['HTTP_USER_AGENT'])==false) echo "overflow:auto;"; ?> padding:3px; border:outset 1px #fff; }
.gantt_line					{ opacity:0.95; filter:alpha(opacity=95); white-space:nowrap; }
.gantt_line:hover			{ opacity:1; filter:alpha(opacity=100); }
.gantt_cell_empty			{ cursor:pointer; }
.gantt_cell_full			{ cursor:pointer; background-image:url('<?php echo PATH_TPL; ?>divers/barre_verte.png'); }
.gantt_cell_full_courant	{ cursor:pointer; background-image:url('<?php echo PATH_TPL; ?>divers/barre_jaune.png'); }
.gantt_cell_full_retard		{ cursor:pointer; background-image:url('<?php echo PATH_TPL; ?>divers/barre_rouge.png'); }
.gantt_cell_full_after		{ cursor:pointer; background-image:url('<?php echo PATH_TPL; ?>divers/barre_background.png'); }
</style>


<table id="contenu_principal_table"><tr>
	<td id="menu_gauche_block_td">
		<div id="menu_gauche_block_flottant">
			<div class="menu_gauche_block content">
				<?php
				////	MENU D'ARBORESCENCE
				$cfg_menu_arbo = array("objet"=>$objet["tache_dossier"], "id_objet"=>$_GET["id_dossier"], "ajouter_dossier"=>true, "droit_acces_dossier"=>$droit_acces_dossier);
				require_once PATH_INC."menu_arborescence.inc.php";
				?>
			</div>
			<div class="menu_gauche_block content">
				<?php
				////	AJOUTER TACHE
				if($droit_acces_dossier>=1.5)	{echo "<div class='menu_gauche_ligne lien' onclick=\"popupLightbox('tache_edit.php?id_dossier=".$_GET["id_dossier"]."');\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/ajouter.png\" /></div><div class='menu_gauche_txt'>".$trad["TACHE_ajouter_tache"]."</div></div><hr />";}
				////	MENU ELEMENTS
				$cfg_menu_elements = array("objet"=>$objet["tache"], "objet_dossier"=>$objet["tache_dossier"], "id_objet_dossier"=>$_GET["id_dossier"], "droit_acces_dossier"=>$droit_acces_dossier);
				require PATH_INC."elements_menu_selection.inc.php";
				////	FILTRE PAR PRIORITE  &  MENU D'AFFICHAGE  &  MENU DE TRI  &  CONTENU DU DOSSIER
				echo menu_tache_filtre_priorite();
				echo menu_type_affichage();
				echo menu_tri($objet["tache"]["tri"]);
				echo contenu_dossier($objet["tache_dossier"],$_GET["id_dossier"]);
				?>
			</div>
		</div>
	</td>
	<td>
		<?php
		////	MENU CHEMIN + OBJETS_DOSSIERS
		////
		echo menu_chemin($objet["tache_dossier"], $_GET["id_dossier"]);
		$cfg_dossiers = array("objet"=>$objet["tache_dossier"], "id_objet"=>$_GET["id_dossier"], "fonction_affichage_liste"=>"tache_synthese_dossier");
		require_once PATH_INC."dossiers.inc.php";

		////	LISTE DES TACHES  (+ INIT. DIAGRAMME GANTT)
		////
		$taches_gantt = array();
		$gantt_time_debut = $gantt_time_fin = "";
		$liste_taches = db_tableau("SELECT * FROM gt_tache WHERE id_dossier='".intval($_GET["id_dossier"])."' ".sql_affichage($objet["tache"],$_GET["id_dossier"])." ".sql_tache_filtre_priorite()." ".tri_sql($objet["tache"]["tri"]));
		foreach($liste_taches as $tache_tmp)
		{
			////	INIT
			$tache_tmp["titre"] = ($tache_tmp["titre"]!="") ? text_reduit($tache_tmp["titre"]) : text_reduit(strip_tags($tache_tmp["description"]));
			$tache_tmp["popup"] = " onclick=\"popupLightbox('tache.php?id_tache=".$tache_tmp["id_tache"]."');stopPropager(event);\"";
			if($tache_tmp["priorite"]!="")	{$tache_tmp["priorite"] = "<img src=\"".PATH_TPL."module_tache/priorite".$tache_tmp["priorite"].".png\" ".infobulle($trad["TACHE_priorite"]." ".@$trad["TACHE_priorite".$tache_tmp["priorite"]])." /> &nbsp; ";}
			////	RESPONSABLES
			$responsables_infobulle = $responsables_text = "";
			$responsables = db_tableau("SELECT T1.* FROM gt_utilisateur T1, gt_tache_responsable T2 WHERE T1.id_utilisateur=T2.id_utilisateur AND T2.id_tache='".$tache_tmp["id_tache"]."'");
			if(count($responsables)>0)
			{
				foreach($responsables as $user_tmp)		{ $responsables_infobulle .= "<br>".$user_tmp["prenom"]." ".$user_tmp["nom"];   $responsables_text .= $user_tmp["prenom"].", "; }
				$responsables_infobulle = infobulle($trad["TACHE_responsables"]." : ".$responsables_infobulle);
				$responsables_text = text_reduit(substr($responsables_text,0,-2), 60);
			}
			////	INFOS / MODIF / SUPPR
			$cfg_menu_elem = array("objet"=>$objet["tache"], "objet_infos"=>$tache_tmp);
			$tache_tmp["droit_acces"] = droit_acces($objet["tache"],$tache_tmp);//ne jamais prendre les droits du dossier!
			if($tache_tmp["droit_acces"]>=2){
				$cfg_menu_elem["modif"] = "tache_edit.php?id_tache=".$tache_tmp["id_tache"];
				$cfg_menu_elem["deplacer"] = PATH_DIVERS."deplacer.php?module_path=".MODULE_PATH."&type_objet_dossier=tache_dossier&id_dossier_parent=".$_GET["id_dossier"]."&SelectedElems[tache]=".$tache_tmp["id_tache"];
				$cfg_menu_elem["suppr"] = "elements_suppr.php?id_tache=".$tache_tmp["id_tache"]."&id_dossier_retour=".$_GET["id_dossier"];
			}
			////	AJOUTE LA TACHE AU DIAGRAMME DE GANTT ?
			if($tache_tmp["date_debut"]!="" || $tache_tmp["date_fin"]!="")
			{
				$date_debut_tmp	= ($tache_tmp["date_debut"]!="")  ?  $tache_tmp["date_debut"]  :  $tache_tmp["date_fin"];
				$date_fin_tmp	= ($tache_tmp["date_fin"]!="")  ?  $tache_tmp["date_fin"]  :  $tache_tmp["date_debut"];
				$tache_tmp["date_debut_time"] = strtotime(strftime("%Y-%m-%d 00:00",strtotime($date_debut_tmp)));
				$tache_tmp["date_fin_time"] = strtotime(strftime("%Y-%m-%d 23:59",strtotime($date_fin_tmp)));
				$tache_tmp["modif"] = @$cfg_menu_elem["modif"];
				$taches_gantt[] = $tache_tmp;
				if($gantt_time_debut=="" || $tache_tmp["date_debut_time"]<$gantt_time_debut)	{$gantt_time_debut = $tache_tmp["date_debut_time"];}
				if($gantt_time_fin=="" || $tache_tmp["date_fin_time"]>$gantt_time_fin)			{$gantt_time_fin = $tache_tmp["date_fin_time"];}
			}

			////	DIV SELECTIONNABLE + OPTIONS
			$cfg_menu_elem["id_div_element"] = div_element($objet["tache"], $tache_tmp["id_tache"]);
			require PATH_INC."element_menu_contextuel.inc.php";
				////	CONTENU
				echo "<div class='div_elem_contenu'>";
				////	AFFICHAGE BLOCK
				if($_REQUEST["type_affichage"]=="bloc")
				{
					////	RESPONSABLES  +  AVANCEMENT  +  PRIORITE  +  DATE DE DEBUT/FIN
					echo "<div style='position:absolute;right:0px;bottom:0px;font-weight:bold;cursor:help;'>";
					 	if($tache_tmp["date_debut"]!="" || $tache_tmp["date_fin"]!="")	{echo "<img src=\"".PATH_TPL."module_tache/date.png\" ".infobulle(tache_debut_fin($tache_tmp,true))." /> &nbsp;";}
						if($tache_tmp["avancement"]>0)									{echo "<img src=\"".PATH_TPL."module_tache/avancement.png\" ".infobulle($trad["TACHE_avancement"]." : ".$tache_tmp["avancement"]."%")." /> &nbsp;";}
						if($tache_tmp["charge_jour_homme"]>0)							{echo "<img src=\"".PATH_TPL."module_tache/charge_jour_homme.png\" ".infobulle($trad["TACHE_charge_jour_homme"]." : ".$tache_tmp["charge_jour_homme"])." /> &nbsp;";}
						if($tache_tmp["budget_disponible"]>0)							{echo "<img src=\"".PATH_TPL."module_tache/budget_disponible.png\" ".infobulle($trad["TACHE_budget_disponible"]." : ".$tache_tmp["budget_disponible"])." /> &nbsp;";}
						if($tache_tmp["budget_engage"]>0)								{echo "<img src=\"".PATH_TPL."module_tache/budget_engage.png\" ".infobulle($trad["TACHE_budget_engage"]." : ".$tache_tmp["budget_engage"])." /> &nbsp;";}
						if($responsables_infobulle!="")									{echo "<img src=\"".PATH_TPL."module_utilisateurs/utilisateurs_small.png\" ".$responsables_infobulle." /> &nbsp;";}
					echo "</div>";
					////	TITRE + DESCRIPTION
					echo "<table class='div_elem_table'><tr><td class='div_elem_infos'><div class='lien' ".$tache_tmp["popup"].">".$tache_tmp["priorite"]." ".$tache_tmp["titre"]."</div></td></tr></table>";
				}
				////	AFFICHAGE LISTE
				else
				{
					echo "<table class='div_elem_table'><tr>";
						echo "<td class='div_elem_td'><span class='lien' ".$tache_tmp["popup"].">".$tache_tmp["priorite"]." ".$tache_tmp["titre"]."</span></td>";
						echo "<td class='div_elem_td div_elem_td_right'>";
							if($responsables_infobulle!="")	{echo "<span ".$responsables_infobulle."><img src=\"".PATH_TPL."module_utilisateurs/utilisateurs_small.png\" /> ".$responsables_text."</span><img src=\"".PATH_TPL."divers/separateur.gif\" />";}
							echo tache_budget($tache_tmp);
							echo tache_barre_avancement_charge($tache_tmp);
							echo tache_debut_fin($tache_tmp);
							echo $cfg_menu_elem["auteur_tmp"]." <img src=\"".PATH_TPL."divers/separateur.gif\" /> ".temps($tache_tmp["date_crea"],"date")."</td>";
						echo "</td>";
					echo "</tr></table>";
				}
				echo "</div>";
			echo "</div>";
		}
		////	AUCUNE TACHE
		if(@$cpt_div_element<1)	{echo "<div class='div_elem_aucun'>".$trad["TACHE_aucune_tache"]."</div>";}


		////	DIAGRAMME DE GANTT SIMPLIFIE  (S'IL Y A PLUSIEURS TACHES SUR UNE PERIODE)
		////
		if(count($taches_gantt)>=1)
		{
			////	Jours de la période  (time début, time fin, libellé..)
			$nb_jour =  round(($gantt_time_fin - $gantt_time_debut) / 86400);
			if($nb_jour < 60)	{$nb_jour = 61;}
			$tab_jours = array();
			for($j=0; $j<$nb_jour; $j++)
			{
				$jour_time_midi = $gantt_time_debut + ($j*86400) + 43200; // 12h00 => Astuce pour contrer le probleme de décalage heures été/hiver
				$jour_time = strtotime(strftime("%Y-%m-%d",$jour_time_midi));
				$jour_mois = intval(strftime("%d",$jour_time));
				$jour_semaine = strftime("%w",$jour_time);
				$jour_libelle = substr(strftime("%a",$jour_time),0,1).$jour_mois; // l5|m6|m7
				if($jour_mois<10)	{$jour_libelle .= "&nbsp;";}
				$separations_timeline = ($jour_semaine==1 || $jour_mois==1)  ?  "border-left:#999 dotted 1px;"  :  "";
				$tab_jours[] = array("jour_time"=>$jour_time, "jour_libelle"=>$jour_libelle, "jour_mois"=>$jour_mois, "jour_semaine"=>$jour_semaine, "separations_timeline"=>$separations_timeline);
			}
			////	Entête des mois & des jours
			$gantt_entete_mois = $gantt_entete_jours = "";
			foreach($tab_jours as $jour_tmp)
			{
				// Mois
				if($gantt_entete_mois=="" || $jour_tmp["jour_mois"]==1) {
					$colspan = date("t",$jour_tmp["jour_time"]) - strftime("%d",$jour_tmp["jour_time"]) + 1;  // nombre de jours dans le mois - jour du mois
					$libelle_mois = (strftime("%Y",$jour_tmp["jour_time"])==strftime("%Y",time()))  ?  majuscule(formatime("%B",$jour_tmp["jour_time"]))  :  majuscule(formatime("%B %Y",$jour_tmp["jour_time"]));
					$gantt_entete_mois .= "<td colspan=\"".$colspan."\" style='".$jour_tmp["separations_timeline"]."'><b>".$libelle_mois."</b></td>";
				}
				// Jours (aujourd'hui / weekend / normal)
				if(strftime("%d%m%y",$jour_tmp["jour_time"])==strftime("%d%m%y",time()))	{$style_j = "font-weight:bold;color:#f00;";}
				elseif($jour_tmp["jour_semaine"]==0 || $jour_tmp["jour_semaine"]==6)		{$style_j = "font-weight:normal;".STYLE_FONT_COLOR_RETRAIT;}
				else																		{$style_j = "font-weight:normal;";}
				$gantt_entete_jours .= "<td style='".$style_j.$jour_tmp["separations_timeline"]."'>".$jour_tmp["jour_libelle"]."&nbsp;</td>";
			}
			?>
			<hr style="clear:both;visibility:hidden;height:0px;margin:15px;" />
			<div id="conteneur_gantt" style="width:99.5%;">
				<div id="contenu_gantt" class="div_elem_deselect gant_contenu">
					<table cellpadding="2px" cellspacing="0px">
						<?php
						echo "<tr><td>&nbsp;</td>".$gantt_entete_mois."</tr>";
						echo "<tr><td>&nbsp;</td>".$gantt_entete_jours."</tr>";
						////	Affichage de chaque tâche
						$cpt_tache = 1;
						foreach(array_multi_sort($taches_gantt,"date_debut") as $tache_tmp)
						{
							// On affiche pas la tache s'il y en a plus de 20, et que l'affichage total n'a pas été demandé
							if($cpt_tache>20 && !isset($_GET["afficher_tout_gantt"]))	{ $bouton_tout_gantt=true;  continue; }
							// Init
							$cpt_tache++;
							$txt_infobulle = $tache_tmp["titre"]."<br>".tache_debut_fin($tache_tmp,true);
							if(tache_retardee($tache_tmp)==true)		{ $class_cell_tache = "gantt_cell_full_retard";		$txt_infobulle .= "<br><br><img src=\"".PATH_TPL."divers/important.png\" /> &nbsp; ".$trad["TACHE_avancement_retard"]." (".$tache_tmp["avancement"]." %)"; }
							elseif($tache_tmp["date_fin_time"]>time())	{ $class_cell_tache = "gantt_cell_full_courant"; }
							else										{ $class_cell_tache = "gantt_cell_full"; }
							if($tache_tmp["modif"]!="")		{$tache_tmp["modif"] = "<img src=\"".PATH_TPL."divers/crayon.png\" style='height:14px;cursor:pointer;' onclick=\"popupLightbox('".$tache_tmp["modif"]."');\" />";}
							// Affichage des cellules du jour
							echo "<tr class='gantt_line' ".infobulle($txt_infobulle).">";
							echo "<td><nobr><b>".text_reduit($tache_tmp["titre"],50)." ".$tache_tmp["modif"]." &nbsp; </b></nobr></td>";
							foreach($tab_jours as $cle_tmp => $jour_tmp)
							{
								// style de la cellule du jour
								if($jour_tmp["jour_time"]<$tache_tmp["date_debut_time"] || $tache_tmp["date_fin_time"]<$jour_tmp["jour_time"])	{ $style_tache_cell = "class='gantt_cell_empty'"; }
								elseif($jour_tmp["jour_time"]<time())																			{ $style_tache_cell = "class='".$class_cell_tache."'"; }
								else																											{ $style_tache_cell = "class='gantt_cell_full_after'"; }
								// Contenu de la cellule : avancement s'il y a
								if($tache_tmp["avancement"]>0 && strftime("%d%m%y",$jour_tmp["jour_time"])==strftime("%d%m%y",$tache_tmp["date_debut_time"]))
									{$jour_tmp["details"] = "<span style='position:absolute;padding:0px;font-size:9px;font-weight:normal;line-height:16px;color:#000;' >".$tache_tmp["avancement"]." %</span>";}
								// Affichage de la cellule
								echo "<td ".$style_tache_cell." onclick=\"popupLightbox('tache.php?id_tache=".$tache_tmp["id_tache"]."');\" style='".$jour_tmp["separations_timeline"]."'>".@$jour_tmp["details"]."<img src=\"".PATH_TPL."module_tache/timeline_cell.gif\" /></td>";
							}
							echo "</tr>";
						}
						?>
					</table>
					<?php
					////	AFFICHER TOUT LE GANTT ?
					if(isset($bouton_tout_gantt) && $bouton_tout_gantt==true){
						echo "<div id='bouton_tout_gantt' class='lien_select' style='text-align:center;margin:5px;margin-top:10px;' onClick=\"redir('".php_self().variables_get()."&afficher_tout_gantt=1');\">".$trad["TACHE_afficher_tout_gantt"]." <img src=\"".PATH_TPL."divers/derouler.png\" /></div>";
						echo "<script>  $(window).load(function(){  $('#bouton_tout_gantt').effect('pulsate',{ times:10 },10000);  });  </script>";
					}
					?>
				</div>
			</div>
			<script type="text/javascript">
			////	Ajustements hauteur / largeur
			$(function(){
				element("contenu_gantt").style.width = (element("conteneur_gantt").offsetWidth - 6) + "px";
				element("conteneur_gantt").style.height = (element("contenu_gantt").offsetHeight + 6) + "px";
			});
			</script>
			<?php
		}
		?>
	</td>
</tr></table>


<?php require PATH_INC."footer.inc.php"; ?>