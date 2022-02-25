<?php
////	PARAMETRAGE
////

// Header http
$trad["HEADER_HTTP"] = "it";
// Editeur Tinymce
$trad["EDITOR"] = "it";
// Dates formatées par PHP
setlocale(LC_TIME, "it_IT.utf8", "it_IT", "it", "italian");




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
		$tab_jours_feries[$date] = "Lunedi di Pasqua";
	}
	
	////	Les fêtes fixes
	// Jour de l'an
	$tab_jours_feries[$annee."-01-01"] = "Capodanno";
	// Liberazione
	$tab_jours_feries[$annee."-04-25"] = "Festa della Liberazione";
	// Primo Maggio
	$tab_jours_feries[$annee."-05-01"] = "Primo Maggio";
	// Festa della Rebubblica
	$tab_jours_feries[$annee."-06-02"] = "Festa della Repubblica";
	// Assomption
	$tab_jours_feries[$annee."-08-15"] = "Ferragosto";
	// Toussaint
	$tab_jours_feries[$annee."-11-01"] = "Tutti i Santi";
	// Noël
	$tab_jours_feries[$annee."-12-25"] = "Natale";
	// Santo Stefano
	$tab_jours_feries[$annee."-12-26"] = "Santo Stefano";

	////	Retourne le résultat
	return $tab_jours_feries;
}




////	COMMUN
////

