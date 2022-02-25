<?php
////	INITIALISATION  &  ESPACE OCCUPE  &  CHEMIN VERS LE DOSSIER DE DESTINATION  &  REPARAMETRAGE PHP
////
require "commun.inc.php";
$espace_occupe = taille_stock_fichier(true);
$chemin_dossier = PATH_MOD_FICHIER.chemin($objet["fichier_dossier"],$_POST["id_dossier"],"url");

////	RECUPERATION DES FICHIERS ENVOYES EN HTML5 (VIA DOSSIER TEMPORAIRE)
////
if(preg_match("/plupload/i",$_POST["type_selection"]))
{
	if(!isset($_FILES))	{$_FILES = array();}
	$dossier_tmp_upload = PATH_TMP.$_POST["dossier_tmp_upload"];
	if(is_dir($dossier_tmp_upload))
	{
		$dirTmp = opendir($dossier_tmp_upload);
		while($fileTmp = readdir($dirTmp)){
			$filePath = $dossier_tmp_upload."/".$fileTmp;
			$fileSize = filesize($filePath);
			if(is_file($filePath) && $fileSize>0)  {$_FILES[] = array("error"=>0, "upload_html5"=>"1", "tmp_name"=>$filePath, "name"=>$fileTmp, "size"=>$fileSize);}
		}
	}
}

////	NOUVELLE VERSION D'UN FICHIER
////
if(@$_POST["id_fichier_version"]>0)
{
	// Récup' des infos du fichier
	$fichier_old = objet_infos($objet["fichier"],$_POST["id_fichier_version"]);
	// Alerte si la nouvelle extension est différente de l'ancienne
	foreach($_FILES as $fileTmp){
		if(!empty($fileTmp["name"]) && extension($fileTmp["name"])!=$fichier_old["extension"])	{alert($trad["MSG_ALERTE_type_version"]." : ".extension($fileTmp["name"]));}
	}
}


