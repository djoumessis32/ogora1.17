<?php
////	PARAMETRAGE
////

// Header http
$trad["HEADER_HTTP"] = "da";
// Editeur Tinymce
$trad["EDITOR"] = "da";
// Dates formatées par PHP
setlocale(LC_TIME, "da_DK.utf8", "da_DK", "da", "danish");




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
		$tab_jours_feries[$date] = "Påskedag";
	}

	////	Les fêtes fixes
	// Jour de l'an
	$tab_jours_feries[$annee."-01-01"] = "Nytår";
	// Noël
	$tab_jours_feries[$annee."-12-24"] = "Juleaften";

	////	Retourne le résultat
	return $tab_jours_feries;
}




////	COMMUN
////

// Divers
$trad["remplir_tous_champs"] = "Venligst udfyld alle felter";
$trad["voir_detail"] = "Se detaljer";
$trad["elem_inaccessible"] = "Utilgængeligt Element";
$trad["champs_obligatoire"] = "Tvunget felt";
$trad["oui"] = "ja";
$trad["non"] = "nej";
$trad["aucun"] = "nej";
$trad["aller_page"] = "Gå til siden";
$trad["alphabet_filtre"] = "Alfabetisk Filter";
$trad["tout"] = "Alle";
$trad["tout_afficher"] = "Vis alle";
$trad["important"] = "Vigtigt";
$trad["afficher"] = "vis";
$trad["masquer"] = "filter";
$trad["deplacer"] = "flyt";
$trad["options"] = "Indstillinger";
$trad["reinitialiser"] = "Opfrisk";
$trad["garder"] = "Gem";
$trad["par_defaut"] = "Som standard";
$trad["localiser_carte"] = "Placer på et kort";
$trad["espace_public"] = "Offentligt skrivebord";
$trad["bienvenue_agora"] = "Velkommen til Agora!";
$trad["mail_pas_valide"] = "E-mail adressen er ikke gyldig";
$trad["element"] = "element";
$trad["elements"] = "elementer";
$trad["dossier"] = "folder";
$trad["dossiers"] = "foldere";
$trad["fermer"] = "Luk";
$trad["imprimer"] = "Print";
$trad["select_couleur"] = "Vælg farven";
$trad["visible_espaces"] = "Skriveborde hvor det vil være synligt";
$trad["visible_ts_espaces"] = "Synligt på alle skriveborde";
$trad["admin_only"] = "Kun Administrator";
$trad["divers"] = "Diverse";
$trad["confirm_close_lightbox"] = "Vil du lukke formularen?";
// images
$trad["photo"] = "Billede";
$trad["fond_ecran"] = "Baggrund";
$trad["image_changer"] = "Udskift";
$trad["pixels"] = "pixels";
// Connexion
$trad["specifier_login_password"] = "Venligst indtast et bruger navn og et kodeord";
$trad["identifiant"] = "Login";
$trad["identifiant_connexion"] = "Login";
$trad["identifiant_ou_email"] = "Email / Login";
$trad["pass"] = "Kodeord";
$trad["pass2"] = "Bekræft kodeord";
$trad["password_verif_alert"] = "Din kodeords validering er fejlet";
$trad["connexion"] = "Forbindelse";
$trad["connexion_auto"] = "Forbliv forbundet";
$trad["connexion_auto_info"] = "Husk mit login og kodeord til automatisk forbindelse";
$trad["password_oublie"] = "Glemt kodeord ?";
$trad["password_oublie_info"] = "Send mit log-in og kodeord til min e-mail adresse (hvis specificeret)";
$trad["acces_invite"] = "Gæste adgang";
$trad["espace_password_erreur"] = "Forkert kodeord";
$trad["version_ie"] = "Din browser er for gammel og understøtter ikke alle HTML standarder : det er tilrådeligt at opdatere din browser eller installere Google Chrome Frame";
// Affichage
$trad["type_affichage"] = "Vis";
$trad["type_affichage_liste"] = "Liste";
$trad["type_affichage_bloc"] = "Blok";
$trad["type_affichage_arbo"] = "Træ";
// Sélectionner / Déselectionner tous les éléments
$trad["select_deselect"] = "Vælg / Fravælg";
$trad["aucun_element_selectionne"] = "Der er ikke valgt noget element";
$trad["tout_selectionner"] = "Vælg alle";
$trad["inverser_selection"] = "Vælg modsat";
$trad["suppr_elements"] = "Fjern de valgte elementer";
$trad["deplacer_elements"] = "Flyt til en anden folder";
$trad["voir_sur_carte"] = "Vis på et kort";
$trad["selectionner_user"] = "Vælg venligst vælg mindst én bruger.";
$trad["selectionner_2users"] = "Vælg venligst mindst to brugere.";
$trad["selectionner_espace"] = "Vælg venligst mindst ét skrivebord.";
// Temps ("de 11h à 12h", "le 25-01-2007 à 10h30", etc.)
$trad["de"] = "af ";
$trad["a"] = "til";
$trad["le"] = "med";
$trad["debut"] = "Begynd";
$trad["fin"] = "Slut";
$trad["separateur_horaire"] = ":";
$trad["jours"] = "dage";
$trad["jour_1"] = "Mandag";
$trad["jour_2"] = "Tirsdag";
$trad["jour_3"] = "Onsdag";
$trad["jour_4"] = "Torsdag";
$trad["jour_5"] = "Fredag";
$trad["jour_6"] = "Lørdag";
$trad["jour_7"] = "Søndag";
$trad["mois_1"] = "januar";
$trad["mois_2"] = "februar";
$trad["mois_3"] = "marts";
$trad["mois_4"] = "april";
$trad["mois_5"] = "maj";
$trad["mois_6"] = "juni";
$trad["mois_7"] = "juli";
$trad["mois_8"] = "august";
$trad["mois_9"] = "september";
$trad["mois_10"] = "oktober";
$trad["mois_11"] = "november";
$trad["mois_12"] = "december";
$trad["mois_suivant"] = "næste måned";
$trad["mois_precedant"] = "forrige måned";
$trad["annee_suivante"] = "næste år";
$trad["annee_precedante"] = "forrige år";
$trad["aujourdhui"] = "Idag";
$trad["aff_aujourdhui"] = "Idag";
$trad["modif_dates_debutfin"] = "Slut datoen kan ikke ligge før start datoen";
// Nom & Description (pour les menus d'édition principalement)
$trad["titre"] = "Titel";
$trad["nom"] = "Navn";
$trad["description"] = "Beskrivelse";
$trad["specifier_titre"] = "Skriv venligst en titel";
$trad["specifier_nom"] = "Skriv venligst et navn";
$trad["specifier_description"] = "Venligst skriv en beskrivelse";
$trad["specifier_titre_description"] = "Skriv venligst en titel eller en beskrivelse";
// Validation des formulaires
$trad["ajouter"] = " Tilføj";
$trad["modifier"] = " Redigér";
$trad["modifier_et_acces"] = "Redigér & definér adgang";
$trad["valider"] = "Gem";
$trad["lancer"] = " Start";
$trad["envoyer"] = "Send";
$trad["envoyer_a"] = "Send til";
// Tri d'affichage. Tous les elements (dossier, tache, lien, etc...) ont par défaut une date, un auteur & une description
$trad["trie_par"] = "Sorteret efter";
$trad["tri"]["date_crea"] = "Oprettelses dato";
$trad["tri"]["date_modif"] = "Ændrings dato";
$trad["tri"]["titre"] = "Tiltale form";
$trad["tri"]["description"] = "Beskrivelse";
$trad["tri"]["id_utilisateur"] = "forfatter";
$trad["tri"]["extension"] = "filtype";
$trad["tri"]["taille_octet"] = "størrelse";
$trad["tri"]["nb_downloads"] = "Hentet";
$trad["tri"]["civilite"] = "Civilstand";
$trad["tri"]["nom"] = "Navn";
$trad["tri"]["prenom"] = "Fornavn";
$trad["tri"]["adresse"] = "Adresse";
$trad["tri"]["codepostal"] = "Postnummer";
$trad["tri"]["ville"] = "By";
$trad["tri"]["pays"] = "Land";
$trad["tri"]["fonction"] = "Titel";
$trad["tri"]["societe_organisme"] = "Firma / Organisation";
$trad["tri_ascendant"] = "Stigende";
$trad["tri_descendant"] = "Faldende";
// Options de suppression
$trad["confirmer"] = "Bekræft ?";
$trad["confirmer_suppr"] = "Bekræft sletning ?";
$trad["confirmer_suppr_bis"] = "Er du sikker ?!";
$trad["confirmer_suppr_dossier"] = "Bekræft sletningen af folderen og alle indeholdte data ? <br><br>Advarsel ! nogen underfoldere er måske ikke synlige for dig : disse vil også blive slettet !! ";
$trad["supprimer"] = "Slet";
// Visibilité d'un Objet : auteur et droits d'accès
$trad["auteur"] = "Forfatter: ";
$trad["cree"] = "Oprettet";  //...12-12-2012
$trad["cree_par"] = "Oprettelse";
$trad["modif_par"] = "Ændret";
$trad["historique_element"] = "Element historik";
$trad["invite"] = "gæst";
$trad["invites"] = "gæster";
$trad["tous"] = "alle";
$trad["inconnu"] = "Ukendt person";
$trad["acces_perso"] = "Personlig adgang";
$trad["lecture"] = "Læse";
$trad["lecture_infos"] = "Læseadgang";
$trad["ecriture_limit"] = "Begrænset skriveadgang";
$trad["ecriture_limit_infos"] = "Begrænset skriveadgang: Mulighed for at tilføje -ELEMENTS-, uden rettighed til at ændre eller slette andres elementer";
$trad["ecriture"] = "Skrive";
$trad["ecriture_infos"] = "Skriveadgang";
$trad["ecriture_infos_conteneur"] = "Adgang til at skrive: Ret til at tilføje, ændre eller slette alle -ELEMENTS- oprettet af -CONTENEUR-";
$trad["ecriture_racine_defaut"] = "Skriveadgang som standard";
$trad["ecriture_auteur_admin"] = "Kun forfatter og administrator kan redigére rettighederne eller slette -CONTENEUR-";
$trad["contenu_dossier"] = "Indhold";
$trad["aucun_acces"] = "Ingen adgang";
$trad["libelles_objets"] = array("element"=>"elementer", "fichier"=>"filer", "tache"=>"opgaver", "lien"=>"links", "contact"=>"kontakter", "evenement"=>"arrangementer", "message"=>"beskeder", "conteneur"=>"kasse", "dossier"=>"folder", "agenda"=>"kalender", "sujet"=>"emne");
// Envoi d'un mail (nouvel utilisateur, notification de création d'objet, etc...)
$trad["mail_envoye_par"] = "Sendt af";  // "Envoyé par" Mr trucmuche
$trad["mail_envoye"] = "E-mailen blev sendt !";
$trad["mail_envoye_notif"] = "Oplysnings e-mailen blev afsendt !";
$trad["mail_pas_envoye"] = "Oplysnings e-mailen kunne ikke afsendes..."; // idem
// Dossier & fichier
$trad["giga_octet"] = "Gb";
$trad["mega_octet"] = "Mb";
$trad["kilo_octet"] = "Kb";
$trad["octet"] = "bytes";
$trad["dossier_racine"] = "Rod folder";
$trad["deplacer_autre_dossier"] = "Flyt til en anden folder";
$trad["ajouter_dossier"] = "Tilføj en folder";
$trad["modifier_dossier"] = "Redigér en folder";
$trad["telecharger"] = "Hent";
$trad["telecharge_nb"] = "Hentet";
$trad["telecharge_nb_bis"] = "gange"; // Téléchargé 'n' fois
$trad["telecharger_dossier"] = "Hent folderen";
$trad["espace_disque_utilise"] = "Disk plads brugt";
$trad["espace_disque_utilise_mod_fichier"] = "Disk plads brugt";
$trad["download_alert"] = "Du kan ikke hente filen i dagtimerne (filstørrelsen er for stor)";
// Infos sur une personne
$trad["civilite"] = "Tiltaleform";
$trad["nom"] = "Navn";
$trad["prenom"] = "Fornavn";
$trad["adresse"] = "Adresse";
$trad["codepostal"] = "Postnummer";
$trad["ville"] = "By";
$trad["pays"] = "Land";
$trad["telephone"] = "Telefon";
$trad["telmobile"] = "Mobil Telefon";
$trad["mail"] = "Email";
$trad["fax"] = "Fax";
$trad["siteweb"] = "Hjemmeside";
$trad["competences"] = "Evner";
$trad["hobbies"] = "Hobbyer";
$trad["fonction"] = "Titel";
$trad["societe_organisme"] = "Firma / Organisation";
$trad["commentaire"] = "Kommentar";
// Rechercher
$trad["preciser_text"] = "Skriv venligst søge ord på minimum 3 karakterer";
$trad["rechercher"] = "Søg";
$trad["rechercher_date_crea"] = "Oprettelses dato";
$trad["rechercher_date_crea_jour"] = "mindre end én dag";
$trad["rechercher_date_crea_semaine"] = "mindre end én uge";
$trad["rechercher_date_crea_mois"] = "mindre end én måned";
$trad["rechercher_date_crea_annee"] = "mindre end ét år";
$trad["rechercher_espace"] = "Søg i dette skrivebord";
$trad["recherche_avancee"] =  "Avanceret søgning";
$trad["recherche_avancee_mots_certains"] =  "vilkårligt ord";
$trad["recherche_avancee_mots_tous"] =  "alle ord";
$trad["recherche_avancee_expression_exacte"] =  "præcis frase";
$trad["recherche_avancee_champs"] =  "søge felter";
$trad["recherche_avancee_pas_concordance"] =  "Moduler og valgte felter stemmer ikke overens. Undersøg venligst uoverenstemmelserne i avanceret søgning.";
$trad["mots_cles"] = "Søge ord";
$trad["liste_modules"] = "Moduler";
$trad["liste_champs"] = "Felter";
$trad["liste_champs_elements"] = "Elementer involveret";
// Importer / Exporter des contact
$trad["exporter"] = "Exporter";
$trad["importer"] = "Importer";
$trad["export_import_users"] = "brugere";
$trad["export_import_contacts"] = "kontakter";
$trad["export_format"] = "format";
$trad["contact_separateur"] = "separator";
$trad["contact_delimiteur"] = "begrænser";
$trad["specifier_fichier"] = "Venligst specificer en fil";
$trad["extension_fichier"] = "Fil typen er forkert. Den skal være af typen";
$trad["format_fichier_invalide"] = "Filformatet passer ikke sammen med den valgte type";
$trad["import_infos"] = "Vælg Agora's felter som mål, med dropdown menuen ud for hver kolonne";
$trad["import_infos_contact"] = "De importerede kontakter bliver tilknyttet det aktive skrivebord som standard.";
$trad["import_infos_user"] = "Hvis bruger navn og kodeord ikke er valgt på forhånd, så vil de blive genereret automatisk.";
$trad["import_alert"] = "Vælg venligst mindst for og efternavns kolonnerne.";
$trad["import_alert2"] = "Vælg venligst mindst én kontakt at importere";
$trad["import_alert3"] = "dette agora felt er allerede blevet valgt i en anden kolonne (Hvert af agora's felter kan kun vælges én gang)";
// Captcha
$trad["captcha"] = "Visuel identifikation";
$trad["captcha_info"] = "Skriv venligst de 4 karakterer, for at identificere dig";
$trad["captcha_alert_specifier"] = "Udfyld venligst den visuelle identifikation";
$trad["captcha_alert_erronee"] = "Den visuelle identifikation fejlede, prøv igen.";
// Gestion des inscriptions d'utilisateur
$trad["inscription_users"] = "Registrer dig som bruger";
$trad["inscription_users_info"] = "Opret en ny bruger konto (valideret af en administrator)";
$trad["inscription_users_espace"] = "at abonnere på plads";
$trad["inscription_users_enregistre"] = "Din tilmelding er blevet registreret : Den vil blive bekræftet af en administrator hurtigst muligt.";
$trad["inscription_users_option_espace"] = "Tillad nye brugere at registrere sig på skrivebordet";
$trad["inscription_users_option_espace_info"] = "Registreringen foregår på forsiden. Regsistreringen skal derefter godkendes af en administrator.";
$trad["inscription_users_validation"] = "Godkend nye brugere";
$trad["inscription_users_valider"] = "Godkend";
$trad["inscription_users_invalider"] = "Afvis godkendelse";
$trad["inscription_users_valider_mail"] = "Din konto er blevet godkendt til ";
$trad["inscription_users_invalider_mail"] = "Din konto er IKKE blevet godkendt til";
// Connexion à un serveur LDAP
$trad["ldap_connexion_serveur"] = "Tilslutning til en LDAP-server";
$trad["ldap_server"] = "serveradresse";
$trad["ldap_server_port"] = "Port-server";
$trad["ldap_server_port_infos"] = "''389'' som standard";
$trad["ldap_admin_login"] = "String Tilslutning til admin";
$trad["ldap_admin_login_infos"] = "for eksempel ''uid=admin,ou=my_company''";
$trad["ldap_admin_pass"] = "Password af admin";
$trad["ldap_groupe_dn"] = "Gruppe / base DN";
$trad["ldap_groupe_dn_infos"] = "Placering af Directory-brugere.<br> for eksempel ''ou=users,o=my_company''";
$trad["ldap_connexion_erreur"] = "Fejl ved forbindelse til LDAP-server !";
$trad["ldap_import_infos"] = "Vis konfigurationen af ​​LDAP-serveren i modulet Administration.";
$trad["ldap_crea_auto_users"] = "Automatisk oprettelse af brugere LDAP";
$trad["ldap_crea_auto_users_infos"] = "Automatisk oprette en bruger, hvis det mangler i Agora men til stede på LDAP-serveren: det vil blive tildelt til områder tilgængelige for alle brugere sitet.<br>Ellers brugeren, vil ikke blive oprettet.";
$trad["ldap_pass_cryptage"] = "Kryptering af passwords på LDAP-serveren";
$trad["ldap_effacer_params"] = "Slet LDAP indstilling?";
$trad["ldap_pas_module_php"] = "PHP-modul for tilslutning til en LDAP-server er ikke installeret!";




