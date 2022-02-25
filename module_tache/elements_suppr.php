<?php
////	INIT
require "commun.inc.php";

////	SUPPRESSION DE DOSSIER / TACHE / ELEMENTS
if(isset($_GET["id_dossier"]))		{ suppr_tache_dossier($_GET["id_dossier"]); }
elseif(isset($_GET["id_tache"]))	{ suppr_tache($_GET["id_tache"]); }
elseif(isset($_GET["SelectedElems"]))
{
	foreach(SelectedElemsArray("tache") as $id_tache)			{ suppr_tache($id_tache); }
	foreach(SelectedElemsArray("tache_dossier") as $id_dossier)	{ suppr_tache_dossier($id_dossier); }
}

///	Redirection
redir("index.php?id_dossier=".$_GET["id_dossier_retour"]);
?>