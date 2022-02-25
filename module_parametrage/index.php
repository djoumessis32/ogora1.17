<?php
////	INIT
define("IS_MAIN_PAGE",true);
require "commun.inc.php";
require PATH_INC."header_menu.inc.php";
$alert_info_sav = (taille_stock_fichier()>(100*1048576))  ?  "alert('".addslashes($trad["PARAMETRAGE_sav_alert"])."');"  :  "";  // (100*1048576) = 100Mo


////	MODIF DU PARAMETRAGE DU SITE & DES VALEURS DE SESSION
////
if(isset($_POST["submit_parametrage"]))
{
	////	MODIFS DE BASE
	$corps_sql = "nom=".db_format($_POST["nom"]).", 
			description=".db_format($_POST["description"]).", 
			adresse_web=".db_format($_POST["adresse_web"]).", 
			footer_html=".db_format($_POST["footer_html"],"jscript").",
			langue=".db_format($_POST["langue"]).",
			timezone=".db_format(@$_POST["timezone"]).",
			skin=".db_format($_POST["skin"]).",
			logo_url=".db_format($_POST["logo_url"],"url").",
			logs_jours_conservation=".db_format($_POST["logs_jours_conservation"]).",
			edition_popup=".db_format($_POST["edition_popup"],"bool").",
			editeur_text_mode=".db_format($_POST["editeur_text_mode"]).",
			libelle_module=".db_format(@$_POST["libelle_module"]).",
			tri_personnes=".db_format(@$_POST["tri_personnes"]).",
			messenger_desactive=".db_format(@$_POST["messenger_desactive"],"bool").",
			agenda_perso_desactive=".db_format(@$_POST["agenda_perso_desactive"],"bool").",
			ldap_server=".db_format(@$_POST["ldap_server"]).",
			ldap_server_port=".db_format(@$_POST["ldap_server_port"]).",
			ldap_admin_login=".db_format(@$_POST["ldap_admin_login"]).",
			ldap_admin_pass=".db_format(@$_POST["ldap_admin_pass"]).",
			ldap_groupe_dn=".db_format(@$_POST["ldap_groupe_dn"]).",
			ldap_pass_cryptage=".db_format(@$_POST["ldap_pass_cryptage"]).",
			ldap_crea_auto_users=".db_format(@$_POST["ldap_crea_auto_users"]);
	if(db_valeur("SELECT count(*) FROM gt_agora_info")>0)	{db_query("UPDATE gt_agora_info SET ".$corps_sql);}
	else													{db_query("INSERT INTO gt_agora_info SET ".$corps_sql);}

	////	MODIF DU FICHIER DE L'ESPACE DISQUE DANS LE FICHIER DE CONFIG
	if(!defined("HOST_DOMAINE") && !empty($_POST["limite_espace_disque"])){
		modif_fichier_config(PATH_STOCK_FICHIERS."config.inc.php", array("limite_espace_disque"=>return_bytes($_POST["limite_espace_disque"].$_POST["limite_espace_disque_unite"])));
	}

	////	FOND D'ECRAN
	////
	////	NOUVEAU
	if(@$_POST["fond_ecran"]=="ajouter" && is_uploaded_file($_FILES["fichier_fond_ecran"]["tmp_name"]))
	{
		if(controle_fichier("image_gd",$_FILES["fichier_fond_ecran"]["name"])==false)	{ alert($trad["PARAMETRAGE_erreur_fond_ecran_logo"]); }
		else {
			$nom_image = suppr_carac_spe($_FILES["fichier_fond_ecran"]["name"],"maxi");
			$path_image = PATH_WALLPAPER_USER.$nom_image;
			move_uploaded_file($_FILES["fichier_fond_ecran"]["tmp_name"], $path_image);
			@chmod($path_image,0775);
			if($_FILES["fichier_fond_ecran"]["size"]>204800)	{reduire_image($path_image, $path_image, 1920, 1920, 85);}//optimise si + de 200ko
			db_query("UPDATE gt_agora_info SET fond_ecran=".db_format($nom_image));
		}
	}
	////	FOND D'ECRAN SELECTIONNE / FOND D'ECRAN PAR DEFAUT
	elseif(@$_POST["fond_ecran"]!="defaut")	{db_query("UPDATE gt_agora_info SET fond_ecran=".db_format(@$_POST["fond_ecran"]));}
	else									{db_query("UPDATE gt_agora_info SET fond_ecran=null");}

	////	LOGO EN BAS DE PAGE
	////
	// NOUVEAU
	if($_POST["logo"]=="ajouter" && is_uploaded_file($_FILES["fichier_logo"]["tmp_name"]))
	{
		if(controle_fichier("image_gd",$_FILES["fichier_logo"]["name"])==false)	{alert($trad["PARAMETRAGE_erreur_fond_ecran_logo"]);}
		else
		{
			if(is_file(PATH_STOCK_FICHIERS.$_SESSION["agora"]["logo"]))		{unlink(PATH_STOCK_FICHIERS.$_SESSION["agora"]["logo"]);}
			$nom_image = "logo".extension($_FILES["fichier_logo"]["name"]);
			$path_image = PATH_STOCK_FICHIERS.$nom_image;
			move_uploaded_file($_FILES["fichier_logo"]["tmp_name"], $path_image);
			@chmod($path_image,0775);
			reduire_image($path_image, $path_image, 80, 300);
			db_query("UPDATE gt_agora_info SET logo=".db_format($nom_image));
		}
	}
	// LOGO PAR DEFAUT
	elseif($_POST["logo"]==""){
		if(is_file(PATH_STOCK_FICHIERS.$_SESSION["agora"]["logo"]))	{unlink(PATH_STOCK_FICHIERS.$_SESSION["agora"]["logo"]);}
		db_query("UPDATE gt_agora_info SET logo=null");
	}

	////	TEST DE CONNEXION LDAP
	if(!empty($_POST["ldap_server"])){
		$test_connexion_ldap = connexion_ldap($_POST["ldap_server"],$_POST["ldap_server_port"],$_POST["ldap_admin_login"],$_POST["ldap_admin_pass"]);
		if($test_connexion_ldap==false)	{alert($trad["ldap_connexion_erreur"]);}
	}

	////	LOG  +  MAJ SESSION  +  REDIRECTION (POUR REFRESH LE BACKGROUND)
	add_logs("modif", "", "", $trad["PARAMETRAGE_description_module"]);
	$_SESSION["agora"] = db_ligne("SELECT * FROM gt_agora_info");
	//redir("index.php");
}


