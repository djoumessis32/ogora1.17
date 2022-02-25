<?php
////	INIT
require "commun.inc.php";

////	TABLEAU DES LOGS
$contenu_export = "";
foreach(tabLogs() as $log_tmp){
	foreach($log_tmp as $log_champ)  { $contenu_export .= "\"".$log_champ."\";"; }
	$contenu_export .= "\n";
}

////	ENVOI DES LOGS
telecharger($_SESSION["agora"]["nom"]." - LOGS.csv", false, $contenu_export);
?>