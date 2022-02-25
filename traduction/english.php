<?php
////	PARAMETRAGE
////

// Header http
$trad["HEADER_HTTP"] = "en";
// Editeur Tinymce
$trad["EDITOR"] = "en";
// Dates formatées par PHP
setlocale(LC_TIME, "en_US.utf8", "en_US", "en", "english");




////	JOURS FERIES DE L'ANNEE
////
function jours_feries($annee)
{
	////	Les fêtes mobiles (si la fonction de récup' de paques existe)
	if(function_exists("easter_date"))
	{
		// Initialisation
		$jour_unix = 86400;
		$paques_unix = easter_date($annee);
		// Lundi de pâques
		$date = strftime("%Y-%m-%d", $paques_unix+$jour_unix);
		$tab_jours_feries[$date] = "Easter Monday";
	}

	////	Les fêtes fixes
	// Jour de l'an
	$tab_jours_feries[$annee."-01-01"] = "New Year's Day";
	// Noël
	$tab_jours_feries[$annee."-12-25"] = "Christmas";

	////	Retourne le résultat
	return $tab_jours_feries;
}




////	COMMUN
////

// Divers
$trad["remplir_tous_champs"] = "Thank you to specify all the fields";
$trad["voir_detail"] = "See in detail";
$trad["elem_inaccessible"] = "Inaccessible Element";
$trad["champs_obligatoire"] = "Fields compulsory";
$trad["oui"] = "yes";
$trad["non"] = "no";
$trad["aucun"] = "no";
$trad["aller_page"] = "Go to the page";
$trad["alphabet_filtre"] = "Alphabetical Filter";
$trad["tout"] = "All";
$trad["tout_afficher"] = "Display all";
$trad["important"] = "Important";
$trad["afficher"] = "display";
$trad["masquer"] = "mask";
$trad["deplacer"] = "move";
$trad["options"] = "Options";
$trad["reinitialiser"] = "initialize";
$trad["garder"] = "Keep";
$trad["par_defaut"] = "By default";
$trad["localiser_carte"] = "Localize on a map";
$trad["espace_public"] = "Public space";
$trad["bienvenue_agora"] = "Welcome on the Agora!";
$trad["mail_pas_valide"] = "The email is not valid";
$trad["element"] = "element";
$trad["elements"] = "elements";
$trad["dossier"] = "folder";
$trad["dossiers"] = "folders";
$trad["fermer"] = "Close";
$trad["imprimer"] = "Print";
$trad["select_couleur"] = "Select the color";
$trad["visible_espaces"] = "Spaces where it will be visible";
$trad["visible_ts_espaces"] = "Visible on all spaces";
$trad["admin_only"] = "Administrator only";
$trad["divers"] = "Miscellaneous";
$trad["confirm_close_lightbox"] = "Would you want to close the form?";
// images
$trad["photo"] = "Picture";
$trad["fond_ecran"] = "wallpaper";
$trad["image_changer"] = "change";
$trad["pixels"] = "pixels";
// Connexion
$trad["specifier_login_password"] = "Thank you to specify an identifier and a password";
$trad["identifiant"] = "Login";
$trad["identifiant_connexion"] = "Login";
$trad["identifiant_ou_email"] = "Email / Login";
$trad["pass"] = "Password";
$trad["pass2"] = "Confirm password";
$trad["password_verif_alert"] = "Your password confirmation is not valid";
$trad["connexion"] = "Connection";
$trad["connexion_auto"] = "stay connected";
$trad["connexion_auto_info"] = "Retain my login and password for an automatic connection";
$trad["password_oublie"] = "forgot password ?";
$trad["password_oublie_info"] = "Send my login and password to my email address (if specified)";
$trad["acces_invite"] = "Guest access";
$trad["espace_password_erreur"] = "Wrong password";
$trad["version_ie"] = "Your browser is too old and does not support all HTML standards : It is advisable to update it or integrate Chrome Frame to your browser";
// Affichage
$trad["type_affichage"] = "View";
$trad["type_affichage_liste"] = "List";
$trad["type_affichage_bloc"] = "Block";
$trad["type_affichage_arbo"] = "Tree";
// Sélectionner / Déselectionner tous les éléments
$trad["select_deselect"] = "Select / unselect";
$trad["aucun_element_selectionne"] = "No element was selected";
$trad["tout_selectionner"] = "Select all";
$trad["inverser_selection"] = "Reverse the selection";
$trad["suppr_elements"] = "Remove the selected elements";
$trad["deplacer_elements"] = "Move in another folder";
$trad["voir_sur_carte"] = "Show on a map";
$trad["selectionner_user"] = "Thank you for selecting at least a user";
$trad["selectionner_2users"] = "Thank you for selecting at least 2 users";
$trad["selectionner_espace"] = "Thank you for selecting at least one space";
// Temps ("de 11h à 12h", "le 25-01-2007 à 10h30", etc.)
$trad["de"] = "of ";
$trad["a"] = "to";
$trad["le"] = "the";
$trad["debut"] = "Begin";
$trad["fin"] = "End";
$trad["separateur_horaire"] = ":";
$trad["jours"] = "days";
$trad["jour_1"] = "Monday";
$trad["jour_2"] = "Tuesday";
$trad["jour_3"] = "Wednesday";
$trad["jour_4"] = "Thursday";
$trad["jour_5"] = "Friday";
$trad["jour_6"] = "Saturday";
$trad["jour_7"] = "Sunday";
$trad["mois_1"] = "january";
$trad["mois_2"] = "february";
$trad["mois_3"] = "march";
$trad["mois_4"] = "april";
$trad["mois_5"] = "may";
$trad["mois_6"] = "june";
$trad["mois_7"] = "july";
$trad["mois_8"] = "august";
$trad["mois_9"] = "september";
$trad["mois_10"] = "october";
$trad["mois_11"] = "november";
$trad["mois_12"] = "december";
$trad["mois_suivant"] = "next month";
$trad["mois_precedant"] = "previous month";
$trad["annee_suivante"] = "next year";
$trad["annee_precedante"] = "previous year";
$trad["aujourdhui"] = "Today";
$trad["aff_aujourdhui"] = "Today";
$trad["modif_dates_debutfin"] = "The end date can't be before the start date";
// Nom & Description (pour les menus d'édition principalement)
$trad["titre"] = "Title";
$trad["nom"] = "Name";
$trad["description"] = "Description";
$trad["specifier_titre"] = "Thank you to specify a title";
$trad["specifier_nom"] = "Thank you to specify a name";
$trad["specifier_description"] = "Thank you to specify a description";
$trad["specifier_titre_description"] = "Thank you to specify a title or a description";
// Validation des formulaires
$trad["ajouter"] = " Add";
$trad["modifier"] = " Modify";
$trad["modifier_et_acces"] = "Modify & define access";
$trad["valider"] = " Validate";
$trad["lancer"] = " Launch";
$trad["envoyer"] = "Send";
$trad["envoyer_a"] = "Send to";
// Tri d'affichage. Tous les elements (dossier, tache, lien, etc...) ont par défaut une date, un auteur & une description
$trad["trie_par"] = "Sorted by";
$trad["tri"]["date_crea"] = "creation date";
$trad["tri"]["date_modif"] = "change date";
$trad["tri"]["titre"] = "title";
$trad["tri"]["description"] = "description";
$trad["tri"]["id_utilisateur"] = "author";
$trad["tri"]["extension"] = "type of file";
$trad["tri"]["taille_octet"] = "size";
$trad["tri"]["nb_downloads"] = "downloads";
$trad["tri"]["civilite"] = "civility";
$trad["tri"]["nom"] = "name";
$trad["tri"]["prenom"] = "first name";
$trad["tri"]["adresse"] = "adress";
$trad["tri"]["codepostal"] = "zip code";
$trad["tri"]["ville"] = "city";
$trad["tri"]["pays"] = "country";
$trad["tri"]["fonction"] = "function";
$trad["tri"]["societe_organisme"] = "company / organization";
$trad["tri_ascendant"] = "Ascend";
$trad["tri_descendant"] = "Descend";
// Options de suppression
$trad["confirmer"] = "Confirm ?";
$trad["confirmer_suppr"] = "Confirm the delete ?";
$trad["confirmer_suppr_bis"] = "Are you sure ?!";
$trad["confirmer_suppr_dossier"] = "Confirm the delete of the folder and all the data which it contains ? <br><br>Caution ! certain sub-folders are perhaps not accessible for you : they will be also deleted !! ";
$trad["supprimer"] = "Delete";
// Visibilité d'un Objet : auteur et droits d'accès
$trad["auteur"] = "Author : ";
$trad["cree"] = "Created";  //...12-12-2012
$trad["cree_par"] = "Creation";
$trad["modif_par"] = "Changed";
$trad["historique_element"] = "History of the element";
$trad["invite"] = "guest";
$trad["invites"] = "guests";
$trad["tous"] = "all";
$trad["inconnu"] = "unknown person";
$trad["acces_perso"] = "Personal access";
$trad["lecture"] = "read";
$trad["lecture_infos"] = "Access in reading";
$trad["ecriture_limit"] = "limited writing";
$trad["ecriture_limit_infos"] = "Limited access in writing: Ability to add -ELEMENTS-, without modify or delete those created by other";
$trad["ecriture"] = "write";
$trad["ecriture_infos"] = "Access in writing";
$trad["ecriture_infos_conteneur"] = "Access in writing : Ability to add, modify or delete all the -ELEMENTS- of the -CONTENEUR-";
$trad["ecriture_racine_defaut"] = "Access in writing by default";
$trad["ecriture_auteur_admin"] = "Only the author and administrators can edit the access rights or delete the -CONTENEUR-";
$trad["contenu_dossier"] = "content";
$trad["aucun_acces"] = "no access";
$trad["libelles_objets"] = array("element"=>"elements", "fichier"=>"files", "tache"=>"tasks", "lien"=>"links", "contact"=>"contacts", "evenement"=>"events", "message"=>"messages", "conteneur"=>"container", "dossier"=>"folder", "agenda"=>"calendar", "sujet"=>"topic");
// Envoi d'un mail (nouvel utilisateur, notification de création d'objet, etc...)
$trad["mail_envoye_par"] = "Sent by";  // "Envoyé par" Mr trucmuche
$trad["mail_envoye"] = "The email was sent !";
$trad["mail_envoye_notif"] = "The notification email was sent !";
$trad["mail_pas_envoye"] = "The email could not be sent..."; // idem
// Dossier & fichier
$trad["giga_octet"] = "Gb";
$trad["mega_octet"] = "Mb";
$trad["kilo_octet"] = "Kb";
$trad["octet"] = "bytes";
$trad["dossier_racine"] = "Root folder";
$trad["deplacer_autre_dossier"] = "Move towards another folder";
$trad["ajouter_dossier"] = "add a folder";
$trad["modifier_dossier"] = "Modify a folder";
$trad["telecharger"] = "Download";
$trad["telecharge_nb"] = "Downloaded";
$trad["telecharge_nb_bis"] = "times"; // Téléchargé 'n' fois
$trad["telecharger_dossier"] = "Download the folder";
$trad["espace_disque_utilise"] = "Disk space used";
$trad["espace_disque_utilise_mod_fichier"] = "Disk space used for the File manager";
$trad["download_alert"] = "Download not accessible during the day (file size too large)";
// Infos sur une personne
$trad["civilite"] = "Civility";
$trad["nom"] = "Name";
$trad["prenom"] = "First name";
$trad["adresse"] = "Address";
$trad["codepostal"] = "Zip code";
$trad["ville"] = "City";
$trad["pays"] = "country";
$trad["telephone"] = "Phone";
$trad["telmobile"] = "Mobile Phone";
$trad["mail"] = "Email";
$trad["fax"] = "Fax";
$trad["siteweb"] = "Web site";
$trad["competences"] = "Skills";
$trad["hobbies"] = "Hobbies";
$trad["fonction"] = "Function";
$trad["societe_organisme"] = "Company /Organization";
$trad["commentaire"] = "Comment";
// Rechercher
$trad["preciser_text"] = "Thank you to specify key words of at least 3 characters";
$trad["rechercher"] = "Search";
$trad["rechercher_date_crea"] = "Creation date";
$trad["rechercher_date_crea_jour"] = "less than one day";
$trad["rechercher_date_crea_semaine"] = "less than a week";
$trad["rechercher_date_crea_mois"] = "less than one month";
$trad["rechercher_date_crea_annee"] = "less than a year";
$trad["rechercher_espace"] = "Search on this space";
$trad["recherche_avancee"] =  "Advanced Search";
$trad["recherche_avancee_mots_certains"] =  "any word";
$trad["recherche_avancee_mots_tous"] =  "all words";
$trad["recherche_avancee_expression_exacte"] =  "exact phrase";
$trad["recherche_avancee_champs"] =  "search fields";
$trad["recherche_avancee_pas_concordance"] =  "Modules and selected fields do not match. Thank you to review their agreement within Advanced search.";
$trad["mots_cles"] = "Key words";
$trad["liste_modules"] = "Modules";
$trad["liste_champs"] = "Fields";
$trad["liste_champs_elements"] = "Elements involved";
// Importer / Exporter des contact
$trad["exporter"] = "Export";
$trad["importer"] = "Import";
$trad["export_import_users"] = "users";
$trad["export_import_contacts"] = "contacts";
$trad["export_format"] = "format";
$trad["contact_separateur"] = "separator";
$trad["contact_delimiteur"] = "delimiter";
$trad["specifier_fichier"] = "Thank you to specify a file";
$trad["extension_fichier"] = "The file type is invalid. It must be of the type";
$trad["format_fichier_invalide"] = "The file format don't correspond to the selected type";
$trad["import_infos"] = "Select the Agora's fields to target, thanks to the dropdown of each column";
$trad["import_infos_contact"] = "The contacts will be affected by default to the current space.";
$trad["import_infos_user"] = "If the login and password are not selected, they will be generated automatically.";
$trad["import_alert"] = "Thank you for selecting the name's column in the select boxes";
$trad["import_alert2"] = "Thank you for selecting at least a contact to import";
$trad["import_alert3"] = "this agora's field has already been selected in another column (each agora's fields can be selected only once)";
// Captcha
$trad["captcha"] = "Visual identification";
$trad["captcha_info"] = "Thank you to recopy the 4 characters for your identification";
$trad["captcha_alert_specifier"] = "Thank you to specify the visual identification";
$trad["captcha_alert_erronee"] = "The visual identification is false";
// Gestion des inscriptions d'utilisateur
$trad["inscription_users"] = "register on the site";
$trad["inscription_users_info"] = "create a new user account (validated by an administrator)";
$trad["inscription_users_espace"] = "subscribe to the space";
$trad["inscription_users_enregistre"] = "Your subscription has been registered : it will be validated as soon as possible by the administrator of the space";
$trad["inscription_users_option_espace"] = "Allow visitors to register on the space";
$trad["inscription_users_option_espace_info"] = "The inscription is on the homepage of the site. Registration must then be validated by the administrator of the space.";
$trad["inscription_users_validation"] = "Validate user entries";
$trad["inscription_users_valider"] = "validate";
$trad["inscription_users_invalider"] = "invalidate";
$trad["inscription_users_valider_mail"] = "Your account has successfully been validated on";
$trad["inscription_users_invalider_mail"] = "Your account has not been validated on";
// Connexion à un serveur LDAP
$trad["ldap_connexion_serveur"] = "Connecting to an LDAP server";
$trad["ldap_server"] = "server address";
$trad["ldap_server_port"] = "Port server";
$trad["ldap_server_port_infos"] = "''389'' by default";
$trad["ldap_admin_login"] = "String connection for admin";
$trad["ldap_admin_login_infos"] = "for example ''uid=admin,ou=my_company''";
$trad["ldap_admin_pass"] = "Password of the admin";
$trad["ldap_groupe_dn"] = "Group / base DN";
$trad["ldap_groupe_dn_infos"] = "Location of directory users.<br> For example ''ou=users,o=my_company''";
$trad["ldap_connexion_erreur"] = "Error connecting to LDAP server !";
$trad["ldap_import_infos"] = "Show the configuration of the LDAP server in the Administration module.";
$trad["ldap_crea_auto_users"] = "Auto creation of users after LDAP identification";
$trad["ldap_crea_auto_users_infos"] = "Automatically create a user if it is missing from the Agora but present on the LDAP server: it will be assigned to areas accessible to ''all users of the Site''.<br>Otherwise, the user will not be created.";
$trad["ldap_pass_cryptage"] = "Encryption of passwords on the LDAP server";
$trad["ldap_effacer_params"] = "Delete LDAP setting?";
$trad["ldap_pas_module_php"] = "PHP module for connection to an LDAP server is not installed!";