// Divers
$trad["remplir_tous_champs"] = "Riempi, per favore, tutti i campi";
$trad["voir_detail"] = "Vedi in dettaglio";
$trad["elem_inaccessible"] = "Elemento inaccessibile";
$trad["champs_obligatoire"] = "Dati obbligatori";
$trad["oui"] = "si";
$trad["non"] = "no";
$trad["aucun"] = "no";
$trad["aller_page"] = "Vai alla pagina";
$trad["alphabet_filtre"] = "Filtro alfabetico";
$trad["tout"] = "Tutto";
$trad["tout_afficher"] = "Visualizza tutto";
$trad["important"] = "Importante";
$trad["afficher"] = "visualizza";
$trad["masquer"] = "nascondi";
$trad["deplacer"] = "sposta";
$trad["options"] = "Opzioni";
$trad["reinitialiser"] = "inizializza";
$trad["garder"] = "Mantieni";
$trad["par_defaut"] = "Per default";
$trad["localiser_carte"] = "Individua sulla mappa";
$trad["espace_public"] = "Spazio pubblico";
$trad["bienvenue_agora"] = "Benvenuto in Agora!";
$trad["mail_pas_valide"] = "L'indirizzo email non è valido";
$trad["element"] = "elemento";
$trad["elements"] = "elementi";
$trad["dossier"] = "cartella";
$trad["dossiers"] = "cartelle";
$trad["fermer"] = "Chiudi";
$trad["imprimer"] = "Stampa";
$trad["select_couleur"] = "Seleziona il colore";
$trad["visible_espaces"] = "Spazi dove sarà visibile";
$trad["visible_ts_espaces"] = "Visibile in tutti gli spazi";
$trad["admin_only"] = "Solo amministratori";
$trad["divers"] = "Varie";
$trad["confirm_close_lightbox"] = "Vuoi chiudere la maschera?";
// images
$trad["photo"] = "Immagine";
$trad["fond_ecran"] = "sfondo";
$trad["image_changer"] = "cambia";
$trad["pixels"] = "pixels";
// Connexion
$trad["specifier_login_password"] = "Specifica, per favore, i dati per il login e la password";
$trad["identifiant"] = "Login";
$trad["identifiant_connexion"] = "Login";
$trad["identifiant_ou_email"] = "Email / Login";
$trad["pass"] = "Password";
$trad["pass2"] = "Conferma password";
$trad["password_verif_alert"] = "La conferma password non è valida";
$trad["connexion"] = "Connessione";
$trad["connexion_auto"] = "Ricorda";
$trad["connexion_auto_info"] = "Ricorda i miei dati di login e la password per la connessione automatica";
$trad["password_oublie"] = "Non riesco a connettermi...";
$trad["password_oublie_info"] = "Invia i miei dati di login e la password al mio indirizzo email (se è stato specificato)";
$trad["acces_invite"] = "Accesso come visitatore";
$trad["espace_password_erreur"] = "Password non corretta";
$trad["version_ie"] = "Il tuo browser è troppo vecchio e non supporta tutto il codice HTML standard : aggiornalo con l'ultima vesione disponibile o integra Chrome Frame nel tuo browser";
// Affichage
$trad["type_affichage"] = "Vista";
$trad["type_affichage_liste"] = "Lista";
$trad["type_affichage_bloc"] = "Blocco";
$trad["type_affichage_arbo"] = "Albero";
// Sélectionner / Déselectionner tous les éléments
$trad["select_deselect"] = "selezionare / deselezionare";
$trad["aucun_element_selectionne"] = "Nessun elemento selezionato";
$trad["tout_selectionner"] = "Seleziona tutto";
$trad["inverser_selection"] = "Inverti la selezione";
$trad["suppr_elements"] = "Rimuovi gli alementi selezionati";
$trad["deplacer_elements"] = "Sposta in un'altra cartella";
$trad["voir_sur_carte"] = "Visualizza su una mappa";
$trad["selectionner_user"] = "Seleziona, per favore, almeno un utente";
$trad["selectionner_2users"] = "Seleziona, per favore, almeno due utenti";
$trad["selectionner_espace"] = "Seleziona, per favore, almeno uno spazio";
// Temps ("de 11h à 12h", "le 25-01-2007 à 10h30", etc.)
$trad["de"] = "di ";
$trad["a"] = "alle";
$trad["le"] = "il";
$trad["debut"] = "Inizio";
$trad["fin"] = "Fine";
$trad["separateur_horaire"] = ":";
$trad["jours"] = "giorni";
$trad["jour_1"] = "Lunedì";
$trad["jour_2"] = "Martedì";
$trad["jour_3"] = "Mercoledì";
$trad["jour_4"] = "Giovedì";
$trad["jour_5"] = "Venerdì";
$trad["jour_6"] = "Sabato";
$trad["jour_7"] = "Domenica";
$trad["mois_1"] = "gennaio";
$trad["mois_2"] = "febbraio";
$trad["mois_3"] = "marzo";
$trad["mois_4"] = "aprile";
$trad["mois_5"] = "maggio";
$trad["mois_6"] = "giugno";
$trad["mois_7"] = "luglio";
$trad["mois_8"] = "agosto";
$trad["mois_9"] = "settembre";
$trad["mois_10"] = "ottobre";
$trad["mois_11"] = "novembre";
$trad["mois_12"] = "dicembre";
$trad["mois_suivant"] = "mese successivo";
$trad["mois_precedant"] = "mese precedente";
$trad["annee_suivante"] = "anno successivo";
$trad["annee_precedante"] = "anno precedente";
$trad["aujourdhui"] = "Oggi";
$trad["aff_aujourdhui"] = "Oggi";
$trad["modif_dates_debutfin"] = "La data finale non può essere prima della data di inizio";
// Nom & Description (pour les menus d'édition principalement)
$trad["titre"] = "Titolo";
$trad["nom"] = "Nome";
$trad["description"] = "Descrizione";
$trad["specifier_titre"] = "Specifica, per favore, il titolo";
$trad["specifier_nom"] = "Specifica, per favore, il nome";
$trad["specifier_description"] = "Specifica, per favore, la descrizione";
$trad["specifier_titre_description"] = "Specifica, per favore, il titolo o la descrizione";
// Validation des formulaires
$trad["ajouter"] = " Aggiungi";
$trad["modifier"] = " Modifica";
$trad["modifier_et_acces"] = "Modifica e definisci gli accessi";
$trad["valider"] = " Convalida";
$trad["lancer"] = " Lancia";
$trad["envoyer"] = "Invia";
$trad["envoyer_a"] = "Invia a";
// Tri d'affichage. Tous les elements (dossier, tache, lien, etc...) ont par défaut une date, un auteur & une description
$trad["trie_par"] = "Ordinato per";
$trad["tri"]["date_crea"] = "data creazione";
$trad["tri"]["date_modif"] = "data modifica";
$trad["tri"]["titre"] = "titolo";
$trad["tri"]["description"] = "descrizione";
$trad["tri"]["id_utilisateur"] = "autore";
$trad["tri"]["extension"] = "tipo di file";
$trad["tri"]["taille_octet"] = "dimensione";
$trad["tri"]["nb_downloads"] = "downloads";
$trad["tri"]["civilite"] = "titolo";
$trad["tri"]["nom"] = "nome";
$trad["tri"]["prenom"] = "cognome";
$trad["tri"]["adresse"] = "indirizzo";
$trad["tri"]["codepostal"] = "Cap";
$trad["tri"]["ville"] = "città";
$trad["tri"]["pays"] = "paese";
$trad["tri"]["fonction"] = "funzione";
$trad["tri"]["societe_organisme"] = "azienda / organizzazione";
$trad["tri_ascendant"] = "Ascendente";
$trad["tri_descendant"] = "Discendente";
// Options de suppression
$trad["confirmer"] = "Confermi ?";
$trad["confirmer_suppr"] = "Confermi l'eliminazione ?";
$trad["confirmer_suppr_bis"] = "Conferma ancora, per favore !";
$trad["confirmer_suppr_dossier"] = "Confermi l'eliminazione della cartella e di tutti i dati cotenuti ? Attenzione ! Forse alcune sotto-cartelle non sono accessibili per te : saranno eliminate anche queste !! ";
$trad["supprimer"] = "Elimina";
// Visibilité d'un Objet : auteur et droits d'accès
$trad["auteur"] = "Autore : ";
$trad["cree"] = "Creato";  //...12-12-2012
$trad["cree_par"] = "Creato_da";
$trad["modif_par"] = "Ultima modifica di";
$trad["historique_modifs_element"] = "Modifica Storico";
$trad["invite"] = "visitatore";
$trad["invites"] = "visitatori";
$trad["tous"] = "tutti";
$trad["inconnu"] = "persona sconosciuta";
$trad["acces_perso"] = "Accesso personale";
$trad["lecture"] = "leggi";
$trad["lecture_infos"] = "Accesso in lettura";
$trad["ecriture_limit"] = "scrittura limitato";
$trad["ecriture_limit_infos"] = "Accesso limitato in scrittura: puoi aggiungere -ELEMENTI-, ma non puoi modificare o eliminare quelli creati da altri";
$trad["ecriture"] = "scrittura";
$trad["ecriture_infos"] = "Accesso in scrittura";
$trad["ecriture_infos_conteneur"] = "Accesso in scrittura : puoi aggiungere, modificare o eliminare tutti gli -ELEMENTI- del -CONTENITORE-";
$trad["ecriture_racine_defaut"] = "Accesso predifinito in scrittura";
$trad["ecriture_auteur_admin"] = "Solo l'autore e gli amministratori possono modificare i diritti di accesso o eliminare il -CONTENITORE-";
$trad["contenu_dossier"] = "contenuto";
$trad["aucun_acces"] = "nessun accesso";
$trad["libelles_objets"] = array("element"=>"elementi", "fichier"=>"files", "tache"=>"Compito", "lien"=>"links", "contact"=>"contatti", "evenement"=>"evento", "message"=>"messaggi", "conteneur"=>"contenitore", "dossier"=>"cartella", "agenda"=>"agenda", "sujet"=>"argomento");
// Envoi d'un mail (nouvel utilisateur, notification de création d'objet, etc...)
$trad["mail_envoye_par"] = "Inviata da";  // "Envoyé par" Mr trucmuche
$trad["mail_envoye"] = "La mail è stata correttamente inviata !";  // trucmuche@trucmuche.fr, bidule@bidule.com
$trad["mail_envoye_notif"] = "La mail di notifica è stata inviata !";
$trad["mail_pas_envoye"] = "Non è stato possibile inviare la mail..."; // idem
// Dossier & fichier
$trad["giga_octet"] = "Gb";
$trad["mega_octet"] = "Mb";
$trad["kilo_octet"] = "Kb";
$trad["octet"] = "bytes";
$trad["dossier_racine"] = "Cartella principale";
$trad["deplacer_autre_dossier"] = "Sposta in un'altra cartella";
$trad["ajouter_dossier"] = "Aggiungi una cartella";
$trad["modifier_dossier"] = "Modifica una cartella";
$trad["telecharger"] = "Scarica";
$trad["telecharge_nb"] = "Scaricata";
$trad["telecharge_nb_bis"] = "volte"; // Téléchargé 'n' fois
$trad["telecharger_dossier"] = "scarica la cartella";
$trad["espace_disque_utilise"] = "Spazio disco usato";
$trad["espace_disque_utilise_mod_fichier"] = "Spazio disco usato dal File manager";
$trad["download_alert"] = "Download non accessibile durante il giorno (dimensione del file troppo grande)";
// Infos sur une personne
$trad["civilite"] = "Titolo";
$trad["nom"] = "Nome";
$trad["prenom"] = "Cognome";
$trad["adresse"] = "Indirizzo";
$trad["codepostal"] = "CAP";
$trad["ville"] = "Città";
$trad["pays"] = "Paese";
$trad["telephone"] = "Telefono";
$trad["telmobile"] = "Cellulare";
$trad["mail"] = "Email";
$trad["fax"] = "Fax";
$trad["siteweb"] = "Sito Web";
$trad["competences"] = "Competenze";
$trad["hobbies"] = "Hobbies";
$trad["fonction"] = "Funzione";
$trad["societe_organisme"] = "Azienda / Organizzazione";
$trad["commentaire"] = "Commenti";
// Rechercher
$trad["preciser_text"] = "Il termine cercato deve essere di almeno 3 caratteri";
$trad["rechercher"] = "Cerca";
$trad["rechercher_date_crea"] = "Data creazione";
$trad["rechercher_date_crea_jour"] = "meno di un giorno";
$trad["rechercher_date_crea_semaine"] = "Meno di una settiamana";
$trad["rechercher_date_crea_mois"] = "meno di un mese";
$trad["rechercher_date_crea_annee"] = "meno di un anno";
$trad["rechercher_espace"] = "Cerca in questo spazio";
$trad["recherche_avancee"] =  "Ricerca Avanzata";
$trad["recherche_avancee_mots_certains"] =  "qualsiasi parola";
$trad["recherche_avancee_mots_tous"] =  "tutte le parole";
$trad["recherche_avancee_expression_exacte"] =  "frase esatta";
$trad["recherche_avancee_champs"] =  "search fields";
$trad["recherche_avancee_pas_concordance"] =  "Moduli e campi selezionati non corrispondono. Grazie a rivedere il loro accordo nella ricerca avanzata.";
$trad["mots_cles"] = "Parola chiave";
$trad["liste_modules"] = "Moduli";
$trad["liste_champs"] = "Campi";
$trad["liste_champs_elements"] = "Elements involved";
$trad["aucun_resultat"] = "nessun risultato";
// Importer ou Exporter : Contact ou Utilisateurs
$trad["exporter"] = "Esporta";
$trad["importer"] = "Importa";
$trad["export_import_users"] = "utenti";
$trad["export_import_contacts"] = "contatti";
$trad["export_format"] = "formato";
$trad["contact_separateur"] = "separatore";
$trad["contact_delimiteur"] = "delimitatore";
$trad["specifier_fichier"] = "Specifica, per favore, un file";
$trad["extension_fichier"] = "Il tipo di file non è valido. Deve essere del tipo";
$trad["format_fichier_invalide"] = "Il formato del file non corrisponde al tipo selezionato";
$trad["import_infos"] = "Seleziona i campi di destinazione, grazie al menù a discesa in ogni colonna";
$trad["import_infos_contact"] = "I contatti saranno aggiunti di default allo spazio corrente.";
$trad["import_infos_user"] = "Se il nome utente e la password non sono indicati, questi saranno generati automaticamente.";
$trad["import_alert"] = "Seleziona, per favore, le colonne del nome";
$trad["import_alert2"] = "Seleziona, per favore, almeno un contatto da importare";
$trad["import_alert3"] = "questo campo è già stato selezionato in un'altra colonna (ogni campo può essere selezionato solo una volta)";
// Captcha
$trad["captcha"] = "Identificazione visuale";
$trad["captcha_info"] = "Ricopia, per favore, i 4 caratteri per identificarti";
$trad["captcha_alert_specifier"] = "Thank you to specify the visual identification";
$trad["captcha_alert_erronee"] = "I caratteri inseriti non sono validi";
// Gestion des inscriptions d'utilisateur
$trad["inscription_users"] = "register on the site";
$trad["inscription_users_info"] = "create a new user account (validated by an administrator)";
$trad["inscription_users_espace"] = "sottoscrivere spazio";
$trad["inscription_users_enregistre"] = "Your subscription has been registered : it will be validated as soon as possible by the administrator of the space";
$trad["inscription_users_option_espace"] = "Allow visitors to register on the space";
$trad["inscription_users_option_espace_info"] = "The inscription is on the homepage of the site. Registration must then be validated by the administrator of the space.";
$trad["inscription_users_validation"] = "Validate user entries";
$trad["inscription_users_valider"] = "validate";
$trad["inscription_users_invalider"] = "invalidate";
$trad["inscription_users_valider_mail"] = "Your account has successfully been validated on";
$trad["inscription_users_invalider_mail"] = "Your account has not been validated on";
// Connexion à un serveur LDAP
$trad["ldap_connexion_serveur"] = "Connessione a un server LDAP";
$trad["ldap_server"] = "indirizzo del server";
$trad["ldap_server_port"] = "porta del server";
$trad["ldap_server_port_infos"] = "''389'' per impostazione predefinita";
$trad["ldap_admin_login"] = "Stringa di connessione per admin";
$trad["ldap_admin_login_infos"] = "per esempio ''uid=admin,ou=my_company''";
$trad["ldap_admin_pass"] = "Password di admin";
$trad["ldap_groupe_dn"] = "Gruppo / base DN";
$trad["ldap_groupe_dn_infos"] = "Ubicazione di utenti di directory.<br> per esempio ''ou=users,o=my_company''";
$trad["ldap_connexion_erreur"] = "Errore di connessione al server LDAP !";
$trad["ldap_import_infos"] = "Mostra la configurazione del server LDAP nel modulo Amministrazione.";
$trad["ldap_crea_auto_users"] = "Creazione automatica degli utenti dopo l'identificazione LDAP";
$trad["ldap_crea_auto_users_infos"] = "Creazione automatica di un utente se non è presente l'Agorà, ma presente sul server LDAP : sarà assegnato alle aree accessibili a ''tutti gli utenti del sito''.<br>In caso contrario, l'utente. non verrà creato.";
$trad["ldap_pass_cryptage"] = "La crittografia delle password sul server LDAP";
$trad["ldap_effacer_params"] = "Elimina impostazione LDAP ?";
$trad["ldap_pas_module_php"] = "Modulo PHP per la connessione a un server LDAP non è installato!";




