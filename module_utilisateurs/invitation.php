<?php
////	INIT
define("NO_MODULE_CONTROL",true);
define("PLACEHOLDER",true);
require "commun.inc.php";
require_once PATH_INC."header.inc.php";
if($_SESSION["user"]["envoi_invitation"]!="1")	{exit();}
nb_users_depasse();


////	ENVOI D'INVITATION PAR MAIL
////
if(isset($_POST["envoi_invitation"]))
{
	// Init
	$id_invitation = mt_rand(1000000000,9999999999);
	$password = mt_rand(10000,99999);
	$adresse_confirmation = $_SESSION["agora"]["adresse_web"]."?id_invitation=".$id_invitation."&mail=".urlencode($_POST["mail"]);
	$expediteur = $_SESSION["user"]["nom"]." ".$_SESSION["user"]["prenom"];
	// Envoi du mail d'invitation
	$objet_mail = $trad["UTILISATEURS_objet_mail_invitation"]." ".$expediteur; // Invitation de Jean DUPOND
	$contenu_mail  = "<b>".$expediteur." ".$trad["UTILISATEURS_admin_invite_espace"]." ".$_SESSION["espace"]["nom"]." :</b><br><br>"; // Jean DUPOND vous invite à rejoindre l'espace Mon Espace :
	$contenu_mail .= $trad["identifiant_connexion"]." : &nbsp; ".$_POST["mail"]."<br>";
	$contenu_mail .= $trad["pass"]." : &nbsp; ".$password."<br><br>";
	if($_POST["message"]!="")	{$contenu_mail .= $_POST["message"]."<br><br>";}
	$contenu_mail .= "<a href=\"".$adresse_confirmation."\" target=\"_blank\"><u><b>".$trad["UTILISATEURS_confirmer_invitation"]."</u></b></a>"; // Confirmer l'invitation ?
	$envoi_mail = envoi_mail($_POST["mail"], $objet_mail, magicquotes_strip($contenu_mail), array("header_footer"=>false));
	// On ajoute l'invitation temporaire
	//if($envoi_mail==true)	
		db_query("INSERT INTO gt_invitation SET id_invitation=".db_format($id_invitation).", id_espace='".$_SESSION["espace"]["id_espace"]."', nom=".db_format($_POST["nom"]).", prenom=".db_format($_POST["prenom"]).", mail=".db_format($_POST["mail"]).", pass=".db_format($password).", date_crea=".db_date_now().", id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."'");
}

////	SUPPRESSION D'UNE INVITATION
if(isset($_GET["suppr_invitation"]))
{
	$infos_invit = db_valeur("SELECT count(*) FROM gt_invitation WHERE id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."' AND id_invitation='".$_GET["suppr_invitation"]."'");
	if($infos_invit > 0)	{db_query("DELETE FROM gt_invitation WHERE id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."' AND id_invitation=".db_format($_GET["suppr_invitation"]));}
}

////	SUPRESSION DES INVITATIONS DE PLUS D'UN AN
db_query("DELETE FROM gt_invitation WHERE UNIX_TIMESTAMP(date_crea) < '".(time()-(86400*365))."'");
?>


<script type="text/javascript">
////	Redimensionne
resizePopupLightbox(500,350);

////    On contrôle les champs
function controle_formulaire()
{
	// Il doit y avoir un texte
	if (get_value("nom")=="" || get_value("prenom")=="" || get_value("mail")=="")		{ alert("<?php echo $trad["remplir_tous_champs"]; ?>"); return false; }
	// Vérification du mail
	if (controle_mail(get_value("mail"))==false)	{ alert("<?php echo $trad["mail_pas_valide"]; ?>");	return false; }
	// controle existance du mail
	requete_ajax("identifiant_verif.php?mail="+urlencode(get_value("mail")));
	if(trouver("oui",retour_ajax))	{ alert("<?php echo $trad["UTILISATEURS_mail_deja_present"]; ?>"); return false; }
}
</script>
<style type="text/css">
body		{ background-image:url('<?php echo PATH_TPL; ?>module_utilisateurs/fond_popup.png'); font-weight:bold; }
.input_text	{ width:300px }
</style>


<form action="<?php echo php_self(); ?>" method="post" OnSubmit="return controle_formulaire();" style="padding:5px;">
	<fieldset class='fieldset_titre'><?php echo $trad["UTILISATEURS_envoi_invitation"]; ?></fieldset>
	<br />
	<input type="text" name="nom" class="input_text" placeholder="<?php echo $trad["nom"]; ?>" /><br><br>
	<input type="text" name="prenom" class="input_text" placeholder="<?php echo $trad["prenom"]; ?>" /><br><br>
	<input type="text" name="mail" class="input_text" placeholder="<?php echo $trad["mail"]; ?>" /><br><br>
	<textarea name="message" class="input_text" style="height:35px;" placeholder="<?php echo $trad["commentaire"]; ?>"><?php echo @$_POST["message"]; ?></textarea><br><br><br>
	<div style="text-align:right;">
		<input type="hidden" name="envoi_invitation" value="1" />
		<input type="submit" value="<?php echo $trad["envoyer"]; ?>" class="button_big" />
	</div>
</form>


<?php
////	INVITATIONS EN ATTENTES
$liste_invitations = db_tableau("SELECT * FROM gt_invitation WHERE id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."'");
if(count($liste_invitations)>0)
{
	echo "<span class='lien_select2' onClick=\"afficher('liste_invitations');resizePopupLightbox();\"><img src=\"".PATH_TPL."divers/envoi_mail.png\" /> &nbsp; ".count($liste_invitations)." ".$trad["UTILISATEURS_invitation_a_confirmer"]."</span>";
	echo "<ul id='liste_invitations' class='cacher'>";
	foreach($liste_invitations as $invit){
		echo"<li style='margin-bottom:5px;'>".$invit["nom"]." ".$invit["prenom"]." - ".$invit["mail"]." - ".info_espace($invit["id_espace"],"nom")." - ".temps($invit["date_crea"])." &nbsp; ".icone_suppr("invitation.php?suppr_invitation=".$invit["id_invitation"])."</li>";
	}
	echo "</ul>";
}

////	FOOTER
require PATH_INC."footer.inc.php";
?>