////	DIVERS
////

// Messages d'alert ou d'erreur
$trad["MSG_ALERTE_identification"] = "Ugyldigt bruger navn eller kodeord";
$trad["MSG_ALERTE_dejapresent"] = "Kontoen bliver lige nu anvendt via en anden IP adresse...(Hver konto kan kun anvendes af én person ad gangen)";
$trad["MSG_ALERTE_adresseip"] = "Den IP adresse du anvender lige nu er ikke godkendt til brug med den bruger konto du bruger.";
$trad["MSG_ALERTE_pasaccesite"] = "Du har ikke adgang til siderne, sandsynligvis fordi du ikke er tilknyttet et eksisterende skrivebord.";
$trad["MSG_ALERTE_captcha"] = "Den visuelle identifikation er forkert.";
$trad["MSG_ALERTE_acces_fichier"] = "Filen kan ikke findes";
$trad["MSG_ALERTE_acces_dossier"] = "Folderen kan ikke findes";
$trad["MSG_ALERTE_espace_disque"] = "Pladsen til at gemme filer er fyldt op, du kan ikke tilføje fil(er).";
$trad["MSG_ALERTE_type_interdit"] = "Filtypen er ikke tilladt";
$trad["MSG_ALERTE_taille_fichier"] = "Størrelsen på filen er for stor";
$trad["MSG_ALERTE_type_version"] = "Filtypen adskiller sig fra originalen.";
$trad["MSG_ALERTE_deplacement_dossier"] = "Du kan ikke flytte en folder ind!";
$trad["MSG_ALERTE_nom_dossier"] = "En folder med det navn findes allerede. Godkend alligevel?";
$trad["MSG_ALERTE_nom_fichier"] = "En fil med samme navn eksisterer allerede, men er ikke blevet overskrevet.";
$trad["MSG_ALERTE_chmod_stock_fichiers"] = "Fil håndteringen har ikke skriveadgang!!!. Giv folderen ''stock_fichiers '' rettigheder med CHMOD 775 (Læse-skrive rettigheder til ejer og gruppen)";
$trad["MSG_ALERTE_nb_users"] = "Du kan ikke tilføje en ny bruger, der er en grænse på "; // "...limité à" 10
$trad["MSG_ALERTE_miseajourconfig"] = "Konfigurations filen (config.inc.php) er ikke skrivbar: Opdatering af ændringer kan ikke fortsætte!";
$trad["MSG_ALERTE_miseajour"] = "Opdatering fuldført. Det anbefales at genstarte din browser før du logger på igen.";
$trad["MSG_ALERTE_user_existdeja"] = "Brugernavn eksisterer allerede : Brugeren er ikke oprettet";
$trad["MSG_ALERTE_temps_session"] = "Din session er udløbet, log på igen.";
$trad["MSG_ALERTE_specifier_nombre"] = "Vælg et nummer";
// header menu
$trad["HEADER_MENU_espace_administration"] = "Administration";
$trad["HEADER_MENU_espaces_dispo"] = "Tilgængelige skriveborde";
$trad["HEADER_MENU_espace_acces_administration"] = "(administrations adgang)";
$trad["HEADER_MENU_affichage_elem"] = "Vis";
$trad["HEADER_MENU_affichage_normal"] = "Elementer som er tildelt mig.";
$trad["HEADER_MENU_affichage_normal_infos"] = "Dette er standard visningen";
$trad["HEADER_MENU_affichage_auteur"] = "Elementer som jeg har oprettet.";
$trad["HEADER_MENU_affichage_auteur_infos"] = "Vis kun de elementer jeg har oprettet.";
$trad["HEADER_MENU_affichage_tout"] = "Alle elementer på skrivebordet (admin)";
$trad["HEADER_MENU_affichage_tout_infos"] = "Til administratorer af skrivebordet : anvendes til at vise alle elementer på et skrivebord, selv dem som ikke er tildelt til administratoren!";
$trad["HEADER_MENU_recherche_elem"] = "Søg et element på skrivebordet.";
$trad["HEADER_MENU_sortie_agora"] = "Log-ud fra Agora";
$trad["HEADER_MENU_raccourcis"] = "Genveje";
$trad["HEADER_MENU_seul_utilisateur_connecte"] = "Lige nu alene på siderne";
$trad["HEADER_MENU_en_ligne"] = "Forbundet";
$trad["HEADER_MENU_connecte_a"] = "forbundet til siderne kl.";   // Mr bidule truc "connecté au site à" 12:45
$trad["HEADER_MENU_messenger"] = "Chat";
$trad["HEADER_MENU_envoye_a"] = "Sendt til";
$trad["HEADER_MENU_ajouter_message"] = "Tilføj besked";
$trad["HEADER_MENU_specifier_message"] = "Skriv en besked";
$trad["HEADER_MENU_enregistrer_conversation"] = "Optag denne chat";
// Footer
$trad["FOOTER_page_generee"] = "side genereret på";
// Password_oublie
$trad["PASS_OUBLIE_preciser_mail"] = "Skriv din e-mail adresse for at modtage dit brugernavn og kodeord.";
$trad["PASS_OUBLIE_mail_inexistant"] = "Den angivne e-mail findes ikke i databasen.";
$trad["PASS_OUBLIE_mail_objet"] = "Forbinder til dit skrivebord";
$trad["PASS_OUBLIE_mail_contenu"] = "Dit brugernavn";
$trad["PASS_OUBLIE_mail_contenu_bis"] = "Klik her for at nulstille dit kodeord";
$trad["PASS_OUBLIE_prompt_changer_pass"] = "Venligst skriv dit nye kodeord.";
$trad["PASS_OUBLIE_id_newpassword_expire"] = "Linket til nulstilling af kodeord er udløbet.. start proceduren forfra.";
$trad["PASS_OUBLIE_password_reinitialise"] = "Dit nye kodeord er registreret !";
$trad["aucun_resultat"] = "Intet resultat";
// menu_edit_objet
$trad["EDIT_OBJET_alert_aucune_selection"] = "Du skal vælge mindst én person eller ét skrivebord";
$trad["EDIT_OBJET_alert_pas_acces_perso"] = "Du er ikke tildelt til elementet. Gem alligevel? ";
$trad["EDIT_OBJET_alert_ecriture"] = "Der skal være mindst en person eller et skrivebord med skriverettigheder";
$trad["EDIT_OBJET_alert_ecriture_limite_defaut"] = "Advarsel! Med skriverettigheder kan alle beskeder slettes! \\n\\nDet anbefales at bruge begrænsede skriverettigheder";
$trad["EDIT_OBJET_alert_invite"] = "Skriv venligst et navn";
$trad["EDIT_OBJET_droit_acces"] = "Adgangs rettigheder";
$trad["EDIT_OBJET_espace_pas_module"] = "Det nuværende modul er endnu ikke tilføjet til dette skrivebord";
$trad["EDIT_OBJET_tous_utilisateurs"] = "Alle brugerne";
$trad["EDIT_OBJET_tous_utilisateurs_espaces"] = "Alle skriveborde";
$trad["EDIT_OBJET_espace_invites"] = "Gæster af dette offentlige skrivebord";
$trad["EDIT_OBJET_aucun_users"] = "Endnu ingen brugere på dette skrivebord";
$trad["EDIT_OBJET_invite"] = "Dit Navn";
$trad["EDIT_OBJET_admin_espace"] = "Administrator for dette skrivebord: Skriveadgang til alle elementer på dette skrivebord";
$trad["EDIT_OBJET_tous_espaces"] = "Vis alle mine skriveborde";
$trad["EDIT_OBJET_notif_mail"] = "Underet via e-mail";
$trad["EDIT_OBJET_notif_mail_joindre_fichiers"] = "Vedhæft filer til anmeldelsen";
$trad["EDIT_OBJET_notif_mail_info"] = "Send en underetning via e-mail til de brugere der vil få adgang til dette element.";
$trad["EDIT_OBJET_notif_mail_selection"] = "Vælg manuelt, modtagere af underetningerne.";
$trad["EDIT_OBJET_notif_tous_users"] = "Vis flere brugere";
$trad["EDIT_OBJET_droits_ss_dossiers"] = "Tildel de samme adgangs rettigheder til underfoldere";
$trad["EDIT_OBJET_raccourci"] = "Genvej";
$trad["EDIT_OBJET_raccourci_info"] = "Sæt en genvej på hovedmenuen";
$trad["EDIT_OBJET_fichier_joint"] = "Tilføj billeder,video eller andre filer";
$trad["EDIT_OBJET_inserer_fichier"] = "Vis i beskrivelsen";
$trad["EDIT_OBJET_inserer_fichier_info"] = "Vis billede / video / mp3 afspiller ... i beskrivelsen herover. Indsættelsen sker efter form validering.";
$trad["EDIT_OBJET_inserer_fichier_alert"] = "Klik på ''Indsæt'' for at tilføje et billede til teksten eller beskrivelsen.";
$trad["EDIT_OBJET_demande_a_confirmer"] = "Din forspørgsel er blevet registreret, den vil snart blive bekræftet.";
// Formulaire d'installation
$trad["INSTALL_connexion_bdd"] = "Forbindelse til databasen";
$trad["INSTALL_db_host"] = "Hostname på database serveren";
$trad["INSTALL_db_name"] = "Database navn";
$trad["INSTALL_db_name_info"] = "Advarsel!!! <br> Hvis der allerede eksisterer en Agora database, så vil den blive overskrevet.";
$trad["INSTALL_db_login"] = "Brugernavn";
$trad["INSTALL_db_password"] = "Kodeord";
$trad["INSTALL_login_password_info"] = "For at logge på som en hoved administrator";
$trad["INSTALL_config_admin"] = "Information om administratoren af ";
$trad["INSTALL_config_espace"] = "Konfiguration af hoved skrivebordet";
$trad["INSTALL_erreur_acces_mysql"] = "No connection to the MySQL database";
$trad["INSTALL_erreur_acces_bdd"] = "Forbindelsen til databasen er ikke etableret, vil du stadig bekræfte?";
$trad["INSTALL_erreur_agora_existe"] = "Agora databasen eksisterer allerede! Bekræft installation og overskrivning af tabeller?";
$trad["INSTALL_confirm_version_php"] = "Agora-Project kræver minimum version 4.3 af PHP, vil du stadig bekræfte ?";
$trad["INSTALL_confirm_version_mysql"] = "Agora-Project kræver minimum version 4.2 of MySQL, vil du stadig bekræfte ?";
$trad["INSTALL_confirm_install"] = "Bekræft installationen ?";
$trad["INSTALL_install_ok"] = "Agora-Project blev installeret! Af sikkerhedsgrunde bør du overvejde at fjerne 'install' folderen inden du tager den i brug.";