////	DIVERS
////

// Messages d'alerte / d'erreur
$trad["MSG_ALERTE_identification"] = "Nome utente o password non valide";
$trad["MSG_ALERTE_dejapresent"] = "Questo account è attualmete utilizzato da un altro idirizzo IP... (Un account può essere utilizzato da una sola persona alla volta)";
$trad["MSG_ALERTE_adresseip"] = "L'indirizzo IP che stai utilizzando non è autorizzato per questo account";
$trad["MSG_ALERTE_pasaccesite"] = "L'accesso al sito non ti è stato autorizzato, al momento, probabilmente sei assegnato ad un altro spazio";
$trad["MSG_ALERTE_captcha"] = "Il codice immesso non è valido";
$trad["MSG_ALERTE_acces_fichier"] = "Il file non è accessibile";
$trad["MSG_ALERTE_acces_dossier"] = "La cartella non è accessibile";
$trad["MSG_ALERTE_espace_disque"] = "Lo spazio a disposizione per il salvataggio dei tuoi file non è sufficiente, non puoi pertanto aggiungere altri file";
$trad["MSG_ALERTE_taille_fichier"] = "La dimensione del file è troppo grande";
$trad["MSG_ALERTE_type_version"] = "Il tipo di file è differente dall'originale";
$trad["MSG_ALERTE_type_interdit"] = "Tipo di file non permesso";
$trad["MSG_ALERTE_deplacement_dossier"] = "Non puoi spostare la cartella qui dentro..!";
$trad["MSG_ALERTE_nom_dossier"] = "Una cartella con lo stesso nome esiste già. Confermare lo stesso?";
$trad["MSG_ALERTE_nom_fichier"] = "Un file con lo stesso nome esiste già e non sarà sostituito";
$trad["MSG_ALERTE_chmod_stock_fichiers"] = "Il file manager non è accessibile in scrittura. Imposta, per favore, i permessi della cartella ''stock_fichiers '' a 775 (accesso in lettura e scrittura al proprietario e al gruppo)";
$trad["MSG_ALERTE_nb_users"] = "Non puoi aggiungere nuovi utenti: il limite è impostato a "; // "...limité à" 10
$trad["MSG_ALERTE_miseajourconfig"] = "Il file di configurazione (config.inc.php) non è scrivibile: impossibile completare l'aggiornamento!";
$trad["MSG_ALERTE_miseajour"] = "Aggiornamento completato. Riavvia il tuo browser prima di riconnetterti";
$trad["MSG_ALERTE_user_existdeja"] = "Il nome utente è già esistente : l'utente non può essere creato";
$trad["MSG_ALERTE_temps_session"] = "La tua sessione è scaduta, riconnettiti, per favore";
$trad["MSG_ALERTE_specifier_nombre"] = "Specifica, per favore, un numero";
// header menu
$trad["HEADER_MENU_espace_administration"] = "Amministrazione";
$trad["HEADER_MENU_espaces_dispo"] = "Spazi disponibili";
$trad["HEADER_MENU_espace_acces_administration"] = "(administration access)";
$trad["HEADER_MENU_affichage_elem"] = "Visualizza";
$trad["HEADER_MENU_affichage_normal"] = "Elementi che sono assegnati a me (visualizzazione normale)";
$trad["HEADER_MENU_affichage_normal_infos"] = "Questa è la visualizzazione normale / default";
$trad["HEADER_MENU_affichage_auteur"] = "Elementi che ho creato";
$trad["HEADER_MENU_affichage_auteur_infos"] = "Visualizza solo quello che ho creato";
$trad["HEADER_MENU_affichage_tout"] = "Tutti gli elementi dello spazio (amministratore)";
$trad["HEADER_MENU_affichage_tout_infos"] = "Per gli amministratori dello spazio: visualizza tutti gli elementi dello spazio, anche quelli che non sono assegnati per l'amministratore !";
$trad["HEADER_MENU_recherche_elem"] = "Cerca un elemento nello spazio";
$trad["HEADER_MENU_sortie_agora"] = "Esci da Agora";
$trad["HEADER_MENU_raccourcis"] = "Scorciatoie";
$trad["HEADER_MENU_seul_utilisateur_connecte"] = "Attualmente sul sito";
$trad["HEADER_MENU_en_ligne"] = "Online :";
$trad["HEADER_MENU_connecte_a"] = "connesso al sito alle";   // Mr bidule truc "connecté au site à" 12:45
$trad["HEADER_MENU_messenger"] = "Messaggeria istantanea";
$trad["HEADER_MENU_envoye_a"] = "Invia a";
$trad["HEADER_MENU_ajouter_message"] = "Aggiungi un messaggio";
$trad["HEADER_MENU_specifier_message"] = "Specifica, per favore, un messaggio";
$trad["HEADER_MENU_enregistrer_conversation"] = "Registra questa conversazione";
// Footer
$trad["FOOTER_page_generee"] = "pagina generata in";
// Password_oublie
$trad["PASS_OUBLIE_preciser_mail"] = "Inserisci il tuo indirizzo email per ricevere i tuoi dati per il login e la password";
$trad["PASS_OUBLIE_mail_inexistant"] = "L'indirizzo email specificato non è presente nel nostro database";
$trad["PASS_OUBLIE_mail_objet"] = "Connessione al tuo spazio";
$trad["PASS_OUBLIE_mail_contenu"] = "Il tuo nome utente";
$trad["PASS_OUBLIE_mail_contenu_bis"] = "Clicca qui per resettare la tua password";
$trad["PASS_OUBLIE_prompt_changer_pass"] = "Specifica, per favore, la tua nuova password";
$trad["PASS_OUBLIE_id_newpassword_expire"] = "Il link per rigenerare la password è scaduto ... riavvia, per favore, la procedura";
$trad["PASS_OUBLIE_password_reinitialise"] = "La tua nuova password è stata registrata !";
// menu_edit_objet 
$trad["EDIT_OBJET_alert_aucune_selection"] = "Seleziona, per favore, almeno un utente per lo spazio";
$trad["EDIT_OBJET_alert_pas_acces_perso"] = "Non sei stato assegnato all'elemento. Confermare ugualmente ?";
$trad["EDIT_OBJET_alert_ecriture"] = "Ci deve essere almeno un utente con uno spazio assegnato in scrittura";
$trad["EDIT_OBJET_alert_ecriture_limite_defaut"] = "Attenzione! Con l'accesso in scrittura tutti i messaggi saranno eliminati ! \\n\\nE' raccomandato usare un accesso in scrittura limitato";
$trad["EDIT_OBJET_alert_invite"] = "Specifica, per favore, un nome o un nome utente";
$trad["EDIT_OBJET_droit_acces"] = "Diritti di accesso";
$trad["EDIT_OBJET_espace_pas_module"] = "Il modulo attuale non è ancora stato aggiunto in questo spazio";
$trad["EDIT_OBJET_tous_utilisateurs"] = "Tutti gli utenti";
$trad["EDIT_OBJET_tous_utilisateurs_espaces"] = "Tutti gli spazi";
$trad["EDIT_OBJET_espace_invites"] = "Visitatori di questo spazio pubblico";
$trad["EDIT_OBJET_aucun_users"] = "Attualmente non ci sono utenti in qusto spazio";
$trad["EDIT_OBJET_invite"] = "Il tuo nome utente";
$trad["EDIT_OBJET_admin_espace"] = "Amministratore dello spazio : <br>Accesso in scrittura a tutti gli elementi dello spazio";
$trad["EDIT_OBJET_tous_espaces"] = "Visualizza tutti i miei spazi";
$trad["EDIT_OBJET_notif_mail"] = "Notifica per email";
$trad["EDIT_OBJET_notif_mail_joindre_fichiers"] = "Allegare file alla notifica";
$trad["EDIT_OBJET_notif_mail_info"] = "Invia una notifica per email alle persone che hanno accesso all'elemento";
$trad["EDIT_OBJET_notif_mail_selection"] = "Seleziona manualmente i destinatari della notifica";
$trad["EDIT_OBJET_notif_tous_users"] = "Visualizza altri utenti";
$trad["EDIT_OBJET_droits_ss_dossiers"] = "Assegna gli stessi diritti alle sottocartelle";
$trad["EDIT_OBJET_raccourci"] = "Scorciatoia";
$trad["EDIT_OBJET_raccourci_info"] = "Inserisci una scorciatoia nel menu princiaple";
$trad["EDIT_OBJET_fichier_joint"] = "Aggiungi immagini, video o altri file";
$trad["EDIT_OBJET_inserer_fichier"] = "Visualizza la descrizione";
$trad["EDIT_OBJET_inserer_fichier_info"] = "Visualizza immagine / video / player mp3 ... nella descrizione sopra. L'inserimento viene effettuato dopo la validazione dei form.";
$trad["EDIT_OBJET_inserer_fichier_alert"] = "Clicca su ''Inserisci'' per aggiungere delle immagini nel testo / descrizione";
$trad["EDIT_OBJET_demande_a_confirmer"] = "La tua richiesta è stata correttamente registrata. Provvederemo presto alla verifica e la eventuale conferma";
// Formulaire d'installation
$trad["INSTALL_connexion_bdd"] = "Connessione al database";
$trad["INSTALL_db_host"] = "Nome host del server del database";
$trad["INSTALL_db_name"] = "Nome del database";
$trad["INSTALL_db_name_info"] = "Attenzione !!! <br> Se il database di Agora esiste già, questo verrà sostituito";
$trad["INSTALL_db_login"] = "Nome utente";
$trad["INSTALL_db_password"] = "Password";
$trad["INSTALL_login_password_info"] = "Per connettersi come amministratore";
$trad["INSTALL_config_admin"] = "Informazioni sull'amministratore ";
$trad["INSTALL_config_espace"] = "Configurazione dello spazio principale";
$trad["INSTALL_erreur_acces_mysql"] = "No connection to the MySQL database";
$trad["INSTALL_erreur_acces_bdd"] = "La connessione al database non è stata effettuata, vuoi continuare ugualmente ?";
$trad["INSTALL_erreur_agora_existe"] = "Il databese di Agora è già presente sul server ! Confermi l'installazione e la sostituzione delle tabelle?";
$trad["INSTALL_confirm_version_php"] = "Agora-Project richiede PHP nella versione 4.3 o superiore, vuoi continuare ugualmente ?";
$trad["INSTALL_confirm_version_mysql"] = "Agora-Project richiede MySQl nella versione 4.2 o superiore, vuoi continuare ugualmente ?";
$trad["INSTALL_confirm_install"] = "Confermi l'installazione ?";
$trad["INSTALL_install_ok"] = "Agora-Project è stato correttamente installato ! Per ragioni di sicurezza, rimuovi la cartella 'install' subito, prima di qualsiasi utilizzo";