////	DIVERS
////

// Messages d'alert ou d'erreur
$trad["MSG_ALERTE_identification"] = "Invalid login or password";
$trad["MSG_ALERTE_dejapresent"] = "Account currently used by another address IP... (Each account can be used only by one person at the same time)";
$trad["MSG_ALERTE_adresseip"] = "Address IP that you use is not authorized for this account";
$trad["MSG_ALERTE_pasaccesite"] = "Access to the site is not authorized to you because currently, you are probably assigned to any space";
$trad["MSG_ALERTE_captcha"] = "The visual identification is invalid";
$trad["MSG_ALERTE_acces_fichier"] = "File not accessible";
$trad["MSG_ALERTE_acces_dossier"] = "Folder not accessible";
$trad["MSG_ALERTE_espace_disque"] = "Space for the storage of your files is insufficient, you cannot add file";
$trad["MSG_ALERTE_type_interdit"] = "File type not allowed";
$trad["MSG_ALERTE_taille_fichier"] = "The size of the file is too importante";
$trad["MSG_ALERTE_type_version"] = "File type different from the original";
$trad["MSG_ALERTE_deplacement_dossier"] = "You cannot even move the folder inside him..!";
$trad["MSG_ALERTE_nom_dossier"] = "A folder with the same name already exists. Confirm all the same?";
$trad["MSG_ALERTE_nom_fichier"] = "A file with the same name already exists, but has not been replaced";
$trad["MSG_ALERTE_chmod_stock_fichiers"] = "The file manager is not accessible in writing. Thank you to make a chmod 775 on the file ''stock_fichiers '' (read-write access to the owner and the group)";
$trad["MSG_ALERTE_nb_users"] = "You cannot add new user: limited to "; // "...limité à" 10
$trad["MSG_ALERTE_miseajourconfig"] = "The configuration file (config.inc.php) is not writable: Update impossible!";
$trad["MSG_ALERTE_miseajour"] = "Update completed. It is advisable to restart your browser before you reconnect";
$trad["MSG_ALERTE_user_existdeja"] = "The username already exists : the user has not been created";
$trad["MSG_ALERTE_temps_session"] = "Your session has expired, thank you to reconnect";
$trad["MSG_ALERTE_specifier_nombre"] = "Thank you to specify a number";
// header menu
$trad["HEADER_MENU_espace_administration"] = "Administration";
$trad["HEADER_MENU_espaces_dispo"] = "Spaces available";
$trad["HEADER_MENU_espace_acces_administration"] = "(administration access)";
$trad["HEADER_MENU_affichage_elem"] = "Display";
$trad["HEADER_MENU_affichage_normal"] = "Elements which are assigned to me";
$trad["HEADER_MENU_affichage_normal_infos"] = "This is the display normal / default";
$trad["HEADER_MENU_affichage_auteur"] = "Elements which i created";
$trad["HEADER_MENU_affichage_auteur_infos"] = "To display only the items that I created";
$trad["HEADER_MENU_affichage_tout"] = "All the elements of the space (admin)";
$trad["HEADER_MENU_affichage_tout_infos"] = "For the administrators of the space : to display all the elements of space, even those who are not assigned to the administrator !";
$trad["HEADER_MENU_recherche_elem"] = "Search an element on the space";
$trad["HEADER_MENU_sortie_agora"] = "Log out from the Agora";
$trad["HEADER_MENU_raccourcis"] = "Shortcuts";
$trad["HEADER_MENU_seul_utilisateur_connecte"] = "Currently alone on the site";
$trad["HEADER_MENU_en_ligne"] = "Online";
$trad["HEADER_MENU_connecte_a"] = "connected to the site at";   // Mr bidule truc "connecté au site à" 12:45
$trad["HEADER_MENU_messenger"] = "Instant messaging";
$trad["HEADER_MENU_envoye_a"] = "Sent to";
$trad["HEADER_MENU_ajouter_message"] = "Add a message";
$trad["HEADER_MENU_specifier_message"] = "Thank you to specify a message";
$trad["HEADER_MENU_enregistrer_conversation"] = "Record this conversation";
// Footer
$trad["FOOTER_page_generee"] = "page generated in";
// Password_oublie
$trad["PASS_OUBLIE_preciser_mail"] = "Enter your email address to receive your login and password";
$trad["PASS_OUBLIE_mail_inexistant"] = "The email indicated does not exist in the database";
$trad["PASS_OUBLIE_mail_objet"] = "Connection to your space";
$trad["PASS_OUBLIE_mail_contenu"] = "Your login";
$trad["PASS_OUBLIE_mail_contenu_bis"] = "Click here to reset your password";
$trad["PASS_OUBLIE_prompt_changer_pass"] = "Please specify your new password";
$trad["PASS_OUBLIE_id_newpassword_expire"] = "The link to regenerate the password has expired .. thank you to restart the procedure";
$trad["PASS_OUBLIE_password_reinitialise"] = "Your new password was registered !";
$trad["aucun_resultat"] = "No result";
// menu_edit_objet
$trad["EDIT_OBJET_alert_aucune_selection"] = "You must select at least a person or a space";
$trad["EDIT_OBJET_alert_pas_acces_perso"] = "You are not assigned to the element. validate all the same ?";
$trad["EDIT_OBJET_alert_ecriture"] = "There must be at least a person or a space assigned in writing";
$trad["EDIT_OBJET_alert_ecriture_limite_defaut"] = "Warning! with write access, the users concerned will be able to delete all the messages of the subject! \\n\\nIt is recommended to use limited write access";
$trad["EDIT_OBJET_alert_invite"] = "Thank you to specify a name or pseudo";
$trad["EDIT_OBJET_droit_acces"] = "Access rights";
$trad["EDIT_OBJET_espace_pas_module"] = "The current module has not yet been added to this space";
$trad["EDIT_OBJET_tous_utilisateurs"] = "All the users";
$trad["EDIT_OBJET_tous_utilisateurs_espaces"] = "All the spaces";
$trad["EDIT_OBJET_espace_invites"] = "Guests of this public space";
$trad["EDIT_OBJET_aucun_users"] = "Currently no user in this space";
$trad["EDIT_OBJET_invite"] = "Your Name/Pseudo";
$trad["EDIT_OBJET_admin_espace"] = "Administrator of this space: write access to all the elements of this space";
$trad["EDIT_OBJET_tous_espaces"] = "Display all my spaces";
$trad["EDIT_OBJET_notif_mail"] = "Notify by email";
$trad["EDIT_OBJET_notif_mail_joindre_fichiers"] = "Attach files to the notification";
$trad["EDIT_OBJET_notif_mail_info"] = "Send a notification by email to the persons who will have access to the element";
$trad["EDIT_OBJET_notif_mail_selection"] = "Manually select the recipients of notifications";
$trad["EDIT_OBJET_notif_tous_users"] = "Display more users";
$trad["EDIT_OBJET_droits_ss_dossiers"] = "Assign the same access rights to the under-folders";
$trad["EDIT_OBJET_raccourci"] = "Shortcut";
$trad["EDIT_OBJET_raccourci_info"] = "Put a shortcut on the main menu";
$trad["EDIT_OBJET_fichier_joint"] = "Add attached files (pictures, videos..)";
$trad["EDIT_OBJET_inserer_fichier"] = "Display in the description";
$trad["EDIT_OBJET_inserer_fichier_info"] = "Display the image / video / mp3 player ... in the description above. The insertion is performed after form validation.";
$trad["EDIT_OBJET_inserer_fichier_alert"] = "Click on ''Insert'' to add pictures in the text / description";
$trad["EDIT_OBJET_demande_a_confirmer"] = "Your request has been registered. It will be confirmed soon.";
// Formulaire d'installation
$trad["INSTALL_connexion_bdd"] = "Connection to the database";
$trad["INSTALL_db_host"] = "Hostname of the databases server";
$trad["INSTALL_db_name"] = "Name of the database";
$trad["INSTALL_db_name_info"] = "Caution !!! <br> If the database of the Agora already exists, it will be replaced";
$trad["INSTALL_db_login"] = "User name";
$trad["INSTALL_db_password"] = "Password";
$trad["INSTALL_login_password_info"] = "To connect as a general administrator";
$trad["INSTALL_config_admin"] = "Information about the administrator of the ";
$trad["INSTALL_config_espace"] = "Configuration of the main space";
$trad["INSTALL_erreur_acces_mysql"] = "No connection to the MySQL database";
$trad["INSTALL_erreur_acces_bdd"] = "The connection to the database has not been established, still confirm ?";
$trad["INSTALL_erreur_agora_existe"] = "The Agora database already exist! Confirm installation and replace tables?";
$trad["INSTALL_confirm_version_php"] = "Agora-Project requires a minimum version 4.3 of PHP, still confirm ?";
$trad["INSTALL_confirm_version_mysql"] = "Agora-Project requires a minimum version 4.2 of MySQL, still confirm ?";
$trad["INSTALL_confirm_install"] = "Confirm the installation ?";
$trad["INSTALL_install_ok"] = "Agora-Project was well installed ! For safety reasons, think of removing the folder 'install' before starting";




