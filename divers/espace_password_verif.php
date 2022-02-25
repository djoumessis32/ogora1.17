<?php
define("CONTROLE_SESSION",false);
require_once "../includes/global.inc.php";
$password = db_valeur("SELECT count(*) FROM gt_espace WHERE id_espace='".intval($_REQUEST["id_espace"])."' AND password='".$_REQUEST["password"]."'");
if($password > 0)	echo "oui";
?>