////	MODULE_PARAMETRAGE
////

// Menu principal
$trad["PARAMETRAGE_nom_module"] = "Impostazioni";
$trad["PARAMETRAGE_nom_module_header"] = "Impostazioni";
$trad["PARAMETRAGE_description_module"] = "Impostazioni del sito";
// Index.php
$trad["PARAMETRAGE_sav"] = "Backup del database e dei files";
$trad["PARAMETRAGE_sav_alert"] = "La creazione del file di backup può prendere diversi minuti ... e per il download ne possono occorrere ancora di più.";
$trad["PARAMETRAGE_sav_bdd"] = "Backup del database";
$trad["PARAMETRAGE_adresse_web_invalide"] = "Attenzione, l'indirizzo web inserito non è valido : deve iniziare con HTTP:// ";
$trad["PARAMETRAGE_espace_disque_invalide"] = "Indica, come impostazione dello spazio su disco, un numero intero";
$trad["PARAMETRAGE_confirmez_modification_site"] = "Confermi le modifiche ?";
$trad["PARAMETRAGE_nom_site"] = "Nome del sito";
$trad["PARAMETRAGE_adresse_web"] = "Indirizzo del sito";
$trad["PARAMETRAGE_footer_html"] = "Codice html per il piè di pagina";
$trad["PARAMETRAGE_footer_html_info"] = "Utile per includere il codice per le statistiche, per esempio";
$trad["PARAMETRAGE_langues"] = "Lingua di default";
$trad["PARAMETRAGE_timezone"] = "Timezone";
$trad["PARAMETRAGE_nom_espace"] = "Nome dello spazio principale";
$trad["PARAMETRAGE_limite_espace_disque"] = "Spazio disponibile per la conservazione dei files";
$trad["PARAMETRAGE_logs_jours_conservation"] = "Durata di conservazione dei registri";
$trad["PARAMETRAGE_mode_edition"] = "Modifica oggetto";
$trad["PARAMETRAGE_edition_popup"] = "in un popup";
$trad["PARAMETRAGE_edition_iframe"] = "in un iframe";
$trad["PARAMETRAGE_skin"] = "Colore dell'interfaccia (sfondo degli elementi, menù, ecc.)";
$trad["PARAMETRAGE_noir"] = "Nero";
$trad["PARAMETRAGE_blanc"] = "Bianco";
$trad["PARAMETRAGE_erreur_fond_ecran_logo"] = "Lo sfondo e il logo devono essere delle immagini jpg o png";
$trad["PARAMETRAGE_suppr_fond_ecran"] = "Eliminare questo sfondo ?";
$trad["PARAMETRAGE_logo_footer"] = "Logo in fondo alla pagina";
$trad["PARAMETRAGE_logo_footer_url"] = "URL";
$trad["PARAMETRAGE_editeur_text_mode"] = "Modo dell'editor di testo (TinyMCE)";
$trad["PARAMETRAGE_editeur_text_minimal"] = "Minimale";
$trad["PARAMETRAGE_editeur_text_complet"] = "Completo (+ tabelle + media + incolla da word)";
$trad["PARAMETRAGE_messenger_desactive"] = "Chat interna al sito abilitata";
$trad["PARAMETRAGE_agenda_perso_desactive"] = "Calendari personali abilitate di default";
$trad["PARAMETRAGE_agenda_perso_desactive_infos"] = "Aggiungere un calendario personale alla creazione di un utente. Il calendario può tuttavia essere disabilitata successivamente, quando si cambia l'account utente.";
$trad["PARAMETRAGE_libelle_module"] = "Visualizza il nome del modulo nel menù principale";
$trad["PARAMETRAGE_libelle_module_masquer"] = "nascosto";
$trad["PARAMETRAGE_libelle_module_icones"] = "sopra l'icona di ogni modulo";
$trad["PARAMETRAGE_libelle_module_page"] = "solo il nome del modulo corrente";
$trad["PARAMETRAGE_tri_personnes"] = "Ordina utenti e contatti";
$trad["PARAMETRAGE_versions"] = "Versione";
$trad["PARAMETRAGE_version_agora_maj"] = "aggiornato";
$trad["PARAMETRAGE_fonction_mail_desactive"] = "La funzione di PHP per inviare le email è disabilitata !";
$trad["PARAMETRAGE_fonction_mail_infos"] = "Alcuni hosters disabilitano la funzione di PHP per inviare le mail per motivi di sicurezza e per limitare lo spam";
$trad["PARAMETRAGE_fonction_image_desactive"] = "La funzione per manipolare le immagini e creare le miniature (PHP GD2) è disabilitata !";




////	MODULE_LOG
////

// Menu principal
$trad["LOGS_nom_module"] = "Logs";
$trad["LOGS_nom_module_header"] = "Logs";
$trad["LOGS_description_module"] = "Log degli eventi";
// Index.php
$trad["LOGS_filtre"] = "filtro";
$trad["LOGS_date_heure"] = "Data / Orario";
$trad["LOGS_espace"] = "Area";
$trad["LOGS_module"] = "Modulo";
$trad["LOGS_action"] = "Azione";
$trad["LOGS_utilisateur"] = "Utente";
$trad["LOGS_adresse_ip"] = "IP";
$trad["LOGS_commentaire"] = "commento";
$trad["LOGS_no_logs"] = "no log";
$trad["LOGS_filtre_a_partir"] = "filtrato da";
$trad["LOGS_chercher"] = "cerca";
$trad["LOGS_chargement"] = "Caricamento dati";
$trad["LOGS_connexion"] = "connessione";
$trad["LOGS_deconnexion"] = "esci";
$trad["LOGS_consult"] = "consult";
$trad["LOGS_consult2"] = "scarica";
$trad["LOGS_ajout"] = "aggiungi";
$trad["LOGS_suppr"] = "elimina";
$trad["LOGS_modif"] = "modifica";




////	MODULE_ESPACE
////

