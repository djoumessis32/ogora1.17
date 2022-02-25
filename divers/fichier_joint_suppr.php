<?php
////	SUPPRIME UN FICHIER JOINT D'UN OBJET
require_once "../includes/global.inc.php";
require_once ROOT_PATH.$_GET["module_path"]."/commun.inc.php";
$fichier_joint = db_ligne("SELECT * FROM gt_jointure_objet_fichier WHERE id_fichier='".intval($_GET["id_fichier"])."'");

if(droit_acces($objet[$fichier_joint["type_objet"]],$fichier_joint["id_objet"])>=2){
	suppr_fichier_joint($_GET["id_fichier"], $fichier_joint["nom_fichier"]);
	echo "oui";
}
?>
