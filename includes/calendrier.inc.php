<?php
////	CALENDRIER DANS UNE IFRAME ?  (POUR SELECTIONNER UNE DATE)
////
if(!defined("ROOT_PATH")){
	require_once "../includes/global.inc.php";
	require_once "../includes/header.inc.php";
}


////	OPTIONS PAR DEFAUT
////
if(!isset($_REQUEST["date_affiche"]))	$_REQUEST["date_affiche"] = time();
if(isset($_REQUEST["date_selection"])){
	$_REQUEST["date_selection_debut"] = strtotime(strftime("%Y-%m-%d 00:00:00",$_REQUEST["date_selection"]));
	$_REQUEST["date_selection_fin"] = strtotime(strftime("%Y-%m-%d 23:59:59",$_REQUEST["date_selection"]));
}
$variables_get = "&".substr(variables_get("date_affiche"),1); // On enlève le "?" de début de variables
$selection_plusieurs_jours =  ((@$_REQUEST["date_selection_fin"]-@$_REQUEST["date_selection_debut"]) > 86400)  ?  1  :  0;


////	INITIALISATION DU TEMPS
////
$cal_annee = strftime("%Y",$_REQUEST["date_affiche"]);
$cal_mois = strftime("%m",$_REQUEST["date_affiche"]);
$nb_jours_mois = date("t",$_REQUEST["date_affiche"]);
$premier_jour_mois = strtotime($cal_annee."-".$cal_mois."-01");
////	MOIS + ANNEE  PRECEDANTE / SUIVANTE
$mois_prec_unix = $premier_jour_mois - (86400*15);
$mois_suiv_unix = $premier_jour_mois + (86400*40);


////	FOND D'ECRAN SI BESOIN
////
if(IS_MAIN_PAGE==false){
	$background = (@$_SESSION["agora"]["skin"]=="blanc")  ?  "background-image:url(".PATH_TPL."divers/background.jpg);"  :  "background-image:url(".PATH_TPL."divers/background_noir.jpg);";
	echo "<style type='text/css'>  body { ".$background." background-position:top left; }  </style>";
}
?>


<script type="text/javascript">
////	ACTION LORSQU'ON CLIQUE SUR UNE DATE
////
function calendrier_select_date(date_unix, date_ymd, id_jour)
{
<?php
	////	REDIRECTION
	if(@$_REQUEST["champ_modif"]=="")	{ echo "redir('".php_self()."?date_affiche='+date_unix);"; }
	////	REMPLISSAGE D'UN CHAMP
	else
	{
		// Réinitialise les dates
		echo "for(var i=1; i <= ".$nb_jours_mois."; i++)	{  if(element('cal_jour_'+i).className!='cal_jour_du_jour')  element('cal_jour_'+i).className=null;  }";
		// Change la class du jour sélectionné  &  Modifie la valeur du champ
		echo "if(element(id_jour).className!='cal_jour_du_jour')	element(id_jour).className='cal_jour_selection';";
		echo "window.parent.set_value('".@$_REQUEST["champ_modif"]."', date_ymd);";
		// Fin avant le début -> erreur  /  Evenement edit -> controle ajax de créneaux horaires  /  Tache edit -> masque les heures / minutes ?
		if(preg_match("/date_debut|date_fin/i", @$_REQUEST["champ_modif"])){
			echo "window.parent.modif_dates_debutfin('".$_REQUEST["champ_modif"]."','".addslashes($trad["modif_dates_debutfin"])."');";
			echo "if(typeof window.parent.controle_creneaux_horaires=='function')	window.parent.controle_creneaux_horaires();";
			echo "if(typeof window.parent.heure_minute_debutfin=='function')		window.parent.heure_minute_debutfin();";
		}
		// Ferme l'iframe
		echo "window.parent.afficher('calendrier_".@$_REQUEST["champ_modif"]."',false);";
	}
?>
}


