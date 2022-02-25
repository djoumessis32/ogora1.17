<?php
////	INIT
define("GLOBAL_EXPRESS",true);
define("IS_INSTALL_PAGE",true);
require_once "../includes/global.inc.php";

////	CONNEXION À LA BDD
define("db_host", $_REQUEST["db_host"]);
define("db_login", $_REQUEST["db_login"]);
define("db_password", $_REQUEST["db_password"]);
define("db_name",$_REQUEST["db_name"]);
$connexion_mysql = db_connexion(false,false);
$connexion_bdd = db_connexion(false);

////	CONNEXION A LA SGBD ?  /  CONNEXION A LA BDD ?  /  VERSION DE MYSQL OK ?  /  BDD CONTIENT DES TABLES D'AGORA ?
if($connexion_mysql===false)									{echo "connexion_mysql_pas_ok";}
elseif($connexion_bdd===false)									{echo "connexion_bdd_pas_ok";}
elseif(version_compare(db_version(),"4.2.0",">=")==false)		{echo "mysql_version_pas_ok : ".db_version();}
elseif(db_query("SELECT * FROM gt_agora_info",false)!=false)	{echo "bdd_existe_pas_ok";}
?>