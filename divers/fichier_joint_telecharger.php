<?php
////	SUPPRIME UN FICHIER JOINT D'UN OBJET
require_once "../includes/global.inc.php";
require_once ROOT_PATH.$_GET["module_path"]."/commun.inc.php";
$fichier = db_ligne("SELECT * FROM gt_jointure_objet_fichier WHERE id_fichier='".intval($_GET["id_fichier"])."'");
droit_acces_controler($objet[$fichier["type_objet"]], $fichier["id_objet"], 1);

////		LANCEMENT DU TELECHARGEMENT
$chemin_fichier = PATH_OBJECT_FILE.$_GET["id_fichier"].extension($fichier["nom_fichier"]);
telecharger($fichier["nom_fichier"], $chemin_fichier);
?>