////	MODULE_PARAMETRAGE
////

// Menu principal
$trad["PARAMETRAGE_nom_module"] = "Settings";
$trad["PARAMETRAGE_nom_module_header"] = "Settings";
$trad["PARAMETRAGE_description_module"] = "Settings of the site";
// Index.php
$trad["PARAMETRAGE_sav"] = "Backup the database and the files";
$trad["PARAMETRAGE_sav_alert"] = "The creation of the backup file may take a few minute ... and download a few dozen minutes.";
$trad["PARAMETRAGE_sav_bdd"] = "Backup the database";
$trad["PARAMETRAGE_adresse_web_invalide"] = "Sorry but the web address is not valid: it must start with HTTP:// ";
$trad["PARAMETRAGE_espace_disque_invalide"] = "The limiting disk space must be an entirety";
$trad["PARAMETRAGE_confirmez_modification_site"] = "Confirm modifications ?";
$trad["PARAMETRAGE_nom_site"] = "Site name";
$trad["PARAMETRAGE_adresse_web"] = "Site address";
$trad["PARAMETRAGE_footer_html"] = "Footer html";
$trad["PARAMETRAGE_footer_html_info"] = "To include statistical tools for exemple";
$trad["PARAMETRAGE_langues"] = "Language by default";
$trad["PARAMETRAGE_timezone"] = "Timezone";
$trad["PARAMETRAGE_nom_espace"] = "Name of principal space";
$trad["PARAMETRAGE_limite_espace_disque"] = "Space available for the storage of the files";
$trad["PARAMETRAGE_logs_jours_conservation"] = "Shelf life of Logs";
$trad["PARAMETRAGE_mode_edition"] = "Edit items";
$trad["PARAMETRAGE_edition_popup"] = "in a popup";
$trad["PARAMETRAGE_edition_iframe"] = "in a iframe";
$trad["PARAMETRAGE_skin"] = "Color of the interface (background of elements, menus, etc.)";
$trad["PARAMETRAGE_noir"] = "Black";
$trad["PARAMETRAGE_blanc"] = "White";
$trad["PARAMETRAGE_erreur_fond_ecran_logo"] = "The wallpaper and the logo must have a .jpg or .png extension";
$trad["PARAMETRAGE_suppr_fond_ecran"] = "Delete the wallpaper ?";
$trad["PARAMETRAGE_logo_footer"] = "Logo at the bottom of each page";
$trad["PARAMETRAGE_logo_footer_url"] = "URL";
$trad["PARAMETRAGE_editeur_text_mode"] = "Text editor's mode (TinyMCE)";
$trad["PARAMETRAGE_editeur_text_minimal"] = "Minimal";
$trad["PARAMETRAGE_editeur_text_complet"] = "Full (+ tables + medias + paste from word)"; 
$trad["PARAMETRAGE_messenger_desactive"] = "Instant messenger enabled";
$trad["PARAMETRAGE_agenda_perso_desactive"] = "Personal calendars enabled by default";
$trad["PARAMETRAGE_agenda_perso_desactive_infos"] = "Add a personal calendar at the creation of a user. The calendar may, however, be disabled later, changing the user account.";
$trad["PARAMETRAGE_libelle_module"] = "Module's name in the main menu";
$trad["PARAMETRAGE_libelle_module_masquer"] = "hide";
$trad["PARAMETRAGE_libelle_module_icones"] = "over each module's icon";
$trad["PARAMETRAGE_libelle_module_page"] = "only the name of the current module";
$trad["PARAMETRAGE_tri_personnes"] = "Sort users and contacts";
$trad["PARAMETRAGE_versions"] = "Versions";
$trad["PARAMETRAGE_version_agora_maj"] = "updated";
$trad["PARAMETRAGE_fonction_mail_desactive"] = "PHP function to send email : disabled !";
$trad["PARAMETRAGE_fonction_mail_infos"] = "Some hosters disable the PHP function for sending mails for security reasons or saturation servers (SPAM)";
$trad["PARAMETRAGE_fonction_image_desactive"] = "Function of handling images and creation of thumbs (PHP GD2) : disabled !";




