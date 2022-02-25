<?php
////	PARAMETRAGE
////

// Header http
$trad["HEADER_HTTP"] = "nl";
// Editeur Tinymce
$trad["EDITOR"] = "nl";
// Dates formatées par PHP
setlocale(LC_TIME, "nl_NL.utf8", "nl_NL", "nl", "dutch");




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
		$tab_jours_feries[$date] = "Lundi de pâques";
		// Jeudi de l'ascension
		$date = strftime("%Y-%m-%d", $paques_unix + ($jour_unix*39));
		$tab_jours_feries[$date] = "Jeudi de l'ascension";
		// Lundi de pentecÙte
		$date = strftime("%Y-%m-%d", $paques_unix + ($jour_unix*50));
		$tab_jours_feries[$date] = "Lundi de pentecôte";
	}

	////	Les fÍtes fixes
	// Jour de l'an
	$tab_jours_feries[$annee."-01-01"] = "Nieuwjaarsdag";
	// Fete du travail
	$tab_jours_feries[$annee."-05-01"] = "Feest van de Arbeid";
	// Fête nationale
	$tab_jours_feries[$annee."-07-21"] = "Nationale Feestdag";
	// Assomption
	$tab_jours_feries[$annee."-08-15"] = "O.L.V-Hemelvaart";
	// Toussaint
	$tab_jours_feries[$annee."-11-01"] = "Pinksteren";
	// Armistice 14-18
	$tab_jours_feries[$annee."-11-11"] = "Wapenstilstand";
	// Noel
	$tab_jours_feries[$annee."-12-25"] = "Kerstmis";

	////	Retourne le résultat
	return $tab_jours_feries;
}




////	COMMUN
////