////	MODULE_PARAMETRAGE
////

// Menu principal
$trad["PARAMETRAGE_nom_module"] = "Indstillinger";
$trad["PARAMETRAGE_nom_module_header"] = "Indstillinger";
$trad["PARAMETRAGE_description_module"] = "Side indstillinger";
// Index.php
$trad["PARAMETRAGE_sav"] = "Lav backup af database og filer.";
$trad["PARAMETRAGE_sav_alert"] = "Oprettelse af backuppen kan tage nogen minutter... og download af samme backup kan tage lang tid.";
$trad["PARAMETRAGE_sav_bdd"] = "Lav backup af databasen.";
$trad["PARAMETRAGE_adresse_web_invalide"] = "Beklager men web adressen er ugyldig: den skal starte med Http:// ";
$trad["PARAMETRAGE_espace_disque_invalide"] = "Diskplads grænsen skal indeholde alt.";
$trad["PARAMETRAGE_confirmez_modification_site"] = "Bekræft ændringer ?";
$trad["PARAMETRAGE_nom_site"] = "Side navn";
$trad["PARAMETRAGE_adresse_web"] = "Side adresse";
$trad["PARAMETRAGE_footer_html"] = "Fodnote html";
$trad["PARAMETRAGE_footer_html_info"] = "Til brug for tæller og lignende.";
$trad["PARAMETRAGE_langues"] = "Standard sprog";
$trad["PARAMETRAGE_timezone"] = "Tidszone";
$trad["PARAMETRAGE_nom_espace"] = "Navn på standard skrivebord";
$trad["PARAMETRAGE_limite_espace_disque"] = "Plads tilgængelig til opbevaring af filer.";
$trad["PARAMETRAGE_logs_jours_conservation"] = "Opbevaring af logs";
$trad["PARAMETRAGE_mode_edition"] = "Redigér elementer";
$trad["PARAMETRAGE_edition_popup"] = "i en popup";
$trad["PARAMETRAGE_edition_iframe"] = "i en frame";
$trad["PARAMETRAGE_skin"] = "Farven på skrivebordets elementer (baggrund på elementer, menuer, osv.)";
$trad["PARAMETRAGE_noir"] = "Sort";
$trad["PARAMETRAGE_blanc"] = "Hvid";
$trad["PARAMETRAGE_erreur_fond_ecran_logo"] = "Baggrunde og logo skal have .jpg eller .png fil extention";
$trad["PARAMETRAGE_suppr_fond_ecran"] = "Slet baggrunden ?";
$trad["PARAMETRAGE_logo_footer"] = "Logo i bunden af hver enkelt side";
$trad["PARAMETRAGE_logo_footer_url"] = "URL";
$trad["PARAMETRAGE_editeur_text_mode"] = "Tekst editor's mode (TinyMCE)";
$trad["PARAMETRAGE_editeur_text_minimal"] = "Minimal";
$trad["PARAMETRAGE_editeur_text_complet"] = "Fuld (+ tabeller + medier + paste fra word)";
$trad["PARAMETRAGE_messenger_desactive"] = "Chat aktiveret";
$trad["PARAMETRAGE_agenda_perso_desactive"] = "Personlige kalendere aktiveret som standard";
$trad["PARAMETRAGE_agenda_perso_desactive_infos"] = "Tilføj en personlig kalender på oprettelse af en bruger. Kalenderen kan dog deaktiveres senere, når du ændrer brugerkonto.";
$trad["PARAMETRAGE_libelle_module"] = "Modulets navn i hovedmenuen";
$trad["PARAMETRAGE_libelle_module_masquer"] = "Gem";
$trad["PARAMETRAGE_libelle_module_icones"] = "over hvert moduls ikon";
$trad["PARAMETRAGE_libelle_module_page"] = "Kun navnet på det valgte modul";
$trad["PARAMETRAGE_tri_personnes"] = "Sortér brugere og kontakter";
$trad["PARAMETRAGE_versions"] = "Versioner";
$trad["PARAMETRAGE_version_agora_maj"] = "Opdateret";
$trad["PARAMETRAGE_fonction_mail_desactive"] = "PHP funktion til at sende e-mail : deaktiveret !";
$trad["PARAMETRAGE_fonction_mail_infos"] = "Nogen webhosts deaktiverer PHP funktionen som anvendes til at sende mails på grund af f.eks. SPAM.";
$trad["PARAMETRAGE_fonction_image_desactive"] = "Funktionen der skal håndtere billeder og eller småbillederne (PHP GD2) : deaktiveret !";




