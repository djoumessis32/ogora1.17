<?php
////	INIT
define("CONTROLE_SESSION",false);
define("PLACEHOLDER",true);
require_once "../includes/global.inc.php";
require_once PATH_INC."header.inc.php";
nb_users_depasse();

////	INSCRIPTION DE L'UTILISATEUR
if(isset($_POST["envoi_inscription"]))
{
	db_query("INSERT INTO gt_utilisateur_inscription SET id_espace=".db_format($_POST["id_espace"],'insert_ext').", nom=".db_format($_POST["nom"],'insert_ext').", prenom=".db_format($_POST["prenom"],'insert_ext').", mail=".db_format($_POST["mail"],'insert_ext').", pass=".db_format($_POST["pass"],'insert_ext').", message=".db_format($_POST["message"],'insert_ext').", date=".db_date_now());
	alert($trad["inscription_users_enregistre"]);
	close_lightbox();
}
?>

<style type="text/css"> .input_text { width:300px; } </style>
<script type="text/javascript">
////	On contrôle les champs
function controle_formulaire()
{
	// Certains champs sont obligatoire
	if(get_value("nom")=="")		{ alert("<?php echo $trad["UTILISATEURS_specifier_nom"]; ?>");		return false; }
	if(get_value("prenom")=="")		{ alert("<?php echo $trad["UTILISATEURS_specifier_prenom"]; ?>");	return false; }
	// controle le mail (obligatoire + bien formaté + unique sur le site) : utilisé comme identifiant
	if(get_value("mail")=="" || controle_mail(get_value("mail"))==false)	{ alert("<?php echo $trad["mail_pas_valide"]; ?>");  return false; }
	requete_ajax("../module_utilisateurs/identifiant_verif.php?mail="+urlencode(get_value("mail")));
	if(trouver("oui",retour_ajax))	{ alert("<?php echo $trad["UTILISATEURS_mail_deja_present"]; ?>"); return false; }
	// Spécifier mot de passe + vérif confirmation
	if(get_value("pass")=="" || get_value("pass")!=get_value("pass2"))	{ alert("<?php echo $trad["UTILISATEURS_specifier_pass"]; ?>");  return false; }
	// Vérif du captcha
	if(controle_captcha()==false)	return false;
}
</script>

<form action="<?php echo php_self(); ?>" method="post" OnSubmit="return controle_formulaire();" style="padding:7px;font-weight:bold;">
	<?php
	//// Selection de l'espace
	echo ucfirst($trad["inscription_users_espace"])." &nbsp; ";
	echo "<select name='id_espace'>";
	foreach(db_tableau("select * from gt_espace where inscription_users='1'") as $espace_tmp){
		echo "<option value='".$espace_tmp["id_espace"]."' title=\"".$espace_tmp["description"]."\">".$espace_tmp["nom"]."</option>";
	}
	echo "</select><br><br>";
	?>
	<input type="text" name="nom" class="input_text" placeholder="<?php echo $trad["nom"]; ?>" /><br><br>
	<input type="text" name="prenom" class="input_text" placeholder="<?php echo $trad["prenom"]; ?>" /><br><br>
	<input type="text" name="mail" class="input_text" placeholder="<?php echo $trad["mail"]; ?>" /><br><br>
	<input type="password" name="pass" class="input_text" placeholder="<?php echo $trad["pass"]; ?>" style="width:150px;" /><br><br>
	<input type="password" name="pass2" class="input_text" placeholder="<?php echo $trad["pass2"]; ?>" style="width:150px;" /><br><br>
	<textarea name="message" class="input_text" style="height:35px;" placeholder="<?php echo $trad["commentaire"]; ?>"><?php echo @$_POST["message"]; ?></textarea><br><br>
	<?php echo menu_captcha(); ?><br><br><br>
	<div style="text-align:center;">
		<input type="hidden" name="envoi_inscription" value="1" />
		<input type="submit" value="<?php echo $trad["envoyer"]; ?>" class="button_big" />
	</div>
</form>

<?php require PATH_INC."footer.inc.php"; ?>