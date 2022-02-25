<?php
////	"MYSQLI" EN POO POUR PHP5+
/////**/
if(version_compare(PHP_VERSION,'5.0','>='))
{
	////	CONNEXION A LA BASE DE DONNEES
	function db_connexion($alertExit=true, $selectDB=true)
	{
		// Connexion à Mysql et aussi à la base de données?
		global $mysqli;
		if($selectDB==true)	{$mysqli=new mysqli(db_host, db_login, db_password, db_name);}
		else				{$mysqli=new mysqli(db_host, db_login, db_password);}
		// DB pas accessible : pas d'install
		if($mysqli->connect_error){
			if($alertExit==false)	{return false;}
			else					{db_error("MYSQL_CONNECT");}
		}
	}

	////	RETOURNE UN TABLEAU A 2 DIMENSIONS (LIGNES & COLONNES)
	function db_tableau($requete, $cle="")
	{
		//$_SESSION["db_nb_lecture"]++;
		global $mysqli;
		$resultat = $mysqli->query($requete);
		//Tableau simple OU tableau avec des clés spécifiques (ID d'élément ou autre)
		if($resultat!==false){
			//garder  if($cle=="")  à un niveau supérieur pour plus de rapidité..
			$tabRetour = array();
			if($cle=="")	{  while($ligne=$resultat->fetch_assoc())  {$tabRetour[]=$ligne;}  }
			else			{  while($ligne=$resultat->fetch_assoc())  {$tabRetour[$ligne[$cle]]=$ligne;}  }
			return $tabRetour;
		}
		else{return db_error($requete);}
	}

	////	RETOURNE UNE LIGNE DE RESULTAT ("Jean","DUPOND","LYON")
	function db_ligne($requete)
	{
		//$_SESSION["db_nb_lecture"]++;
		global $mysqli;
		$resultat = $mysqli->query($requete);
		if($resultat!==false){
			while($ligne=$resultat->fetch_array())	{return $ligne;}
		}
		else{return db_error($requete);}
	}

	////	RETOURNE UNE COLONNE DE VALEURS SUR UNE SEULE CLE  (LISTE D'IDENTIFIANTS PAR EXEMPLE)
	function db_colonne($requete)
	{
		//$_SESSION["db_nb_lecture"]++;
		global $mysqli;
		$resultat = $mysqli->query($requete);
		if($resultat!==false){
			$tabRetour = array();
			while($ligne=$resultat->fetch_array())	{$tabRetour[]=$ligne[0];}
			return $tabRetour;
		}
		else{return db_error($requete);}
	}

	////	RETOURNE LA VALEUR D'UN CHAMP
	function db_valeur($requete)
	{
		//$_SESSION["db_nb_lecture"]++;
		global $mysqli;
		$resultat = $mysqli->query($requete);
		if($resultat!==false){
			while($ligne=$resultat->fetch_array())	{return $ligne[0];}
		}
		else{return db_error($requete);}
	}

	////	EXECUTE UNE REQUETE
	function db_query($requete, $show_error=true)
	{
		//(preg_match("/UPDATE|INSERT|DELETE/i",$requete))  ?  $_SESSION["db_nb_ecriture"]++  :  $_SESSION["db_nb_lecture"]++;
		global $mysqli;
		$resultat = $mysqli->query($requete);
		if($resultat!==false)		{return $resultat;}
		elseif($show_error==true)	{return db_error($requete);}
	}

	////	RETOURNE LE DERNIER ID RENTREE DANS LA DERNIERE REQUETTE
	function db_last_id()
	{
		global $mysqli;
		return $mysqli->insert_id;
	}

	////	FERMER LA BDD
	function db_close()
	{
		//alert("nb lectures : ".$_SESSION["db_nb_lecture"]." - nb écritures : ".$_SESSION["db_nb_ecriture"]);
		global $mysqli;
		$mysqli->close();
	}

	////	VERSION DE MySQL
	function db_version()
	{
		$mysqli = new mysqli(db_host, db_login, db_password);
		return $mysqli->server_info;
	}
	
	////	ECHAPPE UNE CHAINE DE CARACTERE
	function db_escape_string($champ)
	{
		global $mysqli;
		return $mysqli->real_escape_string($champ);
	}
}
////	"MYSQL" POUR PHP4
/////**/
else
{
	////	CONNEXION A LA BASE DE DONNEES
	function db_connexion($alertExit=true, $selectDB=true)
	{
		// Connexion à Mysql et aussi à la base de données?
		global $mysql_link_identifier;
		$mysql_link_identifier = mysql_connect(db_host, db_login, db_password);
		if($selectDB==true)	{$mysql_db_connect=mysql_select_db(db_name);}
		else				{$mysql_db_connect=true;}
		// DB pas accessible : pas d'install
		if($mysql_link_identifier==false || $mysql_db_connect==false){
			if($alertExit==false)	{return false;}
			else					{db_error("MYSQL_CONNECT");}
		}
	}

	////	RETOURNE UN TABLEAU A 2 DIMENSIONS (LIGNES + COLONNES)
	function db_tableau($requete, $cle="")
	{
		global $mysql_link_identifier;
		$tab_resultat = array();
		$resultat = mysql_query($requete, $mysql_link_identifier);
		if($resultat!==false){
			//Tableau simple OU tableau avec des clés spécifiques (ID d'élément ou autre)
			if($cle=="")	{  while($ligne=mysql_fetch_assoc($resultat)) {$tab_resultat[]=$ligne;}  }
			else			{  while($ligne=mysql_fetch_assoc($resultat)) {$tab_resultat[$ligne[$cle]]=$ligne;}  }
			return $tab_resultat;
		}
		else{return db_error($requete);}
	}

	////	RETOURNE UNE LIGNE DE RESULTAT ("Jean","DUPOND","LYON")
	function db_ligne($requete)
	{
		global $mysql_link_identifier;
		$resultat = mysql_query($requete, $mysql_link_identifier);
		if($resultat!==false){
			while($ligne = mysql_fetch_array($resultat))	{return $ligne;}
		}
		else{return db_error($requete);}
	}

	////	RETOURNE UN TABLEAU DE VALEURS SUR UNE SEULE CLE  (LISTE D'IDENTIFIANTS PAR EXEMPLE)
	function db_colonne($requete)
	{
		global $mysql_link_identifier;
		$tab_resultat=array();
		$resultat = mysql_query($requete, $mysql_link_identifier);
		if($resultat!==false){
			while($ligne = mysql_fetch_array($resultat))	{$tab_resultat[]=$ligne[0];}
			return $tab_resultat;
		}	
		else{return db_error($requete);}
	}

	////	RETOURNE LA VALEUR D'UN CHAMP
	function db_valeur($requete)
	{
		global $mysql_link_identifier;
		$resultat = mysql_query($requete, $mysql_link_identifier);
		if(mysql_num_rows($resultat)>0)		{return mysql_result($resultat,0,0);}
		elseif($resultat==false)			{echo "<h4>error: ".$requete."</h4>";return false;}
	}

	////	EXECUTE UNE REQUETE
	function db_query($requete, $show_error=true)
	{
		global $mysql_link_identifier;
		$resultat = mysql_query($requete, $mysql_link_identifier);
		if($resultat!==false)		{return $resultat;}
		elseif($show_error==true)	{return db_error($requete);}
	}

	////	RETOURNE LE DERNIER ID RENTREE DANS LA DERNIERE REQUETTE
	function db_last_id()
	{
		global $mysql_link_identifier;
		return mysql_insert_id($mysql_link_identifier);
	}

	////	FERMER LA BDD
	function db_close()
	{
		global $mysql_link_identifier;
		mysql_close($mysql_link_identifier);
	}

	////	VERSION DE MySQL
	function db_version()
	{
		return @mysql_get_server_info();
	}
	
	////	ECHAPPE UNE CHAINE DE CARACTERE
	function db_escape_string($champ)
	{
		return mysql_real_escape_string($champ);
	}
}


