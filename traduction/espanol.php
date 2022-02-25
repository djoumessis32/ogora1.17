<?php
////	PARAMETRAGE
////

// Header http
$trad["HEADER_HTTP"] = "es";
// Editeur Tinymce
$trad["EDITOR"] = "es";
// Dates formatées par PHP
setlocale(LC_TIME, "es_ES.utf8", "es_ES", "es", "spanish");




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
		$tab_jours_feries[$date] = "Lunes de Pascua";
	}

	////	Les fêtes fixes
	// Jour de l'an
	$tab_jours_feries[$annee."-01-01"] = "Día de Año Nuevo";
	// Epifanie
	$tab_jours_feries[$annee."-01-06"] = "Epifanía";
	// Fête du travail
	$tab_jours_feries[$annee."-05-01"] = "Día del Trabajo";
	// Assomption
	$tab_jours_feries[$annee."-08-15"] = "Asunción";
	// Día de la Hispanidad
	$tab_jours_feries[$annee."-10-12"] = "Día de la Hispanidad";
	// Toussaint
	$tab_jours_feries[$annee."-11-01"] = "Toussaint";
	// Jour de la Constitution
	$tab_jours_feries[$annee."-12-06"] = "Día de la Constitución";
	// Immaculée Conception
	$tab_jours_feries[$annee."-12-08"] = "Inmaculada Concepción";
	// Noël
	$tab_jours_feries[$annee."-12-25"] = "Navidad";

	////	Retourne le résultat
	return $tab_jours_feries;
}




////	COMMUN
////

