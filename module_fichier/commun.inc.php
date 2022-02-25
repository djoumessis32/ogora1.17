<?php
////	INIT
@define("MODULE_NOM","fichier");
@define("MODULE_PATH","module_fichier");
require_once "../includes/global.inc.php";
$objet["fichier_dossier"] = array("type_objet"=>"fichier_dossier", "cle_id_objet"=>"id_dossier", "type_contenu"=>"fichier", "cle_id_contenu"=>"id_fichier", "table_objet"=>"gt_fichier_dossier");
$objet["fichier"]		  = array("type_objet"=>"fichier", "cle_id_objet"=>"id_fichier", "type_conteneur"=>"fichier_dossier", "cle_id_conteneur"=>"id_dossier", "table_objet"=>"gt_fichier");
$objet["fichier_dossier"]["champs_recherche"]	= array("nom","description");
$objet["fichier"]["champs_recherche"]			= array("nom","description");
$objet["fichier"]["tri"]	= array("nom@@asc","nom@@desc","date_crea@@desc","date_crea@@asc","date_modif@@desc","date_modif@@asc","id_utilisateur@@asc","id_utilisateur@@desc","extension@@asc","extension@@desc","taille_octet@@asc","taille_octet@@desc","nb_downloads@@desc","nb_downloads@@asc");
patch_dossier_racine($objet["fichier_dossier"]);


////	INFOS SUR UNE VERSION DU FICHIER (soit on sélectionne toutes les versions, soit la dernière version, soit une version à une date donnée)
////
function infos_version_fichier($id_fichier, $version="derniere", $order_by="date_crea desc")
{
	if($version=="toutes")			{ return db_tableau("SELECT * FROM gt_fichier_version WHERE id_fichier='".intval($id_fichier)."' ORDER BY ".$order_by); }
	elseif($version=="derniere")	{ return db_ligne("SELECT * FROM gt_fichier_version WHERE id_fichier='".intval($id_fichier)."' ORDER BY ".$order_by); }
	else							{ return db_ligne("SELECT * FROM gt_fichier_version WHERE id_fichier='".intval($id_fichier)."' AND date_crea='".$version."'"); }
}


////	SUPPRESSION D'UN FICHIER (toutes ses versions?)
////
function suppr_fichier($id_fichier, $version="toutes", $message_erreur=true)
{
	global $objet,$trad;
	if(droit_acces($objet["fichier"],$id_fichier)>=2)
	{
		////	Init
		$fichier = objet_infos($objet["fichier"],$id_fichier);
		$chemin_dossier = PATH_MOD_FICHIER.chemin($objet["fichier_dossier"],$fichier["id_dossier"],"url");
		$tab_versions = db_tableau("SELECT * FROM gt_fichier_version WHERE id_fichier='".intval($id_fichier)."' ORDER BY date_crea desc");
		////	Supprime la dernière version d'un fichier (si y'en a plusieurs..)
		if($version!="toutes" && count($tab_versions)>1 && $tab_versions[0]["date_crea"]==$version)
		{
			// Récupère les infos de l'avant dernière version ("nom" + "extension" + "taille_octet" + "date_crea" + "id_utilisateur")
			db_query("UPDATE gt_fichier SET  nom=".db_format($tab_versions[1]["nom"]).", extension='".extension($tab_versions[1]["nom"])."', taille_octet='".$tab_versions[1]["taille_octet"]."', date_modif='".$tab_versions[1]["date_crea"]."', id_utilisateur_modif='".$tab_versions[1]["id_utilisateur"]."'  WHERE id_fichier='".$fichier["id_fichier"]."'");
			// Maj de la vignette du fichier?
			if(controle_fichier("image_gd",$tab_versions[1]["nom"])){
				$nom_vignette = $fichier["id_fichier"].extension($tab_versions[1]["nom"]);
				reduire_image($chemin_dossier.$tab_versions[1]["nom_reel"], PATH_MOD_FICHIER2.$nom_vignette, 200, 200);
				db_query("UPDATE gt_fichier SET vignette='".$nom_vignette."' WHERE id_fichier='".$fichier["id_fichier"]."'");
			}
		}
		////	Supprime la/les versions du disque et de la BDD
		$tab_versions_suppr = db_tableau("SELECT * FROM gt_fichier_version WHERE id_fichier='".intval($id_fichier)."'  ".($version=="toutes"?"":"AND date_crea='".$version."'"));
		foreach($tab_versions_suppr as $fichier_tmp)
		{
			// Controle du "path" et de la suppression
			if($chemin_dossier.$fichier_tmp["nom_reel"]!=PATH_MOD_FICHIER)
			{
				$suppression = @unlink($chemin_dossier.$fichier_tmp["nom_reel"]);
				if($suppression==false && $message_erreur==true)	{alert($trad["MSG_ALERTE_acces_fichier"]." : ".$fichier["nom"]);}
				db_query("DELETE FROM gt_fichier_version WHERE id_fichier='".intval($id_fichier)."' AND nom_reel=".db_format($fichier_tmp["nom_reel"]));
				if($fichier["vignette"]!="" && $version=="toutes")	{@unlink(PATH_MOD_FICHIER2.$fichier["vignette"]);}
			}
		}
		////	Supprime le fichier de la BDD s'il ne reste plus de version
		if(db_valeur("SELECT count(*) FROM gt_fichier_version WHERE id_fichier='".intval($id_fichier)."'")==0)	{suppr_objet($objet["fichier"],$id_fichier);}
	}
}