////	RETOURNE L'ERREUR DE LA REQUETE
////
function db_error($requete)
{
	if(!defined("IS_MAIN_AGORA")){
		if($requete=="MYSQL_CONNECT")	{echo "<script> alert(\"Connection error to MySQL\"); window.location.replace(\"".ROOT_PATH."install/\"); </script>";  exit;}
		else							{echo "<h4>error: ".$requete."</h4>";}
	}
	return false;
}


////	SAUVEGARDE LA BDD
/////**/
function db_sauvegarde()
{
	// Recupere chaque table
	foreach(db_colonne("SHOW TABLES FROM `".db_name."`") as $nom_table)
	{
		// Selectionne uniquement les tables de l'agora
		if(preg_match("/gt_/i",$nom_table))
		{
			// Structure de la table
			$create_table = db_ligne("SHOW CREATE TABLE ".$nom_table);
			$tab_dump[] = str_replace(array("\n","\n","\r\n"),"",$create_table[1]).";";
			// Contenu de la table
			foreach(db_tableau("SELECT * FROM ".$nom_table) as $ligne)
			{
				$insertion_tmp = "INSERT INTO ".$nom_table." VALUES(";
				foreach($ligne as $champ){
					$insertion_tmp .= ($champ=="")  ?  "NULL,"  :  "'".db_escape_string($champ)."',";
				}
				$tab_dump[] = trim($insertion_tmp,",").");";
			}
		}
	}
	// Transforme le tableau en texte,  Enregistre le fichier sql,  Retourne le chemin du fichier
	$fichier_dump = PATH_STOCK_FICHIERS."Backup_Mysql_".db_name.".sql";
	$fp = fopen($fichier_dump, "w");
	fwrite($fp, implode("\n", $tab_dump));
	fclose($fp);
	return $fichier_dump;
}



