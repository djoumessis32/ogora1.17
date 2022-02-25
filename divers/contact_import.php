<?php
////	INIT
if($_REQUEST["type_export_import"]=="users"){
	include "../module_utilisateurs/commun.inc.php";
	controle_acces_admin("admin_espace");
	nb_users_depasse();
}
else{
	include "../module_contact/commun.inc.php";
	droit_acces_controler($objet["contact_dossier"], $_REQUEST["id_dossier"], 1);
}


////	HEADER & TITRE DU POPUP
////
require_once PATH_INC."header.inc.php";
require_once "contact_import_export.inc.php";
titre_popup(menu_import_export());


////	IMPORTE LES CONTACTS / UTILISATEURS SELECTIONNES
////
if(isset($_POST["champs_contact"]) && $_POST["contact_import"])
{
	////	TABLEAU DES CONTACTS A IMPORTER : EN FONCTION DE LA SELECTION
	$contacts_import = array();
	foreach($_POST["champs_contact"] as $contact_cpt => $contact)
	{
		// Si le contact a été sélectionné, on l'ajoute au tableau de sortie
		if(in_array($contact_cpt,$_POST["contact_import"])){
			$contact_tmp = array();
			foreach($_POST["champs_agora"] as $champ_cpt => $champ)		{ if($champ!=""){$contact_tmp[$champ]=$contact[$champ_cpt];} }
			$contacts_import[] = $contact_tmp;
		}
	}

	////	ON CREE LE CONTACT / L'UTILISATEUR
	foreach($contacts_import as $contact_tmp)
	{
		// INIT
		$corps_sql = "civilite=".db_format(@$contact_tmp["civilite"]).", adresse=".db_format(@$contact_tmp["adresse"]).", codepostal=".db_format(@$contact_tmp["codepostal"]).", ville=".db_format(@$contact_tmp["ville"]).", pays=".db_format(@$contact_tmp["pays"]).", telephone=".db_format(@$contact_tmp["telephone"]).", telmobile=".db_format(@$contact_tmp["telmobile"]).", fax=".db_format(@$contact_tmp["fax"]).", mail=".db_format(@$contact_tmp["mail"]).", siteweb=".db_format(@$contact_tmp["siteweb"]).", fonction=".db_format(@$contact_tmp["fonction"]).", societe_organisme=".db_format(@$contact_tmp["societe_organisme"]).", competences=".db_format(@$contact_tmp["competences"]).", hobbies=".db_format(@$contact_tmp["hobbies"]).", commentaire=".db_format(@$contact_tmp["commentaire"]);
		// CONTACT
		if($_REQUEST["type_export_import"]=="contacts")
		{
			// Création du contact & affectation en lecture à l'espace courant
			$_POST["lecture_espaces"][0] = $_SESSION["espace"]["id_espace"];
			db_query("INSERT INTO gt_contact SET id_dossier='".intval($_POST["id_dossier"])."', nom=".db_format(@$contact_tmp["nom"]).", prenom=".db_format(@$contact_tmp["prenom"]).", ".$corps_sql.", date_crea=".db_date_now().", id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."'");
			affecter_droits_acces($objet["contact"],db_last_id());
		}
		// UTILISATEUR
		elseif($_REQUEST["type_export_import"]=="users")
		{
			// Identifiant non spécifié  =>  Email  OU  "jdupon" (pour "Jean-claude DUPOND")
			if(empty($contact_tmp["identifiant"]))
			{
				if(!empty($contact_tmp["mail"]))	{$contact_tmp["identifiant"]=$contact_tmp["mail"];}
				else{
					$contact_tmp["identifiant"] = substr(@$contact_tmp["prenom"],0,1).substr(str_replace(" ","",@$contact_tmp["nom"]),0,5);
					$contact_tmp["identifiant"] = strtolower(suppr_carac_spe($contact_tmp["identifiant"],"faible"));
				}
			}
			// Password non spécifié  =>  nouveau password aléatoire
			if(empty($contact_tmp["pass"]))		{$contact_tmp["pass"]=mt_rand(100000,999999);}
			// Création de l'user
			$id_user_tmp = creer_utilisateur($contact_tmp["identifiant"], $contact_tmp["pass"], @$contact_tmp["nom"], @$contact_tmp["prenom"], @$contact_tmp["mail"]);
			if($id_user_tmp==false)	{continue;}
			// Ajout des infos sur l'utilisateur et affectation aux espaces
			db_query("UPDATE gt_utilisateur SET ".$corps_sql." WHERE id_utilisateur='".intval($id_user_tmp)."'");
			if(count(@$_POST["espaces_affectation"])>0)
			{
				foreach($_POST["espaces_affectation"] as $espace_tmp){
					db_query("INSERT INTO gt_jointure_espace_utilisateur SET id_espace=".db_format($espace_tmp).", id_utilisateur='".intval($id_user_tmp)."', droit='1'");
				}
			}
			// Envoi des coordonnées par mail ?
			if(isset($_POST["envoyer_id_password"]) && isset($contact_tmp["mail"]))
			{
				$sujet_mail = $_SESSION["agora"]["nom"]." : ".$trad["UTILISATEURS_mail_coordonnees"];
				$contenu_mail  = $trad["UTILISATEURS_mail_coordonnees"]." :<br>";
				$contenu_mail .= $trad["identifiant_connexion"]." : ".$contact_tmp["identifiant"]."<br>";
				$contenu_mail .= $trad["pass"]." : ".$contact_tmp["pass"]."<br>";
				$envoi_mail = envoi_mail($contact_tmp["mail"], $sujet_mail, $contenu_mail, array("message_alert"=>false));
			}
		}
	}
	////	FERMETURE
	reload_close();
}
?>


<style type="text/css">  body { background-image:url('<?php echo PATH_TPL; ?>module_utilisateurs/fond_popup.png'); font-weight:bold; }  </style>

<script type="text/javascript">
////	Redimensionne la page
<?php echo (empty($_POST)) ? "resizePopupLightbox(500,250);" : "resizePopupLightbox('90%','80%');"; ?>

////	Type d'import : Modif du formulaire en fonction du type
function controle_type_import()
{
	// Affiche le champ de sélection de fichier ?
	if(element('format_import').value=='ldap')	{ afficher('import_fichier',false);	afficher('info_ldap',true); }
	else										{ afficher('import_fichier',true);	afficher('info_ldap',false); }
}

////	Type d'import  : Validation du formulaire
function controle_formulaire_import()
{
	// Il doit y avoir un fichier au format csv
	if(element('format_import').value=='csv'){
		if(get_value("import_fichier")=="")					{ alert("<?php echo $trad["specifier_fichier"]; ?>");		return false; }
		if(extension(get_value("import_fichier"))!="csv")	{ alert("<?php echo $trad["extension_fichier"]; ?> CSV");	return false; }
	}
}

////	Liste des contacts : Contrôle du formulaire
function controle_contacts()
{
	// Le champ Agora "nom" doit être sélectionné
	var select_nom = false;
	for(var champ_cpt=0; champ_cpt < get_value("nb_champs"); champ_cpt++){
		if(get_value("champs_agora["+champ_cpt+"]")=="nom")		select_nom = true;
	}
	if(select_nom==false)	{ alert("<?php echo $trad["import_alert"]; ?>");  return false; }
	// Au moins un contact doit être sélectionné
	var nb_contacts_select = 0;
	for(var contact_cpt=0; contact_cpt < get_value("nb_contacts"); contact_cpt++)	{ if(element("contact_import["+contact_cpt+"]").checked==true)	nb_contacts_select ++; }
	if(nb_contacts_select==0)	{ alert("<?php echo $trad["import_alert2"]; ?>");  return false; }
}

////	Liste des contacts : Selectionne ligne
function select_ligne(contact_cpt)
{
	element("ligne_"+contact_cpt).style.backgroundColor = (element("contact_import["+contact_cpt+"]").checked)  ?  "<?php echo STYLE_TR_SELECT; ?>"  :  "<?php echo STYLE_TR_DESELECT; ?>";
}

////	Liste des contacts : On coche/décoche toute les lignes
function selection_import()
{
	for(var contact_cpt=0; contact_cpt<get_value("nb_contacts"); contact_cpt++)
	{
		contact = element("contact_import["+contact_cpt+"]");
		if(contact.checked==true)	{ contact.checked = false;  select_ligne(contact_cpt); }
		else						{ contact.checked = true;   select_ligne(contact_cpt); }
	}
}

////	Liste des contacts : On vérifie que le champ agora de la colonne (liste déroulante) n'est pas sélectionné 2 fois
function controle_champ(selection)
{
	for(var champ_cpt=0; champ_cpt < get_value("nb_champs"); champ_cpt++)
	{
		if(selection!=champ_cpt && get_value("champs_agora["+champ_cpt+"]")==get_value("champs_agora["+selection+"]") && get_value("champs_agora["+selection+"]")!="") {
			alert("<?php echo $trad["import_alert3"]; ?>");
			set_value("champs_agora["+selection+"]","");
			return false;
		}
	}
}
</script>


<?php
////	SELECTIONNE UN FICHIER CSV / SERVEUR LDAP
////
if(empty($_FILES["import_fichier"]) && empty($_POST["champs_contact"]))
{
	echo "<form action=\"".php_self()."\" method='post' style='text-align:center;' enctype='multipart/form-data' OnSubmit=\"return controle_formulaire_import();\">";
		echo "<select name='format_import' onChange=\"controle_type_import();\">";
			echo "<option value='csv'>CSV</option>";
			if(!empty($_SESSION["agora"]["ldap_server"]))	{echo "<option value='ldap'>LDAP</option>";}
		echo "</select>";
		echo "<input type='file' name='import_fichier' id='import_fichier' />";
		echo "<input type='hidden' name='type_export_import' value=\"".$_REQUEST["type_export_import"]."\" />";
		echo "<input type='hidden' name='id_dossier' value=\"".@$_REQUEST["id_dossier"]."\" /> &nbsp; &nbsp;";
		echo "<input type='submit' value=\"".$trad["valider"]."\" class='button' />";
		echo "<br><br><br><br>";
		echo "<div id='info_ldap' class='div_infos cacher'>".$trad["ldap_import_infos"]." ".(connexion_ldap()==false?"<br><br>".$trad["ldap_connexion_erreur"]:"")."</div>";
	echo "</form>";
}
////	AFFICHE LES CONTACTS DU FICHIER .CSV / SERVEUR LDAP
////
elseif(!empty($_REQUEST["format_import"]))
{
	////	IMPORT DE CSV
	////
	if($_REQUEST["format_import"]=="csv")
	{
		// LISTE DES CHAMPS (EN FONCTION DE LA PREMIERE LIGNE)  +  SEPARATEUR DES CHAMPS  +  NB DE CHAMPS
		$csv_entete = file($_FILES["import_fichier"]["tmp_name"]);
		$csv_entete = str_replace(array("\r","\n"),"",$csv_entete[0]);//utile pour certains formats..
		$cpt_tmp = 0;
		if(substr_count($csv_entete,";") > $cpt_tmp)	{ $separateur = ";";	$cpt_tmp = substr_count($csv_entete,";"); }
		if(substr_count($csv_entete,",") > $cpt_tmp)	{ $separateur = ",";	$cpt_tmp = substr_count($csv_entete,","); }
		if(substr_count($csv_entete,"	") > $cpt_tmp)	{ $separateur = "	";	$cpt_tmp = substr_count($csv_entete,"	"); }
		$entete_champs = array();
		foreach(explode($separateur,$csv_entete) as $valeur_tmp)	{ if(!empty($valeur_tmp)){$entete_champs[]=trim($valeur_tmp,"'\"");} }
		$nb_champs = count($entete_champs);
		// PLACE LE CONTENU DU CSV DANS UN TABLEAU
		$import_contacts = array();
		$handle = fopen($_FILES["import_fichier"]["tmp_name"],"r");
		while(($data = fgetcsv($handle,10000,$separateur))!==false)	{ $import_contacts[] = $data; }
	}
	////	IMPORT DE LDAP
	////
	else
	{
		$recup_login_password = ($_REQUEST["type_export_import"]=="users")  ?  true  :  false;
		$recherche_ldap = recherche_ldap($recup_login_password);
		$import_contacts = $recherche_ldap["users_ldap"];
		$entete_champs = $recherche_ldap["entete_champs"];
		$nb_champs = count($entete_champs);
	}

	////	INFOS SUR L'IMPORTATION
	if($_REQUEST["type_export_import"]=="contacts" && @$_REQUEST["id_dossier"]==1)	{$droit_acces_contact = $trad["import_infos_contact"];}
	elseif($_REQUEST["type_export_import"]=="users")								{$droit_acces_contact = $trad["import_infos_user"];}
	echo "<div class='div_infos' style='margin:30px;margin-top:0px;padding:5px;line-height:16px;'>".$trad["import_infos"]." &nbsp; ".@$droit_acces_contact."</div>";

	////	TABLEAU DES CONTACTS !
	echo "<form action=\"".php_self()."\" method='post' OnSubmit=\"return controle_contacts();\">";
		echo "<fieldset>";
			echo "<table style='font-size:9px;'>";
			////	ENTETE DU TABLEAU
			echo "<tr>";
			echo "<td class='lien' style='margin-left:5px;margin-right:10px;' onClick=\"selection_import();\" ".infobulle($trad["inverser_selection"])."><img src=\"".PATH_TPL."divers/tri_reload.png\" /></td>";
			// SELECTION DU CHAMP D'AGORA
			for($champ_cpt=0; $champ_cpt < $nb_champs; $champ_cpt++)
			{
				echo "<td><select name=\"champs_agora[".$champ_cpt."]\" style='width:120px;font-size:9px;' onChange=\"controle_champ(".$champ_cpt.");\"><option></option>";
				// Affiche chaque champ d'importation dans Agora (utilise le "csv_agora")
				foreach($formats_csv["csv_agora"]["champs"] as $champ_agora)
				{
					$selected = "";
					// sélectionne si la colonne courante correspond à un format de champ répertorié
					foreach($formats_csv as $format_csv){
						foreach($format_csv["champs"] as $champ_tmp)	{ if($entete_champs[$champ_cpt]==@$format_csv["champs"][$champ_agora])	{$selected="selected";} }
					}
					echo "<option value=\"".$champ_agora."\" ".$selected.">".$trad[$champ_agora]."</option>";
				}
				if($_REQUEST["type_export_import"]=="users"){
					echo "<option value='identifiant'>".$trad["identifiant"]."</option>";
					echo "<option value='pass'>".$trad["pass"]."</option>";
				}
				echo "</select></td>";
			}
			echo "</tr>";
			////	AFFICHE CHAQUE CONTACT
			if(count($import_contacts)==0)	{ echo "<div class='div_elem_aucun'>".$trad["aucun_resultat"]."</div>"; }
			else
			{
				foreach($import_contacts as $contact_cpt => $contact)
				{
					echo "<tr id='ligne_".$contact_cpt."'>";
						echo "<td><input type='checkbox' name=\"contact_import[".$contact_cpt."]\" value=\"".$contact_cpt."\" onClick=\"select_ligne('".$contact_cpt."');\" ".(($contact_cpt==0 && $_REQUEST["format_import"]=="csv")?"":"checked")." /><script>select_ligne('".$contact_cpt."');</script></td>";
						foreach($contact as $champ_cpt => $champ_tmp)
						{
							$champ_affiche = ($entete_champs[$champ_cpt]=="pass")  ?  preg_replace("/./","*",$champ_tmp)  :  convert_utf8($champ_tmp);
							echo "<td>".$champ_affiche."<input type='hidden' name=\"champs_contact[".$contact_cpt."][".$champ_cpt."]\" value=\"".convert_utf8($champ_tmp)."\" /></td>";
							if($champ_cpt==$nb_champs-1) break;// Ajoute le "break" car le "fgetcsv()" ci dessus ajoute un champ vide de trop..
						}
					echo "</tr>";
				}
			}
			echo "</table>";
		echo "</fieldset>";

		////	LISTE LES ESPACES POUR LES AFFECTATIONS  +  PROPOSE D'ENVOYER L'ID MOT DE PASSE PAR MAIL
		if($_REQUEST["type_export_import"]=="users")
		{
			echo "<fieldset style='width:300px;margin-left:auto;margin-right:auto;padding:20px;'>";
				echo $trad["ESPACES_description_module"]." :";
				foreach(espaces_affectes_user() as $espace_tmp){
					if(droit_acces_espace($espace_tmp["id_espace"],$_SESSION["user"])==2)	{echo "<div style='margin-left:20px;'><input type='checkbox' name='espaces_affectation[]' value='".$espace_tmp["id_espace"]."' ".($_SESSION["espace"]["id_espace"]==$espace_tmp["id_espace"]?"checked":"")." /> ".$espace_tmp["nom"]."</div>";}
				}
				echo "<hr>";
				echo "<span ".infobulle($trad["UTILISATEURS_envoi_coordonnees_infos2"])."><input type='checkbox' name='envoyer_id_password' value='1' /> ".$trad["UTILISATEURS_envoi_coordonnees"]."</span>";
			echo "</fieldset>";
		}

		////	INFOS SUR L'IMPORT CSV/LDAP + VALIDATION
		echo "<div style='text-align:center;margin-top:30px;'>";
			echo "<input type='hidden' name='nb_champs' value=\"".$nb_champs."\" />";
			echo "<input type='hidden' name='nb_contacts' value=\"".count($import_contacts)."\" />";
			echo "<input type='hidden' name='type_export_import' value=\"".$_REQUEST["type_export_import"]."\" />";
			echo "<input type='hidden' name='id_dossier' value=\"".@$_REQUEST["id_dossier"]."\" />";
			echo "<input type='submit' value=\"".$trad["valider"]."\" class='button_big' />";
		echo "</div>";
	echo "</form>";
}

require PATH_INC."footer.inc.php";
?>