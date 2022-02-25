<?php
////	PARAMETRAGE
////

// Header http
$trad["HEADER_HTTP"] = "pt";
// Editeur Tinymce
$trad["EDITOR"] = "pt";
// Dates formatées par PHP
setlocale(LC_TIME, "pt_PT.utf8", "pt_PT", "pt", "portuguese");




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
		$tab_jours_feries[$date] = "Segunda-feira de Páscoa";
		// Jeudi de l'ascension
		$date = strftime("%Y-%m-%d", $paques_unix + ($jour_unix*39));
		$tab_jours_feries[$date] = "Quinta-feira de Ascensão";
		// Lundi de pentecôte
		$date = strftime("%Y-%m-%d", $paques_unix + ($jour_unix*50));
		$tab_jours_feries[$date] = "Segunda-feira de Pentecostes";
	}

	////	Les fêtes fixes
	// Jour de l'an
	$tab_jours_feries[$annee."-01-01"] = "Ano Novo";
	// Fête du travail
	$tab_jours_feries[$annee."-05-01"] = "Dia do Trabalhador";
	// Fête nationale
	$tab_jours_feries[$annee."-06-10"] = "Feriado nacional";
	// Assomption
	$tab_jours_feries[$annee."-08-15"] = "Assunção";
	// Toussaint
	$tab_jours_feries[$annee."-11-01"] = "Todos os Santos";
	// Noël
	$tab_jours_feries[$annee."-12-25"] = "Natal";

	////	Retourne le résultat
	return $tab_jours_feries;
}




////	COMMUN
////