// Divers
$trad["remplir_tous_champs"] = "Gracias rellene todos los campos";
$trad["voir_detail"] = "Ver detalles";
$trad["elem_inaccessible"] = "Elemento inaccesible";
$trad["champs_obligatoire"] = "Campo obligatorio";
$trad["oui"] = "sí";
$trad["non"] = "no";
$trad["aucun"] = "no";
$trad["aller_page"] = "Ir a la página";
$trad["alphabet_filtre"] = "Filtro alfabético";
$trad["tout"] = "Todo";
$trad["tout_afficher"] = "Mostrar todo";
$trad["important"] = "importante";
$trad["afficher"] = "mostrar";
$trad["masquer"] = "Ocultar";
$trad["deplacer"] = "mover";
$trad["options"] = "Opciones";
$trad["reinitialiser"] = "reset";
$trad["garder"] = "Mantengar";
$trad["par_defaut"] = "Por defecto";
$trad["localiser_carte"] = "Localizar en el mapa";
$trad["espace_public"] = "Espacio Público";
$trad["bienvenue_agora"] = "Bienvenido al Agora!";
$trad["mail_pas_valide"] = "El correo electrónico no es válida";
$trad["element"] = "elemento";
$trad["elements"] = "elementos";
$trad["dossier"] = "directorio";
$trad["dossiers"] = "directorios";
$trad["fermer"] = "Cerrar";
$trad["imprimer"] = "Imprimir";
$trad["select_couleur"] = "Seleccionar color";
$trad["visible_espaces"] = "Espacios en los que será visible";
$trad["visible_ts_espaces"] = "Visible en todos los espacios";
$trad["admin_only"] = "Administrador de sólo";
$trad["divers"] = "Varios";
$trad["confirm_close_lightbox"] = "¿Quieres cerrar el formulario?";
// images
$trad["photo"] = "foto";
$trad["fond_ecran"] = "papel tapiz";
$trad["image_changer"] = "cambiar";
$trad["pixels"] = "píxeles";
// Connexion
$trad["specifier_login_password"] = "Gracias a especificar un nombre de usuario y contraseña";
$trad["identifiant"] = "Identificador";
$trad["identifiant_connexion"] = "Identificador de conexión";
$trad["identifiant_ou_email"] = "Email / Identificador";
$trad["pass"] = "Contraseña";
$trad["pass2"] = "Confirmar contraseña";
$trad["password_verif_alert"] = "Su confirmación de contraseña no es válida";
$trad["connexion"] = "Conexión";
$trad["connexion_auto"] = "mantengamos el contacto";
$trad["connexion_auto_info"] = "Recordar mi nombre de usuario y la contraseña para una conexión automática";
$trad["password_oublie"] = "¿ Has olvidado tu contraseña ?";
$trad["password_oublie_info"] = "Enviar mi nombre de usuario y contraseña a mi dirección de correo electrónico (si se especifica)";
$trad["acces_invite"] = "Acceso de invitado";
$trad["espace_password_erreur"] = "Contraseña incorrecta";
$trad["version_ie"] = "Su navegador es demasiado viejo y no soporta todos los elementos de HTML : Se recomienda actualizarlo o incorporar Chrome Frame (www.google.com/ChromeFrame)";
// Affichage
$trad["type_affichage"] = "Mostrar";
$trad["type_affichage_liste"] = "Lista";
$trad["type_affichage_bloc"] = "Bloque";
$trad["type_affichage_arbo"] = "árbol";
// Sélectionner / Déselectionner tous les éléments
$trad["select_deselect"] = "seleccionar / deseleccionar";
$trad["aucun_element_selectionne"] = "No hay elementos seleccionados";
$trad["tout_selectionner"] = "Seleccionar todo";
$trad["inverser_selection"] = "Invertir selección";
$trad["suppr_elements"] = "Eliminar elementos";
$trad["deplacer_elements"] = "Mover a otro directorio";
$trad["voir_sur_carte"] = "Mostrar en el mapa";
$trad["selectionner_user"] = "Gracias por seleccionar al menos un usuario";
$trad["selectionner_2users"] = "Gracias por seleccionar por lo menos dos usuarios";
$trad["selectionner_espace"] = "Gracias por elegir al menos un espacio";
// Temps ("de 11h à 12h", "le 25-01-2007 à 10h30", etc.)
$trad["de"] = "de";
$trad["a"] = "a";
$trad["le"] = "el";
$trad["debut"] = "inicio";
$trad["fin"] = "Fin";
$trad["separateur_horaire"] = "h";
$trad["jours"] = "dias";
$trad["jour_1"] = "lunes";
$trad["jour_2"] = "Martes";
$trad["jour_3"] = "miércoles";
$trad["jour_4"] = "Jueves";
$trad["jour_5"] = "Viernes";
$trad["jour_6"] = "Sábado";
$trad["jour_7"] = "Domingo";
$trad["mois_1"] = "Enero";
$trad["mois_2"] = "Febrero";
$trad["mois_3"] = "marzo";
$trad["mois_4"] = "Abril";
$trad["mois_5"] = "Mayo";
$trad["mois_6"] = "Junio";
$trad["mois_7"] = "julio";
$trad["mois_8"] = "agosto";
$trad["mois_9"] = "Septiembre";
$trad["mois_10"] = "octubre";
$trad["mois_11"] = "Noviembre";
$trad["mois_12"] = "Diciembre";
$trad["mois_suivant"] = "mes siguiente";
$trad["mois_precedant"] = "mes anterior";
$trad["annee_suivante"] = "año siguiente";
$trad["annee_precedante"] = "año anterior";
$trad["aujourdhui"] = "hoy";
$trad["aff_aujourdhui"] = "Ver hoy";
$trad["modif_dates_debutfin"] = "La fecha de fin no puede ser anterior a la fecha de inicio";
// Nom & Description (pour les menus d'édition principalement)
$trad["titre"] = "Título";
$trad["nom"] = "Nombre";
$trad["description"] = "Descripción";
$trad["specifier_titre"] = "Gracias por especificar un título";
$trad["specifier_nom"] = "Gracias por especificar un nombre";
$trad["specifier_description"] = "Gracias por especificar una descripción";
$trad["specifier_titre_description"] = "Gracias por especificar un título o un nombre";
// Validation des formulaires
$trad["ajouter"] = " Añadir";
$trad["modifier"] = " Editar";
$trad["modifier_et_acces"] = "Editar + derechos de acceso";
$trad["valider"] = " Validar";
$trad["lancer"] = " Iniciar";
$trad["envoyer"] = "Enviar";
$trad["envoyer_a"] = "Enviar a";
// Tri d'affichage. Tous les éléments (dossier, tâche, lien, etc...) ont par défaut une date, un auteur & une description
$trad["trie_par"] = "Ordenar por";
$trad["tri"]["date_crea"] = "fecha de creación";
$trad["tri"]["date_modif"] = "fecha de modification";
$trad["tri"]["titre"] = "Título";
$trad["tri"]["description"] = "Descripción";
$trad["tri"]["id_utilisateur"] = "autor";
$trad["tri"]["extension"] = "Tipo de archivo";
$trad["tri"]["taille_octet"] = "tamaño";
$trad["tri"]["nb_downloads"] = "downloads";
$trad["tri"]["civilite"] = "civilidad";
$trad["tri"]["nom"] = "appelido";
$trad["tri"]["prenom"] = "nombre";
$trad["tri"]["adresse"] = "dirección";
$trad["tri"]["codepostal"] = "Código postal";
$trad["tri"]["ville"] = "ciudad";
$trad["tri"]["pays"] = "país";
$trad["tri"]["fonction"] = "función";
$trad["tri"]["societe_organisme"] = "compañía / organización";
$trad["tri_ascendant"] = "Ascendente";
$trad["tri_descendant"] = "Descendente";
// Options de suppression
$trad["confirmer"] = "Confirmar ?";
$trad["confirmer_suppr"] = "Confirmar eliminación ?";
$trad["confirmer_suppr_bis"] = "Está seguro ?!";
$trad["confirmer_suppr_dossier"] = "Confirmar la elimination del directorio y todo lo que contiene ? <br><br>Advertencia : algunos sub-directorios no pueden ser accessible : serán tambien eliminados !!";
$trad["supprimer"] = "Eliminar";
// Visibilité d'un Objet : auteur et droits d'accès
$trad["auteur"] = "Autor : ";
$trad["cree"] = "Creado";  //...12-12-2012
$trad["cree_par"] = "creación";
$trad["modif_par"] = "Cambio";
$trad["historique_element"] = "histórico del elemento";
$trad["invite"] = "invitado";
$trad["invites"] = "invitados";
$trad["tous"] = "todos";
$trad["inconnu"] = "desconocido";
$trad["acces_perso"] = "Acceso personal";
$trad["lecture"] = "lectura";
$trad["lecture_infos"] = "Acceso en lectura";
$trad["ecriture_limit"] = "escritura limitada";
$trad["ecriture_limit_infos"] = "Acceso en escritura limitada : possibilidad de añadir -ELEMENTS-, sin modificar o suprimir los creados por otros";
$trad["ecriture"] = "escritura";
$trad["ecriture_infos"] = "Acceso en escritura";
$trad["ecriture_infos_conteneur"] = "Acceso en escritura : possibilidad de añadir, modificar o suprimir todos los -ELEMENTS- del -CONTENEUR-";
$trad["ecriture_racine_defaut"] = "Acceso en escritura por defecto";
$trad["ecriture_auteur_admin"] = "Solo el autor y los administradores pueden cambiar los permisos de acceso o eliminar este -CONTENEUR-";
$trad["contenu_dossier"] = "contenido";
$trad["aucun_acces"] = "acceso no autorizado";
$trad["libelles_objets"] = array("element"=>"elementos", "fichier"=>"archivos", "tache"=>"tareas", "lien"=>"favoritos", "contact"=>"contactos", "evenement"=>"eventos", "message"=>"mensajes", "conteneur"=>"contenedor", "dossier"=>"directorio", "agenda"=>"calendario", "sujet"=>"tema");
// Envoi d'un mail (nouvel utilisateur, notification de création d'objet, etc...)
$trad["mail_envoye_par"] = "Enviado por";  // "Envoyé par" M. Trucmuche
$trad["mail_envoye"] = "El correo electrónico ha sido enviado !";
$trad["mail_envoye_notif"] = "El correo electrónico de notificación ha sido enviado !";
$trad["mail_pas_envoye"] = "El correo electrónico no se pudo enviar..."; // idem
// Dossier & fichier
$trad["giga_octet"] = "GB";
$trad["mega_octet"] = "MB";
$trad["kilo_octet"] = "KB";
$trad["octet"] = "Bytes";
$trad["dossier_racine"] = "Directorio raíz";
$trad["deplacer_autre_dossier"] = "Mover a otro directorio";
$trad["ajouter_dossier"] = "añadir directorio";
$trad["modifier_dossier"] = "Editar un directorio";
$trad["telecharger"] = "Descargar";
$trad["telecharge_nb"] = "Descargado";
$trad["telecharge_nb_bis"] = "veces"; // Téléchargé 'n' fois
$trad["telecharger_dossier"] = "Descargar el directorio";
$trad["espace_disque_utilise"] = "Espacio utilizado";
$trad["espace_disque_utilise_mod_fichier"] = "Espacio utilizado para los Archivos";
$trad["download_alert"] = "Download inaccesible durante el día (tamaño de archivo demasiado importante)";
// Infos sur une personne
$trad["civilite"] = "Civilidad";
$trad["nom"] = "Appelido";
$trad["prenom"] = "Nombre";
$trad["adresse"] = "Dirección";
$trad["codepostal"] = "Código postal";
$trad["ville"] = "Ciudad";
$trad["pays"] = "País";
$trad["telephone"] = "Teléfono";
$trad["telmobile"] = "teléfono móvil";
$trad["mail"] = "Email";
$trad["fax"] = "Fax";
$trad["siteweb"] = "Página web";
$trad["competences"] = "Habilidades";
$trad["hobbies"] = "Intereses";
$trad["fonction"] = "Función";
$trad["societe_organisme"] = "compañía / organización";
$trad["commentaire"] = "Comentario";
// Rechercher
$trad["preciser_text"] = "Por favor, especifique las palabras clave de al menos 3 caracteres";
$trad["rechercher"] = "Buscar";
$trad["rechercher_date_crea"] = "Fecha de creación";
$trad["rechercher_date_crea_jour"] = "menos de un día";
$trad["rechercher_date_crea_semaine"] = "menos de una semana";
$trad["rechercher_date_crea_mois"] = "menos de un mes";
$trad["rechercher_date_crea_annee"] = "menos de un año";
$trad["rechercher_espace"] = "Buscar en el espacio";
$trad["recherche_avancee"] =  "Búsqueda avanzada";
$trad["recherche_avancee_mots_certains"] =  "cualquier palabra";
$trad["recherche_avancee_mots_tous"] =  "todas las palabras";
$trad["recherche_avancee_expression_exacte"] =  "frase exacta";
$trad["recherche_avancee_champs"] =  "campos de búsqueda";
$trad["recherche_avancee_pas_concordance"] =  "Módulos y campos seleccionados no coinciden. Gracias a que revise su acuerdo en la búsqueda avanzada.";
$trad["mots_cles"] = "Palabras clave";
$trad["liste_modules"] = "Módulos";
$trad["liste_champs"] = "Campos";
$trad["liste_champs_elements"] = "Elementos involucrados";
$trad["aucun_resultat"] = "No hay resultados";
// Importer / Exporter des contact
$trad["exporter"] = "Exportar";
$trad["importer"] = "Importar";
$trad["export_import_users"] = "usuarios";
$trad["export_import_contacts"] = "contactos";
$trad["export_format"] = "formato";
$trad["contact_separateur"] = "separador";
$trad["contact_delimiteur"] = "delimitador";
$trad["specifier_fichier"] = "or favor, especifique un archivo";
$trad["extension_fichier"] = "El tipo del archivo no es válido. Debe ser de tipo";
$trad["format_fichier_invalide"] = "El formato del archivo no corresponde";
$trad["import_infos"] = "Seleccione los campos (Agora) de destino con las listas desplegables de cada columna.";
$trad["import_infos_contact"] = "Los contactos seran asignados por defecto al espacio actual.";
$trad["import_infos_user"] = "Si el nombre de usuario y la contraseña no son seleccionados, seran generados automáticamente.";
$trad["import_alert"] = "Por favor, seleccione la columna de nombre en las listas desplegables";
$trad["import_alert2"] = "Por favor, seleccione al menos un contacto para importar";
$trad["import_alert3"] = "El campo Agora ya ha sido seleccionado en otra columna (cada campo Agora se puede seleccionar sólo una vez)";
// Captcha
$trad["captcha"] = "Identificación Visual";
$trad["captcha_info"] = "Por favor, escriba los 4 caracteres para su identificación";
$trad["captcha_alert_specifier"] = "Por favor, especifique la identificación visual";
$trad["captcha_alert_erronee"] = "La identificación visual no es valida";
// Gestion des inscriptions d'utilisateur
$trad["inscription_users"] = "registrarse en el sitio";
$trad["inscription_users_info"] = "crear una nueva cuenta de usuario (validado por un administrador)";
$trad["inscription_users_espace"] = "suscribirse al espacio";
$trad["inscription_users_enregistre"] = "Su suscripción se ha registrado : será validado tan pronto como sea posible por el administrador del espacio";
$trad["inscription_users_option_espace"] = "Permitir a los visitantes que  se registren en el espacio";
$trad["inscription_users_option_espace_info"] = "La inscripción se encuentra en la página de inicio. Debe ser validado por el administrador del espacio.";
$trad["inscription_users_validation"] = "Validar las entradas de usuario";
$trad["inscription_users_valider"] = "validar";
$trad["inscription_users_invalider"] = "invalidar";
$trad["inscription_users_valider_mail"] = "Su cuenta ha sido validado en";
$trad["inscription_users_invalider_mail"] = "Su cuenta no ha sido validado en";
// Connexion à un serveur LDAP
$trad["ldap_connexion_serveur"] = "Conexión a un servidor LDAP";
$trad["ldap_server"] = "dirección del servidor";
$trad["ldap_server_port"] = "Puerto de servidor";
$trad["ldap_server_port_infos"] = "''389'' de forma predeterminada";
$trad["ldap_admin_login"] = "Cadena de conexión para admin";
$trad["ldap_admin_login_infos"] = "por ejemplo ''uid=admin,ou=my_company''";
$trad["ldap_admin_pass"] = "Contraseña del administrador";
$trad["ldap_groupe_dn"] = "Grupo / base DN";
$trad["ldap_groupe_dn_infos"] = "Localización de los usuarios del directorio.<br> por ejemplo ''ou=users,o=my_company''";
$trad["ldap_connexion_erreur"] = "Error al conectar con el servidor LDAP !";
$trad["ldap_import_infos"] = "Mostrar la configuración del servidor LDAP en el módulo de administración.";
$trad["ldap_crea_auto_users"] = "Auto creación de usuarios LDAP después de la identificación";
$trad["ldap_crea_auto_users_infos"] = "Creación automática de un usuario si no está en el Agora, pero presente en el servidor LDAP : se le asignará a las áreas accesibles a ''todos los usuarios del sitio''.<br>De lo contrario, el usuario no se creará.";
$trad["ldap_pass_cryptage"] = "El cifrado de contraseñas en el servidor LDAP";
$trad["ldap_effacer_params"] = "Eliminar configuración LDAP?";
$trad["ldap_pas_module_php"] = "Módulo PHP para la conexión a un servidor LDAP no está instalado!";