// Menu principal
$trad["ESPACES_nom_module"] = "Aree";
$trad["ESPACES_nom_module_header"] = "Aree";
$trad["ESPACES_description_module"] = "Aree del sito";
$trad["ESPACES_description_module_infos"] = "Il sito (o l'area principale) può essere diviso in più aree";
// Header_menu.inc.php
$trad["ESPACES_gerer_espaces"] = "Amministra le aree del sito";
$trad["ESPACES_parametrage"] = "Impostazioni delle aree";
$trad["ESPACES_parametrage_infos"] = "Impostazioni delle aree (descrizioni, moduli, utenti, ecc.)";
// Index.php
$trad["ESPACES_confirm_suppr_espace"] = "Confermi l'eliminazione ? Attenzione, i dati di questo spazio verranno definitivamente persi !!";
$trad["ESPACES_espace"] = "area";
$trad["ESPACES_espaces"] = "aree";
$trad["ESPACES_definir_acces"] = "Da definire !";
$trad["ESPACES_modules"] = "Moduli";
$trad["ESPACES_ajouter_espace"] = "Aggiungi un'area";
$trad["ESPACES_supprimer_espace"] = "Elimina un'area";
$trad["ESPACES_aucun_espace"] = "Nessuno spazio, al momento";
$trad["MSG_ALERTE_suppr_espace_impossible"] = "Non puoi eliminare questo spazio";
// Espace_edit.php
$trad["ESPACES_gestion_acces"] = "Utenti assegnati allo spazio";
$trad["ESPACES_selectionner_module"] = "Seleziona, per favore, almeno un modulo";
$trad["ESPACES_modules_espace"] = "Moduli in questo spazio";
$trad["ESPACES_modules_classement"] = "Classificazione dei moduli";
$trad["ESPACES_selectionner_utilisateur"] = "Selezionare solo alcuni utente, tutti gli utente o apreire lo spazio al pubblico";
$trad["ESPACES_espace_public"] = "Area pubblica";
$trad["ESPACES_public_infos"] = "Fornisce l'accesso alle persone che non dispongono di account sul sito : ''ospiti''. Possibilità di specificare una password per proteggere l'accesso.";
$trad["ESPACES_invitations_users"] = "Gli utenti possono inviare inviti via e-mail";
$trad["ESPACES_invitations_users_infos"] = "Tutti gli utenti possono inviare inviti via email a partecipare allo spazio";
$trad["ESPACES_tous_utilisateurs"] = "Tutti gli utenti del sito";
$trad["ESPACES_utilisation"] = " utente";
$trad["ESPACES_utilisation_info"] = "Normale accesso allo spazio";
$trad["ESPACES_administration"] = "Amministratore";
$trad["ESPACES_administration_info"] = "Amministratore dell'area : accesso in scrittura a tutti gli elementi dell'area + possibilità di inviare inviti per email + possibilità di aggiungere utenti";
$trad["ESPACES_creer_agenda_espace"] = "Creare un calendario per lo spazio";
$trad["ESPACES_creer_agenda_espace_info"] = "Questo può essere utile se i calendari degli utenti sono disabilitati.<br>Il calendario avrà lo stesso nome dello spazio e questo sarà un calendario di risorse.";




////	MODULE_UTILISATEUR
////

// Menu principal
$trad["UTILISATEURS_nom_module"] = "Utente";
$trad["UTILISATEURS_nom_module_header"] = "Utenti";
$trad["UTILISATEURS_description_module"] = "Utenti";
$trad["UTILISATEURS_ajout_utilisateurs_groupe"] = "Gli utenti possono anche creare gruppi";
// Index.php
$trad["UTILISATEURS_utilisateurs_site"] = "Utenti del sito";
$trad["UTILISATEURS_gerer_utilisateurs_site"] = "Amministra gli utenti del sito";
$trad["UTILISATEURS_utilisateurs_site_infos"] = "Tutti gli utenti del sito, di tutti gli spazi";
$trad["UTILISATEURS_utilisateurs_espace"] = "Utenti dello spazio";
$trad["UTILISATEURS_confirm_suppr_utilisateur"] = "Confermi l'eliminazione dell'utente? Attenzione ! Tutti i dati relativi saranno definitivamnte eliminati e persi !!";
$trad["UTILISATEURS_confirm_desaffecter_utilisateur"] = "Confirmi la revoca dell'assegnazione di questo utente a questo spazio ?";
$trad["UTILISATEURS_suppr_definitivement"] = "Elimina definitivamente";
$trad["UTILISATEURS_desaffecter"] = "Annulla l'assegnazione allo spazio";
$trad["UTILISATEURS_tous_user_affecte_espace"] = "Tutti gli utenti del sito sono interessati da questo spazio : non è possibile una revoca";
$trad["UTILISATEURS_utilisateur"] = "utente";
$trad["UTILISATEURS_utilisateurs"] = "utenti";
$trad["UTILISATEURS_affecter_utilisateur"] = "Aggiungi un utente esistente a questo spazio";
$trad["UTILISATEURS_ajouter_utilisateur"] = "Aggiungi utente";
$trad["UTILISATEURS_ajouter_utilisateur_site"] = "Crea un utente del sito: per default, sarà assegnato ad ogni spazio !";
$trad["UTILISATEURS_ajouter_utilisateur_espace"] = "Crea un utente e aggiungilo a questo spazio";
$trad["UTILISATEURS_envoi_coordonnees"] = "Invia nome utente e password";
$trad["UTILISATEURS_envoi_coordonnees_infos"] = "Invia agli utenti (per mail) il loro nome utente <br> e una nuova password";
$trad["UTILISATEURS_envoi_coordonnees_infos2"] = "Inviare una mail per i nuovi utenti il loro nome utente e password";
$trad["UTILISATEURS_envoi_coordonnees_confirm"] = "La passwords sarà cambiata ! continuo ?";
$trad["UTILISATEURS_mail_coordonnees"] = "nome utente e password";
$trad["UTILISATEURS_aucun_utilisateur"] = "Nessun utente è assegnato a questo spazio, al momento";
$trad["UTILISATEURS_derniere_connexion"] = "Ultima connessione";
$trad["UTILISATEURS_liste_espaces"] = "Spazi degli utenti";
$trad["UTILISATEURS_aucun_espace"] = "Non ci sono spazi";
$trad["UTILISATEURS_admin_general"] = "Amministratore generale del sito";
$trad["UTILISATEURS_admin_espace"] = "Amministratore dello spazio";
$trad["UTILISATEURS_user_espace"] = "Utente dello spazio";
$trad["UTILISATEURS_user_site"] = "Utente del sito";
$trad["UTILISATEURS_pas_connecte"] = "Non ancora connesso";
$trad["UTILISATEURS_modifier"] = "Modifica utente";
$trad["UTILISATEURS_modifier_mon_profil"] = "Modifica il mio profilo";
$trad["UTILISATEURS_pas_suppr_dernier_admin_ge"] = "Non puoi eliminare l'ultimo ammistratore generale del sito !";
// Invitation.php
$trad["UTILISATEURS_envoi_invitation"] = "Invita qualcuno a visitare lo spazio";
$trad["UTILISATEURS_envoi_invitation_info"] = "L'invito sarà inviato per mail";
$trad["UTILISATEURS_objet_mail_invitation"] = "Invito per "; // ..Jean DUPOND
$trad["UTILISATEURS_admin_invite_espace"] = "ti invito ad unirti sullo spazio "; // Jean DUPOND "vous invite Ã  rejoindre l'espace" Mon Espace
$trad["UTILISATEURS_confirmer_invitation"] = "Clicca qui per confermare il tuo invito";
$trad["UTILISATEURS_invitation_a_confirmer"] = "Inviti non ancora confermato";
$trad["UTILISATEURS_id_invitation_expire"] = "Il link del tuo invito è scaduto ...";
$trad["UTILISATEURS_invitation_confirmer_password"] = "Scegli, per favore, la tua password prima di confermare l'invito";
$trad["UTILISATEURS_invitation_valide"] = "Il tuo invito è stato convalidato !";
// groupes.php
$trad["UTILISATEURS_groupe_espace"] = "gruppi di utenti dello spazio";
$trad["UTILISATEURS_groupe_site"] = "gruppi di utenti";
$trad["UTILISATEURS_groupe_infos"] = "modifica i gruppi di utenti";
$trad["UTILISATEURS_groupe_espace_infos"] = "Gli utenti abilitati hanno accesso a tutti gli spazi selezionati (gli altri sono disabilitati)";
$trad["UTILISATEURS_droit_gestion_groupes"] = "I gruppi possono essere modificati dai suoi autori o dall'amministratore generale";
// Utilisateur_affecter.php
$trad["UTILISATEURS_preciser_recherche"] = "Specifica, per favore, un nome, un cognome o un indirizzo email";
$trad["UTILISATEURS_affecter_user_confirm"] = "Confermi l'assegnazione dell'utente allo spazio?";
$trad["UTILISATEURS_rechercher_user"] = "Cerca un utente per aggiungerlo a questo spazio";
$trad["UTILISATEURS_tous_users_affectes"] = "Tutti gli utenti del sito sono già assegnati a questo spazio";
$trad["UTILISATEURS_affecter_user"] = "Aggiungi un utente allo spazio :";
$trad["UTILISATEURS_aucun_users_recherche"] = "Nessun utente trovato per questa ricerca";
// Utilisateur_edit.php  & CO
$trad["UTILISATEURS_specifier_nom"] = "Specifica, per favore, un nome";
$trad["UTILISATEURS_specifier_prenom"] = "Specifica, per favore, un nome";
$trad["UTILISATEURS_specifier_identifiant"] = "Specifica, per favore, un nome per il login";
$trad["UTILISATEURS_specifier_pass"] = "Specifica, per favore, una password";
$trad["UTILISATEURS_pas_fichier_photo"] = "Non è stata specificata un'immagine !";
$trad["UTILISATEURS_langues"] = "Lingua";
$trad["UTILISATEURS_agenda_perso_active"] = "Agenda personale abilitata";
$trad["UTILISATEURS_agenda_perso_active_infos"] = "Se abilitata, l'agenda personale è <u>sempre</u> visibile per l'utente, anche se il modulo Agenda non è abilitato nello spazio";
$trad["UTILISATEURS_espace_connexion"] = "Spazio visualizzato dopo la connessione";
$trad["UTILISATEURS_notification_mail"] = "Invia una notifica di creazione per email";
$trad["UTILISATEURS_alert_notification_mail"] = "Specifica, per favore, un indirizzo email!";
$trad["UTILISATEURS_adresses_ip"] = "list of control IP addresses";
$trad["UTILISATEURS_info_adresse_ip"] = "When you specify one (or several) control IP addresses, the user can only connect if it uses the specified IP addresses";
$trad["UTILISATEURS_ip_invalide"] = "Indirizzo IP non valido";
$trad["UTILISATEURS_identifiant_deja_present"] = "Il nome utente esiste già. Grazie di specificare un altro";
$trad["UTILISATEURS_mail_deja_present"] = "L'indirizzo e-mail esiste già. Grazie di specificare un diverso";
$trad["UTILISATEURS_mail_objet_nouvel_utilisateur"] = "Benvenuti a ";  //.."mon-espace"
$trad["UTILISATEURS_mail_nouvel_utilisateur"] = "Un nuovo account è stato assegnato a voi in ";  //.."mon-espace"
$trad["UTILISATEURS_mail_infos_connexion"] = "Connettiti con le seguenti coordinate";
$trad["UTILISATEURS_mail_infos_connexion2"] = "Grazie mille conservare questa e-mail per i record.";
// Utilisateur_Messenger.php
$trad["UTILISATEURS_gestion_messenger_livecounter"] = "Gestisci la messaggistica istantanea";
$trad["UTILISATEURS_visibilite_messenger_livecounter"] = "Utenti che possono vedermi online sulla messaggistica istantanea";
$trad["UTILISATEURS_aucun_utilisateur_messenger"] = "Non ci sono utenti, al momento";
$trad["UTILISATEURS_voir_aucun_utilisateur"] = "Nessun utente può vedermi";
$trad["UTILISATEURS_voir_tous_utilisateur"] = "Tutti gli utenti possono vedermi";
$trad["UTILISATEURS_voir_certains_utilisateur"] = "Alcuni utenti possono vedermi";