////	MODULE_LOG
////

// Menu principal
$trad["LOGS_nom_module"] = "Logs";
$trad["LOGS_nom_module_header"] = "Logs";
$trad["LOGS_description_module"] = "Logs - Event Log";
// Index.php
$trad["LOGS_filtre"] = "filter";
$trad["LOGS_date_heure"] = "Date / Time";
$trad["LOGS_espace"] = "space";
$trad["LOGS_module"] = "module";
$trad["LOGS_action"] = "Action";
$trad["LOGS_utilisateur"] = "User";
$trad["LOGS_adresse_ip"] = "IP";
$trad["LOGS_commentaire"] = "comment";
$trad["LOGS_no_logs"] = "no log";
$trad["LOGS_filtre_a_partir"] = "filtered from";
$trad["LOGS_chercher"] = "search";
$trad["LOGS_chargement"] = "Loading data";
$trad["LOGS_connexion"] = "connection";
$trad["LOGS_deconnexion"] = "logout";
$trad["LOGS_consult"] = "consult";
$trad["LOGS_consult2"] = "download";
$trad["LOGS_ajout"] = "add";
$trad["LOGS_suppr"] = "delete";
$trad["LOGS_modif"] = "edit change";




////	MODULE_ESPACE
////

// Menu principal
$trad["ESPACES_nom_module"] = "Spaces";
$trad["ESPACES_nom_module_header"] = "Spaces";
$trad["ESPACES_description_module"] = "Spaces of the site";
$trad["ESPACES_description_module_infos"] = "The site (or main space) can be divided into several spaces";
// Header_menu.inc.php
$trad["ESPACES_gerer_espaces"] = "Manage spaces of the site";
$trad["ESPACES_parametrage"] = "Settings of the space";
$trad["ESPACES_parametrage_infos"] = "Settings of the space (description, modules, Users assigned, etc)";
// Index.php
$trad["ESPACES_confirm_suppr_espace"] = "Confirm the suppression ? Attention, the affected data only with this space will be definitively lost !!";
$trad["ESPACES_espace"] = "space";
$trad["ESPACES_espaces"] = "spaces";
$trad["ESPACES_definir_acces"] = "To define !";
$trad["ESPACES_modules"] = "Modules";
$trad["ESPACES_ajouter_espace"] = "Add a space";
$trad["ESPACES_supprimer_espace"] = "Delete the space";
$trad["ESPACES_aucun_espace"] = "No space for the moment";
$trad["MSG_ALERTE_suppr_espace_impossible"] = "You can not delete the current space";
// Espace_edit.php
$trad["ESPACES_gestion_acces"] = "Users assigned to the space";
$trad["ESPACES_selectionner_module"] = "You must select at least a module";
$trad["ESPACES_modules_espace"] = "Modules of the space";
$trad["ESPACES_modules_classement"] = "Move to set the display order of modules";
$trad["ESPACES_selectionner_utilisateur"] = "Celect certain users, all the users or open the space to the public";
$trad["ESPACES_espace_public"] = "Public space";
$trad["ESPACES_public_infos"] = "Provides access to people who do not have accounts on the site : guests. Ability to specify a password to protect access.";
$trad["ESPACES_invitations_users"] = "Users can send invitations by mail";
$trad["ESPACES_invitations_users_infos"] = "All users can send email invitations to join the space";
$trad["ESPACES_tous_utilisateurs"] = "All the users on the site";
$trad["ESPACES_utilisation"] = " User";
$trad["ESPACES_utilisation_info"] = "User of the space : <br> Normal access to the space";
$trad["ESPACES_administration"] = "Administrator";
$trad["ESPACES_administration_info"] = "Administrator of the space : Write access to all elements of the space + ability to send email invitations + ability to add users";
$trad["ESPACES_creer_agenda_espace"] = "Create a calendar for the space";
$trad["ESPACES_creer_agenda_espace_info"] = "This can be useful if the calendars of the users are disabled.<br>The calendar will have the same name than the space and this will be a resource calendar.";




