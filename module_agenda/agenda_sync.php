<?php
//////////////////////////////////////////////////////////////////////////////////////////
// 	Adresse du calendrier: http://[adresse_agora]/webcal/[identifiant_utilisateur]/     //
//////////////////////////////////////////////////////////////////////////////////////////

////	GENERATION DU CONTENU DU FICHIER ICS
$cat_affichee=$_GET['cat']; // Avant, on sauvegarde la sélection de catégorie a afficher
unset($_GET['cat']); // On désactive temporairement la catégorie sélectionnée, pour que tous les evts soient pris en compte
$agenda_tmp=$AGENDAS_AFFICHES[$id_agenda];
$liste_evenements = liste_evenements($agenda_tmp["id_agenda"], (time()-(86400*30)), (time()+(86400*3650))); // T-30jours => T+10ans
$fichier_ical = fichier_ical($liste_evenements);
$_GET['cat']=$cat_affichee; // Maintenant on rétabli la catégorie sélectionnée, pour l'affichage des agendas suivants.


////	CREATION DU DOSSIER DE L'UTILISATEUR PROPRIéTAIRE (si inexistant)
$agenda_infosutilisateur = db_ligne("SELECT * FROM gt_utilisateur WHERE id_utilisateur='".$agenda_tmp["id_utilisateur"]."'");
$chemin_dossier = "../webcal/".$agenda_infosutilisateur["identifiant"]."";
if (!file_exists($chemin_dossier)) 
	{ mkdir("$chemin_dossier", 0700); }

//// 	PROTECTION DU DOSSIER
$htaccess_file = realpath("$chemin_dossier")."/.htaccess";

// Création du fichier .htaccess
$htaccess_content  = 'Deny from all'."\n";
//$htaccess_content .= '<Files ~ "\.(ics)$">'."\n";
//$htaccess_content .= 'Deny from all'."\n";
//$htaccess_content .= '</Files>'."\n";
$fp = fopen($htaccess_file, "w");
fwrite($fp, $htaccess_content);
fclose($fp);


////	GENERATION DU FICHIER ICAL
$nom_fichier = "calendar.ics";
// Création du fichier ical
$fichier_tmp = "$chemin_dossier/$nom_fichier";
if(file_exists($fichier_tmp)) {unlink($fichier_tmp);}

$fp = fopen($fichier_tmp, "w");
fwrite($fp, $fichier_ical);
fclose($fp);
?>