////	TRAITEMENT DES FICHIERS
////
////	ERREUR (aucun fichier uploadé / dossier en lecture seule)
if(!isset($_FILES) || count($_FILES)==0)	{ alert($trad["MSG_ALERTE_taille_fichier"]); }
elseif(!is_writable($chemin_dossier))		{ alert($trad["MSG_ALERTE_chmod_stock_fichiers"]); }
////	UPLOAD OK
else
{
	foreach($_FILES as $id_input_fichier => $fileTmp)
	{
		////	ERREUR (fichier trop grop / pas assez d'espace disque  /  interdit)
		if($fileTmp["error"]==1 || $fileTmp["error"]==2)					{ alert($trad["MSG_ALERTE_taille_fichier"]." : ".$fileTmp["name"]); }
		elseif(controle_fichier("fichier_interdit",$fileTmp["name"]))		{ alert($trad["MSG_ALERTE_type_interdit"]." : ".$fileTmp["name"]); }
		elseif(($espace_occupe+$fileTmp["size"]) > limite_espace_disque)	{ alert($trad["MSG_ALERTE_espace_disque"]);  break; }
		////	FICHIER OK
		elseif($fileTmp["error"]==0)
		{
			////	Incrémente la taille de l'espace disque  +  Description (formulaire simple)  +  Extension
			$espace_occupe += $fileTmp["size"];
			$fileTmp["description"] = @$_POST[str_replace("fichier","description",$id_input_fichier)];
			$fileTmp["extension"] = extension($fileTmp["name"]);

			////	Infos SQL principales
			$sql_nom = "nom=".db_format($fileTmp["name"]).", extension=".db_format($fileTmp["extension"]);
			$sql_details = "description=".db_format($fileTmp["description"]).", taille_octet='".$fileTmp["size"]."'";
			$sql_version = "date_crea=".db_date_now().", id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."', invite=".db_format(@$_POST["invite"]);

			////	NOUVELLE VERSION DU FICHIER
			if(@$_POST["id_fichier_version"]>0)
			{
				$fileTmp["id_fichier"] = $last_id_fichier = $fichier_old["id_fichier"];
				db_query("UPDATE gt_fichier SET  ".$sql_nom.", ".$sql_details."  WHERE id_fichier=".db_format($fileTmp["id_fichier"]));
				add_logs("modif", $objet["fichier"], $fileTmp["id_fichier"]);
			}
			////	NOUVEAU FICHIER
			else
			{
				// fichier existe déjà avec le meme nom ?
				$fichier_exist = db_valeur("SELECT count(*) FROM gt_fichier WHERE id_dossier='".intval($_POST["id_dossier"])."' AND nom='".addslashes($fileTmp["name"])."'");
				if($fichier_exist>0)	{alert($trad["MSG_ALERTE_nom_fichier"]." : ".$fileTmp["name"]);}
				// Enregistre le fichier
				db_query("INSERT INTO gt_fichier SET id_dossier=".intval($_POST["id_dossier"]).", ".$sql_nom.", ".$sql_details.", ".$sql_version.", raccourci=".db_format(@$_POST["raccourci"],"bool"));
				$fileTmp["id_fichier"] = $last_id_fichier = db_last_id();
				add_logs("ajout", $objet["fichier"], $fileTmp["id_fichier"]);
				// Affectation des droits d'accès !!
				affecter_droits_acces($objet["fichier"],$fileTmp["id_fichier"]);
			}

			////	Nom réel / chemins du fichier  +  Enregistre la version du fichier  +  Transfert vers le dossier final
			$nom_reel_fichier = $fileTmp["id_fichier"]."_".time().$fileTmp["extension"];
			$chemin_fichier	  = $chemin_dossier.$nom_reel_fichier;
			db_query("INSERT INTO gt_fichier_version SET id_fichier=".db_format($fileTmp["id_fichier"]).", nom=".db_format($fileTmp["name"]).", nom_reel=".db_format($nom_reel_fichier).", ".$sql_details.", ".$sql_version);
			if(isset($fileTmp["upload_html5"]))		{@copy($fileTmp["tmp_name"], $chemin_fichier);}
			else									{move_uploaded_file($fileTmp["tmp_name"], $chemin_fichier);}
			@chmod($chemin_fichier,0775);

			////	Optimise l'image ? (à partir de 10ko, si pris en charge par GD2, et si demandé)
			if(isset($_POST["optimiser"]) && filesize($chemin_fichier)>10240 && controle_fichier("image_gd",$chemin_fichier) && is_writable($chemin_fichier))
			{
				reduire_image($chemin_fichier, $chemin_fichier, $_POST["optimiser_taille"], $_POST["optimiser_taille"], 85);
				clearstatcache();//efface le cache linux pour MAJ la taille du fichier
				$fileTmp["size"] = filesize($chemin_fichier);
				db_query("UPDATE gt_fichier SET taille_octet='".$fileTmp["size"]."' WHERE id_fichier='".$fileTmp["id_fichier"]."'");
				db_query("UPDATE gt_fichier_version SET taille_octet='".$fileTmp["size"]."' WHERE id_fichier='".$fileTmp["id_fichier"]."' AND nom_reel='".$nom_reel_fichier."'");
			}

			////	Créé une vignette d'image ou de PDF : limité aux fichiers inférieurs à 3Mo
			if($fileTmp["size"] < (3*1048576)  &&  is_writable($chemin_fichier))
			{
				// Vignette Image
				if(controle_fichier("image_gd",$nom_reel_fichier))
				{
					$fileTmp["vignette"] = $fileTmp["id_fichier"].$fileTmp["extension"];
					reduire_image($chemin_fichier, PATH_MOD_FICHIER2.$fileTmp["vignette"], 200, 200);
				}
				// Vignette PDF
				elseif(controle_fichier("pdf",$nom_reel_fichier) && @class_exists('Imagick'))
				{
					$fileTmp["vignette"] = $fileTmp["id_fichier"].".jpg";
					$image_tmp = new Imagick($chemin_fichier."[0]");
					$image_tmp->writeImage(PATH_MOD_FICHIER2.$fileTmp["vignette"]);
					$image_tmp->clear();
					$image_tmp->destroy();
					reduire_image(PATH_MOD_FICHIER2.$fileTmp["vignette"], PATH_MOD_FICHIER2.$fileTmp["vignette"], 200, 200);//ne pas utiliser Imagik ("parse error" avec PHP4)
				}
				if(isset($fileTmp["vignette"]))	{db_query("UPDATE gt_fichier SET vignette='".$fileTmp["vignette"]."' WHERE id_fichier='".$fileTmp["id_fichier"]."'");}
			}
		}
	}

	////	ENVOI DE NOTIFICATION PAR MAIL
	////
	if(isset($_POST["notification"]) && control_upload()==true && @$last_id_fichier>0)
	{
		// On prends les droits d'accès du dernier fichier
		$liste_id_destinataires = users_affectes($objet["fichier"], $last_id_fichier);
		$objet_mail = $trad["FICHIER_mail_nouveau_fichier_cree"]." ".$_SESSION["user"]["nom"]." ".$_SESSION["user"]["prenom"];
		$contenu_mail = $trad["FICHIER_mail_nouveau_fichier_cree"]." ".$_SESSION["user"]["nom"]." ".$_SESSION["user"]["prenom"]." :<br><br>";
		foreach($_FILES as $fileTmp)	{  if($fileTmp["name"]!="") {$contenu_mail .= $fileTmp["name"]."<br>";}  }
		$options = array("notif"=>true);
		if(empty($_POST["notif_joindre_fichiers"]))		{$options["fichiers_joints"]=false;}
		envoi_mail($liste_id_destinataires, $objet_mail, texteNotification($contenu_mail), $options);
	}
}


////	NETTOYAGE DES DOSSIERS TMP  +  ENREGISTRE PREF. D'OPTIMISATION  +  FERMETURE DU POPUP  +  RECALCULE  $_SESSION["agora"]["taille_stock_fichier"]
////
if(isset($dossier_tmp_upload))	{rm($dossier_tmp_upload);}
nettoyer_tmp();
pref_user("optimiser_taille");
taille_stock_fichier(true);
////	FERMETURE (header.inc.php pour récupérer les javascripts ad'hoc..)
require_once PATH_INC."header.inc.php";
reload_close();
?>