<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";
if($_SESSION["espace"]["droit_acces"]!=2)	exit();
titre_popup($trad["UTILISATEURS_rechercher_user"]);


////	AFFECTATION DE L'UTILISATEUR
////
if(isset($_GET["id_utilisateur_affecter"])) {
	db_query("INSERT INTO gt_jointure_espace_utilisateur SET id_espace='".$_SESSION["espace"]["id_espace"]."', id_utilisateur=".db_format($_GET["id_utilisateur_affecter"]).", droit='1'");
	reload_close();
}
?>



<script type="text/javascript">
////	Redimensionne
resizePopupLightbox(500,400);
////    On contrôle les champs
function controle_formulaire()
{
  // Il doit y avoir un text pour la recherche
  if (get_value("nom")=="" && get_value("prenom")=="" && get_value("mail")=="")		{ alert("<?php echo $trad["UTILISATEURS_preciser_recherche"]; ?>"); return false; }
}
</script>
<style type="text/css">
body	{ background-image:url('<?php echo PATH_TPL; ?>module_utilisateurs/fond_popup.png'); font-weight:bold; }
</style>


<form action="<?php echo php_self(); ?>" method="post" style="padding:10px;" OnSubmit="return controle_formulaire();">
	<table>
		<tr>
			<td width="150px"><?php echo $trad["nom"]; ?></td>
			<td><input type="text" name="nom" id="nom" value="<?php echo @$_POST["nom"]; ?>" style="width:250px" /></td>
		</tr>
		<tr>
			<td><?php echo $trad["prenom"]; ?></td>
			<td><input type="text" name="prenom" id="prenom" value="<?php echo @$_POST["prenom"]; ?>" style="width:250px" /></td>
		</tr>
		<tr>
			<td><?php echo $trad["mail"]; ?></td>
			<td><input type="text" name="mail" id="mail" value="<?php echo @$_POST["mail"]; ?>" style="width:250px" /></td>
		</tr>
		<tr>
			<td colspan="2" align="right" height="40px">
				<input type="hidden" name="rechercher" value="1" />
				<input type="submit" value="<?php echo $trad["rechercher"]; ?>" class="button" />
			</td>
		</tr>
	</table>
</form>


<?php
////	UTILISATEUR A AFFECTER
if(isset($_POST["rechercher"]))
{
	$users_espace = users_espace($_SESSION["espace"]["id_espace"]);
	////	Utilisateurs de la recherche
	$txt_recherche = "";
	if($_POST["nom"]!="")		$txt_recherche .= "nom like '%".$_POST["nom"]."%' OR ";
	if($_POST["prenom"]!="")	$txt_recherche .= "prenom like '%".$_POST["prenom"]."%' OR ";
	if($_POST["mail"]!="")		$txt_recherche .= "mail like '%".$_POST["mail"]."%' OR ";
	$liste_utilisateurs	= db_tableau("SELECT * FROM gt_utilisateur WHERE (".substr($txt_recherche,0,-3).") AND id_utilisateur not in (".implode(",",$users_espace).")");
	////	Tous les utilisateurs déjà affectés  /  affecter les utilisateurs suivants  /  aucun utilisateur pour cette recherche
	if(count($users_espace)==db_valeur("SELECT count(*) FROM gt_utilisateur"))		$titre_resultats = "<h3>".$trad["UTILISATEURS_tous_users_affectes"]."</h3>";
	elseif(count($liste_utilisateurs)>0)	$titre_resultats = $trad["UTILISATEURS_affecter_user"];
	elseif(count($liste_utilisateurs)==0)	$titre_resultats = $trad["UTILISATEURS_aucun_users_recherche"];
	////	Afficher résultats
	echo "<div style='margin:10px;margin-left:15px;'>".$titre_resultats."</div>";
	foreach($liste_utilisateurs as $infos_users)
	{
		echo "<div style='margin:10px;margin-left:30px;cursor:pointer;' onClick=\"confirmer('".addslashes($trad["UTILISATEURS_affecter_user_confirm"])."','utilisateur_affecter.php?id_utilisateur_affecter=".$infos_users["id_utilisateur"]."');\">";
		echo "<img src=\"".PATH_TPL."divers/check.png\" /> &nbsp; ".$infos_users["civilite"]." ".$infos_users["nom"]." ".$infos_users["prenom"]."</div>";
	}
}

////	FOOTER
require PATH_INC."footer.inc.php";
?>