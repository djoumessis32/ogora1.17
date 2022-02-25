<?php
////	PARAMETRAGE
////

// Header http
$trad["HEADER_HTTP"] = "fr";
// Editeur Tinymce
$trad["EDITOR"] = "fr";
// Dates formatées par PHP
setlocale(LC_TIME, "fr_FR.utf8", "fr_FR", "fr", "french");




////	JOURS FERIES DE L'ANNEE
////
function jours_feries($annee)
{
	////	Les fêtes mobiles (si la fonction de récup' de paques existe)
	if(function_exists("easter_date"))
	{
		// Init
		$jour_unix = 86400;
		$paques_unix = easter_date($annee);
		// Lundi de pâques
		$date = strftime("%Y-%m-%d", $paques_unix+$jour_unix);
		$tab_jours_feries[$date] = "Lundi de pâques";
		// Jeudi de l'ascension
		$date = strftime("%Y-%m-%d", $paques_unix + ($jour_unix*39));
		$tab_jours_feries[$date] = "Jeudi de l'ascension";
		// Lundi de pentecôte
		$date = strftime("%Y-%m-%d", $paques_unix + ($jour_unix*50));
		$tab_jours_feries[$date] = "Lundi de pentecôte";
	}

	////	Les fêtes fixes
	// Jour de l'an
	$tab_jours_feries[$annee."-01-01"] = "Jour de l'an";
	// Fête du travail
	$tab_jours_feries[$annee."-05-01"] = "Fête du travail";
	// Armistice 39-45
	$tab_jours_feries[$annee."-05-08"] = "Armistice 39-45";
	// Fête nationale
	$tab_jours_feries[$annee."-07-14"] = "Fête nationale";
	// Assomption
	$tab_jours_feries[$annee."-08-15"] = "Assomption";
	// Toussaint
	$tab_jours_feries[$annee."-11-01"] = "Toussaint";
	// Armistice 14-18
	$tab_jours_feries[$annee."-11-11"] = "Armistice 14-18";
	// Noël
	$tab_jours_feries[$annee."-12-25"] = "Noël";

	////	Retourne le résultat
	return $tab_jours_feries;
}




////	COMMUN
////

