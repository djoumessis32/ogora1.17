<?php
////	INIT
require "commun.inc.php";

////	SUPPRESSION DE DOSSIER / LIEN / ELEMENTS
if(isset($_GET["id_dossier"]))		{ suppr_lien_dossier($_GET["id_dossier"]); }
elseif(isset($_GET["id_lien"]))		{ suppr_lien($_GET["id_lien"]); }
elseif(isset($_GET["SelectedElems"]))
{
	foreach(SelectedElemsArray("lien") as $id_lien)				{ suppr_lien($id_lien); }
	foreach(SelectedElemsArray("lien_dossier") as $id_dossier)	{ suppr_lien_dossier($id_dossier); }
}

////	Redirection
redir("index.php?id_dossier=".$_GET["id_dossier_retour"]);
?>