////	DIVERS
////

// Messages d'alert ou d'erreur
$trad["MSG_ALERTE_identification"] = "Nombre de usuario o contraseña no válida";
$trad["MSG_ALERTE_dejapresent"] = "Cuenta actualmente utilizada con una dirección IP diferente... (una cuenta puede ser utilizada en una sola computadora al mismo tiempo)";
$trad["MSG_ALERTE_adresseip"] = "La dirección IP utilizada no está permitida con esta cuenta";
$trad["MSG_ALERTE_pasaccesite"] = "El acceso no esta actualmente permitido con este cuente : probablemente no asignado a un espacio.";
$trad["MSG_ALERTE_captcha"] = "La identificación visual no válida";
$trad["MSG_ALERTE_acces_fichier"] = "El archivo no está disponible";
$trad["MSG_ALERTE_acces_dossier"] = "El directorio no está disponible";
$trad["MSG_ALERTE_espace_disque"] = "El espacio para almacenar sus archivos no es suficiente, no se puede añadir archivos";
$trad["MSG_ALERTE_type_interdit"] = "Tipo de archivo no permitido";
$trad["MSG_ALERTE_taille_fichier"] = "El tamaño del archivo es demasiado grande";
$trad["MSG_ALERTE_type_version"] = "Tipo de archivo diferente del original";
$trad["MSG_ALERTE_deplacement_dossier"] = "No se puede mover el directorio dentro de sí mismo..!";
$trad["MSG_ALERTE_nom_dossier"] = "Un archivo con el mismo nombre ya existe. Confirmar ?";
$trad["MSG_ALERTE_nom_fichier"] = "Un archivo con el mismo nombre ya existe, pero no ha sido reemplazado";
$trad["MSG_ALERTE_chmod_stock_fichiers"] = "No se puede escribir en el gestor de archivos. Por favor, haga un ''chmod 775'' en el directorio ''stock_fichiers'' (acceso en lectura-escritura para el proprietario y el grupo)";
$trad["MSG_ALERTE_nb_users"] = "No se puede añadir un nuevo usuario : se limita a "; // "...limité à" 10
$trad["MSG_ALERTE_miseajourconfig"] = "El archivo de configuración (config.inc.php) no se puede escribir : Actualización imposible !";
$trad["MSG_ALERTE_miseajour"] = "Actualización completada. Es recomendable reiniciar el navegador antes de volver a conectar";
$trad["MSG_ALERTE_user_existdeja"] = "El nombre de usuario ya existe : el usuario no se ha creado";
$trad["MSG_ALERTE_temps_session"] = "Su sesión ha caducado, gracias por volver a conectar";
$trad["MSG_ALERTE_specifier_nombre"] = "Por favor, especifique un número";
// header menu
$trad["HEADER_MENU_espace_administration"] = "Administración del sitio";
$trad["HEADER_MENU_espaces_dispo"] = "Espacio disponible";
$trad["HEADER_MENU_espace_acces_administration"] = "(Acceso a la administración)";
$trad["HEADER_MENU_affichage_elem"] = "Mostrar elementos";
$trad["HEADER_MENU_affichage_normal"] = "asignado a mí";
$trad["HEADER_MENU_affichage_normal_infos"] = "Es la pantalla normal / por defecto";
$trad["HEADER_MENU_affichage_auteur"] = "que he creado";
$trad["HEADER_MENU_affichage_auteur_infos"] = "Para mostrar sólo los elementos que he creado";
$trad["HEADER_MENU_affichage_tout"] = "Todos los elementos del espacio (admin)";
$trad["HEADER_MENU_affichage_tout_infos"] = "Para el administrador del espacio : para mostrar todos los elementos del espacio, incluso aquellos que no son asignados al administrador !";
$trad["HEADER_MENU_recherche_elem"] = "Búsqueda de elementos en el espacio";
$trad["HEADER_MENU_sortie_agora"] = "Cerrar sesión del Ágora";
$trad["HEADER_MENU_raccourcis"] = "Acceso directo";
$trad["HEADER_MENU_seul_utilisateur_connecte"] = "Actualmente sólo";
$trad["HEADER_MENU_en_ligne"] = "En línea";
$trad["HEADER_MENU_connecte_a"] = "conectado al sitio a";   // M. Bidule truc "connecté au site à" 12:45
$trad["HEADER_MENU_messenger"] = "Mensajería instantánea";
$trad["HEADER_MENU_envoye_a"] = "Enviado a";
$trad["HEADER_MENU_ajouter_message"] = "Añadir un mensaje";
$trad["HEADER_MENU_specifier_message"] = "Por favor, especifique un mensaje";
$trad["HEADER_MENU_enregistrer_conversation"] = "Recordar la conversación";
// Footer
$trad["FOOTER_page_generee"] = "página generada en";
// Password_oublie
$trad["PASS_OUBLIE_preciser_mail"] = "Introduzca la dirección de correo electrónico para recibir su nombre de usuario y contraseña";
$trad["PASS_OUBLIE_mail_inexistant"] = "El correo electrónico no está indicado en la base";
$trad["PASS_OUBLIE_mail_objet"] = "Conexión al espacio";
$trad["PASS_OUBLIE_mail_contenu"] = "Su nombre de usuario";
$trad["PASS_OUBLIE_mail_contenu_bis"] = "Haga clic aquí para reinicializar tu contraseña";
$trad["PASS_OUBLIE_prompt_changer_pass"] = "Por favor, especifique la nueva contraseña";
$trad["PASS_OUBLIE_id_newpassword_expire"] = "El enlace para regenerar la contraseña ha caducado .. gracias por reiniciar la procedura";
$trad["PASS_OUBLIE_password_reinitialise"] = "Su nueva contraseña se registró !";
// menu_edit_objet
$trad["EDIT_OBJET_alert_aucune_selection"] = "Debe seleccionar al menos una persona o un espacio";
$trad["EDIT_OBJET_alert_pas_acces_perso"] = "Usted no se ha asignado al elemento. validar todos lo mismo ?";
$trad["EDIT_OBJET_alert_ecriture"] = "Debe haber al menos una persona o un espacio asignado para escribir";
$trad["EDIT_OBJET_alert_ecriture_limite_defaut"] = "¡Advertencia! con acceso de escritura, todos los mensajes pueden ser eliminados ! \\n\\nSe recomienda limitar el acceso a escritura";
$trad["EDIT_OBJET_alert_invite"] = "Por favor, especifique un nombre o apodo de";
$trad["EDIT_OBJET_droit_acces"] = "Derechos de acceso";
$trad["EDIT_OBJET_espace_pas_module"] = "El módulo actual aún no se ha añadido a este espacio";
$trad["EDIT_OBJET_tous_utilisateurs"] = "Todos los usuarios";
$trad["EDIT_OBJET_tous_utilisateurs_espaces"] = "Todos los espacios";
$trad["EDIT_OBJET_espace_invites"] = "Invitados del espacio público";
$trad["EDIT_OBJET_aucun_users"] = "Actualmente no hay usuarios en este espacio";
$trad["EDIT_OBJET_invite"] = "Tu nombre / apodo";
$trad["EDIT_OBJET_admin_espace"] = "Administrador del espacio: acceso de escritura a todos los elementos del espacio";
$trad["EDIT_OBJET_tous_espaces"] = "Mostrar todos mis espacios";
$trad["EDIT_OBJET_notif_mail"] = "Notificación por correo electrónico";
$trad["EDIT_OBJET_notif_mail_joindre_fichiers"] = "Adjuntar archivos a la notificación";
$trad["EDIT_OBJET_notif_mail_info"] = "Enviar notificación por correo electrónico a los que tienen acceso al elemento";
$trad["EDIT_OBJET_notif_mail_selection"] = "Seleccionar manualmente los destinatarios de las notificaciones";
$trad["EDIT_OBJET_notif_tous_users"] = "Mostrar mas usuarios";
$trad["EDIT_OBJET_droits_ss_dossiers"] = "Dar igualdad de derechos a todos los sub-directorios";
$trad["EDIT_OBJET_raccourci"] = "Acceso directo";
$trad["EDIT_OBJET_raccourci_info"] = "Mostrar un acceso directo en el menú principal";
$trad["EDIT_OBJET_fichier_joint"] = "Añadir archivos (imágenes, vídeos..)";
$trad["EDIT_OBJET_inserer_fichier"] = "Mostrar en la descripción";
$trad["EDIT_OBJET_inserer_fichier_info"] = "Mostrar la imagen / video / mp3... en la descripción anterior. La inserción se realiza después de la validación del formulario.";
$trad["EDIT_OBJET_inserer_fichier_alert"] = "Haga clic a ''Insertar'' para añadir las imágenes en el texto / descripción";
$trad["EDIT_OBJET_demande_a_confirmer"] = "Su solicitud ha sido registrada. Se confirmó pronto.";
// Formulaire d'installation
$trad["INSTALL_connexion_bdd"] = "Conexión a la base de datos";
$trad["INSTALL_db_host"] = "Nombre del servidor host (hostname)";
$trad["INSTALL_db_name"] = "Nombre de la base de datos";
$trad["INSTALL_db_name_info"] = "Advertencia !!<br> Si la base de datos ya existe en el Ágora, será sustituido (sólo las tablas que comienzan con ''gt_'')";
$trad["INSTALL_db_login"] = "Nombre de Usuario";
$trad["INSTALL_db_password"] = "Contraseña";
$trad["INSTALL_login_password_info"] = "Para conectarle como Administrador General";
$trad["INSTALL_config_admin"] = "Administrador del Ágora";
$trad["INSTALL_config_espace"] = "Configuración del espacio principal";
$trad["INSTALL_erreur_acces_mysql"] = "No connection to the MySQL database";
$trad["INSTALL_erreur_acces_bdd"] = "La conexión a la base de datos no se ha establecido, confirmar ?";
$trad["INSTALL_erreur_agora_existe"] = "Base de datos de Agora ya existe ! Confirmar aún instalar y cambiar las tablas ?";
$trad["INSTALL_confirm_version_php"] = "Agora-Project requiere al mínimo la versión 4.3 de PHP, confirmar ?";
$trad["INSTALL_confirm_version_mysql"] = "Agora-Project requiere al mínimo la versión 4.2 de MySQL, confirmar ?";
$trad["INSTALL_confirm_install"] = "Confirmar instalación ?";
$trad["INSTALL_install_ok"] = "Agora-Project ha sido instalado ! por razones de seguridad, no olvide eliminar el directorio 'install', antes de empezar";




