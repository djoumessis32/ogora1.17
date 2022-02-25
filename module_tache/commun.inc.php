<?php
////	INIT
@define("MODULE_NOM","tache");
@define("MODULE_PATH","module_tache");
require_once "../includes/global.inc.php";
$objet["tache_dossier"]	= array("type_objet"=>"tache_dossier", "cle_id_objet"=>"id_dossier", "type_contenu"=>"tache", "cle_id_contenu"=>"id_tache", "table_objet"=>"gt_tache_dossier");
$objet["tache"]			= array("type_objet"=>"tache", "cle_id_objet"=>"id_tache", "table_objet"=>"gt_tache", "type_conteneur"=>"tache_dossier", "cle_id_conteneur"=>"id_dossier");
$objet["tache_dossier"]["champs_recherche"] = array("nom","description");
$objet["tache"]["champs_recherche"]			= array("titre","description");
$objet["tache"]["tri"]	= array("date_crea@@desc","date_crea@@asc","date_modif@@desc","date_modif@@asc","id_utilisateur@@asc","id_utilisateur@@desc","titre@@asc","titre@@desc","description@@asc","description@@desc","priorite@@asc","priorite@@desc","avancement@@asc","avancement@@desc","date_debut@@asc","date_debut@@desc","date_fin@@asc","date_fin@@desc");
patch_dossier_racine($objet["tache_dossier"]);


////	SUPPRESSION D'UNE TACHE
////
function suppr_tache($id_tache)
{
	global $objet;
	if(droit_acces($objet["tache"],$id_tache)>=2){
		suppr_objet($objet["tache"],$id_tache);
		db_query("DELETE FROM gt_tache_responsable WHERE id_tache=".db_format($id_tache));
	}
}


////	SUPPRESSION D'UN DOSSIER
////
function suppr_tache_dossier($id_dossier)
{
	global $objet;
	if(droit_acces($objet["tache_dossier"],$id_dossier)==3 && $id_dossier>1)
	{
		// on créé la liste des dossiers & on supprime chaque dossier
		$liste_dossiers_suppr = arborescence($objet["tache_dossier"], $id_dossier, "tous");
		foreach($liste_dossiers_suppr as $infos_dossier)
		{
			// On supprime chaque tache du dossier puis le dossier en question
			$liste_taches = db_tableau("SELECT * FROM gt_tache WHERE id_dossier='".$infos_dossier["id_dossier"]."'");
			foreach($liste_taches as $infos_tache)		{ suppr_tache($infos_tache["id_tache"]); }
			suppr_objet($objet["tache_dossier"], $infos_dossier["id_dossier"]);
		}
	}
}


////	DEPLACEMENT D'UNE TACHE
////
function deplacer_tache($id_tache, $id_dossier_destination)
{
	global $objet;
	////	Accès en écriture à la tache et au dossier de destination
	if(droit_acces($objet["tache"],$id_tache)>=2  &&  droit_acces($objet["tache_dossier"],$id_dossier_destination)>=2)
	{
		////	Si on deplace à la racine, on donne les droits d'accès de l'ancien dossier
		racine_copie_droits_acces($objet["tache"], $id_tache, $objet["tache_dossier"], $id_dossier_destination);
		////	On déplace la tache
		db_query("UPDATE gt_tache SET id_dossier=".db_format($id_dossier_destination)." WHERE id_tache=".db_format($id_tache));
	}
	////	Logs
	add_logs("modif", $objet["tache"], $id_tache);
}


////	DEPLACEMENT D'UN DOSSIER
////
function deplacer_tache_dossier($id_dossier, $id_dossier_destination)
{
	global $objet;
	////	Accès total au dossier en question  &  accès en écriture au dossier destination  &  controle du déplacement du dossier
	if(droit_acces($objet["tache_dossier"],$id_dossier)==3  &&  droit_acces($objet["tache_dossier"],$id_dossier_destination)>=2  &&  controle_deplacement_dossier($objet["tache_dossier"],$id_dossier,$id_dossier_destination)==1) {
		db_query("UPDATE gt_tache_dossier SET id_dossier_parent=".db_format($id_dossier_destination)." WHERE id_dossier=".intval($id_dossier));
	}
	////	Logs
	add_logs("modif", $objet["tache_dossier"], $id_dossier);
}


