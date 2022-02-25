<?php
if($cfg_plugin["mode"]=="recherche" || $cfg_plugin["mode"]=="nouveautes")
{
	$forcePopup = ($cfg_plugin["mode"]=="recherche") ? ",true" : "";
	$liste_utilisateurs = db_tableau("SELECT DISTINCT * FROM gt_utilisateur WHERE 1 ".sql_utilisateurs_espace()." ".sql_selection_plugins($objet["utilisateur"])." ORDER BY ".$_SESSION["agora"]["tri_personnes"]);
	foreach($liste_utilisateurs as $user_tmp)
	{
		$resultat_tmp = array("type"=>"elem", "module_path"=>$cfg_plugin["module_path"]);
		$resultat_tmp["lien_js_icone"] = $resultat_tmp["lien_js_libelle"] = "popupLightbox('".ROOT_PATH.$cfg_plugin["module_path"]."/utilisateur.php?id_utilisateur=".$user_tmp["id_utilisateur"]."' ".$forcePopup.");";
		$resultat_tmp["libelle"] = $user_tmp["civilite"]." ".$user_tmp["nom"]." ".$user_tmp["prenom"];
		$cfg_plugin["resultats"][] = $resultat_tmp;
	}
}
?>