////	MODULE_LOG
////

// Menu principal
$trad["LOGS_nom_module"] = "Logs";
$trad["LOGS_nom_module_header"] = "Logs";
$trad["LOGS_description_module"] = "Logs - Event Log";
// Index.php
$trad["LOGS_filtre"] = "filter";
$trad["LOGS_date_heure"] = "Dato / Tid";
$trad["LOGS_espace"] = "Skrivebord";
$trad["LOGS_module"] = "modul";
$trad["LOGS_action"] = "Aktion";
$trad["LOGS_utilisateur"] = "Bruger";
$trad["LOGS_adresse_ip"] = "IP";
$trad["LOGS_commentaire"] = "Kommentar";
$trad["LOGS_no_logs"] = "ingen log";
$trad["LOGS_filtre_a_partir"] = "filtreret fra";
$trad["LOGS_chercher"] = "søg";
$trad["LOGS_chargement"] = "Henter data";
$trad["LOGS_connexion"] = "forbindelse";
$trad["LOGS_deconnexion"] = "log-ud";
$trad["LOGS_consult"] = "spørg";
$trad["LOGS_consult2"] = "hent";
$trad["LOGS_ajout"] = "tilføj";
$trad["LOGS_suppr"] = "slet";
$trad["LOGS_modif"] = "redigér";




////	MODULE_ESPACE
////

