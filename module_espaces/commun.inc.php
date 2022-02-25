<?php
////	INITIALISATION
////
@define("MODULE_NOM","espaces");
@define("MODULE_PATH","module_espaces");
require_once "../includes/global.inc.php";
$objet["espace"]["tri"] = array("nom@@asc","nom@@desc","description@@asc","description@@desc");
controle_acces_admin("admin_espace");
?>