////	SUPPRESSION D'UN DOSSIER
////
function suppr_fichier_dossier($id_dossier)
{
	global $objet,$trad;
	if(droit_acces($objet["fichier_dossier"],$id_dossier)==3 && $id_dossier>1)
	{
		//  Chemin du dossier
		$chemin_dossier = PATH_MOD_FICHIER.chemin($objet["fichier_dossier"],$id_dossier,"url");
		// alerte si dossier pas accessible
		if(is_dir($chemin_dossier)==false || $chemin_dossier==PATH_MOD_FICHIER)	alert($trad["MSG_ALERTE_acces_dossier"]." : ".$chemin_dossier);
		// Suppr l'arborescence du dossier dans la BDD  &  l'arborescence du dossier sur le disque
		if($chemin_dossier!=PATH_MOD_FICHIER)
		{
			foreach(arborescence($objet["fichier_dossier"],$id_dossier,"tous") as $dossier)
			{
				$fichiers_dossier = db_tableau("SELECT * FROM gt_fichier WHERE id_dossier=".intval($dossier["id_dossier"]));
				foreach($fichiers_dossier as $fichier)	{ suppr_fichier($fichier["id_fichier"],"toutes",false); }
				suppr_objet($objet["fichier_dossier"], $dossier["id_dossier"]);
			}
			rm($chemin_dossier);
		}
	}
}


////	DEPLACEMENT D'UN FICHIER
////
function deplacer_fichier($id_fichier, $id_dossier_destination)
{
	////	Initialisation
	global $objet;
	$chemin_dossier_origine	= PATH_MOD_FICHIER.chemin($objet["fichier_dossier"],objet_infos($objet["fichier"],$id_fichier,"id_dossier"),"url");
	$chemin_dossier_destination	= PATH_MOD_FICHIER.chemin($objet["fichier_dossier"],$id_dossier_destination,"url");
	////	Accès en écriture au fichier et au dossier de destination  +  acces dossier destination sur le disque
	if(droit_acces($objet["fichier"],$id_fichier)>=2  &&  droit_acces($objet["fichier_dossier"],$id_dossier_destination)>=2  &&  is_dir($chemin_dossier_destination))
	{
		////	On déplace le fichier (& ses différentes versions) sur le disque
		foreach(db_tableau("SELECT nom_reel FROM gt_fichier_version WHERE id_fichier=".$id_fichier." ") as $fichier)	{ rename($chemin_dossier_origine.$fichier["nom_reel"], $chemin_dossier_destination.$fichier["nom_reel"]); }
		////	Si on deplace à la racine, on donne les droits d'accès de l'ancien dossier
		racine_copie_droits_acces($objet["fichier"], $id_fichier, $objet["fichier_dossier"], $id_dossier_destination);
		////	On déplace le fichier
		db_query("UPDATE gt_fichier SET id_dossier=".intval($id_dossier_destination)." WHERE id_fichier=".db_format($id_fichier));
	}
	////	Logs
	add_logs("modif", $objet["fichier"], $id_fichier);
}


