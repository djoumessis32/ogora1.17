<?php
////	INIT
define("NO_MODULE_CONTROL",true);
require "commun.inc.php";
require_once PATH_INC."header.inc.php";
// INFOS + DROIT ACCES + LOGS
$espace_tmp = (isset($_GET["id_espace"]))  ?  info_espace($_GET["id_espace"])  :  array("id_espace"=>"0");
if($_SESSION["espace"]["droit_acces"]<2)	exit;


////	VALIDATION DU FORMULAIRE
////
if(isset($_POST["id_espace"]))
{
	////	MODIF / AJOUT
	$public_password = (!empty($_POST["espace_public"]) && !empty($_POST["password"]))  ?  $_POST["password"]  :  "";
	$corps_sql = "nom=".db_format($_POST["nom"]).", description=".db_format($_POST["description"]).", public=".db_format(@$_POST["espace_public"]).", password=".db_format($public_password).", inscription_users=".db_format(@$_POST["inscription_users"]).", invitations_users=".db_format(@$_POST["invitations_users"]).", fond_ecran=".db_format(@$_POST["fond_ecran"]);
	if($_POST["id_espace"]>0)	{ db_query("UPDATE gt_espace SET ".$corps_sql." WHERE id_espace=".db_format($_POST["id_espace"])); }
	else						{ db_query("INSERT INTO gt_espace SET ".$corps_sql);   $_POST["id_espace"] = db_last_id(); }

	////	AFFECTATION DES UTILISATEURS
	if($_SESSION["user"]["admin_general"]==1)
	{
		// Réinitialisation
		db_query("DELETE FROM gt_jointure_espace_utilisateur WHERE id_espace=".db_format($_POST["id_espace"]));
		// Affectation à tous les utilisateurs
		if(isset($_POST["tous_box_1"]))		{ db_query("INSERT INTO gt_jointure_espace_utilisateur SET id_espace=".db_format($_POST["id_espace"]).", tous_utilisateurs=1, droit=1"); }
		// Chaque utilisateur
		if(isset($_POST["user"])) {
			foreach($_POST["user"] as $id_user => $droit)	{ db_query("INSERT INTO gt_jointure_espace_utilisateur SET id_espace=".db_format($_POST["id_espace"]).", id_utilisateur=".db_format($id_user).", droit=".db_format($droit)); }
		}
	}

	////	MODULES DE L'ESPACE
	$classement_module = 1;
	db_query("DELETE FROM gt_jointure_espace_module WHERE id_espace=".db_format($_POST["id_espace"]));
	foreach($_POST["liste_modules"] as $nom_module)
	{
		$options_module = "";
		if(isset($_POST["option_modules"][$nom_module])){
			foreach($_POST["option_modules"][$nom_module] as $option_module)  { $options_module .= $option_module."@@"; }
		}
		db_query("INSERT INTO gt_jointure_espace_module SET id_espace=".db_format($_POST["id_espace"]).", nom_module=".db_format($nom_module).", classement='".$classement_module."', options=".db_format(trim($options_module),"@@"));
		$classement_module ++;
	}
	
	////	CREATION D'UN AGENDA D'ESPACE
	if(@$_POST["agenda_espace"]==1)
	{
		db_query("INSERT INTO gt_agenda SET id_utilisateur=".db_format($_SESSION["user"]["id_utilisateur"]).", type='ressource', titre=".db_format($_POST["nom"]).", evt_affichage_couleur='background'");
		db_query("INSERT INTO gt_jointure_objet SET type_objet='agenda', id_objet='".db_last_id()."', id_espace='".$_POST["id_espace"]."', target='tous', droit='1'");
		if(db_valeur("SELECT count(*) FROM gt_jointure_espace_module WHERE id_espace='".$_POST["id_espace"]."' AND nom_module='agenda'")==0){
			$classement = db_valeur("SELECT max(classement) FROM gt_jointure_espace_module WHERE id_espace='".$_POST["id_espace"]."'");
			db_query("INSERT INTO gt_jointure_espace_module SET id_espace='".$_POST["id_espace"]."', nom_module='agenda', classement='".$classement."'");
		}
	}

	////	LOGS  +  FERME
	add_logs("modif", "", "", $trad["ESPACES_parametrage"]." : ".$_POST["nom"]);
	////	RELOAD L'ESPACE (+ REDIR VERS "/module_espaces/" SI BESOIN)  OU  RECHARGE JUSTE LA PAGE
	$page_redir = ($_POST["id_espace"]==$_SESSION["espace"]["id_espace"])  ?  "index.php?id_espace_acces=".$_POST["id_espace"]  :  "";
	if($page_redir!="" && preg_match("/".MODULE_PATH."/i",$_POST["page_origine"]))	{$page_redir .= "&redir_module_path=".MODULE_PATH;}
	reload_close($page_redir);
}
?>