// Divers
$trad["remplir_tous_champs"] = "Merci de remplir tous les champs";
$trad["voir_detail"] = "Voir en détail";
$trad["elem_inaccessible"] = "Élément inaccessible";
$trad["champs_obligatoire"] = "Champ obligatoire";
$trad["oui"] = "oui";
$trad["non"] = "non";
$trad["aucun"] = "aucun";
$trad["aller_page"] = "Aller à la page";
$trad["alphabet_filtre"] = "Filtre alphabetique";
$trad["tout"] = "Tout";
$trad["tout_afficher"] = "Tout afficher";
$trad["important"] = "Important";
$trad["afficher"] = "afficher";
$trad["masquer"] = "masquer";
$trad["deplacer"] = "déplacer";
$trad["options"] = "Options";
$trad["reinitialiser"] = "réinitialiser";
$trad["garder"] = "Garder";
$trad["par_defaut"] = "Par défaut";
$trad["localiser_carte"] = "Localiser sur une carte";
$trad["espace_public"] = "Espace public";
$trad["bienvenue_agora"] = "Bienvenue sur l'Agora!";
$trad["mail_pas_valide"] = "L'e-mail n'est pas valide";
$trad["element"] = "élément";
$trad["elements"] = "éléments";
$trad["dossier"] = "dossier";
$trad["dossiers"] = "dossiers";
$trad["fermer"] = "Fermer";
$trad["imprimer"] = "Imprimer";
$trad["select_couleur"] = "Sélectionner la couleur";
$trad["visible_espaces"] = "Espaces où il sera visible";
$trad["visible_ts_espaces"] = "Visible sur tous les espaces";
$trad["admin_only"] = "Administrateur uniquement";
$trad["divers"] = "Divers";
$trad["confirm_close_lightbox"] = "Fermer le formulaire ?";
// images
$trad["photo"] = "Photo";
$trad["fond_ecran"] = "Fond d'écran";
$trad["image_changer"] = "changer";
$trad["pixels"] = "pixels";
// Connexion
$trad["specifier_login_password"] = "Merci de spécifier un identifiant et un mot de passe";
$trad["identifiant"] = "Identifiant";//d'une manière plus generale => nom d'utilisateur OU email
$trad["identifiant_connexion"] = "Identifiant de connexion";//d'une manière plus generale => nom d'utilisateur OU email
$trad["identifiant_ou_email"] = "Email / Identifiant";
$trad["pass"] = "Mot de passe";
$trad["pass2"] = "Confirmer mot de passe";
$trad["password_verif_alert"] = "Votre confirmation de mot de passe n'est pas valide";
$trad["connexion"] = "Connexion";
$trad["connexion_auto"] = "rester connecté";
$trad["connexion_auto_info"] = "Retenir mon identifiant et mot de passe pour une connexion automatique";
$trad["password_oublie"] = "mot de passe oublié ?";
$trad["password_oublie_info"] = "Envoyer mon identifiant et mot de passe à mon adresse mail";
$trad["acces_invite"] = "Accès invité";
$trad["espace_password_erreur"] = "Mot de passe erroné";
$trad["version_ie"] = "Votre navigateur est trop ancien et ne prend pas en charge tous les standards HTML : ll est vivement conseillé de le mettre à jour ou d'y intégrer Chrome Frame (www.google.com/chromeframe)";
// Type d'affichage
$trad["type_affichage"] = "Affichage";
$trad["type_affichage_liste"] = "Liste";
$trad["type_affichage_bloc"] = "Bloc";
$trad["type_affichage_arbo"] = "Arborescence";
// Sélectionner / Déselectionner tous les éléments
$trad["select_deselect"] = "Sélectionner / déselectionner";
$trad["aucun_element_selectionne"] = "Aucun élément n'a été sélectionné";
$trad["tout_selectionner"] = "Tout sélectionner";
$trad["inverser_selection"] = "Inverser la sélection";
$trad["suppr_elements"] = "Supprimer les éléments";
$trad["deplacer_elements"] = "Déplacer vers un autre dossier";
$trad["voir_sur_carte"] = "Voir les contacts sur une carte";
$trad["selectionner_user"] = "Merci de sélectionner au moins un utilisateur";
$trad["selectionner_2users"] = "Merci de sélectionner au moins 2 utilisateurs";
$trad["selectionner_espace"] = "Merci de sélectionner au moins un espace";
// Temps ("de 11h à 12h", "le 25-01-2007 à 10h30", etc.)
$trad["de"] = "de";
$trad["a"] = "à";
$trad["le"] = "le";
$trad["debut"] = "Début";
$trad["fin"] = "Fin";
$trad["separateur_horaire"] = "h";
$trad["jours"] = "jours";
$trad["jour_1"] = "Lundi";
$trad["jour_2"] = "Mardi";
$trad["jour_3"] = "Mercredi";
$trad["jour_4"] = "Jeudi";
$trad["jour_5"] = "Vendredi";
$trad["jour_6"] = "Samedi";
$trad["jour_7"] = "Dimanche";
$trad["mois_1"] = "janvier";
$trad["mois_2"] = "fevrier";
$trad["mois_3"] = "mars";
$trad["mois_4"] = "avril";
$trad["mois_5"] = "mai";
$trad["mois_6"] = "juin";
$trad["mois_7"] = "juillet";
$trad["mois_8"] = "aout";
$trad["mois_9"] = "septembre";
$trad["mois_10"] = "octobre";
$trad["mois_11"] = "novembre";
$trad["mois_12"] = "decembre";
$trad["mois_suivant"] = "mois suivant";
$trad["mois_precedant"] = "mois précédent";
$trad["annee_suivante"] = "année suivante";
$trad["annee_precedante"] = "année précédente";
$trad["aujourdhui"] = "aujourd'hui";
$trad["aff_aujourdhui"] = "Afficher aujourd'hui";
$trad["modif_dates_debutfin"] = "La date de fin ne peut pas être antérieure à la date de début";
// Nom & Description (pour les menus d'édition principalement)
$trad["titre"] = "Titre";
$trad["nom"] = "Nom";
$trad["description"] = "Description";
$trad["specifier_titre"] = "Merci de spécifier un titre";
$trad["specifier_nom"] = "Merci de spécifier un nom";
$trad["specifier_description"] = "Merci de spécifier une description";
$trad["specifier_titre_description"] = "Merci de spécifier un titre ou une description";
// Validation des formulaires
$trad["ajouter"] = " Ajouter";
$trad["modifier"] = " Modifier";
$trad["modifier_et_acces"] = "Modifier + gérer droits d'accès";
$trad["valider"] = " Valider";
$trad["lancer"] = " Lancer";
$trad["envoyer"] = "Envoyer";
$trad["envoyer_a"] = "Envoyer a";
// Tri d'affichage. Tous les éléments (dossier, tâche, lien, etc...) ont par défaut une date, un auteur & une description
$trad["trie_par"] = "Trié par";
$trad["tri"]["date_crea"] = "date de création";
$trad["tri"]["date_modif"] = "date de modif";
$trad["tri"]["titre"] = "titre";
$trad["tri"]["description"] = "description";
$trad["tri"]["id_utilisateur"] = "auteur";
$trad["tri"]["extension"] = "type de fichier";
$trad["tri"]["taille_octet"] = "taille";
$trad["tri"]["nb_downloads"] = "nb de téléchargements";
$trad["tri"]["civilite"] = "civilité";
$trad["tri"]["nom"] = "nom";
$trad["tri"]["prenom"] = "prénom";
$trad["tri"]["adresse"] = "adresse";
$trad["tri"]["codepostal"] = "code postal";
$trad["tri"]["ville"] = "ville";
$trad["tri"]["pays"] = "pays";
$trad["tri"]["fonction"] = "fonction";
$trad["tri"]["societe_organisme"] = "société / organisme";
$trad["tri_ascendant"] = "Ascendant";
$trad["tri_descendant"] = "Descendant";
// Options de suppression
$trad["confirmer"] = "Confirmer ?";
$trad["confirmer_suppr"] = "Confirmer la suppression ?";
$trad["confirmer_suppr_bis"] = "Êtes-vous sûr ?!";
$trad["confirmer_suppr_dossier"] = "Confirmer la suppression du dossier et toutes les données qu'il contient ? <br><br>Attention! certains sous-dossiers ne vous sont peut-être pas accessibles : ils seront également effacés !! ";
$trad["supprimer"] = "Supprimer";
// Visibilité d'un Objet : auteur et droits d'accès
$trad["auteur"] = "Auteur : ";
$trad["cree"] = "Créé";  //...12-12-2012
$trad["cree_par"] = "Création";
$trad["modif_par"] = "Modif.";
$trad["historique_element"] = "Historique de l'element";
$trad["invite"] = "invité";
$trad["invites"] = "invités";
$trad["tous"] = "tous";
$trad["inconnu"] = "personne inconnue";
$trad["acces_perso"] = "Accès personnel";
$trad["lecture"] = "lecture";
$trad["lecture_infos"] = "Accès en lecture";
$trad["ecriture_limit"] = "écriture limité";
$trad["ecriture_limit_infos"] = "Accès limité en écriture : possibilité d'ajouter des -ELEMENTS-, sans modifier ou supprimer ceux créés par d'autres utilisateurs";
$trad["ecriture"] = "écriture";
$trad["ecriture_infos"] = "Accès en écriture";
$trad["ecriture_infos_conteneur"] = "Accès en écriture : possibilité d'ajouter, modifier ou supprimer tous les -ELEMENTS- du -CONTENEUR-";
$trad["ecriture_racine_defaut"] = "Accès en écriture par défaut";
$trad["ecriture_auteur_admin"] = "Seul l'auteur et les administrateurs peuvent modifier les droits d'accès ou supprimer ce -CONTENEUR-";
$trad["contenu_dossier"] = "contenu";
$trad["aucun_acces"] = "accès non autorisé";
$trad["libelles_objets"] = array("element"=>"elements", "fichier"=>"fichiers", "tache"=>"taches", "lien"=>"liens", "contact"=>"contacts", "evenement"=>"evenements", "message"=>"messages", "conteneur"=>"conteneur", "dossier"=>"dossier", "agenda"=>"agenda", "sujet"=>"sujet");
// Envoi d'un mail (nouvel utilisateur, notification de création d'objet, etc...)
$trad["mail_envoye_par"] = "Envoyé par";  // "Envoyé par" M. Trucmuche
$trad["mail_envoye"] = "L'e-mail a bien été envoyé !";
$trad["mail_envoye_notif"] = "L'e-mail de notification a bien été envoyé !";
$trad["mail_pas_envoye"] = "L'e-mail n'a pas pu être envoyé..."; // idem
// Dossier & fichier
$trad["giga_octet"] = "Go";
$trad["mega_octet"] = "Mo";
$trad["kilo_octet"] = "Ko";
$trad["octet"] = "Octets";
$trad["dossier_racine"] = "Dossier racine";
$trad["deplacer_autre_dossier"] = "Deplacer vers un autre dossier";
$trad["ajouter_dossier"] = "ajouter dossier";
$trad["modifier_dossier"] = "Modifier un dossier";
$trad["telecharger"] = "Télécharger";
$trad["telecharge_nb"] = "Téléchargé";
$trad["telecharge_nb_bis"] = "fois"; // Téléchargé 'n' fois
$trad["telecharger_dossier"] = "Télécharger le dossier";
$trad["espace_disque_utilise"] = "Espace disque utilisé";
$trad["espace_disque_utilise_mod_fichier"] = "Espace disque utilisé sur le module fichier";
$trad["download_alert"] = "Telechargement inaccessible en journée (taille du fichier trop importante)";
// Infos sur une personne
$trad["civilite"] = "Civilité";
$trad["nom"] = "Nom";
$trad["prenom"] = "Prénom";
$trad["adresse"] = "Adresse";
$trad["codepostal"] = "Code postal";
$trad["ville"] = "Ville";
$trad["pays"] = "Pays";
$trad["telephone"] = "Téléphone";
$trad["telmobile"] = "Tél. mobile";
$trad["mail"] = "Email";
$trad["fax"] = "Fax";
$trad["siteweb"] = "Site Web";
$trad["competences"] = "Compétences";
$trad["hobbies"] = "Centres d'intérêt";
$trad["fonction"] = "Fonction";
$trad["societe_organisme"] = "Organisme / Société";
$trad["commentaire"] = "Commentaire";
// Rechercher
$trad["preciser_text"] = "Merci de préciser des mots clés d'au moins 3 caractères";
$trad["rechercher"] = "Rechercher";
$trad["rechercher_date_crea"] = "Date de création";
$trad["rechercher_date_crea_jour"] = "moins d'un jour";
$trad["rechercher_date_crea_semaine"] = "moins d'une semaine";
$trad["rechercher_date_crea_mois"] = "moins d'un mois";
$trad["rechercher_date_crea_annee"] = "moins d'un an";
$trad["rechercher_espace"] = "Rechercher sur l'espace";
$trad["recherche_avancee"] =  "Recherche avancée";
$trad["recherche_avancee_mots_certains"] =  "n'importe quel mot";
$trad["recherche_avancee_mots_tous"] =  "tous les mots";
$trad["recherche_avancee_expression_exacte"] =  "expression exacte";
$trad["recherche_avancee_champs"] =  "champs de recherche";
$trad["recherche_avancee_pas_concordance"] =  "Les modules et les champs sélectionnés ne correspondent pas. Merci de revoir leur concordance dans la recherche avancée.";
$trad["mots_cles"] = "Mots clés";
$trad["liste_modules"] = "Modules";
$trad["liste_champs"] = "Champs";
$trad["liste_champs_elements"] = "Elements concernés";
$trad["aucun_resultat"] = "Aucun résultat";
// Captcha
$trad["captcha"] = "Identification visuelle";
$trad["captcha_info"] = "Merci de recopier les 4 caractères pour votre identification";
$trad["captcha_alert_specifier"] = "Merci de spécifier l'identification visuelle";
$trad["captcha_alert_erronee"] = "L'identification visuelle est erronée";
// Gestion des inscriptions d'utilisateur
$trad["inscription_users"] = "m'inscrire sur le site";
$trad["inscription_users_info"] = "créer un nouveau compte utilisateur (validé par un administrateur)";
$trad["inscription_users_espace"] = "m'inscrire à l'espace";//.."trucmuche"
$trad["inscription_users_enregistre"] = "votre inscription a bien été enregistrée : elle sera validé dès que possible par l'administrateur de l'espace";
$trad["inscription_users_option_espace"] = "Les visiteurs peuvent s'incrire sur l'espace";
$trad["inscription_users_option_espace_info"] = "L'inscription d'un utilisateur se fait sur la page d'accueil du site. Elle doit ensuite être validée par l'administrateur de l'espace !";
$trad["inscription_users_validation"] = "Valider l'inscriptions d'utilisateurs";
$trad["inscription_users_valider"] = "Valider";
$trad["inscription_users_invalider"] = "Invalider";
$trad["inscription_users_valider_mail"] = "Votre compte à bien été validé sur";
$trad["inscription_users_invalider_mail"] = "Votre compte n'a pas été validé sur";
// Importer ou Exporter : Contact OU Utilisateurs
$trad["exporter"] = "Exporter";
$trad["importer"] = "Importer";
$trad["export_import_users"] = "des utilisateurs";
$trad["export_import_contacts"] = "des contacts";
$trad["export_format"] = "format";
$trad["contact_separateur"] = "separateur";
$trad["contact_delimiteur"] = "delimiteur";
$trad["specifier_fichier"] = "Merci de spécifier un fichier";
$trad["extension_fichier"] = "Le type de fichier n'est pas valide. Il doit être de type";
$trad["format_fichier_invalide"] = "Le format du fichier ne correspond pas type sélectionné";
$trad["import_infos"] = "Sélectionnez les champs Agora à cibler grâce aux listes déroulantes de chaque colonne.";
$trad["import_infos_contact"] = "Les contacts seront affectés par défaut à l'espace courant.";
$trad["import_infos_user"] = "Si l'identifiant / mot de passe ne sont pas sélectionnés, ils seront générés automatiquement !";
$trad["import_alert"] = "Merci de sélectionner la colonne nom dans les listes déroulante";
$trad["import_alert2"] = "Merci de sélectionner au moins un element à importer";
$trad["import_alert3"] = "Le champ agora à déjà été sélectionné sur une autre colonne (chaque champs agora ne peut être sélectionné qu'une fois)";
// Connexion à un serveur LDAP
$trad["ldap_connexion_serveur"] = "Connexion à un serveur LDAP";
$trad["ldap_server"] = "Adresse sur serveur";
$trad["ldap_server_port"] = "Port sur serveur";
$trad["ldap_server_port_infos"] = "''389'' par défaut";
$trad["ldap_admin_login"] = "Chaine de connexion pour l'admin";
$trad["ldap_admin_login_infos"] = "par exemple ''uid=admin,ou=mon_entreprise''";
$trad["ldap_admin_pass"] = "Mot de passe de l'admin";
$trad["ldap_groupe_dn"] = "Groupe d'utilisateurs / base DN";
$trad["ldap_groupe_dn_infos"] = "Emplacement des utilisateurs dans l'annuaire.<br>Par exemple ''ou=users,o=mon_organisme''";
$trad["ldap_connexion_erreur"] = "Erreur de connexion au serveur LDAP !";
$trad["ldap_import_infos"] = "La configuration du serveur LDAP s'effectue dans le module administration.";
$trad["ldap_crea_auto_users"] = "Création auto d'utilisateurs après identification LDAP";
$trad["ldap_crea_auto_users_infos"] = "Créer automatiquement un utilisateur s'il est absent de l'Agora mais présent sur le serveur LDAP : il sera alors affecté aux espaces accessibles à ''tous les utilisateurs du site''.<br>Dans le cas contraire, l'utilisateur ne sera pas créé.";
$trad["ldap_pass_cryptage"] = "Cryptage des mots de passe sur le serveur LDAP";
$trad["ldap_effacer_params"] = "Effacer le parametrage LDAP ?";
$trad["ldap_pas_module_php"] = "Le module PHP de connexion à un serveur LDAP n'est pas installé !"; 