// Divers
$trad["remplir_tous_champs"] = "Favor preencher todos os campos";
$trad["voir_detail"] = "Mostrar detalhes";
$trad["elem_inaccessible"] = "Elemento inacessível";
$trad["champs_obligatoire"] = "Campo Obrigatório";
$trad["oui"] = "sim";
$trad["non"] = "não";
$trad["aucun"] = "não";
$trad["aller_page"] = "Ir para a página";
$trad["alphabet_filtre"] = "Filtro alfabética";
$trad["tout"] = "Tudo";
$trad["tout_afficher"] = "Tudo";
$trad["important"] = "Importante";
$trad["afficher"] = "Mostrar";
$trad["masquer"] = "Esconder";
$trad["deplacer"] = "mover";
$trad["options"] = "Opções";
$trad["reinitialiser"] = "reinicializar";
$trad["garder"] = "Manter";
$trad["par_defaut"] = "Por defeito";
$trad["localiser_carte"] = "Localizar num mapa";
$trad["espace_public"] = "Espaço Público";
$trad["bienvenue_agora"] = "Bem-vindo na Ágora!";
$trad["mail_pas_valide"] = "O e-mail não é válido";
$trad["element"] = "elemento";
$trad["elements"] = "elementos";
$trad["dossier"] = "pasta";
$trad["dossiers"] = "pastas";
$trad["fermer"] = "Fechar";
$trad["imprimer"] = "Imprimir";
$trad["select_couleur"] = "Selecionar Cor";
$trad["visible_espaces"] = "Espaços onde será visível";
$trad["visible_ts_espaces"] = "Visível em todos os espaços";
$trad["admin_only"] = "administrador unicamente";
$trad["divers"] = "Diversos";
$trad["confirm_close_lightbox"] = "fechar o formulário ?";
// images
$trad["photo"] = "Foto";
$trad["fond_ecran"] = "Ecrã de fundo";
$trad["image_changer"] = "Mudar";
$trad["pixels"] = "pixels";
// Connexion
$trad["specifier_login_password"] = "Favor especificar um login e uma senha";
$trad["identifiant"] = "login";
$trad["identifiant_connexion"] = "login";
$trad["identifiant_ou_email"] = "Email / login";
$trad["pass"] = "Senha";
$trad["pass2"] = "Confirmar senha";
$trad["password_verif_alert"] = "Sua senha de confirmação é inválida";
$trad["connexion"] = "Conexão";
$trad["connexion_auto"] = "fique ligado";
$trad["connexion_auto_info"] = "Lembrar meu login e senha para conexão automática";
$trad["password_oublie"] = "esqueceu a senha ?";
$trad["password_oublie_info"] = "Enviar o meu login e senha para o meu e-mail (se especificado)";
$trad["acces_invite"] = "Acesso para convidado";
$trad["espace_password_erreur"] = "senha incorreta";
$trad["version_ie"] = "Seu navegador é muito antigo e não suporta todos os padrões de HTML: É recomendado atualizar ou incorporar Chrome Frame (www.google.com/ChromeFrame)";
// Affichage
$trad["type_affichage"] = "Visualização";
$trad["type_affichage_liste"] = "Lista";
$trad["type_affichage_bloc"] = "Bloco";
$trad["type_affichage_arbo"] = "árvore";
// Sélectionner / Déselectionner tous les éléments
$trad["select_deselect"] = "Selecionar / deselecionar";
$trad["aucun_element_selectionne"] = "Nenhum elemento foi selecionado";
$trad["tout_selectionner"] = "Selecionar Tudo";
$trad["inverser_selection"] = "Inverter Seleção";
$trad["suppr_elements"] = "Apagar os elementos";
$trad["deplacer_elements"] = "Mover para outra pasta";
$trad["voir_sur_carte"] = "Mostrar no mapa";
$trad["selectionner_user"] = "Favor selecionar pelo menos um usuário";
$trad["selectionner_2users"] = "Obrigado por escolher pelo menos 2 usuários";
$trad["selectionner_espace"] = "Favor Selecionar pelo menos um espaço";
// Temps ("de 11h à 12h", "le 25-01-2007 à 10h30", etc.)
$trad["de"] = "de";
$trad["a"] = "a";
$trad["le"] = "o";
$trad["debut"] = "Início";
$trad["fin"] = "Fim";
$trad["separateur_horaire"] = "h";
$trad["jours"] = "dias";
$trad["jour_1"] = "Segunda";
$trad["jour_2"] = "Terça";
$trad["jour_3"] = "Quarta";
$trad["jour_4"] = "Quinta";
$trad["jour_5"] = "Sexta";
$trad["jour_6"] = "Sábado";
$trad["jour_7"] = "Domingo";
$trad["mois_1"] = "Janeiro";
$trad["mois_2"] = "Fevereiro";
$trad["mois_3"] = "Março";
$trad["mois_4"] = "Abril";
$trad["mois_5"] = "Maio";
$trad["mois_6"] = "Junho";
$trad["mois_7"] = "Julho";
$trad["mois_8"] = "Agosto";
$trad["mois_9"] = "Setembro";
$trad["mois_10"] = "Outubro";
$trad["mois_11"] = "Novembro";
$trad["mois_12"] = "Dezembro";
$trad["mois_suivant"] = "mês precedente";
$trad["mois_precedant"] = "mês anterior";
$trad["annee_suivante"] = "ano precedente";
$trad["annee_precedante"] = "ano anterior";
$trad["aujourdhui"] = "hoje";
$trad["aff_aujourdhui"] = "Visualizar hoje";
$trad["modif_dates_debutfin"] = "A data de fim não pode ser anterior à data de início";
// Nom & Description (pour les menus d'édition principalement)
$trad["titre"] = "Título";
$trad["nom"] = "Nome";
$trad["description"] = "Descrição";
$trad["specifier_titre"] = "Favor especificar um título";
$trad["specifier_nom"] = "Favor especificar um nome";
$trad["specifier_description"] = "Favor especificar uma descrição";
$trad["specifier_titre_description"] = "Favor especificar um título ou uma descrição";
// Validation des formulaires
$trad["ajouter"] = " Adicionar";
$trad["modifier"] = " Modificar";
$trad["modifier_et_acces"] = "Modificar + gestão dos direitos de acesso";
$trad["valider"] = " Validar";
$trad["lancer"] = " Iniciar";
$trad["envoyer"] = "Enviar";
$trad["envoyer_a"] = "Enviar a";
// Tri d'affichage. Tous les éléments (dossier, tâche, lien, etc...) ont par défaut une date, un auteur & une description
$trad["trie_par"] = "Ordenar por";
$trad["tri"]["date_crea"] = "data de criação";
$trad["tri"]["date_modif"] = "data de modificação";
$trad["tri"]["titre"] = "título";
$trad["tri"]["description"] = "descrição";
$trad["tri"]["id_utilisateur"] = "autor";
$trad["tri"]["extension"] = "Tipo de arquivo";
$trad["tri"]["taille_octet"] = "tamanho";
$trad["tri"]["nb_downloads"] = "downloads";
$trad["tri"]["civilite"] = "civilidade";
$trad["tri"]["nom"] = "sobrenome";
$trad["tri"]["prenom"] = "nome";
$trad["tri"]["adresse"] = "endereço";
$trad["tri"]["codepostal"] = "CEP";
$trad["tri"]["ville"] = "Cidade";
$trad["tri"]["pays"] = "país";
$trad["tri"]["fonction"] = "função";
$trad["tri"]["societe_organisme"] = "sociedade / organismo";
$trad["tri_ascendant"] = "Ascendente";
$trad["tri_descendant"] = "Descendente";
// Options de suppression
$trad["confirmer"] = "Confirmar?";
$trad["confirmer_suppr"] = "Confirmar apagar?";
$trad["confirmer_suppr_bis"] = "Tem certeza ?";
$trad["confirmer_suppr_dossier"] = "Confirmar apagar a pasta e todos os dados que ele contém ? <br><br>Cuidado! Você pode não ter acesso a algumas sub-pastas: elas serão apagadas também!!";
$trad["supprimer"] = "Apagar";
// Visibilité d'un Objet : auteur et droits d'accès
$trad["auteur"] = "Autor : ";
$trad["cree"] = "Criado";  //...12-12-2012
$trad["cree_par"] = "Criação";
$trad["modif_par"] = "Mudança";
$trad["historique_element"] = "histórico";
$trad["invite"] = "convidado";
$trad["invites"] = "convidados";
$trad["tous"] = "todos";
$trad["inconnu"] = "pessoa desconhecida";
$trad["acces_perso"] = "Acesso pessoal";
$trad["lecture"] = "leitura";
$trad["lecture_infos"] = "Acesso à leitura";
$trad["ecriture_limit"] = "escrito limitada";
$trad["ecriture_limit_infos"] = "Acesso limitado por escrito : habilidade para criar -ELEMENTS-, sem modificar / excluir aqueles criados por outros";
$trad["ecriture"] = "escrita";
$trad["ecriture_infos"] = "Acesso de escrita";
$trad["ecriture_infos_conteneur"] = "Acesso de escrita : habilidade para criar, modificar o excluir todos os -ELEMENTS- da -CONTENEUR-";
$trad["ecriture_racine_defaut"] = "Acesso de escrita por defeito";
$trad["ecriture_auteur_admin"] = "Apenas o autor e os administradores podem modificar os direitos de acesso ou apagar este -CONTENEUR-";
$trad["contenu_dossier"] = "conteúdo";
$trad["aucun_acces"] = "acesso não autorizado";
$trad["libelles_objets"] = array("element"=>"elementos", "fichier"=>"arquivos", "tache"=>"tarefas", "lien"=>"favoritos", "contact"=>"contatos", "evenement"=>"eventos", "message"=>"mensagem", "conteneur"=>"contêiner", "dossier"=>"arquivo", "agenda"=>"agenda", "sujet"=>"assunto");
// Envoi d'un mail (nouvel utilisateur, notification de création d'objet, etc...)
$trad["mail_envoye_par"] = "Enviado por";  // "Envoyé par" M. Trucmuche
$trad["mail_envoye"] = "O e-mail foi enviado!";
$trad["mail_envoye_notif"] = "O e-mail de notificação foi enviado!";
$trad["mail_pas_envoye"] = "O e-mail não pôde ser enviada ..."; // idem
// Dossier & fichier
$trad["giga_octet"] = "Go";
$trad["mega_octet"] = "Mo";
$trad["kilo_octet"] = "Ko";
$trad["octet"] = "Octets";
$trad["dossier_racine"] = "Pasta raiz";
$trad["deplacer_autre_dossier"] = "Mover para outra pasta";
$trad["ajouter_dossier"] = "adicionar pasta";
$trad["modifier_dossier"] = "Modificar uma pasta";
$trad["telecharger"] = "Baixar";
$trad["telecharge_nb"] = "baixado";
$trad["telecharge_nb_bis"] = "vezes"; // Téléchargé '3' fois
$trad["telecharger_dossier"] = "Baixe a pasta";
$trad["espace_disque_utilise"] = "espaço em disco utilizado";
$trad["espace_disque_utilise_mod_fichier"] = "espaço em disco utilizado";
$trad["download_alert"] = "Download inacessível durante o dia (muito grande tamanho do arquivo)";
// Infos sur une personne
$trad["civilite"] = "Civilidade";
$trad["nom"] = "Sobrenome";
$trad["prenom"] = "Nome";
$trad["adresse"] = "Endereço";
$trad["codepostal"] = "CEP";
$trad["ville"] = "Cidade";
$trad["pays"] = "País";
$trad["telephone"] = "Telefone";
$trad["telmobile"] = "Celular";
$trad["mail"] = "Email";
$trad["fax"] = "Fax";
$trad["siteweb"] = "Site";
$trad["competences"] = "Competências";
$trad["hobbies"] = "Interesses";
$trad["fonction"] = "Função";
$trad["societe_organisme"] = "Organismo / Sociedade";
$trad["commentaire"] = "Comentario";
// Rechercher
$trad["preciser_text"] = "Favor especificar as palavras-chave de pelo menos 3 caracteres";
$trad["rechercher"] = "Buscar";
$trad["rechercher_date_crea"] = "Data de criação";
$trad["rechercher_date_crea_jour"] = "menos de um dia";
$trad["rechercher_date_crea_semaine"] = "menos de uma semana";
$trad["rechercher_date_crea_mois"] = "menos de um mês";
$trad["rechercher_date_crea_annee"] = "menos de um ano";
$trad["rechercher_espace"] = "Buscar no espaço";
$trad["recherche_avancee"] =  "Busca avançada";
$trad["recherche_avancee_mots_certains"] =  "qualquer palavra";
$trad["recherche_avancee_mots_tous"] =  "todas as palavras";
$trad["recherche_avancee_expression_exacte"] =  "frase exata";
$trad["recherche_avancee_champs"] =  "campos de pesquisa";
$trad["recherche_avancee_pas_concordance"] =  "Módulos e campos selecionados não coincidem. Obrigada a rever o seu acordo com a Pesquisa Avançada.";
$trad["mots_cles"] = "Palavras-chave";
$trad["liste_modules"] = "Módulos";
$trad["liste_champs"] = "Campos";
$trad["liste_champs_elements"] = "Elementos envolvidos";
$trad["aucun_resultat"] = "Nenhum resultado encontrado";
// Importer / Exporter des contact
$trad["exporter"] = "Exportar";
$trad["importer"] = "Importar";
$trad["export_import_users"] = "usuários";
$trad["export_import_contacts"] = "contatos";
$trad["export_format"] = "formato";
$trad["contact_separateur"] = "separador";
$trad["contact_delimiteur"] = "delimitador";
$trad["specifier_fichier"] = "Favor especificar um arquivo";
$trad["extension_fichier"] = "O tipo de arquivo é inválido. Deve ser do tipo";
$trad["format_fichier_invalide"] = "O formato de arquivo não corresponde ao tipo selecionado";
$trad["import_infos"] = "Selecione os campos Ágora apontados através das listas de cada coluna.";
$trad["import_infos_contact"] = "Os contatos serão atribuídos por defeito para o atual espaço.";
$trad["import_infos_user"] = "Se o login e a senha não são selecionados, eles serão criados automaticamente.";
$trad["import_alert"] = "Favor selecionar a coluna nome nas listas";
$trad["import_alert2"] = "Favor selecionar pelo menos um contato para importar";
$trad["import_alert3"] = "O campo ágora já foi selecionado em outra coluna (cada campo ágora pode ser selecionado somente uma vez)";
// Captcha
$trad["captcha"] = "Identificação visual";
$trad["captcha_info"] = "Favor digitar os 4 caracteres para a sua identificação";
$trad["captcha_alert_specifier"] = "Favor especificar identificação visual";
$trad["captcha_alert_erronee"] = "A identificação visual é errada";
// Gestion des inscriptions d'utilisateur
$trad["inscription_users"] = "se cadastrar no site";
$trad["inscription_users_info"] = "criar uma nova conta de usuário (validada por um administrador)";
$trad["inscription_users_espace"] = "inscrever-se para o espaço";
$trad["inscription_users_enregistre"] = "Sua assinatura foi registrado : será validado o mais rápido possível pelo administrador do espaço";
$trad["inscription_users_option_espace"] = "Permitir que os visitantes se cadastrar no espaço";
$trad["inscription_users_option_espace_info"] = "A inscrição está na página inicial do site. Deve ser validado pelo administrador do espaço.";
$trad["inscription_users_validation"] = "Validar entradas do usuário";
$trad["inscription_users_valider"] = "validar";
$trad["inscription_users_invalider"] = "Invalidar";
$trad["inscription_users_valider_mail"] = "Sua conta foi validada com sucesso no";
$trad["inscription_users_invalider_mail"] = "Sua conta não foi validado no";
// Connexion à un serveur LDAP
$trad["ldap_connexion_serveur"] = "Conectar a um servidor LDAP";
$trad["ldap_server"] = "endereço do servidor";
$trad["ldap_server_port"] = "porta do servidor";
$trad["ldap_server_port_infos"] = "''389'' por padrão";
$trad["ldap_admin_login"] = "Cadeia de conexão para admin";
$trad["ldap_admin_login_infos"] = "por exemplo ''uid=admin,ou=my_company''";
$trad["ldap_admin_pass"] = "Senha do administrador";
$trad["ldap_groupe_dn"] = "Grupo / base DN";
$trad["ldap_groupe_dn_infos"] = "Localização de usuários de diretório.<br> por exemplo ''ou=users,o=my_company''";
$trad["ldap_connexion_erreur"] = "Erro ao conectar ao servidor LDAP!";
$trad["ldap_import_infos"] = "Mostra a configuração do servidor LDAP no módulo de administração.";
$trad["ldap_crea_auto_users"] = "Criação automática de usuários após a identificação LDAP";
$trad["ldap_crea_auto_users_infos"] = "Criar automaticamente um usuário se ele estiver ausente da Ágora, mas presente no servidor LDAP : ele vai ser atribuído a áreas acessíveis a ''todos os usuários do site''.<br>Caso contrário, o usuário. não será criado.";
$trad["ldap_pass_cryptage"] = "Criptografia de senhas no servidor LDAP";
$trad["ldap_effacer_params"] = "Excluir configuração LDAP?";
$trad["ldap_pas_module_php"] = "PHP módulo para conexão a um servidor LDAP não está instalado!";