////	MODULE_UTILISATEUR
////

// Menu principal
$trad["UTILISATEURS_nom_module"] = "User";
$trad["UTILISATEURS_nom_module_header"] = "User";
$trad["UTILISATEURS_description_module"] = "User";
$trad["UTILISATEURS_ajout_utilisateurs_groupe"] = "Users can also create groups";
// Index.php
$trad["UTILISATEURS_utilisateurs_site"] = "User of the site";
$trad["UTILISATEURS_gerer_utilisateurs_site"] = "Manage all the users";
$trad["UTILISATEURS_utilisateurs_site_infos"] = "All users of the site, from all spaces";
$trad["UTILISATEURS_utilisateurs_espace"] = "Users of the space";
$trad["UTILISATEURS_confirm_suppr_utilisateur"] = "Confirm the delete of the user? Caution ! all the data relating to it will be definitively lost !!";
$trad["UTILISATEURS_confirm_desaffecter_utilisateur"] = "Confirm the unassignment of the user to current space ?";
$trad["UTILISATEURS_suppr_definitivement"] = "Delete definitely";
$trad["UTILISATEURS_desaffecter"] = "Unassign to the space";
$trad["UTILISATEURS_tous_user_affecte_espace"] = "All the users of the site are affected to this space: no possible unassignment";
$trad["UTILISATEURS_utilisateur"] = "user";
$trad["UTILISATEURS_utilisateurs"] = "users";
$trad["UTILISATEURS_affecter_utilisateur"] = "Add an existing user in this space";
$trad["UTILISATEURS_ajouter_utilisateur"] = "Add User";
$trad["UTILISATEURS_ajouter_utilisateur_site"] = "Create a user on the site: by default, assigned to any space!";
$trad["UTILISATEURS_ajouter_utilisateur_espace"] = "Create a user into the current space";
$trad["UTILISATEURS_envoi_coordonnees"] = "Send login and password";
$trad["UTILISATEURS_envoi_coordonnees_infos"] = "Send to users (by mail) their login <br> and a new password";
$trad["UTILISATEURS_envoi_coordonnees_infos2"] = "Send mail to new users their username and password";
$trad["UTILISATEURS_envoi_coordonnees_confirm"] = "Passwords will be renewed ! continue ?";
$trad["UTILISATEURS_mail_coordonnees"] = "login and password";
$trad["UTILISATEURS_aucun_utilisateur"] = "No user assigned to this space for the moment";
$trad["UTILISATEURS_derniere_connexion"] = "Last connection";
$trad["UTILISATEURS_liste_espaces"] = "Spaces of the user";
$trad["UTILISATEURS_aucun_espace"] = "No space";
$trad["UTILISATEURS_admin_general"] = "General administrator of the site";
$trad["UTILISATEURS_admin_espace"] = "Administrator of the space";
$trad["UTILISATEURS_user_espace"] = "User of the space";
$trad["UTILISATEURS_user_site"] = "User of the site";
$trad["UTILISATEURS_pas_connecte"] = "Not connected yet";
$trad["UTILISATEURS_modifier"] = "Modify user";
$trad["UTILISATEURS_modifier_mon_profil"] = "Modify my profil";
$trad["UTILISATEURS_pas_suppr_dernier_admin_ge"] = "You can not delete the last General administrator of the site !";
// Invitation.php
$trad["UTILISATEURS_envoi_invitation"] = "Invite somebody to join the space";
$trad["UTILISATEURS_envoi_invitation_info"] = "The invitation will be sent by mail";
$trad["UTILISATEURS_objet_mail_invitation"] = "Invitation of "; // ..Jean DUPOND
$trad["UTILISATEURS_admin_invite_espace"] = "invites you to join "; // Jean DUPOND "vous invite à rejoindre l'espace" Mon Espace
$trad["UTILISATEURS_confirmer_invitation"] = "Click here to confirm the invitation";
$trad["UTILISATEURS_invitation_a_confirmer"] = "Invitations not confirmed yet";
$trad["UTILISATEURS_id_invitation_expire"] = "The link for your invitation has expired ...";
$trad["UTILISATEURS_invitation_confirmer_password"] = "Thank you for choosing your password before confirming your invitation";
$trad["UTILISATEURS_invitation_valide"] = "Your invitation has been validated !";
// groupes.php
$trad["UTILISATEURS_groupe_espace"] = "groups of users of the space";
$trad["UTILISATEURS_groupe_site"] = "all the groups of users";
$trad["UTILISATEURS_groupe_infos"] = "edit the groups of users";
$trad["UTILISATEURS_groupe_espace_infos"] = "The enabled users have access to all the selected spaces (the others are disabled)";
$trad["UTILISATEURS_droit_gestion_groupes"] = "Each group can be modified by its author or the general administrator";
// Utilisateur_affecter.php
$trad["UTILISATEURS_preciser_recherche"] = "Thank you to specify a name, a first name or an address of email";
$trad["UTILISATEURS_affecter_user_confirm"] = "Confirm the assignement of the user to the space?";
$trad["UTILISATEURS_rechercher_user"] = "Search a user to add it to space";
$trad["UTILISATEURS_tous_users_affectes"] = "All the users of the site are already assigned to this space";
$trad["UTILISATEURS_affecter_user"] = "Assign a user to the space :";
$trad["UTILISATEURS_aucun_users_recherche"] = "No user for this research";
// Utilisateur_edit.php & CO
$trad["UTILISATEURS_specifier_nom"] = "Thank you to specify a name";
$trad["UTILISATEURS_specifier_prenom"] = "Thank you to specify a first name";
$trad["UTILISATEURS_specifier_identifiant"] = "Thank you to specify a login";
$trad["UTILISATEURS_specifier_pass"] = "Thank you to specify a password";
$trad["UTILISATEURS_pas_fichier_photo"] = "You did not specify an image !";
$trad["UTILISATEURS_langues"] = "Language";
$trad["UTILISATEURS_agenda_perso_active"] = "Personal calendar enabled";
$trad["UTILISATEURS_agenda_perso_active_infos"] = "If enabled, the personal calendar is <u>always</u> visible to the user, even if the Calendar module is not enabled in the current space";
$trad["UTILISATEURS_espace_connexion"] = "Space displayed after connection";
$trad["UTILISATEURS_notification_mail"] = "Send a notification of creation by email";
$trad["UTILISATEURS_alert_notification_mail"] = "Think of specifying an address email!";
$trad["UTILISATEURS_adresses_ip"] = "list of control IP addresses";
$trad["UTILISATEURS_info_adresse_ip"] = "When you specify one (or several) control IP addresses, the user can only connect if it uses the specified IP addresses";
$trad["UTILISATEURS_ip_invalide"] = "False IP address";
$trad["UTILISATEURS_identifiant_deja_present"] = "The specified login already exists. Thank you to specify another";
$trad["UTILISATEURS_mail_deja_present"] = "The email already exists. Thank you to specify another";
$trad["UTILISATEURS_mail_objet_nouvel_utilisateur"] = "New account on";  // "...sur" l'Agora machintruc
$trad["UTILISATEURS_mail_nouvel_utilisateur"] = "A new account was created to you on";  // idem
$trad["UTILISATEURS_mail_infos_connexion"] = "Connect with the following login and password";
$trad["UTILISATEURS_mail_infos_connexion2"] = "Thank you to archive this email.";
// Utilisateur_Messenger.php
$trad["UTILISATEURS_gestion_messenger_livecounter"] = "Manage the instant messenger";
$trad["UTILISATEURS_visibilite_messenger_livecounter"] = "Users who can see me online chat on instant messaging";
$trad["UTILISATEURS_aucun_utilisateur_messenger"] = "No user for the moment";
$trad["UTILISATEURS_voir_aucun_utilisateur"] = "No user can see me";
$trad["UTILISATEURS_voir_tous_utilisateur"] = "All the users can see me";
$trad["UTILISATEURS_voir_certains_utilisateur"] = "Certain users can see me";




