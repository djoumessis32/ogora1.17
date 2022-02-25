<?php
////	INITIALISATION
////
define("IS_MAIN_PAGE",true);
define("IS_INSTALL_PAGE",true);
require_once "../includes/global.inc.php";
require_once PATH_INC."header.inc.php";


////	INITIALISATION
////
////	CONTROLE DU DOSSIER "stock_fichiers"
chmod_recursif(PATH_STOCK_FICHIERS);
if(!is_writable(PATH_STOCK_FICHIERS))	{alert($trad["MSG_ALERTE_chmod_stock_fichiers"]);}
////	URL DE CONNEXION (RACINE DU SITE)
$adresse_connexion = "http://".$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI'];
$adresse_connexion = substr($adresse_connexion,0,strpos($adresse_connexion,"install/"))."index.php";
////	REINITIALISE LA SESSION SI ELLE EST DEJA LANCEE
$_SESSION = array();
session_destroy();


////	VALIDE L'INSTALL
////
if(isset($_POST["installation"]))
{
	// SALT DU PASSWORD & CONNEXION A LA BDD
	define("AGORA_SALT", mt_rand(10000,99999));
	define("db_host", $_POST["db_host"]);
	define("db_login", $_POST["db_login"]);
	define("db_password", $_POST["db_password"]);
	define("db_name", $_POST["db_name"]);

	////	CREATION DE LA BDD SI INEXISTANTE  /  REINITIALISE LES TABLES EXISTANTES DE L'AGORA
	$connexion_bdd = db_connexion(false);
	if($connexion_bdd===false){
		db_connexion(false,false);//se connecte juste à mysql, puis créé la bdd
		db_query("CREATE DATABASE `".db_name."` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;");
		db_connexion(false);//se connecte à la bdd fraichement créée avant importation des tables
	}
	else{
		db_connexion(false);//se connecte à la bdd, efface les tables avant réimportation
		foreach(db_colonne("SHOW TABLES FROM `".db_name."`") as $nom_table)		{  if(substr($nom_table,0,3)=="gt_"){db_query("DROP TABLE ".$nom_table);}  }
	}

	////	IMPORTATION DU FICHIER SQL
	$handle = fopen("bdd.sql","r");
	$contenu = fread($handle, filesize("bdd.sql"));
	$contenu = str_replace("utf8;", "utf8@@@", $contenu); //Fin de ligne de création de table
	$contenu = str_replace(");", ")@@@", $contenu); //Fin de ligne d'insertion dans la table
	$contenu = explode("@@@",$contenu);
	fclose ($handle);
	foreach($contenu as $ligne){
		//On prend en compte les derniers retours de ligne..
		if(!empty($ligne) && strlen($ligne)>5) {db_query($ligne);}
	}

	////	PARAMETRAGE GENERAL DU SITE  +  PARAMETRAGE DE L'ESPACE
	db_query("UPDATE gt_agora_info SET adresse_web=".db_format($_POST["site_adresse_web"]).", timezone=".db_format(@$_POST["timezone"]).", langue=".db_format($_POST["langue"]));
	db_query("INSERT INTO gt_espace SET id_espace='1', nom=".db_format($_POST["espace_nom"]).", description=".db_format($_POST["espace_description"]).", public=".db_format(@$_POST["espace_public"]).", invitations_users=1");

	////	CREATION DU COMPTE ADMINISTRATEUR
	$id_utilisateur = creer_utilisateur($_POST["identifiant"], $_POST["pass"], @$_POST["nom"], @$_POST["prenom"], $_POST["mail"], "1");
	$retourUpdateUser = db_query("UPDATE gt_utilisateur SET admin_general='1' WHERE id_utilisateur='".intval($id_utilisateur)."'");

	////	MODIF DE "config.inc.php"
	$modifAjouteConstantes = array(
		"db_host"=>db_host,
		"db_login"=>db_login,
		"db_password"=>db_password,
		"db_name"=>db_name,
		"limite_nb_users"=>"10000",
		"limite_espace_disque"=>return_bytes($_POST["limite_espace_disque"].$_POST["limite_espace_disque_unite"]),
		"AGORA_SALT"=>AGORA_SALT
		);
	modif_fichier_config(PATH_STOCK_FICHIERS."config.inc.php", $modifAjouteConstantes);

	// REDIRECTION EN PAGE DE CONNEXION
	alert($trad["INSTALL_install_ok"]);
	redir("../index.php");
}
?>