////	DIVERS
////

// Messages d'alert ou d'erreur
$trad["MSG_ALERTE_identification"] = "Login ou senha inválido";
$trad["MSG_ALERTE_dejapresent"] = "Conta atualmente usado com um endereço IP diferente ... (uma conta pode ser utilizada em um único computador ao mesmo tempo)";
$trad["MSG_ALERTE_adresseip"] = "O endereço IP que você usa não é permitido para esta conta";
$trad["MSG_ALERTE_pasaccesite"] = "O acesso ao site não é permitido para você, porque provavelmente nenhum espaço foi atribuído para você.";
$trad["MSG_ALERTE_captcha"] = "A identificação visual é errado";
$trad["MSG_ALERTE_acces_fichier"] = "Arquivo não disponível";
$trad["MSG_ALERTE_acces_dossier"] = "Pasta não disponível";
$trad["MSG_ALERTE_espace_disque"] = "O espaço para armazenar seus arquivos não é suficiente, você não pode adicionar arquivo";
$trad["MSG_ALERTE_type_interdit"] = "Tipo de arquivo não permitido";
$trad["MSG_ALERTE_taille_fichier"] = "O tamanho do arquivo é grande demais";
$trad["MSG_ALERTE_type_version"] = "Tipo de arquivo diferente do original";
$trad["MSG_ALERTE_deplacement_dossier"] = "Você não pode mover a pasta dentro de si ...!";
$trad["MSG_ALERTE_nom_dossier"] = "Um arquivo com o mesmo nome já existe. Confirme mesmo assim?";
$trad["MSG_ALERTE_nom_fichier"] = "Um arquivo com o mesmo nome já existe, mas não foi substituído";
$trad["MSG_ALERTE_chmod_stock_fichiers"] = "A gestão dos arquivos não está gravável. Favor fazer um ''chmod 775'' no arquivo ''stock_fichiers'' (acesso em leitura e escrita ao proprietário e ao grupo)";
$trad["MSG_ALERTE_nb_users"] = "Você não pode adicionar um novo usuário: limitado a";
$trad["MSG_ALERTE_miseajourconfig"] = "O arquivo de configuração (config.inc.php) não é gravável: Atualização impossível!";
$trad["MSG_ALERTE_miseajour"] = "Atualização concluída. É aconselhável reiniciar o navegador antes de reconectar-se.";
$trad["MSG_ALERTE_user_existdeja"] = "Login já existe: o usuário não foi criado";
$trad["MSG_ALERTE_temps_session"] = "Sua sessão expirou, favor reconectar-se";
$trad["MSG_ALERTE_specifier_nombre"] = "Favor especificar um númeroe";
// header menu
$trad["HEADER_MENU_espace_administration"] = "Administração do Site";
$trad["HEADER_MENU_espaces_dispo"] = "Espaços disponíveis";
$trad["HEADER_MENU_espace_acces_administration"] = "(Access Administração)";
$trad["HEADER_MENU_affichage_elem"] = "Mostrar os elementos";
$trad["HEADER_MENU_affichage_normal"] = "atribuído a mim";
$trad["HEADER_MENU_affichage_normal_infos"] = "Este é o display / padrão normal";
$trad["HEADER_MENU_affichage_auteur"] = "que eu criei";
$trad["HEADER_MENU_affichage_auteur_infos"] = "Para exibir apenas os itens que eu criei";
$trad["HEADER_MENU_affichage_tout"] = "Todos os elementos do espaço (admin)";
$trad["HEADER_MENU_affichage_tout_infos"] = "Para o administrador do espaço : para mostrar todos os elementos do espaço, mesmo aqueles que não são atribuídos para o administrador !";
$trad["HEADER_MENU_recherche_elem"] = "Buscar um elemento no espaço";
$trad["HEADER_MENU_sortie_agora"] = "Sair da Ágora";
$trad["HEADER_MENU_raccourcis"] = "Atalhos";
$trad["HEADER_MENU_seul_utilisateur_connecte"] = "Atualmente somente você está conectado";
$trad["HEADER_MENU_en_ligne"] = "Conectados";
$trad["HEADER_MENU_connecte_a"] = "conectado ao Site a";   // M. Bidule truc "connecté au site à" 12:45
$trad["HEADER_MENU_messenger"] = "Mensagem Instantânea";
$trad["HEADER_MENU_envoye_a"] = "Enviado a";
$trad["HEADER_MENU_ajouter_message"] = "Adicionar uma mensagem";
$trad["HEADER_MENU_specifier_message"] = "Favor especificar uma mensagem";
$trad["HEADER_MENU_enregistrer_conversation"] = "Salvar a conversa";
// Footer
$trad["FOOTER_page_generee"] = "página gerada em";
// Password_oublie
$trad["PASS_OUBLIE_preciser_mail"] = "Digite seu endereço de e-mail para receber seu nome de usuário e senha";
$trad["PASS_OUBLIE_mail_inexistant"] = "O e-mail não existe no banco de dados";
$trad["PASS_OUBLIE_mail_objet"] = "Conexão com o espaço";
$trad["PASS_OUBLIE_mail_contenu"] = "o seu login";
$trad["PASS_OUBLIE_mail_contenu_bis"] = "Clique aqui para redefinir a sua senha";
$trad["PASS_OUBLIE_prompt_changer_pass"] = "Favor especificar a sua nova senha";
$trad["PASS_OUBLIE_id_newpassword_expire"] = "O link para regenerar a senha expirou .. obrigado a reiniciar o procedimento";
$trad["PASS_OUBLIE_password_reinitialise"] = "Sua nova senha foi registrado !";
// menu_edit_objet
$trad["EDIT_OBJET_alert_aucune_selection"] = "Você deve selecionar pelo menos uma pessoa ou um espaço";
$trad["EDIT_OBJET_alert_pas_acces_perso"] = "Você não está atribuído ao elemento. validar todos os mesmos ?";
$trad["EDIT_OBJET_alert_ecriture"] = "Deve ter pelo menos uma pessoa ou um espaço alocado para a escrita";
$trad["EDIT_OBJET_alert_ecriture_limite_defaut"] = "Atenção! com acesso de escrita, todas as mensagens podem ser apagadas! \\n\\nPortanto, é recomendável para limitar o acesso de escrita";
$trad["EDIT_OBJET_alert_invite"] = "Favor especificar um nome ou apelido";
$trad["EDIT_OBJET_droit_acces"] = "Direitos de acesso";
$trad["EDIT_OBJET_espace_pas_module"] = "O módulo atual ainda não tenha sido adicionado a este espaço";
$trad["EDIT_OBJET_tous_utilisateurs"] = "Todos os usuários";
$trad["EDIT_OBJET_tous_utilisateurs_espaces"] = "todos os espaços";
$trad["EDIT_OBJET_espace_invites"] = "Os convidados deste espaço público";
$trad["EDIT_OBJET_aucun_users"] = "tualmente não há usuários neste espaço";
$trad["EDIT_OBJET_invite"] = "Seu nome/apelido";
$trad["EDIT_OBJET_admin_espace"] = "Administrador do Espaço: tem acesso de escrita a todos os elementos atribuídos ao espaço";
$trad["EDIT_OBJET_tous_espaces"] = "Visualizar todos os meus espaços";
$trad["EDIT_OBJET_notif_mail"] = "Enviar e-mail de notificação";
$trad["EDIT_OBJET_notif_mail_joindre_fichiers"] = "Anexar arquivos à notificação";
$trad["EDIT_OBJET_notif_mail_info"] = "Enviar e-mail de notificação para aqueles que têm acesso ao elemento";
$trad["EDIT_OBJET_notif_mail_selection"] = "Selecionar manualmente os destinatários de notificações";
$trad["EDIT_OBJET_notif_tous_users"] = "Visualizar mais usuários";
$trad["EDIT_OBJET_droits_ss_dossiers"] = "dar direitos iguais a todas as subpastas";
$trad["EDIT_OBJET_raccourci"] = "Atalho";
$trad["EDIT_OBJET_raccourci_info"] = "Mostrar um atalho no menu principal";
$trad["EDIT_OBJET_fichier_joint"] = "Adicionar arquivos (Fotos, vídeos..)";
$trad["EDIT_OBJET_inserer_fichier"] = "Mostrar na descrição";
$trad["EDIT_OBJET_inserer_fichier_info"] = "Mostrar imagem / vídeo / leitor de mp3 ... na descrição acima. A inserção é realizada após a validação do formulário.";
$trad["EDIT_OBJET_inserer_fichier_alert"] = "Clique ''Inserir'' para adicionar as imagens no texto / descrição";
$trad["EDIT_OBJET_demande_a_confirmer"] = "O seu pedido foi registrado. Irá ser confirmada em breve.";
// Formulaire d'installation
$trad["INSTALL_connexion_bdd"] = "Conexão para o banco de dados";
$trad["INSTALL_db_host"] = "Hostname do servidor";
$trad["INSTALL_db_name"] = "Nome da Base de Dados";
$trad["INSTALL_db_name_info"] = "Cuidado !!<br> Se o banco de dados já é existente na Ágora, ele será substituído (somente as tabelas que começam por ''gt_'')";
$trad["INSTALL_db_login"] = "Nome de usuário";
$trad["INSTALL_db_password"] = "Senha";
$trad["INSTALL_login_password_info"] = "Para conectar-se como administrado geral";
$trad["INSTALL_config_admin"] = "Administrator do Ágora";
$trad["INSTALL_config_espace"] = "Configuração do espaço principal";
$trad["INSTALL_erreur_acces_mysql"] = "No connection to the MySQL database";
$trad["INSTALL_erreur_acces_bdd"] = "Conexão com o banco de dados não foi estabelecida, confirme mesmo assim?";
$trad["INSTALL_erreur_agora_existe"] = "O banco de dados da Ágora já existe! Confirme mesmo assim e substituir as tabelas?";
$trad["INSTALL_confirm_version_php"] = "Ágora-Projeto requer um mínimo de PHP versão 4.3, confirme mesmo assim?";
$trad["INSTALL_confirm_version_mysql"] = "Ágora-Projeto requer uma versão mínima 4.2 do MySQL, confirme mesmo assim?";
$trad["INSTALL_confirm_install"] = "Confirmar instalação?";
$trad["INSTALL_install_ok"] = "Ágora-Project foi instalado. Por razões de segurança, lembre-se de apagar a pasta 'install' antes de começar!";