////	TACHE EN RETARD ?  (tache pas terminée et date de fin passée)
////
function tache_retardee($tache_tmp)
{
	if($tache_tmp["avancement"]!="" && $tache_tmp["avancement"]<100 && $tache_tmp["date_fin"]!="" && strtotime($tache_tmp["date_fin"])<time())
		return true;
}


////	BARRE DE STATUT DE L'AVANCEMENT
////
function tache_barre_avancement_charge($tache_tmp, $element="tache")
{
	//Init
	global $trad;
	$lib_avancement			= ($element=="dossier")  ?  $trad["TACHE_avancement_moyen"] : $trad["TACHE_avancement"];
	$lib_charge_jour_homme	= ($element=="dossier")  ?  $trad["TACHE_charge_jour_homme_total"] : $trad["TACHE_charge_jour_homme"];
	// Barre de statut de l'avancement (+ de la charge?)
 	if($tache_tmp["avancement"]>0)
 	{
		// Init
		$tache_retardee = tache_retardee($tache_tmp);
 		$couleur_pourcent = ($tache_retardee==true) ? "rouge" : "jaune";
 		// Infos principales
 		$txt_barre = "<img src=\"".PATH_TPL."module_tache/avancement.png\" /> ".$tache_tmp["avancement"]."% &nbsp;";
		if(@$tache_tmp["avancement_moyen_pondere"]>0)	$txt_barre .= "<img src=\"".PATH_TPL."module_tache/avancement_moyen_pondere.png\" /> ".$tache_tmp["avancement_moyen_pondere"]."% &nbsp;";
		if($tache_tmp["charge_jour_homme"]>0)			$txt_barre .= "<img src=\"".PATH_TPL."module_tache/charge_jour_homme.png\" /> ".$tache_tmp["charge_jour_homme"];
		// Pécision dans l'infobulle
		$txt_infobulle = "<img src=\"".PATH_TPL."module_tache/avancement.png\" /> &nbsp; ".$lib_avancement." : ".$tache_tmp["avancement"]." %";
 		if(@$tache_tmp["avancement_moyen_pondere"]>0)	$txt_infobulle .= "<br><img src=\"".PATH_TPL."module_tache/avancement_moyen_pondere.png\" /> &nbsp; ".$trad["TACHE_avancement_moyen_pondere"]." : ".$tache_tmp["avancement_moyen_pondere"]." %";
		if($tache_tmp["charge_jour_homme"]>0)			$txt_infobulle .= "<br><img src=\"".PATH_TPL."module_tache/charge_jour_homme.png\" /> &nbsp; ".$lib_charge_jour_homme." : ".$tache_tmp["charge_jour_homme"];
		if($tache_retardee==true)						$txt_infobulle .= "<br><br><img src=\"".PATH_TPL."divers/important.png\" /> &nbsp; ".$trad["TACHE_avancement_retard"];
		// Affichage
		return status_bar($tache_tmp["avancement"], $txt_barre, $txt_infobulle, $couleur_pourcent)." &nbsp; &nbsp;";
	}
	// Affichage de la charge
	elseif($tache_tmp["charge_jour_homme"]>0) {
		return "<img src=\"".PATH_TPL."module_tache/charge_jour_homme.png\" /> ".$lib_charge_jour_homme." : ".$tache_tmp["charge_jour_homme"]."<img src=\"".PATH_TPL."divers/separateur.gif\" />";
	}
}


