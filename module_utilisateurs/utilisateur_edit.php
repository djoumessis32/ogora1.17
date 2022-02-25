<?php
////	INIT
define("NO_MODULE_CONTROL",true);
require "commun.inc.php";
require_once PATH_INC."header.inc.php";
$nb_ip_controle = 10;
if(droit_modif_utilisateur(@$_REQUEST["id_utilisateur"])!="1" && $_SESSION["espace"]["droit_acces"]<2)	{exit();}
if(isset($_REQUEST["id_utilisateur"]))	{$user_tmp = user_infos($_REQUEST["id_utilisateur"]);}
else									{nb_users_depasse();}


////	VALIDATION DU FORMULAIRE D'EDITION D'UTILISATEUR
////
if(@$_POST["action"]=="editer")
{
	////	MODIF / AJOUT
	$corps_sql = " civilite=".db_format($_POST["civilite"]).", nom=".db_format($_POST["nom"]).", prenom=".db_format($_POST["prenom"]).", identifiant=".db_format($_POST["identifiant"]).", adresse=".db_format($_POST["adresse"]).", codepostal=".db_format($_POST["codepostal"]).", ville=".db_format($_POST["ville"]).", pays=".db_format($_POST["pays"]).", telephone=".db_format($_POST["telephone"]).", telmobile=".db_format($_POST["telmobile"]).", fax=".db_format($_POST["fax"]).", mail=".db_format($_POST["mail"]).", siteweb=".db_format($_POST["siteweb"]).", competences=".db_format($_POST["competences"]).", hobbies=".db_format($_POST["hobbies"]).", fonction=".db_format($_POST["fonction"]).", societe_organisme=".db_format($_POST["societe_organisme"]).", commentaire=".db_format($_POST["commentaire"]).", langue=".db_format($_POST["langue"]).", espace_connexion=".db_format(@$_POST["espace_connexion"]).", ip_controle=".db_format(tab2text(@$_POST["ip_controle"]));
	if($_POST["id_utilisateur"]>0 && $_POST["pass"]!=""){
		$corps_sql .= ", pass='".sha1_pass($_POST["pass"])."'";
		add_logs("modif", $objet["utilisateur"], $_POST["id_utilisateur"], auteur($_POST["id_utilisateur"]));
	}
	elseif($_POST["id_utilisateur"]<1){
		$_POST["id_utilisateur"] = creer_utilisateur($_POST["identifiant"], $_POST["pass"], @$_POST["nom"], @$_POST["prenom"]);
		add_logs("ajout", $objet["utilisateur"], $_POST["id_utilisateur"], auteur($_POST["id_utilisateur"]));
	}

	////	CREATION VALIDEE
	if($_POST["id_utilisateur"]>0)
	{
		////	ENREGISTRE LES INFOS SUR L'USER
		db_query("UPDATE gt_utilisateur SET ".$corps_sql." WHERE id_utilisateur=".db_format($_POST["id_utilisateur"]));

		////	AGENDA DESACTIVE  /  ADMIN GENERAL  /  ESPACE DE CONNEXION  /  MAJ DES VALEURS DE SESSION
		if(isset($_POST["agenda_desactive"]) && $_SESSION["user"]["admin_general"]==1)
			{db_query("UPDATE gt_utilisateur SET agenda_desactive=".db_format($_POST["agenda_desactive"],"bool")." WHERE id_utilisateur=".db_format($_POST["id_utilisateur"]));}
		if(isset($_POST["admin_general"]) && $_SESSION["user"]["admin_general"]==1)
			{db_query("UPDATE gt_utilisateur SET admin_general=".db_format($_POST["admin_general"],"bool")." WHERE id_utilisateur=".db_format($_POST["id_utilisateur"]));}
		if(isset($_POST["espace_connexion"]) && ($_SESSION["user"]["admin_general"]==1 || $_SESSION["user"]["id_utilisateur"]==$_POST["id_utilisateur"]))
			{db_query("UPDATE gt_utilisateur SET espace_connexion=".db_format($_POST["espace_connexion"])." WHERE id_utilisateur=".db_format($_POST["id_utilisateur"]));}
		if(is_auteur($_POST["id_utilisateur"])==true)
			{$_SESSION["user"] = user_infos($_POST["id_utilisateur"]);}

		////	PHOTO
		if(preg_match("/supprimer|changer/i",$_POST["image"]))
		{
			// Supprime
			$nom_photo = user_infos($_POST["id_utilisateur"],"photo");
			if($nom_photo!=""){
				unlink(PATH_PHOTOS_USER.$nom_photo);
				db_query("UPDATE gt_utilisateur SET photo=null WHERE id_utilisateur=".db_format($_POST["id_utilisateur"]));
			}
			// Ajoute / change
			if($_POST["image"]=="changer" && controle_fichier("image_gd",@$_FILES["fichier_image"]["name"])==true)
			{
				$nom_photo = $_POST["id_utilisateur"].extension($_FILES["fichier_image"]["name"]);
				$chemin_photo = PATH_PHOTOS_USER.$nom_photo;
				move_uploaded_file($_FILES["fichier_image"]["tmp_name"], $chemin_photo);
				reduire_image($chemin_photo, $chemin_photo, 200, 200);
				db_query("UPDATE gt_utilisateur SET photo='".$nom_photo."' WHERE id_utilisateur=".db_format($_POST["id_utilisateur"]));
			}
		}

		/////	AFFECTATION AUX ESPACES (avec réinitialisation)
		if($_SESSION["user"]["admin_general"]==1)
		{
			db_query("DELETE FROM gt_jointure_espace_utilisateur WHERE id_utilisateur=".db_format($_POST["id_utilisateur"]));
			if(isset($_POST["espace"])) {
				foreach($_POST["espace"] as $id_espace => $droit)	{ db_query("INSERT INTO gt_jointure_espace_utilisateur SET id_espace=".db_format($id_espace).", id_utilisateur=".db_format($_POST["id_utilisateur"]).", droit=".db_format($droit)); }
			}
		}

		////	AFFECTATION A L'ESPACE COURANT (S'IL N'EST PAS DEJA OUVERT A TOUS LES USERS DU SITE)
		if(isset($_POST["add_espace_courant"]) && db_valeur("SELECT count(*) FROM gt_jointure_espace_utilisateur WHERE id_espace='".$_SESSION["espace"]["id_espace"]."' AND tous_utilisateurs='1' ")==0)
			{db_query("INSERT INTO gt_jointure_espace_utilisateur SET id_espace='".$_SESSION["espace"]["id_espace"]."', id_utilisateur=".db_format($_POST["id_utilisateur"]).", droit='1'");}

		////	ENVOI DE NOTIFICATION PAR MAIL
		if(isset($_POST["notification"]) && $_POST["mail"])
		{
			$objet_mail = $trad["UTILISATEURS_mail_objet_nouvel_utilisateur"]."  ''".$_SESSION["agora"]["nom"]."''";
			$contenu_mail  = $trad["UTILISATEURS_mail_nouvel_utilisateur"]."  ''".$_SESSION["agora"]["nom"]."''";
			$contenu_mail .= "<br><br>".$trad["UTILISATEURS_mail_infos_connexion"]." :";
			$contenu_mail .= "<br>".$trad["identifiant_connexion"]." : <b>".$_POST["identifiant"]."</b>";
			$contenu_mail .= "<br>".$trad["pass"]." : <b>".$_POST["pass"]."</b>";
			$contenu_mail .= "<br><br>".$trad["UTILISATEURS_mail_infos_connexion2"];
			envoi_mail($_POST["mail"], $objet_mail, texteNotification($contenu_mail), array("notif"=>true));
		}
	}

	////	FERMETURE
	reload_close();
}
?>


