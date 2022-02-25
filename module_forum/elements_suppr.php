<?php
////	INIT
require "commun.inc.php";

////	SUPPRESSION DU SUJET / MESSAGE
if(isset($_GET["id_sujet"]))		{ suppr_sujet($_GET["id_sujet"]); }
if(isset($_GET["id_message"]))		{ suppr_message($_GET["id_message"]); }

////	Redirection
if(isset($_GET["id_sujet_retour"]))	{ redir("sujet.php?id_sujet=".$_GET["id_sujet_retour"]); }
else								{ redir("index.php?num_page=".$_GET["num_page"]); }
?>