////	MODULE_PARAMETRAGE
////

// Menu principal
$trad["PARAMETRAGE_nom_module"] = "Parametrização";
$trad["PARAMETRAGE_nom_module_header"] = "Parametrização";
$trad["PARAMETRAGE_description_module"] = "Parametrização geral";
// Index.php
$trad["PARAMETRAGE_sav"] = "Salvar banco de dados e arquivos";
$trad["PARAMETRAGE_sav_alert"] = "A criação do arquivo de backup pode demorar alguns minutos ... e baixar algumas dezenas de minutos.";
$trad["PARAMETRAGE_sav_bdd"] = "Salvar banco de dados";
$trad["PARAMETRAGE_adresse_web_invalide"] = "Desculpe, mas o endereço de conexão não é válido: deve começar com HTTP://";
$trad["PARAMETRAGE_espace_disque_invalide"] = "O limite de espaço em disco deve ser um inteiro";
$trad["PARAMETRAGE_confirmez_modification_site"] = "confirmar as alterações?";
$trad["PARAMETRAGE_nom_site"] = "Nome do site";
$trad["PARAMETRAGE_adresse_web"] = "Endereço da conexão com o site";
$trad["PARAMETRAGE_footer_html"] = "Rodapé";
$trad["PARAMETRAGE_footer_html_info"] = "Para incluir instrumentos estatísticos por exemplo";
$trad["PARAMETRAGE_langues"] = "Idioma padrão";
$trad["PARAMETRAGE_timezone"] = "Fuso horário";
$trad["PARAMETRAGE_nom_espace"] = "Nome do espaço principal";
$trad["PARAMETRAGE_limite_espace_disque"] = "espaço em disco disponível para armazenar arquivos";
$trad["PARAMETRAGE_logs_jours_conservation"] = "Vida de prateleira de LOGS";
$trad["PARAMETRAGE_mode_edition"] = "Editar os elementos";
$trad["PARAMETRAGE_edition_popup"] = "em uma janela pop-up";
$trad["PARAMETRAGE_edition_iframe"] = "em um iframe (mesma janela)";
$trad["PARAMETRAGE_skin"] = "Cor da interface (fundo dos elementos, menus, etc.)";
$trad["PARAMETRAGE_noir"] = "Preto";
$trad["PARAMETRAGE_blanc"] = "Branco";
$trad["PARAMETRAGE_erreur_fond_ecran_logo"] = "A imagem do papel de parede e o logo deve estar no formato JPG ou PNG.";
$trad["PARAMETRAGE_suppr_fond_ecran"] = "Apagar o papel de parede?";
$trad["PARAMETRAGE_logo_footer"] = "Logo no rodapé";
$trad["PARAMETRAGE_logo_footer_url"] = "URL";
$trad["PARAMETRAGE_editeur_text_mode"] = "Modo do editor de texto (TinyMCE)";
$trad["PARAMETRAGE_editeur_text_minimal"] = "Mínimo";
$trad["PARAMETRAGE_editeur_text_complet"] = "Completo (+ paineis + medias + colar do Word)";
$trad["PARAMETRAGE_messenger_desactive"] = "Mensagens instantâneas ativadas";
$trad["PARAMETRAGE_agenda_perso_desactive"] = "Calendários pessoais ativado por padrão";
$trad["PARAMETRAGE_agenda_perso_desactive_infos"] = "Adicionar uma agenda pessoal para a criação de um utilizador. O calendário pode, contudo, ser desactivado depois, quando se muda a conta do utilizador.";
$trad["PARAMETRAGE_libelle_module"] = "Nome dos módulos na barra de menu";
$trad["PARAMETRAGE_libelle_module_masquer"] = "Esconder";
$trad["PARAMETRAGE_libelle_module_icones"] = "en cima de cada ícone de módulo";
$trad["PARAMETRAGE_libelle_module_page"] = "uapenas para o módulo atual";
$trad["PARAMETRAGE_tri_personnes"] = "Ordenar usuários e contatos com";
$trad["PARAMETRAGE_versions"] = "Versões";
$trad["PARAMETRAGE_version_agora_maj"] = "atualizado o";
$trad["PARAMETRAGE_fonction_mail_desactive"] = "função do PHP para enviar e-mails! desativada!";
$trad["PARAMETRAGE_fonction_mail_infos"] = "Alguns hospedagens de site desativam a função PHP para enviar e-mails por razões de segurança ou por saturação de servidores (SPAM)";
$trad["PARAMETRAGE_fonction_image_desactive"] = "Função de manipulação de imagens e miniaturas (PHP GD2): desativada!";




////	MODULE_LOG
////

// Menu principal
$trad["LOGS_nom_module"] = "Logs";
$trad["LOGS_nom_module_header"] = "Logs";
$trad["LOGS_description_module"] = "Logs - Log de ​​eventos";
// Index.php
$trad["LOGS_filtre"] = "Filtro";
$trad["LOGS_date_heure"] = "Data / Hora";
$trad["LOGS_espace"] = "Space";
$trad["LOGS_module"] = "Módulo";
$trad["LOGS_action"] = "Action";
$trad["LOGS_utilisateur"] = "Usuário";
$trad["LOGS_adresse_ip"] = "IP";
$trad["LOGS_commentaire"] = "comentário";
$trad["LOGS_no_logs"] = "log Não";
$trad["LOGS_filtre_a_partir"] = "filtrada da";
$trad["LOGS_chercher"] = "Pesquisar";
$trad["LOGS_chargement"] = "Carregando dados";
$trad["LOGS_connexion"] = "conexão";
$trad["LOGS_deconnexion"] = "sair";
$trad["LOGS_consult"] = "consulta";
$trad["LOGS_consult2"] = "download";
$trad["LOGS_ajout"] = "adicionado";
$trad["LOGS_suppr"] = "supressão";
$trad["LOGS_modif"] = "mudança";




////	MODULE_ESPACE
////