// Divers
$trad["remplir_tous_champs"] = "Gelieve alle velden in te vullen";
$trad["voir_detail"] = "In detail bekijken";
$trad["elem_inaccessible"] = "Ontoegankelijk element";
$trad["champs_obligatoire"] = "Verplicht veld";
$trad["oui"] = "ja";
$trad["non"] = "niet";
$trad["aucun"] = "geen";
$trad["aller_page"] = "Ga naar de pagina";
$trad["alphabet_filtre"] = "Alfabetische filter";
$trad["tout"] = "Alles";
$trad["tout_afficher"] = "Alles";
$trad["important"] = "Belangrijk";
$trad["afficher"] = "Weergeven";
$trad["masquer"] =" Verbergen";
$trad["deplacer"] = "bewegen";
$trad["options"] = "Opties";
$trad["reinitialiser"] = "Herbeginnen";
$trad["garder"] = "Bewaren";
$trad["par_defaut"] = "standaardselectie";
$trad["localiser_carte"] = "Op een kaart localiseren";
$trad["espace_public"] = "Publieke ruimte";
$trad["bienvenue_agora"] = "Welkom op het Forum !";
$trad["mail_pas_valide"] = "De mail is niet geldig";
$trad["element"] = "element";
$trad["elements"] = "elementen";
$trad["dossier"] = "dossier";
$trad["dossiers"] = "dossiers";
$trad["fermer"] = "Sluiten";
$trad["imprimer"] = "Afdrukken";
$trad["select_couleur"] = "Kleur selecteren";
$trad["visible_espaces"] = "Gebieden waar het zal worden zichtbaar";
$trad["visible_ts_espaces"] = "Op alle plaatsen zichtbaar";
$trad["admin_only"] = "Enkel gebruiker";
$trad["divers"] = "Diversen";
$trad["confirm_close_lightbox"] = "Zou u het formulier sluit?";
// images
$trad["photo"] = "Foto";
$trad["fond_ecran"] = "Schermachtergrond";
$trad["image_changer"] = "Wijzigen";
$trad["pixels"] = "pixels";
// Connexion
$trad["specifier_login_password"] = "Gelieve een gebruikersnaam en een paswoord te kiezen";
$trad["identifiant"] = "Login";
$trad["identifiant_connexion"] = "Login";
$trad["identifiant_ou_email"] = "Email / Login";
$trad["pass"] = "Paswoord";
$trad["pass2"] = "Paswoord bevestigen";
$trad["password_verif_alert"] = "De bevestiging van het paswoord is ongeldig";
$trad["connexion"] = "Verbinding";
$trad["connexion_auto"] = "contact houden";
$trad["connexion_auto_info"] = "Mijn gebruikersnaam en paswoord onthouden voor een automatische ";
$trad["password_oublie"] = "wachtwoord vergeten ?";
$trad["password_oublie_info"] = "Mijn gebruikersnaam en paswoord mailen (indien aangeduid)";
$trad["acces_invite"] = "Gasttoegang";
$trad["espace_password_erreur"] = "Foutief paswoord";
$trad["version_ie"] = "U gebruikt een verouderde browser-versie. Voor de veiligheid van uw PC en gegevens, raden wij u sterk aan om uw huidige versie op te waarderen.";
// Affichage
$trad["type_affichage"] = "Weergave";
$trad["type_affichage_liste"] = "Lijst";
$trad["type_affichage_bloc"] = "Blokkeer";
$trad["type_affichage_arbo"] = "boom";
// SÈlectionner / DÈselectionner tous les ÈlÈments
$trad["select_deselect"] = "selecteren / deselecteren";
$trad["aucun_element_selectionne"] = "Geen enkel onderdeel werd geselecteerd";
$trad["tout_selectionner"] = "Selectie ongedaan maken";
$trad["inverser_selection"] = "Selectioneren/ongedaan maken";
$trad["suppr_elements"] = "De onderdelen verwijderen";
$trad["deplacer_elements"] = "Naar een ander dossier verplaatsen";
$trad["voir_sur_carte"] = "Toon op kaart";
$trad["selectionner_user"] = "Gelieve tenminste ÈÈn gebruiker aan te duiden";
$trad["selectionner_2users"] = "Dank u voor het kiezen van ten minste 2 gebruikers";
$trad["selectionner_espace"] = "Gelieve tenminste ÈÈn ruimte aan te duiden";
// Temps ("de 11h à 12h", "le 25-01-2007 à 10h30", etc.)
$trad["de"] = "van";
$trad["a"] = "tot";
$trad["le"] = "op";
$trad["debut"] = "Begin";
$trad["fin"] = "Einde";
$trad["separateur_horaire"] = "u";
$trad["jours"] = "dagen";
$trad["jour_1"] = "Maandag";
$trad["jour_2"] = "Dinsdag";
$trad["jour_3"] = "Woensdag";
$trad["jour_4"] = "Donderdag";
$trad["jour_5"] = "Vrijdag";
$trad["jour_6"] = "Zaterdag";
$trad["jour_7"] = "Zondag";
$trad["mois_1"] = "januari";
$trad["mois_2"] = "februari";
$trad["mois_3"] = "maart";
$trad["mois_4"] = "april";
$trad["mois_5"] = "mei";
$trad["mois_6"] = "juni";
$trad["mois_7"] = "juli";
$trad["mois_8"] = "augustus";
$trad["mois_9"] = "september";
$trad["mois_10"] = "oktobre";
$trad["mois_11"] = "november";
$trad["mois_12"] = "december";
$trad["mois_suivant"] = "Volgende maand";
$trad["mois_precedant"] = "Vorige maand";
$trad["annee_suivante"] = "Volgend jaar";
$trad["annee_precedante"] = "Vorig jaar";
$trad["aujourdhui"] = "vandaag";
$trad["aff_aujourdhui"] = "Vandaag weergeven";
$trad["modif_dates_debutfin"] = "De einddatum kan niet vroeger dan de begindatum zijn.";
// Nom & Description (pour les menus d'Èdition principalement)
$trad["titre"] = "Titel";
$trad["nom"] = "Naam";
$trad["description"] = "Omschrijving";
$trad["specifier_titre"] = "Gelieve een titel te kiezen";
$trad["specifier_nom"] = "Gelieve een naam te kiezen";
$trad["specifier_description"] = "Gelieve een omschrijving te kiezen";
$trad["specifier_titre_description"] = "Gelieve een titel of een omschrijving te kiezen";
// Validation des formulaires
$trad["ajouter"] = " Toevoegen";
$trad["modifier"] = " Wijzigen";
$trad["modifier_et_acces"] = "Het onderdeel en de toegang wijzigen";
$trad["valider"] = "Valideren ";
$trad["lancer"] = " Vrijgeven";
$trad["envoyer"] = "Stuur";
$trad["envoyer_a"] = "Te sturen naar";
// Tri d'affichage. Tous les ÈlÈments (dossier, t‚che, lien, etc...) ont par dÈfaut une date, un auteur & une description
$trad["trie_par"] = "Ordenen volgens";
$trad["tri"]["date_crea"] = "datum van creatie";
$trad["tri"]["date_modif"] = "datum van wijziging";
$trad["tri"]["titre"] = "titel";
$trad["tri"]["description"] = "beschrijving";
$trad["tri"]["id_utilisateur"] = "auteur";
$trad["tri"]["extension"] = "type fiche";
$trad["tri"]["taille_octet"] = "grootte";
$trad["tri"]["nb_downloads"] = "downloads";
$trad["tri"]["civilite"] = "aanspreektitel";
$trad["tri"]["nom"] = "naam";
$trad["tri"]["prenom"] = "voornaam";
$trad["tri"]["adresse"] = "adres";
$trad["tri"]["codepostal"] = "postcode";
$trad["tri"]["ville"] = "stad";
$trad["tri"]["pays"] = "land";
$trad["tri"]["fonction"] = "functie";
$trad["tri"]["societe_organisme"] = "bedrijf/organisatie";
$trad["tri_ascendant"] ="omhoog";
$trad["tri_descendant"] = "Omlaag";
// Options de suppression
$trad["confirmer"] = "Bevestigen ?";
$trad["confirmer_suppr"] = "Wissen bevestigen ?";
$trad["confirmer_suppr_bis"] = "Weet je zeker dat ?!";
$trad["confirmer_suppr_dossier"] = "Wissen van de folder en wissen van alle gegevens bevestigen ? <br><br>Opgelet! Bepaalde onderdelen zijn mogelijk niet meer toegankelijk : ze zullen ook gewist worden !!";
$trad["supprimer"] = "Wissen";
// Visibilité d'un Objet : auteur et droits d'accès
$trad["auteur"] = "Auteur : ";
$trad["cree"] = "Gemaakt";  //...12-12-2012
$trad["cree_par"] = "Schepping";
$trad["modif_par"] = "Wijziging";
$trad["historique_element"] = "historisch";
$trad["invite"] = "gast";
$trad["invites"] = "gasts";
$trad["tous"] = "alle";
$trad["inconnu"] = "onbekende persoon";
$trad["acces_perso"] = "Persoonlijke toegangscode";
$trad["lecture"] = "lezen";
$trad["lecture_infos"] = "Toegang tot lezen";
$trad["ecriture_limit"] = "beperkte schriftelijk";
$trad["ecriture_limit_infos"] = "Beperkte toegang schriftelijk : mogelijkheid om -ELEMENTS- te creëren zonder te kunnen wijzigen / verwijderen die worden gemaakt door andere";
$trad["ecriture"] = "schrijven";
$trad["ecriture_infos"] = "Toegang tot schrijven";
$trad["ecriture_infos_conteneur"] = "Toegang tot schrijven : mogelijkheid om -ELEMENTS- toe te voegen en te wijzigen of te verwijderen die worden gemaakt door andere";
$trad["ecriture_racine_defaut"] = " Standaard toegang tot schrijven ";
$trad["ecriture_auteur_admin"] = "Alleen de auteur en beheerders kunnen de machtigingen of verwijder deze -CONTENEUR-";
$trad["contenu_dossier"] = "inhoud";
$trad["aucun_acces"] = "toegang geweigerd";
$trad["libelles_objets"] = array("element"=>"elementen", "fichier"=>"bestanden", "tache"=>"vlekken", "lien"=>"favorieten", "contact"=>"contacten", "evenement"=>"evenementen", "message"=>"berichten", "conteneur"=>"container", "dossier"=>"bestand", "agenda"=>"agenda", "sujet"=>"onderwerp");
// Envoi d'un mail (nouvel utilisateur, notification de crÈation d'objet, etc...)
$trad["mail_envoye_par"] = "Verzonden door";  // "Verzonden door" Mr. Trucmuche
$trad["mail_envoye"] = "Het bericht werd succesvol verzonden!";
$trad["mail_envoye_notif"] = "Het bericht werd succesvol verzonden!";
$trad["mail_pas_envoye"] = "Het bericht kon niet verzonden worden..."; // idem
// Dossier & fichier
$trad["giga_octet"] = "Go";
$trad["mega_octet"] = "Mo";
$trad["kilo_octet"] = "Ko";
$trad["octet"] = "Octets";
$trad["dossier_racine"] = "Standaardfolder";
$trad["deplacer_autre_dossier"] = "Verplaatsen naar een andere folder";
$trad["ajouter_dossier"] = "folder toevoegen";
$trad["modifier_dossier"] = "Folder wijzigen";
$trad["telecharger"] = "Downloaden";
$trad["telecharge_nb"] = "Downloaden";
$trad["telecharge_nb_bis"] = ""; // Téléchargé 'n' fois
$trad["telecharger_dossier"] = "Download de folder";
$trad["espace_disque_utilise"] = "Gebruikte ruimte ";
$trad["espace_disque_utilise_mod_fichier"] = "Gebruikte ruimte ";
$trad["download_alert"] = "Download ontoegankelijk tijdens de dag (bestandsgrootte te groot)";
// Infos sur une personne
$trad["civilite"] = "Aanspreektitel";
$trad["nom"] = "Naam";
$trad["prenom"] = "Voornaam";
$trad["adresse"] = "Adres";
$trad["codepostal"] = "Postcode";
$trad["ville"] = "Stad";
$trad["pays"] = "Land";
$trad["telephone"] = "Telefoon";
$trad["telmobile"] = "Mobiel";
$trad["mail"] = "Email";
$trad["fax"] = "Fax";
$trad["siteweb"] = "Website";
$trad["competences"] = "Vaardigheden";
$trad["hobbies"] = "Interesse";
$trad["fonction"] = "Functie";
$trad["societe_organisme"] = "Bedrijf/organisatie";
$trad["commentaire"] = "Commentaar";
// Rechercher
$trad["preciser_text"] = "Gelieve een kernwoord op te geven van tenminste 3 tekens.";
$trad["rechercher"] = "Zoeken";
$trad["rechercher_date_crea"] = "Datum van creatie";
$trad["rechercher_date_crea_jour"] = "minder dan een dag";
$trad["rechercher_date_crea_semaine"] = "minder dan een week";
$trad["rechercher_date_crea_mois"] = "minder dan een maand";
$trad["rechercher_date_crea_annee"] = "minder dan een jaar";
$trad["rechercher_espace"] = "Zoeken binnen de ruimte";
$trad["recherche_avancee"] =  "Geavanceerd zoeken";
$trad["recherche_avancee_mots_certains"] =  "elk woord";
$trad["recherche_avancee_mots_tous"] =  "alle woorden";
$trad["recherche_avancee_expression_exacte"] =  "exacte woordcombinatie";
$trad["recherche_avancee_champs"] =  "zoekvelden";
$trad["recherche_avancee_pas_concordance"] =  "Modules en geselecteerde velden komen niet overeen. Bedankt aan hun overeenkomst binnen een termijn van Geavanceerd zoeken.";
$trad["mots_cles"] = "Kernwoorden";
$trad["liste_modules"] = "Modules";
$trad["liste_champs"] = "Velden";
$trad["liste_champs_elements"] = "Elementen van";
$trad["aucun_resultat"] = "Geen resultaat";
// Importer / Exporter des contact
$trad["exporter"] = "Exporteren";
$trad["importer"] = "Importeren";
$trad["export_import_users"] = "gebruikers";
$trad["export_import_contacts"] = "contacten";
$trad["export_format"] = "format";
$trad["contact_separateur"] = "begrenzingsteken";
$trad["contact_delimiteur"] = "scheidingsteken";
$trad["specifier_fichier"] = "Gelieve een bestand te kiezen";
$trad["extension_fichier"] = "Dit bestandtype is niet geldig. Hij moet van het typeÖ.   zijn";
$trad["format_fichier_invalide"] = "Het bestandformaat komt niet overeen met het geselectioneerde type";
$trad["import_infos"] = "Gelieve de Forum-velden te selectioneren aan de hand van de lijst in elke kolom.";
$trad["import_infos_contact"] = " Als standaardinstelling, zullen de contactpersonen toegewezen worden aan de ruimte.";
$trad["import_infos_user"] = "Indien de gebruikersnaam en het paswoord niet aangeduid zijn, zullen ze automatisch verschijnen.";
$trad["import_alert"] = "Gelieve de kolommen naam en voornaam in de lijsten.";
$trad["import_alert2"] = "Gelieve tenminste ÈÈn contact aan te duiden voor import";
$trad["import_alert3"] = "Het forum-veld is reeds aangeduid in een andere kolom (elk forum-veld kan slechts ÈÈn keer aangeduid worden)";
// Captcha
$trad["captcha"] = "Visuele herkenning";
$trad["captcha_info"] = "Gelieve de 4 tekens hiernaast te kopiren voor de identificatie";
$trad["captcha_alert_specifier"] = "Gelieve de visuele herkenning aan te duiden";
$trad["captcha_alert_erronee"] = "De visuele herkenning is ongeldig ";
// Gestion des inscriptions d'utilisateur
$trad["inscription_users"] = "registreren op de site";
$trad["inscription_users_info"] = "maak een nieuwe gebruikersaccount (gevalideerd door een beheerder van de ruimte)";
$trad["inscription_users_espace"] = "abonneren op de ruimte";
$trad["inscription_users_enregistre"] = "Uw abonnement is geregistreerd : zal het zo snel mogelijk worden gevalideerd door de beheerder van de ruimte";
$trad["inscription_users_option_espace"] = "Laat bezoekers te registreren op de ruimte";
$trad["inscription_users_option_espace_info"] = "De inschrijving is op de homepage van de site. Registratie moet vervolgens worden gevalideerd door de beheerder van de ruimte.";
$trad["inscription_users_validation"] = "Valideer gebruikersinvoer";
$trad["inscription_users_valider"] = "bevestigen";
$trad["inscription_users_invalider"] = "ongeldig";
$trad["inscription_users_valider_mail"] = "Uw account is gevalideerd op";
$trad["inscription_users_invalider_mail"] = "Uw account is niet gevalideerd op";
// Connexion à un serveur LDAP
$trad["ldap_connexion_serveur"] = "Verbinding maken met een LDAP-server";
$trad["ldap_server"] = "serveradres";
$trad["ldap_server_port"] = "Port-server";
$trad["ldap_server_port_infos"] = "''389'' standaard";
$trad["ldap_admin_login"] = "String aansluiting voor adminn";
$trad["ldap_admin_login_infos"] = "bij voorbeeld ''uid=admin,ou=my_company''";
$trad["ldap_admin_pass"] = "Wachtwoord van de beheerder";
$trad["ldap_groupe_dn"] = "Groep / base DN";
$trad["ldap_groupe_dn_infos"] = "Locatie van map gebruikers.<br> bij voorbeeld ''ou=users,o=my_company''";
$trad["ldap_connexion_erreur"] = "Fout bij verbinding maken met LDAP-server !";
$trad["ldap_import_infos"] = "Laat de configuratie van de LDAP-server in de module Beheer.";
$trad["ldap_crea_auto_users"] = "Auto aanmaken van gebruikers na LDAP identificatie";
$trad["ldap_crea_auto_users_infos"] = "Maak automatisch een gebruiker als deze ontbreekt in de Agora, maar aanwezig zijn op de LDAP-server: het zal worden toegewezen aan ''alle gebruikers Site''.<br>Anders zal de gebruiker, zal niet worden gemaakt.";
$trad["ldap_pass_cryptage"] = "Encryptie van wachtwoorden op de LDAP-server";
$trad["ldap_effacer_params"] = "Verwijder LDAP instelling ?";
$trad["ldap_pas_module_php"] = "PHP-module voor aansluiting op een LDAP-server niet is geïnstalleerd!";