////	AFFICHAGE DU TEMPS : DEBUT / FIN / BARRE DE STATUT DE PERIODE (debut+fin)
////
function tache_debut_fin($tache_tmp, $aff_text_infobulle=false)
{
	if($tache_tmp["date_debut"]!="" || $tache_tmp["date_fin"]!="")
	{
		////	Affichage de la période ou date en mode texte
		global $trad;
		if($tache_tmp["date_debut"]!="" && $tache_tmp["date_fin"]!="")	{ $tache_debut_fin = temps($tache_tmp["date_debut"], "normal", $tache_tmp["date_fin"], true); }
		elseif($tache_tmp["date_debut"]!="")							{ $tache_debut_fin = temps($tache_tmp["date_debut"], "normal", "", true); }
		elseif($tache_tmp["date_fin"]!="")								{ $tache_debut_fin = $trad["fin"]." : ".temps($tache_tmp["date_fin"], "normal", "", true); }

		////	Affichage infobulle
		if($aff_text_infobulle==true)	{ return $tache_debut_fin; }
		////	Barre de statut du temps
		elseif($tache_tmp["date_debut"]!="" && $tache_tmp["date_fin"]!="" && substr($tache_tmp["date_debut"],0,10)!=substr($tache_tmp["date_fin"],0,10))
		{
			// Barre d'avancement : (now-debut / fin-debut)*100
			$pourcent = round( ((time()-strtotime($tache_tmp["date_debut"])) / (strtotime($tache_tmp["date_fin"])-strtotime($tache_tmp["date_debut"]))) * 100);
			if($pourcent<0)  $pourcent = 0;
			$tache_retardee = tache_retardee($tache_tmp);
			$couleur_pourcent = ($tache_retardee==true) ? "rouge" : "jaune";
			// Texte
			$txt_barre = "<img src=\"".PATH_TPL."module_tache/date.png\" /> ".temps($tache_tmp["date_debut"],"plugin",$tache_tmp["date_fin"],true);
			$txt_infobulle = "<img src=\"".PATH_TPL."module_tache/date.png\" /> &nbsp; ".$tache_debut_fin;
			if($tache_retardee==true)	$txt_infobulle .= "<br><br><img src=\"".PATH_TPL."divers/important.png\" /> &nbsp; ".$trad["TACHE_avancement_retard"]." (".$tache_tmp["avancement"]." %)";
			// Resultat
			return status_bar($pourcent, $txt_barre, $txt_infobulle, $couleur_pourcent, "width:150px;")." &nbsp; &nbsp;";
	 	}
	 	////	Affichage texte
		else { return $tache_debut_fin."<img src=\"".PATH_TPL."divers/separateur.gif\" />"; }
	}
}


////	AFFICHAGE DU BUDGET : DEBUT / FIN / BARRE DE STATUT DE PERIODE (debut+fin)
////
function tache_budget($tache_tmp, $element="tache")
{
	//Init
	global $trad;
	$lib_budget_disponible	= ($element=="dossier")  ?  $trad["TACHE_budget_disponible_total"] : $trad["TACHE_budget_disponible"];
	$lib_budget_engage		= ($element=="dossier")  ?  $trad["TACHE_budget_engage_total"] : $trad["TACHE_budget_engage"];
	// Barre de statut du budget
	if($tache_tmp["budget_disponible"]>0 && $tache_tmp["budget_engage"]>0)
	{
		$pourcent = round(($tache_tmp["budget_engage"] / $tache_tmp["budget_disponible"]) * 100);
		$couleur_pourcent = ($tache_tmp["budget_engage"]>$tache_tmp["budget_disponible"]) ? "rouge" : "jaune";
		$txt_barre = "<img src=\"".PATH_TPL."module_tache/budget_engage.png\" /> ".$tache_tmp["budget_engage"]." &nbsp; &nbsp; <img src=\"".PATH_TPL."module_tache/budget_disponible.png\" /> ".$tache_tmp["budget_disponible"];
		$txt_infobulle = "<img src=\"".PATH_TPL."module_tache/budget_engage.png\" /> &nbsp; ".$lib_budget_engage." : ".$tache_tmp["budget_engage"]." ".$tache_tmp["devise"]." (".$pourcent." %)<br><img src=\"".PATH_TPL."module_tache/budget_disponible.png\" /> &nbsp; ".$lib_budget_disponible." : ".$tache_tmp["budget_disponible"]." ".$tache_tmp["devise"];
		if($tache_tmp["budget_engage"]>$tache_tmp["budget_disponible"])	$txt_infobulle .= "<br><br><img src=\"".PATH_TPL."divers/important.png\" /> &nbsp; ".$trad["TACHE_budget_depasse"];
		return status_bar($pourcent, $txt_barre, $txt_infobulle, $couleur_pourcent)." &nbsp; &nbsp;";
 	}
 	// Affichage texte
	elseif($tache_tmp["budget_disponible"]!="")	{ return "<img src=\"".PATH_TPL."module_tache/budget_disponible.png\" /> ".$lib_budget_disponible." : ".$tache_tmp["budget_disponible"]." ".$tache_tmp["devise"]."<img src=\"".PATH_TPL."divers/separateur.gif\" />"; }
 	elseif($tache_tmp["budget_engage"]!="")		{ return "<img src=\"".PATH_TPL."module_tache/budget_engage.png\" /> ".$lib_budget_engage." : ".$tache_tmp["budget_engage"]." ".$tache_tmp["devise"]."<img src=\"".PATH_TPL."divers/separateur.gif\" />"; }
}