////	MODULE_PARAMETRAGE
////

// Menu principal
$trad["PARAMETRAGE_nom_module"] = "Administración";
$trad["PARAMETRAGE_nom_module_header"] = "Administración";
$trad["PARAMETRAGE_description_module"] = "Administración general";
// Index.php
$trad["PARAMETRAGE_sav"] = "Copia de seguridad de la base de datos y los archivos";
$trad["PARAMETRAGE_sav_alert"] = "La creación de la copia de seguridad puede tardar unos minutos ... y descargar una docena de minutos.";
$trad["PARAMETRAGE_sav_bdd"] = "Copia de seguridad de la base de datos";
$trad["PARAMETRAGE_adresse_web_invalide"] = "la dirección de conexión no es válido : debe comenzar con HTTP:// ";
$trad["PARAMETRAGE_espace_disque_invalide"] = "El límite de espacio de disco debe ser un número entero";
$trad["PARAMETRAGE_confirmez_modification_site"] = "Confirmar los cambios ?";
$trad["PARAMETRAGE_nom_site"] = "Nombre del sitio";
$trad["PARAMETRAGE_adresse_web"] = "Dirección de conexión al sitio";
$trad["PARAMETRAGE_footer_html"] = "Footer / Pie de página html";
$trad["PARAMETRAGE_footer_html_info"] = "Para incluir herramientas estadísticas, por ejemplo";
$trad["PARAMETRAGE_langues"] = "Lenguaje por defecto";
$trad["PARAMETRAGE_timezone"] = "Zona horaria";
$trad["PARAMETRAGE_nom_espace"] = "Nombre del espacio principal";
$trad["PARAMETRAGE_limite_espace_disque"] = "Espacio de disco disponible para los archivos";
$trad["PARAMETRAGE_logs_jours_conservation"] = "Periodo de validez de los Logs";
$trad["PARAMETRAGE_mode_edition"] = "Edición de elementos";
$trad["PARAMETRAGE_edition_popup"] = "en una ventana emergente";
$trad["PARAMETRAGE_edition_iframe"] = "en una iframe";
$trad["PARAMETRAGE_skin"] = "Color de la interfaz (fondo de los elementos, menús, etc.)";
$trad["PARAMETRAGE_noir"] = "Negro";
$trad["PARAMETRAGE_blanc"] = "Blanco";
$trad["PARAMETRAGE_erreur_fond_ecran_logo"] = "La imagen de fondo y el logotipo debe tener el formato .jpg ou .png";
$trad["PARAMETRAGE_suppr_fond_ecran"] = "Eliminar la imagen de fondo ?";
$trad["PARAMETRAGE_logo_footer"] = "Logotipo en pie de página";
$trad["PARAMETRAGE_logo_footer_url"] = "URL";
$trad["PARAMETRAGE_editeur_text_mode"] = "Modo del editor de texto (TinyMCE)";
$trad["PARAMETRAGE_editeur_text_minimal"] = "Mínimo";
$trad["PARAMETRAGE_editeur_text_complet"] = "Completo (+ Tablas + medias + pegar desde Word)";
$trad["PARAMETRAGE_messenger_desactive"] = "Mensajería instantánea activada";
$trad["PARAMETRAGE_agenda_perso_desactive"] = "Calendarios personales habilitadas por defecto";
$trad["PARAMETRAGE_agenda_perso_desactive_infos"] = "Agregar un calendario personal en la creación de un usuario. El calendario puede ser desactivado más tarde, cuando se cambia el perfil de usuario.";
$trad["PARAMETRAGE_libelle_module"] = "Nombre de los módulos en la barra de menús";
$trad["PARAMETRAGE_libelle_module_masquer"] = "Ocultar";
$trad["PARAMETRAGE_libelle_module_icones"] = "encima de cada icono del módulo";
$trad["PARAMETRAGE_libelle_module_page"] = "sólo para el módulo actual";
$trad["PARAMETRAGE_tri_personnes"] = "Ordenar los usuarios y contactos";
$trad["PARAMETRAGE_versions"] = "Versiones";
$trad["PARAMETRAGE_version_agora_maj"] = "actualización el ";
$trad["PARAMETRAGE_fonction_mail_desactive"] = "Función de PHP para enviar correos electrónicos : desactivada !";
$trad["PARAMETRAGE_fonction_mail_infos"] = "Algunos ''Host'' desactivan la función PHP para enviar correos electrónicos, por razones de seguridad ou saturación de los servidores (SPAM)";
$trad["PARAMETRAGE_fonction_image_desactive"] = "Función de la manipulación de imágenes y miniaturas (PHP GD2) : desactivada !";




////	MODULE_LOG
////

// Menu principal
$trad["LOGS_nom_module"] = "Logs";
$trad["LOGS_nom_module_header"] = "Logs";
$trad["LOGS_description_module"] = "Logs - Registro de eventos";
// Index.php
$trad["LOGS_filtre"] = "filtro";
$trad["LOGS_date_heure"] = "Fecha / Hora";
$trad["LOGS_espace"] = "Espacio";
$trad["LOGS_module"] = "Módulo";
$trad["LOGS_action"] = "Acción";
$trad["LOGS_utilisateur"] = "Usuario";
$trad["LOGS_adresse_ip"] = "IP";
$trad["LOGS_commentaire"] = "Comentario";
$trad["LOGS_no_logs"] = "Ningún registro";
$trad["LOGS_filtre_a_partir"] = "filtrado de la";
$trad["LOGS_chercher"] = "Buscar";
$trad["LOGS_chargement"] = "Carga los datos";
$trad["LOGS_connexion"] = "Conexión";
$trad["LOGS_deconnexion"] = "logout";
$trad["LOGS_consult"] = "consulta";
$trad["LOGS_consult2"] = "descarga";
$trad["LOGS_ajout"] = "Añadir";
$trad["LOGS_suppr"] = "eliminar";
$trad["LOGS_modif"] = "cambio";




////	MODULE_ESPACE
////

