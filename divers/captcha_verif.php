<?php
////	INIT
define("GLOBAL_EXPRESS",true);
require_once "../includes/global.inc.php";
echo (!empty($_GET["captcha"]) && @$_GET["captcha"]==$_SESSION["captcha"])  ?  "true"  :  "false";
?>