////	DIVERS
////

// Messages d'alerte / d'erreur
$trad["MSG_ALERTE_identification"] = "Identifiant ou mot de passe invalide";
$trad["MSG_ALERTE_dejapresent"] = "Compte actuellement utilisé avec une autre adresse ip... (un compte ne peut être utilisé que sur un seul poste en même temps)";
$trad["MSG_ALERTE_adresseip"] = "L'Adresse IP que vous utilisez n'est pas autorisée pour ce compte";
$trad["MSG_ALERTE_pasaccesite"] = "L'accès au site ne vous est pas autorisé car actuellement, vous n'êtes probablement affecté à aucun espace.";
$trad["MSG_ALERTE_captcha"] = "L'identification visuelle est erronée";
$trad["MSG_ALERTE_acces_fichier"] = "Fichier inaccessible";
$trad["MSG_ALERTE_acces_dossier"] = "Dossier inaccessible";
$trad["MSG_ALERTE_espace_disque"] = "L'espace pour le stockage de vos fichiers est insuffisant, vous ne pouvez pas ajouter de fichier";
$trad["MSG_ALERTE_taille_fichier"] = "La taille du fichier est trop importante";
$trad["MSG_ALERTE_type_version"] = "Type de fichier différent de l'original";
$trad["MSG_ALERTE_type_interdit"] = "Type de fichier non autorisé";
$trad["MSG_ALERTE_deplacement_dossier"] = "Vous ne pouvez pas déplacer le dossier à l'intérieur de lui-même..!";
$trad["MSG_ALERTE_nom_dossier"] = "Un dossier portant le même nom existe déjà. Confirmer tout de même ?";
$trad["MSG_ALERTE_nom_fichier"] = "Un fichier avec le même nom existe déjà, mais n'a pas été remplacé";
$trad["MSG_ALERTE_chmod_stock_fichiers"] = "Le gestionnaire de fichiers n'est pas accessible en écriture. Merci de faire un ''chmod 775'' sur le dossier ''stock_fichiers'' (accès en lecture-ecriture au proprietaire et au groupe)";
$trad["MSG_ALERTE_nb_users"] = "Vous ne pouvez pas créer de nouveau compte utilisateur : limité à "; // "...limité à" 10
$trad["MSG_ALERTE_miseajourconfig"] = "Le fichier de configuration (config.inc.php) n'est pas accessible en écriture : mise à jour impossible!";
$trad["MSG_ALERTE_miseajour"] = "Mise à jour terminée. \\n\\nIl est conseillé de redémarrer votre navigateur avant de vous reconnecter !\\n\\n";
$trad["MSG_ALERTE_user_existdeja"] = "L'identifiant existe déjà : l'utilisateur n'a donc pas été créé";
$trad["MSG_ALERTE_temps_session"] = "Votre session vient d'expirer, merci de vous reconnecter";
$trad["MSG_ALERTE_specifier_nombre"] = "Merci de spécifier un nombre";
// header menu
$trad["HEADER_MENU_espace_administration"] = "Administration du site";
$trad["HEADER_MENU_espaces_dispo"] = "Espaces disponibles";
$trad["HEADER_MENU_espace_acces_administration"] = "(accès administration)";
$trad["HEADER_MENU_affichage_elem"] = "Afficher les éléménts";
$trad["HEADER_MENU_affichage_normal"] = "qui me sont affectés";
$trad["HEADER_MENU_affichage_normal_infos"] = "C'est l'affichage normal / par défaut";
$trad["HEADER_MENU_affichage_auteur"] = "que j'ai créés";
$trad["HEADER_MENU_affichage_auteur_infos"] = "Pour afficher uniquement les éléments que j'ai créé";
$trad["HEADER_MENU_affichage_tout"] = "Tous les éléments de l'espace (admin)";
$trad["HEADER_MENU_affichage_tout_infos"] = "Réservé à l'administrateur de l'espace : permet d'afficher tous les éléments sur l'espace, même ceux qui ne sont pas affectés à l'administrateur !";
$trad["HEADER_MENU_recherche_elem"] = "Rechercher un element sur l'espace";
$trad["HEADER_MENU_sortie_agora"] = "Déconnexion";
$trad["HEADER_MENU_raccourcis"] = "Raccourcis";
$trad["HEADER_MENU_seul_utilisateur_connecte"] = "Vous êtes actuellement le seul connecté";
$trad["HEADER_MENU_en_ligne"] = "En ligne";
$trad["HEADER_MENU_connecte_a"] = "connecté au site à";   // M. Bidule truc "connecté au site à" 12:45
$trad["HEADER_MENU_messenger"] = "Messagerie instantanée";
$trad["HEADER_MENU_envoye_a"] = "Envoyé à";
$trad["HEADER_MENU_ajouter_message"] = "Ajouter un message";
$trad["HEADER_MENU_specifier_message"] = "Merci de spécifier un message";
$trad["HEADER_MENU_enregistrer_conversation"] = "Enregistrer cette conversation";
// Footer
$trad["FOOTER_page_generee"] = "page générée en";
// Password_oublie
$trad["PASS_OUBLIE_preciser_mail"] = "Indiquez votre adresse e-mail pour recevoir vos coordonnées de connexion";
$trad["PASS_OUBLIE_mail_inexistant"] = "L'e-mail indiqué n'existe pas dans la base";
$trad["PASS_OUBLIE_mail_objet"] = "Connexion à votre espace";
$trad["PASS_OUBLIE_mail_contenu"] = "Votre identifiant de connexion";
$trad["PASS_OUBLIE_mail_contenu_bis"] = "Cliquer ici pour réinitialiser votre mot de passe";
$trad["PASS_OUBLIE_prompt_changer_pass"] = "Veuillez spécifier votre nouveau mot de passe";
$trad["PASS_OUBLIE_id_newpassword_expire"] = "Le lien pour régénérer le mot de passe a expiré.. merci de recommencer la procédure";
$trad["PASS_OUBLIE_password_reinitialise"] = "Votre nouveau mot de passe a été enregistré !";
// menu_edit_objet
$trad["EDIT_OBJET_alert_aucune_selection"] = "Vous devez sélectionner au moins une personne ou un espace";
$trad["EDIT_OBJET_alert_pas_acces_perso"] = "Vous n'êtes pas affecté à l'élément. valider tout de même ?";
$trad["EDIT_OBJET_alert_ecriture"] = "Il doit y avoir au moins une personne ou un espace affecté en écriture";
$trad["EDIT_OBJET_alert_ecriture_limite_defaut"] = "Attention ! avec un accès en ecriture, les personnes concernées pourront effacer tous les messages du sujet ! \\n\\nIl est donc conseillé de mettre un accès en écriture limité";
$trad["EDIT_OBJET_alert_invite"] = "Merci de préciser un nom ou un pseudo";
$trad["EDIT_OBJET_droit_acces"] = "Droits d'accès";
$trad["EDIT_OBJET_espace_pas_module"] = "Le module courant n'a pas encore été ajouté à cet espace";
$trad["EDIT_OBJET_tous_utilisateurs"] = "Tous les utilisateurs de l'espace";
$trad["EDIT_OBJET_tous_utilisateurs_espaces"] = "Tous les espaces";
$trad["EDIT_OBJET_espace_invites"] = "Invités de l'espace";
$trad["EDIT_OBJET_aucun_users"] = "Actuellement aucun utilisateur dans cet espace";
$trad["EDIT_OBJET_invite"] = "Votre Nom / Pseudo";
$trad["EDIT_OBJET_admin_espace"] = "Administrateur de l'espace : accède en écriture à tous les elements de l'espace";
$trad["EDIT_OBJET_tous_espaces"] = "Afficher tous mes espaces";
$trad["EDIT_OBJET_notif_mail"] = "Notifier par e-mail";
$trad["EDIT_OBJET_notif_mail_joindre_fichiers"] = "Joindre les fichiers à la notification";
$trad["EDIT_OBJET_notif_mail_info"] = "Envoyer une notification de création/modification par e-mail<br> aux personnes affectées à l'élément";
$trad["EDIT_OBJET_notif_mail_selection"] = "Sélectionner manuellement les destinataires des notifications";
$trad["EDIT_OBJET_notif_tous_users"] = "Afficher + d'utilisateurs";
$trad["EDIT_OBJET_droits_ss_dossiers"] = "Donner les mêmes droits aux sous-dossiers";
$trad["EDIT_OBJET_raccourci"] = "Raccourci";
$trad["EDIT_OBJET_raccourci_info"] = "Afficher un raccourci dans le menu principal";
$trad["EDIT_OBJET_fichier_joint"] = "Joindre des fichiers (photo, video..)";
$trad["EDIT_OBJET_inserer_fichier"] = "Afficher dans la description";
$trad["EDIT_OBJET_inserer_fichier_info"] = "Afficher l'image / video / Lecteur Mp3... dans la description ci-dessus. L'insertion sera réalisé après validation du formulaire.";
$trad["EDIT_OBJET_inserer_fichier_alert"] = "Cliquez sur ''Insérer'' pour ajouter les images dans le texte / la description";
$trad["EDIT_OBJET_demande_a_confirmer"] = "Votre demande a bien été enregistrée. Elle sera confirmée prochainement.";
// Formulaire d'installation
$trad["INSTALL_connexion_bdd"] = "Connexion à la base de données";
$trad["INSTALL_db_host"] = "Nom d'Hote du serveur (Hostname)";
$trad["INSTALL_db_name"] = "Nom de la Base de Données";
$trad["INSTALL_db_name_info"] = "Attention !!<br> Si la base de données de l'Agora existe déjà, elle sera remplacée (uniquement les tables qui commencent par ''gt_'')";
$trad["INSTALL_db_login"] = "Nom d'utilisateur";
$trad["INSTALL_db_password"] = "Mot de passe";
$trad["INSTALL_login_password_info"] = "Pour se connecter en tant qu'administrateur général";
$trad["INSTALL_config_admin"] = "Administrateur de l'Agora";
$trad["INSTALL_config_espace"] = "Configuration de l'espace principal";
$trad["INSTALL_erreur_acces_mysql"] = "La connexion au serveur de base de données à échoué";
$trad["INSTALL_erreur_acces_bdd"] = "La base de données n'existe pas : confirmer tout de même et créer la base de données ?";
$trad["INSTALL_erreur_agora_existe"] = "La base de données de l'Agora existe déjà ! Confirmer tout de même et remplacer les tables ?";
$trad["INSTALL_confirm_version_php"] = "Agora-Project necessite une version 4.3 minimum de PHP, confirmer tout de même ?";
$trad["INSTALL_confirm_version_mysql"] = "Agora-Project necessite une version 4.2 minimum de MySQL, confirmer tout de même ?";
$trad["INSTALL_confirm_install"] = "Confirmer l'installation ?";
$trad["INSTALL_install_ok"] = "Agora-Project a bien été installé ! Pour des raisons de sécurité, pensez à supprimer le dossier 'install' avant de commencer";