////	DIVERS
////

// Messages d'alert ou d'erreur
$trad["MSG_ALERTE_identification"] = "Gebruikersnaam of paswoord ongeldig";
$trad["MSG_ALERTE_dejapresent"] = "Huidige account reeds in gebruik door een ander IP-adres... (een account kan slechts gebruikt worden op ÈÈn plaats tegelijkertijd)";
$trad["MSG_ALERTE_adresseip"] = "Het IP-adres dat u gebruikt geeft geen toegang tot deze account";
$trad["MSG_ALERTE_pasaccesite"] = "Toegang tot de site is momenteel geweigerd, u behoort waarschijnlijk tot geen enkele ruimte.";
$trad["MSG_ALERTE_captcha"] = "De visuele herkenning is mislukt";
$trad["MSG_ALERTE_acces_fichier"] = "Ontoegankelijk bestand";
$trad["MSG_ALERTE_acces_dossier"] = "Ontoegankelijke folder";
$trad["MSG_ALERTE_espace_disque"] = "De opslagruimte voor uw bestanden is ontoereikend, u kan geen bestand toevoegen.";
$trad["MSG_ALERTE_type_interdit"] = "Bestandstype niet toegestaan";
$trad["MSG_ALERTE_taille_fichier"] = "De maximale grootte van het bestand wordt overschreden";
$trad["MSG_ALERTE_type_version"] = "Bestand type dat afwijkt van de oorspronkelijke";
$trad["MSG_ALERTE_deplacement_dossier"] = "U kan het bestand niet verplaatsen binnen het bestand zelf... ! ";
$trad["MSG_ALERTE_nom_dossier"] = "Er bestaat reeds een bestand met dezelfde naam. Wil u toch bevestigen?";
$trad["MSG_ALERTE_nom_fichier"] = "Een bestand met dezelfde naam bestaat al, maar werd niet vervangen";
$trad["MSG_ALERTE_chmod_stock_fichiers"] = "Het beheer van de folders is niet toegankelijk voor wijzigingen. Gelieve een ìchmod 775î uit te voeren op de folder ìstock_foldersî (toegang tot lezen-schrijven voor de eigenaar en de groep)";
$trad["MSG_ALERTE_nb_users"] = "U kan gaan nieuwe gebruiker toevoegen : gebruik  "; // "
$trad["MSG_ALERTE_miseajourconfig"] = "Het configuratiebestand is ontoegankelijk (config.inc.php): update onmogelijk!";
$trad["MSG_ALERTE_miseajour"] = "Update voltooid. Gelieve uw browser herop te starten vooraleer u opnieuw verbinding maakt.";
$trad["MSG_ALERTE_user_existdeja"] = "Deze gebruikersnaam bestaat reeds : de gebruiker werd dus niet aangemaakt.";
$trad["MSG_ALERTE_temps_session"] = "Uw sessie is net verlopen, gelieve u opnieuw te verbinden.";
$trad["MSG_ALERTE_specifier_nombre"] = "Gelieve een aantal te geven";
// header menu
$trad["HEADER_MENU_espace_administration"] = "Administratie van de site";
$trad["HEADER_MENU_espaces_dispo"] = "beschikbare ruimtes";
$trad["HEADER_MENU_espace_acces_administration"] = "(toegang administratie)";
$trad["HEADER_MENU_affichage_elem"] = "De elementen weergeven";
$trad["HEADER_MENU_affichage_normal"] = "die mij toebehoren";
$trad["HEADER_MENU_affichage_normal_infos"] = "Dit is het display normaal / default";
$trad["HEADER_MENU_affichage_auteur"] = "die ik aangemaakt heb";
$trad["HEADER_MENU_affichage_auteur_infos"] = "alleen de elementen die ik heb gemaakt";
$trad["HEADER_MENU_affichage_tout"] = "Alle elementen van de ruimte (admins)";
$trad["HEADER_MENU_affichage_tout_infos"] = "Voor de beheerder van de ruimte : alle elementen, zelfs degene die niet zijn toegewezen voor de beheerder !";
$trad["HEADER_MENU_recherche_elem"] = "Een element van de ruimte opzoeken";
$trad["HEADER_MENU_sortie_agora"] = "Het Forum verlaten";
$trad["HEADER_MENU_raccourcis"] = "Shortcut";
$trad["HEADER_MENU_seul_utilisateur_connecte"] = "Momenteel alleen op de site";
$trad["HEADER_MENU_en_ligne"] = "Online";
$trad["HEADER_MENU_connecte_a"] = "verbonden met de site om";   // M. Bidule truc "connectÈ au site ‡" 12:45
$trad["HEADER_MENU_messenger"] = "Chat";
$trad["HEADER_MENU_envoye_a"] = "Verzonden naar";
$trad["HEADER_MENU_ajouter_message"] = "Een bericht toevoegen";
$trad["HEADER_MENU_specifier_message"] = "Gelieve een bericht in te geven";
$trad["HEADER_MENU_enregistrer_conversation"] = "Dit gesprek opnemen";
// Footer
$trad["FOOTER_page_generee"] = "pagina gegenereerd in";
// Password_oublie
$trad["PASS_OUBLIE_preciser_mail"] = "Vul uw e-mailadres uw gebruikersnaam en paswoord te ontvange";
$trad["PASS_OUBLIE_mail_inexistant"] = "De aangeduide mail kan niet worden teruggevonden.";
$trad["PASS_OUBLIE_mail_objet"] = "Verbinding met uw ruimte";
$trad["PASS_OUBLIE_mail_contenu"] = "uw login";
$trad["PASS_OUBLIE_mail_contenu_bis"] = "Klik hier om uw wachtwoord te resetten";
$trad["PASS_OUBLIE_prompt_changer_pass"] = "Geef uw nieuwe wachtwoord";
$trad["PASS_OUBLIE_id_newpassword_expire"] = "De link naar het wachtwoord te regenereren is verlopen .. dank aan de procedure opnieuw te starten";
$trad["PASS_OUBLIE_password_reinitialise"] = "Uw nieuwe wachtwoord werd geregistreerd !";
// menu_edit_objet
$trad["EDIT_OBJET_alert_aucune_selection"] = "U dient tenminste ÈÈn persoon of ruimte aan te duiden ";
$trad["EDIT_OBJET_alert_pas_acces_perso"] = "U bent niet toegewezen aan het element. valideren allemaal hetzelfde ?";
$trad["EDIT_OBJET_alert_ecriture"] = "Er dient tenminste ÈÈn persoon of ÈÈn ruimte aangewezen te zijn voor wijzigingen";
$trad["EDIT_OBJET_alert_ecriture_limite_defaut"] = "Waarschuwing! met schrijf-toegang, Alle berichten kunnen worden verwijderd! \\n\\nHet wordt daarom aanbevolen om te schrijven toegang te beperken";
$trad["EDIT_OBJET_alert_invite"] = "Gelieve een naam of pseudoniem op te geven.";
$trad["EDIT_OBJET_droit_acces"] = "Toegansrechten";
$trad["EDIT_OBJET_espace_pas_module"] = "De module is nog niet toegevoegd aan de ruimte ";
$trad["EDIT_OBJET_tous_utilisateurs"] = "Alle gebruikers";
$trad["EDIT_OBJET_tous_utilisateurs_espaces"] = "Alle ruimtes";
$trad["EDIT_OBJET_espace_invites"] = "De gasten van deze publieke ruimte";
$trad["EDIT_OBJET_aucun_users"] = "Momenteel geen gebruiker in deze ruimte";
$trad["EDIT_OBJET_invite"] = "Uw naam/pseudoniem";
$trad["EDIT_OBJET_admin_espace"] = "Directeur van de ruimte: schrijf-toegang tot alle elementen toegewezen aan de ruimte";
$trad["EDIT_OBJET_tous_espaces"] = "Al mijn ruimtes weergeven";
$trad["EDIT_OBJET_notif_mail"] = "Op de hoogte houden via mail";
$trad["EDIT_OBJET_notif_mail_joindre_fichiers"] = "Bestanden aan de kennisgeving";
$trad["EDIT_OBJET_notif_mail_info"] = "Mailen naar de personen die toegang hebben tot dit onderdeel";
$trad["EDIT_OBJET_notif_mail_selection"] = "De ontvangers manueel aanduiden";
$trad["EDIT_OBJET_notif_tous_users"] = "Weergeven + van de gebruikers";
$trad["EDIT_OBJET_droits_ss_dossiers"] = "Dezelfde rechten toeschrijven aan de sub-folders";
$trad["EDIT_OBJET_raccourci"] = "Shortcut";
$trad["EDIT_OBJET_raccourci_info"] = "Een shortcut weergeven in het hoofdmenu";
$trad["EDIT_OBJET_fichier_joint"] = "Voeg afbeeldingen, video's of andere bestanden";
$trad["EDIT_OBJET_inserer_fichier"] = "Bekijk de beschrijving";
$trad["EDIT_OBJET_inserer_fichier_info"] = "Geef het beeld / video-speler / mp3-speler .. in de bovenstaande beschrijving. Het inbrengen wordt uitgevoerd na formuliervalidatie.";
$trad["EDIT_OBJET_inserer_fichier_alert"] = "Klik ''Invoegen'' om de afbeeldingen toe te voegen in de tekst / beschrijving";
$trad["EDIT_OBJET_demande_a_confirmer"] = "Uw aanvraag is geregistreerd. Het zal binnenkort worden bevestigd.";
// Formulaire d'installation
$trad["INSTALL_connexion_bdd"] = "Configuratie van de databank";
$trad["INSTALL_db_host"] = "Hostname van de server";
$trad["INSTALL_db_name"] = "Naam van de databank";
$trad["INSTALL_db_name_info"] = "Opgelet !!<br> Als de databank van het Forum reeds bestaat, zal ze vervangen worden (enkel de tabs die met ''gt_''beginnen)";
$trad["INSTALL_db_login"] = "Gebruikersnaam";
$trad["INSTALL_db_password"] = "Paswoord";
$trad["INSTALL_login_password_info"] = "Om zich aan te melden als hoofdadministrator";
$trad["INSTALL_config_admin"] = "Administrator van het Forum";
$trad["INSTALL_config_espace"] = "Configuratie van de hoofdsom";
$trad["INSTALL_erreur_acces_mysql"] = "No connection to the MySQL database";
$trad["INSTALL_erreur_acces_bdd"] = "De verbinding met de databank kon niet worden gemaakt, toch bevestigen?";
$trad["INSTALL_erreur_agora_existe"] = "De tabs van het forum zijn reeds bestaande ! De installatie toch bevestigen en de tabs vervangen?";
$trad["INSTALL_confirm_version_php"] = "Forum-Project heeft minstens een PHP- versie van 4.3 nodig, toch bevestigen?";
$trad["INSTALL_confirm_version_mysql"] = "Forum-Project heeft minstens een MySQL-versie van 4.2 nodig, toch bevestigen?";
$trad["INSTALL_confirm_install"] = "De installatie bevestigen?";
$trad["INSTALL_install_ok"] = "Forum-Project is succesvol geÔnstalleerd ! Gelieve ñwegens veiligheidsredenen- de folder ìinstallî te wissen alvorens te beginnen";




