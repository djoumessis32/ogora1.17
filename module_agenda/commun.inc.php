<?php
////	INITIALISATION
////
@define("MODULE_NOM","agenda");
@define("MODULE_PATH","module_agenda");
require_once "../includes/global.inc.php";
$config["module_espace_options"]["agenda"] = array("ajout_agenda_ressource_admin","ajout_categorie_admin");
$objet["agenda"]	= array("type_objet"=>"agenda", "cle_id_objet"=>"id_agenda", "type_contenu"=>"evenement", "cle_id_contenu"=>"id_evenement", "table_objet"=>"gt_agenda");
$objet["evenement"]	= array("type_objet"=>"evenement", "cle_id_objet"=>"id_evenement", "type_conteneur"=>"agenda", "cle_id_conteneur"=>"id_agenda", "table_objet"=>"gt_agenda_evenement");
$objet["evenement"]["champs_recherche"] = array("titre","description");


////	AGENDAS VISIBLES  (RESSOURCE & PERSONNELS)
////
////	Agendas de ressource  ET  Agendas persos activés (user courant en premier + agendas affectés à l'user)
$agendas_ressource	 = db_tableau("SELECT DISTINCT  *  FROM  gt_agenda  WHERE  type='ressource' ".sql_affichage($objet["agenda"])."  ORDER BY titre asc");
$agendas_utilisateur = db_tableau("SELECT DISTINCT  *  FROM  gt_agenda T1, gt_utilisateur T2  WHERE  T1.id_utilisateur=T2.id_utilisateur  AND  T1.type='utilisateur'  AND  T2.agenda_desactive is null  AND  (T1.id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."'  OR  (1 ".sql_affichage($objet["agenda"],"","T1.")."))  ORDER BY (T1.id_utilisateur=".$_SESSION["user"]["id_utilisateur"].") DESC, T2.".$_SESSION["agora"]["tri_personnes"]);
////	Ajoute les droits d'accès (l'user peut voir son agenda sur ts les espaces ET les admins ont pas d'accès privilégiés aux agendas persos des autres)
$AGENDAS_AFFICHES = array();
foreach(array_merge($agendas_utilisateur,$agendas_ressource) as $agenda_tmp)
{
	if($agenda_tmp["type"]=="ressource")	{ $agenda_tmp["droit"] = droit_acces($objet["agenda"],$agenda_tmp); }
	else									{ $agenda_tmp["droit"] = droit_acces($objet["agenda"],$agenda_tmp,false);  $agenda_tmp["titre"] = auteur($agenda_tmp); }
	$AGENDAS_AFFICHES[$agenda_tmp["id_agenda"]] = $agenda_tmp;
}


////	AGENDAS POUR LES AFFECTATIONS D'EVENEMENTS
////
// Ajoute les agendas non visibles des users de l'espace courant : propositions d'evt
$id_users_visibles = array("0");
foreach(users_visibles() as $user_tmp)	{ $id_users_visibles[] = $user_tmp["id_utilisateur"]; }
$agenda_affectations_tmp = $AGENDAS_AFFICHES;
foreach(db_tableau("SELECT * FROM gt_agenda WHERE type='utilisateur' AND id_utilisateur IN (".implode(",",$id_users_visibles).") AND id_utilisateur NOT IN (select id_utilisateur from gt_utilisateur where agenda_desactive=1)") as $agenda_tmp)
{
	if(array_key_exists($agenda_tmp["id_agenda"],$AGENDAS_AFFICHES)==false){
		$agenda_tmp["titre"] = auteur($agenda_tmp["id_utilisateur"]);
		$agenda_tmp["droit"] = ($_SESSION["user"]["admin_general"]==1) ? 3 : 0;
		$agenda_affectations_tmp[$agenda_tmp["id_agenda"]] = $agenda_tmp;
	}
}
// On reclasse les agendas :  agendas des users, re-classés par titre  >>  agendas de ressource
$AGENDAS_AFFECTATIONS = array();
foreach($agenda_affectations_tmp as $agenda_tmp2)	{ if($agenda_tmp2["type"]=="utilisateur"){$AGENDAS_AFFECTATIONS[$agenda_tmp2["id_agenda"]]=$agenda_tmp2;} }
$AGENDAS_AFFECTATIONS = array_multi_sort($AGENDAS_AFFECTATIONS,"titre","asc",true);
foreach($agenda_affectations_tmp as $agenda_tmp2)	{ if($agenda_tmp2["type"]=="ressource"){$AGENDAS_AFFECTATIONS[$agenda_tmp2["id_agenda"]]=$agenda_tmp2;} }


////	AGENDA AFFICHES
////
////	Affichage de tous les agendas demandé ?  (admin général uniquement)
if($_SESSION["user"]["admin_general"]==1){
	if(isset($_REQUEST["afficher_tous_agendas"]))				{$_SESSION["cfg"]["espace"]["afficher_tous_agendas"] = $_REQUEST["afficher_tous_agendas"];}
	if(@$_SESSION["cfg"]["espace"]["afficher_tous_agendas"]==1)	{$AGENDAS_AFFICHES = $AGENDAS_AFFECTATIONS;}
}
////	Agendas demandés - Agendas enregistrés en preference  /  Agendas par défaut (user = agenda perso OU invité = 1er agenda ressource)
$_SESSION["cfg"]["espace"]["agendas_affiches"] = array();
$pref_agendas_affiches = pref_user("agendas_affiches","agendas_demandes",false,true);
foreach($AGENDAS_AFFICHES as $agenda_tmp)
{
	if(isset($pref_agendas_affiches) && @in_array($agenda_tmp["id_agenda"],@$pref_agendas_affiches))						{$_SESSION["cfg"]["espace"]["agendas_affiches"][]=$agenda_tmp["id_agenda"];}
	elseif(empty($pref_agendas_affiches) && $agenda_tmp["type"]=="utilisateur" && is_auteur($agenda_tmp["id_utilisateur"]))	{$_SESSION["cfg"]["espace"]["agendas_affiches"][]=$agenda_tmp["id_agenda"]; break;}
	elseif(empty($pref_agendas_affiches) && $agenda_tmp["type"]=="ressource" && $_SESSION["user"]["id_utilisateur"]<1)		{$_SESSION["cfg"]["espace"]["agendas_affiches"][]=$agenda_tmp["id_agenda"]; break;}
}


////	MODE D'AFFICHAGE  &  DATE D'AFFICHE  &  PERIODE D'AFFICHAGE
////
////	Mode d'affichage (jour / semaine / semaine de W / mois)
$pref_agenda_affichage = pref_user("agenda_affichage","affichage_demande");
if(!isset($_SESSION["cfg"]["espace"]["agenda_affichage"]) || isset($_GET["affichage_demande"])){
	$_SESSION["cfg"]["espace"]["agenda_affichage"] = ($pref_agenda_affichage!="")  ?  $pref_agenda_affichage  :  "mois";
}
////	Date d'affichage
if(!isset($_SESSION["cfg"]["espace"]["agenda_date"]))	{$_SESSION["cfg"]["espace"]["agenda_date"] = time();}
if(isset($_GET["date_affiche"]))						{$_SESSION["cfg"]["espace"]["agenda_date"] = $_GET["date_affiche"];}
////	Période d'affichage au format Unix
if($_SESSION["cfg"]["espace"]["agenda_affichage"]=="jour"){
	$config["agenda_debut"] = strtotime(strftime("%Y-%m-%d 00:00:00",$_SESSION["cfg"]["espace"]["agenda_date"]));
	$config["agenda_fin"] = strtotime(strftime("%Y-%m-%d 23:59:59",$_SESSION["cfg"]["espace"]["agenda_date"]));
}
elseif(preg_match("/semaine/i",$_SESSION["cfg"]["espace"]["agenda_affichage"])){
	$agenda_date_jour_semaine = str_replace("0","7", strftime("%w",$_SESSION["cfg"]["espace"]["agenda_date"]));  // Jour de la semaine : 1=lundi,... 7=dimanche
	$nb_jours_semaine = ($_SESSION["cfg"]["espace"]["agenda_affichage"]=="semaine_w") ? "5" : "7";
	$config["agenda_debut"] = strtotime(strftime("%Y-%m-%d 00:00:00",$_SESSION["cfg"]["espace"]["agenda_date"])) - (86400*($agenda_date_jour_semaine-1));
	$config["agenda_fin"] = $config["agenda_debut"] + (86400*$nb_jours_semaine) - 1;
	// Affichage "semaine_w" de la semaine courante ET on est le weekend : affiche la semaince complete
	if($_SESSION["cfg"]["espace"]["agenda_affichage"]=="semaine_w"  &&  $config["agenda_fin"] < time()  &&  time() < ($config["agenda_fin"] + (86400*2)))	{redir(php_self()."?affichage_demande=semaine");}
}
else{
	$annee_tmp = strftime("%Y",$_SESSION["cfg"]["espace"]["agenda_date"]);
	$mois_tmp  = strftime("%m",$_SESSION["cfg"]["espace"]["agenda_date"]);
	$nb_jours_mois = date("t",$_SESSION["cfg"]["espace"]["agenda_date"]); // nombre de jours dans le mois
	$config["agenda_debut"] = strtotime($annee_tmp."-".$mois_tmp."-01 00:00:00");
	$config["agenda_fin"]   = strtotime($annee_tmp."-".$mois_tmp."-".$nb_jours_mois." 23:59:59");
}




////	PREPARE LE MENU CONTEXTUEL DE CHAQUE EVENEMENT (MODIF, SUPPR, VISIBILITE, ETC.)
////
function evt_cfg_menu_elem($evt_tmp, $agenda_tmp, $jour_ymd_select)
{
	////	INIT
	global $trad, $objet;
	if(!isset($evt_tmp["droit_acces"]))		{$evt_tmp["droit_acces"] = droit_acces($objet["evenement"],$evt_tmp,false);}
	$cfg_menu_elem = array("objet"=>$objet["evenement"], "objet_infos"=>$evt_tmp, "taille_icone"=>"small");

	////	MODIF
	if($evt_tmp["droit_acces"] > 1)		{$cfg_menu_elem["modif"] = "evenement_edit.php?id_evenement=".$evt_tmp["id_evenement"]."&id_agenda=".$agenda_tmp["id_agenda"];}
	////	SUPPRESSION
	if($evt_tmp["droit_acces"]==3 || $agenda_tmp["droit"]>=2)
	{
		////	Suppression ds l'agenda courant
		$cfg_menu_elem["suppr"] = "index.php?action=suppr_evt&id_evenement=".$evt_tmp["id_evenement"]."&id_agenda=".$agenda_tmp["id_agenda"];
		$cfg_menu_elem["suppr_text_confirm"] = $trad["AGENDA_confirm_suppr_evt"];
		////	Suppressions ds les agendas, pour le proprio de l'evt
		if($evt_tmp["droit_acces"]==3)
		{
			// Périodicité : supprime à une date précise
			if($evt_tmp["periodicite_type"]!="")	{$cfg_menu_elem["options_divers"][] = array("action_js"=>"confirmer('".addslashes($trad["AGENDA_confirm_suppr_evt_date"])."','index.php?action=suppr_evt&id_evenement=".$evt_tmp["id_evenement"]."&id_agenda=tous&date_suppr=".$jour_ymd_select."');",  "icone_src"=>PATH_TPL."divers/supprimer.png",  "text"=>$trad["AGENDA_supprimer_evt_date"]);}
			// Supprime pour tous les agendas ou il est affecté (s'il y en a plusieurs)
			if(db_valeur("SELECT count(*) FROM gt_agenda_jointure_evenement WHERE id_evenement='".$evt_tmp["id_evenement"]."'")>1){
				$cfg_menu_elem["options_divers"][] = array("action_js"=>"confirmer('".addslashes($trad["AGENDA_confirm_suppr_evt_tout"])."','index.php?action=suppr_evt&id_evenement=".$evt_tmp["id_evenement"]."&id_agenda=tous');",  "icone_src"=>PATH_TPL."divers/supprimer.png",  "text"=>$trad["AGENDA_supprimer_evt_agendas"]);
				$cfg_menu_elem["suppr_text"] = $trad["AGENDA_supprimer_evt_agenda"];
			}
		}
	}

	////	VISIBILITE SPECIFIQUE  &  RETOUR
	$cfg_menu_elem["visibilite_specifique"] = txt_affections_evt($evt_tmp);
	return $cfg_menu_elem;
}


////	TEXTE AFFICHANT DANS LE MENU CONTECTUEL DES EVENEMENTS :
////	>> LES AGENDA(S) DANS LEQUEL SE TROUVE L'EVENEMENT & AVEC LES AGENDAS EN ATTENTES DE CONFIRMATION
////
function txt_affections_evt($evt_tmp)
{
	if($_SESSION["user"]["id_utilisateur"] > 0)
	{
		////	Initialisation
		global $trad, $AGENDAS_AFFECTATIONS;
		$txt_confirme = $txt_pas_confirme = "";
		////	Agendas confirmés  (+ "d'autres agendas non visibles" ?)
		$agendas_confirmes = agendas_evts($evt_tmp["id_evenement"],"1");
		if(count($agendas_confirmes)>0){
			foreach($agendas_confirmes as $id_agenda)	{ $txt_confirme .= $AGENDAS_AFFECTATIONS[$id_agenda]["titre"].", "; }
			$txt_confirme = "<div>".$trad["AGENDA_affectations_evt"]."<br>".substr($txt_confirme,0,-2)."</div>";
			if(count($agendas_confirmes) < db_valeur("select count(*) from gt_agenda_jointure_evenement where id_evenement=".$evt_tmp["id_evenement"]." and confirme='1'"))		{$txt_confirme .= $trad["AGENDA_affectations_evt_autres"];}
		}
		////	Agendas pas encore confirmés
		$agendas_pas_confirmes = agendas_evts($evt_tmp["id_evenement"],"0");
		if(count($agendas_pas_confirmes)>0){
			foreach($agendas_pas_confirmes as $id_agenda)	{ $txt_pas_confirme .= $AGENDAS_AFFECTATIONS[$id_agenda]["titre"].", "; }
			$txt_pas_confirme = "<hr /><div> ".$trad["AGENDA_affectations_evt_non_confirme"]."<br>".substr($txt_pas_confirme,0,-2)."</div>";
		}
		////	Retourne le résultat
		return $txt_confirme.$txt_pas_confirme;
	}
}


////	LISTE DES EVENEMENTS D'UN AGENDA SUR UNE PERIODE/CRENEAU HORAIRE
////
function liste_evenements($id_agenda, $timeDebutCreneau, $timeFinCreneau, $orderByHourMinute=true, $type_sortie="lecture")
{
	////	CRÉNEAU SIMPLE :  DÉBUT DU CRÉNEAU  ||  FIN DU CRÉNEAU  ||  DEBUT < CRÉNEAU < FIN
	$dateDebutCreneau	= strftime("%Y-%m-%d %H:%M:00", $timeDebutCreneau);
	$dateFinCreneau		= strftime("%Y-%m-%d %H:%M:59", $timeFinCreneau);
	$sql_creneau		= "((T1.date_debut between '".$dateDebutCreneau."' and '".$dateFinCreneau."')  OR  (T1.date_fin between '".$dateDebutCreneau."' and '".$dateFinCreneau."')  OR  (T1.date_debut < '".$dateDebutCreneau."' and T1.date_fin > '".$dateFinCreneau."'))";
	////	PÉRIODICITÉ DES ÉVÉNEMENTS
	// Init
	$dateDebutCreneau_Ymd	= strftime("%Y-%m-%d",$timeDebutCreneau);
	$dateFinCreneau_Ymd		= strftime("%Y-%m-%d",$timeFinCreneau);
	$creneauJourSemaine		= str_replace("0","7",strftime("%w",$timeDebutCreneau)); // de 1 à 7 (lundi à dimanche)
	$creneauJourMois		= strftime("%d",$timeDebutCreneau); // jour du mois en numérique => 01 à 31
	$creneauMois			= strftime("%m",$timeDebutCreneau); // mois de l'annee en numérique => 01 à 12
	$creneauJourAnnee		= strftime("%m-%d", $timeDebutCreneau);
	// Critères de periodicité :
	//  type périodicité spécifié
	//+ debut d'evt déjà commencé
	//+ fin de périodicité pas spécifié OU pas encore arrivée
	//+ date de début d'evt pas présente dans les exceptions (le créneau n'excede pas un jour --> SINON, AJOUTER "NOT IN ('2010-01-01','2010-01-02')"!!)
	//+ périodicité jour / semaine / mois / annee !
	$periodicite	= "(T1.periodicite_type is not null  AND  T1.date_debut<'".$dateDebutCreneau."'  AND  (T1.period_date_fin is null or '".$dateFinCreneau_Ymd."'<=T1.period_date_fin)  AND  (T1.period_date_exception is null or period_date_exception not like '%".$dateDebutCreneau_Ymd."%')  AND    ((T1.periodicite_type='jour_semaine' and T1.periodicite_valeurs like '%".$creneauJourSemaine."%')  OR  (T1.periodicite_type='jour_mois' and T1.periodicite_valeurs like '%".$creneauJourMois."%')  OR  (T1.periodicite_type='mois' and T1.periodicite_valeurs like '%".$creneauMois."%' and DATE_FORMAT(T1.date_debut,'%d')='".$creneauJourMois."')  OR  (T1.periodicite_type='annee' and DATE_FORMAT(T1.date_debut,'%m-%d')='".$creneauJourAnnee."')))";
	////	RÉCUPÈRE LA LISTE DES EVENEMENTS  ($orderByHourMinute==true => tri par "heure:minute" (pour les événements périodique..) au lieu de trier par "date_debut")
	$orderBy = ($orderByHourMinute==true)  ?  "DATE_FORMAT(T1.date_debut,'%H:%i')"  :  "T1.date_debut";
	$liste_evenements_tmp = db_tableau("SELECT  T1.*  FROM gt_agenda_evenement T1, gt_agenda_jointure_evenement T2  WHERE  T1.id_evenement=T2.id_evenement  AND  T2.id_agenda='".intval($id_agenda)."'  AND  T2.confirme='1'  AND  (".$sql_creneau." OR ".$periodicite.") ".sql_evt_filtre_categorie()."  ORDER BY ".$orderBy." ASC");
	////	CREE LE TABLEAU DE SORTIE (EN AJOUTANT LE DROIT D'ACCÈS, MASQUANT LES LIBELLÉS SI BESOIN, ETC.)
	global $objet;
	$liste_evenements = array();
	foreach($liste_evenements_tmp as $key_evt => $evt_tmp)
	{
		$droit_acces = droit_acces($objet["evenement"], $evt_tmp, false);
		if($type_sortie=="tout" || ($type_sortie=="lecture" && $droit_acces>0))
		{
			// Ajoute l'evenement au tableau de sortie avec son droit d'accès
			$liste_evenements[$key_evt] = $evt_tmp;
			$liste_evenements[$key_evt]["droit_acces"] = $droit_acces;
			// masque les détails si besoin : "evt public mais détails masqués"
			if($type_sortie=="lecture")  {$liste_evenements[$key_evt] = masque_details_evt($liste_evenements[$key_evt]);}
		}
	}
	return $liste_evenements;
}


////	MASQUE LES DETAILS D'UN EVENEMENT S'IL EST  "PUBLIC MAIS AVEC DETAILS MASQUES"
////
function masque_details_evt($evt_tmp)
{
	if($evt_tmp["droit_acces"]<1){
		global $trad;
		$evt_tmp["titre"] = "<i>".$trad["AGENDA_evt_prive"]."</i>";
		$evt_tmp["description"] = "";
	}
	return $evt_tmp;
}


////	AFFICHAGE DE LA PERIODICITE D'UN EVENEMENT
////
function periodicite_evt($evt_tmp)
{
	global $trad;
	////	Jours de la semaine
	if($evt_tmp["periodicite_type"]=="jour_semaine"){
		foreach(explode(",",$evt_tmp["periodicite_valeurs"]) as $jour)	{ @$txt_jours .= $trad["jour_".$jour].", "; }
		return $trad["AGENDA_period_jour_semaine"]." : ".trim($txt_jours,", ");
	}
	////	Jours du mois
	if($evt_tmp["periodicite_type"]=="jour_mois"){
		return $trad["AGENDA_period_jour_mois"]." : ".str_replace(",", ", ", $evt_tmp["periodicite_valeurs"]);
	}
	////	Mois
	if($evt_tmp["periodicite_type"]=="mois"){
		foreach(explode(",",$evt_tmp["periodicite_valeurs"]) as $mois)		{ @$txt_mois .= $trad["mois_".round($mois)].", "; }
		return $trad["AGENDA_period_mois"]." : ".$trad["le"]." ".strftime("%d",strtotime($evt_tmp["date_debut"]))." ".trim($txt_mois,", ");
	}
	////	Année
	if($evt_tmp["periodicite_type"]=="annee"){
		return $trad["AGENDA_period_annee"].", ".$trad["le"]." ".strftime("%d %B",strtotime($evt_tmp["date_debut"]));
	}
}


////	AGENDAS DANS LESQUELS EST AFFECTE UN EVENEMENT (PARMI LES AGENDAS D'AFFECTATIONS)
////
function agendas_evts($id_evenement, $evt_confirme)
{
	////	Liste des agendas ou est affecté l'événement
	global $AGENDAS_AFFECTATIONS;
	$tab_agenda = array();
	foreach(db_colonne("SELECT id_agenda FROM gt_agenda_jointure_evenement WHERE id_evenement=".$id_evenement." AND confirme='".$evt_confirme."'") as $id_agenda){
		if(isset($AGENDAS_AFFECTATIONS[$id_agenda]))  {$tab_agenda[] = $id_agenda;}
	}
	return $tab_agenda;
}


////	SUPPRESSION D'UN EVENEMENT ($id_agenda= "tous" / "id_agenda")
////
function suppr_evenement($id_evenement, $id_agenda, $date_suppr="")
{
	////	Init
	global $objet, $AGENDAS_AFFICHES;
	$evt_tmp = db_ligne("SELECT * FROM gt_agenda_evenement WHERE id_evenement='".intval($id_evenement)."'");
	$droit_acces = droit_acces($objet["evenement"],$id_evenement,false);
	$droit_acces_agenda = @$AGENDAS_AFFICHES[$id_agenda]["droit"];
	////	SUPPR A UNE DATE (créé "period_date_exception")  /  SUPPR DANS TOUS LES AGENDAS  /  SUPPR DANS UN AGENDA
	if($id_agenda=="tous" && $droit_acces==3 && $date_suppr!="")		{ db_query("UPDATE gt_agenda_evenement SET period_date_exception='".trim($evt_tmp["period_date_exception"]."@@".$date_suppr,"@@")."' WHERE id_evenement=".db_format($id_evenement)); }
	elseif($id_agenda=="tous" && $droit_acces==3)						{ db_query("DELETE FROM gt_agenda_jointure_evenement WHERE id_evenement=".db_format($id_evenement)); }
	elseif($id_agenda>0 && ($droit_acces==3 || $droit_acces_agenda>=2))	{ db_query("DELETE FROM gt_agenda_jointure_evenement WHERE id_evenement=".db_format($id_evenement)." AND id_agenda=".db_format($id_agenda)); }
	////	ON SUPPRIME L'ÉVÉNEMENT S'IL N'EST AFFECTÉ À AUCUN AGENDA + FICHIERS JOINTS
	if(db_valeur("SELECT count(*) FROM gt_agenda_jointure_evenement WHERE id_evenement='".intval($id_evenement)."'")==0){
		global $objet;
		suppr_objet($objet["evenement"], $id_evenement);
	}
}


////	SUPPRESSION D'UN AGENDA DE RESSOURCE =>  SI ACCES TOTAL A L'AGENDA + CONTIENT DES ÉVÉNEMENTS UNIQUEMENT(!) LIÉS A CET AGENDA
////
function suppr_agenda($id_agenda)
{
	global $objet;
	if(droit_acces($objet["agenda"],$id_agenda)==3)
	{
		$liste_evt = db_tableau("SELECT DISTINCT id_evenement FROM gt_agenda_jointure_evenement WHERE id_agenda='".intval($id_agenda)."' AND id_evenement NOT IN (select id_evenement from gt_agenda_jointure_evenement where id_agenda!='".intval($id_agenda)."')");
		foreach($liste_evt as $id_evt)	{ suppr_evenement($id_evt["id_evenement"],$id_agenda); }
		db_query("DELETE FROM gt_agenda_jointure_evenement WHERE id_agenda=".db_format($id_agenda));
		suppr_objet($objet["agenda"], $id_agenda);
	}
}


////	POSSIBILITE D'AJOUTER UN AGENDA DE RESSOURCE => ADMIN D'ESPACE OU USERS SI AUTORISE
////
function droit_ajout_agenda_ressource()
{
	if($_SESSION["espace"]["droit_acces"]==2 || ($_SESSION["user"]["id_utilisateur"]>0 && option_module("ajout_agenda_ressource_admin")!=true))	{return true;}
}


////	DROIT GESTION DES CATEGORIES => ADMIN D'ESPACE OU USERS SI AUTORISE
////
function droit_gestion_categories()
{
	if($_SESSION["espace"]["droit_acces"]==2 || ($_SESSION["user"]["id_utilisateur"]>0 && option_module("ajout_categorie_admin")!=true))	{return true;}
}


////	CATEGORIES DES EVENEMENTS & LEUR DROIT D'ACCES  ($mode = edition / lecture)
////
function categories_evt($mode="lecture")
{
	// Toutes les categories du site (admin général)  /  Categories affectées à l'espace courant
	$filtre_espace = ($mode=="edition" && $_SESSION["user"]["admin_general"]==1)  ?  ""  :  "WHERE id_espaces is null OR id_espaces LIKE '%@@".$_SESSION["espace"]["id_espace"]."@@%'";
	$liste_categories = db_tableau("SELECT * FROM gt_agenda_categorie ".$filtre_espace." ORDER BY titre", "id_categorie");
	// Ajoute le droit d'accès  (écriture si auteur ou admin général)
	foreach($liste_categories as $cle => $categorie){
		$liste_categories[$cle]["droit"] = (is_auteur($categorie["id_utilisateur"])==true || $_SESSION["user"]["admin_general"]==1)  ?  2  :  1;
	}
	return $liste_categories;
}


////	STYLE DU BLOCK "EVENEMENT"  =>  COULEUR DE LA CATEGORIE EN "BACKGROUND" / EN "BORDER"
////
function style_evt($agenda, $evt)
{
	if($agenda["evt_affichage_couleur"]=="background" && !isset($_GET["printmode"]))	{return STYLE_BORDER_RADIUS."box-shadow: 1px 1px 1px #777;color:#fff;background-color:".$evt["couleur_cat"].";";}
	else																				{return STYLE_BORDER_RADIUS."box-shadow: 1px 1px 1px #777;color:".$evt["couleur_cat"].";background-color:#fff;border:1px solid ".$evt["couleur_cat"].";background-image:url(".PATH_TPL."module_agenda/background.jpg);background-repeat:no-repeat;";}
}


////	IDENTIFIANT ICAL D'UN EVENEMENT
////
function ical_uid_evt($evt_tmp)
{
	return md5($evt_tmp["date_crea"].$evt_tmp["id_evenement"]);
}


////	POSSIBILITE DE PROPOSER ET/OU AFFECTER UN EVENEMENT A UN AGENDA ?
////
function agenda_proposer_affecter_evt($agenda_tmp, $evt_droit=3)
{
	////	AFFECTER UNIQUEMENT (mon agenda perso)
	if($agenda_tmp["type"]=="utilisateur" && is_auteur($agenda_tmp["id_utilisateur"]))																		{return "affecter";}
	////	PROPOSER OU AFFECTER  (écriture sur l'agenda OU écriture limité + proprio de l'evt)
	elseif($_SESSION["user"]["id_utilisateur"]>0  &&  ($agenda_tmp["droit"]>=2 || ($agenda_tmp["droit"]==1.5 && $evt_droit==3)))							{return "proposer_affecter";}
	////	PROPOSER UNIQUEMENT  (lecture sur l'agenda OU ecriture limité + invité)
	elseif(($_SESSION["user"]["id_utilisateur"]>0 && $agenda_tmp["droit"]<=1)  ||  ($_SESSION["user"]["id_utilisateur"]==0 && $agenda_tmp["droit"]==1.5))	{return "proposer";}
	////	SINON NADA...
	else																																					{return false;}
}


////	MENU D'AFFICHAGE (ET CONFIRMATION) DE PROPOSITION D'EVENEMENTS
////
function menu_proposition_evt()
{
	// Init
	global $trad, $AGENDAS_AFFECTATIONS;
	$menu_proposition_evt = "";
	////	AGENDAS ACCESSIBLE EN ECRITURE  (de ressource OU mon agendas perso)
	foreach($AGENDAS_AFFECTATIONS as $agenda_tmp)
	{
		if(($agenda_tmp["type"]=="ressource" && $agenda_tmp["droit"]>=2)  ||  ($agenda_tmp["type"]=="utilisateur" && is_auteur($agenda_tmp["id_utilisateur"])))
		{
			// Evenements de l'agenda : non confirmé et dont on est pas l'auteur !
			$evts_confirmer = db_tableau("SELECT  T1.*  FROM  gt_agenda_evenement T1, gt_agenda_jointure_evenement T2  WHERE  T1.id_evenement=T2.id_evenement  AND  T2.id_agenda='".$agenda_tmp["id_agenda"]."' AND T2.confirme='0' AND T1.id_utilisateur!='".$_SESSION["user"]["id_utilisateur"]."'");
			if(count($evts_confirmer)>0)
			{
				// Libelle de l'agenda
				$menu_proposition_evt .= "<div id='alerte_proposition".$agenda_tmp["id_agenda"]."' style='margin-top:10px;'>";
					$libelle_tmp = ($agenda_tmp["type"]=="utilisateur" && is_auteur($agenda_tmp["id_utilisateur"]))  ?  $trad["AGENDA_evenements_proposes_mon_agenda"]  :  $trad["AGENDA_evenements_proposes_pour_agenda"]." <i>".$agenda_tmp["titre"]."</i>";
					$menu_proposition_evt .= "<img src=\"".PATH_TPL."divers/important_small.png\" style='height:15px;vertical-align:middle;' /> <b>".$libelle_tmp."</b>";
					$menu_proposition_evt .= "<script type='text/javascript'> $(window).load(function(){ $('#alerte_proposition".$agenda_tmp["id_agenda"]."').effect('pulsate',{times:2},2000); }); </script>";
				$menu_proposition_evt .= "</div>";
				// Evénements à confirmer sur l'agenda
				$menu_proposition_evt .= "<ul style='margin:0px;margin-bottom:10px;padding:0px;'>";
				foreach($evts_confirmer as $evt_tmp){
					$libelle_infobulle = temps($evt_tmp["date_debut"],"complet",$evt_tmp["date_fin"])."<br>".$trad["AGENDA_evenement_propose_par"]." ".auteur(user_infos($evt_tmp["id_utilisateur"]),$evt_tmp["invite"])."<div style=margin-top:5px;>".$evt_tmp["description"]."</div>";
					$menu_proposition_evt .= "<li onClick=\"confirmer_propostion_evt('".$evt_tmp["id_evenement"]."','".$agenda_tmp["id_agenda"]."');\"  ".infobulle($libelle_infobulle)." class='lien' style='margin-left:30px;margin-top:5px;'>".$evt_tmp["titre"]."</li>";
				}
				$menu_proposition_evt .= "</ul>";
			}
		}
	}

	////	JAVASCRIPT : CONFIRMER OU PAS UNE PROPOSITION D'EVENEMENT
	if($menu_proposition_evt!="")
	{
		$menu_proposition_evt .=
			"<script type='text/javascript'>
			function confirmer_propostion_evt(id_evt, id_agenda)
			{
				page_redir = \"".ROOT_PATH."module_agenda/index.php?id_agenda=\"+id_agenda;
				if(confirm(\"".$trad["AGENDA_evenement_integrer"]."\"))				redir(page_redir+'&id_evt_confirm='+id_evt);
				else if(confirm(\"".$trad["AGENDA_evenement_pas_integrer"]."\"))	redir(page_redir+'&id_evt_noconfirm='+id_evt);
			}
			</script>";
	}

	////	RETOUR
	return $menu_proposition_evt;
}


////	EXPORT .ICAL  =>  FONCTIONS DE FORMATAGE DE DATE
////
function date_ical($date, $timezone=true)
{
	$date = strftime("%Y%m%dT%H%M%S", strtotime($date));
	if($timezone==true)		{return current_timezone().":".$date;}
	else					{return str_replace("T000000Z","T235959Z", $date."Z");}
}
function jour_ical($jour)
{
	$jours = array(1=>"MO",2=>"TU",3=>"WE",4=>"TH",5=>"FR",6=>"SA",7=>"SU");
	return $jours[$jour];
}
function heure_ical($heure, $decalage=0)
{
	// Exemple avec "-5.30"
	$negatif_positif = (intval($heure)<0)  ?  "-"  :  "+";	// "-"
	$minutes = substr($heure, strpos($heure,".")+1);		// "30"
	$heure_abs = str_replace("-", "", intval($heure));		// "5"
	$heure_abs += $decalage;								// Si $decalage=2 -> "7"
	if($heure_abs<10)	{$heure_abs = "0".$heure_abs;}		// "05"
	// Retourne "-0530"
	return $negatif_positif.$heure_abs.$minutes;
}


////	EXPORT .ICAL  =>  PREPARATION DU FICHIER DE SORTIE
////
function fichier_ical($liste_evenements)
{
	////	INIT
	global $tab_timezones, $AGENDAS_AFFECTATIONS;

	////	DEBUT DU ICAL
	$sortie  = "BEGIN:VCALENDAR\n";
	$sortie .= "PRODID:-//Agora-Project//".$_SESSION["agora"]["nom"]."//EN\n";
	$sortie .= "VERSION:2.0\n";
	$sortie .= "CALSCALE:GREGORIAN\n";
	$sortie .= "METHOD:PUBLISH\n";

	////	TIMEZONE
	$current_timezone = current_timezone();
	$heure_time_zone = $tab_timezones[$current_timezone];
	$sortie .= "BEGIN:VTIMEZONE\n";
	$sortie .= "TZID:".$current_timezone."\n";
	$sortie .= "X-LIC-LOCATION:".$current_timezone."\n";
	//Daylight
	$sortie .= "BEGIN:DAYLIGHT\n";
	$sortie .= "TZOFFSETFROM:".heure_ical($heure_time_zone)."\n";
	$sortie .= "TZOFFSETTO:".heure_ical($heure_time_zone,1)."\n";
	$sortie .= "TZNAME:CEST\n";
	$sortie .= "DTSTART:19700329T020000\n";
	$sortie .= "RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=-1SU;BYMONTH=3\n";
	$sortie .= "END:DAYLIGHT\n";
	//Standard
	$sortie .= "BEGIN:STANDARD\n";
	$sortie .= "TZOFFSETFROM:".heure_ical($heure_time_zone,1)."\n";
	$sortie .= "TZOFFSETTO:".heure_ical($heure_time_zone)."\n";
	$sortie .= "TZNAME:CET\n";
	$sortie .= "DTSTART:19701025T030000\n";
	$sortie .= "RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=-1SU;BYMONTH=10\n";
	$sortie .= "END:STANDARD\n";
	$sortie .= "END:VTIMEZONE\n";

	////	AJOUT DE CHAQUE EVENEMENT
	foreach($liste_evenements as $evt)
	{
		////	Description  &  agendas où est affecté l'événement
		$evt["description"] = strip_tags(str_replace("<br>"," ",$evt["description"]));
		$agendas = agendas_evts($evt["id_evenement"],"1");
		if(count($agendas)>1){
			$agendas_txt = "";
			foreach($agendas as $id_agenda)  { $agendas_txt .= $AGENDAS_AFFECTATIONS[$id_agenda]["titre"].", "; }
			$evt["description"] .= " [".substr(text_reduit($agendas_txt),0,-2)."]";
		}
		////	Affichage
		$sortie .= "BEGIN:VEVENT\n";
		$sortie .= "CREATED:".date_ical($evt["date_crea"],false)."\n";
		$sortie .= "LAST-MODIFIED:".date_ical($evt["date_crea"],false)."\n";
		$sortie .= "DTSTAMP:".date_ical(strftime("%Y-%m-%d %H:%M:%S"),false)."\n";
		$sortie .= "UID:".ical_uid_evt($evt)."\n";
		$sortie .= "SUMMARY:".$evt["titre"]."\n";
		$sortie .= "DTSTART;TZID=".date_ical($evt["date_debut"])."\n";  //exple : "19970714T170000Z" pour 14 juillet 1997 à 17h00
		$sortie .= "DTEND;TZID=".date_ical($evt["date_fin"])."\n";
		if($evt["id_categorie"]>0)		{$sortie .= "CATEGORIES:".db_valeur("SELECT titre FROM gt_agenda_categorie WHERE id_categorie='".$evt["id_categorie"]."'")."\n";}
		if($evt["description"]!="")		{$sortie .= "DESCRIPTION:".preg_replace("/\r\n/"," ",html_entity_decode(strip_tags($evt["description"])))."\n";}
		// Périodicité
		$period_date_fin = ($evt["period_date_fin"])  ?  ";UNTIL=".date_ical($evt["period_date_fin"],false)  :  "";
		if($evt["periodicite_type"]=="annee")				{$sortie .= "RRULE:FREQ=YEARLY;INTERVAL=1".$period_date_fin."\n";}
		elseif($evt["periodicite_type"]=="mois")			{$sortie .= "RRULE:FREQ=MONTHLY;INTERVAL=1;BYMONTHDAY=".trim(strftime("%e",strtotime($evt["date_debut"]))).$period_date_fin."\n";}
		elseif($evt["periodicite_type"]=="jour_mois")		{$sortie .= "RRULE:FREQ=MONTHLY;INTERVAL=1;BYMONTHDAY=".implode(",",array_map("abs",explode(",",$evt["periodicite_valeurs"]))).$period_date_fin."\n";}
		elseif($evt["periodicite_type"]=="jour_semaine")	{$sortie .= "RRULE:FREQ=WEEKLY;INTERVAL=1;BYDAY=".implode(",",array_map("jour_ical",explode(",",$evt["periodicite_valeurs"]))).$period_date_fin."\n";}
		$sortie .= "END:VEVENT\n";
	}

	////	FIN DU ICAL
	$sortie .= "END:VCALENDAR\n";
	return $sortie;
}


////	MENU DE FILTRAGE DES EVT PAR CATEGORIES (PAS ENREGISTRÉ DANS LES PREFERENCES CAR MASQUE DES RESULTATS..)
////
function menu_evt_filtre_categorie()
{
	////	INIT
	global $trad;
	$url_tmp = php_self().variables_get("filtre_categorie")."&filtre_categorie=";
	$liste_categories = categories_evt();
	////	AFFICHAGE
	$affichage = "<div class='menu_gauche_line lien' id='icone_menu_filtre_categorie'>";
		$affichage .= "<div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/filtre.png\" /></div>";
		$affichage .= "<div class='menu_gauche_txt'>".$trad["AGENDA_categorie"]." : ".libelle_categorie(@$liste_categories[$_REQUEST["filtre_categorie"]])."</div>";
	$affichage .= "</div>";
	$affichage .= "<div class='menu_context' id='menu_filtre_categorie'>";
		foreach($liste_categories as $categorie){
			$style_tmp = ($categorie["id_categorie"]==@$_REQUEST["filtre_categorie"])  ?  "lien_select"  :  "lien";
			$affichage .= "<a href=\"".$url_tmp.$categorie["id_categorie"]."\" class='".$style_tmp."'>".libelle_categorie($categorie)."</a><br><br>";
		}
		$affichage .= "<a href=\"".$url_tmp."\">&nbsp; <span style='border:#888 solid 1px;'>&nbsp; &nbsp; &nbsp;</span> &nbsp; <i>".$trad["tout_afficher"]."</i></a>";
	$affichage .= "</div>";
	$affichage .= "<script type='text/javascript'> menu_contextuel('menu_filtre_categorie'); </script>";
	return $affichage;
}
////	...ET LIBELLE DE CATEGORIE
function libelle_categorie($categorie)
{
	global $trad;
	if(!empty($categorie))	{return "&nbsp; <span style='background-color:".$categorie["couleur"]."'>&nbsp; &nbsp; &nbsp;</span> &nbsp; ".$categorie["titre"];}
	else					{return $trad["tout_afficher"];}
}
////	...ET FILTRAGE SQL
function sql_evt_filtre_categorie()
{
	if(!empty($_REQUEST["filtre_categorie"]))	{return "AND id_categorie=".db_format($_REQUEST["filtre_categorie"]);}
}
?>