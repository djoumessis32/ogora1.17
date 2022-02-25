<?php
////	INIT
define("ROOT_PATH","./");
define("IS_MAIN_PAGE",true);
define("IS_CONNEXION_PAGE",true);
define("CONTROLE_SESSION",false);
define("PLACEHOLDER",true);
require_once "./includes/global.inc.php";
require_once PATH_INC."header.inc.php";

////	REINITIALISATION DU MOT DE PASSE
////
if(isset($_GET["id_newpassword"]) && isset($_GET["id_utilisateur"]))
{
	$user_tmp = db_ligne("SELECT * FROM gt_utilisateur WHERE id_newpassword=".db_format($_GET["id_newpassword"])." AND id_utilisateur='".intval($_GET["id_utilisateur"])."'");
	////	"id_password" expiré
	if(count($user_tmp)==0)  { alert($trad["PASS_OUBLIE_id_newpassword_expire"]); }
	////	"id_password" valide
	else
	{
		// On demande un nouveau mot de passe  (récup les $_GET avec $_SERVER["QUERY_STRING"])
		if(!isset($_GET["password"]))	{ echo "<script type='text/javascript'>  $(document).ready(function(){  promptLightbox(\"".$trad["PASS_OUBLIE_prompt_changer_pass"]."\", \"redir('index.php?".$_SERVER["QUERY_STRING"]."&password='+get_value('promptInputText'));\");  });  </script>"; }
		// Enregistrement du nouveau mot de passe
		else{
			db_query("UPDATE gt_utilisateur SET pass='".sha1_pass($_GET["password"])."', id_newpassword=null WHERE id_utilisateur='".$user_tmp["id_utilisateur"]."'");
			$_COOKIE["AGORAP_LOG"] = $user_tmp["identifiant"];
			alert($trad["PASS_OUBLIE_password_reinitialise"]);
		}
	}
}

////	CONFIRMER INVITATION
////
if(isset($_GET["id_invitation"]) && isset($_GET["mail"]))
{
	$invitation_tmp = db_ligne("SELECT * FROM gt_invitation WHERE id_invitation='".intval($_GET["id_invitation"])."' AND mail=".db_format($_GET["mail"],"insert_ext"));
	////	"id_invitation" expiré
	if(count($invitation_tmp)==0)  { alert($trad["UTILISATEURS_id_invitation_expire"]); }
	////	"id_invitation" valide
	else
	{
		// Choix du mot de passe avant validation
		if(!isset($_GET["password"]))	{ echo "<script type='text/javascript'>  $(document).ready(function(){  promptLightbox(\"".$trad["UTILISATEURS_invitation_confirmer_password"]."\", \"redir('index.php?".$_SERVER["QUERY_STRING"]."&password='+get_value('promptInputText'));\", \"text\", \"".$invitation_tmp["pass"]."\");  });  </script>"; }
		// Enregistrement du nouvel utilisateur  (mail comme identifiant + utilise "addslashes" car les données ne sont pas "GPC")
		elseif(nb_users_depasse(false,false)!=true)
		{
			$id_user_tmp = creer_utilisateur(addslashes($invitation_tmp["mail"]), addslashes($_GET["password"]), addslashes(@$invitation_tmp["nom"]), addslashes(@$invitation_tmp["prenom"]), addslashes($invitation_tmp["mail"]), addslashes($invitation_tmp["id_espace"]));
			if($id_user_tmp > 0){
				db_query("DELETE FROM gt_invitation WHERE id_invitation=".db_format($_GET["id_invitation"]));
				$_COOKIE["AGORAP_LOG"] = user_infos($id_user_tmp,"identifiant"); //Préremplis le champ 'login'
				alert($trad["UTILISATEURS_invitation_valide"]);
			}
		}
	}
}
?>


<style>
.block_connexion	{ <?php echo STYLE_BLOCK.STYLE_FONT_BOLD; ?> width:60%; min-width:480px; max-width:650px; margin:auto; text-align:center; display:none; margin-bottom:80px; }
.block_icone		{ position:absolute; margin-top:-20px; margin-left:-20px; }
</style>

<script type="text/javascript">
////	AFFICHAGE LA PAGE DE CONNEXION AVEC "FADE"  +  PREPARATION DE L'INPUT "login"  +  INFOS SUR LE NAVIGATEUR
////
$(document).ready(function(){
	// Infos sur le navigateur dans le formulaire de connexion
	element("infos_navigateur").innerHTML = "<input type='hidden' name='resolution_width' value='"+$(document).width()+"' /><input type='hidden' name='resolution_height' value='"+$(document).height()+"' /><input type='hidden' name='navigateur' value='"+navigateur()+"' />";
	// navigateur obsolète ?
	if(navigateur()=="ie" && version_ie()<8)	alert("<?php echo $trad["version_ie"]; ?>");
	// apparition en "fade" du formulaire
	$("#div_connexion").fadeIn(800);
	$("#div_espaces_publics").fadeIn(800);
	// Mets le focus sur l'input du login (sauf sur IE..). Et "autofocus" de HTML5 n'est pas encore fiable..
    if(navigateur()!="ie")	element('login').focus();
});