// Menu principal
$trad["ESPACES_nom_module"] = "Skriveborde";
$trad["ESPACES_nom_module_header"] = "Skriveborde";
$trad["ESPACES_description_module"] = "Hoved skrivebord";
$trad["ESPACES_description_module_infos"] = "Siden (eller hoved skrivebord) kan deles ind i flere skriveborde";
// Header_menu.inc.php
$trad["ESPACES_gerer_espaces"] = "Håndtér skriveborde";
$trad["ESPACES_parametrage"] = "Skrivebords indstillinger";
$trad["ESPACES_parametrage_infos"] = "Skrivebords indstillinger (beskrivelse, moduler, brugere osv.)";
// Index.php
$trad["ESPACES_confirm_suppr_espace"] = "Confirm the suppression ? Attention, the affected data only with this space will be definitively lost !!";
$trad["ESPACES_espace"] = "skrivebord";
$trad["ESPACES_espaces"] = "skriveborde";
$trad["ESPACES_definir_acces"] = "Definér !";
$trad["ESPACES_modules"] = "Moduler";
$trad["ESPACES_ajouter_espace"] = "Tilføj et skrivebord";
$trad["ESPACES_supprimer_espace"] = "Slet skrivebordet";
$trad["ESPACES_aucun_espace"] = "Endnu ikke noget skrivebord";
$trad["MSG_ALERTE_suppr_espace_impossible"] = "Du kan ikke slette det nuværende skrivebord";
// Espace_edit.php
$trad["ESPACES_gestion_acces"] = "Brugere tilknyttet skrivebordet";
$trad["ESPACES_selectionner_module"] = "Du skal vælge mindst ét modul";
$trad["ESPACES_modules_espace"] = "Moduler tilknyttet skrivebordet";
$trad["ESPACES_modules_classement"] = "Flyt for at indstille rækkefølge af moduler";
$trad["ESPACES_selectionner_utilisateur"] = "Vælg nogle brugere, alle brugere eller åbn skrivebordet for alle, også offentligheden.";
$trad["ESPACES_espace_public"] = "Offentligt skrivebord";
$trad["ESPACES_public_infos"] = "Giver adgang til brugere der ikke har konti på siderne : gæster. Mulighed for at kodeordsbeskytte adgangen.";
$trad["ESPACES_invitations_users"] = "Brugere kan sende invitationer via mail";
$trad["ESPACES_invitations_users_infos"] = "Alle brugere kan sende e-mail invitationer til at deltage i rummet";
$trad["ESPACES_tous_utilisateurs"] = "Alle brugerne på siderne";
$trad["ESPACES_utilisation"] = "Bruger";
$trad["ESPACES_utilisation_info"] = "Bruger af skrivebord : <br> Normal adgang til skrivebordet.";
$trad["ESPACES_administration"] = "Administrator";
$trad["ESPACES_administration_info"] = "Administrator af skrivebord : Skriveadgang til alle elementer på skrivebordet + mulighed for at sende e-mail invitationer + mulighed for at tilføje brugere";
$trad["ESPACES_creer_agenda_espace"] = "Opret en kalender til rummet";
$trad["ESPACES_creer_agenda_espace_info"] = "Dette kan være nyttigt, hvis kalenderne for brugerne er deaktiveret.<br>Kalenderen vil have det samme navn end den plads, og dette vil være en ressource kalender.";




////	MODULE_UTILISATEUR
////

// Menu principal
$trad["UTILISATEURS_nom_module"] = "Bruger";
$trad["UTILISATEURS_nom_module_header"] = "Bruger";
$trad["UTILISATEURS_description_module"] = "Bruger";
$trad["UTILISATEURS_ajout_utilisateurs_groupe"] = "Brugere kan også oprette grupper";
// Index.php
$trad["UTILISATEURS_utilisateurs_site"] = "Brugere på siderne";
$trad["UTILISATEURS_gerer_utilisateurs_site"] = "Håndtér alle brugere";
$trad["UTILISATEURS_utilisateurs_site_infos"] = "Alle brugere fra siderne, og fra alle skriveborde";
$trad["UTILISATEURS_utilisateurs_espace"] = "Brugere af skrivebordet";
$trad["UTILISATEURS_confirm_suppr_utilisateur"] = "Bekræft sletning af brugeren? Advarsel ! Alle data relateret til denne bruger vil blive slettet!!";
$trad["UTILISATEURS_confirm_desaffecter_utilisateur"] = "Bekræft frakobling med det nuværende skrivebord ?";
$trad["UTILISATEURS_suppr_definitivement"] = "Slet for altid";
$trad["UTILISATEURS_desaffecter"] = "Frakobel skrivebordet";
$trad["UTILISATEURS_tous_user_affecte_espace"] = "Alle sidens brugere er tilkoblet dette skrivebord: du kan ikke frakoble nogen brugere";
$trad["UTILISATEURS_utilisateur"] = "bruger";
$trad["UTILISATEURS_utilisateurs"] = "brugere";
$trad["UTILISATEURS_affecter_utilisateur"] = "Tilføj en eksisterende bruger til dette skrivebord";
$trad["UTILISATEURS_ajouter_utilisateur"] = "Tilføj bruger";
$trad["UTILISATEURS_ajouter_utilisateur_site"] = "Tilføj en bruger til siden: Som standard tilknyttet til alle skriveborde!";
$trad["UTILISATEURS_ajouter_utilisateur_espace"] = "Tilføj en bruger til det nuværende skrivebord";
$trad["UTILISATEURS_envoi_coordonnees"] = "Send log-in og kodeord";
$trad["UTILISATEURS_envoi_coordonnees_infos"] = "Send en e-mail til brugeren med log-in <br> og deres nye kodeord";
$trad["UTILISATEURS_envoi_coordonnees_infos2"] = "Send en mail til nye brugere deres brugernavn og password";
$trad["UTILISATEURS_envoi_coordonnees_confirm"] = "Kodeord vil blive fornyet ! Fortsæt? ";
$trad["UTILISATEURS_mail_coordonnees"] = "Log-in og kodeord";
$trad["UTILISATEURS_aucun_utilisateur"] = "Ingen bruger tilknyttet dette skrivebord lige nu";
$trad["UTILISATEURS_derniere_connexion"] = "Sidste forbindelse";
$trad["UTILISATEURS_liste_espaces"] = "Brugerens skriveborde";
$trad["UTILISATEURS_aucun_espace"] = "Ingen skriveborde";
$trad["UTILISATEURS_admin_general"] = "Hoved administrator af siden";
$trad["UTILISATEURS_admin_espace"] = "Administrator for skrivebordet";
$trad["UTILISATEURS_user_espace"] = "Bruger af skrivebordet";
$trad["UTILISATEURS_user_site"] = "Bruger af siden";
$trad["UTILISATEURS_pas_connecte"] = "Ikke forbundet endnu";
$trad["UTILISATEURS_modifier"] = "Redigér bruger";
$trad["UTILISATEURS_modifier_mon_profil"] = "Rediér min profil";
$trad["UTILISATEURS_pas_suppr_dernier_admin_ge"] = "Du kan ikke slette den sidste hoved administrator på siden !";
// Invitation.php
$trad["UTILISATEURS_envoi_invitation"] = "Inviter andre til at deltage på skrivebordet";
$trad["UTILISATEURS_envoi_invitation_info"] = "Invitationen vil blive sendt med e-mail";
$trad["UTILISATEURS_objet_mail_invitation"] = "Invitation af "; // ..Jean DUPOND
$trad["UTILISATEURS_admin_invite_espace"] = "inviterer dig tilat deltage "; // Jean DUPOND "vous invite à rejoindre l'espace" Mon Espace
$trad["UTILISATEURS_confirmer_invitation"] = "Klik her for at acceptere invitationen";
$trad["UTILISATEURS_invitation_a_confirmer"] = "Invitationer er endnu ikke bekræftet";
$trad["UTILISATEURS_id_invitation_expire"] = "Linket i invitationen er forældet...";
$trad["UTILISATEURS_invitation_confirmer_password"] = "Bekræft venligst dit kodeord inden du bekræfter din invitation";
$trad["UTILISATEURS_invitation_valide"] = "Din invitation er belevet bekræftet !";
// groupes.php
$trad["UTILISATEURS_groupe_espace"] = "Grupper af brugere af skrivebordet";
$trad["UTILISATEURS_groupe_site"] = "alle grupper af brugere";
$trad["UTILISATEURS_groupe_infos"] = "redigér gruppen af brugere";
$trad["UTILISATEURS_groupe_espace_infos"] = "De aktive brugere har adgang til alle de valgte skriveborde (de andre skriveborde er deaktiveret)";
$trad["UTILISATEURS_droit_gestion_groupes"] = "Hver gruppe kan redigeres af dens forfatter eller en hoved administrator";
// Utilisateur_affecter.php
$trad["UTILISATEURS_preciser_recherche"] = "Skriv venligst et navn, et fornavn eller en e-mail adresse";
$trad["UTILISATEURS_affecter_user_confirm"] = "Bekræft brugerens tilknytning til skrivebordet?";
$trad["UTILISATEURS_rechercher_user"] = "Søg en bruger for at tilknytte vedkommende til et skrivebord";
$trad["UTILISATEURS_tous_users_affectes"] = "Alle brugerne på siden er allerede tilknyttet dette skrivebord";
$trad["UTILISATEURS_affecter_user"] = "Tilknyt en bruger til dette skrivebord :";
$trad["UTILISATEURS_aucun_users_recherche"] = "Ingen brugere fundet";
// Utilisateur_edit.php & CO
$trad["UTILISATEURS_specifier_nom"] = "Skriv dit navn";
$trad["UTILISATEURS_specifier_prenom"] = "Skriv dit fornavn";
$trad["UTILISATEURS_specifier_identifiant"] = "Skriv et login";
$trad["UTILISATEURS_specifier_pass"] = "Skriv et kodeord";
$trad["UTILISATEURS_pas_fichier_photo"] = "Du har ikke tilknyttet et billede !";
$trad["UTILISATEURS_langues"] = "Sprog";
$trad["UTILISATEURS_agenda_perso_active"] = "Personlig kalender aktiveret";
$trad["UTILISATEURS_agenda_perso_active_infos"] = "Hvis aktiveret, så er den personlige kalender <u>altid</u> synlig for brugeren, selv om kalender modulet ikke er aktiveret for skrivebordet";
$trad["UTILISATEURS_espace_connexion"] = "Hvilket skrivebord bliver vist efter log-in";
$trad["UTILISATEURS_notification_mail"] = "Send en bekræftigelses e-mail";
$trad["UTILISATEURS_alert_notification_mail"] = "Venligst angiv en e-mail adresse!";
$trad["UTILISATEURS_adresses_ip"] = "Liste med kontrol IP adresser";
$trad["UTILISATEURS_info_adresse_ip"] = "Når du angiver en eller flere kontrol IP adresser, så kan brugeren kun forbinde sig til skrivebordet når der anvendes en computer med den angivne kontrol IP adresse";
$trad["UTILISATEURS_ip_invalide"] = "Forkert IP adresse";
$trad["UTILISATEURS_identifiant_deja_present"] = "Det angivne log-in er allerede i brug, vælg venligst et andet";
$trad["UTILISATEURS_mail_deja_present"] = "E-mail adressen er allerede i brug, brug venligst en anden e-mail adresse";
$trad["UTILISATEURS_mail_objet_nouvel_utilisateur"] = "Ny konto på";  // "...sur" l'Agora machintruc
$trad["UTILISATEURS_mail_nouvel_utilisateur"] = "En ny konto blev oprettet til dig på";  // idem
$trad["UTILISATEURS_mail_infos_connexion"] = "Log-in med det følgende log-in og kodeord";
$trad["UTILISATEURS_mail_infos_connexion2"] = "Tak til arkivere denne e-mail.";
// Utilisateur_Messenger.php
$trad["UTILISATEURS_gestion_messenger_livecounter"] = "Håndtér chatten";
$trad["UTILISATEURS_visibilite_messenger_livecounter"] = "Brugere som kan se om jeg er online";
$trad["UTILISATEURS_aucun_utilisateur_messenger"] = "Ingen brugere lige nu";
$trad["UTILISATEURS_voir_aucun_utilisateur"] = "Ingen bruger kan se mig";
$trad["UTILISATEURS_voir_tous_utilisateur"] = "Alle brugere kan se mig";
$trad["UTILISATEURS_voir_certains_utilisateur"] = "Kun visse brugere kan se mig";




