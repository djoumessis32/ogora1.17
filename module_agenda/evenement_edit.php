<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";
$nb_period_date_exception = 10;


////	INFOS & DROIT D'ACCES
////
if(@$_REQUEST["id_evenement"]<1){
	$evt_tmp = array("droit_acces"=>3, "visibilite_contenu"=>"public", "date_debut"=>@$_GET["date"], "date_fin"=>@$_GET["date"]);
	if(@$_GET["T_selection_debut"]>0 && @$_GET["T_selection_fin"]>0){
		$evt_tmp["date_debut"] = $_GET["T_selection_debut"];
		$evt_tmp["date_fin"] = $_GET["T_selection_fin"];
	}
}
else {
	$evt_tmp = objet_infos($objet["evenement"],$_REQUEST["id_evenement"]);
	$evt_tmp["date_debut"]	= strtotime($evt_tmp["date_debut"]);
	$evt_tmp["date_fin"]	= strtotime($evt_tmp["date_fin"]);
	$evt_tmp["droit_acces"]	= droit_acces($objet["evenement"],$_REQUEST["id_evenement"],false);
}
if($evt_tmp["droit_acces"] < 2)   exit();


////	VALIDATION DU FORMULAIRE
////
if(isset($_POST["id_evenement"]))
{
	////	INITIALISATION
	$date_debut = $_POST["date_debut"]." ".$_POST["heure_debut"].":".$_POST["minute_debut"];
	$date_fin = $_POST["date_fin"]." ".$_POST["heure_fin"].":".$_POST["minute_fin"];
	$tab_id_user_notif = array();

	////	MODIF / AJOUT
	if($evt_tmp["droit_acces"]==3)
	{
		// VISIBILITE
		if(@$_POST["visibilite"]=="")	$_POST["visibilite"] = "public";
		// PERIODICITE : jour / jour du mois / mois / année / AUCUNE
		if(@$_POST["periodicite_type"]=="")					$periodicite_valeurs = $_POST["period_date_fin"] = $_POST["period_date_exception"] = "";
		elseif($_POST["periodicite_type"]=="jour_semaine")	$periodicite_valeurs = trim(implode(",",$_POST["period_jour_semaine"]),",");
		elseif($_POST["periodicite_type"]=="jour_mois")		$periodicite_valeurs = trim(implode(",",$_POST["period_jour_mois"]),",");
		elseif($_POST["periodicite_type"]=="mois")			$periodicite_valeurs = trim(implode(",",$_POST["period_mois"]),",");
		elseif($_POST["periodicite_type"]=="annee")			$periodicite_valeurs = 1;
		// MAJ / CREATION D'EVENEMENT
		$corps_sql = " titre=".db_format($_POST["titre"]).", description=".db_format($_POST["description"],"editeur").", date_debut='".$date_debut."', date_fin='".$date_fin."', id_categorie=".db_format(@$_POST["id_categorie"]).", important=".db_format(@$_POST["important"],"bool").", visibilite_contenu=".db_format($_POST["visibilite"]).", periodicite_type=".db_format(@$_POST["periodicite_type"]).", periodicite_valeurs=".db_format(@$periodicite_valeurs).", period_date_fin=".db_format(@$_POST["period_date_fin"]).", period_date_exception=".db_format(tab2text(@$_POST["period_date_exception"]));
		if($_POST["id_evenement"]>0){
			db_query("UPDATE gt_agenda_evenement SET ".$corps_sql." WHERE id_evenement=".db_format($_POST["id_evenement"]));
			add_logs("modif", $objet["evenement"], $_POST["id_evenement"]);
		}
		else{
			db_query("INSERT INTO gt_agenda_evenement SET date_crea=".db_date_now().", id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."', invite=".db_format(@$_POST["invite"]).", ".$corps_sql);
			$_POST["id_evenement"] = db_last_id();
			add_logs("ajout", $objet["evenement"], $_POST["id_evenement"]);
		}
		// INVITE : MESSAGE CONFIRMATION
		if(@$_POST["invite"]!="")	alert($trad["EDIT_OBJET_demande_a_confirmer"]);
	}

	////	AFFECTATION AUX AGENDAS
	// Ré-initialise les affectations aux agendas (uniquement celles que l'on peut modifier)
	foreach(@explode("@",$_POST["agenda_affectations_init"]) as $id_agenda)	 { db_query("DELETE FROM gt_agenda_jointure_evenement WHERE id_evenement=".db_format($_POST["id_evenement"])." AND id_agenda=".db_format($id_agenda)); }
	// Ajoute les propositions & affectations
	$corps_requete_affectations = "INSERT INTO gt_agenda_jointure_evenement SET id_evenement='".intval($_POST["id_evenement"])."', ";
	if(isset($_POST["agenda_propositions"]))
	{
		foreach($_POST["agenda_propositions"] as $id_agenda){
			db_query($corps_requete_affectations." id_agenda=".db_format($id_agenda).", confirme='0'");
			$agenda_tmp = $AGENDAS_AFFECTATIONS[$id_agenda];
			if($agenda_tmp["type"]=="utilisateur")	$tab_id_user_notif[] = $agenda_tmp["id_utilisateur"];
		}
	}
	if(isset($_POST["agenda_affectations"]))
	{
		foreach($_POST["agenda_affectations"] as $id_agenda){
			db_query($corps_requete_affectations." id_agenda=".db_format($id_agenda).", confirme='1'");
			$agenda_tmp = $AGENDAS_AFFECTATIONS[$id_agenda];
			if($agenda_tmp["type"]=="utilisateur")	$tab_id_user_notif[] = $agenda_tmp["id_utilisateur"];
		}
	}

	////	AJOUTER FICHIERS JOINTS
	ajouter_fichiers_joint($objet["evenement"],$_POST["id_evenement"]);

	////	ENVOI DE NOTIFICATION PAR MAIL
	if(isset($_POST["notification"]) && $evt_tmp["droit_acces"]==3)
	{
		// Fichier .Ical (temporaire)
		$evt_ical = objet_infos($objet["evenement"],$_POST["id_evenement"]);
		$nom_fichier = suppr_carac_spe($evt_ical["titre"],"normale").".ics";
		$fichier_tmp = PATH_TMP.uniqid(mt_rand()).$nom_fichier;
		$fp = fopen($fichier_tmp, "w");
		fwrite($fp, fichier_ical(array($evt_ical)));
		fclose($fp);
		$_FILES[] = array("error"=>0, "type"=>"text/Calendar", "name"=>$nom_fichier, "tmp_name"=>$fichier_tmp);
		// Destinataires + titre + description
		$tab_id_user_notif = (isset($_POST["notif_destinataires"]) && count($_POST["notif_destinataires"])>0)  ?  $_POST["notif_destinataires"]  :  $tab_id_user_notif;
		$objet_mail = $trad["AGENDA_mail_nouvel_evenement_cree"]." ".$_SESSION["user"]["nom"]." ".$_SESSION["user"]["prenom"];
		$contenu_mail = $_POST["titre"]." &nbsp; ".temps($date_debut,"normal",$date_fin);
		if($_POST["description"]!="")	{$contenu_mail .= "<br><br>".$_POST["description"];}
		// envoi du mail et supprime le fichier .Ical
		envoi_mail($tab_id_user_notif, $objet_mail, texteNotification($contenu_mail), array("notif"=>true,"envoi_fichiers"=>true));
		unlink($fichier_tmp);
	}

	////	FERMETURE
	reload_close();
}
?>


<script type="text/javascript">
////    On redimensionne
resizePopupLightbox(800,650);

////	Inversion la sélection d'agendas
function inverser_selection()
{
	tab_affectations = document.getElementsByName("agenda_affectations[]");
	for(i=0; i < tab_affectations.length; i++){
		id_agenda = tab_affectations[i].id.replace("box_","");
		checkbox_text("txt_"+id_agenda);
	}
	// Controle des créneaux horaires occupés
	controle_creneaux_horaires();
}

////	Afficher le menu des périodicités
function afficher_periodicite()
{
	// Init
	periodicite_type = get_value('periodicite_type');

	// Affiche le div de périodicité concerné
	afficher('div_periodicite_jour_semaine',false);
	afficher('div_periodicite_jour_mois',false);
	afficher('div_periodicite_mois',false);
	if(existe('div_periodicite_'+periodicite_type))		afficher('div_periodicite_'+periodicite_type,true,'block');

	// Masque / affiche les divs principaux
	if(periodicite_type=="")	{ afficher_dynamic('div_periodicite',false); afficher('div_periodicite_options',false); }
	else						{ afficher_dynamic('div_periodicite',true);	 afficher('div_periodicite_options',true); }

	// Ajoute un libellé spécifique si périodicité "mois" ou "annee"
	if(periodicite_type=="mois")		{ element('libelle_periodicite').innerHTML = "<?php echo $trad["le"]; ?> "+get_value('date_debut').substring(8,10)+" <?php echo $trad["AGENDA_period_mois_xdumois"]; ?>"; }
	else if(periodicite_type=="annee")	{ element('libelle_periodicite').innerHTML = "<?php echo $trad["le"]; ?> "+get_value('date_debut').substring(8,10)+"/"+get_value('date_debut').substring(5,7)+" <?php echo $trad["AGENDA_period_mois_xdeannee"]; ?>"; }
	else								{ element('libelle_periodicite').innerHTML = ""; }
}

////	AJOUTER / SUPPRIMER UN CHAMP D'IP DE CONTROLE
function ajouter_period_date_exception()
{
	for(var i=0; i< <?php echo $nb_period_date_exception; ?>; i++){
		if(element("div_period_date_exception_"+i).style.display=="none")  { afficher("div_period_date_exception_"+i, true, 'block');  break; }
	}
}
function supprimer_period_date_exception(id_champ)
{
	element(id_champ).value = "";
	afficher("div_"+id_champ,false);
}

////	Controle occupation créneaux horaires des agendas sélectionnés
function controle_creneaux_horaires()
{
	// Récupère la liste des agendas sélectionnés
	agendas_selectionnes = "";
	tab_affectations = document.getElementsByName("agenda_affectations[]");
	for(i=0; i < tab_affectations.length; i++)
	{
		id_agenda = tab_affectations[i].value;
		id_agenda_tmp	= tab_affectations[i].id.replace("box_","");
		if(is_checked("box_proposition_"+id_agenda_tmp) || is_checked("box_"+id_agenda_tmp))	agendas_selectionnes += "&agendas_selectionnes[]="+id_agenda;
	}

	// Vérifie le créneau horaire des agendas en Ajax
	var creneau_occupe = false;
	if(agendas_selectionnes!="")
	{
		recup_dates();
		requete_ajax("evenement_edit_verif.php?id_evenement=<?php echo @$evt_tmp["id_evenement"]; ?>&datetime_debut="+datetime_debut+"&datetime_fin="+datetime_fin+agendas_selectionnes);
		if(trouver("creneau_occupe",retour_ajax)){
			element("message_creneaux_horaires").innerHTML = retour_ajax.replace("creneau_occupe","");
			creneau_occupe = true;
		}
	}

	// Affiche / masque le block d'info
	if(creneau_occupe==false)												afficher("message_creneaux_horaires",false);
	else if(element("message_creneaux_horaires").style.display=="none")		afficher_dynamic("message_creneaux_horaires",true);
}

////	Controle de sélection d'un agenda
function select_agenda(id_elem_select, id_agenda, selection_groupe)
{
	// proposition coché -> décoche l'affectation directe  (et vice versa)
	box_propose = "box_proposition_"+id_agenda;
	box_select  = "box_"+id_agenda;
	if(trouver("proposition",id_elem_select)==true && is_checked(box_propose))	set_check(box_select,false);
	if(trouver("proposition",id_elem_select)==false && is_checked(box_select))	set_check(box_propose,false);

	// Cochage depuis une box / libellé
	if(element(box_select).disabled==false){
		id_elem_select2 = (trouver("box_",id_elem_select))  ?  box_select  :  "txt_"+id_agenda;
		checkbox_text(id_elem_select2);
	}

	// Controle des créneaux horaires occupés
	if(selection_groupe!=true)	controle_creneaux_horaires();
}

////	CONTROLE VALIDATION FINALE
function controle_formulaire()
{
	// Il doit y avoir un titre
	if(get_value("titre").length==0){
		alert("<?php echo $trad["specifier_titre"]; ?>");
		return false;
	}

	// Il doit y avoir au moins un agenda en affectation ou proposition
	verif_agenda = 0;
	tab_affectations = document.getElementsByName("agenda_affectations[]");
	tab_propositions = document.getElementsByName("agenda_propositions[]");
	for(i=0; i < tab_affectations.length; i++)	{ if(tab_affectations[i].checked==true)  verif_agenda ++; }
	for(i=0; i < tab_propositions.length; i++)	{ if(tab_propositions[i].checked==true)  verif_agenda ++; }
	if(verif_agenda==0)		{ alert("<?php echo $trad["AGENDA_verif_nb_agendas"]; ?>");  return false; }

	// Contrôte de la date si l'on précise une période (date au format unix)
	recup_dates();
	if(date_debut_unix > date_fin_unix || get_value("date_debut").length==0 || get_value("date_fin").length==0) {
		alert("<?php echo $trad["modif_dates_debutfin"]; ?>");
		return false;
	}

	// Controle le captcha si 'est un invité
	if(Controle_Menu_Objet(true)==false)	return false;

	// On réactive toutes les checkbox d'affectation et de proposition
	for(i=0; i < tab_affectations.length; i++)	{ tab_affectations[i].disabled = false; }
	for(i=0; i < tab_propositions.length; i++)	{ tab_propositions[i].disabled = false; }
}
</script>


<style>
.div_periodicite			{ margin-top:10px; display:none; }
.hr_details					{ margin:15px; height:3px; opacity:0.25; filter:alpha(opacity=25); }
.affect_users_groupes		{ float:left; width:32%; }
.affect_users_groupes:hover	{ background-color:<?php echo STYLE_TR_DESELECT; ?>; }
</style>


<form class="form_edit_element" action="<?php echo php_self(); ?>" method="post" enctype="multipart/form-data" OnSubmit="return controle_formulaire();">

	<?php
	////	TITRE  &  DESCRIPTION
	////
	echo "<fieldset style='text-align:center;'>";
		echo $trad["titre"]." &nbsp;<input type='text' name='titre' id='titre' value=\"".@$evt_tmp["titre"]."\" style='width:65%' /> &nbsp; &nbsp; ";
		echo "<span onClick=\"afficher_dynamic('block_description');afficher_tinymce();\" class='lien'>".$trad["description"]." <img src=\"".PATH_TPL."divers/derouler.png\" /></span>";
		echo "<span id='block_description'><br><br><textarea name='description' id='description' class='tinymce_textarea'>".@$evt_tmp["description"]."</textarea></span>";
		init_editeur_tinymce("description","block_description");
	echo "</fieldset>";

	////	DEBUT  &  FIN  &  PERIODICITE  &  AUTRES OPTIONS
	////
	echo "<fieldset style='text-align:center;'>";

		////	DATE DEBUT
		echo $trad["debut"]."<iframe id='calendrier_date_debut' class='menu_context calendrier_flottant' src=\"".PATH_INC."calendrier.inc.php?date_affiche=".$evt_tmp["date_debut"]."&date_selection=".$evt_tmp["date_debut"]."&champ_modif=date_debut&pas_reinit=1\"></iframe>";
		echo "<input type='text' name='date_debut' value=\"".strftime("%Y-%m-%d",$evt_tmp["date_debut"])."\" class='calendrier_input' style='margin-left:7px;margin-right:7px;' onClick=\"afficher_dynamic('calendrier_date_debut');\" onChange='controle_creneaux_horaires();' readonly />&nbsp;";
		////	HEURE DEBUT
		echo "<select name='heure_debut' id='heure_debut' onChange='controle_creneaux_horaires();'>";
		for($H=0; $H<=23; $H++)		{ echo "<option value=\"".num2carac($H)."\" ".(num2carac($H)==strftime("%H",$evt_tmp["date_debut"])?"selected":"").">".num2carac($H)."</option>"; }
		echo "</select>";
		////	MINUTE DEBUT
		echo "<select name='minute_debut' id='minute_debut' style='margin-left:7px;margin-right:30px;' onChange='controle_creneaux_horaires();'>";
		for($M=0; $M<=55; $M+=5)	{ echo "<option value=\"".num2carac($M)."\" ".(num2carac($M)==strftime("%M",$evt_tmp["date_debut"])?"selected":"").">".num2carac($M)."</option>"; }
		echo "</select>";

		////	DATE FIN
		echo $trad["fin"]."<iframe id='calendrier_date_fin' class='menu_context calendrier_flottant' src=\"".PATH_INC."calendrier.inc.php?date_affiche=".$evt_tmp["date_fin"]."&date_selection=".$evt_tmp["date_fin"]."&champ_modif=date_fin&pas_reinit=1\"></iframe>";
		echo "<input type='text' name='date_fin' value=\"".strftime("%Y-%m-%d",$evt_tmp["date_fin"])."\" class='calendrier_input' style='margin-left:10px;margin-right:10px;' onClick=\"afficher_dynamic('calendrier_date_fin');\" onChange='controle_creneaux_horaires();' readonly />&nbsp;";
		////	HEURE FIN
		echo "<select name='heure_fin' id='heure_fin' onChange='controle_creneaux_horaires();'>";
		for($H=0; $H<=23; $H++)		{ echo "<option value=\"".num2carac($H)."\" ".(num2carac($H)==strftime("%H",$evt_tmp["date_fin"])?"selected":"").">".num2carac($H)."</option>"; }
		echo "</select>";
		////	MINUTE FIN
		echo "<select name='minute_fin' id='minute_fin' style='margin-left:7px;' onChange='controle_creneaux_horaires();'>";
		for($M=0; $M<=55; $M+=5)	{ echo "<option value=\"".num2carac($M)."\" ".(num2carac($M)==strftime("%M",$evt_tmp["date_fin"])?"selected":"").">".num2carac($M)."</option>"; }
		echo "</select>";


		////	OPTION POUR LES UTILISATEURS (PAS LES INVITES)
		if($_SESSION["user"]["id_utilisateur"]>0)
		{
			////	PERIODICITES
			////
			echo "<span class='lien' style='margin-left:35px;' onClick=\"afficher_dynamic('div_periodicite');\">".$trad["AGENDA_periodicite"]." &nbsp;<img src=\"".PATH_TPL."divers/reload.png\" /></span>";
			echo "<div id='div_periodicite'>";
				////	SELECTION PERIODICITE
				echo "<hr class='hr_details' />".$trad["AGENDA_periodicite"]." : ";
				echo "<select name='periodicite_type' onChange=\"afficher_periodicite();\">";
					echo "<option value=''>".$trad["non"]."</option>";
					echo "<option value='jour_semaine'>".$trad["AGENDA_period_jour_semaine"]."</option>";
					echo "<option value='jour_mois'>".$trad["AGENDA_period_jour_mois"]."</option>";
					echo "<option value='mois'>".$trad["AGENDA_period_mois"]."</option>";
					echo "<option value='annee'>".$trad["AGENDA_period_annee"]."</option>";
				echo "</select> &nbsp; ";
				echo "<span id='libelle_periodicite'></span>";
				////	JOURS DE LA SEMAINE
				echo "<div id='div_periodicite_jour_semaine' class='div_periodicite'>";
				for($j=1; $j<=7; $j++)
				{
					$checked = (@$evt_tmp["periodicite_type"]=="jour_semaine" && preg_match("/".$j."/", @$evt_tmp["periodicite_valeurs"]))  ?  "checked"  :  "";
					echo "<input type='checkbox' name='period_jour_semaine[]' value='".$j."' ".$checked." /> ".$trad["jour_".$j]." &nbsp; &nbsp; &nbsp; ";
				}
				echo "</div>";
				////	JOURS DU MOIS
				echo "<div id='div_periodicite_jour_mois' class='div_periodicite'><table width='100%'><tr>";
				for($i=1; $i<=31; $i++)
				{
					$checked = (@$evt_tmp["periodicite_type"]=="jour_mois" && preg_match("/".num2carac($i)."/", @$evt_tmp["periodicite_valeurs"]))  ?  "checked"  :  "";
					echo "<td style='text-align:left;'><input type='checkbox' name='period_jour_mois[]' value=\"".num2carac($i)."\" ".$checked." />".$i."</td>";
					if($i==15)	echo "</tr><tr>";
				}
				echo "</tr></table></div>";
				////	MOIS DE L'ANNEE
				echo "<div id='div_periodicite_mois' class='div_periodicite'><table width='100%'><tr>";
				for($m=1; $m<=12; $m++)
				{
					$checked = (@$evt_tmp["periodicite_type"]=="mois" && preg_match("/".num2carac($m)."/", @$evt_tmp["periodicite_valeurs"]))  ?  "checked"  :  "";
					echo "<td style='text-align:left;'><input type='checkbox' name='period_mois[]' value=\"".num2carac($m)."\" ".$checked." /> ".$trad["mois_".$m]."</td>";
					if($m==6)	echo "</tr><tr>";
				}
				echo "</tr></table></div>";
				////	FIN DE PERIODICITE  &  EXCEPTIONS DE PERIODICITE
				echo "<div id='div_periodicite_options' class='div_periodicite'><table style='width:100%;margin-top:10px;'><tr>";
					////	FIN DE PERIODICITE
					echo "<td style='width:50%;text-align:right;padding-right:15px;vertical-align:top;'>";
						echo $trad["AGENDA_period_date_fin"]." ";
						$period_date_fin_cal = (strtotime(@$evt_tmp["period_date_fin"])<1)  ?  time()  :  strtotime(@$evt_tmp["period_date_fin"]);
						echo "<iframe id='calendrier_period_date_fin' class='menu_context calendrier_flottant' src=\"".PATH_INC."calendrier.inc.php?date_selection=".$period_date_fin_cal."&champ_modif=period_date_fin\" scrolling='auto'></iframe>";
						echo "<input type='text' name='period_date_fin' value=\"".@$evt_tmp["period_date_fin"]."\" class='calendrier_input' onClick=\"afficher_dynamic('calendrier_period_date_fin');\" readonly />";
					echo "</td>";
					////	EXCEPTIONS DE PERIODICITE (10 maxi)
					echo "<td style='text-align:left;padding-left:15px;vertical-align:middle;'>";
						$liste_period_date_exception = text2tab(@$evt_tmp["period_date_exception"]);
						echo $trad["AGENDA_exception_periodicite"]." &nbsp;<img src=\"".PATH_TPL."divers/plus2.png\" onclick=\"ajouter_period_date_exception();\" class='lien' title=\"".$trad["ajouter"]."\" />";
						for($i=0; $i < $nb_period_date_exception; $i++)
						{
							$date_exception_cal = (@$liste_period_date_exception[$i]!="")  ?  strtotime($liste_period_date_exception[$i])  :  time();
							$id_input = "period_date_exception_".$i;
							echo "<div id='div_".$id_input."' style='padding:3px;".((@$liste_period_date_exception[$i]!="")?"":"display:none;")."'>";
								echo "<iframe id='calendrier_".$id_input."' class='menu_context calendrier_flottant' scrolling='auto'></iframe>";
								echo "<input type='text' name='period_date_exception[]' value=\"".@$liste_period_date_exception[$i]."\" id='".$id_input."' class='calendrier_input'  onClick=\"afficher_dynamic('calendrier_".$id_input."'); if(element('calendrier_".$id_input."').src=='') element('calendrier_".$id_input."').src='".PATH_INC."calendrier.inc.php?date_affiche=".$date_exception_cal."&date_selection=".$date_exception_cal."&champ_modif=".$id_input."';\" readonly />";
								echo "<img src=\"".PATH_TPL."divers/supprimer.png\" onclick=\"supprimer_period_date_exception('".$id_input."');\" class='lien' title=\"".$trad["supprimer"]."\" />";
							echo "</div>";
						}
					echo "</td>";
				echo "</tr></table></div>";
				////	AFFICHE LES VALEURS ET DIV DES PERIODICITES
				echo "<script>  set_value('periodicite_type','".@$evt_tmp["periodicite_type"]."');  afficher_periodicite();  </script>";
			echo "</div>";


			////	VISIBILITE
			////
			echo "<hr class='hr_details' />";
			echo $trad["AGENDA_visibilite"]." &nbsp; ";
			echo "<select name='visibilite' ".infobulle($trad["AGENDA_visibilite_info"]).">";
				echo "<option value='public'>".$trad["AGENDA_visibilite_public"]."</option>";
				echo "<option value='public_cache'>".$trad["AGENDA_visibilite_public_cache"]."</option>";
				echo "<option value='prive'>".$trad["AGENDA_visibilite_prive"]."</option>";
			echo "</select>";
			echo "<script> set_value('visibilite','".@$evt_tmp["visibilite_contenu"]."'); </script>";

			////	CATEGORIES
			////
			echo "&nbsp; &nbsp; &nbsp;".$trad["AGENDA_categorie"]." &nbsp; ";
			echo "<select name='id_categorie' onChange=\"style_select(this.name);\">";
			echo "<option value=''>&nbsp;</option>";
			foreach(categories_evt() as $categorie){
				echo "<option value='".$categorie["id_categorie"]."' style='color:#fff;background-color:".$categorie["couleur"]."' ".(@$evt_tmp["id_categorie"]==$categorie["id_categorie"]?"selected":"")." title=\"".$categorie["description"]."\">".$categorie["titre"]."</option>";
			}
			echo "</select>";
			echo "<script> set_value('id_categorie','".@$evt_tmp["id_categorie"]."'); style_select('id_categorie'); </script>";

			////	IMPORTANT
			////
			echo "&nbsp; &nbsp; &nbsp;".$trad["important"]." &nbsp; ";
			echo "<select name='important' onChange=\"style_select(this.name);\">";
				echo "<option value=''>".$trad["non"]."</option>";
				echo "<option value='1' style='background-color:#900;color:#fff;'>".$trad["oui"]."</option>";
			echo "</select>";
			echo "<script> set_value('important','".@$evt_tmp["important"]."');  style_select('important'); </script>";
		}
	echo "</fieldset>";


	////	AFFECTATION AUX AGENDAS
	////
	echo "<fieldset class='pas_selection' style='".($_SESSION["user"]["id_utilisateur"]<1?"display:none;":"")."'>";
		echo "<div style='text-align:center;margin-bottom:10px;'>".$trad["AGENDA_agendas_affectations"]." <img src=\"".PATH_TPL."divers/check_inverser.png\" class='lien' onclick=\"inverser_selection();\" ".infobulle($trad["inverser_selection"])." /></div>";
		echo "<div style='max-height:240px;overflow:auto;padding-left:20px;'>";
		////	GROUPES D'USERS (AFFECTATIONS EXPRESS PAR COCHAGE DE CHECKBOX)
		////
		$groupes_users = groupes_users($_SESSION["espace"]["id_espace"]);
		foreach($groupes_users as $groupe_tmp)
		{
			// Init
			$groupe_tmp["id_groupe_bis"] = "G".$groupe_tmp["id_groupe"];
			$groupe_tmp["users_tab_bis"] = array();
			foreach($groupe_tmp["users_tab"] as $id_user)	{ $groupe_tmp["users_tab_bis"][] = "'U".$id_user."'"; }
			// Groupe  +  tableau des utilisateurs ("users_ensembles[]" initialisé sur javascript.js)
			echo "<div class='affect_users_groupes lien' ".infobulle(str_replace(",","<br>",$groupe_tmp["users_title"])).">";
				echo "<img src=\"".PATH_TPL."module_utilisateurs/utilisateurs_groupe.png\" class='icone_groupe' />";
				echo "<span id='txt_".$groupe_tmp["id_groupe_bis"]."' onClick=\"checkbox_text(this);selection_groupe('".$groupe_tmp["id_groupe_bis"]."');controle_creneaux_horaires();\">".$groupe_tmp["titre"]."</span>";
				echo "<input type='checkbox' id='box_".$groupe_tmp["id_groupe_bis"]."' style='display:none;' />";
				echo "<script>  users_ensembles['".$groupe_tmp["id_groupe_bis"]."'] = Array(".implode(",",$groupe_tmp["users_tab_bis"])."); </script>";
			echo "</div>";
		}
		if(count($groupes_users)>0)  echo "<hr style='clear:both;margin:5px;' />";

		////	AFFECTATIONS AUX AGENDAS (USERS & RESSOURCES)
		////
		$agendas_affectes = db_tableau("SELECT * FROM gt_agenda_jointure_evenement WHERE id_evenement='".@$evt_tmp["id_evenement"]."'", "id_agenda");
		foreach($AGENDAS_AFFECTATIONS as $agenda_tmp)
		{
			////	INVITE : AFFICHE JUSTE L'AGENDA CONCERNE PAR LA PROPOSITION
			if($_SESSION["user"]["id_utilisateur"]<1 && $agenda_tmp["id_agenda"]!=@$_GET["id_agenda"])  continue;
			////	INIT
			$proposer_evt = $proposer_checked = "";
			$deja_affecte = $deja_propose = false;
			$proposer_affecter = agenda_proposer_affecter_evt($agenda_tmp, $evt_tmp["droit_acces"]);
			$id_agenda_tmp = ($agenda_tmp["type"]=="utilisateur")  ?  "U".$agenda_tmp["id_utilisateur"]  :  "A".$agenda_tmp["id_agenda"];
			////	AGENDA DEJA PROPOSE / AFFECTE ?
			if(is_array(@$agendas_affectes[$agenda_tmp["id_agenda"]])==true){
				if($agendas_affectes[$agenda_tmp["id_agenda"]]["confirme"]==1)	$deja_affecte = true;
				else															$deja_propose = true;
			}
			////	PROPOSITION
			if(preg_match("/proposer/i",$proposer_affecter))
			{
				// Check :  déjà proposé (ancien evt)  OU  présélectionné pour proposition uniquement ("proposer")
				if($deja_propose==true || (@$_GET["id_agenda"]==$agenda_tmp["id_agenda"] && $proposer_affecter=="proposer"))	$proposer_checked = "checked";
				// Désactiver (ancien evt) :  pas auteur de l'evt  et  agenda pas accessible en écriture
				$proposer_evt = ($evt_tmp["droit_acces"]<3 && $agenda_tmp["droit"]<2)  ?  "disabled='disabled' class='champ_desactive' "  :  infobulle($trad["AGENDA_input_proposer"]);
				$proposer_evt = "<span style='font-size:10px;'>?</span><input type='checkbox' name='agenda_propositions[]' value='".$agenda_tmp["id_agenda"]."' id='box_proposition_".$id_agenda_tmp."' onClick=\"select_agenda(this.id,'".$id_agenda_tmp."');\" ".$proposer_checked." ".$proposer_evt." />";
			}
			////	AFFECTATION
			// Check :  déjà affecté (ancien evt)  OU  présélectionné (nouvel evt)
			$affecter_checked = ($deja_affecte==true || (@$_GET["id_agenda"]==$agenda_tmp["id_agenda"] && preg_match("/affecter/i",$proposer_affecter)==true))  ?  "checked"  :  "";
			// Activer :  dejà affecté + auteur de l'evt  OU  affectation possible
			$affecter_desactive	= (($deja_affecte==true && $evt_tmp["droit_acces"]==3) || preg_match("/affecter/i",$proposer_affecter)==true)  ?  ""  :  "disabled='disabled' class='champ_desactive'";
			$affecter_info = ($affecter_desactive=="")  ?  infobulle($trad["AGENDA_input_affecter"])  :  "";
			// style du texte et affichage de la box
			if($affecter_checked!="") {$style="lien_select";}    elseif($affecter_desactive=="") {$style="lien";}    else {$style="";}
			if($agenda_tmp["type"]=="ressource")	$agenda_tmp["titre"] = "<i>".$agenda_tmp["titre"]."</i>";
			$affectation = "<input type='checkbox' name='agenda_affectations[]' value='".$agenda_tmp["id_agenda"]."' id='box_".$id_agenda_tmp."' onClick=\"select_agenda(this.id,'".$id_agenda_tmp."');\" ".$affecter_checked." ".$affecter_desactive." ".$affecter_info." />";
			////	INFOBULLE  (droits d'affectation  +  descripon de l'agenda)
			if($affecter_checked!="" && $affecter_desactive!="")	{ $infos_acces = $trad["AGENDA_info_pas_modif"]; }
			elseif($affecter_desactive!="")							{ $infos_acces = $trad["AGENDA_info_proposer"]; }
			else													{ $infos_acces = $trad["AGENDA_input_affecter"]; }
			if($agenda_tmp["description"]!="")  $infos_acces = "<div style='font-weight:normal;font-style:italic;'>".$agenda_tmp["description"]."</div>".$infos_acces;

			////	AFFICHAGE
			echo "<table class='affect_users_groupes' cellspacing='1px'>";
				echo "<tr>";
					echo "<td style='width:30px;cursor:help;opacity:0.8;filter:alpha(opacity=80);'>".$proposer_evt."</td>";
					echo "<td style='width:18px;'>".$affectation."</td>";
					echo "<td class='".$style."' style='vertical-align:middle;padding-right:10px;' ".infobulle($infos_acces)." id='txt_".$id_agenda_tmp."'  onClick=\"select_agenda(this.id,'".$id_agenda_tmp."');\">".$agenda_tmp["titre"]."</td>";
				echo "</tr>";
			echo "</table>";
		}
		echo "</div>";

		////	ACCES EN ECRITURE LIMITE : MESSAGE D'INFO
		if($evt_tmp["droit_acces"]==2){
			echo "<hr class='hr_details' /><div style='".STYLE_SELECT_YELLOW."text-align:center;'><img src=\"".PATH_TPL."divers/important.png\" id='alerte_edit' /> &nbsp; ".$trad["AGENDA_edit_limite"]."</div />";
			echo "<script> $('#alerte_edit').effect('pulsate',{times:5},5000); </script>";
		}
		
		echo "<div id='message_creneaux_horaires' style='max-height:150px;overflow:auto;display:none;".STYLE_SELECT_YELLOW."'></div>";
	echo "</fieldset>";


	////	DROITS D'ACCES ET OPTIONS
	////
	$cfg_menu_edit = array("objet"=>$objet["evenement"], "id_objet"=>@$evt_tmp["id_evenement"]);
	require_once PATH_INC."element_menu_edit.inc.php";
	?>

	<div class="submit_edit_element">
		<input type="hidden" name="id_evenement" value="<?php echo @$evt_tmp["id_evenement"]; ?>" />
		<input type="hidden" name="agenda_affectations_init" value="<?php foreach($AGENDAS_AFFECTATIONS as $agenda_tmp)  { echo $agenda_tmp["id_agenda"]."@"; } ?>" />
		<input type="submit" value="<?php echo $trad["valider"]; ?>" class="button_big" />
	</div>

</form>


<?php require PATH_INC."footer.inc.php"; ?>