// Menu principal
$trad["ESPACES_nom_module"] = "Espacios";
$trad["ESPACES_nom_module_header"] = "Espacios";
$trad["ESPACES_description_module"] = "Espacios del sitio";
$trad["ESPACES_description_module_infos"] = "El sitio (o el espacio principal) puede ser subdivisado en varios espacios";
// Header_menu.inc.php
$trad["ESPACES_gerer_espaces"] = "Gestión de los spacios del sitio";
$trad["ESPACES_parametrage"] = "Administración del espacio";
$trad["ESPACES_parametrage_infos"] = "Administración del espacio (descripción, los módulos, los usuarios, etc)";
// Index.php
$trad["ESPACES_confirm_suppr_espace"] = "Confirmar eliminación ? Atención, los datos afectados a este espacio seran  definitivamente perdidas !!";
$trad["ESPACES_espace"] = "Espacio";
$trad["ESPACES_espaces"] = "Espacios";
$trad["ESPACES_definir_acces"] = "Definir !";
$trad["ESPACES_modules"] = "Módulos";
$trad["ESPACES_ajouter_espace"] = "Añadir un espacio";
$trad["ESPACES_supprimer_espace"] = "Eliminar el espacio";
$trad["ESPACES_aucun_espace"] = "No hay espacio por ahora";
$trad["MSG_ALERTE_suppr_espace_impossible"] = "No se puede eliminar el espacio actual";
// Espace_edit.php
$trad["ESPACES_gestion_acces"] = "Usuarios asignados al espacio";
$trad["ESPACES_selectionner_module"] = "Debe seleccionar al menos un módulo";
$trad["ESPACES_modules_espace"] = "Módulos del espacio";
$trad["ESPACES_modules_classement"] = "Mover a establecer el orden de presentación de los módulos";
$trad["ESPACES_selectionner_utilisateur"] = "Seleccione algunos usuarios, todos los usuarios, o abrir el espacio al público";
$trad["ESPACES_espace_public"] = "Espacio Público";
$trad["ESPACES_public_infos"] = "Proporciona acceso a las personas que no tienen cuentas en el sitio : invitados. Capacidad de especificar una contraseña para proteger el acceso.";
$trad["ESPACES_invitations_users"] = "Los usuarios pueden enviar invitaciones por correo";
$trad["ESPACES_invitations_users_infos"] = "Todos los usuarios pueden enviar invitaciones por correo electrónico para unirse al espacio";
$trad["ESPACES_tous_utilisateurs"] = "Todos los usuarios del sitio";
$trad["ESPACES_utilisation"] = " Usuarios";
$trad["ESPACES_utilisation_info"] = "Usuario del espacio : <br> Acceso normal al espacio";
$trad["ESPACES_administration"] = "Administrador";
$trad["ESPACES_administration_info"] = "Administrador del espacio : ecceso en escritura a todos los elementos del espacio + posibilidad de enviar invitaciones por correo electrónico + añadir nuevos usuarios";
$trad["ESPACES_creer_agenda_espace"] = "Crear un calendario para el espacio";
$trad["ESPACES_creer_agenda_espace_info"] = "Esto puede ser útil si los calendarios de los usuarios están desactivados.<br>El calendario tendrá el mismo nombre que el espacio y este será un calendario de recursos.";




////	MODULE_UTILISATEUR
////

// Menu principal
$trad["UTILISATEURS_nom_module"] = "Usuarios";
$trad["UTILISATEURS_nom_module_header"] = "Usuarios";
$trad["UTILISATEURS_description_module"] = "Usuarios";
$trad["UTILISATEURS_ajout_utilisateurs_groupe"] = "Los usuarios también pueden crear grupos";
// Index.php
$trad["UTILISATEURS_utilisateurs_site"] = "Usuarios del sitio";
$trad["UTILISATEURS_gerer_utilisateurs_site"] = "Gestión de todos los usuarios";
$trad["UTILISATEURS_utilisateurs_site_infos"] = "Todos los usuarios del sitio, todas las áreas combinadas";
$trad["UTILISATEURS_utilisateurs_espace"] = "Usuarios del espacio";
$trad["UTILISATEURS_confirm_suppr_utilisateur"] = "Confirmar eliminación del usuario ? Atención ! Todos los datos sobre ellos seran perdidos !!";
$trad["UTILISATEURS_confirm_desaffecter_utilisateur"] = "Confirmar la desasignación del usuario al espacio corriente ?";
$trad["UTILISATEURS_suppr_definitivement"] = "Eliminar definitivamente";
$trad["UTILISATEURS_desaffecter"] = "Desasignar del espacio";
$trad["UTILISATEURS_tous_user_affecte_espace"] = "Todo los usuarios del sitio son asignados a este espacio : no es possible desasignar";
$trad["UTILISATEURS_utilisateur"] = "usuario";
$trad["UTILISATEURS_utilisateurs"] = "usuarios";
$trad["UTILISATEURS_affecter_utilisateur"] = "Añadir un usuario existente, a ese espacio";
$trad["UTILISATEURS_ajouter_utilisateur"] = "Añadir un usuario";
$trad["UTILISATEURS_ajouter_utilisateur_site"] = "Crear un usuario en el sitio : por defecto, asignado a ningun espacio !";
$trad["UTILISATEURS_ajouter_utilisateur_espace"] = "Crear un usuario en el espacio actual";
$trad["UTILISATEURS_envoi_coordonnees"] = "Enviar el nombre de usuario y contraseña";
$trad["UTILISATEURS_envoi_coordonnees_infos"] = "Enviar a usuarios (por correo electronico) sus nombre de usuario<br> y una nueva contraseña";
$trad["UTILISATEURS_envoi_coordonnees_infos2"] = "Envíe un mensaje a los nuevos usuarios su nombre de usuario y contraseña";
$trad["UTILISATEURS_envoi_coordonnees_confirm"] = "Atención : las contraseñas seran reinicializadas ! confirmar ?";
$trad["UTILISATEURS_mail_coordonnees"] = "Coordenadas de connecion";
$trad["UTILISATEURS_aucun_utilisateur"] = "Ningún usuario asignado a este espacio por el momento";
$trad["UTILISATEURS_derniere_connexion"] = "Última conexión";
$trad["UTILISATEURS_liste_espaces"] = "Espacios del usuario";
$trad["UTILISATEURS_aucun_espace"] = "Ningún espacio";
$trad["UTILISATEURS_admin_general"] = "Administrador General del Sitio";
$trad["UTILISATEURS_admin_espace"] = "Administrador del espacio";
$trad["UTILISATEURS_user_espace"] = "Usuario del espacio";
$trad["UTILISATEURS_user_site"] = "Usuario del sitio";
$trad["UTILISATEURS_pas_connecte"] = "No está conectado";
$trad["UTILISATEURS_modifier"] = "Editar usuario";
$trad["UTILISATEURS_modifier_mon_profil"] = "Editar mi perfil";
$trad["UTILISATEURS_pas_suppr_dernier_admin_ge"] = "No se puede eliminar el último administrador general del sitio !";
// Invitation.php
$trad["UTILISATEURS_envoi_invitation"] = "Invitar alguien a unirse al espacio";
$trad["UTILISATEURS_envoi_invitation_info"] = "La invitacion sera enviada por correo electronico";
$trad["UTILISATEURS_objet_mail_invitation"] = "Invitación de "; // ..Jean DUPOND
$trad["UTILISATEURS_admin_invite_espace"] = "le invita a "; // Jean DUPOND "vous invite à rejoindre l'espace" Mon Espace
$trad["UTILISATEURS_confirmer_invitation"] = "Haga clic aquí para confirmar la invitación";
$trad["UTILISATEURS_invitation_a_confirmer"] = "Invitaciones a confirmar";
$trad["UTILISATEURS_id_invitation_expire"] = "La enlace de su invitación ha caducado";
$trad["UTILISATEURS_invitation_confirmer_password"] = "Gracias por elegir su contraseña antes de confirmar su invitación";
$trad["UTILISATEURS_invitation_valide"] = "Su invitación ha sido validado !";
// groupes.php
$trad["UTILISATEURS_groupe_espace"] = "grupos de usuarios del espacio";
$trad["UTILISATEURS_groupe_site"] = "todos los grupos de usuarios";
$trad["UTILISATEURS_groupe_infos"] = "modificar los grupos de usuarios";
$trad["UTILISATEURS_groupe_espace_infos"] = "Los usuarios activos tienen acceso a todos los espacios selectionados (los otros no son activados)";
$trad["UTILISATEURS_droit_gestion_groupes"] = "Cada grupo puede ser modificado por su autor o por el administrador general";
// Utilisateur_affecter.php
$trad["UTILISATEURS_preciser_recherche"] = "Gracias a especificar un nombre, un apellido o una dirección de correo electrónico";
$trad["UTILISATEURS_affecter_user_confirm"] = "Confirmar la asignación del usuario al espacio ?";
$trad["UTILISATEURS_rechercher_user"] = "Buscar a un usuario para añadirlo al espacio";
$trad["UTILISATEURS_tous_users_affectes"] = "Todos los usuarios del sitio ya están asignados a este espacio";
$trad["UTILISATEURS_affecter_user"] = "Asignar un usuario al espacio :";
$trad["UTILISATEURS_aucun_users_recherche"] = "No hay usuarios para esta búsqueda";
// Utilisateur_edit.php & CO
$trad["UTILISATEURS_specifier_nom"] = "Gracias especificar un appelido";
$trad["UTILISATEURS_specifier_prenom"] = "Gracias especificar un nombre";
$trad["UTILISATEURS_specifier_identifiant"] = "Gracias especificar un identificador";
$trad["UTILISATEURS_specifier_pass"] = "Gracias especificar una contraseña";
$trad["UTILISATEURS_pas_fichier_photo"] = "No se ha especificado la imagen !";
$trad["UTILISATEURS_langues"] = "Idioma";
$trad["UTILISATEURS_agenda_perso_active"] = "Calendario personal activado";
$trad["UTILISATEURS_agenda_perso_active_infos"] = "Si está activado, el calendar personal sigue siendo <u>siempre</ u> accessible para el usuario, incluso si el módulo Agenda del espacio actual está desactivado.";
$trad["UTILISATEURS_espace_connexion"] = "Espacio de conexión";
$trad["UTILISATEURS_notification_mail"] = "Enviar una notificación por e-mail de la creación";
$trad["UTILISATEURS_alert_notification_mail"] = "Gracias especificar una dirección de correo electrónico !";
$trad["UTILISATEURS_adresses_ip"] = "Direcciónes IP de control";
$trad["UTILISATEURS_info_adresse_ip"] = "Si se especifica una (o más) direcciones IP, el usuario sólo podra conectarse si utiliza las direcciones IP especificadas";
$trad["UTILISATEURS_ip_invalide"] = "Direccion IP invalida";
$trad["UTILISATEURS_identifiant_deja_present"] = "El identificador especificado ya existe. ¡ Gracias a especificar otro !";
$trad["UTILISATEURS_mail_deja_present"] = "El email ya existe. ¡ Gracias a especificar otro !";
$trad["UTILISATEURS_mail_objet_nouvel_utilisateur"] = "Nueva cuenta en ";  // "...sur" l'Agora machintruc
$trad["UTILISATEURS_mail_nouvel_utilisateur"] = "Una nueva cuenta se le dio a usted en";  // idem
$trad["UTILISATEURS_mail_infos_connexion"] = "Conectar con el login y la contraseña siguientes";
$trad["UTILISATEURS_mail_infos_connexion2"] = "Gracias a mantener este correo electrónico para sus archivos.";
// Utilisateur_Messenger.php
$trad["UTILISATEURS_gestion_messenger_livecounter"] = "Gestión de mensajería instantánea";
$trad["UTILISATEURS_visibilite_messenger_livecounter"] = "Usuarios que podran verme en línea y hablar en la mensajería instantánea";
$trad["UTILISATEURS_aucun_utilisateur_messenger"] = "No hay usuarios por el momento";
$trad["UTILISATEURS_voir_aucun_utilisateur"] = "Todos los usuarios no pueden verme";
$trad["UTILISATEURS_voir_tous_utilisateur"] = "Todos los usuarios pueden verme";
$trad["UTILISATEURS_voir_certains_utilisateur"] = "Algunos usuarios pueden verme";