////	AFFICHAGE DES BARRES D'AVANCEMENT D'UN DOSSIER (ET SOUS DOSSIERS!!)  =  MIN / MAX / MOYENNE DE L'ENSEMBLE DES TACHES
////
function tache_synthese_dossier($dossier_tmp)
{
	// Init
	global $objet;
	$tache_synthese_dossier = $sql_selection_dossiers = "";
	$arborescence = arborescence($objet["tache_dossier"], $dossier_tmp["id_dossier"]);
	foreach($arborescence as $dossier_tmp2)		{ $sql_selection_dossiers .= $dossier_tmp2["id_dossier"].","; }
	$sql_selection_dossiers = "FROM gt_tache WHERE id_dossier in (".substr($sql_selection_dossiers,0,-1).")";
	// Recup des infos
	if(db_valeur("SELECT count(*) ".$sql_selection_dossiers)>0)
	{
		$tache_tmp1 = db_ligne("SELECT SUM(budget_disponible) as budget_disponible, SUM(budget_engage) as budget_engage, SUM(charge_jour_homme) as charge_jour_homme ".$sql_selection_dossiers);
		$tache_tmp2 = db_ligne("SELECT MIN(date_debut) as date_debut, MAX(date_fin) as date_fin ".$sql_selection_dossiers);
		$tache_tmp = array_merge($tache_tmp1, $tache_tmp2);
		$tache_tmp["devise"]					= db_valeur("SELECT devise ".$sql_selection_dossiers);
		$tache_tmp["avancement"]				= db_valeur("SELECT ROUND(AVG(avancement)) as avancement ".$sql_selection_dossiers." and avancement > 0 ");
		$tache_tmp["avancement_moyen_pondere"]	= db_valeur("SELECT ROUND(SUM(charge_jour_homme*avancement)/SUM(charge_jour_homme)) as avancement ".$sql_selection_dossiers."  and avancement > 0 and charge_jour_homme > 0 ");
		$tache_synthese_dossier .= tache_budget($tache_tmp, "dossier");
		$tache_synthese_dossier .= tache_barre_avancement_charge($tache_tmp, "dossier");
		$tache_synthese_dossier .= tache_debut_fin($tache_tmp);
		return $tache_synthese_dossier;
	}
}


////	MENU DE FILTRAGE DES TACHES PAR PRIORITE (PAS ENREGISTRÉ DANS LES PREFERENCES CAR MASQUE DES RESULTATS..)
////
function menu_tache_filtre_priorite()
{
	////	INIT
	global $trad;
	$url_tmp = php_self().variables_get("filtre_priorite")."&filtre_priorite=";
	$libelle_filtre_priorite = (@$_REQUEST["filtre_priorite"]!="")  ?  $trad["TACHE_priorite".@$_REQUEST["filtre_priorite"]]  :  "";
	////	AFFICHAGE
	$affichage = "<div class='menu_gauche_line lien' id='icone_menu_filtre_priorite'>";
		$affichage .= "<div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/filtre.png\" /></div>";
		$affichage .= "<div class='menu_gauche_txt'>".$trad["TACHE_priorite"]." ".$libelle_filtre_priorite." <img src=\"".PATH_TPL."module_tache/priorite".@$_REQUEST["filtre_priorite"].".png\" /></div>";
	$affichage .= "</div>";
	$affichage .= "<div class='menu_context' id='menu_filtre_priorite'>";
		for($i=1; $i<=4; $i++){
			$style_tmp = ($i==@$_REQUEST["filtre_priorite"])  ?  "lien_select"  :  "lien";
			$affichage .= "<a href=\"".$url_tmp.$i."\" class='".$style_tmp."'><img src=\"".PATH_TPL."module_tache/priorite".$i.".png\" />&nbsp; ".$trad["TACHE_priorite".$i]."</a><br><br>";
		}
		$affichage .= "<a href=\"".$url_tmp."\"><img src=\"".PATH_TPL."module_tache/priorite.png\" />&nbsp; <i>".$trad["tout_afficher"]."</i></a>";
	$affichage .= "</div>";
	$affichage .= "<script type='text/javascript'> menu_contextuel('menu_filtre_priorite'); </script>";
	return $affichage;
}
////	...ET FILTRAGE SQL
function sql_tache_filtre_priorite()
{
	if(@$_REQUEST["filtre_priorite"]!="")	return "AND priorite=".db_format($_REQUEST["filtre_priorite"]);
}
?>