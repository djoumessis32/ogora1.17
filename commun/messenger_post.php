<?php
////	INIT
require_once "../includes/global.inc.php";


////	ON AJOUTE LE MESSAGE
////
$_REQUEST["tab_users_messenger"][] = $_SESSION["user"]["id_utilisateur"];
db_query("INSERT INTO  gt_utilisateur_messenger SET id_utilisateur_expediteur='".$_SESSION["user"]["id_utilisateur"]."', id_utilisateur_destinataires=".db_format(tab2text($_REQUEST["tab_users_messenger"])).", message=".db_format(rawurldecode($_REQUEST["texte_messenger"])).", couleur=".db_format($_REQUEST["couleur_messenger"]).", date='".time()."'");
// Couleur et liste des utilisateurs sélectionnés
$_SESSION["couleur_messenger"] = $_REQUEST["couleur_messenger"];
$_SESSION["messenger_users_dest"] = $_REQUEST["tab_users_messenger"];

// Déconnexion à la bdd
db_close();
?>
