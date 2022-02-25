<?php
////	LISTE DES UTILISATEURS AFFECTES A UN ESPACE
////
function users_espace($id_espace, $retour="id")
{
	// Tous les utilisateurs du site / Uniquement ceux affectés à l'espace
	$ts_users_site = db_valeur("SELECT count(*) FROM gt_jointure_espace_utilisateur WHERE id_espace='".intval($id_espace)."' AND tous_utilisateurs=1");
	if($ts_users_site > 0)	{$tab_users_tmp = db_tableau("SELECT * FROM gt_utilisateur ORDER BY ".$_SESSION["agora"]["tri_personnes"]);}
	else					{$tab_users_tmp = db_tableau("SELECT DISTINCT T1.*  FROM  gt_utilisateur T1, gt_jointure_espace_utilisateur T2  WHERE  T1.id_utilisateur=T2.id_utilisateur  AND  T2.id_espace='".intval($id_espace)."'  ORDER BY ".$_SESSION["agora"]["tri_personnes"]);}
	// Retourne toutes les infos ou juste les identifiants des users
	if($retour!="id")	{return $tab_users_tmp;}
	else{
		$tab_id = array();
		foreach($tab_users_tmp as $infos_user)   {$tab_id[]=$infos_user["id_utilisateur"];}
		return $tab_id;
	}
}


////	LISTE DES UTILISATEURS QU'UN UTILISATEUR PEUT VOIR  (tous espace confondu)
////
function users_visibles($infos_user="", $sauf_user_courant=true, $avec_mail=false)
{
	// Init
	$tab_users=array();
	if($infos_user=="")  {$infos_user=$_SESSION["user"];}
	$sauf_user_courant	= ($sauf_user_courant==true)  ?  "AND id_utilisateur!='".$infos_user["id_utilisateur"]."'"  :  "";
	$avec_mail			= ($avec_mail==true)  ?  "AND mail!=''"  :  "";
	// Liste les autres users des espaces auquel peut accéder l'utilisateur courant
	if($infos_user["admin_general"]==1)		{$tab_users=db_tableau("SELECT * FROM gt_utilisateur WHERE 1 ".$sauf_user_courant." ".$avec_mail." ORDER BY ".$_SESSION["agora"]["tri_personnes"]);}
	elseif($infos_user["id_utilisateur"]>0){
		$tab_users_selected = array();
		foreach(espaces_affectes_user($infos_user) as $espace_tmp)	{$tab_users_selected=array_merge($tab_users_selected,users_espace($espace_tmp["id_espace"]));}
		$tab_users=db_tableau("SELECT * FROM gt_utilisateur WHERE id_utilisateur IN (0,".implode(",",array_unique($tab_users_selected)).") ".$sauf_user_courant." ".$avec_mail." ORDER BY ".$_SESSION["agora"]["tri_personnes"]);
	}
	// Renvoi la liste des users
	return $tab_users;
}


////	LISTE DES AUTRES USERS QU'IL PEUT VOIR SUR LE LIVECOUNTER & MESSENGER  :  CONNECTES + DECONNECTES
////
function users_livecounter($infos_user="")
{
	if($infos_user=="")  {$infos_user = $_SESSION["user"];}
	$users_selected = "0,";  // ID factice pour pas tout sélectionner si ya rien
	foreach(users_visibles($infos_user) as $user_tmp)	{$users_selected.=$user_tmp["id_utilisateur"].",";}
	return db_tableau("SELECT DISTINCT T1.*  FROM  gt_utilisateur T1, gt_jointure_messenger_utilisateur T2  WHERE  T1.id_utilisateur=T2.id_utilisateur_messenger  AND  T1.id_utilisateur IN (".trim($users_selected,',').")  AND  (T2.id_utilisateur='".$infos_user["id_utilisateur"]."' OR T2.tous_utilisateurs='1')");
}


////	LISTE DES AUTRES USERS QU'IL PEUT VOIR SUR LE LIVECOUNTER & MESSENGER  : CONNECTES UNIQUEMENT
////
function users_connectes()
{
	$users_selected = "0,";  // ID factice pour pas tout sélectionner si ya rien
	foreach(users_livecounter() as $user_tmp)	{  $users_selected .= $user_tmp["id_utilisateur"].",";  }
	return db_tableau("SELECT DISTINCT T1.*  FROM  gt_utilisateur T1, gt_utilisateur_livecounter T2  WHERE  T1.id_utilisateur=T2.id_utilisateur  AND  T1.id_utilisateur IN (".trim($users_selected,',').")  AND  T2.date_verif > '".(time() - duree_livecounter)."'");
}