////	FORMATE LA VALEUR D'UN CHAMP DANS UNE REQUETE (SELECT, INSERT..)
////
function db_format($chaine, $options="")
{
	////	Chaine null / vide / booléen-numerique NULL
	if($chaine===null || $chaine==="" || $chaine=="<div>&nbsp;</div>" || (preg_match("/bool/i",$options) && empty($chaine)) || (preg_match("/numerique/i",$options) && empty($chaine)))	{ return "null"; }
	////	Formate la chaine de caractere
	else
	{
		////	Filtre le code provenant de TinyMCE
		if(preg_match("/editeur/i",$options))
		{
			// Ajoute "wmode=transparent" pour afficher les menus contextuels au dessus des animations flash
			$chaine = str_replace(array("<EMBED","<embed"), "<embed wmode='transparent'", $chaine);
			// Enleve le javascript pour limiter les XSS  (les attribus qui commencent par "on", les balises "<script>", etc.)
			$chaine = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $chaine);
			$chaine = preg_replace('#<script[^>]*?.*?</script>#siu', '', $chaine);
		}
		////	Sinon convertit les caractères spéciaux ("insert_ext" -> convertit les simples + doubles quotes. Sinon juste les doubles quotes)  ET  enlève le code PHP et les balises HTML
		elseif(preg_match("/insert_ext/i",$options))	{$chaine = htmlspecialchars(strip_tags($chaine), ENT_QUOTES);}
		elseif(!preg_match("/jscript/i",$options))		{$chaine = htmlspecialchars(strip_tags($chaine));}
		////	Remplace les virgules par des points si c'est une valeur flottante
		if(preg_match("/float/i",$options))		{$chaine = str_replace(",", ".", $chaine);}
		////	Ajoute  "http://"  dans l'url si besoin
		if(preg_match("/url/i",$options) && !preg_match("/http:/i",$chaine))	{$chaine = "http://".$chaine;}
		////	Ajoute des slashes si besoin
		if(substr_count($chaine,"'")!=substr_count($chaine,"\'"))	{$chaine = addslashes($chaine);}
		////	Retour avec les guillements simple.. ou pas (pour la recherche de carac.)
		if(preg_match("/noquotes/i",$options))	{return trim($chaine);}
		else									{return "'".trim($chaine)."'";}
	}
}