////	REINITIALISER DATE
////
function reinitialiser_date()
{
	window.parent.set_value('<?php echo @$_REQUEST["champ_modif"]; ?>','');
	window.parent.afficher('calendrier_<?php echo @$_REQUEST["champ_modif"]; ?>',false);
	if(typeof window.parent.heure_minute_debutfin=='function')	window.parent.heure_minute_debutfin();
	<?php  if(preg_match("/date_debut|date_fin/i",@$_REQUEST["champ_modif"]))  echo "window.parent.modif_dates_debutfin('".@$_REQUEST["champ_modif"]."','".addslashes($trad["modif_dates_debutfin"])."');";  ?>
}
</script>


<style>
.cal_tableau			{ width:95%; text-align:center; }
.cal_jour_normal		{ font-weight:normal; }
.cal_jour_normal:hover	{ font-weight:bold; font-style:italic; }
.cal_jour_du_jour		{ font-weight:bold; color:#f33; }
.cal_jour_selection		{ font-weight:bold; border:solid 1px #999; }	/* sélect. d'1 jour */
.cal_jour_selection2	{ font-weight:bold; background-color:<?php echo (@$_SESSION["agora"]["skin"]=="blanc") ? "#ddd" : "#333"; ?>; }	/* sélect. de plusieurs jours */
.cal_options			{ cursor:pointer; font-size:11px; margin:3px; margin-bottom:-5px; font-style:italic; font-weight:bold; }
</style>


<div onMouseDown="return false;" style="margin:2px;text-align:center;">
	<table class="cal_tableau table_nospace" cellpadding='0' cellspacing='0' style="font-weight:bold;margin:5px;"><tr>
		<td style="font-size:13px;text-lign:left">
			<?php
			////	MOIS PRECEDANT
			echo "<a href=\"javascript:redir('".php_self()."?date_affiche=".$mois_prec_unix.$variables_get."');\" title=\"".$trad["mois_precedant"]."\"><img src=\"".PATH_TPL."divers/precedent.png\" height='11px' /></a>";
			?>
		</td>
		<td style="font-size:11px">
			<?php
			////	MOIS (avec menu d'accès direct)
			echo "<div class='menu_context' id='calendrier_mois'><div style='float:left;'>";
			for($cpt_mois=1; $cpt_mois<=12; $cpt_mois++){
				$mois_unix = strtotime($cal_annee."-".$cpt_mois."-01");
				if($cpt_mois==7)	echo "</div><div style='float:left;margin-left:10px;'>"; // affiche 2ème colonne
				echo "<a href=\"javascript:redir('".php_self()."?date_affiche=".$mois_unix.$variables_get."');\" ".($cpt_mois==$cal_mois?"class='lien_select'":"").">".formatime("%B",$mois_unix)."</a><br>";
			}
			echo "</div></div>";
			echo "<span class='lien' id='icone_calendrier_mois'>".ucfirst(formatime("%B",$_REQUEST["date_affiche"]))." <img src=\"".PATH_TPL."divers/derouler.png\" style='height:8px;' /></span>";
			echo "<script type='text/javascript'> menu_contextuel('calendrier_mois'); </script> &nbsp;";

			////	ANNEE (avec menu d'accès direct)
			echo "<div class='menu_context' id='calendrier_annee'>";
			for($cpt_annee=($cal_annee-3); $cpt_annee<=($cal_annee+3); $cpt_annee++){
				$annee_unix = strtotime($cpt_annee."-".$cal_mois."-01");
				echo "<a href=\"javascript:redir('".php_self()."?date_affiche=".$annee_unix.$variables_get."');\" ".($cpt_annee==$cal_annee?"class='lien_select'":"").">".strftime("%Y",$annee_unix)."</a><br>";
			}
			echo "</div>";
			echo "<span class='lien' id='icone_calendrier_annee'>".ucfirst(strftime("%Y",$_REQUEST["date_affiche"]))." <img src=\"".PATH_TPL."divers/derouler.png\" style='height:8px;' /></span>";
			echo "<script type='text/javascript'> menu_contextuel('calendrier_annee'); </script> &nbsp;";
			////	AFFICHER AUJOURD'HUI
			echo "<img src=\"".PATH_TPL."divers/point_blanc.png\" style='height:12px;cursor:pointer;vertical-align:middle;' onClick=\"redir('".php_self()."?date_affiche=".time().$variables_get."');\" ".infobulle($trad["aff_aujourdhui"])." />";
			?>
		</td>
		<td style="font-size:13px;text-lign:right">
			<?php
			////	MOIS SUIVANT
			echo "<a href=\"javascript:redir('".php_self()."?date_affiche=".$mois_suiv_unix.$variables_get."');\" title=\"".$trad["mois_suivant"]."\"><img src=\"".PATH_TPL."divers/suivant.png\" height='11px' /></a>";
			?>
		</td>
	</tr></table>


	<table class="cal_tableau table_nospace" cellpadding='0' cellspacing='0' style="font-size:11px;">
		<tr>
			<?php
			////	JOURS DE LA SEMAINE
			for($j=1; $j<=7; $j++)  { echo "<td style='font-style:italic;padding-top:5px;padding-bottom:5px;'>".substr($trad["jour_".$j],0,2)."</td>"; }
			?>
		</tr>
		<?php
		////	PREMIERES CELLULES VIDES
		$jour_semaine_tmp = str_replace("0","7", strftime("%w",strtotime($cal_annee."-".$cal_mois."-01")));
		for($cpt_jour=1; $cpt_jour<$jour_semaine_tmp; $cpt_jour++)	{ echo "<td>&nbsp;</td>"; }

		////	CHAQUE JOUR DU MOIS
		for($cpt_jour=1; $cpt_jour<=$nb_jours_mois; $cpt_jour++)
		{
			// DATE & TEMPS
			$jour_ymd = $cal_annee."-".$cal_mois."-".num2carac($cpt_jour);
			$jour_semaine_tmp = str_replace("0","7", strftime("%w",strtotime($jour_ymd)));
			// Aujourd'hui / jour normal / jour dans la periode de selection (..sur un ou plusieurs jours?)
			$jour_selection =  (@$_REQUEST["date_selection_debut"] <= strtotime($jour_ymd." 00:00:00") && strtotime($jour_ymd." 23:59:59") <= @$_REQUEST["date_selection_fin"])  ?  "1"  :   "0";
			if($jour_ymd==strftime("%Y-%m-%d",time()))	{ $class_cal_jour = "cal_jour_du_jour"; }
			elseif($jour_selection!="1")				{ $class_cal_jour = "cal_jour_normal"; }
			elseif($selection_plusieurs_jours==1)		{ $class_cal_jour = "cal_jour_selection2"; }
			else										{ $class_cal_jour = "cal_jour_selection"; }
			// AFFICHAGE DU JOUR
			echo "<td class='".$class_cal_jour."' style='width:14%;height:18px;vertical-align:middle;cursor:pointer;' id='cal_jour_".$cpt_jour."' onClick=\"calendrier_select_date('".strtotime($jour_ymd)."','".$jour_ymd."',this.id);\"> ".$cpt_jour." </td>";
			// Fin de ligne + Retour ligne?
			if($jour_semaine_tmp==7)	{   echo "</tr>";   if($cpt_jour<$nb_jours_mois) echo "<tr>";   }
		}

		////	DERNIERES CELLULES VIDES (sauf si le dernier jour est un dimanche...)
		$jour_semaine_tmp = strftime("%w", strtotime($cal_annee."-".$cal_mois."-".$nb_jours_mois));
		if($jour_semaine_tmp>0) {
			for($cpt_jour=$jour_semaine_tmp+1; $cpt_jour<=7; $cpt_jour++)	{ echo "<td>&nbsp;</td>"; }
		}
		?>
	</table>

	<?php
	////	REINITIALISER LA DATE
	if(!isset($_REQUEST["pas_reinit"]) && isset($_REQUEST["champ_modif"]))
		echo "<div onClick=\"reinitialiser_date();\" class='cal_options' style='float:left;'><img src=\"".PATH_TPL."divers/supprimer.png\" style='height:13px;' /> ".$trad["reinitialiser"]."</div>";
	////	FERMER
	if(isset($_REQUEST["champ_modif"]))
		echo "<div onClick=\"window.parent.afficher('calendrier_".@$_REQUEST["champ_modif"]."',false);\" class='cal_options' style='float:right;'>".$trad["fermer"]." <img src=\"".PATH_TPL."divers/supprimer.png\" style='height:13px;' /></div>";
	?>
</div>