<style type="text/css">
body			{ font-weight:bold; }
table			{ width:100%; margin-bottom:5px; font-weight:bold; }
.module_line	{ display:table; width:100%; padding:4px; }
.module_cell	{ display:table-cell; }
.module_cell2	{ display:table-cell; width:30px; }
#sortable_modules				{ list-style-type: none; margin: 0; padding: 0; width: 100%; }
#sortable_modules li			{ margin: 2px; min-height: 25px; }
#sortable_modules li.highlight	{ border:1px dashed #aaa; height:50px; } /*module "fantome" durant le déplacement/tri*/
</style>


<script type="text/javascript">
////	Redimensionne
resizePopupLightbox(600,700);

////    CONTROLE DE SAISIE DU FORMULAIRE
////
function controle_formulaire()
{
	// Vérif des modules cochés
	var nb_modules = 0;
	tab_modules = document.getElementsByName("liste_modules[]");
	for(i=0; i<tab_modules.length; i++)		{ if(tab_modules[i].checked==true)  nb_modules++; }
	if(nb_modules==0)			{ alert("<?php echo $trad["ESPACES_selectionner_module"]; ?>"); return false; }
	if(get_value("nom")=="")	{ alert("<?php echo $trad["specifier_nom"]; ?>");  return false; }
}


////	SELECTION DE "ESPACE PUBLIC"
////
function selection_espace_public(id_elem)
{
	// Sélectionne / désélectionne
	checkbox_text(id_elem,'lien_select2');
	// Affiche le password?
	afficher('password_espace_public',is_checked('box_espace_public'));
	if(is_checked('txt_espace_public'))		element('password').focus();
}

////	AFFICHER OPTION(S) DU MODULE
////
function afficher_options_module(id_tmp)
{
	if(element('box_'+id_tmp).checked==true)	afficher_dynamic('options_'+id_tmp,true);
	else										afficher_dynamic('options_'+id_tmp,false);
}
</script>