////	MODULE_PARAMETRAGE
////

// Menu principal
$trad["PARAMETRAGE_nom_module"] = "Paramétrage";
$trad["PARAMETRAGE_nom_module_header"] = "Paramétrage";
$trad["PARAMETRAGE_description_module"] = "Paramétrage général";
// Index.php
$trad["PARAMETRAGE_sav"] = "Sauvegarder la base de données et les fichiers";
$trad["PARAMETRAGE_sav_alert"] = "La création du fichier de sauvegarde peut durer quelques minutes... et son téléchargement quelques dizaines de minutes.";
$trad["PARAMETRAGE_sav_bdd"] = "Sauvegarder la base de données";
$trad["PARAMETRAGE_adresse_web_invalide"] = "Désolé mais l'adresse de connexion n'est pas valide : elle doit commencer par HTTP:// ";
$trad["PARAMETRAGE_espace_disque_invalide"] = "L'espace disque limite doit être un entier";
$trad["PARAMETRAGE_confirmez_modification_site"] = "Confirmez-vous les modifications ?";
$trad["PARAMETRAGE_nom_site"] = "Nom du site";
$trad["PARAMETRAGE_adresse_web"] = "Adresse de connexion au site";
$trad["PARAMETRAGE_footer_html"] = "Footer / pied de page html";
$trad["PARAMETRAGE_footer_html_info"] = "Pour inclure des outils de statistique par exemple";
$trad["PARAMETRAGE_langues"] = "Langue par défaut";
$trad["PARAMETRAGE_timezone"] = "Fuseau horaire";
$trad["PARAMETRAGE_nom_espace"] = "Nom de l'espace principal";
$trad["PARAMETRAGE_limite_espace_disque"] = "Espace disque disponible pour les fichiers";
$trad["PARAMETRAGE_logs_jours_conservation"] = "Durée de conservation des Logs d'événements";
$trad["PARAMETRAGE_mode_edition"] = "Edition des éléments";
$trad["PARAMETRAGE_edition_popup"] = "dans une fenêtre popup";
$trad["PARAMETRAGE_edition_iframe"] = "dans un iframe (même fenêtre)";
$trad["PARAMETRAGE_skin"] = "Couleur de l'interface (fond des éléments, menus, etc.)";
$trad["PARAMETRAGE_noir"] = "Noir";
$trad["PARAMETRAGE_blanc"] = "Blanc";
$trad["PARAMETRAGE_erreur_fond_ecran_logo"] = "L'image de fond d'écran et le logo doivent être au format .jpg ou .png";
$trad["PARAMETRAGE_suppr_fond_ecran"] = "Supprimer le fond d'écran ?";
$trad["PARAMETRAGE_logo_footer"] = "Logo en bas de page";
$trad["PARAMETRAGE_logo_footer_url"] = "URL";
$trad["PARAMETRAGE_editeur_text_mode"] = "Mode de l'éditeur de texte (TinyMCE)";
$trad["PARAMETRAGE_editeur_text_minimal"] = "Minimal";
$trad["PARAMETRAGE_editeur_text_complet"] = "Complet  (Minimal + tableaux, medias, coller depuis Word)";
$trad["PARAMETRAGE_messenger_desactive"] = "Messagerie instantanée activée";
$trad["PARAMETRAGE_agenda_perso_desactive"] = "Agendas personnels activés par défaut";
$trad["PARAMETRAGE_agenda_perso_desactive_infos"] = "Ajouter par défaut un agenda personnel à la création d'un utilisateur. L'agenda pourra toutefois être désactivé par la suite, en modifiant le compte de l'utilisateur.";
$trad["PARAMETRAGE_libelle_module"] = "Nom des modules dans la barre de menu du haut";
$trad["PARAMETRAGE_libelle_module_masquer"] = "masquer";
$trad["PARAMETRAGE_libelle_module_icones"] = "au dessus de chaque icône de module";
$trad["PARAMETRAGE_libelle_module_page"] = "uniquement pour le module courant";
$trad["PARAMETRAGE_tri_personnes"] = "Trier les utilisateurs et contacts par";
$trad["PARAMETRAGE_versions"] = "Versions";
$trad["PARAMETRAGE_version_agora_maj"] = "mis à jour le";
$trad["PARAMETRAGE_fonction_mail_desactive"] = "Fonction PHP pour envoyer des e-mails : désactivée !";
$trad["PARAMETRAGE_fonction_mail_infos"] = "Certains hébergeurs désactivent la fonction PHP d'envoi de mails pour des raisons de sécurité ou de saturation des serveurs (SPAM)";
$trad["PARAMETRAGE_fonction_image_desactive"] = "Fonction de manipulation d'images et de vignettes (PHP GD2) : désactivée !";




////	MODULE_LOG
////

// Menu principal
$trad["LOGS_nom_module"] = "Logs";
$trad["LOGS_nom_module_header"] = "Logs";
$trad["LOGS_description_module"] = "Logs - Journal des événements";
// Index.php
$trad["LOGS_filtre"] = "Filtre";
$trad["LOGS_date_heure"] = "Date/Heure";
$trad["LOGS_espace"] = "Espace";
$trad["LOGS_module"] = "Module";
$trad["LOGS_action"] = "Action";
$trad["LOGS_utilisateur"] = "Utilisateur";
$trad["LOGS_adresse_ip"] = "IP";
$trad["LOGS_commentaire"] = "Commentaire";
$trad["LOGS_no_logs"] = "Aucun log";
$trad["LOGS_filtre_a_partir"] = "filtré à partir des";
$trad["LOGS_chercher"] = "Chercher";
$trad["LOGS_chargement"] = "Chargement des données";
$trad["LOGS_connexion"] = "connexion";
$trad["LOGS_deconnexion"] = "déconnexion";
$trad["LOGS_consult"] = "consultation";
$trad["LOGS_consult2"] = "telechargement";
$trad["LOGS_ajout"] = "ajout";
$trad["LOGS_suppr"] = "suppression";
$trad["LOGS_modif"] = "modification";