////	MODULE_TABLEAU BORD
////

// Menu principal + options du module
$trad["TABLEAU_BORD_nom_module"] = "Noticias y novedades";
$trad["TABLEAU_BORD_nom_module_header"] = "Noticias";
$trad["TABLEAU_BORD_description_module"] = "Noticias y novedades";
$trad["TABLEAU_BORD_ajout_actualite_admin"] = "Sólo el administrador puede Añadir noticias";
// Index.php
$trad["TABLEAU_BORD_new_elems"] = "novedades";
$trad["TABLEAU_BORD_new_elems_bulle"] = "Elementos creados durante el período seleccionado";
$trad["TABLEAU_BORD_new_elems_realises"] = "elementos corrientes";
$trad["TABLEAU_BORD_new_elems_realises_bulle"] = "Eventos y tareas <br>hoy";
$trad["TABLEAU_BORD_plugin_connexion"] = "desde mi última conexión";
$trad["TABLEAU_BORD_plugin_jour"] = "Hoy";
$trad["TABLEAU_BORD_plugin_semaine"] = "esta semana";
$trad["TABLEAU_BORD_plugin_mois"] = "este mes";
$trad["TABLEAU_BORD_autre_periode"] = "otro período";
$trad["TABLEAU_BORD_pas_nouveaux_elements"] = "No hay elementos para el periodo seleccionado";
$trad["TABLEAU_BORD_actualites"] = "Noticias";
$trad["TABLEAU_BORD_actualite"] = "noticia";
$trad["TABLEAU_BORD_actualites"] = "noticias";
$trad["TABLEAU_BORD_ajout_actualite"] = "Añadir una noticia";
$trad["TABLEAU_BORD_actualites_offline"] = "Noticias archivadas";
$trad["TABLEAU_BORD_pas_actualites"] = "No hay noticias por el momento";
// Actualite_edit.php
$trad["TABLEAU_BORD_mail_nouvelle_actualite_cree"] = "Nueva noticia creada por ";
$trad["TABLEAU_BORD_ala_une"] = "Mostrar en el frente";
$trad["TABLEAU_BORD_ala_une_info"] = "Siempre ver esta noticia en primero";
$trad["TABLEAU_BORD_offline"] = "Archivado";
$trad["TABLEAU_BORD_date_online_auto"] = "En línea programada";
$trad["TABLEAU_BORD_date_online_auto_alerte"] = "La noticia ha sido archivado en la expectativa de su línea automática";
$trad["TABLEAU_BORD_date_offline_auto"] = "programado archivo";




////	MODULE_AGENDA
////