<form action="<?php echo php_self(); ?>" method="post" OnSubmit="return controle_formulaire();" style="padding:5px;">

	<?php
	////	PARAMETRAGE GENERAL
	////
	echo "<fieldset>";
		////	NOM & DESCRIPTION
		echo "<div>";
			echo $trad["nom"]." <input type='text' name='nom' value=\"".@$espace_tmp["nom"]."\" style='width:300px;' /> &nbsp; &nbsp; ";
			echo "<span onClick=\"afficher_dynamic('block_description');\" class='lien'>".$trad["description"]." <img src=\"".PATH_TPL."divers/derouler.png\" /></span>";
			echo "<div id='block_description'><br><input type='text' name='description' value=\"".@$espace_tmp["description"]."\" style='width:98%;' /></div>";
			if(@$espace_tmp["description"]=="")	echo "<script type='text/javascript'>  afficher('block_description',false);  </script>";
		echo "</div>";

		////	ESPACE PUBLIC
		echo "<div style='margin-top:20px;' ".infobulle($trad["ESPACES_public_infos"]).">";
			echo "<input type='checkbox' name='espace_public' value='1' id='box_espace_public' onClick=\"selection_espace_public(this.id);\"  ".(@$espace_tmp["public"]>0?"checked":"")." /> ";
			echo "<span id='txt_espace_public' onClick=\"selection_espace_public(this.id);\" class='".(@$espace_tmp["public"]>0?"lien_select2":"lien")."'>".$trad["ESPACES_espace_public"]."</span>&nbsp; <img src=\"".PATH_TPL."divers/planete.png\" />";
			echo "<span id='password_espace_public' style='margin-left:10px;".(@$espace_tmp["public"]==0?"display:none;":"")."'><img src=\"".PATH_TPL."divers/fleche_droite.png\" />&nbsp;<i>".$trad["pass"]."</i> <input type='text' name='password' value=\"".@$espace_tmp["password"]."\" style='width:60px;' /></span>";
		echo "</div>";
		
		////	INSCRIPTION DES INVITES
		echo "<div style='margin-top:10px;' ".infobulle($trad["inscription_users_option_espace_info"]).">";
			echo "<input type='checkbox' name='inscription_users' value='1' id='box_inscription_users' onClick=\"checkbox_text(this,'lien_select2');\" ".(@$espace_tmp["inscription_users"]>0?"checked":"")." />";
			echo "<span id='txt_inscription_users' class='".(@$espace_tmp["inscription_users"]>0?"lien_select2":"lien")." pas_selection' onClick=\"checkbox_text(this,'lien_select2');\">".$trad["inscription_users_option_espace"]."&nbsp; <img src=\"".PATH_TPL."divers/crayon.png\" /></span>";
		echo "</div>";
		
		////	INVITATION PAR MAIL
		echo "<div style='margin-top:10px;' ".infobulle($trad["ESPACES_invitations_users_infos"]).">";
			echo "<input type='checkbox' name='invitations_users' value='1' id='box_invitations_users' onClick=\"checkbox_text(this,'lien_select2');\" ".(@$espace_tmp["invitations_users"]>0?"checked":"")." />";
			echo "<span id='txt_invitations_users' class='".(@$espace_tmp["invitations_users"]>0?"lien_select2":"lien")." pas_selection' onClick=\"checkbox_text(this,'lien_select2');\">".$trad["ESPACES_invitations_users"]."&nbsp; <img src=\"".PATH_TPL."divers/envoi_mail.png\" style='height:20px;' /></span>";
		echo "</div>";
		
		////	FONDS D'ECRAN
		echo "<hr style='margin-top:15px;opacity:0.2;' /><div style='margin-top:15px;display:table;'>";
			echo "<div style='display:table-cell;width:80px;vertical-align:top;'>".$trad["fond_ecran"]."</div>";
			echo "<div style='display:table-cell;'>".menu_fonds_ecran(@$espace_tmp["fond_ecran"],0)."</div>";
		echo "</div>";
	echo "</fieldset>";


	////	AFFECTATION DES UTILISATEURS (ADMIN GENERAL)
	////
	if($_SESSION["user"]["admin_general"]==1)
	{
		echo "<fieldset style='margin-top:40px'>";
			echo "<legend>".$trad["ESPACES_gestion_acces"]."</legend>";
			echo "<div class='div_liste_users'><table class='pas_selection' style='width:100%;'>";
				////	ENTETE
				echo "<tr>";
					echo "<td>&nbsp;</td>";
					echo "<td style='width:100px;' title=\"".$trad["ESPACES_utilisation_info"]."\"><img src=\"".PATH_TPL."module_utilisateurs/acces_utilisateur.png\" /> ".$trad["ESPACES_utilisation"]."</td>";
					echo "<td style='width:120px;' title=\"".$trad["ESPACES_administration_info"]."\"><img src=\"".PATH_TPL."module_utilisateurs/acces_admin_espace.png\" /> ".$trad["ESPACES_administration"]."</td>";
				echo "</tr>";
				////	TOUS LES UTILISATEURS
				
				if(db_valeur("SELECT count(*) FROM gt_jointure_espace_utilisateur WHERE id_espace='".$espace_tmp["id_espace"]."' AND tous_utilisateurs=1 AND droit=1")>0)	{ $class_txt = "txt_acces_user";  $checked1 = "checked"; }
				else																																						{ $class_txt = "lien";  $checked1 = ""; }
				echo "<tr class='ligne_survol'>";
					echo "<td class=\"".$class_txt."\" name='tous_txt' onClick=\"affect_users_espaces(this,'tous');\"><i>".majuscule($trad["ESPACES_tous_utilisateurs"])."</i></td>";
					echo "<td><input type='checkbox' name='tous_box_1' value='1' onClick=\"affect_users_espaces(this,'tous');\" title=\"".$trad["ESPACES_utilisation_info"]."\" ".$checked1." /></td>";
					echo "<td>&nbsp;</td>";
				echo "</tr>";
				////	CHAQUE UTILISATEUR
				$users_site = db_tableau("SELECT * FROM gt_utilisateur ORDER BY ".$_SESSION["agora"]["tri_personnes"]);
				foreach($users_site as $compteur => $user_infos)
				{
					$class_txt = "lien";
					$checked1 = $checked2 = "";
					$id_tmp = "user".$compteur;
					$sql_tmp = "SELECT count(*) FROM gt_jointure_espace_utilisateur WHERE id_espace='".$espace_tmp["id_espace"]."' AND id_utilisateur='".$user_infos["id_utilisateur"]."'";
					if(db_valeur($sql_tmp." AND droit=1")>0)	{ $checked1  = "checked";  $class_txt = "txt_acces_user"; }
					if(db_valeur($sql_tmp." AND droit=2")>0)	{ $checked2  = "checked";  $class_txt = "txt_acces_admin"; }
					echo "<tr class='ligne_survol'>";
						echo "<td class='".$class_txt." pas_selection' id='".$id_tmp."_txt' onClick=\"affect_users_espaces(this,'".$id_tmp."');\">".$user_infos["nom"]." ".$user_infos["prenom"]."</td>";
						echo "<td><input type='checkbox' name='user[".$user_infos["id_utilisateur"]."]' value='1' id='".$id_tmp."_box_1' onClick=\"affect_users_espaces(this,'".$id_tmp."');\" title=\"".$trad["ESPACES_utilisation_info"]."\" ".$checked1." /></td>";
						echo "<td><input type='checkbox' name='user[".$user_infos["id_utilisateur"]."]' value='2' id='".$id_tmp."_box_2' onClick=\"affect_users_espaces(this,'".$id_tmp."');\" title=\"".$trad["ESPACES_administration_info"]."\" ".$checked2." /></td>";
					echo "</tr>";
				}
			echo "</table></div>";
		echo "</fieldset>";
	}


	////	LISTE DES MODULES
	////
	$modules_espace = modules_espace($espace_tmp["id_espace"],false);
	$autres_modules = db_tableau("SELECT * FROM gt_module WHERE nom NOT IN (SELECT DISTINCT nom_module FROM gt_jointure_espace_module WHERE id_espace='".$espace_tmp["id_espace"]."')");
	$modules_site = array_merge($modules_espace, $autres_modules);
	echo "<fieldset style='margin-top:40px'>";
		echo "<legend>".$trad["ESPACES_modules_espace"]."</legend>";
		echo "<ul id='sortable_modules'>";
		foreach($modules_site as $module_cle => $module_tmp)
		{
			////	Init
			$compteur = $module_cle+1;
			$id_tmp = "module".$compteur;
			echo "<li class='ui-state-default'>";
			////	Module affecté à l'espace ?
			$module_check = db_valeur("SELECT count(*) FROM gt_jointure_espace_module WHERE id_espace='".$espace_tmp["id_espace"]."' AND nom_module='".$module_tmp["nom"]."'");
			echo "<div class='module_line ligne_survol' title=\"".$trad["ESPACES_modules_classement"]."\" onClick=\"afficher_options_module('".$id_tmp."');\">";
				echo "<div class='module_cell2' style='cursor:move;'><img src='".PATH_TPL."divers/move.png' /></div>";
				echo "<div class='module_cell ".($module_check>0?"lien_select2":"lien")." pas_selection' id='txt_".$id_tmp."' onClick=\"checkbox_text(this,'lien_select2');\">".$trad[strtoupper($module_tmp["nom"])."_nom_module"]."</div>";
				echo "<div class='module_cell2'><input type='checkbox' name='liste_modules[]' value=\"".$module_tmp["nom"]."\" id='box_".$id_tmp."'  onClick=\"checkbox_text(this,'lien_select2');\" ".($module_check>0?"checked":"")." /></div>";
			echo "</div>";
			////	Options du module (s'il y en a)
			require_once ROOT_PATH.$module_tmp["module_path"]."/commun.inc.php";
			if(isset($config["module_espace_options"][$module_tmp["nom"]]))
			{
				echo "<div id='options_".$id_tmp."'  ".($module_check==0?"style='display:none;'":"lien").">";
				foreach($config["module_espace_options"][$module_tmp["nom"]] as $module_option)
				{
					$module_option_check = db_valeur("SELECT count(*) FROM gt_jointure_espace_module WHERE id_espace='".$espace_tmp["id_espace"]."' AND nom_module='".$module_tmp["nom"]."' AND options LIKE '%".$module_option."%'");
					echo "<div class='module_line' style='padding-top:0px;'>";
						echo "<div class='module_cell2'>&nbsp;</div>";
						echo "<div class='module_cell ".($module_option_check>0?"lien_select2":"lien")." pas_selection' id='txt_".$module_option."' onClick=\"checkbox_text(this,'lien_select2');\"> <img src=\"".PATH_TPL."divers/dependance_dossier.png\" style=\"opacity:0.5;filter:alpha(opacity=50);height:15px;\" /> ".$trad[strtoupper($module_tmp["nom"])."_".$module_option]."</div>";
						echo "<div class='module_cell2'><input type='checkbox' name=\"option_modules[".$module_tmp["nom"]."][]\" value=\"".$module_option."\" id='box_".$module_option."' onClick=\"checkbox_text(this,'lien_select2');\" ".($module_option_check>0?"checked":"")." /></div>";
					echo "</div>";
				}
				echo "</div>";
			}
			echo "</li>";
		}
		echo "</ul>";
	echo "</fieldset>";
	
	
	////	CREATION D'UN AGENDA DE RESSOURCE AU NOM DE L'ESPACE (nouvel espace)
	////
	if($espace_tmp["id_espace"]<1)
	{
		echo "<div style='float:left;margin-top:30px;' ".infobulle($trad["ESPACES_creer_agenda_espace_info"]).">";
			echo "<input type='checkbox' name='agenda_espace' value='1' id='box_agenda_espace' onClick=\"checkbox_text(this);\" />";
			echo "<span id='txt_agenda_espace' class='lien pas_selection' onClick=\"checkbox_text(this);\">".$trad["ESPACES_creer_agenda_espace"]."&nbsp; <img src=\"".PATH_TPL."module_agenda/plugin.png\" /></span>";
		echo "</div>";
	}
	?>


	<div style="margin-top:30px;text-align:right;">
		<input type="hidden" name="id_espace" value="<?php echo $espace_tmp["id_espace"]; ?>" />
		<input type="hidden" name="page_origine" value="<?php echo @$_SERVER["HTTP_REFERER"]; ?>" />
		<input type="submit" value="<?php echo $trad["valider"]; ?>" class="button_big" />
	</div>

</form>


<script type="text/javascript">
////	INITIALISE LE TRI DES MODULES, A METTRE A LA FIN POUR PAS INTERFERER AVEC LE LIGHTBOX!  (Et affiche un module "fantome" durant le déplacement  &  désactive la sélection du bloc par le navigateur)
////
$(window).load(function(){
	$("#sortable_modules").sortable({placeholder:'highlight'}).disableSelection();
});
</script>


<?php require PATH_INC."footer.inc.php"; ?>