////	LISTE DES ESPACES AFFECTES A UN UTILISATEUR
////
function espaces_affectes_user($infos_user=null, $espace_courant_premier=false)
{
	// Init
	if($infos_user==null)	{$infos_user = @$_SESSION["user"];}
	$espace_courant_premier = ($espace_courant_premier==true)  ?  "(T1.id_espace=".$_SESSION["espace"]["id_espace"].") DESC,"  :  "";
	// Admin général
	if(@$infos_user["admin_general"]==1)	{return db_tableau("SELECT * FROM gt_espace T1 ORDER BY ".$espace_courant_premier." T1.nom asc");}
	// Invité / Utilisateur
	else{
		$sql_selection = ($infos_user["id_utilisateur"]<1)  ?  "T1.public=1"  :  "T2.id_utilisateur=".$infos_user["id_utilisateur"]." OR T2.tous_utilisateurs=1";
		return db_tableau("SELECT DISTINCT  T1.*  FROM  gt_espace T1  LEFT JOIN  gt_jointure_espace_utilisateur T2  ON  T1.id_espace=T2.id_espace WHERE ".$sql_selection." ORDER BY ".$espace_courant_premier." T1.nom asc");
	}
}


////	DROIT D'ACCÈS A L'ESPACE (1=utilisation et 2=administration)
////
function droit_acces_espace($id_espace, $infos_user)
{
	if($infos_user["admin_general"]==1)		{ return 2; }
	else
	{
		if($infos_user["id_utilisateur"]>=1)	{$droit_tmp = db_valeur("SELECT MAX(droit) FROM gt_jointure_espace_utilisateur WHERE id_espace='".intval($id_espace)."' AND (id_utilisateur='".$infos_user["id_utilisateur"]."' OR tous_utilisateurs=1)");}
		if(@$droit_tmp < 1)						{$droit_tmp = db_valeur("SELECT public FROM gt_espace WHERE id_espace='".intval($id_espace)."'");}
		return $droit_tmp;
	}
}


////	CONTROLE L'AFFICHAGE D'UN UTILISATEUR POUR L'UTILISATEUR COURANT ($mode = 'exit'/'bool')
////
function controle_affichage_utilisateur($id_utilisateur, $mode="exit")
{
	// Admin général OU l'utilisateur concerné : pas de controle
	if($_SESSION["user"]["admin_general"]==1 || $_SESSION["user"]["id_utilisateur"]==$id_utilisateur){
		$controle = true;
	}
	// Sinon : controle l'accès
	else{
		$controle = false;
		foreach(users_visibles() as $infos_user)	{ if($id_utilisateur==$infos_user["id_utilisateur"])  {$controle = true;} }
		if($controle==false && $mode=="exit")	{exit();}
	}
	return $controle;
}


////	CONTROLE D'ACCES AU MODULES D'ADMINISTRATION (ESPACE / PARAMETRAGE / ETC)
////
function controle_acces_admin($type)
{
	if(($type=="admin_general" && $_SESSION["user"]["admin_general"]!=1) || ($type=="admin_espace" && $_SESSION["espace"]["droit_acces"]<2)){
		global $trad;
		alert($trad["admin_only"]);
		exit();
	}
}


////	INFOS SUR UN UTILISATEUR
////
function user_infos($id_utilisateur, $champ="*")
{
	if($champ=="*")		{return db_ligne("SELECT * FROM gt_utilisateur WHERE id_utilisateur='".intval($id_utilisateur)."'");}
	else				{return db_valeur("SELECT ".$champ." FROM gt_utilisateur WHERE id_utilisateur='".intval($id_utilisateur)."'");}
}


////	PHOTO UTILISATEUR
////
function photo_user($infos_user, $width_height_maxi="", $dimensions_fixes=false)
{
	$chemin_img = (@$infos_user["photo"]=="")  ?  PATH_TPL."module_utilisateurs/user.png" :  PATH_PHOTOS_USER.$infos_user["photo"];
	$prefixe_max = ($dimensions_fixes==false)  ?  "max-"  :  "";
	return "<img src=\"".$chemin_img."\" style='".$prefixe_max."width:".$width_height_maxi."px;".$prefixe_max."height:".$width_height_maxi."px;' ".popup_user(@$infos_user["id_utilisateur"])." />";
}