////	MODULE_TABLEAU BORD
////

// Menu principal
$trad["TABLEAU_BORD_nom_module"] = "News";
$trad["TABLEAU_BORD_nom_module_header"] = "News";
$trad["TABLEAU_BORD_description_module"] = "News";
$trad["TABLEAU_BORD_ajout_actualite_admin"] = "Only the admin can add News";
// Index.php
$trad["TABLEAU_BORD_new_elems"] = "new elements";
$trad["TABLEAU_BORD_new_elems_bulle"] = "New elements created during the selected period";
$trad["TABLEAU_BORD_new_elems_realises"] = "currents éléments";
$trad["TABLEAU_BORD_new_elems_realises_bulle"] = "Events and tasks <br>today";
$trad["TABLEAU_BORD_plugin_connexion"] = "since last connection";
$trad["TABLEAU_BORD_plugin_jour"] = "today";
$trad["TABLEAU_BORD_plugin_semaine"] = "this Week";
$trad["TABLEAU_BORD_plugin_mois"] = "this month";
$trad["TABLEAU_BORD_autre_periode"] = "Another period";
$trad["TABLEAU_BORD_pas_nouveaux_elements"] = "No elements for the selected period";
$trad["TABLEAU_BORD_actualites"] = "News";
$trad["TABLEAU_BORD_actualite"] = "new";
$trad["TABLEAU_BORD_actualites"] = "news";
$trad["TABLEAU_BORD_ajout_actualite"] = "Add a news";
$trad["TABLEAU_BORD_actualites_offline"] = "Archived news";
$trad["TABLEAU_BORD_pas_actualites"] = "No news for the moment moment";
// Actualite_edit.php
$trad["TABLEAU_BORD_mail_nouvelle_actualite_cree"] = "New news created by";
$trad["TABLEAU_BORD_ala_une"] = "In Focus";
$trad["TABLEAU_BORD_ala_une_info"] = "Always show that news first";
$trad["TABLEAU_BORD_offline"] = "Archived";
$trad["TABLEAU_BORD_date_online_auto"] = "Online scheduled";
$trad["TABLEAU_BORD_date_online_auto_alerte"] = "The news has been archived in expectation of its online cheduled";
$trad["TABLEAU_BORD_date_offline_auto"] = "scheduled archiving";




////	MODULE_AGENDA
////