////	MODULE_TABLEAU BORD
////

// Menu principal
$trad["TABLEAU_BORD_nom_module"] = "Nyheder";
$trad["TABLEAU_BORD_nom_module_header"] = "Nyheder";
$trad["TABLEAU_BORD_description_module"] = "Nyheder";
$trad["TABLEAU_BORD_ajout_actualite_admin"] = "Kun administrator kan tilføje nyheder";
// Index.php
$trad["TABLEAU_BORD_new_elems"] = "Nyt element";
$trad["TABLEAU_BORD_new_elems_bulle"] = "Nye elementer oprettet i den valgte periode";
$trad["TABLEAU_BORD_new_elems_realises"] = "Nuværende elementer";
$trad["TABLEAU_BORD_new_elems_realises_bulle"] = "Aktiviteter og Opgaver <br>idag";
$trad["TABLEAU_BORD_plugin_connexion"] = "siden sidste log-in";
$trad["TABLEAU_BORD_plugin_jour"] = "idag";
$trad["TABLEAU_BORD_plugin_semaine"] = "denne uge";
$trad["TABLEAU_BORD_plugin_mois"] = "denne måned";
$trad["TABLEAU_BORD_autre_periode"] = "En anden periode";
$trad["TABLEAU_BORD_pas_nouveaux_elements"] = "Ingen elementer i den valget periode";
$trad["TABLEAU_BORD_actualites"] = "Nyheder";
$trad["TABLEAU_BORD_actualite"] = "nyt";
$trad["TABLEAU_BORD_actualites"] = "nyheder";
$trad["TABLEAU_BORD_ajout_actualite"] = "Tilføj en nyhed";
$trad["TABLEAU_BORD_actualites_offline"] = "Arkiverede nyheder";
$trad["TABLEAU_BORD_pas_actualites"] = "Ingen nyheder for øjeblikket";
// Actualite_edit.php
$trad["TABLEAU_BORD_mail_nouvelle_actualite_cree"] = "Nye nyheder oprettet af";
$trad["TABLEAU_BORD_ala_une"] = "I Fokus";
$trad["TABLEAU_BORD_ala_une_info"] = "Vis altid nyhderne først";
$trad["TABLEAU_BORD_offline"] = "Arkiveret";
$trad["TABLEAU_BORD_date_online_auto"] = "Planlagt udgivelse";
$trad["TABLEAU_BORD_date_online_auto_alerte"] = "Nyheden er blevet arkiveret og afventer sin planlagte udgivelse";
$trad["TABLEAU_BORD_date_offline_auto"] = "planlagt arkivering";




////	MODULE_AGENDA
////