////	LIEN VERS LA FICHE D'UN UTILISATEUR
////
function popup_user($id_utilisateur)
{
	if($id_utilisateur>0 && $_SESSION["user"]["id_utilisateur"]>0)	{return "class='lien' OnClick=\"popupLightbox('".ROOT_PATH."module_utilisateurs/utilisateur.php?id_utilisateur=".$id_utilisateur."');\"";}
}


////	CONTROLE D'AJOUT D'UTILISATEUR
////
function nb_users_depasse($alerte=true, $close_windows=true)
{
	global $trad;
	// DEPASSE LE NB D'USERS?
	if(defined("limite_nb_users") && limite_nb_users > 0 && db_valeur("SELECT count(*) FROM gt_utilisateur") >= limite_nb_users){
		if($alerte==true)	{alert($trad["MSG_ALERTE_nb_users"].limite_nb_users);}
		if($close_windows==true)	{reload_close();}
		else						{return true;}
	}
}


////	CREER USER
////
function creer_utilisateur($identifiant, $pass, $nom=null, $prenom=null, $mail=null, $id_espace=null)
{
/**/global $trad;
/**//// Création d'un agenda personnel (si pas désactivé au niveau du parametrage général et du compte utilisateur)
/**/$agenda_desactive_value = (empty($_SESSION["agora"]["agenda_perso_desactive"]) && empty($_POST["agenda_desactive"]))  ?  ""  :  "1";
/**/////	NB d'users atteint ?  Identifiant existe déjà ?
/**/if(nb_users_depasse(true,false))																						{return false;}	
/**/elseif(db_valeur("SELECT count(*) FROM gt_utilisateur WHERE identifiant!='' AND identifiant='".$identifiant."'")>0)		{ alert($trad["MSG_ALERTE_user_existdeja"]." : ".$identifiant);  return false; }
/**/elseif(!empty($identifiant) && !empty($pass))
	{
		// Création de l'utilisateur
		db_query("INSERT INTO gt_utilisateur SET identifiant=".db_format($identifiant).", pass=".db_format(sha1_pass($pass)).", nom=".db_format($nom).", prenom=".db_format($prenom).", mail=".db_format($mail).", date_crea=".db_date_now().", espace_connexion=".db_format($id_espace).", agenda_desactive=".db_format(@$agenda_desactive_value));
		$id_utilisateur = db_last_id();
		// Ajoute l'utilisateur au Messenger, au module Agenda et éventuellement à un Espace
		db_query("INSERT INTO gt_jointure_messenger_utilisateur SET id_utilisateur_messenger='".$id_utilisateur."', tous_utilisateurs='1'");
		if(empty($agenda_desactive_value))	{db_query("INSERT INTO gt_agenda SET id_utilisateur='".$id_utilisateur."', type='utilisateur'");}
		if($id_espace > 0)					{db_query("INSERT INTO gt_jointure_espace_utilisateur SET id_espace='".$id_espace."', id_utilisateur='".$id_utilisateur."', droit='1'");}
		return $id_utilisateur;
	}
}


////	LIVECOUNTER ET MESSENGER ACTIF?
////
function livecounter_messenger_actif()
{
	////	utilisateur avec messenger activé ?
	if($_SESSION["user"]["id_utilisateur"]>0 && $_SESSION["agora"]["messenger_desactive"]!="1")		{return true;}
	else																							{return false;}
}


////	AFFICHAGE D'UNE CARTE POUR UNE GEOLOCALISATION (DANS UN POPUP)
////
function carte_localisation($personne_tmp)
{
	global $trad;
	if($personne_tmp["adresse"]!="" || $personne_tmp["codepostal"]!="" || $personne_tmp["ville"]!="" || $personne_tmp["pays"]!=""){
		$mapURL = "https://maps.google.fr/maps?f=q&hl=fr&q=".addslashes($personne_tmp["adresse"].", ".$personne_tmp["codepostal"]." ".$personne_tmp["ville"]." ".$personne_tmp["pays"]);
		return "<a href=\"javascript:popupLightbox('".$mapURL."',true,950,600);\" ".infobulle($trad["localiser_carte"])."><img src=\"".PATH_TPL."divers/carte.png\" /></a>";
	}
}


