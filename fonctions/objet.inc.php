<?php
////	INFOS SUR UN OBJET
////	$objet_infos =>  tableau d'infos déjà précisé (evite une requête)  OU  l'id_objet pour récupérer les infos
////	$champ =>  "*"  pour obtenir tous les champs  OU  "date_crea"  par exemple pour obtenir le champ en question
////
function objet_infos($obj_tmp, $objet_infos, $champ="*")
{
	// Retourne tout
	if($champ=="*"){
		if(is_array($objet_infos))	{return $objet_infos;}
		else						{return db_ligne("SELECT * FROM  ".$obj_tmp["table_objet"]." WHERE ".$obj_tmp["cle_id_objet"]."='".intval($objet_infos)."'");}
	}
	// Retourne juste un champ
	else{
		if(is_array($objet_infos))	{return $objet_infos[$champ]; }
		else						{return db_valeur("SELECT ".$champ." FROM ".$obj_tmp["table_objet"]." WHERE ".$obj_tmp["cle_id_objet"]."='".intval($objet_infos)."'");}
	}
}


////	SELECTION SQL D'OBJET EN FONCTION DE LA "TARGET" =>  "invites" / "tous" / "tous_espace" / "U1" / "G1"
////
function objet_targets()
{
	// Objet(s) pour les invités
	if($_SESSION["user"]["id_utilisateur"]<1)	{return "'invites'";}
	// Objet(s) affectés à tous les utilisateurs de l'espace courant ou à l'utilisateur courant ou à un des groupes de l'utilisateur courant
	else
	{
		$selection_target = "'tous','tous_espaces','U".$_SESSION["user"]["id_utilisateur"]."'";
		if(isset($_SESSION["espace"]["groupes_user_courant"])){
			foreach($_SESSION["espace"]["groupes_user_courant"] as $groupe_tmp)		{$selection_target.=",'G".$groupe_tmp["id_groupe"]."'";}
		}
		return $selection_target;
	}
}


////	DROIT D'ACCÈS A UN OBJET :  CONTENEUR (dossier, agenda, sujet, etc.)   /   CONTENU (evenement, actualité, fichier, etc.)
////
////	3	[ecriture total]			conteneur	-> accès total
////									contenu		-> accès total
////	2	[ecriture]					conteneur	-> lecture conteneur  +  ajout-modif-suppr tout le contenu
////									contenu		-> ajout-modif-suppr
////	1.5	[ecriture limité]			conteneur	-> lecture conteneur  +  ajout-modif-suppr le contenu qu'on a créé (c'est le droit maxi pour un invité!)
////									contenu		-> -pas dispo-
////	1	[lecture]					conteneur	-> lecture
////									contenu		-> lecture
////	0.5	[lecture limité des EVT]	conteneur	-> -pas dispo-
////									contenu		-> lecture de la plage horaire, mais détails de l'événement cachés
////
function droit_acces($obj_tmp, $objet_infos, $privilege_admin_espace=true)
{
	// Init
	$droit_acces = 0;
	$objet_infos = objet_infos($obj_tmp, $objet_infos);	//  id_objet -> Récup' toutes les infos sur l'objet
	if(count($objet_infos)==0)	{return 0;} // objet supprimé ?
	$id_objet = $objet_infos[$obj_tmp["cle_id_objet"]];

	////	AUTEUR ou ADMIN GENERAL : No limit
	if(is_auteur($objet_infos["id_utilisateur"]) || $_SESSION["user"]["admin_general"]==1){
		$droit_acces = 3;
	}
	////	DROIT SUR UN EVENEMENT (Dépend du droit d'accès aux agendas où il est affecté)
	elseif($obj_tmp["type_objet"]=="evenement")
	{
		global $AGENDAS_AFFICHES;
		$droit_agenda_max = 0;
		$agendas_tous_ecriture = true;
		foreach(db_colonne("SELECT DISTINCT id_agenda FROM gt_agenda_jointure_evenement WHERE id_evenement='".$objet_infos["id_evenement"]."'") as $id_agenda)
		{
			$droit_agenda_tmp = @$AGENDAS_AFFICHES[$id_agenda]["droit"];
			if($droit_agenda_tmp > $droit_agenda_max)	{$droit_agenda_max = $droit_agenda_tmp;}	// Droit de l'agenda > au droit maxi temporaire ?
			if($droit_agenda_tmp < 2)					{$agendas_tous_ecriture = false;}			// Tous les agenda en écriture ?
		}
		// Définition du droit d'accès
		if($agendas_tous_ecriture==true && $droit_agenda_max>=2)							{$droit_acces = 3;}		// Que des agendas accessible écriture
		elseif($droit_agenda_max>=2)														{$droit_acces = 2;}		// Au moins 1 agenda en écriture
		elseif($droit_agenda_max>=1 && $objet_infos["visibilite_contenu"]=="public")		{$droit_acces = 1;}		// Au moins 1 agenda en lecture/ecriture limité
		elseif($droit_agenda_max>=1 && $objet_infos["visibilite_contenu"]=="public_cache")	{$droit_acces = 0.5;}	// Au moins 1 agenda en lecture/ecriture limité  +  Lecture plage horaire uniquement
	}
	////	DROIT LE PLUS IMPORTANT DES AFFECTATIONS
	else
	{
		// OBJET DEPENDANT D'UN CONTENEUR (sauf dossier racine) -> ON PREND LES DROITS DU CONTENEUR
		if(objet_independant($obj_tmp,$objet_infos)==false){
			global $objet;
			$id_objet = $objet_infos[$obj_tmp["cle_id_conteneur"]];
			$obj_tmp = $objet[$obj_tmp["type_conteneur"]];
		}
		// DROIT MAXI AFFECTE A L'OBJET
		$droit_acces = db_valeur("SELECT max(droit) FROM gt_jointure_objet WHERE type_objet='".$obj_tmp["type_objet"]."' AND id_objet='".intval($id_objet)."' AND (id_espace='".$_SESSION["espace"]["id_espace"]."' or id_espace is null) AND target IN (".objet_targets().")");
		// DROIT DU DOSSIER RACINE ENCORE INDEFINI : utilisateurs=écriture / visiteurs=lecture
		if($droit_acces<1 && is_dossier_racine($obj_tmp,$id_objet))		{$droit_acces = ($_SESSION["user"]["id_utilisateur"]>0)  ?  2  :  1;}
		// ADMIN DE L'ESPACE -> TOUS LES DROITS ?
		if($_SESSION["espace"]["droit_acces"]==2 && $privilege_admin_espace==true){
			// Si l'objet lui est affecté en écriture  OU  est affecté uniquement à l'espace courant  ->  tous les droits
			if($droit_acces>=2 || db_valeur("SELECT count(distinct id_espace) FROM gt_jointure_objet WHERE type_objet='".$obj_tmp["type_objet"]."' AND id_objet='".intval($id_objet)."' AND (id_espace!='".$_SESSION["espace"]["id_espace"]."' or id_espace is null)")==0)
				{$droit_acces = 3;}
		}
	}

	////	RETOUR
	return $droit_acces;
}