////	MODULE_PARAMETRAGE
////

// Menu principal
$trad["PARAMETRAGE_nom_module"] = "Setup";
$trad["PARAMETRAGE_nom_module_header"] = "Setup";
$trad["PARAMETRAGE_description_module"] = "Algemene setup";
// Index.php
$trad["PARAMETRAGE_sav"] = "De databank en de bestanden bewaren";
$trad["PARAMETRAGE_sav_alert"] = "De oprichting van het back-upbestand kan een paar minuten ... en download een enkele tientallen minuten.";
$trad["PARAMETRAGE_sav_bdd"] = "De databank bewaren";
$trad["PARAMETRAGE_adresse_web_invalide"] = "Spijtiggenoeg is het login-adres ongeldig : het adres moet beginnen met HTTP:// ";
$trad["PARAMETRAGE_espace_disque_invalide"] = "De maximale schijfruimte moet een een heid zijn";
$trad["PARAMETRAGE_confirmez_modification_site"] = "Wijzigingen bevestigen?";
$trad["PARAMETRAGE_nom_site"] = "Naam van de site";
$trad["PARAMETRAGE_adresse_web"] = "Login-adres van de site";
$trad["PARAMETRAGE_footer_html"] = "Html footer / voetteksts";
$trad["PARAMETRAGE_footer_html_info"] = "Om bijvoorbeeld statistische tools toe te voegen";
$trad["PARAMETRAGE_langues"] = "Standaardtaal";
$trad["PARAMETRAGE_timezone"] = "Tijdzone";
$trad["PARAMETRAGE_nom_espace"] = "Naam van de hoofdruimte";
$trad["PARAMETRAGE_limite_espace_disque"] = "Beschikbare schijfruimte voor het opslaan van bestanden";
$trad["PARAMETRAGE_logs_jours_conservation"] = "Houdbaarheid van de LOGS";
$trad["PARAMETRAGE_mode_edition"] = "Onderdelen uitgeven";
$trad["PARAMETRAGE_edition_popup"] = "in een popup";
$trad["PARAMETRAGE_edition_iframe"] = "in een iframe";
$trad["PARAMETRAGE_skin"] = "Kleur van de interface (achtergrond van de onderdelen, menuís, etc.)";
$trad["PARAMETRAGE_noir"] = "Zwart";
$trad["PARAMETRAGE_blanc"] = "Wit";
$trad["PARAMETRAGE_erreur_fond_ecran_logo"] = "De scherm en het logo moeten in het formaat .jpg of .png staan";
$trad["PARAMETRAGE_suppr_fond_ecran"] = "De schermachtergrond annuleren ?";
$trad["PARAMETRAGE_logo_footer"] = "logo voettekst";
$trad["PARAMETRAGE_logo_footer_url"] = "URL";
$trad["PARAMETRAGE_editeur_text_mode"] = "Mode van de teksteditor (TinyMCE)";
$trad["PARAMETRAGE_editeur_text_minimal"] = "Minimaal";
$trad["PARAMETRAGE_editeur_text_complet"] = "Compleet (+ borden + medias + plakken vanuit Word)";
$trad["PARAMETRAGE_messenger_desactive"] = "Instant messenger geactiveerd ";
$trad["PARAMETRAGE_agenda_perso_desactive"] = "Persoonlijke agenda standaard ingeschakeld";
$trad["PARAMETRAGE_agenda_perso_desactive_infos"] = "Voeg een persoonlijke agenda op het creëren van een gebruiker. De kalender kan echter worden later uitgeschakeld bij het verwisselen van gebruikersaccount.";
$trad["PARAMETRAGE_libelle_module"] = "Naam van de modules in de menubalk";
$trad["PARAMETRAGE_libelle_module_masquer"] = "Verberg";
$trad["PARAMETRAGE_libelle_module_icones"] = "over elk pictogram module";
$trad["PARAMETRAGE_libelle_module_page"] = "alleen voor de huidige module";
$trad["PARAMETRAGE_tri_personnes"] = "Standaard sortering van gebruikers en contacten";
$trad["PARAMETRAGE_versions"] = "Versies";
$trad["PARAMETRAGE_version_agora_maj"] = "Updates";
$trad["PARAMETRAGE_fonction_mail_desactive"] = "PHP functie om e-mail sturen : OFF !";
$trad["PARAMETRAGE_fonction_mail_infos"] = "Sommige hosts uitschakelen van de PHP om e-mails versturen om redenen van veiligheid of verzadiging servers (SPAM)";
$trad["PARAMETRAGE_fonction_image_desactive"] = "Functie manipulatie van beelden en thumbnails (PHP GD2) : OFF !";




////	MODULE_LOG
////

// Menu principal
$trad["LOGS_nom_module"] = "Logs";
$trad["LOGS_nom_module_header"] = "Logs";
$trad["LOGS_description_module"] = "Logs - Event Log";
// Index.php
$trad["LOGS_filtre"] = "filter";
$trad["LOGS_date_heure"] = "Datum / tijd";
$trad["LOGS_espace"] = "Space";
$trad["LOGS_module"] = "Module";
$trad["LOGS_action"] = "Actie";
$trad["LOGS_utilisateur"] = "Gebruiker";
$trad["LOGS_adresse_ip"] = "IP";
$trad["LOGS_commentaire"] = "Commentaar";
$trad["LOGS_no_logs"] = "Geen logboek";
$trad["LOGS_filtre_a_partir"] = "gefilterd uit de";
$trad["LOGS_chercher"] = "Zoeken";
$trad["LOGS_chargement"] = "Loading Data";
$trad["LOGS_connexion"] = "verbinding";
$trad["LOGS_deconnexion"] = "logout";
$trad["LOGS_consult"] = "overleg";
$trad["LOGS_consult2"] = "downloaden";
$trad["LOGS_ajout"] = "toe te voegen";
$trad["LOGS_suppr"] = "verwijderen";
$trad["LOGS_modif"] = "change";




////	MODULE_ESPACE
////