////	GROUPES D'UTILISATEURS & DROITS D'ACCES D'UN ESPACE (ET EVENTUELLEMENT UN UTILISATEUR)
////
function groupes_users($id_espace="", $id_utilisateur="")
{
	////	INIT
	$groupes = array();
	$sql_groupe = "";
	if($id_espace>0){
		$sql_groupe = "WHERE id_espaces is null OR id_espaces LIKE '%@@".$id_espace."@@%'";
		$users_espace = users_espace($id_espace);
	}
	////	LISTE DES GROUPES
	foreach(db_tableau("SELECT * FROM gt_utilisateur_groupe  ".$sql_groupe."  ORDER BY titre") as $groupe_tmp)
	{
		// Tous les groupes sélectionnés / Groupes sélectionnés, affectés à l'utilisateur
		if($id_utilisateur=="" || ($id_utilisateur>0 && preg_match("/@".$id_utilisateur."@/",$groupe_tmp["id_utilisateurs"])))
		{
			// Droit : lecture OU écriture si auteur ou admin gé.
			$groupe_tmp["droit"] = (is_auteur($groupe_tmp["id_utilisateur"]) || $_SESSION["user"]["admin_general"]==1)  ?  2  :  1;
			// Utilisateurs du groupe (& dans l'espace sélectionné?)
			$groupe_tmp["users_title"] = "";
			$groupe_tmp["users_tab"] = array();
			foreach(text2tab($groupe_tmp["id_utilisateurs"]) as $id_user)
			{
				if($id_espace=="" || in_array($id_user,$users_espace)){
					$groupe_tmp["users_title"] .= auteur($id_user).", ";
					$groupe_tmp["users_tab"][]  = $id_user;
				}
			}
			$groupe_tmp["users_title"] = substr($groupe_tmp["users_title"],0,-2);
			// Ajoute le groupe au tableau de sortie
			$groupes[$groupe_tmp["id_groupe"]] = $groupe_tmp;
		}
	}
	return $groupes;
}


////	EDITION D'UTILISATEUR / CONTACT : AFFICHE UN CHAMP TEXTE
////
function user_champ($user_tmp, $cle_champ, $options="")
{
	// Init
	global $trad;
	$infobulle = $value = $autocomplete = "";
	// Libelle du champ
	if($cle_champ=="identifiant")	{$libelle_champ=$trad["identifiant_connexion"];}
	else							{$libelle_champ=$trad[$cle_champ];}
	// Champ obligatoire?
	if(preg_match("/obligatoire/i",$options))		{ $class_lib = "lien_select";	$infobulle = infobulle($trad["champs_obligatoire"]); }
	else											{ $class_lib = "form_libelle"; }
	// Champ password / autre
	if($cle_champ=="pass" || $cle_champ=="pass2")	{ $type_input = "password";	 $autocomplete = "Autocomplete='off'"; }
	else											{ $type_input = "text";		 $value = @$user_tmp[$cle_champ]; }
	// Affiche
	echo "<div class='user_champ_ligne' ".$infobulle.">";
		echo "<div class='user_champ_cell_lib ".$class_lib."'>".$libelle_champ."</div>";
		echo "<div class='user_champ_cell'><input type='".$type_input."' name='".$cle_champ."' value=\"".$value."\" ".$autocomplete." style='width:100%;' /></div>";
	echo "</div>";
}