////	MODULE_TABLEAU BORD
////

// Menu principal + options du module
$trad["TABLEAU_BORD_nom_module"] = "Notizie";
$trad["TABLEAU_BORD_nom_module_header"] = "Notizie";
$trad["TABLEAU_BORD_description_module"] = "Notizie";
$trad["TABLEAU_BORD_ajout_actualite_admin"] = "Solo l'amministratore può aggiungere nuove notizie";
// Index.php
$trad["TABLEAU_BORD_new_elems"] = "nuovi elementi";
$trad["TABLEAU_BORD_new_elems_bulle"] = "Nuovi elementi creati durante il periodo selezionato";
$trad["TABLEAU_BORD_new_elems_realises"] = "elementi attuali";
$trad["TABLEAU_BORD_new_elems_realises_bulle"] = "Eventi e cose da fare <br>oggi";
$trad["TABLEAU_BORD_plugin_connexion"] = "dall'ultima connessione";
$trad["TABLEAU_BORD_plugin_jour"] = "oggi";
$trad["TABLEAU_BORD_plugin_semaine"] = "questa settimana";
$trad["TABLEAU_BORD_plugin_mois"] = "questo mese";
$trad["TABLEAU_BORD_autre_periode"] = "Un altro periodo";
$trad["TABLEAU_BORD_pas_nouveaux_elements"] = "Non ci sono elementi per il periodo selezionato";
$trad["TABLEAU_BORD_actualites"] = "News";
$trad["TABLEAU_BORD_actualite"] = "nuovo";
$trad["TABLEAU_BORD_actualites"] = "news";
$trad["TABLEAU_BORD_ajout_actualite"] = "Aggiungi una news";
$trad["TABLEAU_BORD_actualites_offline"] = "Notizie archiviate";
$trad["TABLEAU_BORD_pas_actualites"] = "Non ci sono notizie, al momento";
// Actualite_edit.php
$trad["TABLEAU_BORD_mail_nouvelle_actualite_cree"] = "Nuova notizia creata da";
$trad["TABLEAU_BORD_ala_une"] = "Sempre in primo piano";
$trad["TABLEAU_BORD_ala_une_info"] = "Visualizza sempre questa notizia per prima";
$trad["TABLEAU_BORD_offline"] = "Archiviata";
$trad["TABLEAU_BORD_date_online_auto"] = "Online dal";
$trad["TABLEAU_BORD_date_online_auto_alerte"] = "La pagina è stata correttamente archiviata in attesa della sua programmata pubblicazione";
$trad["TABLEAU_BORD_date_offline_auto"] = "fino al";




////	MODULE_AGENDA
////

