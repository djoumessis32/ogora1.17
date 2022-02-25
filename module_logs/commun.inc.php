<?php
////	INIT
@define("MODULE_NOM","logs");
@define("MODULE_PATH","module_logs");
require_once "../includes/global.inc.php";
controle_acces_admin("admin_general");


////	RENVOI LES LOGS : PAR UTILISATEUR ET PAR ESPACE
////
function tabLogs()
{
	return db_tableau("SELECT  L.date, S.nom, L.module, L.ip, U.identifiant, L.action, L.commentaire  FROM  gt_logs L  LEFT JOIN gt_utilisateur U ON U.id_utilisateur=L.id_utilisateur  LEFT JOIN gt_espace S ON S.id_espace=L.id_espace");	
}
?>