////	CONNEXION A LDAP
////
function connexion_ldap($ldap_server=null, $ldap_server_port=null, $ldap_admin_login=null, $ldap_admin_pass=null)
{
	// la fonction de connexion LDAP est activée ?
	if(!function_exists("ldap_connect"))	{return false;}
	// Config
	if($ldap_server==null)			{$ldap_server		= $_SESSION["agora"]["ldap_server"];}		//localhost
	if($ldap_server_port==null)		{$ldap_server_port	= $_SESSION["agora"]["ldap_server_port"];}	//ApacheDS="10389"  ActiveDirectory="389"
	if($ldap_admin_login==null)		{$ldap_admin_login	= $_SESSION["agora"]["ldap_admin_login"];}	//DS="uid=admin,ou=system"  AD="xarmen@test.local"
	if($ldap_admin_pass==null)		{$ldap_admin_pass	= $_SESSION["agora"]["ldap_admin_pass"];}	//DS="secret"  AD="admin"
	// Connexion au serveur LDAP
	$connexion_ldap = ldap_connect($ldap_server, $ldap_server_port);//Ne retourne pas d'erreur en ldap v2 ! (cf. doc PHP)
	ldap_set_option($connexion_ldap, LDAP_OPT_PROTOCOL_VERSION, 3);	//Spécifie la version 3 de LDAP (..version 2 par défaut pour PHP)
	ldap_set_option($connexion_ldap, LDAP_OPT_REFERRALS, 0);		//Pour window server
	// Identification au serveur LDAP en tant qu'admin + retourne la connexion ldap si c'est ok
	$identification_ldap = @ldap_bind($connexion_ldap, $ldap_admin_login, $ldap_admin_pass);
	return ($identification_ldap==false)  ?  false  :  $connexion_ldap;
}


////	RECUPERES LES USERS DE L'ANNUAIRE LDAP  (exple de $filtre_recherche -> "(&(samaccountname=MONLOGIN)(cn=*))" )
////
function recherche_ldap($recup_login_password=false, $mode_recherche="tableau_import", $filtre_recherche="(&(cn=*))")
{
	$connexion_ldap = connexion_ldap();
	if($connexion_ldap!=false)
	{
		////	Champs Agora  =>  Attributs LDAP correspondants (Toujours en minucule!)
		$tab_correspondance = array(
			"civilite"			=> array("designation"),
			"nom"				=> array("sn","name","lastname"),//Sur ActiveDirectory : "sn" est avant "name"
			"prenom"			=> array("firstname","givenname","knownas"),
			"mail"				=> array("mail"),
			"telmobile"			=> array("mobile","mobiletelephonenumber"),
			"telephone"			=> array("telephonenumber","homephone","hometelephonenumber"),
			"fax"				=> array("fax","facsimiletelephonenumber"),
			"adresse"			=> array("postaladdress","homepostaladdress","streetaddress","street"),
			"codepostal"		=> array("postalcode","homepostalcode"),
			"ville"				=> array("localityname"),
			"societe_organisme"	=> array("company","department","organizationname","organizationalunitname","o","ou"),
			"fonction"			=> array("title","titleall"),
			"commentaire"		=> array("description"));
		////	Champs Agora  =>  On ajoute l'id/password en cas d'import d'utilisateur
		if($recup_login_password==true)
		{
			$tab_correspondance2 = array(
					"identifiant" => array("uid","samaccountname"),
					"pass"		  => array("userpassword","password"));
			$tab_correspondance = array_merge($tab_correspondance2, $tab_correspondance);
		}
		////	Récupere les users LDAP (DS="ou=users,o=mojo"  AD="OU=TEST,DC=test,DC=local")
		$recherche_ldap = ldap_search($connexion_ldap, $_SESSION["agora"]["ldap_groupe_dn"], $filtre_recherche);
		if($recherche_ldap!=false)
		{
			$users_ldap = ldap_get_entries($connexion_ldap, $recherche_ldap);
			if($users_ldap["count"]>0)
			{
				$entete_champs = $users_retour = array();
				////	Récupère les champs de l'entête (colonnes du tableau)
				for($i=0; $i<$users_ldap["count"]; $i++)
				{
					// Si le champ agora est absent de l'entête  &  l'attribut LDAP est rempli chez cet utilisateur  =>  on ajoute le champ agora !
					foreach($tab_correspondance as $id_agora => $liste_id_ldap)
					{
						foreach($liste_id_ldap as $id_ldap){
							if(array_key_exists($id_agora,$entete_champs)==false && empty($users_ldap[$i][$id_ldap][0])==false && in_array($id_agora,$entete_champs)==false)	{$entete_champs[]=$id_agora;}
							//echo $id_agora." -> ".$id_ldap." -> <i>".@$users_ldap[$i][$id_ldap][0]."</i><br>";
						}
					}
				}
				////	Récupère les valeurs des attributs de chaque contact
				for($i=0; $i<$users_ldap["count"]; $i++)
				{
					$user_tmp = array();
					foreach($tab_correspondance as $id_agora => $liste_id_ldap)
					{
						// Cle du tableau d'entête correspondant au champ visé
						$cle_entete = array_search($id_agora,$entete_champs);
						$cle_user_tmp = ($mode_recherche=="tableau_import")  ?  $cle_entete  :  $id_agora;
						// Ajoute la valeure si l'attribut ldap correspond à un champ de l'agora
						foreach($liste_id_ldap as $id_ldap){
							if(@$users_ldap[$i][$id_ldap][0]!="" && @$user_tmp[$cle_entete]=="")	{$user_tmp[$cle_user_tmp]=$users_ldap[$i][$id_ldap][0];}
						}
						// Initialise une valeure null pour quand même afficher une cellule dans le tableau..
						if($mode_recherche=="tableau_import" && @$user_tmp[$cle_entete]=="")	{$user_tmp[$cle_entete]=null;}
					}
					$users_retour[] = $user_tmp;
				}
				return array("entete_champs"=>$entete_champs, "users_ldap"=>$users_retour);
			}
			ldap_close($connexion_ldap);
		}
	}
}