////	MODULE_ESPACE
////

// Menu principal
$trad["ESPACES_nom_module"] = "Espaces";
$trad["ESPACES_nom_module_header"] = "Espaces";
$trad["ESPACES_description_module"] = "Espaces du site";
$trad["ESPACES_description_module_infos"] = "Le site (ou espace principal) peut être subdivisé en plusieurs espaces";
// Header_menu.inc.php
$trad["ESPACES_gerer_espaces"] = "Gérer les espaces du site";
$trad["ESPACES_parametrage"] = "Paramétrage de l'espace";
$trad["ESPACES_parametrage_infos"] = "Paramétrage de l'espace (description, modules, utilisateurs, etc)";
// Index.php
$trad["ESPACES_confirm_suppr_espace"] = "Confirmer la suppression ? Attention, les données affectées uniquement à cet espace seront définitivement perdues !!";
$trad["ESPACES_espace"] = "espace";
$trad["ESPACES_espaces"] = "espaces";
$trad["ESPACES_definir_acces"] = "A définir !";
$trad["ESPACES_modules"] = "Modules";
$trad["ESPACES_ajouter_espace"] = "Ajouter un espace";
$trad["ESPACES_supprimer_espace"] = "Supprimer l'espace";
$trad["ESPACES_aucun_espace"] = "Aucun espace pour le moment";
$trad["MSG_ALERTE_suppr_espace_impossible"] = "Vous ne pouvez pas supprimer l'espace courant";
// Espace_edit.php
$trad["ESPACES_gestion_acces"] = "Utilisateurs affectés à l'espace";
$trad["ESPACES_selectionner_module"] = "Vous devez sélectionner au moins un module";
$trad["ESPACES_modules_espace"] = "Modules de l'espace";
$trad["ESPACES_modules_classement"] = "Déplacer pour définir l'ordre d'affichage des modules";
$trad["ESPACES_selectionner_utilisateur"] = "Sélectionner certains utilisateurs, tous les utilisateurs ou ouvrir l'espace au public";
$trad["ESPACES_espace_public"] = "Espace public";
$trad["ESPACES_public_infos"] = "Donne accès aux personnes qui n'ont pas de compte sur le site : ''invités''. Possibilité de spécifier un mot de passe pour protéger l'accès.";
$trad["ESPACES_invitations_users"] = "Les utilisateurs peuvent envoyer des invitations par mail";
$trad["ESPACES_invitations_users_infos"] = "Tous les utilisateurs peuvent envoyer des invitations par mail pour rejoindre l'espace";
$trad["ESPACES_tous_utilisateurs"] = "Tous les utilisateurs du site";
$trad["ESPACES_utilisation"] = " Utilisateur";
$trad["ESPACES_utilisation_info"] = "Accès normal à l'espace";
$trad["ESPACES_administration"] = "Administrateur";
$trad["ESPACES_administration_info"] = "Administrateur de l'espace : Accès en écriture à tous les éléments de l'espace + envoi d'invitations par mail + création d'utilisateurs sur l'espace";
$trad["ESPACES_creer_agenda_espace"] = "Créer un agenda pour l'espace";
$trad["ESPACES_creer_agenda_espace_info"] = "Cette option est utile si les agendas des utilisateurs sont désactivés. L'agenda portera alors le même nom que l'espace et fonctionnera comme un agenda de ressource.";




////	MODULE_UTILISATEUR
////

// Menu principal
$trad["UTILISATEURS_nom_module"] = "Utilisateurs";
$trad["UTILISATEURS_nom_module_header"] = "Utilisateurs";
$trad["UTILISATEURS_description_module"] = "Utilisateurs";
$trad["UTILISATEURS_ajout_utilisateurs_groupe"] = "Tous les utilisateurs peuvent créer des groupes";
// Index.php
$trad["UTILISATEURS_utilisateurs_site"] = "Tous les utilisateurs";
$trad["UTILISATEURS_gerer_utilisateurs_site"] = "Gérer tous les utilisateurs";
$trad["UTILISATEURS_utilisateurs_site_infos"] = "Ensemble des utilisateurs du site, tous espaces confondus";
$trad["UTILISATEURS_utilisateurs_espace"] = "Utilisateurs de l'espace";
$trad["UTILISATEURS_confirm_suppr_utilisateur"] = "Confirmer la suppression de l'utilisateur ? Attention, ce profil sera définitivement perdu !";
$trad["UTILISATEURS_confirm_desaffecter_utilisateur"] = "Confirmer la désaffectation de l'utilisateur à l'espace courant ?";
$trad["UTILISATEURS_suppr_definitivement"] = "Supprimer définitivement";
$trad["UTILISATEURS_desaffecter"] = "Désaffecter de l'espace";
$trad["UTILISATEURS_tous_user_affecte_espace"] = "Tous les utilisateurs du site sont affectés à cet espace : pas de désaffectation possible";
$trad["UTILISATEURS_utilisateur"] = "utilisateur";
$trad["UTILISATEURS_utilisateurs"] = "utilisateurs";
$trad["UTILISATEURS_affecter_utilisateur"] = "Ajouter un utilis. existant à l'espace";
$trad["UTILISATEURS_ajouter_utilisateur"] = "Ajouter un utilisateur";
$trad["UTILISATEURS_ajouter_utilisateur_site"] = "Créer un utilisateur sur le site : affecté par défaut à aucun espace !";
$trad["UTILISATEURS_ajouter_utilisateur_espace"] = "Créer un utilisateur pour l'espace courant";
$trad["UTILISATEURS_envoi_coordonnees"] = "Envoyer identifiant et mot de passe";
$trad["UTILISATEURS_envoi_coordonnees_infos"] = "Renvoyer par mail à des utilisateurs<br>leur identifiant et <u>un nouveau mot de passe</u>";
$trad["UTILISATEURS_envoi_coordonnees_infos2"] = "Envoyer par mail aux nouveaux utilisateurs<br>leur identifiant et mot de passe";
$trad["UTILISATEURS_envoi_coordonnees_confirm"] = "Attention : les mots de passe seront réinitialisés ! confirmer tout de même ?";
$trad["UTILISATEURS_mail_coordonnees"] = "Vos coordonnées de connexion";
$trad["UTILISATEURS_aucun_utilisateur"] = "Aucun utilisateur affecté à cet espace pour le moment";
$trad["UTILISATEURS_derniere_connexion"] = "Dernière connexion";
$trad["UTILISATEURS_liste_espaces"] = "Espaces de l'utilisateur";
$trad["UTILISATEURS_aucun_espace"] = "aucun espace";
$trad["UTILISATEURS_admin_general"] = "Administrateur général";
$trad["UTILISATEURS_admin_espace"] = "Administrateur de l'espace";
$trad["UTILISATEURS_user_espace"] = "Utilisateur de l'espace";
$trad["UTILISATEURS_user_site"] = "Utilisateur";
$trad["UTILISATEURS_pas_connecte"] = "Pas encore connecté";
$trad["UTILISATEURS_modifier"] = "Modifier l'utilisateur";
$trad["UTILISATEURS_modifier_mon_profil"] = "Modifier mon profil";
$trad["UTILISATEURS_pas_suppr_dernier_admin_ge"] = "Vous ne pouvez pas supprimer le dernier administrateur général !";
// Invitations
$trad["UTILISATEURS_envoi_invitation"] = "Inviter quelqu'un à rejoindre l'espace";
$trad["UTILISATEURS_envoi_invitation_info"] = "L'invitation sera envoyé par mail";
$trad["UTILISATEURS_objet_mail_invitation"] = "Invitation de "; // ..Jean DUPOND
$trad["UTILISATEURS_admin_invite_espace"] = "vous invite sur "; // Jean DUPOND "vous invite à rejoindre l'espace" Mon Espace
$trad["UTILISATEURS_confirmer_invitation"] = "Cliquer ici pour confirmer l'invitation";
$trad["UTILISATEURS_invitation_a_confirmer"] = "Invitation(s) en attente de confirmation";
$trad["UTILISATEURS_id_invitation_expire"] = "Le lien de votre invitation a expiré...";
$trad["UTILISATEURS_invitation_confirmer_password"] = "Merci de choisir votre mot de passe avant de valider votre invitation";
$trad["UTILISATEURS_invitation_valide"] = "Votre invitation a été validée !";
// groupes.php
$trad["UTILISATEURS_groupe_espace"] = "groupes d'utilisateur de l'espace";
$trad["UTILISATEURS_groupe_site"] = "tous les groupes d'utilisateurs";
$trad["UTILISATEURS_groupe_infos"] = "editer les groupes d'utilisateurs";
$trad["UTILISATEURS_groupe_espace_infos"] = "Seuls les utilisateurs se trouvant sur tous les espaces sélectionnés sont cochables";
$trad["UTILISATEURS_droit_gestion_groupes"] = "Chaque groupe peut être modifié par son auteur ou par l'administrateur général";
// Utilisateur_affecter.php
$trad["UTILISATEURS_preciser_recherche"] = "Merci de préciser un nom, un prénom ou une adresse e-mail";
$trad["UTILISATEURS_affecter_user_confirm"] = "Confirmer l'affectation de l'utilisateur à l'espace ?";
$trad["UTILISATEURS_rechercher_user"] = "Rechercher un utilisateur avant de l'ajouter à l'espace";
$trad["UTILISATEURS_tous_users_affectes"] = "Tous les utilisateurs du site sont déjà affectés à cet espace";
$trad["UTILISATEURS_affecter_user"] = "Affecter un utilisateur à l'espace :";
$trad["UTILISATEURS_aucun_users_recherche"] = "Aucun utilisateur pour cette recherche";
// Utilisateur_edit.php  & CO
$trad["UTILISATEURS_specifier_nom"] = "Merci de spécifier un nom";
$trad["UTILISATEURS_specifier_prenom"] = "Merci de spécifier un prénom";
$trad["UTILISATEURS_specifier_identifiant"] = "Merci de spécifier un identifiant";
$trad["UTILISATEURS_specifier_pass"] = "Merci de spécifier un mot de passe";
$trad["UTILISATEURS_pas_fichier_photo"] = "Vous n'avez pas spécifié d'image !";
$trad["UTILISATEURS_langues"] = "Langue";
$trad["UTILISATEURS_agenda_perso_active"] = "Agenda personnel activé";
$trad["UTILISATEURS_agenda_perso_active_infos"] = "Dans ce cas, l'agenda perso reste <u>toujours</u> accessible à l'utilisateur, même si le module Agenda est désactivé sur l'espace courant..";
$trad["UTILISATEURS_espace_connexion"] = "Espace affiché à la connexion";
$trad["UTILISATEURS_notification_mail"] = "Envoyer une notification de création par e-mail";
$trad["UTILISATEURS_alert_notification_mail"] = "Pensez à spécifier une adresse e-mail !";
$trad["UTILISATEURS_adresses_ip"] = "Adresses IP de contrôle";
$trad["UTILISATEURS_info_adresse_ip"] = "Si vous spécifiez une adresses IP (ou plusieurs), l'utilisateur ne pourra se connecter que s'il utilise les adresses IP spécifiées";
$trad["UTILISATEURS_ip_invalide"] = "Adresse IP invalide";
$trad["UTILISATEURS_identifiant_deja_present"] = "L'identifiant spécifié existe déjà. Merci d'en spécifier un autre";
$trad["UTILISATEURS_mail_deja_present"] = "L'adresse e-mail existe déjà. Merci d'en spécifier une autre";
$trad["UTILISATEURS_mail_objet_nouvel_utilisateur"] = "Bienvenue sur ";  //.."mon-espace"
$trad["UTILISATEURS_mail_nouvel_utilisateur"] = "Un nouveau compte vous a été attribué sur ";  //.."mon-espace"
$trad["UTILISATEURS_mail_infos_connexion"] = "Connectez vous avec les coordonnées suivantes";
$trad["UTILISATEURS_mail_infos_connexion2"] = "Merci de conserver cet e-mail dans vos archives.";
// Utilisateur_Messenger.php
$trad["UTILISATEURS_gestion_messenger_livecounter"] = "Gérer la messagerie instantanée";
$trad["UTILISATEURS_visibilite_messenger_livecounter"] = "Utilisateurs qui me voient en ligne et avec qui je peux discuter sur la messagerie instantanée";
$trad["UTILISATEURS_aucun_utilisateur_messenger"] = "Aucun utilisateur pour l'instant";
$trad["UTILISATEURS_voir_aucun_utilisateur"] = "Aucun utilisateur ne peut me voir";
$trad["UTILISATEURS_voir_tous_utilisateur"] = "Tous les utilisateurs peuvent me voir";
$trad["UTILISATEURS_voir_certains_utilisateur"] = "Certains utilisateurs peuvent me voir";