////	SUPPRESSION D'UN FOND D'ECRAN
////
if(isset($_GET["suppr_fond_ecran"])){
	if(is_file(PATH_WALLPAPER_USER.$_GET["suppr_fond_ecran"]))			{unlink(PATH_WALLPAPER_USER.$_GET["suppr_fond_ecran"]);}
	if($_SESSION["agora"]["fond_ecran"]==$_GET["suppr_fond_ecran"])		{db_query("UPDATE gt_agora_info SET fond_ecran=''");}
}
?>


<script type="text/javascript">
////	CONTROLE DE L'ADRESSE WEB
function controle_adresse_web()
{
	// On enlève "*.php*" de l'adresse web
	adresse = get_value("adresse_web");
	if(trouver(".php",adresse)){
		adresse = adresse.substring(0, adresse.lastIndexOf("/")+1);
		set_value("adresse_web", adresse);
	}
}

////	CONTROLE DE SAISIE DU FORMULAIRE
function controle_parametrage_site()
{
	// Contrôle du nom du site & de l'adresse web
	if(get_value("nom")=="" || get_value("adresse_web")=="" || (existe("limite_espace_disque") && get_value("limite_espace_disque")==""))	{ alert("<?php echo $trad["remplir_tous_champs"]; ?>");  return false; }
	if(get_value("adresse_web").substring(0,4)!="http")								{ alert("<?php echo $trad["PARAMETRAGE_adresse_web_invalide"]; ?>");   return false; }
	if(existe("limite_espace_disque") && isNaN(get_value("limite_espace_disque")))	{ alert("<?php echo $trad["PARAMETRAGE_espace_disque_invalide"]; ?>"); return false; }
	// Confirmation de modif'
	if (confirm("<?php echo $trad["PARAMETRAGE_confirmez_modification_site"]; ?>")==false)	{return false;}
}

////	URL DU LOGO DU FOOTER
function gestion_logo_url()
{
	// Init
	logo_footer = element("logo_footer").value;
	// Ajoute un nouveau logo ?
	if(logo_footer=="ajouter")	afficher("fichier_logo",true);
	else						afficher("fichier_logo",false);
	// URL du logo : par défaut / personnalisé
	if(logo_footer=="")			{ afficher("span_logo_url",false);	set_value("logo_url","<?php echo AGORA_PROJECT_URL; ?>"); }
	else						{ afficher("span_logo_url",true);	set_value("logo_url","<?php echo $_SESSION["agora"]["logo_url"]; ?>"); }
}

