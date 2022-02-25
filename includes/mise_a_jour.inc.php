<?php
////	INITIALISATIONS
////
define("VERSION_AGORA", "2.17.5.1");
define("VERSION_MAJ_DATE", 20150923);


////	MISE A JOUR
////
if(version_compare($_SESSION["agora"]["version_agora"],VERSION_AGORA,"<")  ||  $_SESSION["agora"]["mise_a_jour"] < VERSION_MAJ_DATE)
{
	////	ON LANCE LA MISE A JOUR  (SI PAS DANS L'ESPACE + FICHIER CONFIG ACCESSIBLE EN ECRITURE)
	////
	if(CONTROLE_SESSION==true)											{ redir(ROOT_PATH."index.php?deconnexion=oui"); }
	elseif(is_writable(PATH_STOCK_FICHIERS."config.inc.php")==false)	{ $_GET["msg_alerte"] = "miseajourconfig"; }
	else
	{
		////	ON SAUVEGARDE LA BASE DE DONNEES !
		db_sauvegarde();

		////	AJOUTE LE CHAMP "MISE_A_JOUR"
		db_maj_champ_ajoute("2.11.0", "gt_agora_info", "mise_a_jour", "ALTER TABLE gt_agora_info ADD mise_a_jour INT UNSIGNED");

		////	AJOUTE TABLE "INVITATIONS"
		db_maj_table_ajoute("2.11.0", "gt_invitation", "CREATE TABLE gt_invitation (id_invitation TINYTEXT, id_espace int, nom TINYTEXT, prenom TINYTEXT, mail TINYTEXT, pass TINYTEXT, date DATETIME)");

		////	AJOUTE CHAMPS PARAMETRAGE AGENDA SUR TABLE "gt_utilisateur"
		db_maj_champ_ajoute("2.11.0", "gt_utilisateur", "agenda_plage_horaire", "ALTER TABLE gt_utilisateur ADD agenda_plage_horaire TINYTEXT");

		////	AJOUTE JOINTURE DANS "gt_jointure_objet" POUR LES ELEMENTS DES DOSSIERS RACINE
		$tab_elements = array("tache","lien","fichier","contact");
		$espaces = db_colonne("SELECT id_espace FROM gt_espace");
		foreach($tab_elements as $elem)
		{
			$elems_racine = db_colonne("SELECT id_".$elem." FROM gt_".$elem." WHERE id_dossier='1' AND id_".$elem." NOT IN (SELECT id_objet as id_".$elem." FROM gt_jointure_objet WHERE type_objet='".$elem."')");
			// On ajoute les jointures manquantes : chaque element est rattaché à chaque espace en lecture
			foreach($espaces as $id_espace){
				foreach($elems_racine as $id_elem)	{ db_maj_query("2.11.0", "INSERT INTO gt_jointure_objet SET type_objet='".$elem."', id_objet='".intval($id_elem)."', id_espace='".intval($id_espace)."', tous='1', id_utilisateur=null, droit='1'"); }
			}
		}

		////	AJOUTE LE CHAMPS "date_crea" DANS LA TABLE "gt_utilisateur"
		db_maj_champ_ajoute("2.11.0", "gt_utilisateur", "date_crea", "ALTER TABLE gt_utilisateur ADD date_crea DATETIME DEFAULT NULL AFTER commentaire");

		////	CHANGE LE NOM DU DOSSIER DES FONDS D'ECRAN & ON DEPLACE LE CHAMPS "fond_ecran" DE LA TABLE gt_espace VERS gt_agora_info
		if(is_dir(PATH_STOCK_FICHIERS."fond_ecran_espace/"))	{rename(PATH_STOCK_FICHIERS."fond_ecran_espace/", PATH_STOCK_FICHIERS."fond_ecran/");}
		chmod_recursif(PATH_STOCK_FICHIERS);
		db_maj_champ_ajoute("2.11.0", "gt_agora_info", "fond_ecran", "ALTER TABLE gt_agora_info ADD fond_ecran TEXT AFTER langue");

		////	AJOUTER LE DOSSIER STOCK_FICHIERS/TMP
		if(is_dir(PATH_STOCK_FICHIERS."tmp")==false)	{ mkdir(PATH_STOCK_FICHIERS."tmp");  @chmod(PATH_STOCK_FICHIERS."tmp",0775); }

		////	AJOUTER CHAMPS "password" DANS LA TABLE "gt_espace"
		db_maj_champ_ajoute("2.11.0", "gt_espace", "password", "ALTER TABLE gt_espace ADD password TINYTEXT DEFAULT NULL AFTER description");

		////	AJOUTER CHAMPS "id_utilisateur" DANS LA TABLE "gt_invitation"
		db_maj_champ_ajoute("2.11.0", "gt_invitation", "id_utilisateur", "ALTER TABLE gt_invitation ADD id_utilisateur INT UNSIGNED AFTER id_invitation");

		////	ON CHANGE LE NOM DU CHAMP "afficher_tdb" en "raccourci"
		$tab_tdb_raccourci = array("gt_tache", "gt_tache_dossier", "gt_lien", "gt_lien_dossier", "gt_contact", "gt_contact_dossier", "gt_fichier", "gt_fichier_dossier", "gt_forum_sujet");
		foreach($tab_tdb_raccourci as $tab_tmp)
		{
			db_maj_champ_rename("2.11.0", $tab_tmp, "afficher_tdb", "ALTER TABLE ".$tab_tmp." CHANGE `afficher_tdb` `raccourci` TINYINT DEFAULT NULL");
			db_maj_champ_ajoute("2.11.0", $tab_tmp, "raccourci", "ALTER TABLE ".$tab_tmp." ADD raccourci TINYINT");
		}

		////	ON MODIFIE LA "PRIORITE" DES TACHES
		db_maj_champ_rename("2.11.0", "gt_tache", "important", "ALTER TABLE gt_tache CHANGE `important` `priorite` TINYTEXT DEFAULT NULL");
		db_maj_query("2.11.0", "UPDATE gt_tache SET priorite='haute' WHERE priorite='1'");
		db_maj_query("2.11.0", "UPDATE gt_tache SET priorite=null WHERE priorite='0'");

		////	AJOUTE LA TABLE DES PREFERENCES UTILISATEUR  &  SUPPRIME LE CHAMP "module_actualite_tri" de "gt_agora_info"
		db_maj_table_ajoute("2.11.0", "gt_utilisateur_preferences", "CREATE TABLE gt_utilisateur_preferences (id_utilisateur INT UNSIGNED, cle TINYTEXT, valeur TINYTEXT)");
		if(db_maj_champ_ajoute("2.11.0", "gt_agora_info","module_actualite_tri")==true)		{db_maj_query("2.11.0", "ALTER TABLE gt_agora_info DROP module_actualite_tri");}

		////	AJOUTE LES CHAMPS SUR LA PERIODICITE DES EVENEMENTS
		db_maj_champ_ajoute("2.11.0", "gt_agenda_evenement", "period_jour_semaine", "ALTER TABLE gt_agenda_evenement ADD period_jour_semaine TINYTEXT");
		db_maj_champ_ajoute("2.11.0", "gt_agenda_evenement", "period_jour_mois", "ALTER TABLE gt_agenda_evenement ADD period_jour_mois TINYTEXT");
		db_maj_champ_ajoute("2.11.0", "gt_agenda_evenement", "period_mois", "ALTER TABLE gt_agenda_evenement ADD period_mois TINYTEXT");
		db_maj_champ_ajoute("2.11.0", "gt_agenda_evenement", "period_annee", "ALTER TABLE gt_agenda_evenement ADD period_annee TINYINT");
		db_maj_champ_ajoute("2.11.0", "gt_agenda_evenement", "period_date_fin", "ALTER TABLE gt_agenda_evenement ADD period_date_fin DATE DEFAULT NULL");

		////	SUPPRIME LE CHAMP SUR L'AFFICHAGE PAR DEFAUT DE L'AGENDA
		if(db_maj_champ_ajoute("2.11.0", "gt_utilisateur","agenda_affichage")==true)	{db_maj_query("2.11.0", "ALTER TABLE gt_utilisateur DROP agenda_affichage");}

		////	AJOUTE LE CHAMP DES OPTIONS DE MODULE POUR CHAQUE ESPACE
		db_maj_champ_ajoute("2.11.0", "gt_jointure_espace_module", "options", "ALTER TABLE gt_jointure_espace_module ADD options TEXT");

		////	SI LE FOND D'ECRAN PAR DEFAUT EST NON DEFINI
		if(db_valeur("SELECT fond_ecran FROM gt_agora_info")=="")	{db_maj_query("2.11.0", "UPDATE gt_agora_info SET fond_ecran='default@@1.jpg'");}

		////	AJOUT DU CHAMP D'EXCEPTION DE PERIODICITE
		db_maj_champ_ajoute("2.11.0", "gt_agenda_evenement", "period_date_exception", "ALTER TABLE gt_agenda_evenement ADD period_date_exception TEXT AFTER period_date_fin");

		////	AJOUT DU CHAMP D'ACTIVATION DE L'AGENDA
		db_maj_champ_ajoute("2.11.0", "gt_utilisateur", "agenda_desactive", "ALTER TABLE gt_utilisateur ADD agenda_desactive TINYINT");

		////	AJOUTE CHAMPS "date_debut" DANS LA TABLE "gt_tache"  &  CREATION TABLE "gt_tache_responsable"
		db_maj_champ_ajoute("2.11.0", "gt_tache", "date_debut", "ALTER TABLE gt_tache ADD date_debut DATETIME DEFAULT NULL AFTER avancement");
		db_maj_table_ajoute("2.11.0", "gt_tache_responsable", "CREATE TABLE gt_tache_responsable (id_tache INT UNSIGNED, id_utilisateur INT UNSIGNED)");

		////	AJOUTE TABLE DES FICHIERS DES OBJETS (note, evenement, etc)  +  CREATION DOSSIER
		if(is_dir(PATH_OBJECT_FILE)!=true)	{mkdir(PATH_OBJECT_FILE);}
		@chmod(PATH_OBJECT_FILE,0775);
		db_maj_table_ajoute("2.11.0", "gt_jointure_objet_fichier", "CREATE TABLE gt_jointure_objet_fichier (id_fichier INT UNSIGNED AUTO_INCREMENT, nom_fichier TEXT, type_objet TEXT, id_objet INT UNSIGNED, PRIMARY KEY (id_fichier))");

		////	CORRECTIF TABLE TACHE ABSENTES (erreur d'install)
		db_maj_table_ajoute("2.11.0", "gt_tache", "CREATE TABLE gt_tache (id_tache INT UNSIGNED AUTO_INCREMENT, id_dossier INT UNSIGNED, id_utilisateur INT UNSIGNED, invite TEXT, titre TEXT, description TEXT, priorite TINYTEXT, avancement TINYINT, date_debut DATETIME, date_fin DATETIME, date DATETIME, raccourci TINYINT, PRIMARY KEY (id_tache))");

		////	CREATION DU CHAMP "skin" TABLE PARAMETRAGE
		db_maj_champ_ajoute("2.11.0", "gt_agora_info", "skin", "ALTER TABLE gt_agora_info ADD skin TINYTEXT");

		////	CREATION DES FONDS D'ECRAN DE L'ESPACE
		db_maj_champ_ajoute("2.11.0", "gt_espace", "fond_ecran", "ALTER TABLE gt_espace ADD fond_ecran TEXT");

		////	SUPPRIME LES FICHIERS JOINTS "FANTOMES" DES EVENEMENTS
		$fichiers_fantome = db_tableau("SELECT * FROM gt_jointure_objet_fichier WHERE type_objet='evenement' AND id_objet='0'");
		foreach($fichiers_fantome as $fichier_tmp)	{ suppr_fichier_joint($fichier_tmp["id_fichier"], $fichier_tmp["nom_fichier"]); }

		////	CREATION DU CHAMP "A LA UNE"
		db_maj_champ_ajoute("2.11.0", "gt_actualite", "une", "ALTER TABLE gt_actualite ADD une TINYINT");

		////	CREATION DU CHAMP "edition_popup" & "footer_html" dans la table "gt_agora_info"
		db_maj_champ_ajoute("2.11.0", "gt_agora_info", "edition_popup", "ALTER TABLE gt_agora_info ADD edition_popup TINYINT");
		db_maj_champ_ajoute("2.11.0", "gt_agora_info", "footer_html", "ALTER TABLE gt_agora_info ADD footer_html TEXT");

		////	AJOUT DU CHAMP "COMPETENCES" ET "HOBBIES" DANS LA TABLE "gt_utilisateur" & "gt_contact"
		db_maj_champ_ajoute("2.11.0", "gt_utilisateur", "competences", "ALTER TABLE gt_utilisateur ADD competences TEXT AFTER siteweb");
		db_maj_champ_ajoute("2.11.0", "gt_utilisateur", "hobbies", "ALTER TABLE gt_utilisateur ADD hobbies TEXT AFTER competences");
		db_maj_champ_ajoute("2.11.0", "gt_contact", "competences", "ALTER TABLE gt_contact ADD competences TEXT AFTER siteweb");
		db_maj_champ_ajoute("2.11.0", "gt_contact", "hobbies", "ALTER TABLE gt_contact ADD hobbies TEXT AFTER competences");

		////	ON CRYPTE LES MOTS DE PASSE AVEC sha1 (sur 40 caracteres)
		if(@function_exists("sha1"))	{db_maj_query("2.11.0", "UPDATE gt_utilisateur SET pass=sha1(pass) WHERE CHAR_LENGTH(pass)!=40");}

		////	CREATION DE LA TABLE DES THEMES DU FORUM
		db_maj_table_ajoute("2.11.0", "gt_forum_theme", "CREATE TABLE gt_forum_theme (id_theme INT UNSIGNED AUTO_INCREMENT, id_utilisateur INT UNSIGNED, id_espaces TEXT, titre TINYTEXT, description TEXT DEFAULT NULL, couleur TEXT, PRIMARY KEY (id_theme))");
		db_maj_champ_ajoute("2.11.0", "gt_forum_sujet", "id_theme", "ALTER TABLE gt_forum_sujet ADD id_theme INT UNSIGNED AFTER date");

		////	ARCHIVAGE DES ACTUALITES
		db_maj_champ_ajoute("2.11.0", "gt_actualite", "offline", "ALTER TABLE gt_actualite ADD offline TINYINT");
		db_maj_champ_ajoute("2.11.0", "gt_actualite", "date_offline", "ALTER TABLE gt_actualite ADD date_offline DATETIME DEFAULT NULL");

		////	CHAMP POUR LA DATE EFFECTIVE DE LA MISE A JOUR
		db_maj_champ_ajoute("2.11.0", "gt_agora_info", "mise_a_jour_effective", "ALTER TABLE gt_agora_info ADD mise_a_jour_effective INT UNSIGNED AFTER mise_a_jour");

		////	CHAMP POUR LE LOGO EN BAS A DROITE
		db_maj_champ_ajoute("2.11.0", "gt_agora_info", "logo", "ALTER TABLE gt_agora_info ADD logo TEXT AFTER fond_ecran");

		////	CHAMP POUR LA DATE D'AJOUT DU DERNIER SUJET + MISE A JOUR DES INFOS
		db_maj_champ_ajoute("2.11.0", "gt_forum_sujet", "date_dernier_message", "ALTER TABLE gt_forum_sujet ADD date_dernier_message DATETIME DEFAULT NULL");
		db_maj_champ_ajoute("2.11.0", "gt_forum_sujet", "auteur_dernier_message", "ALTER TABLE gt_forum_sujet ADD auteur_dernier_message TINYTEXT DEFAULT NULL");
		db_maj_champ_ajoute("2.11.0", "gt_forum_sujet", "users_consult_dernier_message", "ALTER TABLE gt_forum_sujet ADD users_consult_dernier_message TEXT DEFAULT NULL");
		$champ_existe = db_maj_champ_ajoute("2.11.0", "gt_forum_sujet", "users_notifier_dernier_message", "ALTER TABLE gt_forum_sujet ADD users_notifier_dernier_message TEXT DEFAULT NULL");
		$existe_date_crea = db_query("SELECT date_crea FROM gt_forum_message");// MaJ 2.12.5 et utilisé par maj_dernier_message()
		if($champ_existe==false && $existe_date_crea==true){
			include_once ROOT_PATH."module_forum/commun.inc.php";
			foreach(db_colonne("SELECT id_sujet FROM gt_forum_sujet") as $id_sujet_tmp)		{ maj_dernier_message($id_sujet_tmp); }
		}

		////	CREATION DU CHAMP "precedente_connexion"
		db_maj_champ_ajoute("2.11.0", "gt_utilisateur", "precedente_connexion", "ALTER TABLE gt_utilisateur ADD precedente_connexion INT UNSIGNED AFTER derniere_connexion");

		////	CREATION DU CHAMP "charge_jour_homme", "budget_disponible", "budget_engage", "devise" sur la table "gt_tache"
		db_maj_champ_ajoute("2.11.0", "gt_tache", "charge_jour_homme", "ALTER TABLE gt_tache ADD charge_jour_homme FLOAT AFTER avancement");
		db_maj_champ_ajoute("2.11.0", "gt_tache", "budget_disponible", "ALTER TABLE gt_tache ADD budget_disponible INT UNSIGNED AFTER charge_jour_homme");
		db_maj_champ_ajoute("2.11.0", "gt_tache", "budget_engage", "ALTER TABLE gt_tache ADD budget_engage INT UNSIGNED AFTER budget_disponible");
		db_maj_champ_ajoute("2.11.0", "gt_tache", "devise", "ALTER TABLE gt_tache ADD devise TINYTEXT AFTER budget_engage");
		db_maj_query("2.11.0", "UPDATE gt_tache SET date_fin=REPLACE(date_fin,'00:00:00','23:59:59')");

		////	NETTOYAGE DE PRIMPTEMPS
		db_maj_query("2.11.0", "DELETE FROM gt_jointure_objet WHERE id_objet='0'");
		db_maj_query("2.11.0", "ALTER TABLE gt_tache CHANGE date_debut date_debut DATE DEFAULT NULL, CHANGE date_fin date_fin DATE DEFAULT NULL");
		db_maj_query("2.11.0", "ALTER TABLE gt_agenda_evenement CHANGE period_date_fin period_date_fin DATE DEFAULT NULL");

		////	CREATION DU CHAMP  "editeur_text_mode" + "logo_url" + "messenger_desactive" + "libelle_module" + "tri_personnes"  SUR LA TABLE DE PARAMETRAGE
		db_maj_champ_ajoute("2.11.0", "gt_agora_info", "editeur_text_mode", "ALTER TABLE gt_agora_info ADD editeur_text_mode TINYTEXT AFTER edition_popup");
		$champ_existe = db_maj_champ_ajoute("2.11.0", "gt_agora_info", "logo_url", "ALTER TABLE gt_agora_info ADD logo_url TINYTEXT AFTER logo");
		if($champ_existe==false)	{db_maj_query("2.11.0", "UPDATE gt_agora_info SET logo_url='".AGORA_PROJECT_URL."'");}
		db_maj_champ_ajoute("2.11.0", "gt_agora_info", "messenger_desactive", "ALTER TABLE gt_agora_info ADD messenger_desactive TINYINT");
		db_maj_champ_ajoute("2.11.0", "gt_agora_info", "libelle_module", "ALTER TABLE gt_agora_info ADD libelle_module TINYTEXT");
		$champ_existe = db_maj_champ_ajoute("2.11.0", "gt_agora_info", "tri_personnes", "ALTER TABLE gt_agora_info ADD tri_personnes ENUM('nom','prenom') NOT NULL");
		if($champ_existe==false)	{db_maj_query("2.11.0", "UPDATE gt_agora_info SET tri_personnes='prenom'");}

		////	PASSAGE DE LA BASE DE DONNEES EN UTF8
		if(db_valeur("SELECT CHARSET(nom) from gt_agora_info")=="latin1")
		{
			// Modif de la base de données
			db_query("ALTER DATABASE `".db_name."` CHARACTER SET UTF8");
			// Modif de chaque table & de chaque colonne
			foreach(db_colonne("SHOW TABLES FROM `".db_name."` ") as $nom_table) {
				db_query("ALTER TABLE ".$nom_table." CHARACTER SET UTF8");
				db_query("ALTER TABLE ".$nom_table." CONVERT TO CHARACTER SET UTF8");
			}
		}

		////	SUPPRIME LES PREFERENCES ERRONNEES
		db_maj_query("2.11.0", "DELETE FROM gt_utilisateur_preferences WHERE  cle like '%.php%'");

		////	CREATION DU CHAMP "evt_affichage_couleur"
		db_maj_champ_ajoute("2.11.0", "gt_agenda", "evt_affichage_couleur", "ALTER TABLE gt_agenda ADD evt_affichage_couleur ENUM('background','border') NOT NULL");

		////	MODIFIE LE CHAMPS "priorité" de "gt_tache"
		db_maj_query("2.11.0", "UPDATE gt_tache SET priorite='1' WHERE priorite='basse'");
		db_maj_query("2.11.0", "UPDATE gt_tache SET priorite='2' WHERE priorite='moyenne'");
		db_maj_query("2.11.0", "UPDATE gt_tache SET priorite='3' WHERE priorite='haute'");
		db_maj_query("2.11.0", "UPDATE gt_tache SET priorite='4' WHERE priorite='critique'");

		////	MODIF .HTACCESS DU STOCK_FICHIERS :  AUTORISE FICHIERS XML / VIDEO ...
		$htaccess_new  = "Deny from all\n\n";
		$htaccess_new .= "<Files ~ \"\.(mp3|swf|jpe?g|gif|png|bmp|tiff|xml|mp4|mpe?g|avi|flv|ogv|webm|wmv|mov)$\">\n";
		$htaccess_new .= "Allow from all\n";
		$htaccess_new .= "</Files>\n";
		$fp = fopen(PATH_STOCK_FICHIERS.".htaccess", "w");
		fwrite($fp, $htaccess_new);
		fclose($fp);

		////	MODIF DES CHAMPS "date_debut" et "date_fin" de type "DATETIME"
		if(db_maj_champ_ajoute("2.11.0","gt_tache","heure_debut"))	{db_maj_query("2.11.0", "ALTER TABLE gt_tache DROP heure_debut");}
		if(db_maj_champ_ajoute("2.11.0","gt_tache","heure_fin"))	{db_maj_query("2.11.0", "ALTER TABLE gt_tache DROP heure_fin");}
		db_maj_query("2.11.0", "ALTER TABLE gt_tache CHANGE date_debut date_debut DATETIME DEFAULT NULL");
		db_maj_query("2.11.0", "ALTER TABLE gt_tache CHANGE date_fin date_fin DATETIME DEFAULT NULL");

		////	MODIF DES CHAMPS DE PERIODICITE DE L'AGENDA
		// Créé les nouveaux champs
		db_maj_champ_ajoute("2.11.0", "gt_agenda_evenement", "periodicite_type", "ALTER TABLE gt_agenda_evenement ADD periodicite_type TINYTEXT AFTER visibilite_contenu");
		db_maj_champ_ajoute("2.11.0", "gt_agenda_evenement", "periodicite_valeurs", "ALTER TABLE gt_agenda_evenement ADD periodicite_valeurs TINYTEXT AFTER periodicite_type");
		// Transfert des données
		db_maj_query("2.11.0", "UPDATE gt_agenda_evenement SET periodicite_type='jour_semaine', periodicite_valeurs=period_jour_semaine  WHERE period_jour_semaine is not null and period_jour_semaine!=0");
		db_maj_query("2.11.0", "UPDATE gt_agenda_evenement SET periodicite_type='jour_mois', periodicite_valeurs=period_jour_mois  WHERE period_jour_mois is not null and period_jour_mois!=0");
		db_maj_query("2.11.0", "UPDATE gt_agenda_evenement SET periodicite_type='mois', periodicite_valeurs=period_mois  WHERE period_mois is not null and period_mois!=0");
		db_maj_query("2.11.0", "UPDATE gt_agenda_evenement SET periodicite_type='annee', periodicite_valeurs=period_annee  WHERE period_annee is not null and period_annee!=0");
		// Supprime les anciens champs
		if(db_maj_champ_ajoute("2.11.0","gt_agenda_evenement","period_jour_semaine"))	{db_maj_query("2.11.0", "ALTER TABLE gt_agenda_evenement DROP period_jour_semaine, DROP period_jour_mois, DROP period_mois, DROP period_annee");}
		// Nettoyage divers
		db_maj_query("2.11.0", "UPDATE gt_agenda_evenement SET periodicite_valeurs=null WHERE periodicite_valeurs='' OR periodicite_valeurs='0'");
		db_maj_query("2.11.0", "UPDATE gt_agenda_evenement SET periodicite_type=null WHERE periodicite_valeurs is null");
		db_maj_query("2.11.0", "UPDATE gt_agenda_evenement SET period_date_exception=null WHERE period_date_exception=''");
		db_maj_query("2.11.0", "UPDATE gt_agenda_evenement SET period_date_fin=null WHERE period_date_fin like '%0000-00-00%'");
		db_maj_query("2.11.0", "UPDATE gt_utilisateur SET agenda_desactive=null WHERE agenda_desactive!=1");
		db_maj_query("2.11.0", "DELETE FROM gt_utilisateur_livecounter");

		////	CREATION DE LA TABLE DE GROUPE D'UTILISATEUR POUR CHAQUE ESPACE
		db_maj_table_ajoute("2.11.0", "gt_espace_groupe", "CREATE TABLE gt_espace_groupe (id_groupe INT UNSIGNED AUTO_INCREMENT, id_utilisateur INT UNSIGNED, id_espace INT UNSIGNED, titre TINYTEXT, id_utilisateurs TEXT, PRIMARY KEY (id_groupe))");

		////	UNIFICATION DES CHAMPS DE TYPE "ARRAY"
		db_maj_query("2.11.0", "UPDATE gt_forum_theme SET id_espaces=replace(replace(replace(id_espaces,'idid','id'), 'id','|'), '|','@@')");
		db_maj_query("2.11.0", "UPDATE gt_forum_theme SET id_espaces=null WHERE id_espaces=''");
		db_maj_query("2.11.0", "UPDATE gt_utilisateur_messenger SET  id_utilisateur_destinataires=replace(replace(replace(id_utilisateur_destinataires, 'idid','id'), 'id','|'), '|','@@')");
		db_maj_query("2.11.0", "UPDATE gt_utilisateur_preferences SET  valeur=replace(replace(replace(valeur, '##','|'), '@@','|'), '|','@@')");
		db_maj_query("2.11.0", "UPDATE gt_agora_info SET  fond_ecran=replace(replace(fond_ecran, '@@','|'), '|','@@')");		//pour versions test
		db_maj_query("2.11.0", "UPDATE gt_jointure_espace_module SET  options=replace(replace(options, '@@','|'), '|','@@')");	//idem
		db_maj_query("2.11.0", "UPDATE gt_espace_groupe SET id_utilisateurs=REPLACE(id_utilisateurs,'|','@@')");				//idem

		////	v2.11.0 : AJOUT DU CHAMP "TIMEZONE" DANS L'AGORA  ("no_control" : rattrape un bug de "version_compare()")
		db_maj_champ_ajoute("no_control", "gt_agora_info", "timezone", "ALTER TABLE gt_agora_info ADD timezone TINYTEXT DEFAULT NULL AFTER langue");

		////	v2.11.1 : MAJ DES FONDS D'ECRAN :  default@@default5.jpg  =>  default@@5.jpg
		db_maj_query("2.11.1", "UPDATE gt_agora_info SET fond_ecran=replace(replace(fond_ecran,'default.jpg','1.jpg') ,'default@@default','default@@')");
		db_maj_query("2.11.1", "UPDATE gt_espace SET fond_ecran=replace(replace(fond_ecran,'default.jpg','1.jpg') ,'default@@default','default@@')");

		////	v2.11.2 : DEPLACEMENT DES PLAGES HORAIRES DU PROFIL D'UTILISATEUR VERS L'EDITION DE L'AGENDA
		db_maj_champ_ajoute("2.11.2", "gt_agenda", "plage_horaire", "ALTER TABLE gt_agenda ADD plage_horaire TINYTEXT DEFAULT NULL");
		if(db_maj_champ_ajoute("2.11.2","gt_utilisateur","agenda_plage_horaire"))
		{
			foreach(db_tableau("SELECT id_utilisateur, agenda_plage_horaire FROM gt_utilisateur") as $user_tmp){
				if($user_tmp["agenda_plage_horaire"]!="")	{db_maj_query("2.11.2", "UPDATE gt_agenda SET plage_horaire='".$user_tmp["agenda_plage_horaire"]."' WHERE id_utilisateur='".$user_tmp["id_utilisateur"]."' AND type='utilisateur'");}
			}
		}
		db_maj_query("2.11.2", "ALTER TABLE gt_utilisateur DROP agenda_plage_horaire", false);

		////	v2.11.2 : MODIFIE LES PREFERENCES SUR L'AFFICHAGE DES DOSSIERS + AJOUTE LA DESCRIPTION DES THEMES DES FORUMS
		db_maj_query("2.11.2", "UPDATE gt_utilisateur_preferences SET cle=REPLACE(cle,'type_affichage_dossier_','type_affichage_')");
		db_maj_champ_ajoute("no_control", "gt_forum_theme", "description", "ALTER TABLE gt_forum_theme ADD description TEXT DEFAULT NULL AFTER titre");

		////	v2.11.2 : ON ETEND LES GROUPES D'UTILISATEUR A PLUSIEURS ESPACES
		if(db_maj_table_ajoute("2.11.2", "gt_espace_groupe"))  {db_maj_query("2.11.2", "RENAME TABLE gt_espace_groupe TO gt_utilisateur_groupe",false);}
		$champ_existe = db_maj_champ_ajoute("2.11.2", "gt_utilisateur_groupe", "id_espaces", "ALTER TABLE gt_utilisateur_groupe ADD id_espaces TEXT DEFAULT NULL AFTER id_utilisateurs");
		if($champ_existe==false){
			db_maj_query("2.11.2", "UPDATE gt_utilisateur_groupe SET id_espaces=CONCAT('@@',id_espace,'@@') WHERE id_espace is not null");
			db_maj_query("2.11.2", "ALTER TABLE gt_utilisateur_groupe DROP id_espace", false);
		}
		if(db_maj_table_ajoute("2.11.2","gt_espace_groupe") && db_maj_table_ajoute("2.11.2","gt_utilisateur_groupe"))	{db_maj_query("2.11.2", "DROP TABLE gt_espace_groupe", false);}

		////	v2.11.2 : ON CHANGE LE TYPE DU CHAMP VIGNETTE & ON AJOUTE LE NOM DU FICHIER
		db_maj_query("2.11.2", "ALTER TABLE gt_fichier CHANGE vignette vignette TINYTEXT DEFAULT NULL", false);
		db_maj_query("2.11.2", "UPDATE gt_fichier SET vignette=CONCAT(id_fichier,extension) WHERE vignette='1'");
		db_maj_query("2.11.2", "UPDATE gt_fichier SET vignette=null WHERE vignette='0'");

		////	v2.12.0 : CONVERSION DES LIMITES D'ESPACE DISQUE (1 Mo = 1048576 octets...)
		if(is_int(limite_espace_disque/1024000))	{$tab_valeurs_modif["limite_espace_disque"] = (limite_espace_disque/1024000) * 1048576;}

		////	v2.12.0 : NOUVELLE GESTION DES DROITS D'ACCES  ->  AJOUT DE  "TARGET"
		$champ_existe = db_maj_champ_ajoute("2.12.0", "gt_jointure_objet", "target", "ALTER TABLE gt_jointure_objet ADD target TINYTEXT NOT NULL AFTER id_espace");
		if($champ_existe==false)
		{
			// champs "droit" : "TINYINT" -> "float"
			db_maj_query("2.12.0", "ALTER TABLE gt_jointure_objet CHANGE droit droit FLOAT(3) UNSIGNED DEFAULT NULL");
			// Droit d'accès aux sujets : 2 -> 1.5 par défaut
			db_maj_query("2.12.0", "UPDATE gt_jointure_objet SET droit='1.5' WHERE type_objet='sujet' AND droit='2'");
			////	Ajoute le droit d'accès spécifique aux invités (objets affectés à tous les users d'un espace public)
			foreach(db_tableau("SELECT * FROM gt_jointure_objet WHERE tous='1' AND id_espace IN (select id_espace from gt_jointure_espace_utilisateur where invites='1')") as $objet_tmp)
			{
				// ajoute le droit écriture limité (1.5) pour les invités sur les dossiers en écriture, sinon lecture (1)
				$droit_tmp = ($objet_tmp["droit"]=="2" && preg_match("/dossier/i",$objet_tmp["type_objet"]))  ?  "1.5"  :  "1";
				db_maj_query("2.12.0", "INSERT INTO gt_jointure_objet SET type_objet='".$objet_tmp["type_objet"]."', id_objet='".$objet_tmp["id_objet"]."', id_espace='".$objet_tmp["id_espace"]."', target='invites', droit='".$droit_tmp."'");
			}
			////	Modifie chaque droit d'accès pour "tous" et "id_utilisateur"
			foreach(db_tableau("SELECT * FROM gt_jointure_objet WHERE tous > 0 OR id_utilisateur > 0") as $objet_tmp)
			{
				if($objet_tmp["tous"]>0)	{ $sql_select = "tous='1'";												$sql_new_value = "tous"; }
				else						{ $sql_select = "id_utilisateur='".$objet_tmp["id_utilisateur"]."'";	$sql_new_value = "U".$objet_tmp["id_utilisateur"]; }
				db_maj_query("2.12.0", "UPDATE  gt_jointure_objet  SET  target='".$sql_new_value."'  WHERE type_objet='".$objet_tmp["type_objet"]."' AND id_objet='".$objet_tmp["id_objet"]."' AND id_espace='".$objet_tmp["id_espace"]."' AND ".$sql_select);
			}
			////	SUPPRIME LES CHAMPS "tous" et "id_utilisateur"
			db_maj_query("2.12.0", "ALTER TABLE gt_jointure_objet DROP tous", false);
			db_maj_query("2.12.0", "ALTER TABLE gt_jointure_objet DROP id_utilisateur", false);

			////	AJOUT D'UN "GRAIN DE SEL" DANS LES MOTS DE PASSE DEJA CRYPTES EN SHA1..
			if(@function_exists("sha1"))
			{
				if(!defined("AGORA_SALT"))	 {define("AGORA_SALT","Ag0rA-Pr0j3cT");}
				foreach(db_tableau("select * from gt_utilisateur") as $user_tmp){
					//On ne fait pas "sha1(AGORA_SALT.sha1($user_tmp["pass"]))" car à ce stage le sha1 est dejà appliqué au pass..
					db_maj_query("2.12.0", "UPDATE gt_utilisateur SET pass='".sha1(AGORA_SALT.$user_tmp["pass"])."' WHERE id_utilisateur='".$user_tmp["id_utilisateur"]."'", false);
				}
			}

			////	PASSAGE DES CHAMPS TEXT "INVITE" EN TINYTEXT
			db_maj_query("2.12.0", "ALTER TABLE gt_tache_dossier CHANGE invite invite TINYTEXT DEFAULT NULL");
			db_maj_query("2.12.0", "ALTER TABLE gt_tache CHANGE invite invite TINYTEXT DEFAULT NULL");
			db_maj_query("2.12.0", "ALTER TABLE gt_lien_dossier CHANGE invite invite TINYTEXT DEFAULT NULL");
			db_maj_query("2.12.0", "ALTER TABLE gt_lien CHANGE invite invite TINYTEXT DEFAULT NULL");
			db_maj_query("2.12.0", "ALTER TABLE gt_contact_dossier CHANGE invite invite TINYTEXT DEFAULT NULL");
			db_maj_query("2.12.0", "ALTER TABLE gt_contact CHANGE invite invite TINYTEXT DEFAULT NULL");
			db_maj_query("2.12.0", "ALTER TABLE gt_fichier_dossier CHANGE invite invite TINYTEXT DEFAULT NULL");
			db_maj_query("2.12.0", "ALTER TABLE gt_fichier CHANGE invite invite TINYTEXT DEFAULT NULL");
			db_maj_query("2.12.0", "ALTER TABLE gt_fichier_version CHANGE invite invite TINYTEXT DEFAULT NULL");
			db_maj_query("2.12.0", "ALTER TABLE gt_forum_sujet CHANGE invite invite TINYTEXT DEFAULT NULL");
			db_maj_query("2.12.0", "ALTER TABLE gt_forum_message CHANGE invite invite TINYTEXT DEFAULT NULL");
			db_maj_query("2.12.0", "ALTER TABLE gt_agenda_evenement CHANGE invite invite TINYTEXT DEFAULT NULL");
			////	PASSAGE D'AUTRES CHAMPS TEXT EN TINYTEXT
			db_maj_query("2.12.0", "ALTER TABLE gt_jointure_objet_fichier CHANGE type_objet type_objet TINYTEXT DEFAULT NULL");
			db_maj_query("2.12.0", "ALTER TABLE gt_forum_theme CHANGE couleur couleur TINYTEXT DEFAULT NULL");

			////	OPTIMISATION DES CLES PRIMAIRES :  ELEMENT / DOSSIER
			db_maj_query("2.12.0", "ALTER TABLE gt_contact DROP PRIMARY KEY, ADD PRIMARY KEY(`id_contact`,`id_dossier`)");
			db_maj_query("2.12.0", "ALTER TABLE gt_tache DROP PRIMARY KEY, ADD PRIMARY KEY(`id_tache`,`id_dossier`)");
			db_maj_query("2.12.0", "ALTER TABLE gt_fichier DROP PRIMARY KEY, ADD PRIMARY KEY(`id_fichier`,`id_dossier`)");
			db_maj_query("2.12.0", "ALTER TABLE gt_lien DROP PRIMARY KEY, ADD PRIMARY KEY(`id_lien`,`id_dossier`)");
			////	OPTIMISATION DES CLES PRIMAIRES :  DOSSIER / DOSSIER PARENT
			db_maj_query("2.12.0", "UPDATE gt_contact_dossier SET id_dossier_parent='0' WHERE id_dossier='1'");
			db_maj_query("2.12.0", "UPDATE gt_tache_dossier SET id_dossier_parent='0' WHERE id_dossier='1'");
			db_maj_query("2.12.0", "UPDATE gt_fichier_dossier SET id_dossier_parent='0' WHERE id_dossier='1'");
			db_maj_query("2.12.0", "UPDATE gt_lien_dossier SET id_dossier_parent='0' WHERE id_dossier='1'");
			db_maj_query("2.12.0", "ALTER TABLE gt_contact_dossier DROP PRIMARY KEY, ADD PRIMARY KEY(`id_dossier`,`id_dossier_parent`)");
			db_maj_query("2.12.0", "ALTER TABLE gt_tache_dossier DROP PRIMARY KEY, ADD PRIMARY KEY(`id_dossier`,`id_dossier_parent`)");
			db_maj_query("2.12.0", "ALTER TABLE gt_fichier_dossier DROP PRIMARY KEY, ADD PRIMARY KEY(`id_dossier`,`id_dossier_parent`)");
			db_maj_query("2.12.0", "ALTER TABLE gt_lien_dossier DROP PRIMARY KEY, ADD PRIMARY KEY(`id_dossier`,`id_dossier_parent`)");
			////	OPTIMISATION DES CLES PRIMAIRES DE DIVERSES TABLES
			db_maj_query("2.12.0", "ALTER TABLE gt_agenda_jointure_evenement ADD PRIMARY KEY(`id_evenement`,`id_agenda`)");
			db_maj_query("2.12.0", "ALTER TABLE gt_tache_responsable ADD PRIMARY KEY(`id_tache`,`id_utilisateur`)");
		}

		////	v2.12.1 : MISE EN LIGNE DES ACTUALITES
		db_maj_champ_rename("2.12.1", "gt_actualite", "archive", "ALTER TABLE gt_actualite CHANGE archive offline TINYINT DEFAULT NULL");
		db_maj_champ_rename("2.12.1", "gt_actualite", "date_archivage", "ALTER TABLE gt_actualite CHANGE date_archivage date_offline DATETIME DEFAULT NULL");
		db_maj_champ_ajoute("2.12.1", "gt_actualite", "date_online", "ALTER TABLE gt_actualite ADD date_online DATETIME DEFAULT NULL AFTER offline");

		////	v2.12.3 : IDENTIFIANT DE RENOUVELLEMENT DE MOT DE PASSE
		db_maj_champ_ajoute("2.12.3", "gt_utilisateur", "id_newpassword", "ALTER TABLE gt_utilisateur ADD id_newpassword TINYTEXT DEFAULT NULL");

		////	v2.12.3 : CORRECTION DU BUG DE "id_message_parent" EN PRIMARY KEY (ne peut pas être "null")
		db_maj_query("2.12.3", "ALTER TABLE gt_forum_message DROP PRIMARY KEY, ADD PRIMARY KEY(`id_message`)");
		db_maj_query("2.12.3", "ALTER TABLE gt_forum_message CHANGE id_message_parent id_message_parent INT(10) UNSIGNED DEFAULT NULL");
		db_maj_query("2.12.3", "UPDATE gt_forum_message SET id_message_parent=null WHERE id_message_parent='0'");

		////	v2.12.4 : AJOUT DE LA TABLE DES LOGS
		db_maj_table_ajoute("2.12.4", "gt_logs", "CREATE TABLE gt_logs (action VARCHAR(50), module VARCHAR(50), type_objet VARCHAR(50), id_objet INT UNSIGNED, date DATETIME, id_utilisateur INT UNSIGNED, id_espace INT UNSIGNED, ip VARCHAR(100), commentaire VARCHAR(300), KEY action (action), KEY module (module), KEY type_objet (type_objet), KEY id_objet (id_objet), KEY date (date))");
		db_maj_champ_ajoute("2.12.4", "gt_fichier", "nb_downloads", "ALTER TABLE gt_fichier ADD nb_downloads INT UNSIGNED NOT NULL DEFAULT '0' AFTER vignette");
		db_maj_champ_ajoute("2.12.4", "gt_jointure_objet_fichier", "nb_downloads", "ALTER TABLE gt_jointure_objet_fichier ADD nb_downloads INT UNSIGNED NOT NULL DEFAULT '0'");
		db_maj_champ_ajoute("2.12.4", "gt_agora_info", "logs_jours_conservation", "ALTER TABLE gt_agora_info ADD logs_jours_conservation SMALLINT UNSIGNED DEFAULT '15'");

		////	v2.12.5 : MAJ DES LOGS
		db_maj_query("2.12.5", "UPDATE gt_logs SET action='consult2' WHERE action='telechargement'");
		db_maj_query("2.12.5", "ALTER TABLE gt_logs CHANGE commentaire commentaire VARCHAR(300) DEFAULT NULL");
		if(db_maj_champ_ajoute("2.12.5","gt_logs","id_log"))	{db_maj_query("2.12.5", "ALTER TABLE gt_logs DROP id_log");}

		////	v2.12.5 :  DEPLACE  "date", "id_utilisateur", "invite"  &  AJOUT DE  "date_modif", "id_utilisateur_modif"
		if(db_maj_champ_ajoute("2.12.5","gt_actualite","date_crea")==false)
		{
			// Fonction de récup du nom du dernier champ
			function after_last_field($nom_table, $champ_deplacer="")
			{
				$dernier_champ = db_tableau("SHOW COLUMNS FROM ".$nom_table);
				$dernier_champ = $dernier_champ[(count($dernier_champ)-1)]["Field"];
				if($champ_deplacer!=$dernier_champ)  {return " AFTER ".$dernier_champ;}
			}
			// Pour toutes les tables suivante : on déplace/créé les champs adéquats
			$tables_tmp = array("gt_actualite"=>"actualite", "gt_agenda_evenement"=>"evenement", "gt_contact"=>"contact", "gt_contact_dossier"=>"contact_dossier", "gt_fichier"=>"fichier", "gt_fichier_dossier"=>"fichier_dossier", "gt_fichier_version"=>"", "gt_forum_message"=>"message", "gt_forum_sujet"=>"sujet", "gt_historique_mails"=>"", "gt_invitation"=>"", "gt_lien"=>"lien", "gt_lien_dossier"=>"lien_dossier", "gt_tache"=>"tache", "gt_tache_dossier"=>"tache_dossier");
			foreach($tables_tmp as $nom_table => $type_objet)
			{
				// Déplace les champs en fin de table :  "date" (devient "date_crea"), "id_utilisateur" et "invite"
				db_maj_query("2.12.5", "ALTER TABLE ".$nom_table." CHANGE date date_crea DATETIME DEFAULT NULL ".after_last_field($nom_table,"date"), false);
				db_maj_query("2.12.5", "ALTER TABLE ".$nom_table." CHANGE id_utilisateur id_utilisateur INT DEFAULT NULL ".after_last_field($nom_table,"id_utilisateur"), false);
				if(db_maj_champ_ajoute("2.12.5",$nom_table,"invite"))	{db_maj_query("2.12.5", "ALTER TABLE ".$nom_table." CHANGE invite invite TINYTEXT DEFAULT NULL AFTER id_utilisateur", false);}
				// Table d'élément
				if(preg_match("/gt_fichier_version|gt_historique_mails|gt_invitation/i",$nom_table)==false)
				{
					// Ajoute les champs 'date_modif' et 'id_utilisateur_modif' en fin de table
					db_maj_champ_ajoute("2.12.5", $nom_table, "date_modif", "ALTER TABLE ".$nom_table." ADD `date_modif` DATETIME DEFAULT NULL");
					db_maj_champ_ajoute("2.12.5", $nom_table, "id_utilisateur_modif", "ALTER TABLE ".$nom_table." ADD `id_utilisateur_modif` INT DEFAULT NULL");
					// Récupère les dates de modif des logs
					foreach(db_tableau("SELECT * FROM gt_logs WHERE action='modif' AND type_objet='".$type_objet."'") as $log_tmp){
						$champ_id_objet = (preg_match("/dossier/i",$type_objet))  ?  "id_dossier"  :  "id_".$type_objet;
						db_maj_query("2.12.5", "UPDATE ".$nom_table."  SET  date_modif='".$log_tmp["date"]."', id_utilisateur_modif='".$log_tmp["id_utilisateur"]."'  WHERE ".$champ_id_objet."='".$log_tmp["id_objet"]."'", false);
					}
				}
			}
			// On modifie le tri des préférences
			db_maj_query("2.12.5", "UPDATE gt_utilisateur_preferences SET valeur=REPLACE(valeur,'date@@','date_crea@@')");
		}

		////	v2.12.7 :  Ajoute le champ "nom" à la table "gt_fichier_version" pour garder le nom d'origine du fichier
		$champ_existe = db_maj_champ_ajoute("2.12.7", "gt_fichier_version", "nom", "ALTER TABLE gt_fichier_version ADD nom TINYTEXT DEFAULT NULL AFTER id_fichier");
		if($champ_existe==false)
		{
			foreach(db_colonne("SELECT id_fichier FROM gt_fichier_version WHERE nom is null") as $id_fichier_tmp){
				$nom_tmp = db_valeur("SELECT nom FROM gt_fichier WHERE id_fichier='".intval($id_fichier_tmp)."'");
				db_query("UPDATE gt_fichier_version SET nom=".db_format($nom_tmp)." WHERE id_fichier='".intval($id_fichier_tmp)."'");
			}
		}

		////	v2.12.8 : rattrapage de "timezone" + "contacts"
		if(@$_SESSION["agora"]["timezone"]=="-12.00" || @$_SESSION["agora"]["timezone"]=="1")	{db_query("UPDATE gt_agora_info SET timezone='1.00'");}
		db_maj_champ_ajoute("2.12.8", "gt_contact", "date_modif", "ALTER TABLE gt_contact ADD `date_modif` DATETIME DEFAULT NULL");
		db_maj_champ_ajoute("2.12.8", "gt_contact", "id_utilisateur_modif", "ALTER TABLE gt_contact ADD `id_utilisateur_modif` INT DEFAULT NULL");

		////	v2.13.0 : ajout des "espaces" et "description" aux catégories d'événement
		db_maj_champ_ajoute("2.13.0", "gt_agenda_categorie", "id_espaces", "ALTER TABLE gt_agenda_categorie ADD `id_espaces` TEXT DEFAULT NULL AFTER id_utilisateur");
		db_maj_champ_ajoute("2.13.0", "gt_agenda_categorie", "description", "ALTER TABLE gt_agenda_categorie ADD `description` TEXT DEFAULT NULL AFTER titre");

		////	v2.13.0 : MODIFIE LE CHAMPS "date_crea" DANS LA TABLE "gt_utilisateur"
		db_maj_champ_rename("2.13.0", "gt_utilisateur", "date_creation", "ALTER TABLE gt_utilisateur CHANGE `date_creation` `date_crea` DATETIME DEFAULT NULL");

		////	v2.13.0 : AJOUTE LE CHAMPS "inscription_users" DANS LA TABLE "gt_espace"
		db_maj_champ_ajoute("2.13.0", "gt_espace", "inscription_users", "ALTER TABLE gt_espace ADD inscription_users TINYINT UNSIGNED DEFAULT NULL AFTER password");

		////	V2.13.0 : AJOUTE LA TABLE D'INSCRIPTION DES USERS
		db_maj_table_ajoute("2.13.0", "gt_utilisateur_inscription", "CREATE TABLE gt_utilisateur_inscription (id_inscription INT UNSIGNED AUTO_INCREMENT, id_espace INT UNSIGNED, nom TINYTEXT, prenom TINYTEXT, mail TINYTEXT, pass TINYTEXT, date DATETIME, PRIMARY KEY (id_inscription))");
		db_maj_champ_ajoute("2.13.0", "gt_utilisateur_inscription", "identifiant", "ALTER TABLE gt_utilisateur_inscription ADD identifiant TINYTEXT AFTER mail");
		db_maj_champ_ajoute("2.13.0", "gt_utilisateur_inscription", "message", "ALTER TABLE gt_utilisateur_inscription ADD message TEXT DEFAULT NULL AFTER pass");

		////	V2.13.0 : CORRECTIF POUR SUPPRIMER LES AGENDAS DES UTILISATEURS SUPPRIMES (old bug)
		db_query("DELETE FROM gt_agenda	WHERE type='utilisateur' AND id_utilisateur NOT IN (select id_utilisateur from gt_utilisateur)");

		////	V2.13.1 : AJOUT DU CHAMP "version_agora" DANS LA TABLE "gt_agora_info" & SUPRESSION DU CHAMP "version_agora" DANS "config.inc.php"
		db_maj_champ_ajoute("2.13.1", "gt_agora_info", "version_agora", "ALTER TABLE gt_agora_info ADD version_agora TINYTEXT AFTER mise_a_jour_effective");
		if(defined("version_agora"))	{modif_fichier_config(PATH_STOCK_FICHIERS."config.inc.php", null, array("version_agora"));}

		////	V2.13.2 : SUPPR LES CONSTANTES OBSOLETES DE CONFIG.INC.PHP
		if(defined("lang_defaut"))	{modif_fichier_config(PATH_STOCK_FICHIERS."config.inc.php", null, array("agora_installer","memory_limit","max_execution_time","taille_limit_vignette","lang_defaut"));}

		////	V2.14.0 : AJOUT DU CHAMP "ip_controle" dans "gt_utilisateur"
		$champ_existe = db_maj_champ_ajoute("2.14.0", "gt_utilisateur", "ip_controle", "ALTER TABLE gt_utilisateur ADD ip_controle TEXT DEFAULT NULL");
		if($champ_existe==false)
		{
			// On ajoute un "ip_controle" à l'utilisateur courant  (@@192.168.1.1@@  ou @@192.168.1.1@@127.0.0.1@@)
			foreach(db_tableau("SELECT * FROM gt_utilisateur_adresse_ip") as $ip_tmp)
			{
				$ip_controle_tmp = db_valeur("SELECT ip_controle FROM gt_utilisateur WHERE id_utilisateur='".$ip_tmp["id_utilisateur"]."'");
				$ip_controle_tmp = ($ip_controle_tmp=="")  ?  "@@".$ip_tmp["adresse_ip"]."@@"  :  $ip_controle_tmp.$ip_tmp["adresse_ip"]."@@";
				db_query("UPDATE gt_utilisateur SET ip_controle='".$ip_controle_tmp."' WHERE id_utilisateur='".$ip_tmp["id_utilisateur"]."'");
			}
			// On supprime la table des adresse IP de controle
			db_query("DROP TABLE  gt_utilisateur_adresse_ip");
		}

		////	V2.15.0 : AJOUT DU CHAMP "invitations_users" DANS "gt_espace"
		$champ_existe = db_maj_champ_ajoute("2.15.0", "gt_espace", "invitations_users", "ALTER TABLE gt_espace ADD invitations_users TINYINT DEFAULT NULL");
		if($champ_existe==false)
		{
			foreach(db_tableau("SELECT DISTINCT id_espace FROM gt_jointure_espace_utilisateur WHERE envoi_invitation='1' AND tous_utilisateurs='1'") as $espace_tmp){
				db_query("UPDATE gt_espace SET invitations_users='1' WHERE id_espace='".$espace_tmp["id_espace"]."'");
			}
			db_query("ALTER TABLE gt_jointure_espace_utilisateur DROP envoi_invitation");
		}

		////	V2.15.0 : SUPPR LA CONSTANTE "duree_livecounter_recharge" DE CONFIG.INC.PHP
		if(defined("duree_livecounter_recharge"))	{modif_fichier_config(PATH_STOCK_FICHIERS."config.inc.php", null, array("duree_livecounter_recharge"));}
		
		////	V2.15.1 : AJOUTE LE CHAMP "gt_espace"->"public" +++
		$champ_existe = db_maj_champ_ajoute("2.15.1", "gt_espace", "public", "ALTER TABLE gt_espace ADD public TINYINT DEFAULT NULL AFTER description");
		if($champ_existe==false)
		{
			// TRANSFERT LES DONNEES DE "espace public"  +  SUPPRIME "gt_jointure_espace_utilisateur"->"invite"
			foreach(db_tableau("SELECT DISTINCT id_espace FROM gt_jointure_espace_utilisateur WHERE invites='1'") as $espace_tmp){
				db_query("UPDATE gt_espace SET public='1' WHERE id_espace='".$espace_tmp["id_espace"]."'");
			}
			db_query("DELETE FROM gt_jointure_espace_utilisateur WHERE invites='1'");
			db_query("ALTER TABLE gt_jointure_espace_utilisateur DROP invites");
			// OPTIMISATION DES CLES PRIMAIRES DE DIVERSES TABLES (CHAMPS DONT LA CLAUSE 'WHERE' EST TOUT LE TEMPS PRESENTE)
			db_maj_query("2.15.1", "ALTER TABLE gt_fichier_version ADD INDEX (`id_fichier`)");
			db_maj_query("2.15.1", "ALTER TABLE gt_jointure_espace_module ADD INDEX (`id_espace`)");
			db_maj_query("2.15.1", "ALTER TABLE gt_jointure_espace_utilisateur ADD INDEX (`id_espace`)");
			db_maj_query("2.15.1", "ALTER TABLE `gt_utilisateur_preferences` ADD INDEX (`id_utilisateur`)");
			//  OPTIMISE LA TABLE "gt_jointure_objet"
			db_maj_query("2.15.1", "ALTER TABLE gt_jointure_objet CHANGE type_objet type_objet TINYTEXT DEFAULT NULL");
		}

		////	V2.16.0 : AJOUTE LES CHAMP RELATIFS A UNE CONNEXION LDAP
		$champ_existe = db_maj_champ_ajoute("2.16.0", " gt_agora_info", "ldap_server", "ALTER TABLE gt_agora_info ADD ldap_server TINYTEXT DEFAULT NULL");
		$champ_existe = db_maj_champ_ajoute("2.16.0", " gt_agora_info", "ldap_server_port", "ALTER TABLE gt_agora_info ADD ldap_server_port TINYTEXT DEFAULT NULL");
		$champ_existe = db_maj_champ_ajoute("2.16.0", " gt_agora_info", "ldap_admin_login", "ALTER TABLE gt_agora_info ADD ldap_admin_login TINYTEXT DEFAULT NULL");
		$champ_existe = db_maj_champ_ajoute("2.16.0", " gt_agora_info", "ldap_admin_pass", "ALTER TABLE gt_agora_info ADD ldap_admin_pass TINYTEXT DEFAULT NULL");
		$champ_existe = db_maj_champ_ajoute("2.16.0", " gt_agora_info", "ldap_groupe_dn", "ALTER TABLE gt_agora_info ADD ldap_groupe_dn TINYTEXT DEFAULT NULL");
		$champ_existe = db_maj_champ_ajoute("2.16.0", " gt_agora_info", "ldap_crea_auto_users", "ALTER TABLE gt_agora_info ADD ldap_crea_auto_users TINYINT DEFAULT NULL");
		$champ_existe = db_maj_champ_ajoute("2.16.0", " gt_agora_info", "ldap_pass_cryptage", "ALTER TABLE gt_agora_info ADD ldap_pass_cryptage ENUM('aucun','md5','sha') NOT NULL");

		////	V2.16.3 : RENOMME LE CHAMPS "module_dossier_fichier" DE LA TABLE "gt_module"
		db_maj_champ_rename("2.16.3", "gt_module", "module_dossier_fichier", "ALTER TABLE gt_module CHANGE `module_dossier_fichier` `module_path` TINYTEXT DEFAULT NULL");

		////	V2.16.3 : AJOUTE LE CHAMPS "agenda" DE LA TABLE "gt_module"
		db_maj_champ_ajoute("2.16.3", "gt_agora_info", "agenda_perso_desactive", "ALTER TABLE gt_agora_info ADD agenda_perso_desactive TINYINT AFTER messenger_desactive");



		////	CONSIGNES DE MISE A JOUR :
		////
		////	Modifier le numero de version sur tous les scripts du projet (2.xx.x)  +  le nom de "javascript_2.xx.x.js"
		////	Modifier la date de dernière mise à jour (cf. debut de ce script)
		////	Modifier (si besoin) les fichiers "install/bdd.sql"  +  "BDD_TEST.sql"  !!!
		////	Tester 1ère installation
		////	Tester vieille mise à jour avec "BDD_TEST_version_2.8.2.sql" à importer en latin1
		////	Comparer nombre de lignes de la structure de la base de donnée   "install.sql"  +  "test.sql"  (exporté via mysql), et voir le nombre de champs...



		////	OPTIMISE LES TABLES
		foreach(db_colonne("SHOW TABLES FROM `".db_name."`") as $nom_table)  {db_query("OPTIMIZE TABLE `".$nom_table."`");}
		////	MODIF DATES DE MAJ & RECHARGE LA SESSION
		db_query("UPDATE gt_agora_info SET mise_a_jour='".VERSION_MAJ_DATE."', mise_a_jour_effective='".strftime("%Y%m%d")."', version_agora='".VERSION_AGORA."'");
		$_SESSION["agora"] = db_ligne("SELECT * FROM gt_agora_info");
		////	AFFICHE UN MESSAGE "MISE A JOUR REALISE"
		if(isset($_GET["deconnexion"]))	{$_GET["msg_alerte"]="miseajour";}
	}
}