// Menu principal
$trad["ESPACES_nom_module"] = "Ruimten";
$trad["ESPACES_nom_module_header"] = "Ruimten";
$trad["ESPACES_description_module"] = "Ruimten van de site";
$trad["ESPACES_description_module_infos"] = "De site (of hoofdruimte) kan worden opgedeeld in verschillende ruimten";
// Header_menu.inc.php
$trad["ESPACES_gerer_espaces"] = "Setup ruimten van de site";
$trad["ESPACES_parametrage"] = "Setup van de ruimte";
$trad["ESPACES_parametrage_infos"] = "Setup van de ruimte (omschrijving, modules, gebruikers, etc)";
// Index.php
$trad["ESPACES_confirm_suppr_espace"] = "Wissen bevestigen ? Opgelet, de aangewezen gegevens voor deze ruimte zullen definitief verwijderd worden !!";
$trad["ESPACES_espace"] = "ruimte";
$trad["ESPACES_espaces"] = "ruimten";
$trad["ESPACES_definir_acces"] = "Nader te Bepalen !";
$trad["ESPACES_modules"] = "Modules";
$trad["ESPACES_ajouter_espace"] = "Een ruimte toevoegen";
$trad["ESPACES_supprimer_espace"] = "De ruimte annuleren";
$trad["ESPACES_aucun_espace"] = "Momenteel geen enkele ruimte";
$trad["MSG_ALERTE_suppr_espace_impossible"] = "U kan geen enkele ruimte annuleren";
// Espace_edit.php
$trad["ESPACES_gestion_acces"] = "Gebruikers van deze ruimte";
$trad["ESPACES_selectionner_module"] = "U dient tenminste ÈÈn module aan te duiden";
$trad["ESPACES_modules_espace"] = "Modules van de ruimte";
$trad["ESPACES_modules_classement"] = "Ga naar de volgorde van de modules te stellen";
$trad["ESPACES_selectionner_utilisateur"] = "Enkele /alle gebruikers aanduiden of een publieke ruimte openen";
$trad["ESPACES_espace_public"] = "Publieke ruimte";
$trad["ESPACES_public_infos"] = "Biedt toegang tot mensen die geen rekeningen hebben: gasten. U kan een paswoord opgeven om de gasttoegang te beschermen.";
$trad["ESPACES_invitations_users"] = "Gebruikers kunnen uitnodigingen verzenden per e-mail";
$trad["ESPACES_invitations_users_infos"] = "Alle gebruikers kunnen e-mail verzenden uitnodigingen voor de ruimte mee";
$trad["ESPACES_tous_utilisateurs"] = "Alle gebruikers van de site";
$trad["ESPACES_utilisation"] = " Gebruiker";
$trad["ESPACES_utilisation_info"] = "Gebruiker van de ruimte: <br> Normale toegang tot de ruimte";
$trad["ESPACES_administration"] = "Administrator";
$trad["ESPACES_administration_info"] = "Administrator van de ruimte : Toegang tot wijziging van alle onderdelen van de ruimte + versturen van uitnodigingen via mail + toevoegen van gebruikers";
$trad["ESPACES_creer_agenda_espace"] = "Maak een kalender voor de ruimte";
$trad["ESPACES_creer_agenda_espace_info"] = "Dit kan handig zijn als de agenda's van de gebruikers zijn uitgeschakeld.<br>De kalender zal dezelfde naam hebben dan de ruimte en dit zal een resourcekalender zijn.";




////	MODULE_UTILISATEUR
////

// Menu principal
$trad["UTILISATEURS_nom_module"] = "Gebruikers";
$trad["UTILISATEURS_nom_module_header"] = "Gebruikers";
$trad["UTILISATEURS_description_module"] = "Gebruikers";
$trad["UTILISATEURS_ajout_utilisateurs_groupe"] = "Gebruikers kunnen ook groepen maken";
// Index.php
$trad["UTILISATEURS_utilisateurs_site"] = "Gebruikers van de site";
$trad["UTILISATEURS_gerer_utilisateurs_site"] = "het beheer van gebruikers van de site";
$trad["UTILISATEURS_utilisateurs_site_infos"] = "Alle gebruikers van de site, Alle ruimten";
$trad["UTILISATEURS_utilisateurs_espace"] = "Gebruikers van de ruimte";
$trad["UTILISATEURS_confirm_suppr_utilisateur"] = "Wissen van de gebruiker bevestigen ? Opgelet ! Alle gegevens betreffende de gebruiker zullen definitief verdwijnen!!";
$trad["UTILISATEURS_confirm_desaffecter_utilisateur"] = "Het verwijderen van de gebruiker (van deze ruimte) bevestigen ?";
$trad["UTILISATEURS_suppr_definitivement"] = "Definitief wissen";
$trad["UTILISATEURS_desaffecter"] = "Verwijderen van de ruimte";
$trad["UTILISATEURS_tous_user_affecte_espace"] = "Alle gebruikers van de site worden toegewezen aan deze ruimte : geen verwijdering mogelijk";
$trad["UTILISATEURS_utilisateur"] = "gebruiker";
$trad["UTILISATEURS_utilisateurs"] = "gebruikers";
$trad["UTILISATEURS_affecter_utilisateur"] = "Gebruiker toevoegen van de ruimte aanduiden";
$trad["UTILISATEURS_ajouter_utilisateur"] = "Gebruiker toevoegen";
$trad["UTILISATEURS_ajouter_utilisateur_site"] = "Maak een gebruiker op de site : Als standaardinstelling, de gebruiker niet is toegewezen aan een ruimte !";
$trad["UTILISATEURS_ajouter_utilisateur_espace"] = "Maak een gebruiker en toevoegen aan de huidige ruimte";
$trad["UTILISATEURS_envoi_coordonnees"] = "Gebruikersnaam en paswoord opsturen";
$trad["UTILISATEURS_envoi_coordonnees_infos"] = "Naar de gebruikers hun gebruikersnaam <br> en een nieuw paswoord verzenden";
$trad["UTILISATEURS_envoi_coordonnees_infos2"] = "Stuur mail naar nieuwe gebruikers hun gebruikersnaam en wachtwoord";
$trad["UTILISATEURS_envoi_coordonnees_confirm"] = "Opgelet : de paswoorden zullen geherinitialiseerd worden ! Toch bevestigen ?";
$trad["UTILISATEURS_mail_coordonnees"] = "Login-gegevens";
$trad["UTILISATEURS_aucun_utilisateur"] = "Momenteel geen enkele gebruiker van deze ruimte";
$trad["UTILISATEURS_derniere_connexion"] = "Laatste aanmelding";
$trad["UTILISATEURS_liste_espaces"] = "Gerbuikersruimten";
$trad["UTILISATEURS_aucun_espace"] = "geen enkele ruimte";
$trad["UTILISATEURS_admin_general"] = "Hoofdadministrator van de site";
$trad["UTILISATEURS_admin_espace"] = "Administrator van de ruimte";
$trad["UTILISATEURS_user_espace"] = "Gebruiker van de ruimte";
$trad["UTILISATEURS_user_site"] = "Gebruiker van de site";
$trad["UTILISATEURS_pas_connecte"] = "Nog niet aangemeld";
$trad["UTILISATEURS_modifier"] = "De gebruiker wijzigen";
$trad["UTILISATEURS_modifier_mon_profil"] = "Mijn profiel wijzigen";
$trad["UTILISATEURS_pas_suppr_dernier_admin_ge"] = "U mag de laatste hoofdadministrator van de site niet verwijderen !";
// groupes.php
$trad["UTILISATEURS_groupe_espace"] = "groepen gebruikers van de ruimte";
$trad["UTILISATEURS_groupe_site"] = "groepen gebruikers";
$trad["UTILISATEURS_groupe_infos"] = "Bewerk de groepen gebruikers";
$trad["UTILISATEURS_groupe_espace_infos"] = "Sommige gebruikers zijn uitgeschakeld, omdat ze niet in alle geselecteerde gebieden";
$trad["UTILISATEURS_droit_gestion_groupes"] = "Elk group kan gewijzigd worden door zijn auteur of door de hoofdadministrator";
// Utilisateur_affecter.php
$trad["UTILISATEURS_preciser_recherche"] = "Gelieve een naam, voornaam of emailadres op te geven";
$trad["UTILISATEURS_affecter_user_confirm"] = "Gebruiker van deze ruimte bevest gen ?";
$trad["UTILISATEURS_rechercher_user"] = "Een gebruiker zoeken om aan deze ruimte toe te voegen";
$trad["UTILISATEURS_tous_users_affectes"] = "Alle gebruikers zijn reeds toegewezen aan deze ruimte";
$trad["UTILISATEURS_affecter_user"] = "Een gebruiker aan deze ruimte toewijzen:";
$trad["UTILISATEURS_aucun_users_recherche"] = "Geen enkele gebruiker voor deze zoekopdracht";
// Invitation.php
$trad["UTILISATEURS_envoi_invitation"] = "Iemand uitnodigen voor deze ruimte";
$trad["UTILISATEURS_envoi_invitation_info"] = "De uitnodiging zal verstuurd worden via mail";
$trad["UTILISATEURS_objet_mail_invitation"] = "Uitnodiging van"; // ..Jean DUPOND
$trad["UTILISATEURS_admin_invite_espace"] = "nodigt u uit in "; // Jean DUPOND "nodigt u uit in deze ruimte" Mijn Ruimte
$trad["UTILISATEURS_confirmer_invitation"] = "Klik hier om de uitnodiging te bevestigen";
$trad["UTILISATEURS_invitation_a_confirmer"] = "Uitnodigingen in afwachting van bevestiging";
$trad["UTILISATEURS_id_invitation_expire"] = "De link voor uw uitnodiging is verlopen...";
$trad["UTILISATEURS_invitation_confirmer_password"] = "Hartelijk dank voor het kiezen van uw wachtwoord voor de bevestiging van uw uitnodiging";
$trad["UTILISATEURS_invitation_valide"] = "Uw uitnodiging is gevalideerd !";
// Utilisateur_edit.php & CO
$trad["UTILISATEURS_specifier_nom"] = "Gelieve een naam op te geven";
$trad["UTILISATEURS_specifier_prenom"] = "Gelieve een voornaam op te geven";
$trad["UTILISATEURS_specifier_identifiant"] = "Gelieve een gebruikersnaam op te geven";
$trad["UTILISATEURS_specifier_pass"] = "Gelieve een paswoord op te geven";
$trad["UTILISATEURS_pas_fichier_photo"] = "U hebt geen afbeelding aangeduid !";
$trad["UTILISATEURS_langues"] = "Taal";
$trad["UTILISATEURS_agenda_perso_active"] = "Persoonlijke agenda geactiveerd";
$trad["UTILISATEURS_agenda_perso_active_infos"] = "Als de persoonlijke agenda geactiveerd is, blijft die <u>altijd</u> toegankelijk voor de gebruiker, zelfs wanneer de module ëAgendaí van de ruimte niet op actief staat.";
$trad["UTILISATEURS_espace_connexion"] = "Ruimte weergegeven bij de aanmelding";
$trad["UTILISATEURS_notification_mail"] = "Een melding van aanmaak via mail versturen";
$trad["UTILISATEURS_alert_notification_mail"] = "Niet vergeten een emailadres op te geven !";
$trad["UTILISATEURS_adresses_ip"] = "Controle IP-adres";
$trad["UTILISATEURS_info_adresse_ip"] = "Indien u ÈÈn (of meerdere) IP-adressen opgeeft, zal de gebruiker zich enkel kunnen aanmelden als hij ÈÈn van de IP-adressen gebruikt.";
$trad["UTILISATEURS_ip_invalide"] = "Ongeldig IP-adres";
$trad["UTILISATEURS_identifiant_deja_present"] = "De gekozen gebruikersnaam is reeds bestaande. Gelieve een andere gebruikersnaam te kiezen.";
$trad["UTILISATEURS_mail_deja_present"] = "Het gekozen email-adres is reeds bestaande. Gelieve een ander emailadres te kiezen.";
$trad["UTILISATEURS_mail_objet_nouvel_utilisateur"] = "Nieuwe account voor Nouveau ";  // "...voor het Forum machintruc
$trad["UTILISATEURS_mail_nouvel_utilisateur"] = "Een nieuwe account werd voor u aangemaakt voor";  // idem
$trad["UTILISATEURS_mail_infos_connexion"] = "Gelieve u aan te melden met de volgende login en paswoord";
$trad["UTILISATEURS_mail_infos_connexion2"] = "Bedankt aan deze e-mail bewaren voor uw administratie.";
// Utilisateur_Messenger.php
$trad["UTILISATEURS_gestion_messenger_livecounter"] = "Instant messenger beheren";
$trad["UTILISATEURS_visibilite_messenger_livecounter"] = "Gebruikers die me online kunnen zien en me kunnen aanspreken via instant messenger ";
$trad["UTILISATEURS_aucun_utilisateur_messenger"] = "Momenteel geen enkele gebruiker";
$trad["UTILISATEURS_voir_aucun_utilisateur"] = "Geen enkele gebruiker kan mij zien";
$trad["UTILISATEURS_voir_tous_utilisateur"] = "Alle gebruikers kunnen mij zien";
$trad["UTILISATEURS_voir_certains_utilisateur"] = "Bepaalde gebruikers kunnen mij zien";