////	GESTION DU PARAMETRAGE LDAP
function gestion_ldap()
{
	if(element('ldap_parametrage').style.display!="none")
	{
		if(confirm("<?php echo $trad["ldap_effacer_params"]; ?>")){
			champs_ldap = new Array('ldap_server','ldap_server_port','ldap_admin_login','ldap_admin_pass','ldap_groupe_dn','ldap_pass_cryptage','ldap_crea_auto_users');
			for(var i=0; i < champs_ldap.length; i++)	{ set_value(champs_ldap[i],''); }
		}
	}
	afficher("ldap_parametrage","bascule");
}
</script>

<style>
input[type=text],textarea	{  width:400px; }
.titre_line					{ font-weight:bold; width:350px; }
.titre_line_infos			{ font-weight:normal; }
.input_text_ldap			{ width:300px; }
</style>


<div class="contenu_principal_centre">
	<table class="div_elem_deselect" style="width:100%;margin-top:10px;margin-bottom:20px;font-weight:bold;" cellpadding="5px"><tr>
		<td style="text-align:center;font-weight:bold;">
			<button class="button_big" style="width:43%;" onClick="<?php echo $alert_info_sav; ?>window.open('sav.php');" /><img src="<?php echo PATH_TPL; ?>divers/telecharger.png"  /> &nbsp; <?php echo $trad["PARAMETRAGE_sav"]; ?> <img src="<?php echo PATH_TPL; ?>divers/export_bdd.png" /><img src="<?php echo PATH_TPL; ?>divers/dossier_small.png" style="height:20px;" /></button> &nbsp; &nbsp; &nbsp; &nbsp;
			<button class="button_big" style="width:43%;" onClick="window.open('sav.php?savbdd=1');" /><img src="<?php echo PATH_TPL; ?>divers/telecharger.png"  /> &nbsp; <?php echo $trad["PARAMETRAGE_sav_bdd"]; ?> <img src="<?php echo PATH_TPL; ?>divers/export_bdd.png" /></button>
		</td>
	</tr></table>


	<form action="<?php echo php_self(); ?>" method="post" enctype="multipart/form-data" OnSubmit="return controle_parametrage_site()">
		<table class="div_elem_deselect" style="width:100%;margin-bottom:50px;" cellpadding="5px">
			<?php
			////	VERSION D'AGORA (+PHP +Mysql +GD2? + Mail()?)
			echo "<tr>";
				echo "<td class='titre_line'>".$trad["PARAMETRAGE_versions"]."</td>";
				echo "<td>";
					echo "<b style='margin-right:50px;'>Agora-Project ".$_SESSION["agora"]["version_agora"]." - ".$trad["PARAMETRAGE_version_agora_maj"]." ".strftime("%d/%m/%Y", strtotime($_SESSION["agora"]["mise_a_jour_effective"]))."</b>";
					echo "PHP ".str_replace(strstr(@phpversion(),"ubuntu"),"",@phpversion());
					echo "&nbsp; &nbsp; / &nbsp; &nbsp; MySQL ".str_replace(strstr(db_version(),"ubuntu"),"",db_version());
					if(function_exists("mail")==false)					{echo "<br><span ".infobulle($trad["PARAMETRAGE_fonction_mail_infos"])."><img src=\"".PATH_TPL."divers/supprimer.png\" /> &nbsp; ".$trad["PARAMETRAGE_fonction_mail_desactive"]."</span>";}
					if(function_exists("ImageCreateTrueColor")==false)	{echo "<br><span><img src=\"".PATH_TPL."divers/supprimer.png\" /> &nbsp; ".$trad["PARAMETRAGE_fonction_image_desactive"]."</span>";}
				echo "</td>";
			echo "</tr>";
			echo "<tr><td colspan='2'></td></tr>";
			////	NOM
			echo "<tr>";
				echo "<td class='titre_line'>".$trad["PARAMETRAGE_nom_site"]."</td>";
				echo "<td><input type='text' name='nom' id='nom' value=\"".$_SESSION["agora"]["nom"]."\" /></td>";
			echo "</tr>";
			////	DESCRIPTION
			echo "<tr>";
				echo "<td class='titre_line'>".$trad["description"]."</td>";
				echo "<td><input type='text' name='description' value=\"".$_SESSION["agora"]["description"]."\" /></td>";
			echo "</tr>";
			////	ADRESSE WEB
			echo "<tr>";
				echo "<td class='titre_line'>".$trad["PARAMETRAGE_adresse_web"]."</td>";
				echo "<td><input type='text' name='adresse_web' id='adresse_web' value=\"".$_SESSION["agora"]["adresse_web"]."\" onkeyup=\"controle_adresse_web();\" onchange=\"controle_adresse_web();\"  ".(defined("HOST_DOMAINE")?"readonly='readonly'":"")." /></td>";
			echo "</tr>";
			////	FOOTER HTML
			echo "<tr>";
				echo "<td  class='titre_line'><acronym ".infobulle($trad["PARAMETRAGE_footer_html_info"]).">".$trad["PARAMETRAGE_footer_html"]."</acronym></td>";
				echo "<td><textarea name='footer_html' id='footer_html' style='height:35px;'>".str_replace("\"","&quot;",$_SESSION["agora"]["footer_html"])."</textarea></td>";
			echo "</tr>";


			echo "<tr><td colspan='2'><hr></td></tr>";
			////	SKIN BLANC OU NOIR
			echo "<tr>";
				echo "<td class='titre_line'>".$trad["PARAMETRAGE_skin"]."</td>";
				echo "<td>";
					echo "<select name='skin'>
							<option value='noir' selected='selected'>".$trad["PARAMETRAGE_noir"]."</option>
							<option value='blanc'>".$trad["PARAMETRAGE_blanc"]."</option>
						  </select>";
					echo "<script> set_value('skin','".$_SESSION["agora"]["skin"]."',true); </script>";
				echo "</td>";
			echo "</tr>";
			////	FOND D'ECRAN
			echo "<tr>";
				echo "<td class='titre_line' class='titre_line'>".$trad["fond_ecran"]."</td>";
				echo "<td style='vertical-align:top'>".menu_fonds_ecran($_SESSION["agora"]["fond_ecran"],1)."</td>";
			echo "</tr>";
			////	LOGO DU FOOTER
			echo "<tr>";
				echo "<td class='titre_line'>".$trad["PARAMETRAGE_logo_footer"]."</td>";
				echo "<td style='vertical-align:top;font-weight:bold;'>";
					echo "<img src=\"".path_logo_footer()."\" style='max-height:".@$_SESSION["logo_footer_height"]."px;' /> &nbsp; ";
					echo "<select name='logo' id='logo_footer' OnChange=\"gestion_logo_url();\">";
						echo "<option value=''>".$trad["par_defaut"]."</option>";
						if($_SESSION["agora"]["logo"]!="")	{echo "<option value=\"".$_SESSION["agora"]["logo"]."\" selected>".$trad["garder"]."</option>";}
						echo "<option value='ajouter'>".$trad["modifier"]."</option>";
					echo "</select>";
					echo "&nbsp; <input type='file' name='fichier_logo' id='fichier_logo' class='cacher' />";
					echo "<span id='span_logo_url'>&nbsp; ".$trad["PARAMETRAGE_logo_footer_url"]." <input type='text' name='logo_url' value=\"".$_SESSION["agora"]["logo_url"]."\" style='width:200px;' /></span>";
					echo "<script type='text/javascript'> gestion_logo_url(); </script>";
				echo "</td>";
			echo "</tr>";
			////	LIBELLE DES MODULES DANS LE MENU PRINCIPAL ?
			echo "<tr>";
				echo "<td class='titre_line'>".$trad["PARAMETRAGE_libelle_module"]."</td>";
				echo "<td>";
					echo "<select name='libelle_module'>
							<option value=''>".$trad["PARAMETRAGE_libelle_module_masquer"]."</option>
							<option value='icones'>".$trad["PARAMETRAGE_libelle_module_icones"]."</option>
							<option value='page'>".$trad["PARAMETRAGE_libelle_module_page"]."</option>
						  </select>";
					echo "<script> set_value('libelle_module','".$_SESSION["agora"]["libelle_module"]."'); </script>";
				echo "</td>";
			echo "</tr>";


			echo "<tr><td colspan='2'><hr></td></tr>";
			////	LANGUE PAR DEFAUT
			echo "<tr>";
				echo "<td class='titre_line'>".$trad["PARAMETRAGE_langues"]."</td>";
				echo "<td>".liste_langues($_SESSION["agora"]["langue"],"site")."</td>";
			echo "</tr>";
			////	TIMEZONE
			echo "<tr>";
				echo "<td class='titre_line'>".$trad["PARAMETRAGE_timezone"]."</td>";
				echo "<td><select name='timezone'>";
					foreach($tab_timezones as $timezone_libelle => $timezone){
						echo "<option value=\"".$timezone."\">[GMT ".($timezone>0?"+".$timezone:$timezone)."]&nbsp; ".$timezone_libelle."</option>";
					}
				echo "</select></td>";
				echo "<script> set_value('timezone','".($_SESSION["agora"]["timezone"]==""?server_timezone("num"):$_SESSION["agora"]["timezone"])."',true); </script>";
			echo "</tr>";
			////	ESPACE DISQUE LIMIT
			if(!defined("HOST_DOMAINE"))
			{
				echo "<tr>";
					echo "<td class='titre_line'>".$trad["PARAMETRAGE_limite_espace_disque"]."</td>";
					echo "<td>";
						echo "<input type='text' name='limite_espace_disque' value=\"".afficher_taille(limite_espace_disque,false)."\" style='width:50px' /> ";
						echo "<select name='limite_espace_disque_unite'>
								<option value='g'>".$trad["giga_octet"]."</option>
								<option value='m' ".(limite_espace_disque<1073741824?"selected":"").">".$trad["mega_octet"]."</option>
							  </select>";
					echo "</td>";
				echo "</tr>";
			}
			////	TEMPS DE CONSERVATION DES LOGS ?
			echo "<tr>";
				echo "<td class='titre_line'>".$trad["PARAMETRAGE_logs_jours_conservation"]."</td>";
				echo "<td>";
					echo "<select name='logs_jours_conservation'><option value='0'>0</option><option value='15'>15</option><option value='30'>30</option><option value='60'>60</option><option value='120'>120</option>".(defined("HOST_DOMAINE")?"":"<option value='360'>360</option>")."</select> &nbsp; ".$trad["jours"];
					echo "<script> set_value('logs_jours_conservation','".$_SESSION["agora"]["logs_jours_conservation"]."',true); </script>";
				echo "</td>";
			echo "</tr>";
			////	EDITION POPUP OU LIGHTBOX
			echo "<tr>";
				echo "<td class='titre_line'>".$trad["PARAMETRAGE_mode_edition"]."</td>";
				echo "<td>";
					echo "<select name='edition_popup'>
							<option value=''>".$trad["PARAMETRAGE_edition_iframe"]."</option>
							<option value='1'>".$trad["PARAMETRAGE_edition_popup"]."</option>
						  </select>";
					echo "<script> set_value('edition_popup','".$_SESSION["agora"]["edition_popup"]."'); </script>";
				echo "</td>";
			echo "</tr>";
			////	MODE DE L'EDITEUR TINYMCE
			echo "<tr>";
				echo "<td class='titre_line'>".$trad["PARAMETRAGE_editeur_text_mode"]."</td>";
				echo "<td>";
					echo "<select name='editeur_text_mode'>
							<option value='minimal' selected=selected>".$trad["PARAMETRAGE_editeur_text_minimal"]."</option>
							<option value='complet'>".$trad["PARAMETRAGE_editeur_text_complet"]."</option>
						  </select>";
					echo "<script> set_value('editeur_text_mode','".$_SESSION["agora"]["editeur_text_mode"]."',true); </script>";
				echo "</td>";
			echo "</tr>";
			////	TRI DES PERSONNES PAR DEFAUT : PAR NOM OU PAR PRENOM
			echo "<tr>";
				echo "<td class='titre_line'>".$trad["PARAMETRAGE_tri_personnes"]."</td>";
				echo "<td>";
					echo "<select name='tri_personnes'>
							<option value='nom'>".$trad["nom"]."</option>
							<option value='prenom'>".$trad["prenom"]."</option>
						  </select>";
					echo "<script> set_value('tri_personnes','".$_SESSION["agora"]["tri_personnes"]."',true); </script>";
				echo "</td>";
			echo "</tr>";
			////	MESSENGER DESACTIVE ?
			echo "<tr>";
				echo "<td class='titre_line'>".$trad["PARAMETRAGE_messenger_desactive"]."</td>";
				echo "<td>";
					echo "<select name='messenger_desactive'>";
						echo "<option value=''>".$trad["oui"]."</option>";
						echo "<option value='1'>".$trad["non"]."</option>";
					echo "</select>";
					echo "<script> set_value('messenger_desactive','".$_SESSION["agora"]["messenger_desactive"]."'); </script>";
				echo "</td>";
			echo "</tr>";
			////	AGENDAS PERSONNELS DESACTIVES PAR DEFAUT ?
			echo "<tr>";
				echo "<td class='titre_line'><acronym ".infobulle($trad["PARAMETRAGE_agenda_perso_desactive_infos"]).">".$trad["PARAMETRAGE_agenda_perso_desactive"]."</acronym></td>";
				echo "<td>";
					echo "<select name='agenda_perso_desactive'>";
						echo "<option value=''>".$trad["oui"]."</option>";
						echo "<option value='1'>".$trad["non"]."</option>";
					echo "</select>";
					echo "<script> set_value('agenda_perso_desactive','".$_SESSION["agora"]["agenda_perso_desactive"]."'); </script>";
				echo "</td>";
			echo "</tr>";			
			////	CONNEXION A UN SERVEUR LDAP ?
			echo "<tr>";
				echo "<td class='titre_line lien' onClick=\"gestion_ldap();\">".$trad["ldap_connexion_serveur"]." <img src=\"".PATH_TPL."divers/plus.png\" /></td>";
				echo "<td>";
					echo "<table id='ldap_parametrage' style='".($_SESSION["agora"]["ldap_server"]==""?"display:none;":"")."'  cellpadding='2px'>";
						echo "<tr><td style='width:350px;'>".$trad["ldap_server"]."</td><td><input type='text' name='ldap_server' value='".$_SESSION["agora"]["ldap_server"]."' class='input_text_ldap' /></td></tr>";
						echo "<tr><td><acronym ".infobulle($trad["ldap_server_port_infos"]).">".$trad["ldap_server_port"]."</acronym></td><td><input type='text' name='ldap_server_port' value='".$_SESSION["agora"]["ldap_server_port"]."' class='input_text_ldap' /></td></tr>";
						echo "<tr><td><acronym ".infobulle($trad["ldap_admin_login_infos"]).">".$trad["ldap_admin_login"]."</acronym></td><td><input type='text' name='ldap_admin_login' value='".$_SESSION["agora"]["ldap_admin_login"]."' class='input_text_ldap' /></td></tr>";
						echo "<tr><td>".$trad["ldap_admin_pass"]."</td><td><input type='password' name='ldap_admin_pass' value='".$_SESSION["agora"]["ldap_admin_pass"]."' class='input_text_ldap' /></td></tr>";
						echo "<tr><td><acronym ".infobulle($trad["ldap_groupe_dn_infos"]).">".$trad["ldap_groupe_dn"]."</acronym></td><td><input type='text' name='ldap_groupe_dn' value='".$_SESSION["agora"]["ldap_groupe_dn"]."' class='input_text_ldap' /></td></tr>";
						echo "<tr><td><acronym ".infobulle($trad["ldap_crea_auto_users_infos"]).">".$trad["ldap_crea_auto_users"]."</acronym></td><td><select name='ldap_crea_auto_users'><option value=''>".$trad["non"]."</option><option value='1'>".$trad["oui"]."</option></select><script> set_value('ldap_crea_auto_users','".$_SESSION["agora"]["ldap_crea_auto_users"]."'); </script></td></tr>";
						echo "<tr><td><img src=\"".PATH_TPL."divers/dependance_dossier.png\" /> ".$trad["ldap_pass_cryptage"]."</td><td><select name='ldap_pass_cryptage'><option value='aucun'>".$trad["aucun"]."</option><option value='md5'>MD5</option><option value='sha'>SHA</option></select><script> set_value('ldap_pass_cryptage','".$_SESSION["agora"]["ldap_pass_cryptage"]."',true); </script></td></tr>";
					echo "</table>";
					// Module de connexion LDAP désactivé?
					if(!function_exists("ldap_connect"))	{echo "<div class='div_infos' style='font-weight:bold;color:#900'>".$trad["ldap_pas_module_php"]."</div>";}
				echo "</td>";
			echo "</tr>";

			////	BOUTON VALIDATION
			echo "<tr>";
				echo "<td colspan='2' style='text-align:right'><input type='submit' name='submit_parametrage' value=\"".$trad["modifier"]."\" class='button_big' style='width:250px;margin:10px;' /></td>";
			echo "</tr>";
			?>
		</table>
	</form>

</div>


<?php require PATH_INC."footer.inc.php"; ?>