<script type="text/javascript">
////	Redimensionne
resizePopupLightbox(550,600);

////	AJOUTER / SUPPRIMER UN CHAMP D'IP DE CONTROLE
function ajouter_ip_controle()
{
	for(var i=0; i<<?php echo $nb_ip_controle; ?>; i++){
		if(element("div_ip_controle_"+i).style.display=="none")  { afficher("div_ip_controle_"+i);  break; }
	}
}
function supprimer_ip_controle(id_champ)
{
	element(id_champ).value = "";
	afficher("div_"+id_champ,false);
}


////	On contrôle les champs principaux
function controle_formulaire()
{
	// Certains champs sont obligatoire
	if(get_value("nom")=="")			{ alert("<?php echo $trad["UTILISATEURS_specifier_nom"]; ?>");			return false; }
	if(get_value("prenom")=="")			{ alert("<?php echo $trad["UTILISATEURS_specifier_prenom"]; ?>");		return false; }
	if(get_value("identifiant")=="")	{ alert("<?php echo $trad["UTILISATEURS_specifier_identifiant"]; ?>");	return false; }
	// Vérif du password (new user / modif de password)
	if(get_value("pass")=="" && '<?php echo @$_REQUEST["id_utilisateur"]; ?>' < 1)	{ alert("<?php echo $trad["UTILISATEURS_specifier_pass"]; ?>");	return false; }
	if(get_value("pass")!=get_value("pass2"))	{  alert("<?php echo $trad["password_verif_alert"]; ?>");  return false;  }
	// Vérif de l'email (si spécifié)
	if(get_value("mail")!="" && controle_mail(get_value("mail"))==false)	{ alert("<?php echo $trad["mail_pas_valide"]; ?>");  return false; }
	// vérif des adresses ip de controle
	var reg = /([0-9]{1,3}\.){3}[0-9]{1,3}/;  //Adresse ip => 1 à 3 chiffres suivit d'un point, répété 3 fois, puis 1 à 3 chiffres
	for(var i=0; i < <?php echo $nb_ip_controle; ?>; i++)
	{
		if(existe("ip_controle_"+i) && element("ip_controle_"+i).value!="" && reg.test(element("ip_controle_"+i).value)==false){
			alert(element("ip_controle_"+i).value+" >> <?php echo $trad["UTILISATEURS_ip_invalide"]; ?>");
			return false;
		}
	}
	// controle existance identifiant
	requete_ajax("identifiant_verif.php?identifiant="+urlencode(get_value("identifiant"))+"&id_utilisateur="+get_value("id_utilisateur"));
	if(trouver("oui",retour_ajax))	{ alert("<?php echo $trad["UTILISATEURS_identifiant_deja_present"]; ?>"); return false; }
}
</script>