<script type="text/javascript">
////	On contrôle les champs principaux
function controle_formulaire()
{
	////	CHAMPS OBLIGATOIRES
	if(get_value("db_host")=="" || get_value("db_login")=="" || get_value("db_name")=="" || get_value("nom")=="" || get_value("prenom")=="" || get_value("identifiant")=="" || get_value("pass")=="" || get_value("mail")=="")	{ alert("<?php echo $trad["remplir_tous_champs"]; ?>");  return false; }

	////	CONTROLE DE CONNEXION A LA BDD
	requete_ajax("controle.php?db_host="+get_value("db_host") +"&db_login="+get_value("db_login") +"&db_password="+get_value("db_password") +"&db_name="+get_value("db_name"));
	if(trouver("connexion_mysql_pas_ok",retour_ajax))	{ alert("<?php echo $trad["INSTALL_erreur_acces_mysql"]; ?>"); return false; }
	if(trouver("connexion_bdd_pas_ok",retour_ajax))		{ if(!confirm("<?php echo $trad["INSTALL_erreur_acces_bdd"]; ?>"))		{return false;} }
	if(trouver("mysql_version_pas_ok",retour_ajax))		{ if(!confirm("<?php echo $trad["INSTALL_confirm_version_mysql"]; ?>"))	{return false;} }
	if(trouver("bdd_existe_pas_ok",retour_ajax))		{ if(!confirm("<?php echo $trad["INSTALL_erreur_agora_existe"]; ?>"))	{return false;} }

	////	CONTROLE MOT DE PASSE ET MAIL
	if(get_value("pass")!=get_value("pass2"))	{ alert("<?php echo $trad["password_verif_alert"]; ?>");  return false; }
	if(controle_mail(get_value("mail"))==false)	{ alert("<?php echo $trad["mail_pas_valide"]; ?>");  return false; }

	////	DERNIERE CONFIRMATION
	if(!confirm("<?php echo $trad["INSTALL_confirm_install"]; ?>"))  {return false;}
}
</script>


<style>
input[type=text],textarea	{width:95%;}
form						{width:600px;margin-left:auto;margin-right:auto;}
table						{width:100%;margin-top:20px;font-weight:bold;}
table td					{padding:3px;}
</style>