// Menu principal
$trad["ESPACES_nom_module"] = "Espaços";
$trad["ESPACES_nom_module_header"] = "Espaços";
$trad["ESPACES_description_module"] = "Espaços do site";
$trad["ESPACES_description_module_infos"] = "O site (ou espaço principal) pode ser subdividido em vários espaços";
// Header_menu.inc.php
$trad["ESPACES_gerer_espaces"] = "Geranciar os espaços do site";
$trad["ESPACES_parametrage"] = "Parametrização do espaço";
$trad["ESPACES_parametrage_infos"] = "Parametrização do espaço (descrição, módulos, usuários, etc)";
// Index.php
$trad["ESPACES_confirm_suppr_espace"] = "Confirmar apagar? Nota, os dados atribuído a esse espaço só serão perdidos para sempre!";
$trad["ESPACES_espace"] = "espaço";
$trad["ESPACES_espaces"] = "espaços";
$trad["ESPACES_definir_acces"] = "Para ser definido!";
$trad["ESPACES_modules"] = "Módulos";
$trad["ESPACES_ajouter_espace"] = "Adicionar um espaço";
$trad["ESPACES_supprimer_espace"] = "Apagar espaço";
$trad["ESPACES_aucun_espace"] = "Nenhum espaço por enquanto";
$trad["MSG_ALERTE_suppr_espace_impossible"] = "Você não pode apagar o espaço atual";
// Espace_edit.php
$trad["ESPACES_gestion_acces"] = "Usuários atribuídos ao espaço ";
$trad["ESPACES_selectionner_module"] = "Você deve selecionar pelo menos um módulo";
$trad["ESPACES_modules_espace"] = "Módulos no espaço";
$trad["ESPACES_modules_classement"] = "Mover-se para definir a ordem de exibição dos módulos";
$trad["ESPACES_selectionner_utilisateur"] = "Selecione alguns usuários, todos os usuários ou abrir o espaço ao público";
$trad["ESPACES_espace_public"] = "Espaço público";
$trad["ESPACES_public_infos"] = "Fornece acesso a pessoas que não tenham contas no site: ''Convidados''. Possibilidade de especificar uma senha para proteger o acesso.";
$trad["ESPACES_invitations_users"] = "Os usuários podem enviar convites por e-mail";
$trad["ESPACES_invitations_users_infos"] = "Todos os usuários podem enviar e-mail convites para se juntar ao espaço";
$trad["ESPACES_tous_utilisateurs"] = "Todos os usuários do site";
$trad["ESPACES_utilisation"] = " Usuários";
$trad["ESPACES_utilisation_info"] = "Acesso normal ao espaço";
$trad["ESPACES_administration"] = "Administrator";
$trad["ESPACES_administration_info"] = "Administrador do espaço: Acceso em escrita a todos os elementos do espaço + enviar convites por email + criação de usuários no espaço";
$trad["ESPACES_creer_agenda_espace"] = "Criar um calendário para o espaço";
$trad["ESPACES_creer_agenda_espace_info"] = "Isto pode ser útil se os calendários dos usuários estão desativados.<br>O calendário irá ter o mesmo nome que o espaço e este será um calendário de recursos.";




////	MODULE_UTILISATEUR
////

// Menu principal
$trad["UTILISATEURS_nom_module"] = "Usuários";
$trad["UTILISATEURS_nom_module_header"] = "Usuários";
$trad["UTILISATEURS_description_module"] = "Usuários";
$trad["UTILISATEURS_ajout_utilisateurs_groupe"] = "Os usuários também podem criar grupos";
// Index.php
$trad["UTILISATEURS_utilisateurs_site"] = "Usuários do site";
$trad["UTILISATEURS_gerer_utilisateurs_site"] = "Geranciar os Usuários do site";
$trad["UTILISATEURS_utilisateurs_site_infos"] = "Todos os usuários do site, todos os espaços combinados";
$trad["UTILISATEURS_utilisateurs_espace"] = "Usuários do espaço";
$trad["UTILISATEURS_confirm_suppr_utilisateur"] = "Confirmar exclusão do Usuário? Cuidado! Todos os dados sobre ele serão perdidos para sempre!!";
$trad["UTILISATEURS_confirm_desaffecter_utilisateur"] = "Confirmar a exclusão do espaço do usuário atual?";
$trad["UTILISATEURS_suppr_definitivement"] = "Apagar definitivamente";
$trad["UTILISATEURS_desaffecter"] = "Excluir do espaço";
$trad["UTILISATEURS_tous_user_affecte_espace"] = "Todos os usuários do site são atribuídos a esse espaço: não há exclusão possível";
$trad["UTILISATEURS_utilisateur"] = "Usuário";
$trad["UTILISATEURS_utilisateurs"] = "Usuários";
$trad["UTILISATEURS_affecter_utilisateur"] = "Adicionar um usuário existente no espaço";
$trad["UTILISATEURS_ajouter_utilisateur"] = "Adicionar um usuário";
$trad["UTILISATEURS_ajouter_utilisateur_site"] = "Criar um usuário no site: nenhum espaço atribuído por defeito!";
$trad["UTILISATEURS_ajouter_utilisateur_espace"] = "Criar um usuário e adicioná-lo ao espaço atual";
$trad["UTILISATEURS_envoi_coordonnees"] = "Enviar login e senha";
$trad["UTILISATEURS_envoi_coordonnees_infos"] = "Enviar a usuários (via email)<br> seu login e uma <u>nova</u> senha";
$trad["UTILISATEURS_envoi_coordonnees_infos2"] = "Envie e-mail para novos usuários seu nome de usuário e senha";
$trad["UTILISATEURS_envoi_coordonnees_confirm"] = "Cuidado! Senhas serão reinicializadas ! Confirme mesmo assim?";
$trad["UTILISATEURS_mail_coordonnees"] = "Detalhes da conexão";
$trad["UTILISATEURS_aucun_utilisateur"] = "Nenhum usuário atribuído a esse espaço por enquanto";
$trad["UTILISATEURS_derniere_connexion"] = "Última conexão";
$trad["UTILISATEURS_liste_espaces"] = "Espaços do usuário";
$trad["UTILISATEURS_aucun_espace"] = "Nenhum espaço";
$trad["UTILISATEURS_admin_general"] = "Administrador geral do site";
$trad["UTILISATEURS_admin_espace"] = "Administrador do espaço";
$trad["UTILISATEURS_user_espace"] = "Usuários do espaço";
$trad["UTILISATEURS_user_site"] = "Usuários do site";
$trad["UTILISATEURS_pas_connecte"] = "Ainda não conectado";
$trad["UTILISATEURS_modifier"] = "Modificar o usuário";
$trad["UTILISATEURS_modifier_mon_profil"] = "Modificar meu perfil";
$trad["UTILISATEURS_pas_suppr_dernier_admin_ge"] = "Você não pode excluir o último administrator geral do site!";
// Invitation.php
$trad["UTILISATEURS_envoi_invitation"] = "Convidar alguém para juntar no espaço";
$trad["UTILISATEURS_envoi_invitation_info"] = "O convite será enviado por e-mail";
$trad["UTILISATEURS_objet_mail_invitation"] = "Convite de "; // ..Jean DUPOND
$trad["UTILISATEURS_admin_invite_espace"] = "convida você em "; // Jean DUPOND "vous invite à rejoindre l'espace" Mon Espace
$trad["UTILISATEURS_confirmer_invitation"] = "Clique aqui para confirmar o convite";
$trad["UTILISATEURS_invitation_a_confirmer"] = "aguardando confirmação de convites";
$trad["UTILISATEURS_id_invitation_expire"] = "O link para o seu convite expirou...";
$trad["UTILISATEURS_invitation_confirmer_password"] = "Obrigado por escolher a senha antes de confirmar o seu convite";
$trad["UTILISATEURS_invitation_valide"] = "Seu convite foi validado !";
// groupes.php
$trad["UTILISATEURS_groupe_espace"] = "grupos de usuários do espaço";
$trad["UTILISATEURS_groupe_site"] = "grupos de usuários";
$trad["UTILISATEURS_groupe_infos"] = "editar os grupos de usuários";
$trad["UTILISATEURS_groupe_espace_infos"] = "Usuários ativos têm acesso a todas as espaços selecionados (outros não são ativados)";
$trad["UTILISATEURS_droit_gestion_groupes"] = "Cada grupo pode ser modificado por seu autor ou pelo administrador geral";
// Utilisateur_affecter.php
$trad["UTILISATEURS_preciser_recherche"] = "Favor indicar um sobrenome, um nome ou um endereço de e-mail";
$trad["UTILISATEURS_affecter_user_confirm"] = "Confirme a atribuição do usuário ao espaço?";
$trad["UTILISATEURS_rechercher_user"] = "Buscar um usuário para adicioná-lo ao espaço";
$trad["UTILISATEURS_tous_users_affectes"] = "Todos os usuários do site já estam atribuídos a este espaço";
$trad["UTILISATEURS_affecter_user"] = "Atribuir um usuário ao espaço:";
$trad["UTILISATEURS_aucun_users_recherche"] = "Nenhum usuário encontrado";
// Utilisateur_edit.php & CO
$trad["UTILISATEURS_specifier_nom"] = "Favor especificar um sobrenome";
$trad["UTILISATEURS_specifier_prenom"] = "Favor especificar um nome";
$trad["UTILISATEURS_specifier_identifiant"] = "Favor especificar um usuário";
$trad["UTILISATEURS_specifier_pass"] = "Favor especificar uma senha";
$trad["UTILISATEURS_pas_fichier_photo"] = "Você não especificou uma foto!";
$trad["UTILISATEURS_langues"] = "Idioma";
$trad["UTILISATEURS_agenda_perso_active"] = "Agenda pessoal activado";
$trad["UTILISATEURS_agenda_perso_active_infos"] = "Se está activado, a agenda pessoal permanece <u>sempre</u> acessível ao usuário, mesmo que o módulo Agenda do espaço atual está desactivado.";
$trad["UTILISATEURS_espace_connexion"] = "Espaço visualizado quando conecta-se";
$trad["UTILISATEURS_notification_mail"] = "Enviar e-mail de notificação da criação";
$trad["UTILISATEURS_alert_notification_mail"] = "Lembre-se de especificar um e-mail!";
$trad["UTILISATEURS_adresses_ip"] = "Endereço IP de Controle";
$trad["UTILISATEURS_info_adresse_ip"] = "Se você especificar uma (ou mais) endereços IP, o usuário poderá se conectar apenas se utiliza os endereços IP especificados";
$trad["UTILISATEURS_ip_invalide"] = "Endereço IP inválido";
$trad["UTILISATEURS_identifiant_deja_present"] = "O login já existe. Favor especificar outro.";
$trad["UTILISATEURS_mail_deja_present"] = "O endereço de email já existe. Favor especificar outro.";
$trad["UTILISATEURS_mail_objet_nouvel_utilisateur"] = "Nova conta em";  // "...sur" l'Agora machintruc
$trad["UTILISATEURS_mail_nouvel_utilisateur"] = "Uma nova conta foi atribuída a você em";  // idem
$trad["UTILISATEURS_mail_infos_connexion"] = "Conexão com o seu login e senha a abaixo contras";
$trad["UTILISATEURS_mail_infos_connexion2"] = "Obrigado a manter este e-mail para seus registros.";
// Utilisateur_Messenger.php
$trad["UTILISATEURS_gestion_messenger_livecounter"] = "Geranciar Mensagens Instantâneas";
$trad["UTILISATEURS_visibilite_messenger_livecounter"] = "Usuários que podem me ver online e discutir comigo em mensagens instantâneas";
$trad["UTILISATEURS_aucun_utilisateur_messenger"] = "Nenhum usuário nesse momento";
$trad["UTILISATEURS_voir_aucun_utilisateur"] = "Nenhum usuário pode ver-me";
$trad["UTILISATEURS_voir_tous_utilisateur"] = "Todos os usuários podem ver-me";
$trad["UTILISATEURS_voir_certains_utilisateur"] = "Alguns usuários poderão me ver";