////	MODULE_TABLEAU BORD
////

// Menu principal + options du module
$trad["TABLEAU_BORD_nom_module"] = "Actualiteiten & nieuwe elementen";
$trad["TABLEAU_BORD_nom_module_header"] = "Actualiteiten";
$trad["TABLEAU_BORD_description_module"] = "Actualiteiten & nieuwe elementen";
$trad["TABLEAU_BORD_ajout_actualite_admin"] = "Alleen de beheerder kan toevoegen nieuws";
// Index.php
$trad["TABLEAU_BORD_new_elems"] = "nieuw";
$trad["TABLEAU_BORD_new_elems_bulle"] = "Onderdelen aangemaakt tijdens de aangeduide periode";
$trad["TABLEAU_BORD_new_elems_realises"] = "Onderdelen in aanmaak";
$trad["TABLEAU_BORD_new_elems_realises_bulle"] = "Gebeurtenissen en taken vanaf vandaag tot en met";
$trad["TABLEAU_BORD_plugin_connexion"] = "sinds ik laatst ben ingelogd";
$trad["TABLEAU_BORD_plugin_jour"] = "vandaag";
$trad["TABLEAU_BORD_plugin_semaine"] = "deze week";
$trad["TABLEAU_BORD_plugin_mois"] = "deze maand";
$trad["TABLEAU_BORD_autre_periode"] = "Andere periode";
$trad["TABLEAU_BORD_pas_nouveaux_elements"] = "Geen onderdelen voor de aangeduide periode";
$trad["TABLEAU_BORD_actualites"] = "Actualiteiten";
$trad["TABLEAU_BORD_actualite"] = "actualiteit";
$trad["TABLEAU_BORD_actualites"] = "actualiteiten";
$trad["TABLEAU_BORD_ajout_actualite"] = "Een actualiteit toevoegen";
$trad["TABLEAU_BORD_actualites_offline"] = "Actualiteiten in het archief";
$trad["TABLEAU_BORD_pas_actualites"] = "Momenteel geen enkele actualiteit";
// Actualite_edit.php
$trad["TABLEAU_BORD_mail_nouvelle_actualite_cree"] = "Nieuwe actualiteit aangemaakt door";
$trad["TABLEAU_BORD_ala_une"] = "Op de hoofdpagina weergeven";
$trad["TABLEAU_BORD_ala_une_info"] = "Deze actualiteit altijd als eerste weergeven";
$trad["TABLEAU_BORD_offline"] = "In het archief";
$trad["TABLEAU_BORD_date_online_auto"] = "Online gepland";
$trad["TABLEAU_BORD_date_online_auto_alerte"] = "Het nieuws is gearchiveerd in de verwachting van de online automatische";
$trad["TABLEAU_BORD_date_offline_auto"] = "geplande archivering";




////	MODULE_AGENDA
////