<div class="contenu_principal_centre" style="margin-top:30px;">
	<form action="index.php" method="post" class="content" OnSubmit="return controle_formulaire();">
		<div>
			<img src="<?php echo PATH_TPL; ?>divers/installer.png" />
			<img src="<?php echo PATH_LOGO; ?>" />
		</div>
		<table>
			<tr>
				<td style='width:300px;'><?php echo $trad["UTILISATEURS_langues"]; ?></td>
				<td><?php echo liste_langues(CUR_LANG,"install"); ?></td>
			</tr>
			<tr><td colspan="2"><h3><?php echo $trad["INSTALL_connexion_bdd"]; ?> :</h3></td></tr>
			<tr>
				<td><?php echo $trad["INSTALL_db_host"]; ?></td>
				<td <?php echo infobulle("exemple : ''localhost'', ''sql4.free.fr'', etc."); ?>><input type="text" name="db_host" /></td>
			</tr>
			<tr>
				<td><?php echo $trad["INSTALL_db_login"]; ?></td>
				<td><input type="text" name="db_login" /></td>
			</tr>
			<tr>
				<td><?php echo $trad["INSTALL_db_password"]; ?></td>
				<td><input type="password" name="db_password" /></td>
			</tr>
			<tr>
				<td><?php echo $trad["INSTALL_db_name"]; ?></td>
				<td<?php echo infobulle($trad["INSTALL_db_name_info"]); ?>><input type="text" name="db_name" /></td>
			</tr>
			<tr><td colspan="2"><h3><?php echo $trad["INSTALL_config_admin"]; ?> :</h3></td></tr>
			<tr>
				<td><?php echo $trad["nom"]; ?></td>
				<td><input type="text" name="nom" /></td>
			</tr>
			<tr>
				<td><?php echo $trad["prenom"]; ?></td>
				<td><input type="text" name="prenom" /></td>
			</tr>
			<tr <?php echo infobulle($trad["INSTALL_login_password_info"]); ?> >
				<td><?php echo $trad["identifiant_connexion"]; ?></td>
				<td><input type="text" name="identifiant" /></td>
			</tr>
			<tr <?php echo infobulle($trad["INSTALL_login_password_info"]); ?> >
				<td><?php echo $trad["pass"]; ?></td>
				<td><input type="password" name="pass" Autocomplete="off" /></td>
			</tr>
			<tr <?php echo infobulle($trad["INSTALL_login_password_info"]); ?> >
				<td><?php echo $trad["pass2"]; ?></td>
				<td><input type="password" name="pass2" /></td>
			</tr>
			<tr>
				<td><?php echo $trad["mail"]; ?></td>
				<td><input type="text" name="mail" /></td>
			</tr>
			<tr><td colspan="2"><h3><?php echo $trad["PARAMETRAGE_description_module"]; ?> :</h3></td></tr>
			<tr>
				<td><?php echo $trad["PARAMETRAGE_limite_espace_disque"]; ?></td>
				<td>
					<input type="text" name="limite_espace_disque" value="10" style="width:50px" />
					<select name="limite_espace_disque_unite"><option value="g"><?php echo $trad["giga_octet"]; ?></option><option value="m"><?php echo $trad["mega_octet"]; ?></option></select>
				</td>
			<?php
			////	TIMEZONE
			echo "<tr>";
				echo "<td>".$trad["PARAMETRAGE_timezone"]."</td>";
				echo "<td><select name=\"timezone\">";
					foreach($tab_timezones as $timezone_libelle => $timezone)	{ echo "<option value=\"".$timezone."\">[GMT ".($timezone>0?"+".$timezone:$timezone)."]&nbsp; ".$timezone_libelle."</option>"; }
				echo "</select><script>set_value('timezone','".server_timezone("num")."');</script></td>";
			echo "</tr>";
			?>
			</tr>
			<tr>
				<td><?php echo $trad["PARAMETRAGE_adresse_web"]; ?></td>
				<td><input type="text" name="site_adresse_web" value="<?php echo $adresse_connexion; ?>" /></td>
			</tr>
			<tr><td colspan="2"><h3><?php echo $trad["INSTALL_config_espace"]; ?> :</h3></td></tr>
			<tr>
				<td><?php echo $trad["nom"]; ?></td>
				<td><input type="text" name="espace_nom" value="Espace principal" /></td>
			</tr>
			<tr>
				<td><?php echo $trad["description"]; ?></td>
				<td><textarea name="espace_description"></textarea></td>
			</tr>
			<tr>
				<td><?php echo $trad["ESPACES_espace_public"]; ?></td>
				<td><select name="espace_public"><option value="0"><?php echo $trad["non"]; ?></option><option value="1"><?php echo $trad["oui"]; ?></option></select></td>
			</tr>
		</table>
		<div style="text-align:right;margin-top:20px;">
			<input type="hidden" name="installation" value="1"/>
			<input type="submit" class='button' value="<?php echo $trad["valider"]; ?>" />
		</div>
	</form>
</div>


</body>
</html>