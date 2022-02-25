<?php
////	IDENTIFIANT OU MAIL D'UN UTILISATEUR DEJA UTILISE ?
define("CONTROLE_SESSION",false);
require_once "../includes/global.inc.php";

// Vérif du mail
if(isset($_REQUEST["mail"])){
	$sql_verif = "mail=".db_format($_REQUEST["mail"],"insert_ext");
}
// Vérif de l'identifiant
elseif(isset($_REQUEST["identifiant"])){
	$sql_verif = "identifiant=".db_format($_REQUEST["identifiant"],"insert_ext");
	if(isset($_REQUEST["id_utilisateur"]))	{$sql_verif .= " AND id_utilisateur!='".@$_REQUEST["id_utilisateur"]."'";}
}

$verif_identifiant = db_valeur("SELECT count(*) FROM gt_utilisateur WHERE ".$sql_verif);
if($verif_identifiant > 0)	{echo "oui";}
?>