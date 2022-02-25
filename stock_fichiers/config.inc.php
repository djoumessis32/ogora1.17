<?php
// CONNEXION À LA BDD
define("db_host", "localhost");
define("db_login", "root");
define("db_password", "admin");
define("db_name", "agora");

// AGORA EN MAINTENANCE ?  CONTROLE L'ADRESSE IP ?
define("agora_maintenance", false);
define("controle_ip", true);

// ESPACE DISQUE / NB USERS
define("limite_espace_disque", "10737418240");
define("limite_nb_users", "10000");

// DUREE DE SESSION DU LIVECOUNTER + DUREE CONSERVATION MESSAGES DU MESSENGER
define("duree_livecounter", "45");
define("duree_messages_messenger", 7200);


//LE SALT NE DOIT ETRE AJOUTE QU'APRES INSTALL -> DOIT ETRE ABSENT PAS DEFAUT !!!
