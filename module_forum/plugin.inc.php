<?php
//INIT
$opener = ($cfg_plugin["mode"]=="recherche") ? "window.opener." : "";
////	SUJETS
$liste_sujets = db_tableau("SELECT DISTINCT * FROM gt_forum_sujet WHERE 1 ".sql_affichage($objet["sujet"])." ".sql_selection_plugins($objet["sujet"])." ORDER BY date_crea desc");
foreach($liste_sujets as $sujet_tmp)
{
	$libelle = ($sujet_tmp["titre"]!="")  ?  $sujet_tmp["titre"]  :  strip_tags($sujet_tmp["description"]);
	$resultat_tmp = array("type"=>"elem", "module_path"=>$cfg_plugin["module_path"]);
	$resultat_tmp["lien_js_icone"] = $resultat_tmp["lien_js_libelle"] = $opener."redir('".ROOT_PATH.$cfg_plugin["module_path"]."/sujet.php?id_sujet=".$sujet_tmp["id_sujet"]."');";
	$resultat_tmp["libelle"] = "<span ".infobulle($trad["auteur"]." ".auteur($sujet_tmp["id_utilisateur"],$sujet_tmp["invite"])).">".text_reduit($libelle)."</span>";
	$cfg_plugin["resultats"][] = $resultat_tmp;
}
////	MESSAGES
if($cfg_plugin["mode"]!="raccourcis")
{
	$liste_messages	= db_tableau("SELECT * FROM gt_forum_message WHERE id_sujet IN (SELECT id_sujet FROM gt_forum_sujet WHERE 1 ".sql_affichage($objet["sujet"]).")  ".sql_selection_plugins($objet["message"])." ORDER BY date_crea desc");
	foreach($liste_messages as $message_tmp)
	{
		$libelle = ($message_tmp["titre"]!="")  ?  $message_tmp["titre"]  :  strip_tags($message_tmp["description"]);
		$resultat_tmp = array("type"=>"elem", "module_path"=>$cfg_plugin["module_path"]);
		$resultat_tmp["lien_js_icone"] = $resultat_tmp["lien_js_libelle"] = $opener."redir('".ROOT_PATH.$cfg_plugin["module_path"]."/sujet.php?id_sujet=".$message_tmp["id_sujet"]."');";
		$resultat_tmp["libelle"] = "<span ".infobulle($trad["auteur"]." ".auteur($message_tmp["id_utilisateur"],$message_tmp["invite"])).">".text_reduit($libelle)."</span>";
		$cfg_plugin["resultats"][] = $resultat_tmp;
	}
}
?>