////	MODULE_TABLEAU BORD
////

// Menu principal + options du module
$trad["TABLEAU_BORD_nom_module"] = "Actualidades & elementos novos";
$trad["TABLEAU_BORD_nom_module_header"] = "Actualidades";
$trad["TABLEAU_BORD_description_module"] = "Actualidades & elementos novos";
$trad["TABLEAU_BORD_ajout_actualite_admin"] = "Apenas o administrador pode adicionar actualidades";
// Index.php
$trad["TABLEAU_BORD_new_elems"] = "novidades";
$trad["TABLEAU_BORD_new_elems_bulle"] = "Elementos criados durante o período selecionado";
$trad["TABLEAU_BORD_new_elems_realises"] = "elementos atuais";
$trad["TABLEAU_BORD_new_elems_realises_bulle"] = "Eventos e tarefas <br>hoje";
$trad["TABLEAU_BORD_plugin_connexion"] = "desde o minha última conexão";
$trad["TABLEAU_BORD_plugin_jour"] = "hoje";
$trad["TABLEAU_BORD_plugin_semaine"] = "esta semana";
$trad["TABLEAU_BORD_plugin_mois"] = "este mês";
$trad["TABLEAU_BORD_autre_periode"] = "Otro período";
$trad["TABLEAU_BORD_pas_nouveaux_elements"] = "Nenhum elemento selecionado para o período";
$trad["TABLEAU_BORD_actualites"] = "Actualidades";
$trad["TABLEAU_BORD_actualite"] = "actualidade";
$trad["TABLEAU_BORD_actualites"] = "actualidades";
$trad["TABLEAU_BORD_ajout_actualite"] = "Adicionar uma actualidade";
$trad["TABLEAU_BORD_actualites_offline"] = "Actualidades arquivadas";
$trad["TABLEAU_BORD_pas_actualites"] = "Nenhuma actualidade por enquanto";
// Actualite_edit.php
$trad["TABLEAU_BORD_mail_nouvelle_actualite_cree"] = "Nova actualidade criada por ";
$trad["TABLEAU_BORD_ala_une"] = "Mostrar em primeira página";
$trad["TABLEAU_BORD_ala_une_info"] = "Sempre mostrar esta actualidade em primeiro";
$trad["TABLEAU_BORD_offline"] = "Arquivado";
$trad["TABLEAU_BORD_date_online_auto"] = "programados on-line";
$trad["TABLEAU_BORD_date_online_auto_alerte"] = "A notícia foi arquivada na expectativa de sua automática on-line";
$trad["TABLEAU_BORD_date_offline_auto"] = "programada arquivamento";




////	MODULE_AGENDA
////