////	CONTROLE LE DROIT D'ACCÈS A UN OBJET
////
function droit_acces_controler($obj_tmp, $objet_infos, $droit_acces_minimum)
{
	$droit_acces = droit_acces($obj_tmp, $objet_infos);
	// ON RETOURNE LE DROIT D'ACCES
	if($droit_acces >= $droit_acces_minimum)	{ return $droit_acces; }
	// ALERTE + ARRETE LE SCRIPT
	else{
		global $trad;
		echo $trad["elem_inaccessible"];
		exit();
	}
}


////	PRESELECTION D'OBJETS DE L'ESPACE COURANT
////
function sql_affichage($obj_tmp, $id_dossier="", $table_alias="")
{
	// Objet indépendant : pas dans un dossier ou dans le dossier racine
	if($id_dossier<=1)
	{
		////	AFFECTES A L'USER COURANT (normal)  /  CREES PAR L'USER COURANT (auteur)  /  TOUS (admin -> tous les objets de l'espace)
		$select_normal = $select_auteur = "";
		if($_SESSION["cfg"]["espace"]["affichage_objet"]=="normal")		{ $select_normal = " AND target IN (".objet_targets().")"; }
		elseif($_SESSION["cfg"]["espace"]["affichage_objet"]=="auteur")	{ $select_auteur = " AND ".$table_alias."id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."' AND ".$table_alias."id_utilisateur > 0"; }
		////	OBJETS AFFECTES A L'ESPACE +  QUI ME SONT ACCESSIBLES ? (affichage_objet="normal")
		$id_objets_espace = "'0',".db_valeur("SELECT GROUP_CONCAT(DISTINCT id_objet) FROM gt_jointure_objet WHERE type_objet='".$obj_tmp["type_objet"]."' AND (id_espace='".$_SESSION["espace"]["id_espace"]."' or id_espace is null) ".$select_normal);
		////	Retour
		return $select_auteur." AND ".$table_alias.$obj_tmp["cle_id_objet"]." IN (".trim($id_objets_espace,",").")";
	}
}


////	SELECTION SQL POUR LISTER LES OBJETS D'UNE ARBORESCENCE
////
function sql_affichage_objets_arbo($obj_tmp)
{
	global $objet;
	// Sélectionne les objets accessibles à la racine  &  dans les dossiers accessibles (sauf dossier racine..)
	return "(  (1 ".sql_affichage($obj_tmp).")  OR  (id_dossier > 1 ".sql_affichage($objet[$obj_tmp["type_conteneur"]]).") )";
}


////	SELECTION D'ELEMENTS DES PLUGINS  :  NOUVELLEMENTS CREES  /  AYANT UN RACCOURCIS  /   RECHERCHES
////
function sql_selection_plugins($obj_tmp, $table_alias="")
{
	global $cfg_plugin;
	////	ELEMENTS CREES DANS LA PERIODE SELECTIONNE
	if($cfg_plugin["mode"]=="nouveautes")		{ return " AND ".$table_alias."date_crea BETWEEN '".$cfg_plugin["debut"]."' and '".$cfg_plugin["fin"]."' "; }
	////	ELEMENTS AYANT UN RACCOURCIS
	elseif($cfg_plugin["mode"]=="raccourcis")	{ return " AND ".$table_alias."raccourci='1' "; }
	////	ELEMENTS RECHERCHES
	elseif($cfg_plugin["mode"]=="recherche")
	{
		////	INIT
		$sql_recherche = "";
		$texte_recherche =  suppr_carac_spe($_POST["texte_recherche"],"normale"," ");

		////	RECHERCHE DANS TOUS LES CHAMPS DE L'OBJET, OU UNIQUEMENT CEUX DEMANDES
		if(empty($_POST["champs_recherche"]))	{$champs_recherche = $obj_tmp["champs_recherche"];}
		else{
			$champs_recherche = array();
			foreach($obj_tmp["champs_recherche"] as $champ_tmp)		{ if(in_array($champ_tmp,$_POST["champs_recherche"])) {$champs_recherche[] = $champ_tmp;} }
		}

		////	RECHERCHE SUR LE TEXTE
		// RECHERCHE L'EXPRESSION EXACTE   =>  titre LIKE '%mot1 mot2%' OR description LIKE '%mot1 mot2%'
		if($_POST["mode_recherche"]=="expression_exacte")
		{
			foreach($champs_recherche as $champ)	{ $sql_recherche .= "OR ".$table_alias.$champ." LIKE '%".db_format($texte_recherche,"slash,noquotes")."%' "; }
		}
		// RECHERCHE DANS CHAQUE CHAMP : AU MOINS 1 MOT / TOUS LES MOTS  =>  (titre LIKE '%mot1' OR titre LIKE '%mot2') OR (description LIKE '%mot1' OR description LIKE '%mot2')  -OU-  (titre LIKE '%mot1' AND titre LIKE '%mot2') OR (description LIKE '%mot1' AND description LIKE '%mot2')
		else
		{
			$mots_recherche = explode(" ", $texte_recherche);
			$operateur_liaison = (@$_POST["mode_recherche"]=="mots_tous")  ?  "AND"  :  "OR";
			foreach($champs_recherche as $champ)
			{
				$sql_recherche2 = "";
				foreach($mots_recherche as $mot)	{ $sql_recherche2 .= $operateur_liaison." ".$table_alias.$champ." LIKE '%".db_format($mot,"slash,noquotes")."%' "; }
				$sql_recherche .= "OR (".trim($sql_recherche2,$operateur_liaison).") ";
			}
		}
		// Mets en session le texte / les mots pour la mise en valeur des résultats
		$_SESSION["texte_recherche"] = (isset($mots_recherche))  ?  $mots_recherche  :  $texte_recherche;
		// Retourne le resultat (si aucune sélection : erreur de concordance module / champ)
		if($sql_recherche=="")	{ $sql_recherche = " AND 0 "; }
		else					{ $sql_recherche = " AND (".trim($sql_recherche,"OR").") ";  $_POST["concordance_modules_champs"] = true; }

		////	RECHERCHE SUR LA DATE DE CREATION
		if($_POST["date_crea"]!="tous")
		{
			if($_POST["date_crea"]=="jour")		{$date_limit = time() - 86400;}
			if($_POST["date_crea"]=="semaine")	{$date_limit = time() - (86400*7);}
			if($_POST["date_crea"]=="mois")		{$date_limit = time() - (86400*31);}
			if($_POST["date_crea"]=="annee")	{$date_limit = time() - (86400*365);}
			$sql_recherche .= " AND ".$table_alias."date_crea BETWEEN '".strftime("%Y-%m-%d %H:%M",$date_limit)."' and '".strftime("%Y-%m-%d %H:%M")."' ";
		}

		////	RETOURNE LE RESULTAT
		return $sql_recherche;
	}
}


////	ESPACES / GROUPES / UTILISATEURS AFFECTES A UN OBJET : LECTURE=1 / ECRITURE LIMITE=1.5 / ECRITURE=2
////
function objet_affectations($obj_tmp, $id_objet, $target, $droit_acces="")
{
	////	Présélection de l'objet et du droit demandé
	$selection_base = "AND T2.type_objet='".$obj_tmp["type_objet"]."' AND T2.id_objet=".$id_objet;
	if($droit_acces!="")	{$selection_base .= " AND T2.droit='".$droit_acces."'";}
	////	Retourne une liste d'espaces / groupes / utilisateurs
	if($target=="invites")			{return db_tableau("SELECT  DISTINCT T1.*, T2.droit  FROM  gt_espace T1, gt_jointure_objet T2  WHERE  T1.id_espace=T2.id_espace  AND  T2.target='invites'  ".$selection_base,  "id_espace");}		//"id_espace" comme cle pour une vérif rapide
	elseif($target=="espaces")		{return db_tableau("SELECT  DISTINCT T1.*, T2.droit  FROM  gt_espace T1, gt_jointure_objet T2  WHERE  T1.id_espace=T2.id_espace  AND  T2.target='tous'  ".$selection_base,  "id_espace");}		//IDEM
	elseif($target=="groupes")		{return db_tableau("SELECT  DISTINCT T1.*, T2.id_espace, T2.droit  FROM  gt_utilisateur_groupe T1, gt_jointure_objet T2  WHERE  CONCAT('G',T1.id_groupe)=T2.target  ".$selection_base);}
	elseif($target=="users")		{return db_tableau("SELECT  DISTINCT T1.*, T2.id_espace, T2.droit  FROM  gt_utilisateur T1, gt_jointure_objet T2  WHERE  CONCAT('U',T1.id_utilisateur)=T2.target  ".$selection_base);}
	elseif($target=="tous_espaces")	{return db_tableau("SELECT DISTINCT T2.* FROM gt_jointure_objet T2 WHERE T2.target='tous_espaces' ".$selection_base);}
}


////	TEXTE POUR LES AFFECTATIONS  (element_menu_contextuel.inc.php)
////
function txt_affectations($droit, $libelle_target)
{
	global $txt_ecriture, $txt_ecriture_limit, $txt_lecture;
	if($droit>=2)			{$txt_ecriture .= $libelle_target;}
	elseif($droit==1.5)		{$txt_ecriture_limit .= $libelle_target;}
	else					{$txt_lecture  .= $libelle_target;}
}


////	UTILISATEURS AYANT ACCES A UN OBJET (POUR LES ENVOIS DE NOTIF PAR MAIL...)
////
function users_affectes($obj_tmp, $id_objet, $action="notif_mail")
{
	////	Notification par mail & destinataires selectionnés
	if(isset($_POST["notif_destinataires"]) && count(@$_POST["notif_destinataires"])>0 && $action=="notif_mail"){
		return $_POST["notif_destinataires"];
	}
	////	Sélection normale
	else
	{
		// Element dépendant (dans un conteneur, sauf dossier racine) : récupère les droits du conteneur
		if(objet_independant($obj_tmp,$id_objet)==false){
			global $objet;
			$id_objet = objet_infos($obj_tmp, $id_objet, $obj_tmp["cle_id_conteneur"]);
			$obj_tmp  = $objet[$obj_tmp["type_conteneur"]];
		}
		// Liste des id_utilisateur affectés à un objet
		$liste_id_users = array();
		foreach(objet_affectations($obj_tmp,$id_objet,"espaces") as $espace_tmp)	{ $liste_id_users = array_merge($liste_id_users, users_espace($espace_tmp["id_espace"])); }
		foreach(objet_affectations($obj_tmp,$id_objet,"groupes") as $groupe_tmp)	{ $liste_id_users = array_merge($liste_id_users, text2tab($groupe_tmp["id_utilisateurs"])); }
		foreach(objet_affectations($obj_tmp,$id_objet,"users") as $user_tmp)		{ $liste_id_users[] = $user_tmp["id_utilisateur"]; }
		return array_unique($liste_id_users);
	}
}


////	AUTEUR D'UN OBJET (PRÉSENT SUR LE SITE / INVITE DU SITE / PERSONNE INCONNUE)
////
function auteur($infos_user, $invite="")
{
	global $trad;
	if(is_array($infos_user)==false)	{$infos_user = user_infos($infos_user);}
	if(@$infos_user["id_utilisateur"]>0)	{ return $infos_user["prenom"]." ".$infos_user["nom"]; }
	elseif($invite!="")						{ return $invite." (".$trad["invite"].") "; }
	else									{ return $trad["inconnu"]; }
}


////	AUTEUR D'UN OBJET ?
////
function is_auteur($id_utilisateur)
{
	if($id_utilisateur==$_SESSION["user"]["id_utilisateur"] && $_SESSION["user"]["id_utilisateur"] > 0)		{return true;}
	else																									{return false;}
}


////	INDEX INIT ID_DOSSIER
////
function init_id_dossier()
{
	if(!isset($_GET["id_dossier"]))	{$_GET["id_dossier"] = 1;}
	else							{$_GET["id_dossier"] = intval($_GET["id_dossier"]);}
}


////	DOSSIER RACINE ?
////
function is_dossier_racine($obj_tmp, $id_objet)
{
	if($id_objet==1 && preg_match("/dossier/i",$obj_tmp["type_objet"]))	{return true;}
	else																{return false;}
}


////	ELEMENT INDEPENDANT ?   (CONTENEUR / CONTENU PAS DANS UN CONTENEUR / CONTENEU DANS UN DOSSIER RACINE (y compris nouveau objets))
////
function objet_independant($obj_tmp, $objet_infos)
{
	$objet_infos = objet_infos($obj_tmp, $objet_infos); // Récupère tout si on a que l'id_objet
	if(!isset($obj_tmp["type_conteneur"])  ||  (isset($obj_tmp["type_conteneur"]) && (@$objet_infos["id_dossier"]==1 || (count($objet_infos)==0 && @$_REQUEST["id_dossier"]==1))))	{return true;}
	else																																											{return false;}
}


////	ARBORESCENCE DE DOSSIERS
////
function arborescence($objet_dossier, $id_dossier_courant=1, $acces="lecture", $niveau=1, $initialiser=true)
{
	////	Dossier de départ (racine ou branche)
	global $tab_arbo_tmp, $trad;
	if($initialiser==true)
	{
		$objet_depart = objet_infos($objet_dossier,$id_dossier_courant);
		$objet_depart["droit_acces"] = droit_acces($objet_dossier,$objet_depart);
		$objet_depart["niveau"] = 0;
		if(is_dossier_racine($objet_dossier,$id_dossier_courant)==true) 	{$objet_depart["nom"] = $trad["dossier_racine"];}
		$tab_arbo_tmp = array($objet_depart);
	}
	////	Ajout des dossiers enfants  +  lancement recursif
	foreach(db_tableau("SELECT DISTINCT * FROM ".$objet_dossier["table_objet"]." WHERE id_dossier_parent='".intval($id_dossier_courant)."' ".sql_affichage($objet_dossier)." ORDER BY nom asc")  as  $infos_enfant)
	{
		// Récupère tous / ceux accessible en lecture / ceux en accès écriture (3=accès total)
		$droit_acces_enfant = droit_acces($objet_dossier,$infos_enfant);
		if($acces=="tous"  ||  ($acces=="lecture" && $droit_acces_enfant > 0)  ||  ($acces=="ecriture" && $droit_acces_enfant==3))
		{
			$infos_enfant["droit_acces"] = $droit_acces_enfant;
			$infos_enfant["niveau"] = $niveau;
			$tab_arbo_tmp[] = $infos_enfant;
			arborescence($objet_dossier, $infos_enfant["id_dossier"], $acces, $niveau+1, false);
		}
	}
	////	On retourne le résultat final
	return $tab_arbo_tmp;
}


////	CHEMIN D'UN DOSSIER ($TYPE = URL REELLE / URL VIRTUELLE / TABLEAU)
////
function chemin($obj_tmp, $id_dossier, $type="url", $initialiser=true)
{
	////	Initialisation
	global $trad, $tab_chemin_tmp;
	if($initialiser==true)  {$tab_chemin_tmp = array();}
	////	On ajoute l'objet courant s'il existe
	$objet_infos = objet_infos($obj_tmp, $id_dossier);
	if(count($objet_infos)>0)
	{
		////	Ajoute le dossier
		if(is_dossier_racine($obj_tmp,$id_dossier)==true)	{$objet_infos["nom"] = $trad["dossier_racine"];}
		$tab_chemin_tmp[] = $objet_infos;
		////	Lance récursivement la fonction si on est pas encore à la racine
		if(is_dossier_racine($obj_tmp,$id_dossier)==false)	{chemin($obj_tmp, $objet_infos["id_dossier_parent"], $type, false);}
		////	Retourne tableau / texte
		$tab_chemin = array_reverse($tab_chemin_tmp);
		if($type=="tableau") 	{ return $tab_chemin; }
		else
		{
			$chaine_chemin = "";
			foreach($tab_chemin as $infos_dossier)
			{
				if($type=="url_virtuelle")				{ $chaine_chemin .= $infos_dossier["nom"]." / "; }
				if($type=="url_zip")					{ $chaine_chemin .= suppr_carac_spe($infos_dossier["nom"],"faible")."/"; }
				elseif(@$infos_dossier["nom_reel"]!="")	{ $chaine_chemin .= $infos_dossier["nom_reel"]."/"; }
			}
			if($type=="url_virtuelle")	{$chaine_chemin = substr($chaine_chemin,0,-2);}
			return $chaine_chemin;
		}
	}
}


////	RETOURNE L'ID_ESPACE  /  L'ID_USER  /  L'ID_GROUPE  D'UNE CHECKBOX
////
function affectation_id($valeur_box, $type)
{
	// "E22_U55"   =>   22 = id_espace   &   U55 = id_utilisateur
	// "E22_G55"   =>   22 = id_espace   &   G55 = id_groupe
	$valeur_box = str_replace("E", "", $valeur_box);
	$valeur_box = explode("_", $valeur_box);
	// Retourne "id_espace" ou "id_utilisateur" ?
	if($type=="espace")	{return $valeur_box[0];}
	else				{return $valeur_box[1];}
}


////	AFFECTATION DES DROITS D'ACCES A L'OBJET :  INVITE / TOUS USERS / GROUPE / UTILISATEUR
////
function affecter_droits_acces($obj_tmp, $id_objet, $invite_droit_par_defaut=1)
{
	if($id_objet > 0 && objet_independant($obj_tmp,$id_objet)==true)
	{
		// RÉINITIALISE LES DROITS POUR LES ESPACES AUQUELS ON A ACCES
		$sql_espaces = "";
		foreach(espaces_affectes_user() as $espace_tmp)  { $sql_espaces .= "id_espace='".$espace_tmp["id_espace"]."' OR "; }
		db_query("DELETE FROM gt_jointure_objet WHERE type_objet='".$obj_tmp["type_objet"]."' AND id_objet='".intval($id_objet)."' AND (".substr($sql_espaces,0,-3).")");

		// RÉINITIALISE LES DROITS POUR TOUS LES ESPACES
		if($_SESSION["user"]["admin_general"]==1)	{db_query("DELETE FROM gt_jointure_objet WHERE type_objet='".$obj_tmp["type_objet"]."' AND id_objet='".intval($id_objet)."' AND target='tous_espaces'");}

		// PREPARE LA REQUETE D'INSERTION
		$sql_insertion = "INSERT INTO gt_jointure_objet SET type_objet='".$obj_tmp["type_objet"]."', id_objet='".intval($id_objet)."',";

		// OBJET CRÉÉ PAR UN INVITE : affectation aux invités et aux users de l'espace (lecture par defaut)
		if(isset($_POST["invite"])){
			db_query($sql_insertion." id_espace='".$_SESSION["espace"]["id_espace"]."', target='invites', droit='".$invite_droit_par_defaut."'");
			db_query($sql_insertion." id_espace='".$_SESSION["espace"]["id_espace"]."', target='tous', droit='".$invite_droit_par_defaut."'");
		}
		// OBJET PARTAGÉ
		else
		{
			////	ACCES LECTURE
			if(isset($_POST["lecture_invites"]))		{   foreach($_POST["lecture_invites"] as $id_espace)		{ db_query($sql_insertion." id_espace=".db_format($id_espace).", target='invites', droit='1'"); }   }
			if(isset($_POST["lecture_espaces"]))		{   foreach($_POST["lecture_espaces"] as $id_espace)		{ db_query($sql_insertion." id_espace=".db_format($id_espace).", target='tous', droit='1'"); }   }
			if(isset($_POST["lecture_groupes"]))		{   foreach($_POST["lecture_groupes"] as $id_espace_groupe)	{ db_query($sql_insertion." id_espace='".affectation_id($id_espace_groupe,"espace")."', target='".affectation_id($id_espace_groupe,"groupe")."', droit='1'"); }   }
			if(isset($_POST["lecture_users"]))			{   foreach($_POST["lecture_users"] as $id_espace_user)		{ db_query($sql_insertion." id_espace='".affectation_id($id_espace_user,"espace")."', target='".affectation_id($id_espace_user,"user")."', droit='1'"); }   }
			if(isset($_POST["lecture_tous_espaces"]))	{   db_query($sql_insertion." id_espace=null, target='tous_espaces', droit='1'"); }

			////	ACCES ECRITURE LIMITE
			if(isset($_POST["ecriture_limit_invites"]))			{   foreach($_POST["ecriture_limit_invites"] as $id_espace)			{ db_query($sql_insertion." id_espace=".db_format($id_espace).", target='invites', droit='1.5'"); }   }
			if(isset($_POST["ecriture_limit_espaces"]))			{   foreach($_POST["ecriture_limit_espaces"] as $id_espace)			{ db_query($sql_insertion." id_espace=".db_format($id_espace).", target='tous', droit='1.5'"); }   }
			if(isset($_POST["ecriture_limit_groupes"]))			{   foreach($_POST["ecriture_limit_groupes"] as $id_espace_groupe)	{ db_query($sql_insertion." id_espace='".affectation_id($id_espace_groupe,"espace")."', target='".affectation_id($id_espace_groupe,"groupe")."', droit='1.5'"); }   }
			if(isset($_POST["ecriture_limit_users"]))			{   foreach($_POST["ecriture_limit_users"] as $id_espace_user)		{ db_query($sql_insertion." id_espace='".affectation_id($id_espace_user,"espace")."', target='".affectation_id($id_espace_user,"user")."', droit='1.5'"); }   }
			if(isset($_POST["ecriture_limit_tous_espaces"]))	{   db_query($sql_insertion." id_espace=null, target='tous_espaces', droit='1.5'"); }

			////	ACCES ECRITURE (pas pour les invites..)
			if(isset($_POST["ecriture_espaces"]))		{   foreach($_POST["ecriture_espaces"] as $id_espace)			{ db_query($sql_insertion." id_espace=".db_format($id_espace).", target='tous', droit='2'"); }   }
			if(isset($_POST["ecriture_groupes"]))		{   foreach($_POST["ecriture_groupes"] as $id_espace_groupe)	{ db_query($sql_insertion." id_espace='".affectation_id($id_espace_groupe,"espace")."', target='".affectation_id($id_espace_groupe,"groupe")."', droit='2'"); }   }
			if(isset($_POST["ecriture_users"]))			{   foreach($_POST["ecriture_users"] as $id_espace_user)		{ db_query($sql_insertion." id_espace='".affectation_id($id_espace_user,"espace")."', target='".affectation_id($id_espace_user,"user")."', droit='2'"); }   }
			if(isset($_POST["ecriture_tous_espaces"]))	{   db_query($sql_insertion." id_espace=null, target='tous_espaces', droit='2'"); }
		}
	}
}


////	DEPLACE UN OBJET DE TYPE "CONTENU" VERS LA RACINE : RECUPERE LES DROITS DE L'ANCIEN DOSSIER
////
function racine_copie_droits_acces($obj_tmp, $id_objet, $objet_dossier, $id_dossier_destination)
{
	// On réinitialise les droits de l'objet
	db_query("DELETE FROM gt_jointure_objet WHERE type_objet='".$obj_tmp["type_objet"]."' AND id_objet='".intval($id_objet)."'");
	// Déplace à la racine : devient indépendant et récupère les droits d'accès de l'ancien dossier conteneur
	if(is_dossier_racine($objet_dossier,$id_dossier_destination))
	{
		// Droits à dupliquer
		$droits_acces_modele = db_tableau("SELECT * FROM gt_jointure_objet WHERE type_objet='".$objet_dossier["type_objet"]."' AND id_objet='".objet_infos($obj_tmp,$id_objet,"id_dossier")."'");
		// On applique les droits d'accès
		foreach($droits_acces_modele as $droit)
		{
			if($droit["droit"]==1.5)	{$droit["droit"] = 1;}
			db_query("INSERT INTO gt_jointure_objet SET type_objet='".$obj_tmp["type_objet"]."', id_objet='".intval($id_objet)."', id_espace='".$droit["id_espace"]."', target='".$droit["target"]."', droit='".$droit["droit"]."'");
		}
	}
}


////	CONTROLE DU DEPLACEMENT D'UN DOSSIER : NE PAS LE DEPLACER A L'INTERIEUR DE LUI MEME !
////
function controle_deplacement_dossier($obj_tmp, $id_dossier, $id_dossier_destination)
{
	$controle_ok = 1;
	foreach(chemin($obj_tmp,$id_dossier_destination,"tableau") as $infos_sous_dossier){
		if($id_dossier==$infos_sous_dossier["id_dossier"])  {$controle_ok = 0;}
	}
	if($controle_ok==0){
		global $trad;
		alert($trad["MSG_ALERTE_deplacement_dossier"]);
	}
	return $controle_ok;
}


////	SUPPRESSION D'UN OBJET  +  DES JOINTURES SUR LES DROITS D'ACCES (auquel cas)  +  DES FICHIERS JOINTS (auquel cas)
////
function suppr_objet($obj_tmp, $id_objet)
{
	if($id_objet>0 && isset($obj_tmp["cle_id_objet"]))
	{
		// Ajout dans les logs
		add_logs("suppr", $obj_tmp, $id_objet);
		// Suppr fichiers joints
		$fichiers_joint = db_tableau("SELECT * FROM gt_jointure_objet_fichier WHERE type_objet='".$obj_tmp["type_objet"]."' AND id_objet='".intval($id_objet)."'");
		foreach($fichiers_joint as $fichier_joint)	{ suppr_fichier_joint($fichier_joint["id_fichier"],$fichier_joint["nom_fichier"]); }
		// Suppr jointure des droits  +  supprime les logs de mofif  +  supprime l'element lui-même !
		db_query("DELETE FROM gt_jointure_objet WHERE type_objet='".$obj_tmp["type_objet"]."' AND id_objet='".intval($id_objet)."'");
		db_query("DELETE FROM  gt_logs  WHERE  action not like '%suppr%'  AND type_objet='".$obj_tmp["type_objet"]."' AND id_objet='".intval($id_objet)."'");
		db_query("DELETE FROM ".$obj_tmp["table_objet"]." WHERE ".$obj_tmp["cle_id_objet"]."='".intval($id_objet)."'");
	}
}


////	RECUP DES ELEMENTS SELECTIONNES ($_GET["SelectedElems"] OU $POST["SelectedElems"] -> $_REQUEST["SelectedElems"])
////
function SelectedElemsArray($type_objet)
{
	if(!empty($_REQUEST["SelectedElems"][$type_objet]))		{return explode("-",$_REQUEST["SelectedElems"][$type_objet]);}
	else													{return array();}
}


////	PATCH DOSSIER RACINE  (vérifie s'il a été effacé de la bdd)
////
function patch_dossier_racine($obj_tmp)
{
	if(db_valeur("SELECT count(*) FROM ".$obj_tmp["table_objet"]." WHERE ".$obj_tmp["cle_id_objet"]."='1'")==0)
		{db_query("INSERT INTO ".$obj_tmp["table_objet"]." SET id_dossier='1', id_dossier_parent='0', id_utilisateur='0'");}
}


////	LARGEUR DE CHAQUE ELEMENT =>  largeur page  -300px(largeur menu gauche)  -7px(marge de chaque element)  -10px(ascenceur)
////
function width_element($nb_elems_ligne)
{
	global $nb_elements_ligne;
	$nb_elements_ligne = $nb_elems_ligne; //Initialise la valeur
	return floor((@$_SESSION["cfg"]["resolution_width"] - LARGEUR_MENU_GAUCHE - (8 * $nb_elems_ligne) - 10) / $nb_elems_ligne)."px";
}


////	INITIALISATION DE LA TAILLE DES ELEMENTS  =>   $width_init  = "100%" / "large" / "medium" / "small"
////
function elements_width_height_type_affichage($width_init, $height_init_px, $type_affichage_defaut, $force_height_affichage_liste=false)
{
	////	INIT
	global $width_element, $height_element, $nb_elements_ligne;

	////	AFFICHAGE "BLOCK" ou "LISTE" ou "ARBORESCENCE" (messages du forum)  =>  RECUP' LA PREFERENCE EN BDD, SINON AFFICHAGE PAR DEFAUT
	$cle_pref_db = "type_affichage_".MODULE_NOM;
	if(isset($_GET["id_dossier"]))	{$cle_pref_db .= "_".$_GET["id_dossier"];}
	pref_user($cle_pref_db, "type_affichage");//recupère la valeure en "$_REQUEST"
	if(empty($_REQUEST["type_affichage"]))	{$_REQUEST["type_affichage"] = $type_affichage_defaut;}

	////	AFFICHAGE "LISTE" / "ARBO"
	if(@$_REQUEST["type_affichage"]=="liste" || @$_REQUEST["type_affichage"]=="arbo")
	{
		$nb_elements_ligne = 1; //Initialise la valeur
		$width_element = "99%"; // Prend en compte l'ombre portée
		$height_element = ($force_height_affichage_liste==true)  ?  $height_init_px  :  "45px";
	}
	////	AFFICHAGE "BLOCK"
	else
	{
		// Hauteur de l'element
		$height_element = $height_init_px;
		// 2560  /  1920  /  1600-1680  /  1330-1440 / 1280 / 1024
		if(@$_SESSION["cfg"]["resolution_width"]>=2560){
			if($width_init=="small")		{$width_element = width_element(10);}
			elseif($width_init=="medium")	{$width_element = width_element(6);}
			else							{$width_element = width_element(4);}
		}
		elseif(@$_SESSION["cfg"]["resolution_width"]>=1900){
			if($width_init=="small")		{$width_element = width_element(8);}
			elseif($width_init=="medium")	{$width_element = width_element(5);}
			else							{$width_element = width_element(3);}
		}
		elseif(@$_SESSION["cfg"]["resolution_width"]>=1600){
			if($width_init=="small")		{$width_element = width_element(7);}
			elseif($width_init=="medium")	{$width_element = width_element(4);}
			else							{$width_element = width_element(2);}
		}
		elseif(@$_SESSION["cfg"]["resolution_width"]>=1440){
			if($width_init=="small")		{$width_element = width_element(6);}
			elseif($width_init=="medium")	{$width_element = width_element(4);}
			else							{$width_element = width_element(2);}
		}
		elseif(@$_SESSION["cfg"]["resolution_width"]>=1280){
			if($width_init=="small")		{$width_element = width_element(5);}
			elseif($width_init=="medium")	{$width_element = width_element(3);}
			else							{$width_element = width_element(2);}
		}
		else{
			if($width_init=="small")		{$width_element = width_element(4);}
			elseif($width_init=="medium")	{$width_element = width_element(2);}
			else							{$width_element = width_element(1);}
		}
	}
}


////	DIV & CHECKBOX DE SELECTION D'ELEMENT
////
function div_element($obj_tmp, $id_objet)
{
	// Init
	global $cpt_div_element, $width_element, $height_element;
	$cpt_div_element ++;
	$id_div_element = "div_elem_".$cpt_div_element;
	// Style
	$style = "width:".$width_element.";height:".$height_element.";cursor:url(".PATH_TPL."divers/check.png),crosshair;";
	if(@$_REQUEST["type_affichage"]=="liste")	{$style .= STYLE_ELEMENT_LISTE.STYLE_BORDER_RADIUS2;}
	// Affiche le menu
	echo "<div id='".$id_div_element."' class='div_elem_deselect' style=\"".$style."\" onMouseMove='$(this).click(function(event){ event.stopPropagation();	});'>
			<input type='checkbox' name='SelectedElems[]' id='checkbox_element_".$cpt_div_element."' value=\"".$obj_tmp["type_objet"]."-".$id_objet."\" style='display:none;' />";
	// Retourne l'id du block de l'element
	return $id_div_element;
}


////	NOMBRE D'ELEMENTS PAS PAGE => NAVIGATION SUR PLUSIEURS PAGES
////
function nb_elements_page()
{
	global $height_element, $nb_elements_ligne;
	return floor((@$_SESSION["cfg"]["resolution_height"]-150) / $height_element) * $nb_elements_ligne;
}


////	SELECTION D'UNE FRACTION DU TABLEAU DE RESULTATS =>  NAVIGATION SUR PLUSIEURS PAGES
////
function tableau_elements_page($tableau_elements)
{
	$nb_elements_page = nb_elements_page();
	$_SESSION["cfg"]["espace"]["num_page"] = (@$_GET["num_page"]>1)  ?  $_GET["num_page"]  :  1;
	$debut_limit = ($_SESSION["cfg"]["espace"]["num_page"]-1) * $nb_elements_page;
	return array_slice($tableau_elements, $debut_limit, $nb_elements_page);
}


////	CONTENU D'UN DOSSIER  :  NOMBRE D'ELEMENTS + TAILLE DU DOSSIER (FICHIERS)
////
function contenu_dossier($obj_tmp, $id_objet, $affichage="page")
{
	global $objet, $trad;
	// Recup des resultats
	$objet_enfant = $objet[$obj_tmp["type_contenu"]];
	$nb_elems_tmp	 = db_valeur("SELECT count(*) FROM ".$objet_enfant["table_objet"]." WHERE id_dossier=".intval($id_objet)." ".sql_affichage($objet_enfant,$id_objet));
	$nb_dossiers_tmp = db_valeur("SELECT count(*) FROM ".$obj_tmp["table_objet"]." WHERE id_dossier_parent=".intval($id_objet)." ".sql_affichage($obj_tmp)." ");
	// Prepare l'affichage
	$contenu_dossier = $nb_elems_tmp." ".($nb_elems_tmp>1?$trad["elements"]:$trad["element"]);
	if($nb_dossiers_tmp>0)												{$contenu_dossier .= " &nbsp;-&nbsp; ".$nb_dossiers_tmp." ".($nb_dossiers_tmp>1?$trad["dossiers"]:$trad["dossier"]);}
	if($obj_tmp["type_objet"]=="fichier_dossier" && $nb_elems_tmp>0)	{$contenu_dossier .= " &nbsp;-&nbsp; ".afficher_taille(dossier_taille(PATH_MOD_FICHIER.chemin($obj_tmp,$id_objet,"url")));}
	// Affichage
	if($affichage=="page")	{$contenu_dossier = "<div class='menu_gauche_ligne'><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/info.png\" /></div><div class='menu_gauche_txt'>".$contenu_dossier."</div></div>";}
	return $contenu_dossier;
}


////	REMPLACER LES LIBELLES :  -ELEMENTS- -> messages, fichiers, actualite..  /  -CONTENEUR- -> dossier, agenda..
////
function change_libelles_objets($objet_tmp, $libelle)
{
	global $trad;
	// Objets "element" contenus dans l'objet
	if(array_key_exists(@$objet_tmp["type_contenu"],$trad["libelles_objets"]))	{$libelle = str_replace("-ELEMENTS-", $trad["libelles_objets"][$objet_tmp["type_contenu"]], $libelle);}
	else																		{$libelle = str_replace("-ELEMENTS-", $trad["libelles_objets"]["element"], $libelle);}
	// Objet "conteneur"
	if(preg_match("/dossier/i",$objet_tmp["type_objet"]))						{$libelle = str_replace("-CONTENEUR-", $trad["libelles_objets"]["dossier"], $libelle);}
	if(array_key_exists(@$objet_tmp["type_objet"],$trad["libelles_objets"]))	{$libelle = str_replace("-CONTENEUR-", $trad["libelles_objets"][$objet_tmp["type_objet"]], $libelle);}
	else																		{$libelle = str_replace("-CONTENEUR-", $trad["libelles_objets"]["conteneur"], $libelle);}
	// Retour
	return $libelle;
}
?>