////	MODULE_TABLEAU BORD
////

// Menu principal + options du module
$trad["TABLEAU_BORD_nom_module"] = "Actualités & nouveaux éléments";
$trad["TABLEAU_BORD_nom_module_header"] = "Actualités";
$trad["TABLEAU_BORD_description_module"] = "Actualités & nouveaux éléments";
$trad["TABLEAU_BORD_ajout_actualite_admin"] = "Seul l'administrateur peut ajouter des actualités";
// Index.php
$trad["TABLEAU_BORD_new_elems"] = "éléments nouveaux";
$trad["TABLEAU_BORD_new_elems_bulle"] = "Eléments créés sur la période sélectionnée";
$trad["TABLEAU_BORD_new_elems_realises"] = "éléments courants";
$trad["TABLEAU_BORD_new_elems_realises_bulle"] = "Evenements et tâches <br>aujourd'hui";
$trad["TABLEAU_BORD_plugin_connexion"] = "depuis ma dernière connexion";
$trad["TABLEAU_BORD_plugin_jour"] = "d'aujourd'hui";
$trad["TABLEAU_BORD_plugin_semaine"] = "de la semaine";
$trad["TABLEAU_BORD_plugin_mois"] = "du mois";
$trad["TABLEAU_BORD_autre_periode"] = "Autre période";
$trad["TABLEAU_BORD_pas_nouveaux_elements"] = "Aucun élément sur la période";
$trad["TABLEAU_BORD_actualites"] = "Actualités";
$trad["TABLEAU_BORD_actualite"] = "actualité";
$trad["TABLEAU_BORD_actualites"] = "actualités";
$trad["TABLEAU_BORD_ajout_actualite"] = "Ajouter une actualité";
$trad["TABLEAU_BORD_actualites_offline"] = "Actualités archivées";
$trad["TABLEAU_BORD_pas_actualites"] = "Aucune actualité pour le moment";
// Actualite_edit.php
$trad["TABLEAU_BORD_mail_nouvelle_actualite_cree"] = "Nouvelle actualité créée par ";
$trad["TABLEAU_BORD_ala_une"] = "Afficher à la une";
$trad["TABLEAU_BORD_ala_une_info"] = "Toujours afficher cette actualité en premier";
$trad["TABLEAU_BORD_offline"] = "Archivé";
$trad["TABLEAU_BORD_date_online_auto"] = "Mise en ligne programmée";
$trad["TABLEAU_BORD_date_online_auto_alerte"] = "L'actualité a été archivée dans l'attente de sa mise en ligne automatique";
$trad["TABLEAU_BORD_date_offline_auto"] = "Archivage programmé";




////	MODULE_AGENDA
////

