<?php
////	LIEN
$opener = ($cfg_plugin["mode"]=="recherche") ? "window.opener." : "";
$liste_liens = db_tableau("SELECT DISTINCT * FROM gt_lien WHERE ".sql_affichage_objets_arbo($objet["lien"])." ".sql_selection_plugins($objet["lien"])."  ORDER BY date_crea desc");
foreach($liste_liens as $lien_tmp)
{
	$libelle_lien = ($lien_tmp["description"]!="") ? $lien_tmp["description"] : $lien_tmp["adresse"];
	$resultat_tmp = array("type"=>"elem", "module_path"=>$cfg_plugin["module_path"]);
	$resultat_tmp["lien_js_icone"] = $opener."redir('".ROOT_PATH.$cfg_plugin["module_path"]."/index.php?id_dossier=".$lien_tmp["id_dossier"]."');";
	$resultat_tmp["lien_js_libelle"] = "window.open('".$lien_tmp["adresse"]."');";
	$resultat_tmp["libelle"] = "<span ".infobulle(chemin($objet["lien_dossier"],$lien_tmp["id_dossier"],"url_virtuelle")."<br>".$trad["auteur"]." ".auteur($lien_tmp["id_utilisateur"],$lien_tmp["invite"])).">".text_reduit($libelle_lien)."</span>";
	$cfg_plugin["resultats"][] = $resultat_tmp;
}
////	DOSSIERS
$liste_dossiers	= db_tableau("SELECT * FROM gt_lien_dossier WHERE 1 ".sql_affichage($objet["lien_dossier"])." ".sql_selection_plugins($objet["lien_dossier"])."  ORDER BY date_crea desc");
foreach($liste_dossiers as $dossier_tmp)
{
	$resultat_tmp = array("type"=>"dossier", "module_path"=>$cfg_plugin["module_path"]);
	$resultat_tmp["lien_js_icone"] = $opener."redir('".ROOT_PATH.$cfg_plugin["module_path"]."/index.php?id_dossier=".$dossier_tmp["id_dossier"]."');";
	$resultat_tmp["lien_js_libelle"] = $resultat_tmp["lien_js_icone"];
	$resultat_tmp["libelle"] = $dossier_tmp["nom"];
	$cfg_plugin["resultats"][] = $resultat_tmp;
}
?>