// Menu principal
$trad["AGENDA_nom_module"] = "Calendar";
$trad["AGENDA_nom_module_header"] = "Calendar";
$trad["AGENDA_description_module"] = "Personal and shared calendar";
$trad["AGENDA_ajout_agenda_ressource_admin"] = "Only the admin can add resource calendars";
$trad["AGENDA_ajout_categorie_admin"] = "Only the admin can add a category of event";
// Index.php
$trad["AGENDA_agendas_visibles"] = "Available calendars (personal & resources)";
$trad["AGENDA_afficher_tous_agendas"] = "Show all calendars";
$trad["AGENDA_masquer_tous_agendas"] = "Hide all calendars";
$trad["AGENDA_cocher_tous_agendas"] = "Check/Uncheck all Calendars";
$trad["AGENDA_cocher_agendas_users"] = "Check/Uncheck users";
$trad["AGENDA_cocher_agendas_ressources"] = "Check/Uncheck resources";
$trad["AGENDA_imprimer_agendas"] = "Print calendar(s)";
$trad["AGENDA_imprimer_agendas_infos"] = "print in landscape mode";
$trad["AGENDA_ajouter_agenda_ressource"] = "Add a resource calendar";
$trad["AGENDA_ajouter_agenda_ressource_bis"] = "Add a resource calendar : room, vehicle, videoprojector, etc";
$trad["AGENDA_exporter_ical"] = "Export the events (iCal format)";
$trad["AGENDA_exporter_ical_mail"] = "Export the events by mail (iCal)";
$trad["AGENDA_exporter_ical_mail2"] = "To integrate in an calendar IPHONE, ANDROID, OUTLOOK, GOOGLE CALENDAR...";
$trad["AGENDA_importer_ical"] = "Import the events (iCal)";
$trad["AGENDA_importer_ical_etat"] = "State";
$trad["AGENDA_importer_ical_deja_present"] = "Already present";
$trad["AGENDA_importer_ical_a_importer"] = "To import";
$trad["AGENDA_suppr_anciens_evt"] = "Remove the past events";
$trad["AGENDA_confirm_suppr_anciens_evt"] = "Are you sure you want to definitively remove the events which precede the displayed period ?";
$trad["AGENDA_ajouter_evt_heure"] = "Add an event at";
$trad["AGENDA_ajouter_evt_jour"] = "Add an event to this day";
$trad["AGENDA_evt_jour"] = "Day";
$trad["AGENDA_evt_semaine"] = "Week";
$trad["AGENDA_evt_semaine_w"] = "Working week";
$trad["AGENDA_evt_mois"] = "Month";
$trad["AGENDA_num_semaine"] = "week";
$trad["AGENDA_voir_num_semaine"] = "See the week n°";
$trad["AGENDA_periode_suivante"] = "Next period";
$trad["AGENDA_periode_precedante"] = "Preceding period";
$trad["AGENDA_affectations_evt"] = "Event in the calendar of ";
$trad["AGENDA_affectations_evt_autres"] = "+ other calendars not visibles";
$trad["AGENDA_affectations_evt_non_confirme"] = "Confirmation on standby : ";
$trad["AGENDA_evenements_proposes_pour_agenda"] = "Events proposed for"; // "Videoprojecteur" / "salle de réunion" / etc.
$trad["AGENDA_evenements_proposes_mon_agenda"] = "Events proposed for my calendar";
$trad["AGENDA_evenement_propose_par"] = "Proposed by";  // "Proposé par" Mr bidule truc
$trad["AGENDA_evenement_integrer"] = "Integrate the event into the calendar ?";
$trad["AGENDA_evenement_pas_integrer"] = "Delete the proposal of the event ?";
$trad["AGENDA_supprimer_evt_agenda"] = "Delete for this calendar ?";
$trad["AGENDA_supprimer_evt_agendas"] = "Delete for all the calendars ?";
$trad["AGENDA_supprimer_evt_date"] = "Delete this date only ?";
$trad["AGENDA_confirm_suppr_evt"] = "Delete the event on this calendar ?";
$trad["AGENDA_confirm_suppr_evt_tout"] = "Delete the event on all the calendars where it is placed ?";
$trad["AGENDA_confirm_suppr_evt_date"] = "Delete the event to this date, all calendars where it is placed ?";
$trad["AGENDA_evt_prive"] = "Private event";
$trad["AGENDA_aucun_agenda_visible"] = "No calendars displayed";
$trad["AGENDA_evt_proprio"] = "Events which I created";
$trad["AGENDA_evt_proprio_inaccessibles"] = "Show only the events which I created for calendars that i can't access";
$trad["AGENDA_aucun_evt"] = "No event";
$trad["AGENDA_proposer"] = "Propose an event";
$trad["AGENDA_synthese"] = "Calendars synthesis";
$trad["AGENDA_pourcent_agendas_occupes"] = "Busy calendars";
$trad["AGENDA_agendas_details"] = "See detailed calendars";
$trad["AGENDA_agendas_details_masquer"] = "Hide details agendas";
// Evenement.php
$trad["AGENDA_categorie"] = "Category";
$trad["AGENDA_visibilite"] = "Visibility";
$trad["AGENDA_visibilite_public"] = "public";
$trad["AGENDA_visibilite_public_cache"] = "public, but details masked";
$trad["AGENDA_visibilite_prive"] = "private";
// Agenda_edit.php
$trad["AGENDA_affichage_evt"] = "Event display";
$trad["AGENDA_affichage_evt_border"] = "Border with the color of the category";
$trad["AGENDA_affichage_evt_background"] = "Background with the color of the category";
$trad["AGENDA_plage_horaire"] = "Time display";
// Evenement_edit.php
$trad["AGENDA_periodicite"] = "Recurring Event";
$trad["AGENDA_period_non"] = "Ponctual event";
$trad["AGENDA_period_jour_semaine"] = "Every week";
$trad["AGENDA_period_jour_mois"] = "Every month, some days";
$trad["AGENDA_period_mois"] = "Every month";
$trad["AGENDA_period_mois_xdumois"] = "of the month"; // Le 21 du mois
$trad["AGENDA_period_annee"] = "Every year";
$trad["AGENDA_period_mois_xdeannee"] = "of the year"; // Le 21/12 de l'année
$trad["AGENDA_period_date_fin"] = "End of periodicity";
$trad["AGENDA_exception_periodicite"] = "Exception de périodicité";
$trad["AGENDA_agendas_affectations"] = "Assign to the following calendars";
$trad["AGENDA_verif_nb_agendas"] = "Thank you to select at least a calendar";
$trad["AGENDA_mail_nouvel_evenement_cree"] = "New event created by ";
$trad["AGENDA_input_proposer"] = "Propose the event to the owner of the calendar";
$trad["AGENDA_input_affecter"] = "Add the event to the calendar";
$trad["AGENDA_info_proposer"] = "Only propose the event (you don't have access in writing to this calendar)";
$trad["AGENDA_info_pas_modif"] = "Modification forbidden because you don't have access in writing to this calendar";
$trad["AGENDA_visibilite_info"] = "<u>Public</u> : Visible to users who have read access (or +) to the calendars where the event is assigned.<br><u>Public, but masked details</u> : Same, but those who have access in read only, see the timeframe of the event but not the details.<br><u>Private</u> : Visible only to those who have write access to the calendars which he is assigned.";
$trad["AGENDA_edit_limite"] = "You are not the author of the event and he was assigned to calendars that you can't acces in writing. You only can manage the assignments to your calendar(s)";
$trad["AGENDA_creneau_occupe"] = "The slot is already occupied on this calendar :";

// Categories.php
$trad["AGENDA_gerer_categories"] = "Manage event categories";
$trad["AGENDA_categories_evt"] = "Event categories";
$trad["AGENDA_droit_gestion_categories"] = "Each category can be modified by its author or the general administrator";




////	MODULE_FICHIER
////

// Menu principal
$trad["FICHIER_nom_module"] = "File manager";
$trad["FICHIER_nom_module_header"] = "Files";
$trad["FICHIER_description_module"] = "File manager";
// Index.php
$trad["FICHIER_ajouter_fichier"] = "Add files";
$trad["FICHIER_ajouter_fichier_alert"] = "Folder on the server not accessible in writing! thank you to contact the administrator";
$trad["FICHIER_telecharger_dossier"] = "Download the folder";
$trad["FICHIER_telecharger_selection"] = "Download Selection";
$trad["FICHIER_telecharger_dossier_confirm"] = "Confirm the files download ?";
$trad["FICHIER_voir_images"] = "Show the pictures";
$trad["FICHIER_defiler_images"] = "Scroll the images automatically";
$trad["FICHIER_pixels"] = "pixels";
$trad["FICHIER_nb_versions_fichier"] = "versions of the file"; // n versions du fichier
$trad["FICHIER_ajouter_versions_fichier"] = "add a new file version";
$trad["FICHIER_apercu"] = "Outline"; // n versions du fichier
$trad["FICHIER_aucun_fichier"] = "No file for the moment";
// Fichier_edit.php  &  Dossier_edit.php  &  fichier_edit_ajouter.php  &  Versions_fichier.php
$trad["FICHIER_limite_chaque_fichier"] = "The files should not exceed"; // ...2 Mega Octets
$trad["FICHIER_optimiser_images"] = "Limit the size of the images to "; // ..1024*768 pixels
$trad["FICHIER_maj_nom"] = "The filename will be replaced by the new version";
$trad["FICHIER_ajout_plupload"] = "Multiple upload";
$trad["FICHIER_ajout_classique"] = "Classic upload";
$trad["FICHIER_erreur_nb_fichiers"] = "Please select fewer files";
$trad["FICHIER_erreur_taille_fichier"] = "File is too big";
$trad["FICHIER_erreur_non_geree"] = "Unhandled Error";
$trad["FICHIER_ajout_multiple_info"] = "Button 'Shift' or 'Ctrl' to select multiple files";
$trad["FICHIER_select_fichier"] = "Select files";
$trad["FICHIER_annuler"] = "Cancel";
$trad["FICHIER_selectionner_fichier"] = "Thank you to select at least a file";
$trad["FICHIER_nouvelle_version"] = "already exist, a new version was added.";  // mon_fichier.gif "existe déjà"...
$trad["FICHIER_mail_nouveau_fichier_cree"] = "New file created by ";
$trad["FICHIER_mail_fichier_modifie"] = "File modified by ";
$trad["FICHIER_contenu"] = "content";
// Versions_fichier.php
$trad["FICHIER_versions_de"] = "Versions of"; // versions de fichier.gif
$trad["FICHIER_confirmer_suppression_version"] = "Confirm the removal of this version ?";
// Images.php
$trad["FICHIER_info_https_flash"] = "To not have the message ''Do you want to display the nonsecure items?'' : <br> <br>> Click on ''Tools'' <br>> click ''Internet Options'' < br />> click ''Security tab'' <br>> Choose ''Internet Zone'' <br>> Custom Level <br>> Enable Show ''mixed content'' in ''Other '' ";
$trad["FICHIER_img_precedante"] = "Preceding image [left arrow]";
$trad["FICHIER_img_suivante"] = "Next image [right arrow / space bar]";
$trad["FICHIER_rotation_gauche"] = "Rotate left [up arrow]";
$trad["FICHIER_rotation_droite"] = "Rotate Right [Down arrow]";
$trad["FICHIER_zoom"] = "Zoom / Zoom out";
/// Video.php
$trad["FICHIER_voir_videos"] = "Watch the videos";
$trad["FICHIER_regarder"] = "Watch the video";
$trad["FICHIER_video_precedante"] = "Previous video";
$trad["FICHIER_video_suivante"] = "Next video";
$trad["FICHIER_video_mp4_flv"] = "<a href='http://get.adobe.com/flashplayer'>Flash</a> not installed.";