// Menu principal
$trad["AGENDA_nom_module"] = "Agendas";
$trad["AGENDA_nom_module_header"] = "Agendas";
$trad["AGENDA_description_module"] = "Persoonlijke agendaís en gedeelde agendaís";
$trad["AGENDA_ajout_agenda_ressource_admin"] = "Enkel de administrator mag contact-agendaís toevoegen";
$trad["AGENDA_ajout_categorie_admin"] = "Enkel de administrator mag rubrieken van een evenement toevoegen";
// Index.php
$trad["AGENDA_agendas_visibles"] = "Beschikbare agendaís (persoonlijke en contact-agendaís)";
$trad["AGENDA_afficher_tous_agendas"] = "Toon alle kalenders";
$trad["AGENDA_masquer_tous_agendas"] = "Verberg alle kalenders";
$trad["AGENDA_cocher_tous_agendas"] = "Controleren/schieten alle kalenders";
$trad["AGENDA_cocher_agendas_users"] = "Controleren/schieten gebruikers";
$trad["AGENDA_cocher_agendas_ressources"] = "Controleren/schieten middelen";
$trad["AGENDA_imprimer_agendas"] = "De agenda (ës) afdrukken";
$trad["AGENDA_imprimer_agendas_infos"] = "Print in landscape-modus";
$trad["AGENDA_ajouter_agenda_ressource"] = "Een contact-agenda toevoegen";
$trad["AGENDA_ajouter_agenda_ressource_bis"] = "Een contact-agenda toevoegen : zaal, vervoersmiddel, projector, etc ..";
$trad["AGENDA_exporter_ical"] = "Exporteer de gebeurtenissen (iCal indeling)";
$trad["AGENDA_exporter_ical_mail"] = "Exporteer de gebeurtenissen per e-mail (iCal)";
$trad["AGENDA_exporter_ical_mail2"] = "Te integreren agenda IPHONE, ANDROID, OUTLOOK, GOOGLE CALENDAR...";
$trad["AGENDA_importer_ical"] = "Import de gebeurtenissen (iCal)";
$trad["AGENDA_importer_ical_etat"] = "Staat";
$trad["AGENDA_importer_ical_deja_present"] = "Reeds aanwezig";
$trad["AGENDA_importer_ical_a_importer"] = "Te importeren";
$trad["AGENDA_suppr_anciens_evt"] = "De voorbije evenementen wissen";
$trad["AGENDA_confirm_suppr_anciens_evt"] = "Bent u zeker dat u de evenementen -die de aangeduide periode voorafgaan- definitief wil wissen?";
$trad["AGENDA_ajouter_evt_heure"] = "Een evenement toevoegen om";
$trad["AGENDA_ajouter_evt_jour"] = "Een evenement toevoegen aan deze datum";
$trad["AGENDA_evt_jour"] = "Dag";
$trad["AGENDA_evt_semaine"] = "Week";
$trad["AGENDA_evt_semaine_w"] = "Werkweek";
$trad["AGENDA_evt_mois"] = "Maand";
$trad["AGENDA_num_semaine"] = "Week"; //...5
$trad["AGENDA_voir_num_semaine"] = "Zie week nr."; //...5
$trad["AGENDA_periode_suivante"] = "Volgende periode";
$trad["AGENDA_periode_precedante"] = "Vorige periode";
$trad["AGENDA_affectations_evt"] = "Evenement in de agenda van";
$trad["AGENDA_affectations_evt_autres"] = "+ andere onzichtbare agendaís ";
$trad["AGENDA_affectations_evt_non_confirme"] = "Wachtende op een bevestiging: ";
$trad["AGENDA_evenements_proposes_pour_agenda"] = " Voorgesteld evenementen voor"; // "Videoprojecteur" / "salle de réunion" / etc.
$trad["AGENDA_evenements_proposes_mon_agenda"] = " Voorgesteld evenementen voor mijn agenda";
$trad["AGENDA_evenement_propose_par"] = "Evenement voorgesteld door";  // "ProposÈ par" M. Bidule
$trad["AGENDA_evenement_integrer"] = "Het evenement toevoegen aan de agenda ?";
$trad["AGENDA_evenement_pas_integrer"] = "Het voorstel van dit evenement wissen ?";
$trad["AGENDA_supprimer_evt_agenda"] = "Verwijder ?";
$trad["AGENDA_supprimer_evt_agendas"] = "Verwijder alle agenda's ?";
$trad["AGENDA_supprimer_evt_date"] = "Verwijder deze datum ?";
$trad["AGENDA_confirm_suppr_evt"] = "Dit evenement van de agenda wissen ?";
$trad["AGENDA_confirm_suppr_evt_tout"] = "Dit evenement van alle betrokken agendaís wissen ?";
$trad["AGENDA_confirm_suppr_evt_date"] = "Verwijder deze datum, dit evenement van alle betrokken agendaís wissen ?";
$trad["AGENDA_evt_prive"] = "PrivÈ-evenement";
$trad["AGENDA_aucun_agenda_visible"] = "Geen enkele agenda weergegeven";
$trad["AGENDA_evt_proprio"] = "Evenementen die ik aangemaakt heb";
$trad["AGENDA_evt_proprio_inaccessibles"] = "Enkel diegenen weergeven die ik aangemaakt heb voor agendaís waar ik geen toegang toe heb";
$trad["AGENDA_aucun_evt"] = "Geen enkel evenement";
$trad["AGENDA_proposer"] = "Stel een evenement";
$trad["AGENDA_synthese"] = "Synthese van dagboeken";
$trad["AGENDA_pourcent_agendas_occupes"] = "Drukke agendas";  // Agendas occupés : 2/5
$trad["AGENDA_agendas_details"] = "Zie agenda's in detail";
$trad["AGENDA_agendas_details_masquer"] = "Hide details agenda";
// Evenement.php
$trad["AGENDA_categorie"] = "categorie";
$trad["AGENDA_visibilite"] = "Zichtbaarheid";
$trad["AGENDA_visibilite_public"] = "publiek";
$trad["AGENDA_visibilite_public_cache"] = "publiek, maar verborgen details";
$trad["AGENDA_visibilite_prive"] = "privaat";
//  Agenda_edit.php
$trad["AGENDA_affichage_evt"] = "Bekijkt evenementen";
$trad["AGENDA_affichage_evt_border"] = "Grens met de kleur van de categorie";
$trad["AGENDA_affichage_evt_background"] = "Achtergrond met de kleur van de categorie";
$trad["AGENDA_plage_horaire"] = "Tijdstabel van de agenda";
// Evenement_edit.php
$trad["AGENDA_periodicite"] = "Periodiek evenement";
$trad["AGENDA_period_non"] = "EÈnmalig evenement";
$trad["AGENDA_period_jour_semaine"] = "Elke week";
$trad["AGENDA_period_jour_mois"] = "Dagen van de maand";
$trad["AGENDA_period_mois"] = "Elke maand";
$trad["AGENDA_period_mois_xdumois"] = "van de maand"; // Le 21 du mois
$trad["AGENDA_period_annee"] = "Elk jaar";
$trad["AGENDA_period_mois_xdeannee"] = "van het jaar"; // Le 21/12 de l'annÈe
$trad["AGENDA_period_date_fin"] = "Einde periodiek evenement";
$trad["AGENDA_exception_periodicite"] = "Uitzondering periodiek evenement";
$trad["AGENDA_agendas_affectations"] = "Betrokken agendaís";
$trad["AGENDA_verif_nb_agendas"] = "Gelieve tenminste ÈÈn agenda aan te duiden";
$trad["AGENDA_mail_nouvel_evenement_cree"] = "Nieuw evenement aangemaakt door";
$trad["AGENDA_input_proposer"] = "Het evenement voorstellen aan de beheerder van de agenda";
$trad["AGENDA_input_affecter"] = "Het evenement toevoegen aan de agenda";
$trad["AGENDA_info_proposer"] = "Het evenement voorstellen aangezien u geen toegang hebt tot het wijzigen van de agenda";
$trad["AGENDA_info_pas_modif"] = "Ongeldige wijziging aangezien u geen toegang hebt tot het wijzigen van de agenda";
$trad["AGENDA_visibilite_info"] = "<u>Publiek</u> : Zichtbaar voor gebruikers die het hebben gelezen toegang (or+) om de agenda's waar het evenement wordt toegewezen.<br><u>Publiek, maar de details zijn verborgen</u> : Hetzelfde, maar degenen die toegang hebben in alleen-lezen, zie het tijdschema van het evenement, maar niet de details.<br><u>Privaat</u> : Alleen zichtbaar voor diegenen die schrijf-toegang tot de agenda's waaraan hij is toegewezen.";
$trad["AGENDA_edit_limite"] = "U ben niet de auteur van het evenement en u hebt geen toegang tot het wijzigen van de betrokken agendaís : u kan enkel uw eigen agenda(ës) aanpassen.";
$trad["AGENDA_creneau_occupe"] = "De sleuf is al bezet op deze kalender :";
// Categories.php
$trad["AGENDA_gerer_categories"] = "De rubrieken van evenementen beheren";
$trad["AGENDA_categories_evt"] = "Evenementen-rubrieken";
$trad["AGENDA_droit_gestion_categories"] = "Elk rubriek kan gewijzigd worden door zijn auteur of door de hoofdadministrator";




////	MODULE_FICHIER
////

// Menu principal
$trad["FICHIER_nom_module"] = "Beheer van de bestanden";
$trad["FICHIER_nom_module_header"] = "Bestanden";
$trad["FICHIER_description_module"] = "Beheer van de bestanden";
// Index.php
$trad["FICHIER_ajouter_fichier"] = "Bestanden toevoegen";
$trad["FICHIER_ajouter_fichier_alert"] = "Server-folder ontoegankelijk voor wijzigingen ! Gelieve de administrator te contacteren";
$trad["FICHIER_telecharger_dossier"] = "Downloaden van bestanden";
$trad["FICHIER_telecharger_dossier_confirm"] = "Downloaden van bestanden bevestigen ?";
$trad["FICHIER_voir_images"] = "Afbeeldingen bekijken";
$trad["FICHIER_defiler_images"] = "automatisch scrollen van de foto's";
$trad["FICHIER_pixels"] = "pixels";
$trad["FICHIER_nb_versions_fichier"] = "versies van het bestand"; // n versies van het bestand
$trad["FICHIER_ajouter_versions_fichier"] = "nieuwe versie van het bestand toevoegen";
$trad["FICHIER_apercu"] = "Weergave"; // n versions du fichier
$trad["FICHIER_aucun_fichier"] = "Momenteel geen enkel bestand";
// fichier_edit_ajouter.php  &  Fichier_edit.php
$trad["FICHIER_limite_chaque_fichier"] = "De bestanden mogen niet groter dan Ö zijn"; // ...2 Mega Octets
$trad["FICHIER_optimiser_images"] = "De grootte van de afbeeldingen limiteren tot "; // ..1024*768 pixels
$trad["FICHIER_maj_nom"] = "De bestandsnaam wordt vervangen door de nieuwe versie";
$trad["FICHIER_ajout_plupload"] = "Meervoudige bijlage";
$trad["FICHIER_ajout_classique"] = "klassiek bijlage";
$trad["FICHIER_erreur_nb_fichiers"] = "Gelieve minder bestanden aan te duiden";
$trad["FICHIER_erreur_taille_fichier"] = "Te groot bestand";
$trad["FICHIER_erreur_non_geree"] = "Onbekende fout";
$trad["FICHIER_ajout_multiple_info"] = "Druk op de toets 'Maj' of 'Ctrl' om verschillende bestanden aan te duiden";
$trad["FICHIER_select_fichier"] = "Bestanden aanduiden";
$trad["FICHIER_annuler"] = "Annuleren";
$trad["FICHIER_selectionner_fichier"] = "Gelieve tenminste ÈÈn bestand aan te duiden";
$trad["FICHIER_nouvelle_version"] = "is reeds bestaande, een nieuwe versie werd toegevoegd.";  // mon_fichier.gif "is reeds bestaande"...
$trad["FICHIER_mail_nouveau_fichier_cree"] = "Nieuw(e) bestand(en) aangemaakt door";
$trad["FICHIER_mail_fichier_modifie"] = "Bestand gewijzigd door";
$trad["FICHIER_contenu"] = "inhoud";
// Versions_fichier.php
$trad["FICHIER_versions_de"] = "Versies van"; // versions de fichier.gif
$trad["FICHIER_confirmer_suppression_version"] = "Wissen van deze versie bevestigen ?";
// Images.php
$trad["FICHIER_info_https_flash"] = "Om het bericht niet meer te krijgen ''Wenst u de onbeveiligde onderdelen weer te geven '' :<br> <br>> klik ''Tools'' <br>> klik ''Internet-opties'' <br>> klik ''Beveiligingstoets'' <br>> Kies '' Internet Zone '' <br>> Personaliseer het niveau <br>> Activeer ''Een gemengde inhoud weergeven '' in ''Divers''  ";
$trad["FICHIER_img_precedante"] = "Vorige afbeelding (pijl links op het klavier)";
$trad["FICHIER_img_suivante"] = "Volgende afbeelding (pijl rechts op het klavier/spacebar)";
$trad["FICHIER_rotation_gauche"] = "Linksom [pijl omhoog]";
$trad["FICHIER_rotation_droite"] = "Rechtsom draaien [pijl omlaag]";
$trad["FICHIER_zoom"] = "Inzoomen / Uitzoomen";
// Video.php
$trad["FICHIER_voir_videos"] = "De videos bekijken";
$trad["FICHIER_regarder"] = "Video bekijken";
$trad["FICHIER_video_precedante"] = "Vorige video";
$trad["FICHIER_video_suivante"] = "Volgende video";
$trad["FICHIER_video_mp4_flv"] = "<a href='http://get.adobe.com/flashplayer'>Flash</a> niet geïnstalleerd.";




