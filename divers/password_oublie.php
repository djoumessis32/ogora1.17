<?php
////	INIT
define("CONTROLE_SESSION",false);
require_once "../includes/global.inc.php";
require_once PATH_INC."header.inc.php";


////	ENVOI DU MAIL DE REINITIALISATION DU MOT DE PASSE (validé dans "global.inc.php")
////
if(isset($_POST["mail"]))
{
	$infos_user = db_ligne("SELECT * FROM gt_utilisateur WHERE mail=".db_format($_POST["mail"])." AND mail!=''");
	if(count($infos_user)==0)	{ alert($trad["PASS_OUBLIE_mail_inexistant"]); }
	else
	{
		// Init
		$id_newpassword = mt_rand(1000000000,9999999999);
		$adresse_confirmation = $_SESSION["agora"]["adresse_web"]."?id_utilisateur=".$infos_user["id_utilisateur"]."&id_newpassword=".$id_newpassword;
		// Envoi du mail pour vérificaton
		$contenu_mail  = $trad["PASS_OUBLIE_mail_contenu"]." :&nbsp; <b>".$infos_user["identifiant"]."</b><br><br>";
		$contenu_mail .= "<a href=\"".$adresse_confirmation."\" target=\"_blank\"><b>".$trad["PASS_OUBLIE_mail_contenu_bis"]."</b></a>";
		$envoi_mail = envoi_mail($_POST["mail"], $trad["PASS_OUBLIE_mail_objet"], $contenu_mail);
		// On ajoute l'invitation temporaire & ferme popup
		if($envoi_mail==true)	{db_query("UPDATE gt_utilisateur SET id_newpassword='".$id_newpassword."' WHERE id_utilisateur='".$infos_user["id_utilisateur"]."'");}
		close_lightbox();
	}
}
?>


<script type="text/javascript">
////    On contrôle du mail
function controle_formulaire()
{
	if(controle_mail(get_value("mail"))==false)  { alert("<?php echo $trad["mail_pas_valide"]; ?>"); return false; }
}
</script>

<form action="<?php echo php_self(); ?>" method="post" style="margin-top:20px;text-align:center;" OnSubmit="return controle_formulaire();">
	<div><b><?php echo $trad["PASS_OUBLIE_preciser_mail"]; ?></b></div><br>
	<input type="text" name="mail" style="width:200px" />
	<input type="submit" value="<?php echo $trad["envoyer"]; ?>" class="button" />
</form>


<?php require PATH_INC."footer.inc.php"; ?>