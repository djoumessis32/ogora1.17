<?php
////	ACTUALITES
if($cfg_plugin["mode"]=="recherche")
{
	$liste_actualites = db_tableau("SELECT DISTINCT * FROM gt_actualite WHERE 1 ".sql_affichage($objet["actualite"])." ".sql_selection_plugins($objet["actualite"])." ORDER BY date_crea desc");
	foreach($liste_actualites as $actualite_tmp)
	{
		$resultat_tmp = array("type"=>"elem", "module_path"=>$cfg_plugin["module_path"]);
		$resultat_tmp["lien_js_icone"] = $resultat_tmp["lien_js_libelle"] = "afficher_dynamic('actualite_plugin".$actualite_tmp["id_actualite"]."');";
		$resultat_tmp["libelle"]  = "<span ".infobulle($trad["auteur"]." ".auteur($actualite_tmp["id_utilisateur"])).">".text_reduit($actualite_tmp["description"])."</span>";
		$resultat_tmp["libelle"] .= "<div id=\"actualite_plugin".$actualite_tmp["id_actualite"]."\" class=\"div_elem_deselect\" style=\"display:none;margin:10px;padding:5px;\">".$actualite_tmp["description"]."</div>";
		$cfg_plugin["resultats"][] = $resultat_tmp;
	}
}
?>