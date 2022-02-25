<?php
//INIT
$opener = ($cfg_plugin["mode"]=="recherche") ? "window.opener." : "";
$forcePopup = ($cfg_plugin["mode"]=="recherche") ? ",true" : "";
////	TACHES AYANT COURS SUR LA PERIODE DEMANDEE
$liste_taches = array();
if($cfg_plugin["mode"]=="nouveautes")
{
	$date_debut_nouveautes = strftime("%Y-%m-%d 00:00:00");
	$liste_taches_tmp = db_tableau("SELECT DISTINCT * FROM gt_tache WHERE ".sql_affichage_objets_arbo($objet["tache"])."  AND  ((date_debut between '".$date_debut_nouveautes."' and '".$cfg_plugin["fin"]."') OR (date_fin between '".$date_debut_nouveautes."' and '".$cfg_plugin["fin"]."') OR (date_debut < '".$date_debut_nouveautes."' and date_fin > '".$cfg_plugin["fin"]."'))  ORDER BY date_debut desc");
	foreach($liste_taches_tmp as $key_tache_tmp => $tache_tmp)	{ $liste_taches_tmp[$key_tache_tmp]["icone_time_alert"] = "1"; }
	$liste_taches = array_merge($liste_taches, $liste_taches_tmp);
}
////	TACHES RECHERCHES / CREES SUR LA PERIODE ?
$liste_taches_tmp = db_tableau("SELECT DISTINCT * FROM gt_tache WHERE ".sql_affichage_objets_arbo($objet["tache"])." ".sql_selection_plugins($objet["tache"])." ORDER BY date_crea desc ");
$liste_taches = array_merge($liste_taches, $liste_taches_tmp);
////	AJOUT AUX RESULTATS
foreach($liste_taches as $tache_tmp)
{
	$libelle = ($tache_tmp["titre"]!="")  ?  $tache_tmp["titre"]  :  strip_tags($tache_tmp["description"]);
	if($tache_tmp["date_debut"]!="" || $tache_tmp["date_fin"]!="")	{$libelle = tache_debut_fin($tache_tmp,true)." : ".$libelle;}
	$resultat_tmp = array("type"=>"elem", "module_path"=>$cfg_plugin["module_path"], "icone_time_alert"=>@$tache_tmp["icone_time_alert"]);
	$resultat_tmp["lien_js_icone"] = $opener."redir('".ROOT_PATH.$cfg_plugin["module_path"]."/index.php?id_dossier=".$tache_tmp["id_dossier"]."');";
	$resultat_tmp["lien_js_libelle"] = "popupLightbox('".ROOT_PATH.$cfg_plugin["module_path"]."/tache.php?id_tache=".$tache_tmp["id_tache"]."' ".$forcePopup.");";
	$resultat_tmp["libelle"] = "<span ".infobulle(chemin($objet["tache_dossier"],$tache_tmp["id_dossier"],"url_virtuelle")."<br>".$trad["auteur"]." ".auteur($tache_tmp["id_utilisateur"],$tache_tmp["invite"])).">".text_reduit($libelle)."</span>";
	$cfg_plugin["resultats"][] = $resultat_tmp;
}
////	DOSSIERS
$liste_dossiers	= db_tableau("SELECT * FROM gt_tache_dossier WHERE 1 ".sql_affichage($objet["tache_dossier"])." ".sql_selection_plugins($objet["tache_dossier"])." ORDER BY date_crea desc ");
foreach($liste_dossiers as $dossier_tmp)
{
	$resultat_tmp = array("type"=>"dossier", "module_path"=>$cfg_plugin["module_path"]);
	$resultat_tmp["lien_js_icone"] = $opener."redir('".ROOT_PATH.$cfg_plugin["module_path"]."/index.php?id_dossier=".$dossier_tmp["id_dossier"]."');";
	$resultat_tmp["lien_js_libelle"] = $resultat_tmp["lien_js_icone"];
	$resultat_tmp["libelle"] = $dossier_tmp["nom"];
	$cfg_plugin["resultats"][] = $resultat_tmp;
}
?>