////	DEPLACEMENT D'UN DOSSIER
////
function deplacer_fichier_dossier($id_dossier, $id_dossier_destination)
{
	////	Initialisation
	global $objet;
	$chemin_dossier_destination = PATH_MOD_FICHIER.chemin($objet["fichier_dossier"],$id_dossier_destination,"url");
	////	Accès total au dossier en question  &  accès en écriture au dossier destination  &  controle du déplacement du dossier
	if(droit_acces($objet["fichier_dossier"],$id_dossier)==3  &&  droit_acces($objet["fichier_dossier"],$id_dossier_destination)>=2  &&  is_dir($chemin_dossier_destination)  &&  controle_deplacement_dossier($objet["fichier_dossier"],$id_dossier,$id_dossier_destination)==1)
	{
		////	On déplace le fichier sur le disque
		$infos_dossier = objet_infos($objet["fichier_dossier"],$id_dossier);
		$chemin_dossier_origine = PATH_MOD_FICHIER.chemin($objet["fichier_dossier"],$infos_dossier["id_dossier_parent"],"url");
		rename($chemin_dossier_origine.$infos_dossier["nom_reel"]."/", $chemin_dossier_destination.$infos_dossier["nom_reel"]."/");
		////	On déplace le dossier dans la BDD
		db_query("UPDATE gt_fichier_dossier SET id_dossier_parent=".db_format($id_dossier_destination)." WHERE id_dossier=".intval($id_dossier));
	}
	////	Logs
	add_logs("modif", $objet["fichier_dossier"], $id_dossier);
}


////	IMAGE DU FICHIER
////
function image_fichier($fichier_nom)
{
	if(controle_fichier("image",$fichier_nom)==true)																					{ return "image"; }
	elseif(controle_fichier("word",$fichier_nom) || controle_fichier("ootext",$fichier_nom) || controle_fichier("text",$fichier_nom))	{ return "texte"; }
	elseif(controle_fichier("excel",$fichier_nom) || controle_fichier("oocalc",$fichier_nom))											{ return "tableur"; }
	elseif(controle_fichier("powerpoint",$fichier_nom) || controle_fichier("oopresent",$fichier_nom))									{ return "presentation"; }
	elseif(controle_fichier("archive",$fichier_nom))																					{ return "compresse"; }
	elseif(preg_match("/(\.pdf)$/i", $fichier_nom))																						{ return "pdf"; }
	elseif(preg_match("/(\.mp3|\.wma|\.wav|\.ra|\.ram|\.ogg|\.aac|\.m4r)$/i", $fichier_nom))											{ return "audio"; }
	elseif(controle_fichier("video_browser",$fichier_nom)==true || preg_match("/(\.mpe|\.qt|\.mkv|\.ogm|\.vob)$/i", $fichier_nom))		{ return "video"; }
	elseif(preg_match("/(\.exe|\.sh|\.bat|\.bin|\.cgi|\.dat|\.dll|\.msi|\.rpm|\.deb)$/i", $fichier_nom))								{ return "executable"; }
	elseif(preg_match("/(\.fla|\.swf)$/i", $fichier_nom))																				{ return "flash"; }
	elseif(preg_match("/(\.htm|\.html|\.php|\.phtml|\.asp|\.aspx|\.jsp|\.cfm|\.js)$/i", $fichier_nom))									{ return "html"; }
	elseif(preg_match("/(\.dwg|\.dxf)$/i", $fichier_nom))                                    											{ return "dwg"; }
	else																																{ return "inconnu"; }
}
?>