// Menu principal
$trad["AGENDA_nom_module"] = "Agenda";
$trad["AGENDA_nom_module_header"] = "Agenda";
$trad["AGENDA_description_module"] = "Agenda personale e condivisa";
$trad["AGENDA_ajout_agenda_ressource_admin"] = "Solo un amministratore può impegnare risorse nell'agenda comune";
$trad["AGENDA_ajout_categorie_admin"] = "Solo un amministratore può aggiungere una categoria di eventi";
// Index.php
$trad["AGENDA_agendas_visibles"] = "Agende disponibili (personali & comuni)";
$trad["AGENDA_afficher_tous_agendas"] = "Mostra tutti i calendari";
$trad["AGENDA_masquer_tous_agendas"] = "Nascondi tutti i calendari";
$trad["AGENDA_cocher_tous_agendas"] = "Controllare/riprendere tutti i calendari";
$trad["AGENDA_cocher_agendas_users"] = "Controllare/riprendere gli utenti";
$trad["AGENDA_cocher_agendas_ressources"] = "Controllare/riprendere le risorse";
$trad["AGENDA_imprimer_agendas"] = "Stampa agenda";
$trad["AGENDA_imprimer_agendas_infos"] = "stampa in modalità orizzontale";
$trad["AGENDA_ajouter_agenda_ressource"] = "Impegna una risorsa";
$trad["AGENDA_ajouter_agenda_ressource_bis"] = "Aggiungi una risorsa : stanza, automobile, videoproiettore, ecc.";
$trad["AGENDA_exporter_ical"] = "Esporta gli eventi (formato iCal)";
$trad["AGENDA_exporter_ical_mail"] = "Esporta gli eventi per email (iCal)";
$trad["AGENDA_exporter_ical_mail2"] = "Per integrarli in una agenda Lightning/Thunderbird, Iphone, Android, Outlook, Google Calendar...";
$trad["AGENDA_importer_ical"] = "Importa gli eventi (iCal)";
$trad["AGENDA_importer_ical_etat"] = "Stato";
$trad["AGENDA_importer_ical_deja_present"] = "Già presente";
$trad["AGENDA_importer_ical_a_importer"] = "Da importare";
$trad["AGENDA_suppr_anciens_evt"] = "Rimuovi gli eventi già passati";
$trad["AGENDA_confirm_suppr_anciens_evt"] = "Sei sicuro di voler rimuovere definitivamente gli eventi che precedono il periodo visualizzato ?";
$trad["AGENDA_ajouter_evt_heure"] = "Aggiungi un evento alle";
$trad["AGENDA_ajouter_evt_jour"] = "Aggiungi un evento a questo giorno";
$trad["AGENDA_evt_jour"] = "Giorno";
$trad["AGENDA_evt_semaine"] = "Settimana";
$trad["AGENDA_evt_semaine_w"] = "Settimana lavorativa";
$trad["AGENDA_evt_mois"] = "Mese";
$trad["AGENDA_num_semaine"] = "settimana n°";
$trad["AGENDA_voir_num_semaine"] = "Vai alla settimana n°";
$trad["AGENDA_periode_suivante"] = "Prossimo periodo";
$trad["AGENDA_periode_precedante"] = "Periodo precedente";
$trad["AGENDA_affectations_evt"] = "Eventi nell'agenda di ";
$trad["AGENDA_affectations_evt_autres"] = "+ altre agende non visibili";
$trad["AGENDA_affectations_evt_non_confirme"] = "Confirmation on standby : ";
$trad["AGENDA_evenements_proposes_pour_agenda"] = "Eventi proposti per l'agenda di"; // "pour l'agenda de" Videoprojecteur / Mr bidule truc
$trad["AGENDA_evenements_proposes_mon_agenda"] = "Eventi proposti per la mia agenda";
$trad["AGENDA_evenement_propose_par"] = "Proposto da";  // "Proposé par" Mr bidule truc
$trad["AGENDA_evenement_integrer"] = "Integro questo evento all'agenda ?";
$trad["AGENDA_evenement_pas_integrer"] = "Eliminare l'evento proposto ?";
$trad["AGENDA_supprimer_evt_agenda"] = "Elimino per questa agenda ?";
$trad["AGENDA_supprimer_evt_agendas"] = "Elimino per tutte le agende ?";
$trad["AGENDA_supprimer_evt_date"] = "Elimino solo questa date ?";
$trad["AGENDA_confirm_suppr_evt"] = "Eliminare l'evento in questa agenda ?";
$trad["AGENDA_confirm_suppr_evt_tout"] = "Eliminare l'evento in tutte le agende dove è inserito ?";
$trad["AGENDA_confirm_suppr_evt_date"] = "Delete the event to this date, all calendars where it is placed ?";
$trad["AGENDA_evt_prive"] = "Evento privato";
$trad["AGENDA_aucun_agenda_visible"] = "Nessuna agenda visualizzata";
$trad["AGENDA_evt_proprio"] = "Eventi che ho creato";
$trad["AGENDA_evt_proprio_inaccessibles"] = "Visualizza solo gli eventi che ho creato per agende a cui non ho accesso";
$trad["AGENDA_aucun_evt"] = "Nessun evento";
$trad["AGENDA_proposer"] = "Proponi un evento";
$trad["AGENDA_synthese"] = "Sintesi agenda";
$trad["AGENDA_pourcent_agendas_occupes"] = "Data occupata";
$trad["AGENDA_agendas_details"] = "Vedi agende in dettaglio";
$trad["AGENDA_agendas_details_masquer"] = "Nascondi agende in dettaglio";
// Evenement.php
$trad["AGENDA_categorie"] = "Categoria";
$trad["AGENDA_visibilite"] = "Visibilità";
$trad["AGENDA_visibilite_public"] = "pubblica";
$trad["AGENDA_visibilite_public_cache"] = "pubblica, ma i dettagli sono nascosti";
$trad["AGENDA_visibilite_prive"] = "privata";
// Agenda_edit.php
$trad["AGENDA_affichage_evt"] = "Visualizza eventi";
$trad["AGENDA_affichage_evt_border"] = "Bordo con il colore della categoria";
$trad["AGENDA_affichage_evt_background"] = "Sfondo con il colore della categoria";
$trad["AGENDA_plage_horaire"] = "Visualizza orario";
// Evenement_edit.php
$trad["AGENDA_periodicite"] = "Evento ricorrente";
$trad["AGENDA_period_non"] = "Evento unico";
$trad["AGENDA_period_jour_semaine"] = "Ogni settimana";
$trad["AGENDA_period_jour_mois"] = "Ogni mese, alcuni giorni";
$trad["AGENDA_period_mois"] = "Ogni mese";
$trad["AGENDA_period_mois_xdumois"] = "del mese"; // Le 21 du mois
$trad["AGENDA_period_annee"] = "Ogni anno";
$trad["AGENDA_period_mois_xdeannee"] = "dell'anno"; // Le 21/12 de l'année
$trad["AGENDA_period_date_fin"] = "Data fine ripetizione";
$trad["AGENDA_exception_periodicite"] = "Eccezione di periodicità";
$trad["AGENDA_agendas_affectations"] = "Assegna alla seguente agenda";
$trad["AGENDA_verif_nb_agendas"] = "Seleziona, per favore, almeno una agenda";
$trad["AGENDA_mail_nouvel_evenement_cree"] = "Nuovo evento creato da ";
$trad["AGENDA_input_proposer"] = "Propose the event to the owner of the calendar";
$trad["AGENDA_input_affecter"] = "Aggiungi un evento al calendario";
$trad["AGENDA_info_proposer"] = "Puoi solo proporre un evento, perché non hai accesso in scrittura a questa agenda";
$trad["AGENDA_info_pas_modif"] = "Modifica non permessa perchè non hai accesso in scrittura in questo calendario";
$trad["AGENDA_visibilite_info"] = "<u>Public</u> : Visibile per gli utenti che hanno accesso in lettura (o +) per i calendari in cui è assegnato l'evento.<br><u>Dettagli pubblici, ma mascherato</u> : Lo stesso, ma coloro che hanno accesso in sola lettura, vedere il calendario della manifestazione, ma non i dettagli.<br><u>privato</u> : Visibile solo a coloro che hanno accesso in scrittura ai calendari che si è assegnato.";
$trad["AGENDA_edit_limite"] = "Tu non sei l'autore della manifestazione e fu assegnato ai calendari che non possono accedervi per iscritto. È solo possibile gestire le assegnazioni al calendario (s)";
$trad["AGENDA_creneau_occupe"] = "Il periodo è già occupato su questa agenda :";
// Categories.php
$trad["AGENDA_gerer_categories"] = "Amministra categorie eventi";
$trad["AGENDA_categories_evt"] = "Categorie eventi";
$trad["AGENDA_droit_gestion_categories"] = "Le categorie possono essere modificate solo dal suo autore o dall'amministratore del sistema";




////	MODULE_FICHIER
////

// Menu principal
$trad["FICHIER_nom_module"] = "File manager";
$trad["FICHIER_nom_module_header"] = "Files";
$trad["FICHIER_description_module"] = "File manager";
// Index.php
$trad["FICHIER_ajouter_fichier"] = "Aggiungi files";
$trad["FICHIER_ajouter_fichier_alert"] = "La cartella sul server non è accessibile in scrittura! Se pensi che sia un errore, contatta per favore l'ammionistratore del sistema";
$trad["FICHIER_telecharger_dossier"] = "Scarica il file";
$trad["FICHIER_telecharger_selection"] = "Scarica la selezione";
$trad["FICHIER_telecharger_dossier_confirm"] = "Confermi lo scaricamento del file ?";
$trad["FICHIER_voir_images"] = "Visualizza le immagini";
$trad["FICHIER_defiler_images"] = "Scorri le immagini";
$trad["FICHIER_pixels"] = "pixels";
$trad["FICHIER_nb_versions_fichier"] = "versione del file"; // n versions du fichier
$trad["FICHIER_ajouter_versions_fichier"] = "aggiungi una nuova versione";
$trad["FICHIER_apercu"] = "Outline"; // n versions du fichier
$trad["FICHIER_aucun_fichier"] = "Nessun file, al momento";
// fichier_edit_ajouter.php  &  Fichier_edit.php
$trad["FICHIER_limite_chaque_fichier"] = "Il file non deve eccedere i "; // ...2 Mega Octets
$trad["FICHIER_optimiser_images"] = "Il limite di dimensione per le immagini è "; // ..1024*768 pixels
$trad["FICHIER_maj_nom"] = "Il nome del file sarà sostituito dalla nuova versione";
$trad["FICHIER_ajout_plupload"] = "Caricamento file multiplo";
$trad["FICHIER_ajout_classique"] = "Caricamento file classico";
$trad["FICHIER_erreur_nb_fichiers"] = "Seleziona, per favore, alcuni files";
$trad["FICHIER_erreur_taille_fichier"] = "Il file è troppo grande";
$trad["FICHIER_erreur_non_geree"] = "Errore sconosciuto";
$trad["FICHIER_ajout_multiple_info"] = "Il tasto 'Shift' o 'Ctrl' seleziona più files contemporaneamente";
$trad["FICHIER_select_fichier"] = "Seleziona files";
$trad["FICHIER_annuler"] = "Cancella";
$trad["FICHIER_selectionner_fichier"] = "Seleziona, per favore, almeno un file";
$trad["FICHIER_nouvelle_version"] = "esiste già, una versione verrà aggiunta.";  // mon_fichier.gif "existe déjà"...
$trad["FICHIER_mail_nouveau_fichier_cree"] = "Nuovo file creato da ";
$trad["FICHIER_mail_fichier_modifie"] = "File modificato da ";
$trad["FICHIER_contenu"] = "contenuto";
// Versions_fichier.php
$trad["FICHIER_versions_de"] = "Versione di"; // versions de fichier.gif
$trad["FICHIER_confirmer_suppression_version"] = "Confermi la rimozione di questa versione ?";
// Images.php
$trad["FICHIER_info_https_flash"] = "Per non avere il messaggio ''Vuoi visualizzare gli oggetti protetti e non protetti?'' : <br> <br>> Clicca su ''Strumenti'' <br>> clicca ''Opzioni Internet'' < br />> clicca ''Sicurezza'' <br>> Scegli ''Internet Zone'' <br>> Livello Personalizzato <br>> Abilita Visualizza ''contenuto misto'' in ''Altro'' ";
$trad["FICHIER_img_precedante"] = "Immagine precedente [freccia sinistra]";
$trad["FICHIER_img_suivante"] = "Prossima immagine [freccia destra / barra spaziatrice]";
$trad["FICHIER_rotation_gauche"] = "Rouota a sinistra [freccia su]";
$trad["FICHIER_rotation_droite"] = "Ruota a destra [freccia giù]";
$trad["FICHIER_zoom"] = "Ingrandisci / Riduci";
// Video.php
$trad["FICHIER_voir_videos"] = "Guarda il video";
$trad["FICHIER_regarder"] = "Guarda il video";
$trad["FICHIER_video_precedante"] = "Video precedente";
$trad["FICHIER_video_suivante"] = "Prossimo video";
$trad["FICHIER_video_mp4_flv"] = "<a href='http://get.adobe.com/flashplayer'>Adobe Flash Player</a> non è installato.";