// Menu principal
$trad["AGENDA_nom_module"] = "Agendas";
$trad["AGENDA_nom_module_header"] = "Agendas";
$trad["AGENDA_description_module"] = "Agendas pessoais e agendas partilhadas";
$trad["AGENDA_ajout_agenda_ressource_admin"] = "Apenas o administrador pode adicionar agendas de recursos";
$trad["AGENDA_ajout_categorie_admin"] = "Apenas o administrador pode adicionar categorias de evento";
// Index.php
$trad["AGENDA_agendas_visibles"] = "Agendas disponíveis (pessoal e recursos)";
$trad["AGENDA_afficher_tous_agendas"] = "Mostrar todos os calendários";
$trad["AGENDA_masquer_tous_agendas"] = "Esconder todos os calendários";
$trad["AGENDA_cocher_tous_agendas"] = "Confira/atirar todos os calendários";
$trad["AGENDA_cocher_agendas_users"] = "Confira/atirar usuários";
$trad["AGENDA_cocher_agendas_ressources"] = "Confira/atirar os recursos";
$trad["AGENDA_imprimer_agendas"] = "Imprimir a/as agendas";
$trad["AGENDA_imprimer_agendas_infos"] = "imprimir de modo paisagem";
$trad["AGENDA_ajouter_agenda_ressource"] = "Adicionar uma agenda de recurso";
$trad["AGENDA_ajouter_agenda_ressource_bis"] = "Adicionar uma agenda de recurso: quarto, carro, vídeo, etc.";
$trad["AGENDA_exporter_ical"] = "Exportar os eventos (formato iCal)";
$trad["AGENDA_exporter_ical_mail"] = "Exportar os eventos por e-mail (iCal)";
$trad["AGENDA_exporter_ical_mail2"] = "Para integrar o calendário IPHONE, ANDROID, OUTLOOK, GOOGLE CALENDAR...";
$trad["AGENDA_importer_ical"] = "Importar os eventos (iCal)";
$trad["AGENDA_importer_ical_etat"] = "Estado";
$trad["AGENDA_importer_ical_deja_present"] = "Para importar";
$trad["AGENDA_importer_ical_a_importer"] = "Te importeren";
$trad["AGENDA_suppr_anciens_evt"] = "Apagar os eventos anteriores";
$trad["AGENDA_confirm_suppr_anciens_evt"] = "Tem certeza de que deseja apagar permanentemente os eventos que precederam o período indicado ?";
$trad["AGENDA_ajouter_evt_heure"] = "Adicionar um evento a";
$trad["AGENDA_ajouter_evt_jour"] = "Adicionar um evento nesta data";
$trad["AGENDA_evt_jour"] = "Dia";
$trad["AGENDA_evt_semaine"] = "Semana";
$trad["AGENDA_evt_semaine_w"] = "Semana de Trabalho";
$trad["AGENDA_evt_mois"] = "Mês";
$trad["AGENDA_num_semaine"] = "Semana"; //...5
$trad["AGENDA_voir_num_semaine"] = "Ver la semana n°"; //...5
$trad["AGENDA_periode_suivante"] = "período seguinte";
$trad["AGENDA_periode_precedante"] = "Período anterior";
$trad["AGENDA_affectations_evt"] = "Eventos na agenda de ";
$trad["AGENDA_affectations_evt_autres"] = "+ otras agendas não visíveis";
$trad["AGENDA_affectations_evt_non_confirme"] = "A aguardar confirmação : ";
$trad["AGENDA_evenements_proposes_pour_agenda"] = "Eventos propostos para"; // "Videoprojecteur" / "salle de réunion" / etc.
$trad["AGENDA_evenements_proposes_mon_agenda"] = "Eventos propostos para a minha agenda";
$trad["AGENDA_evenement_propose_par"] = "Proposto por";  // "Proposé par" M. Bidule
$trad["AGENDA_evenement_integrer"] = "Incorporar o evento para a agenda ?";
$trad["AGENDA_evenement_pas_integrer"] = "Apagar o evento proposto ?";
$trad["AGENDA_supprimer_evt_agenda"] = "Apagar ?";
$trad["AGENDA_supprimer_evt_agendas"] = "Apagar eventos de todas as agendas ?";
$trad["AGENDA_supprimer_evt_date"] = "Apagar essa data ?";
$trad["AGENDA_confirm_suppr_evt"] = "Apagar o evento nesta agenda ?";
$trad["AGENDA_confirm_suppr_evt_tout"] = "Apagar o evento de todas as agendas nas quais ele é atribuído ?";
$trad["AGENDA_confirm_suppr_evt_date"] = "Apagar essa data, de todas as agendas nas quais ele é atribuído ?";
$trad["AGENDA_evt_prive"] = "Evento privado";
$trad["AGENDA_aucun_agenda_visible"] = "Nenhuma agenda";
$trad["AGENDA_evt_proprio"] = "Eventos que criei";
$trad["AGENDA_evt_proprio_inaccessibles"] = "Mostrar apenas os que criei para agendas nas quais não tenho acesso";
$trad["AGENDA_aucun_evt"] = "Não há eventos";
$trad["AGENDA_proposer"] = "Propor um evento";
$trad["AGENDA_synthese"] = "Síntese de agendas";
$trad["AGENDA_pourcent_agendas_occupes"] = "Agendas ocupadas";  // Agendas occupés : 2/5
$trad["AGENDA_agendas_details"] = "Veja agendas em detalhe";
$trad["AGENDA_agendas_details_masquer"] = "Ocultar agendas detalhes";
// Evenement.php
$trad["AGENDA_categorie"] = "Categoria";
$trad["AGENDA_visibilite"] = "Visibilidade";
$trad["AGENDA_visibilite_public"] = "público";
$trad["AGENDA_visibilite_public_cache"] = "público mas com detalhes ocultos";
$trad["AGENDA_visibilite_prive"] = "privado";
//  Agenda_edit.php
$trad["AGENDA_affichage_evt"] = "Mostrar eventos";
$trad["AGENDA_affichage_evt_border"] = "Fronteira com a cor da categoria";
$trad["AGENDA_affichage_evt_background"] = "Fundo com a cor da categoria";
$trad["AGENDA_plage_horaire"] = "Intervalo de tempo";
// Evenement_edit.php
$trad["AGENDA_periodicite"] = "Evento periódico";
$trad["AGENDA_period_non"] = "Evento pontual";
$trad["AGENDA_period_jour_semaine"] = "Todas as semanas";
$trad["AGENDA_period_jour_mois"] = "Dias do mês";
$trad["AGENDA_period_mois"] = "Todos os meses";
$trad["AGENDA_period_mois_xdumois"] = "do mês"; // Le 21 du mois
$trad["AGENDA_period_annee"] = "Todos os anos";
$trad["AGENDA_period_mois_xdeannee"] = "do ano"; // Le 21/12 de l'année
$trad["AGENDA_period_date_fin"] = "Fim da periodicidade";
$trad["AGENDA_exception_periodicite"] = "Exceção de periodicidade";
$trad["AGENDA_agendas_affectations"] = "Atribuição de agendas";
$trad["AGENDA_verif_nb_agendas"] = "Favor selecionar ao menos uma agenda";
$trad["AGENDA_mail_nouvel_evenement_cree"] = "Novo evento criado por ";
$trad["AGENDA_input_proposer"] = "Enviar o evento ao proprietário da agenda";
$trad["AGENDA_input_affecter"] = "Adicionar o evento para a agenda";
$trad["AGENDA_info_proposer"] = "Enviar o evento (você não tem acesso de gravação para a agenda)";
$trad["AGENDA_info_pas_modif"] = "Modificação não autorizada, porque você não tem acesso de gravação para esta agenda";
$trad["AGENDA_visibilite_info"] = "<u>Público</u>: Visíveis para os usuários que tem acesso a ler (ou +) para as agendas onde o evento é atribuído.<br><u>Público, mas os detalhes ocultos</u>: Mesmo, mas aqueles que têm acesso em somente leitura, consulte o horário do evento, mas não os detalhes.<br><u>Privado</u>: Visível apenas para aqueles que têm acesso de gravação para os diários que ele é atribuído.";
$trad["AGENDA_edit_limite"] = "Você não é o autor do evento e ele foi atribuído para agendas que não são graváveis por você: você só pode controlar as atribuições ao seu agenda(s)";
$trad["AGENDA_creneau_occupe"] = "O slot já está ocupado na agenda :";
// Categories.php
$trad["AGENDA_gerer_categories"] = "Geranciar categorias de eventos";
$trad["AGENDA_categories_evt"] = "Categorias de eventos";
$trad["AGENDA_droit_gestion_categories"] = "Cada categoria pode ser modificado por seu autor ou pelo administrador geral";




////	MODULE_FICHIER
////

// Menu principal
$trad["FICHIER_nom_module"] = "Gerenciador de arquivos";
$trad["FICHIER_nom_module_header"] = "Arquivos";
$trad["FICHIER_description_module"] = "Gerenciador de arquivos";
// Index.php
$trad["FICHIER_ajouter_fichier"] = "Adicionar arquivos";
$trad["FICHIER_ajouter_fichier_alert"] = "Arquivos do servidor não gravável! Obrigado para manter contato com o administrador";
$trad["FICHIER_telecharger_dossier"] = "Baixar os arquivos";
$trad["FICHIER_telecharger_selection"] = "Selecção de downloads";
$trad["FICHIER_telecharger_dossier_confirm"] = "Confirmar baixar os arquivos?";
$trad["FICHIER_voir_images"] = "Mostrar as imagens";
$trad["FICHIER_defiler_images"] = "Rolar automaticamente as imagens";
$trad["FICHIER_pixels"] = "pixels";
$trad["FICHIER_nb_versions_fichier"] = "versões do arquivo"; // n versions du fichier
$trad["FICHIER_ajouter_versions_fichier"] = "adicionar nova versão do arquivo";
$trad["FICHIER_apercu"] = "Visualizar"; // n versions du fichier
$trad["FICHIER_aucun_fichier"] = "Nenhum arquivo no momento";
// fichier_edit_ajouter.php  &  Fichier_edit.php
$trad["FICHIER_limite_chaque_fichier"] = "Os arquivos não devem ultrapassar"; // ...2 Mega Octets
$trad["FICHIER_optimiser_images"] = "Limite de tamanho da imagem a "; // ..1024*768 pixels
$trad["FICHIER_maj_nom"] = "O nome do arquivo será substituído pela nova versão";
$trad["FICHIER_ajout_plupload"] = "Adição múltipl";
$trad["FICHIER_ajout_classique"] = "Adição clássico";
$trad["FICHIER_erreur_nb_fichiers"] = "Favor selecionar menos arquivos";
$trad["FICHIER_erreur_taille_fichier"] = "Arquivo grande demais";
$trad["FICHIER_erreur_non_geree"] = "Erro não tratado";
$trad["FICHIER_ajout_multiple_info"] = "Pressione o 'Shift' or 'Ctrl' para selecionar vários arquivos";
$trad["FICHIER_select_fichier"] = "Selecionar os arquivos";
$trad["FICHIER_annuler"] = "Cancelar";
$trad["FICHIER_selectionner_fichier"] = "Favor Selecione pelo menos um arquivo";
$trad["FICHIER_nouvelle_version"] = "já existe, uma nova versão foi adicionada.";  // mon_fichier.gif "existe déjà"...
$trad["FICHIER_mail_nouveau_fichier_cree"] = "Novo(s) arquivo(s) criado(s) por ";
$trad["FICHIER_mail_fichier_modifie"] = "Arquivo modificado por ";
$trad["FICHIER_contenu"] = "conteúdo";
// Versions_fichier.php
$trad["FICHIER_versions_de"] = "Versões de"; // versions de fichier.gif
$trad["FICHIER_confirmer_suppression_version"] = "Confirmar apagar esta versão?";
// Images.php
$trad["FICHIER_info_https_flash"] = "Para não mais ter o recado ''Você deseja visualizar os elementos não seguros?'':<br> <br>> Clique em ''Ferramentas'' <br>> clique em ''Opções da Internet''<br>> clique em ''Segurança Guia'' < br />> Escolhe ''Zona Internet''<br>> Nível Personalizado<br>> Active ''Mostrar um conteúdo mixto'' em ''Diverso''";
$trad["FICHIER_img_precedante"] = "Imagem anterior (seta a esquerda do teclado)";
$trad["FICHIER_img_suivante"] = "Imagem seguinte (seta a direita do teclado / barra de espaço)";
$trad["FICHIER_rotation_gauche"] = "Girar para a esquerda [seta para cima]";
$trad["FICHIER_rotation_droite"] = "Girar para a direita [Seta para baixo]";
$trad["FICHIER_zoom"] = "Zoom in / Zoom out";
// Video.php
$trad["FICHIER_voir_videos"] = "Ver as vídeos";
$trad["FICHIER_regarder"] = "Ver a vídeo";
$trad["FICHIER_video_precedante"] = "Vídeo anterior";
$trad["FICHIER_video_suivante"] = "Vídeo seguinte";
$trad["FICHIER_video_mp4_flv"] = "<a href='http://get.adobe.com/flashplayer'>Flash</a> não instalado.";




