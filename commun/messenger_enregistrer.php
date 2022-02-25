<?php
////	INIT
require_once "../includes/global.inc.php";
$sortie_html = "";


/////   LISTE DES MESSAGES
$liste_messages = db_tableau("SELECT DISTINCT T1.*,  T2.*  FROM  gt_utilisateur_messenger T1, gt_utilisateur T2  WHERE  T1.id_utilisateur_expediteur=T2.id_utilisateur  AND  T1.id_utilisateur_destinataires LIKE '%@@".$_SESSION["user"]["id_utilisateur"]."@@%'  ORDER BY  T1.date asc");
foreach($liste_messages as $message)
{
	// Liste des destinataires du message
	$phrase_dest = $trad["HEADER_MENU_envoye_a"]." ";
	foreach(text2tab($message["id_utilisateur_destinataires"]) as $id_user)  { $phrase_dest .= user_infos($id_user,"prenom")." ".user_infos($id_user,"nom").", "; }
	// On ajoute le message
	$sortie_html .= "<div style='font-size:16px;font-weight:bold;margin-bottom:5px;margin-top:20px;'>".$message["message"]."</div>";
	$sortie_html .= "<div style='font-size:12px;'> ".temps($message["date"])." >> ".$message["nom"]." ".$message["prenom"]."</div>";
	$sortie_html .= "<div style='font-size:12px;'>".$phrase_dest."</div>";
}


////		LANCEMENT DU TELECHARGEMENT
telecharger("messenger.html", null, utf8_decode($sortie_html));
?>