////	FORMATE LA DATE ACTUELLE (DATETIME) POUR L'ECRITURE DANS LA BDD (NE PAS UTILISER "NOW()" CAR INCOMPATIBLE AVEC "date_default_timezone_set()")
////
function db_date_now()
{
	return "'".strftime("%Y-%m-%d %H:%M:%S")."'";
}

////
////	LANCE LA CONNEXION && PARAMETRAGE DE LA CONNEXION !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
/////**/
if(!defined("IS_INSTALL_PAGE"))
{
	db_connexion();
	db_query("SET NAMES UTF8");
	db_query("SET SESSION group_concat_max_len=102400;");//pour les commandes sql "GROUP_CONCAT()"
	//$_SESSION["db_nb_lecture"] = $_SESSION["db_nb_ecriture"] = "0"; //TEST POUR COMPTER LES LECTURES/ECRITURES
}




////	MAJ -> TESTE SI ON DOIT LANCER LA MAJ UNITAIRE DE LA BDD CAR LES SRCIPTS D'AGORA-PROJECT SONT PLUS RECENT
/////**/
function db_maj_test_version($version_maj_unitaire)
{
	//version actuelle de la BDD plus ancienne que la version de mise à jour unitaire  (..ou pas de controle de version)  =>  on met à jour !
	global $db_maj_version_agora;
	if(empty($db_maj_version_agora))	{$db_maj_version_agora=db_valeur("SELECT version_agora FROM gt_agora_info");}
	if(version_compare($db_maj_version_agora,$version_maj_unitaire,"<") || $version_maj_unitaire=="no_control")	{return true;}
	else																										{return false;}
}


////	MAJ -> EFFECTUE UNE MISE A JOUR SIMPLE : "INSERT", "CHANGE", ETC
/////**/
function db_maj_query($version_maj_unitaire, $requete, $show_error=true)
{
	if(db_maj_test_version($version_maj_unitaire))	{db_query($requete,$show_error);}
}


////	MAJ -> TESTE L'EXISTANCE D'UNE TABLE, ET LA CREE SI BESOIN
/////**/
function db_maj_table_ajoute($version_maj_unitaire, $table, $requete_creation="")
{
	$existe = db_query("SHOW COLUMNS FROM ".$table, false);
	if($existe==false && !empty($requete_creation) && db_maj_test_version($version_maj_unitaire)){
		db_query($requete_creation);
	}
	return $existe;
}


////	MAJ -> TESTE L'EXISTANCE D'UN CHAMP DANS UNE TABLE, ET LE CREE SI BESOIN  (exemple : "ALTER TABLE `gt_utilisateur` ADD `plage_horaire` TINYTEXT NULL")
/////**/
function db_maj_champ_ajoute($version_maj_unitaire, $table, $champ, $requete_creation="")
{
	$existe = db_query("SELECT ".$champ." FROM ".$table, false);
	if($existe==false && !empty($requete_creation) && db_maj_test_version($version_maj_unitaire))	{db_query($requete_creation);}
	return $existe;
}


////	MAJ -> TEST L'EXISTANCE D'UN CHAMP (ET CHANGE SON NOM ?)  !!!! ATTENTION => AUQUEL CAS, PENSER A METTRE A JOUR LE "db_maj_champ_rename()" QUI LE PRECEDE, POUR QUE LE CHAMP NE SOIT PAS RECREE A CHAQUE MAJ !!!!
/////**/
function db_maj_champ_rename($version_maj_unitaire, $table, $champ_old, $requete_renommage)
{
	$existe = db_query("SELECT ".$champ_old." FROM ".$table, false);
	if($existe!=false && !empty($requete_renommage) && db_maj_test_version($version_maj_unitaire))	{db_query($requete_renommage);}
	return $existe;
}