<?php
////	FORMULAIRE PRINCIPAL
////
echo "<form action=\"".php_self()."\" enctype='multipart/form-data' method='post' style='padding:10px' OnSubmit='return controle_formulaire();'>";

	////	INFOS PRINCIPALES
	////
	echo "<fieldset style='margin-top:10px'>";
		user_champ(@$user_tmp, "civilite");
		user_champ(@$user_tmp, "nom", "obligatoire");
		user_champ(@$user_tmp, "prenom", "obligatoire");
		user_champ(@$user_tmp, "identifiant", "obligatoire");
		$pass_obligatoire = (@$_REQUEST["id_utilisateur"]<1)  ?  "obligatoire"  :  "";
		user_champ(@$user_tmp, "pass", $pass_obligatoire);
		user_champ(@$user_tmp, "pass2", $pass_obligatoire);
		echo "<hr class='user_champ_hr' />";
		user_champ(@$user_tmp, "mail");
		user_champ(@$user_tmp, "telephone");
		user_champ(@$user_tmp, "telmobile");
		user_champ(@$user_tmp, "fax");
		user_champ(@$user_tmp, "siteweb");
		echo "<hr class='user_champ_hr' />";
		user_champ(@$user_tmp, "adresse");
		user_champ(@$user_tmp, "codepostal");
		user_champ(@$user_tmp, "ville");
		user_champ(@$user_tmp, "pays");
		echo "<hr class='user_champ_hr' />";
		user_champ(@$user_tmp, "competences");
		user_champ(@$user_tmp, "hobbies");
		user_champ(@$user_tmp, "fonction");
		user_champ(@$user_tmp, "societe_organisme");
		echo "<div class='user_champ_ligne'>";
			echo "<div class='user_champ_cell_lib'>".$trad["commentaire"]."</div>";
			echo "<div class='user_champ_cell'><textarea name='commentaire' style='width:100%;height:45px;'>".@$user_tmp["commentaire"]."</textarea></div>";
		echo "</div>";
		echo "<div class='user_champ_ligne'>";
			$src_photo = (!isset($user_tmp["photo"]) || $user_tmp["photo"]=="")  ?  PATH_TPL."module_utilisateurs/user.png"  :  PATH_PHOTOS_USER.$user_tmp["photo"];
			echo "<div class='user_champ_cell_lib'><img src=\"".$src_photo."\" class='user_champ_photo' /></div>";
			echo "<div class='user_champ_cell' style='vertical-align:middle;'><br>".menu_photo(@$user_tmp["photo"])."</div>";
		echo "</div>";
	echo "</fieldset>";


	////	PARAMETRAGE DU COMPTE UTILISATEUR
	////
	echo "<fieldset style='margin-top:40px'><legend>".$trad["divers"]."</legend>";
		////	AGENDA ACTIVE
		if($_SESSION["user"]["admin_general"]==1)
		{
			echo "<div class='user_champ_ligne' ".infobulle($trad["UTILISATEURS_agenda_perso_active_infos"]).">";
				echo "<div class='user_champ_cell_lib2'><img src=\"".PATH_TPL."module_utilisateurs/user_agenda.png\" />&nbsp; <acronym>".$trad["UTILISATEURS_agenda_perso_active"]."</acronym></div>";
				echo "<div class='user_champ_cell'>";
					echo "<select name='agenda_desactive' onChange=\"style_select(this.name);\">";
						echo "<option value='0' style='color:#090;font-weight:bold;'>".$trad["oui"]."</option>";
						echo "<option value='1'>".$trad["non"]."</option>";
					echo "</select>";
					$agenda_desactive_value = (@$user_tmp["agenda_desactive"]=="1" || ($_SESSION["agora"]["agenda_perso_desactive"]=="1" && empty($user_tmp["id_utilisateur"])))  ?  "1"  :  "0";
					echo "<script>  set_value('agenda_desactive','".$agenda_desactive_value."');  style_select('agenda_desactive');  </script>";
				echo "</div>";
			echo "</div>";
		}
		////	ADMIN GENERAL ?
		if($_SESSION["user"]["admin_general"]==1 && $_SESSION["user"]["id_utilisateur"]!=@$user_tmp["id_utilisateur"])
		{
			echo "<div class='user_champ_ligne'>";
				echo "<div class='user_champ_cell_lib2'><img src=\"".PATH_TPL."module_utilisateurs/acces_admin_general.png\" width='16px' />&nbsp; ".$trad["UTILISATEURS_admin_general"]."</div>";
				echo "<div class='user_champ_cell'>";
					echo "<select name='admin_general' onChange=\"style_select(this.name);\">";
						echo "<option value='0'>".$trad["non"]."</option>";
						echo "<option value='1' style='color:#900;font-weight:bold;'>".$trad["oui"]."</option>";
					echo "</select>";
					$admin_general_value = (@$user_tmp["admin_general"]=="1")  ?  "1"  :  "0";
					echo "<script>  set_value('admin_general','".$admin_general_value."');  style_select('admin_general');  </script>";;
				echo "</div>";
			echo "</div>";
		}
		////	ESPACE CONNEXION AGORA
		$espaces_user = espaces_affectes_user(@$user_tmp,true);
		if(count($espaces_user)>0)
		{
			foreach($espaces_user as $espace_tmp)	{ @$liste_espaces_consult .= "<option value=\"".$espace_tmp["id_espace"]."\" ". (($user_tmp["espace_connexion"]==$espace_tmp["id_espace"])?"selected":"") ."> ".$espace_tmp["nom"]."</option>";  }
			echo "<div class='user_champ_ligne'>";
				echo "<div class='user_champ_cell_lib2'><img src=\"".PATH_TPL."module_utilisateurs/user_connexion.png\" />&nbsp; ".$trad["UTILISATEURS_espace_connexion"]."</div>";
				echo "<div class='user_champ_cell'><select name='espace_connexion'>".@$liste_espaces_consult."</select></div>";
			echo "</div>";
		}
		////	LANGUE
		echo "<div class='user_champ_ligne'>";
			echo "<div class='user_champ_cell_lib2'><img src=\"".PATH_TPL."module_utilisateurs/user_pays.png\" />&nbsp; ".$trad["UTILISATEURS_langues"]."</div>";
			echo "<div class='user_champ_cell'>".liste_langues(@$user_tmp["langue"],"user")."</div>";
		echo "</div>";
		////	ADRESSES IP DE CONTROLE (ADMIN GENERAL UNIQUEMENT)
		if($_SESSION["user"]["admin_general"]==1 && controle_ip==true)
		{
			$liste_ip_contole = text2tab(@$user_tmp["ip_controle"]);
			echo "<div class='user_champ_ligne' title=\"".$trad["UTILISATEURS_info_adresse_ip"]."\">";
				echo "<div class='user_champ_cell_lib2'><img src=\"".PATH_TPL."module_utilisateurs/user_ip.png\" />&nbsp; ".$trad["UTILISATEURS_adresses_ip"]." &nbsp;<img src=\"".PATH_TPL."divers/plus2.png\" onclick=\"ajouter_ip_controle();\" class='lien' title=\"".$trad["ajouter"]."\" /></div>";
				echo "<div class='user_champ_cell'>";
				for($i=0; $i < $nb_ip_controle; $i++)
				{
					$display_tmp = ($i==0 || @$liste_ip_contole[$i]!="")  ?  ""  :  "style='display:none;'";
					echo "<div id='div_ip_controle_".$i."' ".$display_tmp.">";
						echo "<input type='text' name='ip_controle[]' value=\"".@$liste_ip_contole[$i]."\" id='ip_controle_".$i."' style='color:#900;' />";
						if($i>0)	{echo "<img src=\"".PATH_TPL."divers/supprimer.png\" onclick=\"supprimer_ip_controle('ip_controle_".$i."');\" class='lien' title=\"".$trad["supprimer"]."\" />";}
					echo "</div>";
				}
				echo "</div>";
			echo "</div>";
		}
		////	NOTIFICATION PAR MAIL
		if(empty($user_tmp["id_utilisateur"]) && function_exists("mail")==true)
		{
			echo "<hr class='user_champ_hr' />";
			echo "<div style='text-align:center;' onClick='if(get_value('mail')=='' && is_checked('notification')) alert('".addslashes($trad["UTILISATEURS_alert_notification_mail"])."');'>";
				echo "<span onClick=\"set_check('notification','bascule');\" class='lien'><img src=\"".PATH_TPL."module_utilisateurs/user_mail.png\" /> &nbsp; ".$trad["UTILISATEURS_notification_mail"]."</span>";
				echo "<input type='checkbox' name='notification' value='1' />";
			echo "</div>";
		}
	echo "</fieldset>";


	////	ESPACES OU L'UTILISATEUR EST AFFECTE
	////
	if($_SESSION["user"]["admin_general"]==1)
	{
		echo "<fieldset style='margin-top:40px'><legend>".$trad["UTILISATEURS_liste_espaces"]."</legend>".
			"<table class='pas_selection' style='width:100%;font-weight:bold;'>".
				"<tr>".
					"<td>&nbsp;</td>".
					"<td title=\"".$trad["ESPACES_utilisation_info"]."\"><img src=\"".PATH_TPL."module_utilisateurs/acces_utilisateur.png\" /> ".$trad["ESPACES_utilisation"]."</td>".
					"<td title=\"".$trad["ESPACES_administration_info"]."\"><img src=\"".PATH_TPL."module_utilisateurs/acces_admin_espace.png\" /> ".$trad["ESPACES_administration"]."</td>".
				"</tr>";
			foreach(db_tableau("SELECT * FROM gt_espace") as $compteur => $espace_tmp)
			{
				$class_txt = "lien";
				$checked1 = $checked2 = $tous_users_info = "";
				$id_tmp = "espace".$compteur;
				$sql_tmp = "SELECT count(*) FROM gt_jointure_espace_utilisateur WHERE id_espace='".$espace_tmp["id_espace"]."' AND id_utilisateur='".@$user_tmp["id_utilisateur"]."'";
				if(db_valeur($sql_tmp." AND droit=1")>0)	{ $checked1  = "checked";	$class_txt = "txt_acces_user"; }
				if(db_valeur($sql_tmp." AND droit=2")>0)	{ $checked2  = "checked";	$class_txt = "txt_acces_admin"; }
				if(db_valeur("SELECT count(*) FROM gt_jointure_espace_utilisateur WHERE id_espace='".$espace_tmp["id_espace"]."' AND tous_utilisateurs='1' AND droit=1")>0)		{ $checked1 .= "checked disabled";  $tous_users_info = "title=\"".$trad["UTILISATEURS_tous_users_affectes"]."\"";  $class_txt = "txt_acces_user"; }
				echo "<tr class='ligne_survol'>".
					"<td class='".$class_txt." pas_selection' style='width:200px;' id='".$id_tmp."_txt' onClick=\"affect_users_espaces(this,'".$id_tmp."');\" ".$tous_users_info.">".$espace_tmp["nom"]." ".($tous_users_info!=""?"*":"")."</td>".
					"<td style='width:100px;'><input type='checkbox' name='espace[".$espace_tmp["id_espace"]."]' value='1' id='".$id_tmp."_box_1' onClick=\"affect_users_espaces(this,'".$id_tmp."');\" title=\"".$trad["ESPACES_utilisation_info"]."\" ".$checked1." /></td>".
					"<td style='width:120px;'><input type='checkbox' name='espace[".$espace_tmp["id_espace"]."]' value='2' id='".$id_tmp."_box_2' onClick=\"affect_users_espaces(this,'".$id_tmp."');\" title=\"".$trad["ESPACES_administration_info"]."\" ".$checked2." /></td>".
				"</tr>";
			}
		echo "</table></fieldset>";
	}


	////	VALIDATION FINALE
	////
	echo "<div style='text-align:center;margin-top:30px;'>";
		if(isset($_GET["add_espace_courant"]))	{echo "<input type='hidden' name='add_espace_courant' value='1' />";}
		echo "<input type='hidden' name='id_utilisateur' value='".@$user_tmp["id_utilisateur"]."' />";
		echo "<input type='hidden' name='action' value='editer' />";
		echo "<input type='submit' value=\"".$trad["valider"]."\" class='button_big' />";
	echo "</div>";
echo "</form>";


////	Footer
require PATH_INC."footer.inc.php";
?>