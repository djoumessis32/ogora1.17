<?php
////	INIT
require "commun.inc.php";

////	SUPPRESSION / DESAFFECTATION D'UTILISATEUR
if(isset($_GET["id_utilisateur"]))	{suppr_utilisateur($_GET["id_utilisateur"],$_GET["action"]);}
elseif(isset($_GET["SelectedElems"]))
{
	foreach(SelectedElemsArray("utilisateur") as $id_utilisateur)	{suppr_utilisateur($id_utilisateur,$_GET["action"]);}
}

////	Redirection
redir("index.php");
?>