// Menu principal
$trad["AGENDA_nom_module"] = "Kalender";
$trad["AGENDA_nom_module_header"] = "kalender";
$trad["AGENDA_description_module"] = "Personlig og delt kalender";
$trad["AGENDA_ajout_agenda_ressource_admin"] = "Kun admin kan tilføje ressource kalender";
$trad["AGENDA_ajout_categorie_admin"] = "Kun admin kan tilføje kategorier til aktiviteter";
// Index.php
$trad["AGENDA_agendas_visibles"] = "Tilgængelige kalendere (personlige & ressource)";
$trad["AGENDA_afficher_tous_agendas"] = "Vis alle kalendere";
$trad["AGENDA_masquer_tous_agendas"] = "Skjul alle kalendere";
$trad["AGENDA_cocher_tous_agendas"] = "Check/Uncheck alle kalendere";
$trad["AGENDA_cocher_agendas_users"] = "Check/Uncheck brugere";
$trad["AGENDA_cocher_agendas_ressources"] = "Check/Uncheck ressourcer";
$trad["AGENDA_imprimer_agendas"] = "Print kalender(e)";
$trad["AGENDA_imprimer_agendas_infos"] = "print i landscape mode";
$trad["AGENDA_ajouter_agenda_ressource"] = "Tilføj en ressource kalender";
$trad["AGENDA_ajouter_agenda_ressource_bis"] = "Tilføj en ressource kalender : rum, køretøjer, videoprojektor, osv.";
$trad["AGENDA_exporter_ical"] = "Exporter aktiviteter (iCal format)";
$trad["AGENDA_exporter_ical_mail"] = "Exporter aktiviteter via e-mail (iCal)";
$trad["AGENDA_exporter_ical_mail2"] = "Til brug i kalendere i IPHONE, ANDROID, OUTLOOK, GOOGLE CALENDAR...";
$trad["AGENDA_importer_ical"] = "Importer aktiviteter (iCal)";
$trad["AGENDA_importer_ical_etat"] = "Tilstand";
$trad["AGENDA_importer_ical_deja_present"] = "Allerede tilstede";
$trad["AGENDA_importer_ical_a_importer"] = "For at importere";
$trad["AGENDA_suppr_anciens_evt"] = "Fjern tidligere aktiviteter";
$trad["AGENDA_confirm_suppr_anciens_evt"] = "Er du sikker på at du vil slette aktiviteter som ligger før den viste dato ?";
$trad["AGENDA_ajouter_evt_heure"] = "Tilføj en aktivitet den";
$trad["AGENDA_ajouter_evt_jour"] = "Tilføj en aktivitet til denne dag";
$trad["AGENDA_evt_jour"] = "Dag";
$trad["AGENDA_evt_semaine"] = "Uge";
$trad["AGENDA_evt_semaine_w"] = "Arbejds uge";
$trad["AGENDA_evt_mois"] = "Måned";
$trad["AGENDA_num_semaine"] = "uge";
$trad["AGENDA_voir_num_semaine"] = "Vis uge nummer";
$trad["AGENDA_periode_suivante"] = "Næste periode";
$trad["AGENDA_periode_precedante"] = "Forrige periode";
$trad["AGENDA_affectations_evt"] = "Aktivitet i kalenderen for ";
$trad["AGENDA_affectations_evt_autres"] = "+ andre usynlige kalendere";
$trad["AGENDA_affectations_evt_non_confirme"] = "Bekræftelse af Stand-By: ";
$trad["AGENDA_evenements_proposes_pour_agenda"] = "Aktiviteter foreslået for"; // "Videoprojecteur" / "salle de réunion" / etc.
$trad["AGENDA_evenements_proposes_mon_agenda"] = "Aktiviteter foreslået i min kalender";
$trad["AGENDA_evenement_propose_par"] = "Foreslået af";  // "Proposé par" Mr bidule truc
$trad["AGENDA_evenement_integrer"] = "Integrere aktiviteten ind i kalenderen ?";
$trad["AGENDA_evenement_pas_integrer"] = "Slet aktivitets forslaget ?";
$trad["AGENDA_supprimer_evt_agenda"] = "Slet for denne kalender ?";
$trad["AGENDA_supprimer_evt_agendas"] = "Slet i alle kalendere ?";
$trad["AGENDA_supprimer_evt_date"] = "Slet kun denne dato ?";
$trad["AGENDA_confirm_suppr_evt"] = "Slet aktiviteten i denne kalender ?";
$trad["AGENDA_confirm_suppr_evt_tout"] = "Slet aktiviteten i alle de kalendere hvor den er placeret ?";
$trad["AGENDA_confirm_suppr_evt_date"] = "Slet aktiviteten til denne dato, i alle de kalendere hvor den forekommer ?";
$trad["AGENDA_evt_prive"] = "Privat aktivitet";
$trad["AGENDA_aucun_agenda_visible"] = "Ingen kalendere vist";
$trad["AGENDA_evt_proprio"] = "Aktiviteter som jeg har oprettet";
$trad["AGENDA_evt_proprio_inaccessibles"] = "Vis kun aktiviteter som jeg har oprettet i kalendere jeg ikke har adgang til";
$trad["AGENDA_aucun_evt"] = "ingen aktivitet";
$trad["AGENDA_proposer"] = "Foreslå en aktivitet";
$trad["AGENDA_synthese"] = "Kalender synthesis";
$trad["AGENDA_pourcent_agendas_occupes"] = "Optagede kalendere";
$trad["AGENDA_agendas_details"] = "Se detaljerede dagsordener";
$trad["AGENDA_agendas_details_masquer"] = "Skjul detaljer dagsordeners";
// Evenement.php
$trad["AGENDA_categorie"] = "Kategori";
$trad["AGENDA_visibilite"] = "Synlighed";
$trad["AGENDA_visibilite_public"] = "Offentlig";
$trad["AGENDA_visibilite_public_cache"] = "Offentlig, men med maskerede detaljer";
$trad["AGENDA_visibilite_prive"] = "privat";
// Agenda_edit.php
$trad["AGENDA_affichage_evt"] = "Aktivitets visning";
$trad["AGENDA_affichage_evt_border"] = "Kant, med kategori farven";
$trad["AGENDA_affichage_evt_background"] = "Baggrund, med kategori farven";
$trad["AGENDA_plage_horaire"] = "Tids visning";
// Evenement_edit.php
$trad["AGENDA_periodicite"] = "Gentaget aktivitet";
$trad["AGENDA_period_non"] = "Præcis aktivitet";
$trad["AGENDA_period_jour_semaine"] = "Hver uge";
$trad["AGENDA_period_jour_mois"] = "Hver måned, nogen dage";
$trad["AGENDA_period_mois"] = "Hver måned";
$trad["AGENDA_period_mois_xdumois"] = "i måneden"; // Le 21 du mois
$trad["AGENDA_period_annee"] = "Hver år";
$trad["AGENDA_period_mois_xdeannee"] = "om året"; // Le 21/12 de l'année
$trad["AGENDA_period_date_fin"] = "Slut på perioden";
$trad["AGENDA_exception_periodicite"] = "Afvigelser i perioden";
$trad["AGENDA_agendas_affectations"] = "Tilføj til følgende kalendere";
$trad["AGENDA_verif_nb_agendas"] = "Vælg venligst én kalender";
$trad["AGENDA_mail_nouvel_evenement_cree"] = "Ny aktivitet, oprettet af";
$trad["AGENDA_input_proposer"] = "Foreslå aktivitet til ejer af kalenderen";
$trad["AGENDA_input_affecter"] = "Tilføj aktiviteten til kalenderen";
$trad["AGENDA_info_proposer"] = "Vis kun formål med aktivitet (du har ikke skriveadgang til denne kalender)";
$trad["AGENDA_info_pas_modif"] = "Ændringer forbudt fordi du ikek har skriveadgang til denne kalender";
$trad["AGENDA_visibilite_info"] = "<u>Offentlig</u> : Synlig for brugere som har læseadgang (eller +) til kalendere hvor aktiviteten er oprettet.<br><u>Offentlig, men maskerede detaljer</u> : Det samme, men dem der har læseadgang kan kun se tidspunktet, ikke detaljerne.<br><u>Privat</u> : Synlig kun for dem der har skriveadgang til de tildelte kalendere.";
$trad["AGENDA_edit_limite"] = "Du er ikke ejer af aktiviteten og du har ikke skriveadgang til disse kalendere. Du kan kun administrere aktiviteter i din egen kalender(e)";
$trad["AGENDA_creneau_occupe"] = "Perioden er allerede optaget i denne kalender :";

// Categories.php
$trad["AGENDA_gerer_categories"] = "Håndtér aktivitets kategorier";
$trad["AGENDA_categories_evt"] = "Aktivitets kategorier";
$trad["AGENDA_droit_gestion_categories"] = "Hver kategori kan ændres af dens ejer eller hoved administrator";




////	MODULE_FICHIER
////

// Menu principal
$trad["FICHIER_nom_module"] = "Filhåndtering";
$trad["FICHIER_nom_module_header"] = "Filer";
$trad["FICHIER_description_module"] = "Filhåndtering";
// Index.php
$trad["FICHIER_ajouter_fichier"] = "Tilføj filer";
$trad["FICHIER_ajouter_fichier_alert"] = "Folderen på serveren er ikke skrivbar! Kontakt administratoren";
$trad["FICHIER_telecharger_dossier"] = "Hent filerne";
$trad["FICHIER_telecharger_selection"] = "Hent Selection";
$trad["FICHIER_telecharger_dossier_confirm"] = "Bekræft at du har hentet filerne ?";
$trad["FICHIER_voir_images"] = "Vis billederne";
$trad["FICHIER_defiler_images"] = "Scroll billederne automatisk";
$trad["FICHIER_pixels"] = "pixels";
$trad["FICHIER_nb_versions_fichier"] = "versioner af filen"; // n versions du fichier
$trad["FICHIER_ajouter_versions_fichier"] = "tilføj ny fil version";
$trad["FICHIER_apercu"] = "Outline"; // n versions du fichier
$trad["FICHIER_aucun_fichier"] = "Antal filer lige nu";
// Fichier_edit.php  &  Dossier_edit.php  &  fichier_edit_ajouter.php  &  Versions_fichier.php
$trad["FICHIER_limite_chaque_fichier"] = "Fil størrelsen må ikke overskride"; // ...2 Mega Octets
$trad["FICHIER_optimiser_images"] = "Begræns størrelsen på billeder til "; // ..1024*768 pixels
$trad["FICHIER_maj_nom"] = "Filnavnet vil blive erstattet af den nye version";
$trad["FICHIER_ajout_plupload"] = "Mange uploads";
$trad["FICHIER_ajout_classique"] = "Klassisk upload";
$trad["FICHIER_erreur_nb_fichiers"] = "venligst vælg færre filer";
$trad["FICHIER_erreur_taille_fichier"] = "Filen er for stor";
$trad["FICHIER_erreur_non_geree"] = "Uhåndteret fejl";
$trad["FICHIER_ajout_multiple_info"] = "Tryk på 'Shift' eller 'Ctrl' for at vælge flere filer ad gangen";
$trad["FICHIER_select_fichier"] = "Vælg filer";
$trad["FICHIER_annuler"] = "Annuller";
$trad["FICHIER_selectionner_fichier"] = "Vælg venligst mindst én fil";
$trad["FICHIER_nouvelle_version"] = "eksisterer allerede, en ny version er tilføjet.";  // mon_fichier.gif "existe déjà"...
$trad["FICHIER_mail_nouveau_fichier_cree"] = "Ny fil oprettet af ";
$trad["FICHIER_mail_fichier_modifie"] = "Fil ændret af ";
$trad["FICHIER_contenu"] = "indhold";
// Versions_fichier.php
$trad["FICHIER_versions_de"] = "Versioner af"; // versions de fichier.gif
$trad["FICHIER_confirmer_suppression_version"] = "Bekræft sletning af denne version ?";
// Images.php
$trad["FICHIER_info_https_flash"] = "For at slippe for beskeden ''Do you want to display the nonsecure items?'' : <br> <br>> Klik på ''Tools'' <br>> Klik ''Internet Options'' < br />> Klik ''Security tab'' <br>> Klik ''Internet Zone'' <br>> Custom Level <br>> Enable Show ''mixed content'' i ''Other '' ";
$trad["FICHIER_img_precedante"] = "Foregående billede [venstre pil]";
$trad["FICHIER_img_suivante"] = "Næste billede [Højre pil / Mellemrums tast]";
$trad["FICHIER_rotation_gauche"] = "Rotér Venstre [Pil op]";
$trad["FICHIER_rotation_droite"] = "Rotér Højre [Pil ned]";
$trad["FICHIER_zoom"] = "Zoom ind / Zoom ud";
/// Video.php
$trad["FICHIER_voir_videos"] = "Se videoer";
$trad["FICHIER_regarder"] = "Se videoen";
$trad["FICHIER_video_precedante"] = "Forrige video";
$trad["FICHIER_video_suivante"] = "Næste video";
$trad["FICHIER_video_mp4_flv"] = "<a href='http://get.adobe.com/flashplayer'>Flash</a> ikke installeret.";




