<?php
////	AJOUTE LA CONSULTATION d'UN FAVORIS DANS LES LOGS (requete_ajax())
require "commun.inc.php";
$lien_tmp = objet_infos($objet["lien"],$_GET["id_lien"]);
add_logs("consult", $objet["lien"], $lien_tmp["id_lien"], $lien_tmp["adresse"]);
?>