////	MODULE_FORUM
////

// Menu principal
$trad["FORUM_nom_module"] = "Forum";
$trad["FORUM_nom_module_header"] = "Forum";
$trad["FORUM_description_module"] = "Forum";
$trad["FORUM_ajout_sujet_admin"] = "Solo l'amministratore può aggiungere argomenti";
$trad["FORUM_ajout_sujet_theme"] = "Anche gli utenti possono aggiungere argomenti";
// TRI
$trad["tri"]["date_dernier_message"] = "ultimo messaggio";
// Index.php & Sujet.php
$trad["FORUM_sujet"] = "argomento";
$trad["FORUM_sujets"] = "argomenti";
$trad["FORUM_message"] = "messaggio";
$trad["FORUM_messages"] = "messaggi";
$trad["FORUM_dernier_message"] = "ultimo";
$trad["FORUM_ajouter_sujet"] = "Aggiungi un argomento";
$trad["FORUM_voir_sujet"] = "Vedi argomento";
$trad["FORUM_repondre"] = "Rispondi";
$trad["FORUM_repondre_message"] = "Rispondere a questo messaggio";
$trad["FORUM_repondre_message_citer"] = "Rispondi e quota questo messaggio";
$trad["FORUM_aucun_sujet"] = "Non ci sono argomenti, per ora";
$trad["FORUM_pas_message"] = "Non ci sono messaggi per ora";
$trad["FORUM_aucun_message"] = "Non ci sono messaggi";
$trad["FORUM_confirme_suppr_message"] = "Confermi l'eliminazione del messaggio (e dei sottomessaggi) ?";
$trad["FORUM_retour_liste_sujets"] = "Ritorna alla lista degli argomenti";
$trad["FORUM_notifier_dernier_message"] = "Notifica per email";
$trad["FORUM_notifier_dernier_message_info"] = "Inviami una notifica per mail per ogni nuovo messaggio";
// Sujet_edit.php  &  Message_edit.php
$trad["FORUM_infos_droits_acces"] = "Per partecipare a questo argomento devi avere almeno un ''accesso limitato in scrittura''";
$trad["FORUM_theme_espaces"] = "L'argomento è disponibile nello spazio";
$trad["FORUM_mail_nouveau_sujet_cree"] = "Nuovo argomento creato da ";
$trad["FORUM_mail_nouveau_message_cree"] = "Nuovo messaggio creato da ";
// Themes
$trad["FORUM_theme_sujet"] = "Argomento";
$trad["FORUM_accueil_forum"] = "Indice forum";
$trad["FORUM_sans_theme"] = "Senza argomento";
$trad["FORUM_themes_gestion"] = "Amministra argomenti";
$trad["FORUM_droit_gestion_themes"] = "Ogni argomento può essere modificato dal suo autore o dall'ammistratore del forum";
$trad["FORUM_confirm_suppr_theme"] = "Attenzione! I messaggi coinvolti non avranno un argomento! Confermi l'eliminazione?";




////	MODULE_TACHE
////

// Menu principal
$trad["TACHE_nom_module"] = "Compito";
$trad["TACHE_nom_module_header"] = "Compito";
$trad["TACHE_description_module"] = "Compito";
// TRI
$trad["tri"]["priorite"] = "Priorità";
$trad["tri"]["avancement"] = "Progresso";
$trad["tri"]["date_debut"] = "Data inizio";
$trad["tri"]["date_fin"] = "Data fine";
// Index.php
$trad["TACHE_ajouter_tache"] = "Aggiungi un compito";
$trad["TACHE_aucune_tache"] = "Non ci sono compito, al momento";
$trad["TACHE_avancement"] = "Progresso";
$trad["TACHE_avancement_moyen"] = "Media progresso";
$trad["TACHE_avancement_moyen_pondere"] = "Media progresoo calcolata per giorno/uomo";
$trad["TACHE_priorite"] = "Priorità";
$trad["TACHE_priorite1"] = "Bassa";
$trad["TACHE_priorite2"] = "Media";
$trad["TACHE_priorite3"] = "Alta";
$trad["TACHE_priorite4"] = "Critica";
$trad["TACHE_responsables"] = "Responsabile";
$trad["TACHE_budget_disponible"] = "Budget disponibile";
$trad["TACHE_budget_disponible_total"] = "Totale budget disponibile";
$trad["TACHE_budget_engage"] = "Budget impegnato";
$trad["TACHE_budget_engage_total"] = "Totale Budget impegnato";
$trad["TACHE_charge_jour_homme"] = "Giorni/uomo necessari";
$trad["TACHE_charge_jour_homme_total"] = "Totale giorni/uomo caricati ";
$trad["TACHE_charge_jour_homme_info"] = "Numero di giorni lavorativi necessari ad una persona per completare questo compito";
$trad["TACHE_avancement_retard"] = "Ritardo";
$trad["TACHE_budget_depasse"] = "Budget non rispettato";
$trad["TACHE_afficher_tout_gantt"] = "Visualizza tutte le compito";
// tache_edit.php
$trad["TACHE_mail_nouvelle_tache_cree"] = "Nuovo compito creato da ";
$trad["TACHE_specifier_date"] = "Per favore, specifica una data";




////	MODULE_CONTACT
////

// Menu principal
$trad["CONTACT_nom_module"] = "Lista dei contatti";
$trad["CONTACT_nom_module_header"] = "Contatti";
$trad["CONTACT_description_module"] = "Lista dei contatti";
// Index.php
$trad["CONTACT_ajouter_contact"] = "Aggiungi un contatto";
$trad["CONTACT_aucun_contact"] = "Non ci sono contatti inseriti, al momento";
$trad["CONTACT_creer_user"] = "Crea un utente in questo spazio";
$trad["CONTACT_creer_user_infos"] = "Creare un utente in questo spazio con questo contatto ?";
$trad["CONTACT_creer_user_confirm"] = "l'utente è stato creato";
// Contact_edit.php
$trad["CONTACT_mail_nouveau_contact_cree"] = "Nuovo contatto creato da ";




////	MODULE_LIEN
////

// Menu principal
$trad["LIEN_nom_module"] = "Preferiti";
$trad["LIEN_nom_module_header"] = "Preferiti";
$trad["LIEN_description_module"] = "Preferiti";
// Index.php
$trad["LIEN_ajouter_lien"] = "Aggiungi un link";
$trad["LIEN_aucun_lien"] = "Non è stato inserito alcun link, al momento";
// lien_edit.php & dossier_edit.php
$trad["LIEN_adresse"] = "Indirizzo";
$trad["LIEN_specifier_adresse"] = "Specifica, per favore, un indirizzo";
$trad["LIEN_mail_nouveau_lien_cree"] = "Nuovo link inserito da ";




////	MODULE_MAIL
////

// Menu principal
$trad["MAIL_nom_module"] = "Email";
$trad["MAIL_nom_module_header"] = "Email";
$trad["MAIL_description_module"] = "Invia email con un click!";
// Index.php
$trad["MAIL_specifier_mail"] = "Per favore, seleziona un destinatario per la tua email";
$trad["MAIL_titre"] = "Oggetto della mail";
$trad["MAIL_no_header_footer"] = "Non inserire intestazione e piè di pagina";
$trad["MAIL_no_header_footer_infos"] = "Non inserire il nome di chi invia e il link del sito web";
$trad["MAIL_masquer_dest_message"] = "Nascondi destinatari";
$trad["MAIL_masquer_dest_message_infos"] = "Per impostazione predefinita, destinatari di posta elettronica vengono visualizzati nel messaggio.";
$trad["MAIL_accuse_reception"] = "Richiedi conferma di ricezione";
$trad["MAIL_accuse_reception_infos"] = "Attenzione: alcuni clients email non supportano la ricevuta di ricezione";
$trad["MAIL_fichier_joint"] = "File allegati";
// Historique Mail
$trad["MAIL_historique_mail"] = "Storico delle email inviate";
$trad["MAIL_aucun_mail"] = "Nessuna email";
$trad["MAIL_envoye_par"] = "Email inviata da";
$trad["MAIL_destinataires"] = "Destinatari";
?>