////	MODULE_FORUM
////

// Menu principal
$trad["FORUM_nom_module"] = "Forum";
$trad["FORUM_nom_module_header"] = "Forum";
$trad["FORUM_description_module"] = "Forum";
$trad["FORUM_ajout_sujet_admin"] = "Only the administrator can add topics";
$trad["FORUM_ajout_sujet_theme"] = "Users can also add themes";
// TRI
$trad["tri"]["date_dernier_message"] = "last message";
// Index.php & Sujet.php
$trad["FORUM_sujet"] = "topic";
$trad["FORUM_sujets"] = "topics";
$trad["FORUM_message"] = "message";
$trad["FORUM_messages"] = "messages";
$trad["FORUM_dernier_message"] = "last by";
$trad["FORUM_ajouter_sujet"] = "Add a topic";
$trad["FORUM_voir_sujet"] = "View topic";
$trad["FORUM_repondre"] = "Answer";
$trad["FORUM_repondre_message"] = "Answer this message";
$trad["FORUM_repondre_message_citer"] = "Answer and quote this message";
$trad["FORUM_aucun_sujet"] = "No topic for the moment";
$trad["FORUM_pas_message"] = "No message for the moment";
$trad["FORUM_aucun_message"] = "No message";
$trad["FORUM_confirme_suppr_message"] = "Confirm the delete of the message (and sub-messages) ?";
$trad["FORUM_retour_liste_sujets"] = "Return to the list of the topics";
$trad["FORUM_notifier_dernier_message"] = "Notify by email";
$trad["FORUM_notifier_dernier_message_info"] = "Send me a notification by mail to each new message";
// Sujet_edit.php  &  Message_edit.php
$trad["FORUM_infos_droits_acces"] = "To participate in the topic, you must have at least a ''limited write access''";
$trad["FORUM_theme_espaces"] = "The topic is available in the spaces";
$trad["FORUM_mail_nouveau_sujet_cree"] = "New topic created by ";
$trad["FORUM_mail_nouveau_message_cree"] = "New message created by ";
// Themes
$trad["FORUM_theme_sujet"] = "Theme";
$trad["FORUM_accueil_forum"] = "Forum index";
$trad["FORUM_sans_theme"] = "Without theme";
$trad["FORUM_themes_gestion"] = "Manage themes";
$trad["FORUM_droit_gestion_themes"] = "Each theme can be modified by its author or the general administrator";
$trad["FORUM_confirm_suppr_theme"] = "Warning! The subjects involved will have no theme! Confirm Delete?";




////	MODULE_TACHE
////

// Menu principal
$trad["TACHE_nom_module"] = "Tasks";
$trad["TACHE_nom_module_header"] = "Tasks";
$trad["TACHE_description_module"] = "Tasks";
// TRI
$trad["tri"]["priorite"] = "Priority";
$trad["tri"]["avancement"] = "Progress";
$trad["tri"]["date_debut"] = "Begin date";
$trad["tri"]["date_fin"] = "End date";
// Index.php
$trad["TACHE_ajouter_tache"] = "Add a task";
$trad["TACHE_aucune_tache"] = "No task for the moment";
$trad["TACHE_avancement"] = "Progress";
$trad["TACHE_avancement_moyen"] = "Average progress";
$trad["TACHE_avancement_moyen_pondere"] = "Average progress weighted on the day/man charge";
$trad["TACHE_priorite"] = "Priority";
$trad["TACHE_priorite1"] = "Low";
$trad["TACHE_priorite2"] = "Medium";
$trad["TACHE_priorite3"] = "High";
$trad["TACHE_priorite4"] = "Critical";
$trad["TACHE_responsables"] = "Leaders";
$trad["TACHE_budget_disponible"] = "Budget available";
$trad["TACHE_budget_disponible_total"] = "Total Budget available";
$trad["TACHE_budget_engage"] = "Budget committed";
$trad["TACHE_budget_engage_total"] = "Total Budget committed";
$trad["TACHE_charge_jour_homme"] = "Day/man charge";
$trad["TACHE_charge_jour_homme_total"] = "Total day/man charge ";
$trad["TACHE_charge_jour_homme_info"] = "Number of working days required for one person to accomplish this task";
$trad["TACHE_avancement_retard"] = "Progress delayed";
$trad["TACHE_budget_depasse"] = "Budget exceeded";
$trad["TACHE_afficher_tout_gantt"] = "Show all tasks";
// tache_edit.php
$trad["TACHE_mail_nouvelle_tache_cree"] = "New task created by ";
$trad["TACHE_specifier_date"] = "Thank you to specify a date";




////	MODULE_CONTACT
////

// Menu principal
$trad["CONTACT_nom_module"] = "Directory of contacts";
$trad["CONTACT_nom_module_header"] = "Contacts";
$trad["CONTACT_description_module"] = "Directory of contacts";
// Index.php
$trad["CONTACT_ajouter_contact"] = "Add a contact";
$trad["CONTACT_aucun_contact"] = "No contact for the moment";
$trad["CONTACT_creer_user"] = "Create a user in this space";
$trad["CONTACT_creer_user_infos"] = "Create a user in this space from this contact ?";
$trad["CONTACT_creer_user_confirm"] = "the user was created";
// Contact_edit.php
$trad["CONTACT_mail_nouveau_contact_cree"] = "New contact created by ";




////	MODULE_LIEN
////

// Menu principal
$trad["LIEN_nom_module"] = "Favourites";
$trad["LIEN_nom_module_header"] = "Favourites";
$trad["LIEN_description_module"] = "Favourites";
// Index.php
$trad["LIEN_ajouter_lien"] = "Add a link";
$trad["LIEN_aucun_lien"] = "No link for the moment";
// lien_edit.php & dossier_edit.php
$trad["LIEN_adresse"] = "Address";
$trad["LIEN_specifier_adresse"] = "Thank you to specify an address";
$trad["LIEN_mail_nouveau_lien_cree"] = "New link created by ";




////	MODULE_MAIL
////

//  Menu principal
$trad["MAIL_nom_module"] = "Email";
$trad["MAIL_nom_module_header"] = "Email";
$trad["MAIL_description_module"] = "Send emails in a click!";
// Index.php
$trad["MAIL_specifier_mail"] = "Thank you to specify at least an address email";
$trad["MAIL_titre"] = "Email of the title";
$trad["MAIL_no_header_footer"] = "No header and footer";
$trad["MAIL_no_header_footer_infos"] = "Do not include the sender's name and a link to the space";
$trad["MAIL_masquer_dest_message"] = "Hide recipients";
$trad["MAIL_masquer_dest_message_infos"] = "By default, email recipients are displayed in the message.";
$trad["MAIL_accuse_reception"] = "Request a delivery receipt";
$trad["MAIL_accuse_reception_infos"] = "Warning: some email clients don't accept the delivery receipt";
$trad["MAIL_fichier_joint"] = "Attached file";
// Historique Mail
$trad["MAIL_historique_mail"] = "History of the emails sent";
$trad["MAIL_aucun_mail"] = "No email";
$trad["MAIL_envoye_par"] = "Email sent by";
$trad["MAIL_destinataires"] = "Recipients";
?>