// Menu principal
$trad["AGENDA_nom_module"] = "Calendarios";
$trad["AGENDA_nom_module_header"] = "Calendarios";
$trad["AGENDA_description_module"] = "Calendarios personal y calendarios compartidos";
$trad["AGENDA_ajout_agenda_ressource_admin"] = "Sólo el administrador puede añadir calendarios de recursos";
$trad["AGENDA_ajout_categorie_admin"] = "Sólo el administrador puede añadir categorías de eventos";
// Index.php
$trad["AGENDA_agendas_visibles"] = "Calendarios disponibles (personal y recursos)";
$trad["AGENDA_afficher_tous_agendas"] = "Ver todo los calendarios";
$trad["AGENDA_masquer_tous_agendas"] = "Ocultar todo los calendarios";
$trad["AGENDA_cocher_tous_agendas"] = "comprobar/disparar todos los calendarios";
$trad["AGENDA_cocher_agendas_users"] = "comprobar/disparar usuarios";
$trad["AGENDA_cocher_agendas_ressources"] = "comprobar/disparar los recursos";
$trad["AGENDA_imprimer_agendas"] = "Imprimir el/los calendarios";
$trad["AGENDA_imprimer_agendas_infos"] = "imprimir la página en modo horizontal";
$trad["AGENDA_ajouter_agenda_ressource"] = "Añadir un calendario de recursos";
$trad["AGENDA_ajouter_agenda_ressource_bis"] = "Añadir un calendario de recursos : habitación, coche, vídeo, etc.";
$trad["AGENDA_exporter_ical"] = "Exportar los eventos (formato iCal)";
$trad["AGENDA_exporter_ical_mail"] = "Exportar los eventos por e-mail (iCal)";
$trad["AGENDA_exporter_ical_mail2"] = "Para integrar en un calendario IPHONE, ANDROID, OUTLOOK, GOOGLE CALENDAR...";
$trad["AGENDA_importer_ical"] = "Importar los eventos (iCal)";
$trad["AGENDA_importer_ical_etat"] = "Estado";
$trad["AGENDA_importer_ical_deja_present"] = "Ya está presente";
$trad["AGENDA_importer_ical_a_importer"] = "a importar";
$trad["AGENDA_suppr_anciens_evt"] = "Eliminar los eventos pasados";
$trad["AGENDA_confirm_suppr_anciens_evt"] = "Eliminar permanentemente los eventos anteriores al período mostrado ?";
$trad["AGENDA_ajouter_evt_heure"] = "Añadir un evento a";
$trad["AGENDA_ajouter_evt_jour"] = "Añadir un evento a esa fecha";
$trad["AGENDA_evt_jour"] = "Día";
$trad["AGENDA_evt_semaine"] = "Semana";
$trad["AGENDA_evt_semaine_w"] = "Semana de trabajo";
$trad["AGENDA_evt_mois"] = "Mes";
$trad["AGENDA_num_semaine"] = "Semana"; //...5
$trad["AGENDA_voir_num_semaine"] = "Ver la semana n°"; //...5
$trad["AGENDA_periode_suivante"] = "Período siguiente";
$trad["AGENDA_periode_precedante"] = "Período anterior";
$trad["AGENDA_affectations_evt"] = "Evento en el calendario de ";
$trad["AGENDA_affectations_evt_autres"] = "+ otros calendarios que no son visibles";
$trad["AGENDA_affectations_evt_non_confirme"] = "Pendiente de confirmación : ";
$trad["AGENDA_evenements_proposes_pour_agenda"] = "Eventos propuestos para"; // "Videoprojecteur" / "salle de réunion" / etc.
$trad["AGENDA_evenements_proposes_mon_agenda"] = "Eventos propuestos para mi calendario";
$trad["AGENDA_evenement_propose_par"] = "Propuestos por";  // "Proposé par" M. Bidule
$trad["AGENDA_evenement_integrer"] = "Integrar el evento al calendario ?";
$trad["AGENDA_evenement_pas_integrer"] = "Eliminar el evento propuesto ?";
$trad["AGENDA_supprimer_evt_agenda"] = "Eliminar en ese calendario ?";
$trad["AGENDA_supprimer_evt_agendas"] = "Eliminar en todos los calendarios ?";
$trad["AGENDA_supprimer_evt_date"] = "Eliminar sólo en esta fecha ?";
$trad["AGENDA_confirm_suppr_evt"] = "Eliminar el evento en ese calendario ?";
$trad["AGENDA_confirm_suppr_evt_tout"] = "Eliminar el evento en todos los calendarios donde esta asignado ?";
$trad["AGENDA_confirm_suppr_evt_date"] = "Eliminar el evento en esta fecha, en todos los calendarios donde esta asignado ?";
$trad["AGENDA_evt_prive"] = "Évento privado";
$trad["AGENDA_aucun_agenda_visible"] = "No calendario";
$trad["AGENDA_evt_proprio"] = "Eventos que he creado";
$trad["AGENDA_evt_proprio_inaccessibles"] = "Mostrar sólo los eventos que he creado, para calendarios en que no puedo acceder";
$trad["AGENDA_aucun_evt"] = "No hay eventos";
$trad["AGENDA_proposer"] = "Envíar un evento";
$trad["AGENDA_synthese"] = "Síntesis de los calendarios";
$trad["AGENDA_pourcent_agendas_occupes"] = "Calendarios ocupados";  // Agendas occupés : 2/5
$trad["AGENDA_agendas_details"] = "Ver calendarios en detaille";
$trad["AGENDA_agendas_details_masquer"] = "Ocultar calendarios en detaille";
// Evenement.php
$trad["AGENDA_categorie"] = "Categoría";
$trad["AGENDA_visibilite"] = "Visibilidad";
$trad["AGENDA_visibilite_public"] = "público";
$trad["AGENDA_visibilite_public_cache"] = "público, pero ocultados detalles";
$trad["AGENDA_visibilite_prive"] = "privado";
//  Agenda_edit.php
$trad["AGENDA_affichage_evt"] = "Mostrar eventos";
$trad["AGENDA_affichage_evt_border"] = "Frontera con el color de la categoría";
$trad["AGENDA_affichage_evt_background"] = "Fondo con el color de la categoría";
$trad["AGENDA_plage_horaire"] = "Banda horaria";
// Evenement_edit.php
$trad["AGENDA_periodicite"] = "Evento périodico";
$trad["AGENDA_period_non"] = "Evento puntual";
$trad["AGENDA_period_jour_semaine"] = "Cada semana";
$trad["AGENDA_period_jour_mois"] = "Dia del mes";
$trad["AGENDA_period_mois"] = "Cada mes";
$trad["AGENDA_period_mois_xdumois"] = "del mes"; // Le 21 du mois
$trad["AGENDA_period_annee"] = "Cada año";
$trad["AGENDA_period_mois_xdeannee"] = "del año"; // Le 21/12 de l'année
$trad["AGENDA_period_date_fin"] = "Fin de periodicidad";
$trad["AGENDA_exception_periodicite"] = "Excepción de periodicidad";
$trad["AGENDA_agendas_affectations"] = "Asignación a los calendarios";
$trad["AGENDA_verif_nb_agendas"] = "Gracias por seleccionar por lo menos un calendario";
$trad["AGENDA_mail_nouvel_evenement_cree"] = "Nuevo evento creado por ";
$trad["AGENDA_input_proposer"] = "Proponer el evento al propietario del calendario";
$trad["AGENDA_input_affecter"] = "Añadir el evento al calendario";
$trad["AGENDA_info_proposer"] = "Proponer el evento (no tiene acceso de escritura a este calendario)";
$trad["AGENDA_info_pas_modif"] = "Edición prohibida porque no tiene acceso de escritura al calendario";
$trad["AGENDA_visibilite_info"] = "<u>Pública</u> : Visibles para los usuarios que tienen acceso en lectura (o +) a los calendarios en el que el evento esta asignado.<br><u>Público, pero los detalles ocultados</u> : Idem, pero los que tienen acceso en lectura solo a los calendarios, solo pueden ver el horario del evento.<br><u>Privado</u> : Visibles para los usuarios que tienen acceso en escritura a los calendarios en el que el evento esta asignado.";
$trad["AGENDA_edit_limite"] = "Usted no es el autor del evento y fue asignado calendarios que no le son accessible en escritura : sólo se puedes administrar las asignaciones a su(s) calendario";
$trad["AGENDA_creneau_occupe"] = "La ranura ya está ocupado en este calendario :";
// Categories.php
$trad["AGENDA_gerer_categories"] = "Administrar las categorías de eventos";
$trad["AGENDA_categories_evt"] = "Categorías de eventos";
$trad["AGENDA_droit_gestion_categories"] = "Cada categoría puede ser modificado por su autor o por el administrador general";




////	MODULE_FICHIER
////

// Menu principal
$trad["FICHIER_nom_module"] = "Administración de Archivos";
$trad["FICHIER_nom_module_header"] = "Archivos";
$trad["FICHIER_description_module"] = "Administración de Archivos";
// Index.php
$trad["FICHIER_ajouter_fichier"] = "Añadir archivos";
$trad["FICHIER_ajouter_fichier_alert"] = "Los directorios del servidor no son accesible en escritura !  gracias de contactar el administrador";
$trad["FICHIER_telecharger_dossier"] = "Descargar archivo";
$trad["FICHIER_telecharger_selection"] = "Descargar selección";
$trad["FICHIER_telecharger_dossier_confirm"] = "Confirme la descarga de archivos ?";
$trad["FICHIER_voir_images"] = "Ver las imágenes";
$trad["FICHIER_defiler_images"] = "Vaya automáticamente las imágenes";
$trad["FICHIER_pixels"] = "píxeles";
$trad["FICHIER_nb_versions_fichier"] = "Archivo versiones"; // n versions du fichier
$trad["FICHIER_ajouter_versions_fichier"] = "Añadir nueva versión del archivo";
$trad["FICHIER_apercu"] = "Información general";
$trad["FICHIER_aucun_fichier"] = "No hay archivo en este momento";
// fichier_edit_ajouter.php  &  Fichier_edit.php
$trad["FICHIER_limite_chaque_fichier"] = "Los archivos no deben exceder"; // ...2 Mega Octets
$trad["FICHIER_optimiser_images"] = "Limite el tamaño de las imágenes a "; // ..1024*768 pixels
$trad["FICHIER_maj_nom"] = "El nombre del archivo será reemplazado por la nueva versión";
$trad["FICHIER_ajout_plupload"] = "Agregación múltiple";
$trad["FICHIER_ajout_classique"] = "Agregación clásico";
$trad["FICHIER_erreur_nb_fichiers"] = "Gracias a seleccionar menos archivos";
$trad["FICHIER_erreur_taille_fichier"] = "Archivo demasiado grande";
$trad["FICHIER_erreur_non_geree"] = "error no controlada";
$trad["FICHIER_ajout_multiple_info"] = "Pulse 'Maj' o 'Ctrl' para seleccionar varios archivos";
$trad["FICHIER_select_fichier"] = "Seleccione los archivos";
$trad["FICHIER_annuler"] = "Cancelar";
$trad["FICHIER_selectionner_fichier"] = "Gracias por elegir al menos un archivo";
$trad["FICHIER_nouvelle_version"] = "ya existe, una nueva versión se ha añadido.";  // mon_fichier.gif "existe déjà"...
$trad["FICHIER_mail_nouveau_fichier_cree"] = "Nuevo archivo creado por ";
$trad["FICHIER_mail_fichier_modifie"] = "Archivo editado por ";
$trad["FICHIER_contenu"] = "contenido";
// Versions_fichier.php
$trad["FICHIER_versions_de"] = "Versiones de"; // versions de fichier.gif
$trad["FICHIER_confirmer_suppression_version"] = "Confirme la eliminación de esta versión ?";
// Images.php
$trad["FICHIER_info_https_flash"] = "Para no tener mas el mensaje  ''Desea mostrar los elementos no seguros ?'' :<br> <br>> haga clic en ''Herramientas'' <br>> haga clic en ''Seguridad'' <br>> Elige ''Zona Internet'' <br>> Nivel personalizado <br>> Habilitar ''Mostrar contenido mixto'' ";
$trad["FICHIER_img_precedante"] = "Imagen anterior [flecha de izquierda]";
$trad["FICHIER_img_suivante"] = "Imagen siguiente [flecha de derecha / barra espaciadora]";
$trad["FICHIER_rotation_gauche"] = "Girar a la izquierda [flecha arriba]";
$trad["FICHIER_rotation_droite"] = "Girar a la derecha [Flecha abajo]";
$trad["FICHIER_zoom"] = "Zoom / Dézoomar";
// Video.php
$trad["FICHIER_voir_videos"] = "Mirar los videos";
$trad["FICHIER_regarder"] = "Mirar el video";
$trad["FICHIER_video_precedante"] = "Video anterior";
$trad["FICHIER_video_suivante"] = "Video siguiente";
$trad["FICHIER_video_mp4_flv"] = "<a href='http://get.adobe.com/flashplayer'>Flash</a> no instalado";




