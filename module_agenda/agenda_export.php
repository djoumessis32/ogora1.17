<?php
////	INITIALISATION
////
require "commun.inc.php";
if($AGENDAS_AFFICHES[$_REQUEST["id_agenda"]]["droit"]<3)	exit();


////	PREPARATION DU FICHIER .ICAL
////
$liste_evenements = liste_evenements($_REQUEST["id_agenda"], (time()-(86400*30)), (time()+(86400*3650)), false); // T-30jours => T+10ans
$fichier_ical = fichier_ical($liste_evenements);


////	ENVOI PAR MAIL / TELECHARGEMENT
////
$nom_fichier = $AGENDAS_AFFICHES[$_REQUEST["id_agenda"]]["titre"]." - ".strftime("%d-%m-%Y").".ics";
if(isset($_REQUEST["envoi_mail"]))
{
	// créé un fichier temporaire
	$fichier_tmp = PATH_TMP.uniqid(mt_rand()).$nom_fichier;
	$fp = fopen($fichier_tmp, "w");
	fwrite($fp, $fichier_ical);
	fclose($fp);
	$_FILES[] = array("error"=>0, "type"=>"text/Calendar", "name"=>$nom_fichier, "tmp_name"=>$fichier_tmp);
	// Envoi le fichier par mail + redirection
	envoi_mail($_SESSION["user"]["mail"], $nom_fichier, $nom_fichier, array("envoi_fichiers"=>true));
	unlink($fichier_tmp);
	redir("index.php");
}
else
{
	header("Content-Type: text/Calendar");
	header("Content-Disposition: inline; filename=\"".$nom_fichier."\"");
	echo $fichier_ical;
}
?>