// Menu principal
$trad["AGENDA_nom_module"] = "Agendas";
$trad["AGENDA_nom_module_header"] = "Agendas";
$trad["AGENDA_description_module"] = "Agendas personnel et agendas partagés";
$trad["AGENDA_ajout_agenda_ressource_admin"] = "Seul l'administrateur peut ajouter des agendas de ressource";
$trad["AGENDA_ajout_categorie_admin"] = "Seul l'administrateur peut ajouter des categories d'événement";
// Index.php
$trad["AGENDA_agendas_visibles"] = "Agendas disponibles (perso et ressources)";
$trad["AGENDA_afficher_tous_agendas"] = "Afficher tous les agendas";
$trad["AGENDA_masquer_tous_agendas"] = "Masquer tous les agendas";
$trad["AGENDA_cocher_tous_agendas"] = "Cocher / décocher tous les agendas";
$trad["AGENDA_cocher_agendas_users"] = "Cocher / décocher les utilisateurs";
$trad["AGENDA_cocher_agendas_ressources"] = "Cocher / décocher les ressources";
$trad["AGENDA_imprimer_agendas"] = "Imprimer le/les agendas";
$trad["AGENDA_imprimer_agendas_infos"] = "Imprimez la page en mode paysage";
$trad["AGENDA_ajouter_agenda_ressource"] = "Ajouter un agenda de ressource";
$trad["AGENDA_ajouter_agenda_ressource_bis"] = "Ajouter un agenda de ressource : salle, véhicule, vidéoprojecteur, etc.";
$trad["AGENDA_exporter_ical"] = "Exporter les événements (format iCal)";
$trad["AGENDA_exporter_ical_mail"] = "Envoyer les événements par mail (iCal)";
$trad["AGENDA_exporter_ical_mail2"] = "Pour les intégrer dans un calendrier IPHONE, ANDROID, OUTLOOK, GOOGLE CALENDAR...";
$trad["AGENDA_importer_ical"] = "Importer les événements (iCal)";
$trad["AGENDA_importer_ical_etat"] = "Etat";
$trad["AGENDA_importer_ical_deja_present"] = "Déjà présent";
$trad["AGENDA_importer_ical_a_importer"] = "A importer";
$trad["AGENDA_suppr_anciens_evt"] = "Supprimer les évènements passés";
$trad["AGENDA_confirm_suppr_anciens_evt"] = "Êtes-vous sûr de vouloir supprimer définitivement les évènements qui précèdent la période affichée ?";
$trad["AGENDA_ajouter_evt_heure"] = "Ajouter un évènement à";
$trad["AGENDA_ajouter_evt_jour"] = "Ajouter un évènement à cette date";
$trad["AGENDA_evt_jour"] = "Jour";
$trad["AGENDA_evt_semaine"] = "Semaine";
$trad["AGENDA_evt_semaine_w"] = "Semaine de travail";
$trad["AGENDA_evt_mois"] = "Mois";
$trad["AGENDA_num_semaine"] = "Semaine";
$trad["AGENDA_voir_num_semaine"] = "Voir la semaine n°"; //...5
$trad["AGENDA_periode_suivante"] = "Période suivante";
$trad["AGENDA_periode_precedante"] = "Période précédente";
$trad["AGENDA_affectations_evt"] = "Evenement dans l'agenda de ";
$trad["AGENDA_affectations_evt_autres"] = "+ d'autres agendas non visibles";
$trad["AGENDA_affectations_evt_non_confirme"] = "Attente de confirmation : ";
$trad["AGENDA_evenements_proposes_pour_agenda"] = "Proposition pour "; // "Videoprojecteur" / "salle de réunion" / etc.
$trad["AGENDA_evenements_proposes_mon_agenda"] = "Proposition pour mon agenda perso.";
$trad["AGENDA_evenement_propose_par"] = "Proposé par";  // "Proposé par" M. Bidule
$trad["AGENDA_evenement_integrer"] = "Intégrer l'évènement à l'agenda ?";
$trad["AGENDA_evenement_pas_integrer"] = "Supprimer la proposition d'évènement ?";
$trad["AGENDA_supprimer_evt_agenda"] = "Supprimer dans cet agenda ?";
$trad["AGENDA_supprimer_evt_agendas"] = "Supprimer dans tous les agendas ?";
$trad["AGENDA_supprimer_evt_date"] = "Supprimer uniquement à cette date ?";
$trad["AGENDA_confirm_suppr_evt"] = "Supprimer l'évènement dans cet agenda ?";
$trad["AGENDA_confirm_suppr_evt_tout"] = "Supprimer l'évènement dans tous les agendas où il est affecté ?";
$trad["AGENDA_confirm_suppr_evt_date"] = "Supprimer l'évènement à cette date, dans tous les agendas où il est affecté ?";
$trad["AGENDA_evt_prive"] = "Évènement privé";
$trad["AGENDA_aucun_agenda_visible"] = "Aucun agenda affiché";
$trad["AGENDA_evt_proprio"] = "Événements que j'ai créés";
$trad["AGENDA_evt_proprio_inaccessibles"] = "Afficher uniquement ceux que j'ai créés pour des agendas auxquels je n'ai pas accès";
$trad["AGENDA_aucun_evt"] = "Aucun évènement";
$trad["AGENDA_proposer"] = "Proposer l'événement";
$trad["AGENDA_synthese"] = "Synthèse des agendas";
$trad["AGENDA_pourcent_agendas_occupes"] = "Agendas occupés";  // Agendas occupés : 2/5
$trad["AGENDA_agendas_details"] = "Voir le détail des agendas";
$trad["AGENDA_agendas_details_masquer"] = "Masquer le détail des agendas";
// Evenement.php
$trad["AGENDA_categorie"] = "Catégorie";
$trad["AGENDA_visibilite"] = "Visibilité";
$trad["AGENDA_visibilite_public"] = "public";
$trad["AGENDA_visibilite_public_cache"] = "public, mais détails masqués";
$trad["AGENDA_visibilite_prive"] = "privé";
// Agenda_edit.php
$trad["AGENDA_affichage_evt"] = "Affichage des événements";
$trad["AGENDA_affichage_evt_border"] = "Bordure de la même couleur que la catégorie";
$trad["AGENDA_affichage_evt_background"] = "Fond de la même couleur que la catégorie";
$trad["AGENDA_plage_horaire"] = "Plage horaire";
// Evenement_edit.php
$trad["AGENDA_periodicite"] = "Evenement périodique";
$trad["AGENDA_period_non"] = "Evenement ponctuel";
$trad["AGENDA_period_jour_semaine"] = "Toutes les semaines";
$trad["AGENDA_period_jour_mois"] = "Jours du mois";
$trad["AGENDA_period_mois"] = "Tous les mois";
$trad["AGENDA_period_mois_xdumois"] = "du mois"; // Le 21 du mois
$trad["AGENDA_period_annee"] = "Tous les ans";
$trad["AGENDA_period_mois_xdeannee"] = "de l'année"; // Le 21/12 de l'année
$trad["AGENDA_period_date_fin"] = "Fin de périodicité";
$trad["AGENDA_exception_periodicite"] = "Exception de périodicité";
$trad["AGENDA_agendas_affectations"] = "Affectation aux agendas";
$trad["AGENDA_verif_nb_agendas"] = "Merci de sélectionner au moins un agenda";
$trad["AGENDA_mail_nouvel_evenement_cree"] = "Nouvel évènement créé par ";
$trad["AGENDA_input_proposer"] = "Proposer l'événement au propriétaire de l'agenda";
$trad["AGENDA_input_affecter"] = "Ajouter l'événement à l'agenda";
$trad["AGENDA_info_proposer"] = "Proposition seulement (vous n'avez pas accès en écriture à cet agenda)";
$trad["AGENDA_info_pas_modif"] = "Modification non autorisé car vous n'avez pas accès en écriture à cet agenda";
$trad["AGENDA_visibilite_info"] = "<u>Public</u> : visible si l'on accède en lecture (ou+) aux agendas où l'evenement se trouve.<br><br><u>Public, mais détails masqués</u> : Idem, mais l'accès en lecture seule ne permet de voir que la plage horaire de l'événement.<br><br><u>Privé</u> : visible uniquement si l'on accède en écriture aux agendas où l'événement se trouve.";
$trad["AGENDA_edit_limite"] = "Vous n'êtes pas l'auteur de l'événement et il a été affecté à des agendas qui ne vous sont pas accessible en écriture : vous ne pouvez que gérer les affectations à votre/vos agenda(s)";
$trad["AGENDA_creneau_occupe"] = "Le créneau horaire est déjà occupé sur l'agenda(s) :";
// Categories.php
$trad["AGENDA_gerer_categories"] = "Gérer les catégories d'évènements";
$trad["AGENDA_categories_evt"] = "Catégories d'évènement";
$trad["AGENDA_droit_gestion_categories"] = "Chaque categorie peut être modifié par son auteur ou par l'administrateur général";




////	MODULE_FICHIER
////

// Menu principal
$trad["FICHIER_nom_module"] = "Gestionnaire de fichiers";
$trad["FICHIER_nom_module_header"] = "Fichiers";
$trad["FICHIER_description_module"] = "Gestionnaire de fichiers";
// Index.php
$trad["FICHIER_ajouter_fichier"] = "Ajouter des fichiers";
$trad["FICHIER_ajouter_fichier_alert"] = "Dossier du serveur inaccessible en écriture!  merci de contacter l'administrateur";
$trad["FICHIER_telecharger_dossier"] = "Télécharger le dossier";
$trad["FICHIER_telecharger_selection"] = "télécharger la sélection";
$trad["FICHIER_telecharger_dossier_confirm"] = "Confirmer le téléchargement des fichiers ?";
$trad["FICHIER_voir_images"] = "Voir les images";
$trad["FICHIER_defiler_images"] = "Faire défiler automatiquement les images";
$trad["FICHIER_pixels"] = "pixels";
$trad["FICHIER_nb_versions_fichier"] = "versions du fichier"; // n versions du fichier
$trad["FICHIER_ajouter_versions_fichier"] = "Ajouter nouvelle version du fichier";
$trad["FICHIER_apercu"] = "Aperçu"; // n versions du fichier
$trad["FICHIER_aucun_fichier"] = "Aucun fichier pour le moment";
// fichier_edit_ajouter.php  &  Fichier_edit.php
$trad["FICHIER_limite_chaque_fichier"] = "Les fichiers ne doivent pas dépasser"; // ...2 Mega Octets
$trad["FICHIER_optimiser_images"] = "Limiter la taille des images à "; // ..1024 pixels
$trad["FICHIER_maj_nom"] = "Le nom du fichier sera remplacé par celui de la nouvelle version";
$trad["FICHIER_ajout_plupload"] = "Ajout multiple";
$trad["FICHIER_ajout_classique"] = "Ajout classique";
$trad["FICHIER_erreur_nb_fichiers"] = "Merci de sélectionner moins de fichiers";
$trad["FICHIER_erreur_taille_fichier"] = "Fichier trop gros";
$trad["FICHIER_erreur_non_geree"] = "Erreur non gérée";
$trad["FICHIER_ajout_multiple_info"] = "Touche 'Maj' ou 'Ctrl' pour sélectionner plusieurs fichiers";
$trad["FICHIER_select_fichier"] = "Sélectionner les fichiers";
$trad["FICHIER_annuler"] = "Annuler";
$trad["FICHIER_selectionner_fichier"] = "Merci de sélectionner au moins un fichier";
$trad["FICHIER_nouvelle_version"] = "existe déjà, une nouvelle version a été ajoutée.";  // mon_fichier.gif "existe déjà"...
$trad["FICHIER_mail_nouveau_fichier_cree"] = "Nouveaux fichiers créés par";
$trad["FICHIER_mail_fichier_modifie"] = "Fichier modifié par ";
$trad["FICHIER_contenu"] = "contenu";
// Versions_fichier.php
$trad["FICHIER_versions_de"] = "Versions de"; // versions de fichier.gif
$trad["FICHIER_confirmer_suppression_version"] = "Confirmer la suppression de cette version ?";
// Images.php
$trad["FICHIER_info_https_flash"] = "Pour ne plus avoir le message ''Souhaitez-vous afficher les éléments non sécurisés ?'' :<br> <br>> cliquez sur ''Outils'' <br>> cliquez sur ''Options Internet'' <br>> cliquez sur ''Onglet Sécurité'' <br>> Choisir ''Zone Internet'' <br>> Personnaliser le niveau <br>> Activer ''Afficher un contenu mixte'' dans ''Divers''  ";
$trad["FICHIER_img_precedante"] = "Image précédente [flèche gauche]";
$trad["FICHIER_img_suivante"] = "Image suivante [barre espace / flèche droite]";
$trad["FICHIER_rotation_gauche"] = "Rotation à gauche [flèche haut]";
$trad["FICHIER_rotation_droite"] = "Rotation à droite [flèche bas]";
$trad["FICHIER_zoom"] = "Zoomer / Dézoomer";
// Video.php
$trad["FICHIER_voir_videos"] = "Regarder les vidéos";
$trad["FICHIER_regarder"] = "Regarder la vidéo";
$trad["FICHIER_video_precedante"] = "Vidéo précédente";
$trad["FICHIER_video_suivante"] = "Vidéo suivante";
$trad["FICHIER_video_mp4_flv"] = "<a href='http://get.adobe.com/flashplayer'>Flash</a> non-installé.";