////	MODULE_FORUM
////

// Menu principal
$trad["FORUM_nom_module"] = "Forum";
$trad["FORUM_nom_module_header"] = "Forum";
$trad["FORUM_description_module"] = "Forum";
$trad["FORUM_ajout_sujet_admin"] = "Enkel de administrator mag onderwerpen toevoegen";
$trad["FORUM_ajout_sujet_theme"] = "Gebruikers kunnen ook kan toevoegen thema's";
// TRI
$trad["tri"]["date_dernier_message"] = "laatste bericht";
// Index.php & Sujet.php
$trad["FORUM_sujet"] = "onderwerp";
$trad["FORUM_sujets"] = "onderwerpen";
$trad["FORUM_message"] = "bericht";
$trad["FORUM_messages"] = "berichten";
$trad["FORUM_dernier_message"] = "laatste";
$trad["FORUM_ajouter_sujet"] = "Een onderwerp toevoegen";
$trad["FORUM_voir_sujet"] = "Het onderwerp bekijken";
$trad["FORUM_repondre"] = "Een bericht toevoegen";
$trad["FORUM_repondre_message"] = "Op dit bericht antwoorden";
$trad["FORUM_repondre_message_citer"] = "Antwoord en Quote dit bericht";
$trad["FORUM_aucun_sujet"] = "Momenteel geen onderwerp";
$trad["FORUM_pas_message"] = "Geen bericht voor dit onderwerp";
$trad["FORUM_aucun_message"] = "Geen enkel bericht";
$trad["FORUM_confirme_suppr_message"] = "Wissen van het bericht (en alle bijhorende berichten) bevestigen ?";
$trad["FORUM_retour_liste_sujets"] = "Terug naar de lijst van onderwerpen";
$trad["FORUM_notifier_dernier_message"] = "Op de hoogte houden via mail";
$trad["FORUM_notifier_dernier_message_info"] = "Hou mij op de hoogte wat betreft nieuwe berichten";
// Sujet_edit.php  &  Message_edit.php
$trad["FORUM_infos_droits_acces"] = "Om deel te nemen in het onderwerp, moet u beschikken over schrijf toegang";
$trad["FORUM_theme_espaces"] = "Gebieden waar het onderwerp beschikbaar is";
$trad["FORUM_mail_nouveau_sujet_cree"] = "Nieuw onderwerp aangemaakt door ";
$trad["FORUM_mail_nouveau_message_cree"] = "Nieuw bericht aangemaakt door ";
// Themes
$trad["FORUM_theme_sujet"] = "Thema";
$trad["FORUM_accueil_forum"] = "Forum Index";
$trad["FORUM_sans_theme"] = "zonder thema";
$trad["FORUM_themes_gestion"] = "De themaís beheren";
$trad["FORUM_droit_gestion_themes"] = "Elk thema kan gewijzigd worden door zijn auteur of door de hoofdadministrator";
$trad["FORUM_confirm_suppr_theme"] = "Let op! De proefpersonen die geen thema hebben! Bevestig verwijderen?";




////	MODULE_TACHE
////

// Menu principal
$trad["TACHE_nom_module"] = "Taken";
$trad["TACHE_nom_module_header"] = "Taken";
$trad["TACHE_description_module"] = "Taken";
// TRI
$trad["tri"]["priorite"] = "Prioriteit";
$trad["tri"]["avancement"] = "Vooruitgang";
$trad["tri"]["date_debut"] = "Begindatum";
$trad["tri"]["date_fin"] = "Einddatum";
// Index.php
$trad["TACHE_ajouter_tache"] = "Een taak toevoegen";
$trad["TACHE_aucune_tache"] = "Momenteel geen enkele taak";
$trad["TACHE_avancement"] = "Vooruitgang";
$trad["TACHE_avancement_moyen"] = "Matige vooruitgang";
$trad["TACHE_avancement_moyen_pondere"] = "Matige vooruitgang afhankelijk van man-day workload (takenplan per dag/man)";
$trad["TACHE_priorite"] = "Prioriteit";
$trad["TACHE_priorite1"] = "Laag";
$trad["TACHE_priorite2"] = "Middelmatig";
$trad["TACHE_priorite3"] = "Hoog";
$trad["TACHE_priorite4"] = "Kritiek";
$trad["TACHE_responsables"] = "Verantwoordelijken";
$trad["TACHE_budget_disponible"] = "Beschikbaar budget";
$trad["TACHE_budget_disponible_total"] = "Totaal beschikbaar budget";
$trad["TACHE_budget_engage"] = "Budget in gebruik";
$trad["TACHE_budget_engage_total"] = "Totaal budget in gebruik";
$trad["TACHE_charge_jour_homme"] = "Man-day workload";
$trad["TACHE_charge_jour_homme_total"] = "Totaal man-day workload";
$trad["TACHE_charge_jour_homme_info"] = "Aantal werkdagen noodzakelijk voor ÈÈn persoon om deze taak te volbrengen";
$trad["TACHE_avancement_retard"] = "Vooruitgang vertraagd";
$trad["TACHE_budget_depasse"] = "Budget overschreden";
$trad["TACHE_afficher_tout_gantt"] = "Toon alle taken";
// tache_edit.php
$trad["TACHE_mail_nouvelle_tache_cree"] = "Nieuwe taak aangemaakt door ";
$trad["TACHE_specifier_date"] = "Dank u om een datum te geven";




////	MODULE_CONTACT
////

// Menu principal
$trad["CONTACT_nom_module"] = "Contactenlijst";
$trad["CONTACT_nom_module_header"] = "Contacten";
$trad["CONTACT_description_module"] = "Contactenlijst";
// Index.php
$trad["CONTACT_ajouter_contact"] = "Toevoegen aan contactpersoon";
$trad["CONTACT_aucun_contact"] = "Momenteel geen enkele contactpersoon";
$trad["CONTACT_creer_user"] = "Een gebruiker aanmaken in deze ruimte";
$trad["CONTACT_creer_user_infos"] = "Van deze contactpersoon een gebruiker maken in deze ruimte?";
$trad["CONTACT_creer_user_confirm"] = "De gebruiker werd aangemaakt";
// Contact_edit.php
$trad["CONTACT_mail_nouveau_contact_cree"] = "Nieuwe contactpersoon aangemaakt door";




////	MODULE_LIEN
////

// Menu principal
$trad["LIEN_nom_module"] = "Favorieten";
$trad["LIEN_nom_module_header"] = "Favorieten";
$trad["LIEN_description_module"] = "Favorieten";
// Index.php
$trad["LIEN_ajouter_lien"] = "Een link toevoegen";
$trad["LIEN_aucun_lien"] = "Momenteel geen enkele link";
// lien_edit.php & dossier_edit.php
$trad["LIEN_adresse"] = "Adres";
$trad["LIEN_specifier_adresse"] = "Gelieve een adres op te geven";
$trad["LIEN_mail_nouveau_lien_cree"] = "Nieuwe link aangemaakt door";




////	MODULE_MAIL
////

// Menu principal
$trad["MAIL_nom_module"] = "Mail";
$trad["MAIL_nom_module_header"] = "Mail";
$trad["MAIL_description_module"] = "Mails versturen door één klik op de muisknop!";
// Index.php
$trad["MAIL_specifier_mail"] = "Gelieve tenminste één ontvanger aan te duiden";
$trad["MAIL_titre"] = "Titel van de mail";
$trad["MAIL_no_header_footer"] = "Zonder koptekst, noch voettekst";
$trad["MAIL_no_header_footer_infos"] = "Noch de naam van de afzender, noch de link naar de site weergeven";
$trad["MAIL_masquer_dest_message"] = "verbergen ontvangers";
$trad["MAIL_masquer_dest_message_infos"] = "Standaard worden e-mailontvangers weergegeven in het bericht.";
$trad["MAIL_accuse_reception"] = "Vraag een ontvangstbevestiging";
$trad["MAIL_accuse_reception_infos"] = "Waarschuwing: sommige e-mailprogramma's niet accepteert het ontvangstbewijs";
$trad["MAIL_fichier_joint"] = "Toegevoegd bestand";
// Historique Mail
$trad["MAIL_historique_mail"] = "Historiek van de verzonden mail";
$trad["MAIL_aucun_mail"] = "Geen mail";
$trad["MAIL_envoye_par"] = "Mail verzonden door";
$trad["MAIL_destinataires"] = "Ontvangers";
?>