////	MODULE_FORUM
////

// Menu principal
$trad["FORUM_nom_module"] = "Forum";
$trad["FORUM_nom_module_header"] = "Forum";
$trad["FORUM_description_module"] = "Forum";
$trad["FORUM_ajout_sujet_admin"] = "Kun administrator kan tilføje emner";
$trad["FORUM_ajout_sujet_theme"] = "Brugere kan også tilføje emner";
// TRI
$trad["tri"]["date_dernier_message"] = "Sidste besked";
// Index.php & Sujet.php
$trad["FORUM_sujet"] = "emne";
$trad["FORUM_sujets"] = "emner";
$trad["FORUM_message"] = "besked";
$trad["FORUM_messages"] = "beskeder";
$trad["FORUM_dernier_message"] = "sidste af";
$trad["FORUM_ajouter_sujet"] = "Tilføj emne";
$trad["FORUM_voir_sujet"] = "Vis emne";
$trad["FORUM_repondre"] = "Besvar";
$trad["FORUM_repondre_message"] = "Svar på denne besked";
$trad["FORUM_repondre_message_citer"] = "Svar og citér denne besked";
$trad["FORUM_aucun_sujet"] = "Ingen emner i øjeblikket";
$trad["FORUM_pas_message"] = "Ingen beskeder om dette emne";
$trad["FORUM_aucun_message"] = "Ingen besked";
$trad["FORUM_confirme_suppr_message"] = "Bekræft sletning af besked og underliggende beskeder ?";
$trad["FORUM_retour_liste_sujets"] = "Returnér til listen med emner";
$trad["FORUM_notifier_dernier_message"] = "Underet via e-mail";
$trad["FORUM_notifier_dernier_message_info"] = "Send mig besked via e-mail ved hver nye besked";
// Sujet_edit.php  &  Message_edit.php
$trad["FORUM_infos_droits_acces"] = "For at deltage i dette denne debat, skal du have mindst ''begrænset skriveadgang''";
$trad["FORUM_theme_espaces"] = "Debatten er tilgængelig på skrivebordene";
$trad["FORUM_mail_nouveau_sujet_cree"] = "Nyt debat emne oprettet af ";
$trad["FORUM_mail_nouveau_message_cree"] = "Ny besked oprettet af";
// Themes
$trad["FORUM_theme_sujet"] = "Emne";
$trad["FORUM_accueil_forum"] = "Forum index";
$trad["FORUM_sans_theme"] = "Uden emne";
$trad["FORUM_themes_gestion"] = "Håndtér emner";
$trad["FORUM_droit_gestion_themes"] = "Hvert emne kan ændres af emnets forfatter eller af en hoved administrator";
$trad["FORUM_confirm_suppr_theme"] = "Advarsel! De valgte emner vil ikke indeholde nogen debatter! Bekræft sletning?";




////	MODULE_TACHE
////

// Menu principal
$trad["TACHE_nom_module"] = "Opgaver";
$trad["TACHE_nom_module_header"] = "Opgaver";
$trad["TACHE_description_module"] = "Opgaver";
// TRI
$trad["tri"]["priorite"] = "Prioritet";
$trad["tri"]["avancement"] = "Fremskridt";
$trad["tri"]["date_debut"] = "Start Dato";
$trad["tri"]["date_fin"] = "Slut Dato";
// Index.php
$trad["TACHE_ajouter_tache"] = "Tilføj en opgave";
$trad["TACHE_aucune_tache"] = "Ingen opgaver for øjeblikket";
$trad["TACHE_avancement"] = "Fremskridt";
$trad["TACHE_avancement_moyen"] = "Gennemsnitligt fremskridt";
$trad["TACHE_avancement_moyen_pondere"] = "Gennemsnitligt fremskridt vægtet på dagsats satsen";
$trad["TACHE_priorite"] = "Prioritet";
$trad["TACHE_priorite1"] = "Lav";
$trad["TACHE_priorite2"] = "Medium";
$trad["TACHE_priorite3"] = "Høj";
$trad["TACHE_priorite4"] = "Kritisk";
$trad["TACHE_responsables"] = "Ledere";
$trad["TACHE_budget_disponible"] = "Budget tilgængeligt";
$trad["TACHE_budget_disponible_total"] = "Totalt Budget tilgængeligt";
$trad["TACHE_budget_engage"] = "Budget brugt";
$trad["TACHE_budget_engage_total"] = "Totalt af budget brugt";
$trad["TACHE_charge_jour_homme"] = "Dagsats";
$trad["TACHE_charge_jour_homme_total"] = "Total dagsats";
$trad["TACHE_charge_jour_homme_info"] = "Antal dage krævet for én person at udføre opgaven";
$trad["TACHE_avancement_retard"] = "Fremskridt forsinket";
$trad["TACHE_budget_depasse"] = "Budget overskredet";
$trad["TACHE_afficher_tout_gantt"] = "Vis alle opgaver";
// tache_edit.php
$trad["TACHE_mail_nouvelle_tache_cree"] = "Ny opgave oprettet af";
$trad["TACHE_specifier_date"] = "Vælg venligst en dato";




////	MODULE_CONTACT
////

// Menu principal
$trad["CONTACT_nom_module"] = "Kontakter";
$trad["CONTACT_nom_module_header"] = "Kontakter";
$trad["CONTACT_description_module"] = "Kontakter";
// Index.php
$trad["CONTACT_ajouter_contact"] = "Tilføj en kontakt";
$trad["CONTACT_aucun_contact"] = "Ingen kontakter i øjeblikket";
$trad["CONTACT_creer_user"] = "Opret en bruger på dette skrivebord";
$trad["CONTACT_creer_user_infos"] = "Opret en bruger på dette skrivebord baseret på denne kontakt?";
$trad["CONTACT_creer_user_confirm"] = "brugeren blev oprettet";
// Contact_edit.php
$trad["CONTACT_mail_nouveau_contact_cree"] = "Ny kontakt oprettet af ";




////	MODULE_LIEN
////

// Menu principal
$trad["LIEN_nom_module"] = "Favoritter";
$trad["LIEN_nom_module_header"] = "Favoritter";
$trad["LIEN_description_module"] = "Favoritter";
// Index.php
$trad["LIEN_ajouter_lien"] = "Tilføj et link";
$trad["LIEN_aucun_lien"] = "Ingen links for øjeblikket";
// lien_edit.php & dossier_edit.php
$trad["LIEN_adresse"] = "Adresse";
$trad["LIEN_specifier_adresse"] = "Vælg venligst en adresse";
$trad["LIEN_mail_nouveau_lien_cree"] = "Ny adresse oprettet af ";




////	MODULE_MAIL
////

//  Menu principal
$trad["MAIL_nom_module"] = "E-mail";
$trad["MAIL_nom_module_header"] = "E-mail";
$trad["MAIL_description_module"] = "Send emails med et klik!";
// Index.php
$trad["MAIL_specifier_mail"] = "Skriv venligst en e-mail adresse";
$trad["MAIL_titre"] = "Titel på e-mail";
$trad["MAIL_no_header_footer"] = "Intet hoved eller fodnote";
$trad["MAIL_no_header_footer_infos"] = "Inkluder IKKE afsenders navn og link til skrivebordet";
$trad["MAIL_masquer_dest_message"] = "Skjul modtagere";
$trad["MAIL_masquer_dest_message_infos"] = "Som standard e-mail-modtagere vises i meddelelsen.";
$trad["MAIL_accuse_reception"] = "Anmod om en kvittering for modtagelse";
$trad["MAIL_accuse_reception_infos"] = "Advarsel: nogle e-mail-klienter ikke acceptere kvittering for modtagelse";
$trad["MAIL_fichier_joint"] = "Vedhæftet fil";
// Historique Mail
$trad["MAIL_historique_mail"] = "E-mail historik";
$trad["MAIL_aucun_mail"] = "Ingen e-mail";
$trad["MAIL_envoye_par"] = "E-mail sendt af";
$trad["MAIL_destinataires"] = "Modtagere";
?>