////	MODULE_FORUM
////

// Menu principal
$trad["FORUM_nom_module"] = "Forum";
$trad["FORUM_nom_module_header"] = "Forum";
$trad["FORUM_description_module"] = "Forum";
$trad["FORUM_ajout_sujet_admin"] = "Seul l'administrateur peut ajouter des sujets";
$trad["FORUM_ajout_sujet_theme"] = "Tous les utilisateurs peuvent ajouter des thèmes";
// TRI
$trad["tri"]["date_dernier_message"] = "dernier message";
// Index.php & Sujet.php
$trad["FORUM_sujet"] = "sujet";
$trad["FORUM_sujets"] = "sujets";
$trad["FORUM_message"] = "message";
$trad["FORUM_messages"] = "messages";
$trad["FORUM_dernier_message"] = "dernier de";
$trad["FORUM_ajouter_sujet"] = "Ajouter un sujet";
$trad["FORUM_voir_sujet"] = "Voir le sujet";
$trad["FORUM_repondre"] = "Ajouter un message";
$trad["FORUM_repondre_message"] = "Répondre à ce message";
$trad["FORUM_repondre_message_citer"] = "Citer ce message";
$trad["FORUM_aucun_sujet"] = "Pas de sujet pour le moment";
$trad["FORUM_pas_message"] = "Pas de message pour le moment";
$trad["FORUM_aucun_message"] = "Aucun message";
$trad["FORUM_confirme_suppr_message"] = "Confirmer la suppression du message (et sous-messages associées) ?";
$trad["FORUM_retour_liste_sujets"] = "Retour à la liste des sujets";
$trad["FORUM_notifier_dernier_message"] = "Me notifier par mail";
$trad["FORUM_notifier_dernier_message_info"] = "Me prévenir par mail à chaque nouveau message";
// Sujet_edit.php  &  Message_edit.php
$trad["FORUM_infos_droits_acces"] = "Pour participer à la discussion, il faut au moins un accès en ''ecriture limité''";
$trad["FORUM_theme_espaces"] = "Le thème est accessible aux espaces";
$trad["FORUM_mail_nouveau_sujet_cree"] = "Nouveau sujet créé par ";
$trad["FORUM_mail_nouveau_message_cree"] = "Nouveau message créé par ";
// Themes
$trad["FORUM_theme_sujet"] = "Thème";
$trad["FORUM_accueil_forum"] = "Accueil du forum";
$trad["FORUM_sans_theme"] = "Sans thème";
$trad["FORUM_themes_gestion"] = "Gérer les thèmes de sujet";
$trad["FORUM_droit_gestion_themes"] = "Chaque theme peut être modifié par son auteur ou par l'administrateur général";
$trad["FORUM_confirm_suppr_theme"] = "Attention !! Les sujets concernés n'auront plus de thème! Confirmer la suppression ?";




////	MODULE_TACHE
////

// Menu principal
$trad["TACHE_nom_module"] = "Tâches";
$trad["TACHE_nom_module_header"] = "Tâches";
$trad["TACHE_description_module"] = "Tâches";
// TRI
$trad["tri"]["priorite"] = "Priorité";
$trad["tri"]["avancement"] = "Avancement";
$trad["tri"]["date_debut"] = "Date de debut";
$trad["tri"]["date_fin"] = "Date de fin";
// Index.php
$trad["TACHE_ajouter_tache"] = "Ajouter une tâche";
$trad["TACHE_aucune_tache"] = "Aucune tâche pour le moment";
$trad["TACHE_avancement"] = "Avancement";
$trad["TACHE_avancement_moyen"] = "Avancement moyen";
$trad["TACHE_avancement_moyen_pondere"] = "Avancement moyen pondéré sur charge jour/homme";
$trad["TACHE_priorite"] = "Priorité";
$trad["TACHE_priorite1"] = "Basse";
$trad["TACHE_priorite2"] = "Moyenne";
$trad["TACHE_priorite3"] = "Haute";
$trad["TACHE_priorite4"] = "Critique";
$trad["TACHE_responsables"] = "Responsables";
$trad["TACHE_budget_disponible"] = "Budget disponible";
$trad["TACHE_budget_disponible_total"] = "Total budget disponible";
$trad["TACHE_budget_engage"] = "Budget engagé";
$trad["TACHE_budget_engage_total"] = "Total budget engagé";
$trad["TACHE_charge_jour_homme"] = "Charge jours/homme";
$trad["TACHE_charge_jour_homme_total"] = "Total charge jours/homme";
$trad["TACHE_charge_jour_homme_info"] = "Nombre de jours de travail necessaires à une seule personne pour accomplir cette tâche";
$trad["TACHE_avancement_retard"] = "Avancement en retard";
$trad["TACHE_budget_depasse"] = "Budget dépassé";
$trad["TACHE_afficher_tout_gantt"] = "Afficher toutes les tâches";
// tache_edit.php
$trad["TACHE_mail_nouvelle_tache_cree"] = "Nouvelle tâche créée par ";
$trad["TACHE_specifier_date"] = "Merci de spécifier une date";




////	MODULE_CONTACT
////

// Menu principal
$trad["CONTACT_nom_module"] = "Annuaire de contacts";
$trad["CONTACT_nom_module_header"] = "Contacts";
$trad["CONTACT_description_module"] = "Annuaire de contacts";
// Index.php
$trad["CONTACT_ajouter_contact"] = "Ajouter un contact";
$trad["CONTACT_aucun_contact"] = "Aucun contact pour le moment";
$trad["CONTACT_creer_user"] = "Creer un utilisateur sur cet espace";
$trad["CONTACT_creer_user_infos"] = "Créer un utilisateur sur cet espace à partir de ce contact ?";
$trad["CONTACT_creer_user_confirm"] = "L'utilisateur a été créé";
// Contact_edit.php
$trad["CONTACT_mail_nouveau_contact_cree"] = "Nouveau contact créé par ";




////	MODULE_LIEN
////

// Menu principal
$trad["LIEN_nom_module"] = "Favoris";
$trad["LIEN_nom_module_header"] = "Favoris";
$trad["LIEN_description_module"] = "Favoris";
// Index.php
$trad["LIEN_ajouter_lien"] = "Ajouter un lien";
$trad["LIEN_aucun_lien"] = "Aucun lien pour le moment";
// lien_edit.php & dossier_edit.php
$trad["LIEN_adresse"] = "Adresse";
$trad["LIEN_specifier_adresse"] = "Merci de spécifier une adresse";
$trad["LIEN_mail_nouveau_lien_cree"] = "Nouveau lien créé par ";




////	MODULE_MAIL
////

// Menu principal
$trad["MAIL_nom_module"] = "e-mail";
$trad["MAIL_nom_module_header"] = "e-mail";
$trad["MAIL_description_module"] = "Envoyer des e-mails en un clic!";
// Index.php
$trad["MAIL_specifier_mail"] = "Merci de spécifier au moins un destinataire";
$trad["MAIL_titre"] = "Titre de l'e-mail";
$trad["MAIL_no_header_footer"] = "Pas d'entête ni de signature";
$trad["MAIL_no_header_footer_infos"] = "Ne pas inclure le nom de l'expéditeur et le lien vers l'espace";
$trad["MAIL_masquer_dest_message"] = "Masquer les destinataires";
$trad["MAIL_masquer_dest_message_infos"] = "Par défaut, les mails des destinataires sont affichés dans le message. Attention : le mail peut atterir en Spam si l'option est cochée.";
$trad["MAIL_accuse_reception"] = "Demander un accusé de reception";
$trad["MAIL_accuse_reception_infos"] = "Attention! certaines messageries ne prennent pas en compte les demandes d'accusé de réception.";
$trad["MAIL_fichier_joint"] = "Fichier joint";
// Historique Mail
$trad["MAIL_historique_mail"] = "Historique des e-mails envoyés";
$trad["MAIL_aucun_mail"] = "Aucun e-mail";
$trad["MAIL_envoye_par"] = "e-mail envoyé par";
$trad["MAIL_destinataires"] = "Destinataires";
?>