////	MODULE_FORUM
////

// Menu principal
$trad["FORUM_nom_module"] = "Fórum";
$trad["FORUM_nom_module_header"] = "Fórum";
$trad["FORUM_description_module"] = "Fórum";
$trad["FORUM_ajout_sujet_admin"] = "Apenas o administrador pode adicionar assuntos";
$trad["FORUM_ajout_sujet_theme"] = "Os usuários também podem adicionar temas";
// TRI
$trad["tri"]["date_dernier_message"] = "última mensagem";
// Index.php & Sujet.php
$trad["FORUM_sujet"] = "assunto";
$trad["FORUM_sujets"] = "assuntos";
$trad["FORUM_message"] = "mensagem";
$trad["FORUM_messages"] = "mensagens";
$trad["FORUM_dernier_message"] = "último de";
$trad["FORUM_ajouter_sujet"] = "Adicionar um assunto";
$trad["FORUM_voir_sujet"] = "Mostrar o assunto";
$trad["FORUM_repondre"] = "Adicionar uma mensagem";
$trad["FORUM_repondre_message"] = "Responder a esta mensagem";
$trad["FORUM_repondre_message_citer"] = "Responder e citar esta mensagem";
$trad["FORUM_aucun_sujet"] = "Nenhum assunto por enquanto";
$trad["FORUM_pas_message"] = "Nenhuma mensagem por enquanto";
$trad["FORUM_aucun_message"] = "Nenhuma mensagem";
$trad["FORUM_confirme_suppr_message"] = "Confirmar apagar a mensagem (e respectivas sub-mensagens)?";
$trad["FORUM_retour_liste_sujets"] = "Regressar à lista de assuntos";
$trad["FORUM_notifier_dernier_message"] = "Avise-me por e-mail";
$trad["FORUM_notifier_dernier_message_info"] = "Avise-me por e-mail a cada nova mensagem";
// Sujet_edit.php  &  Message_edit.php
$trad["FORUM_infos_droits_acces"] = "Para participar do tópico, você deve ter acesso de escrita";
$trad["FORUM_theme_espaces"] = "Áreas onde o tema está disponível";
$trad["FORUM_mail_nouveau_sujet_cree"] = "Novo assunto criado por ";
$trad["FORUM_mail_nouveau_message_cree"] = "Nova mensagem criado por ";
// Themes
$trad["FORUM_theme_sujet"] = "Assunto";
$trad["FORUM_accueil_forum"] = "Índice do Fórum";
$trad["FORUM_sans_theme"] = "sem assunto";
$trad["FORUM_themes_gestion"] = "Gerenciar os assuntos de tema";
$trad["FORUM_droit_gestion_themes"] = "Cada assunto pode ser modificado por seu autor ou pelo administrador geral";
$trad["FORUM_confirm_suppr_theme"] = "Atenção! Os sujeitos envolvidos não terá nenhum tema! Confirmar exclusão?";




////	MODULE_TACHE
////

// Menu principal
$trad["TACHE_nom_module"] = "Tarefas";
$trad["TACHE_nom_module_header"] = "Tarefas";
$trad["TACHE_description_module"] = "Tarefas";
// TRI
$trad["tri"]["priorite"] = "Prioridade";
$trad["tri"]["avancement"] = "Progresso";
$trad["tri"]["date_debut"] = "Data de início";
$trad["tri"]["date_fin"] = "Data final";
// Index.php
$trad["TACHE_ajouter_tache"] = "Adicionar tarefa";
$trad["TACHE_aucune_tache"] = "Nenhuma tarefa por enquanto";
$trad["TACHE_avancement"] = "Progresso";
$trad["TACHE_avancement_moyen"] = "Progresso médio";
$trad["TACHE_avancement_moyen_pondere"] = "Progresso médio ponderado por dias/homem ";
$trad["TACHE_priorite"] = "Prioridade";
$trad["TACHE_priorite1"] = "Baixa";
$trad["TACHE_priorite2"] = "Média";
$trad["TACHE_priorite3"] = "Alta";
$trad["TACHE_priorite4"] = "Crítica";
$trad["TACHE_responsables"] = "Responsáveis";
$trad["TACHE_budget_disponible"] = "Orçamento disponível";
$trad["TACHE_budget_disponible_total"] = "Orçamento total disponível";
$trad["TACHE_budget_engage"] = "Orçamento cometido";
$trad["TACHE_budget_engage_total"] = "Orçamento total cometido";
$trad["TACHE_charge_jour_homme"] = "carga dias/homem";
$trad["TACHE_charge_jour_homme_total"] = "carga total dias/homem";
$trad["TACHE_charge_jour_homme_info"] = "Número de dias de trabalho necessário para uma pessoa só para realizar essa tarefa";
$trad["TACHE_avancement_retard"] = "Progresso em atraso";
$trad["TACHE_budget_depasse"] = "Excedeu orçamento";
$trad["TACHE_afficher_tout_gantt"] = "Mostrar todas as tarefas";
// tache_edit.php
$trad["TACHE_mail_nouvelle_tache_cree"] = "Nova tarefa criada por ";
$trad["TACHE_specifier_date"] = "Favor especificar uma data";




////	MODULE_CONTACT
////

// Menu principal
$trad["CONTACT_nom_module"] = "Lista de contatos";
$trad["CONTACT_nom_module_header"] = "Contatos";
$trad["CONTACT_description_module"] = "Lista de contatos";
// Index.php
$trad["CONTACT_ajouter_contact"] = "Adicionar um contato";
$trad["CONTACT_aucun_contact"] = "Nenhum contato por enquanto";
$trad["CONTACT_creer_user"] = "Criar um usuário neste espaço";
$trad["CONTACT_creer_user_infos"] = "Criar um usuário neste espaço a partir desse contato?";
$trad["CONTACT_creer_user_confirm"] = "O usuário foi criado";
// Contact_edit.php
$trad["CONTACT_mail_nouveau_contact_cree"] = "Novo contato criado por ";




////	MODULE_LIEN
////

// Menu principal
$trad["LIEN_nom_module"] = "Favoritos";
$trad["LIEN_nom_module_header"] = "Favoritos";
$trad["LIEN_description_module"] = "Favoritos";
// Index.php
$trad["LIEN_ajouter_lien"] = "Adicionar uma ligação";
$trad["LIEN_aucun_lien"] = "Nenhuma ligação por enquanto";
// lien_edit.php & dossier_edit.php
$trad["LIEN_adresse"] = "Endereço";
$trad["LIEN_specifier_adresse"] = "Favor Especificar um endereço";
$trad["LIEN_mail_nouveau_lien_cree"] = "Nova ligação criada por ";




////	MODULE_MAIL
////

// Menu principal
$trad["MAIL_nom_module"] = "Email";
$trad["MAIL_nom_module_header"] = "Email";
$trad["MAIL_description_module"] = "Enviar e-mail com um clique!";
// Index.php
$trad["MAIL_specifier_mail"] = "Favor especificar pelo menos um destinatário";
$trad["MAIL_titre"] = "Título do e-mail";
$trad["MAIL_no_header_footer"] = "Sem cabeçalho ou assinatura";
$trad["MAIL_no_header_footer_infos"] = "Não inclua o nome do remetente e a ligação com o espaço";
$trad["MAIL_masquer_dest_message"] = "Ocultar destinatários";
$trad["MAIL_masquer_dest_message_infos"] = "Por defeito, os destinatários de e-mail são exibidas na mensagem.";
$trad["MAIL_accuse_reception"] = "Pedir um recibo de entrega";
$trad["MAIL_accuse_reception_infos"] = "Atenção: alguns clientes de e-mail não aceitam o recibo de entrega";
$trad["MAIL_fichier_joint"] = "Arquivo ligado";
// Historique Mail
$trad["MAIL_historique_mail"] = "Histórico de emails enviados";
$trad["MAIL_aucun_mail"] = "Nenhum e-mail";
$trad["MAIL_envoye_par"] = "E-mail enviado por";
$trad["MAIL_destinataires"] = "destinatários";
?>