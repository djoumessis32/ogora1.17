<?php
////	INIT
require_once "../includes/global.inc.php";
$_SESSION["cfg"]["espace"]["messenger_dernier_affichage"] = time();
$_SESSION["cfg"]["espace"]["messenger_alerte"] = 0;

////	SUPPRESSION DES ANCIENS MESSAGES "PERIMÉS" DU MESSENGER
db_query("DELETE FROM gt_utilisateur_messenger WHERE date < '".(time() - duree_messages_messenger)."'");


////	LISTE DES MESSAGES
////
$liste_messages = db_tableau("SELECT DISTINCT  T1.*, T2.*  FROM  gt_utilisateur_messenger T1, gt_utilisateur T2  WHERE  T1.id_utilisateur_expediteur=T2.id_utilisateur  AND  T1.id_utilisateur_destinataires LIKE '%@@".$_SESSION["user"]["id_utilisateur"]."@@%'  ORDER BY T1.date asc");
foreach($liste_messages as $infos_elem)
{
	// HEURE & DESTINATAIRES DU MESSAGE
	$heure_destinataires = $trad["HEADER_MENU_envoye_a"]." ".strftime("%H:%M",$infos_elem["date"])." :";
	foreach(text2tab($infos_elem["id_utilisateur_destinataires"]) as $id_user){
		if($infos_elem["id_utilisateur"]!=$id_user)		$heure_destinataires .= "<div style='margin-top:5px;'>".auteur($id_user)."</div>";
	}
	// MESSAGE
	echo "<div style='display:table-row;cursor:help;font-weight:bold;' ".infobulle("<div style='display:table-cell;vertical-align:middle;padding:5px;'>".$heure_destinataires."</div><div style='display:table-cell'>".photo_user($infos_elem,90)."</div>").">
			<div style='display:table-cell;padding:4px;width:80px;text-align:right;'>".$infos_elem["prenom"]."</div>
			<div style='display:table-cell;padding:4px;color:".$infos_elem["couleur"].";'>".$infos_elem["message"]."</div>
		</div>";
}
?>