////	CONNEXION D'UN USER PAS PRESENT SUR L'AGORA : TENTE UNE CONNEXION LDAP POUR UNE CREATION A LA VOLEE
////
function ldap_connexion_creation_user($login, $password)
{
	// INIT
	$user_retour = array();
	// CREATION D'USER LDAP AUTORISEE ?
	if($_SESSION["agora"]["ldap_crea_auto_users"]=="1")
	{
		// YA AU MOINS UN ESPACE AFFECTE A TOUS LES USERS  +  QUOTA D'USERS PAS DEPASSE
		if(db_valeur("SELECT count(*) FROM gt_jointure_espace_utilisateur WHERE tous_utilisateurs='1'")>0  &&  nb_users_depasse(true,false)==false)
		{
			// Mot de passe crypté.. ou pas (note : certains serveurs LDAP ne fournissent pas le password, tel ActiveDirectory)
			if($_SESSION["agora"]["ldap_pass_cryptage"]=="sha")			{$password_tmp = "{SHA}".base64_encode(mhash(MHASH_SHA1,$password));}
			elseif($_SESSION["agora"]["ldap_pass_cryptage"]=="md5")		{$password_tmp = "{MD5}".base64_encode(mhash(MHASH_MD5,$password));}
			else														{$password_tmp = $password;}
			// récupère les valeurs de l'user sur le serveur LDAP
			$users_recherche = recherche_ldap(true, "connexion_user", "(|(uid=$login)(samaccountname=$login))");
			if(count($users_recherche["users_ldap"])>0)
			{
				foreach($users_recherche["users_ldap"] as $user_ldap_tmp)
				{
					// Teste la connexion de l'user sur le serveur LDAP (2ème test pour ActiveDirectory, où on ajoute "@domaine" au login)
					$connexion_ldap	= connexion_ldap(null, null, $login, $password);
					if($connexion_ldap==false)	{$connexion_ldap=connexion_ldap(null,null, $login.strrchr($_SESSION["agora"]["ldap_admin_login"],"@"), $password);}
					//  Vérifie si l'id/password du serveur LDAP est identique à celui spécifié
					$id_pass_identiques = (@$user_ldap_tmp["identifiant"]==$login && @$user_ldap_tmp["pass"]==$password_tmp)  ?  true  :  false;
					// Vérifie si l'user n'a pas déjà été importé :  la connexion peut être faite par erreur avec les login/password LDAP, différent de ceux de l'agora...
					$controle_user_existe = db_valeur("SELECT count(*) FROM gt_utilisateur WHERE nom=".db_format(@$user_ldap_tmp["nom"])." AND prenom=".db_format(@$user_ldap_tmp["prenom"]));
					// Créé le compte sur l'agora
					if(($connexion_ldap!=false || $id_pass_identiques==true) && $controle_user_existe==0){
						$id_user_tmp = creer_utilisateur($login, $password, @$user_ldap_tmp["nom"], @$user_ldap_tmp["prenom"], @$user_ldap_tmp["mail"]);
						$user_retour = db_ligne("SELECT * FROM gt_utilisateur WHERE id_utilisateur='".$id_user_tmp."'");
						break;
					}
				}
			}
		}
	}
	return $user_retour;
}
?>