////	MODULE_FORUM
////

// Menu principal
$trad["FORUM_nom_module"] = "Foro";
$trad["FORUM_nom_module_header"] = "Foro";
$trad["FORUM_description_module"] = "Foro";
$trad["FORUM_ajout_sujet_admin"] = "Sólo el administrador puede añadir sujetos";
$trad["FORUM_ajout_sujet_theme"] = "Los usuarios también pueden añadir temas";
// TRI
$trad["tri"]["date_dernier_message"] = "último mensaje";
// Index.php & Sujet.php
$trad["FORUM_sujet"] = "sujeto";
$trad["FORUM_sujets"] = "sujetos";
$trad["FORUM_message"] = "mensaje";
$trad["FORUM_messages"] = "mensajes";
$trad["FORUM_dernier_message"] = "último de";
$trad["FORUM_ajouter_sujet"] = "añadir un sujeto";
$trad["FORUM_voir_sujet"] = "Ver el sujeto";
$trad["FORUM_repondre"] = "añadir un mensaje";
$trad["FORUM_repondre_message"] = "Responder a ese mensaje";
$trad["FORUM_repondre_message_citer"] = "Responder y citar a ese mensaje";
$trad["FORUM_aucun_sujet"] = "No sujeto por el momento";
$trad["FORUM_pas_message"] = "No mensaje por el momento";
$trad["FORUM_aucun_message"] = "No mensaje";
$trad["FORUM_confirme_suppr_message"] = "Confirmar la eliminación de mensajes (y sub-mensajes asociados) ?";
$trad["FORUM_retour_liste_sujets"] = "Volver a la lista de sujetos";
$trad["FORUM_notifier_dernier_message"] = "Notificar por e-mail";
$trad["FORUM_notifier_dernier_message_info"] = "Deseo recibir una notificación por correo a cada nuevo mensaje";
// Sujet_edit.php  &  Message_edit.php
$trad["FORUM_infos_droits_acces"] = "Para participar al sujeto, debe tener al minimo un ''acceso limitado de escritura''";
$trad["FORUM_theme_espaces"] = "El tema está disponible en los espacios";
$trad["FORUM_mail_nouveau_sujet_cree"] = "Nuevo sujeto creado por ";
$trad["FORUM_mail_nouveau_message_cree"] = "Nuevo mensaje creado por ";
// Themes
$trad["FORUM_theme_sujet"] = "Temas";
$trad["FORUM_accueil_forum"] = "Index del foro";
$trad["FORUM_sans_theme"] = "Sin tema";
$trad["FORUM_themes_gestion"] = "Gestión de los temas";
$trad["FORUM_droit_gestion_themes"] = "Cada tema puede ser modificado por su autor o por el administrador general";
$trad["FORUM_confirm_suppr_theme"] = "¡Atención! Los sujetos no tendrán mas temas! Confirmar eliminación?";




////	MODULE_TACHE
////

// Menu principal
$trad["TACHE_nom_module"] = "Tareas";
$trad["TACHE_nom_module_header"] = "Tareas";
$trad["TACHE_description_module"] = "Tareas";
// TRI
$trad["tri"]["priorite"] = "Prioridad";
$trad["tri"]["avancement"] = "Progreso";
$trad["tri"]["date_debut"] = "Fecha de inicio";
$trad["tri"]["date_fin"] = "Fecha de fin";
// Index.php
$trad["TACHE_ajouter_tache"] = "Añadir una tareas";
$trad["TACHE_aucune_tache"] = "No hay tarea por el momento";
$trad["TACHE_avancement"] = "Progreso";
$trad["TACHE_avancement_moyen"] = "Progreso promedio";
$trad["TACHE_avancement_moyen_pondere"] = "Progreso promedio ponderado con la carga dia/hombre";
$trad["TACHE_priorite"] = "Prioridad";
$trad["TACHE_priorite1"] = "Baja";
$trad["TACHE_priorite2"] = "promedia";
$trad["TACHE_priorite3"] = "alta";
$trad["TACHE_priorite4"] = "Crítica";
$trad["TACHE_responsables"] = "Responsables";
$trad["TACHE_budget_disponible"] = "Presupuesto disponible";
$trad["TACHE_budget_disponible_total"] = "Presupuesto disponible total";
$trad["TACHE_budget_engage"] = "Presupuesto comprometido";
$trad["TACHE_budget_engage_total"] = "Presupuesto comprometido total";
$trad["TACHE_charge_jour_homme"] = "Carga dia/hombre";
$trad["TACHE_charge_jour_homme_total"] = "Carga dia/hombre total";
$trad["TACHE_charge_jour_homme_info"] = "Número de días de trabajo necesarios para una persona para realizar esta tarea";
$trad["TACHE_avancement_retard"] = "Progreso retrasado";
$trad["TACHE_budget_depasse"] = "Presupuesto excedido";
$trad["TACHE_afficher_tout_gantt"] = "Mostrar todas las tareas";
// tache_edit.php
$trad["TACHE_mail_nouvelle_tache_cree"] = "Nueva tarea creada por ";
$trad["TACHE_specifier_date"] = "Gracias a especificar una fecha";




////	MODULE_CONTACT
////

// Menu principal
$trad["CONTACT_nom_module"] = "Directorio de contactos";
$trad["CONTACT_nom_module_header"] = "Contactos";
$trad["CONTACT_description_module"] = "Directorio de contactos";
// Index.php
$trad["CONTACT_ajouter_contact"] = "Añadir un contacto";
$trad["CONTACT_aucun_contact"] = "No hay contacto todavía";
$trad["CONTACT_creer_user"] = "Crear un usuario en este espacio";
$trad["CONTACT_creer_user_infos"] = "Crear un usuario en este espacio con este contacto ?";
$trad["CONTACT_creer_user_confirm"] = "El usuario fue creado";
// Contact_edit.php
$trad["CONTACT_mail_nouveau_contact_cree"] = "Nuevo contacto creado por ";




////	MODULE_LIEN
////

// Menu principal
$trad["LIEN_nom_module"] = "Favoritos";
$trad["LIEN_nom_module_header"] = "Favoritos";
$trad["LIEN_description_module"] = "Favoritos";
// Index.php
$trad["LIEN_ajouter_lien"] = "Añadir un enlace";
$trad["LIEN_aucun_lien"] = "No hay enlaces por el momento";
// lien_edit.php & dossier_edit.php
$trad["LIEN_adresse"] = "Dirección";
$trad["LIEN_specifier_adresse"] = "Gracias especificar una dirección";
$trad["LIEN_mail_nouveau_lien_cree"] = "Nuevo enlace creado por ";




////	MODULE_MAIL
////

// Menu principal
$trad["MAIL_nom_module"] = "Correo electrónico";
$trad["MAIL_nom_module_header"] = "Correo electrónico";
$trad["MAIL_description_module"] = "Enviar mensajes de correo electrónico con un solo clic !";
// Index.php
$trad["MAIL_specifier_mail"] = "Gracias especificar al menos un destinatario";
$trad["MAIL_titre"] = "Título del correo electrónico";
$trad["MAIL_no_header_footer"] = "No encabezado y firma";
$trad["MAIL_no_header_footer_infos"] = "No incluir el nombre del remitente y el enlace al espacio";
$trad["MAIL_masquer_dest_message"] = "Ocultar los destinatarios";
$trad["MAIL_masquer_dest_message_infos"] = "Por defecto, los destinatarios de correo electrónico aparecen en el mensaje.";
$trad["MAIL_accuse_reception"] = "Solicitar confirmación de entrega";
$trad["MAIL_accuse_reception_infos"] = "Advertencia: algunos clientes de correo electrónico no acepta el recibo de entrega";
$trad["MAIL_fichier_joint"] = "Archivo adjunto";
// Historique Mail
$trad["MAIL_historique_mail"] = "Historia de los correos electrónicos enviados";
$trad["MAIL_aucun_mail"] = "No correo electrónico";
$trad["MAIL_envoye_par"] = "Correo electrónico enviado por";
$trad["MAIL_destinataires"] = "Destinatarios";
?>