////	ACCES INVITE  (accès direct ou avec mot de passe)
////
function Espace_Public(id_espace, mot_de_passe)
{
	// Config du navigateur
	url_config_navigateur = "&resolution_width="+$(document).width()+"&resolution_height="+$(document).height()+"&navigateur="+navigateur();
	// Accès direct sans password  /  Saisie du mot de passe  /  Controle Ajax du mot de passe
	if(mot_de_passe=="0")			{ redir("index.php?id_espace_acces="+id_espace+url_config_navigateur); }
	else if(mot_de_passe=="1")		{ promptLightbox("<?php echo $trad["pass"]; ?>", "Espace_Public('"+id_espace+"',get_value('promptInputText'));", "password"); }
	else if(mot_de_passe.length > 2){
		requete_ajax("divers/espace_password_verif.php?password="+urlencode(mot_de_passe)+"&id_espace="+id_espace);
		if(trouver("oui",retour_ajax))	{ redir('index.php?id_espace_acces='+id_espace+'&password='+mot_de_passe+url_config_navigateur); }
		else							{ alert("<?php echo $trad["espace_password_erreur"]; ?>"); return false; }
	}
}
</script>


<table id="div_entete" style="padding:5px;width:100%;height:25px;background-image:url('<?php echo PATH_TPL."divers/".(@$_SESSION["agora"]["skin"]=="blanc"?"header_droite.png":"header_droite_noir.png"); ?>');"><tr>
	<td style="text-align:left;font-size:16px;font-weight:bold;"><?php echo @$_SESSION["agora"]["nom"]; ?></td>
	<td style="text-align:right;"><?php if(@$_SESSION["agora"]["description"]!="") echo $_SESSION["agora"]["description"]; ?></td>
</tr></table>
<hr style="visibility:hidden;margin-top:150px;" />

<form action="<?php echo php_self(); ?>" method="post" OnSubmit="return controle_connexion('<?php echo addslashes($trad["specifier_login_password"]) ?>','<?php echo addslashes($trad["identifiant_ou_email"]); ?>','<?php echo addslashes($trad["pass"]); ?>');" id="div_connexion" class="block_connexion">
	<div class="block_icone"><img src="<?php echo PATH_TPL; ?>divers/connexion.png" /></div>
	<div style="text-align:center;padding-top:50px;padding-bottom:30px;">
		<input type="text" name="login" value="<?php echo @$_COOKIE["AGORAP_LOG"]; ?>" placeholder="<?php echo $trad["identifiant_ou_email"]; ?>" style="width:180px;margin-right:10px;" />
		<input type="password" name="password" placeholder="<?php echo $trad["pass"]; ?>" style="width:100px;margin-right:15px;" />
		<input type="submit" value="<?php echo $trad["connexion"]; ?>" class="button" style="width:100px;" />
		<div id="infos_navigateur" style="diplay:none;"></div>
	</div>
	<div style="text-align:right;padding:5px;">
		<?php
		////	S'INSCRIRE
		if(db_valeur("select count(*) from gt_espace where inscription_users='1'")>0){
			echo "<a class='fancybox.iframe utilisateur_inscription' style='float:left' href='./module_utilisateurs/utilisateur_inscription.php' ".infobulle($trad["inscription_users_info"])."><img src=\"".PATH_TPL."divers/check.png\" style='height:16px;' />&nbsp; ".$trad["inscription_users"]." !</a><script>$('.utilisateur_inscription').fancybox({width:500});</script>";
		}
		////	PASSWORD OUBLIE
		echo "<a class='fancybox.iframe password_oublie' href='".PATH_DIVERS."password_oublie.php' ".infobulle($trad["password_oublie_info"]).">".$trad["password_oublie"]."</a><script>$('.password_oublie').fancybox({width:480});</script>";
		if(@$_GET["msg_alerte"]=="identification"){
			echo "<script>  $('.password_oublie').css('color','#d00');  $('.password_oublie').effect('pulsate',{times:5},5000); </script>";
		}
		////	RESTER CONNECTE
		echo "<span style='margin-left:15px;' ".infobulle($trad["connexion_auto_info"],220)." >";
			echo "<span class='lien_select2' id='txt_connexion_auto' onClick=\"checkbox_text(this,'lien_select2');\">".$trad["connexion_auto"]."</span>";
			echo "<input type='checkbox' name='connexion_auto' value='1' id='box_connexion_auto' onClick=\"checkbox_text(this,'lien_select2');\" checked=checked />";
		echo "</span>";
		?>
	</div>
</form>


<?php
////	ESPACES PUBLICS
////
$infos_user["id_utilisateur"] = 0;
$liste_espaces = espaces_affectes_user($infos_user);
if(count($liste_espaces)>0)
{
	echo "<div id='div_espaces_publics' class='block_connexion pas_selection'>";
		echo "<div class='block_icone'><img src=\"".PATH_TPL."divers/connexion_public.png\" /></div>";
		echo "<div style='display:table;margin:auto;'>";
			echo "<div style='display:table-cell;padding:15px;font-size:14px;'>".$trad["acces_invite"]."</div>";
			echo "<div style='display:table-cell;padding:15px;text-align:left;'>";
			foreach($liste_espaces as $infos_espace){
				echo "<div onClick=\"Espace_Public('".$infos_espace["id_espace"]."','".($infos_espace["password"]!=""?"1":"0")."');\" class='lien' style='font-size:14px;padding:5px;'><img src=\"".PATH_TPL."divers/fleche_droite.png\" />&nbsp; ".$infos_espace["nom"]."</div>";
			}
			echo "</div>";
		echo "</div>";
	echo "</div>";
}


////	FOOTER
require PATH_INC."footer.inc.php";
?>