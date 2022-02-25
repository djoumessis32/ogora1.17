<?php
////	INIT
define("NO_MODULE_CONTROL",true);
require "commun.inc.php";
require_once PATH_INC."header.inc.php";
droit_modif_utilisateur($_REQUEST["id_utilisateur"],true);


////	VALIDATION DU FORMULAIRE DE VISIBILITE DU LIVECOUNTER & DU MESSENGER
////
if(isset($_POST["id_utilisateur"])) {
	// On réinitialise la table de jointure
	db_query("DELETE FROM gt_jointure_messenger_utilisateur WHERE id_utilisateur_messenger=".db_format($_POST["id_utilisateur"]));
	// On affecte à  TOUS / CERTAINS  utilisateurs
	if($_POST["selection_utilisateurs"]=="tous")	db_query("INSERT INTO gt_jointure_messenger_utilisateur SET id_utilisateur_messenger=".db_format($_POST["id_utilisateur"]).", id_utilisateur=null, tous_utilisateurs='1'");
	elseif($_POST["selection_utilisateurs"]=="certains"){
		foreach($_POST["liste_users"] as $id_user)  { db_query("INSERT INTO gt_jointure_messenger_utilisateur SET id_utilisateur_messenger=".db_format($_POST["id_utilisateur"]).", id_utilisateur=".db_format($id_user).", tous_utilisateurs=null"); }
	}
	reload_close();
}
?>


<script type="text/javascript">
////	Redimensionne
resizePopupLightbox(470,400);

////	AFFICHAGE DE LA LISTE DES UTILISATEURS
function aff_user(type_affichage)
{
	// Ré-init
	element("txt_aucun").className = element("txt_tous").className = element("txt_certains").className = "lien";
	// Sélection par texte : check le bon bouton radio
	set_check("input_"+type_affichage, true);
	element("txt_"+type_affichage).className="lien_select";
	// On affiche ou masque la liste des utilisateurs
	if(type_affichage=="certains")	afficher_dynamic("block_users",true);
	else							afficher("block_users",false);
}
</script>


<form action="<?php echo php_self(); ?>" method="post">
	
	<fieldset class="fieldset_titre"><?php echo $trad["UTILISATEURS_visibilite_messenger_livecounter"]; ?></fieldset>

	<div style="cursor:pointer;">
		<input type="radio" name="selection_utilisateurs" value="aucun" id="input_aucun" onClick="aff_user(this.value);" />
		<span id="txt_aucun" onClick="aff_user('aucun');"><?php echo $trad["UTILISATEURS_voir_aucun_utilisateur"]; ?></span>
	</div>
	<div style="cursor:pointer;margin-top:5px;">
		<input type="radio" name="selection_utilisateurs" value="tous" id="input_tous" onClick="aff_user(this.value);" />
		<span id="txt_tous" onClick="aff_user('tous');"><?php echo $trad["UTILISATEURS_voir_tous_utilisateur"]; ?></span>
	</div>
	<div style="cursor:pointer;margin-top:5px;">
		<input type="radio" name="selection_utilisateurs" value="certains" id="input_certains" onClick="aff_user(this.value);" />
		<span id="txt_certains" onClick="aff_user('certains');"><?php echo $trad["UTILISATEURS_voir_certains_utilisateur"]; ?> </span>
	</div>

	<div id="block_users" class="pas_selection" style="padding-left:30px;">
	<?php
	////	LISTE DES UTILISATEURS
	////
	$users_visibles = users_visibles(user_infos($_REQUEST["id_utilisateur"]));
	if(count($users_visibles)==0)	{ echo ">> ".$trad["UTILISATEURS_aucun_utilisateur_messenger"]; }
	else
	{
		// Utilisateurs que je peux autoriser à me voir
		$users_restreints = db_colonne("SELECT id_utilisateur FROM gt_jointure_messenger_utilisateur WHERE id_utilisateur_messenger='".intval($_GET["id_utilisateur"])."' AND id_utilisateur > 0");
		// Affichage des utilisateurs
		foreach($users_visibles as $user_tmp)
		{
			$id_tmp = "user".$user_tmp["id_utilisateur"];
			if((in_array($user_tmp["id_utilisateur"],$users_restreints)))	{ $check_user = "checked";	$style_user = "lien_select"; }
			else															{ $check_user = "";			$style_user = "lien"; }
			echo "<input type='checkbox' name='liste_users[]' value='".$user_tmp["id_utilisateur"]."' id='box_".$id_tmp."' onClick=\"checkbox_text(this);\" ".$check_user." /> &nbsp; ";
			echo "<span class='".$style_user."' id='txt_".$id_tmp."' onClick=\"checkbox_text(this);\">".$user_tmp["prenom"]." ".$user_tmp["nom"]."</span><br>";
		}
	}
	?>
	</div>

	<div style="text-align:center;margin-top:20px;">
		<input type="hidden" name="id_utilisateur" value="<?php echo $_GET["id_utilisateur"]; ?>" />
		<input type="submit" value="<?php echo $trad["modifier"]; ?>" class="button_big" />
	</div>

</form>


<?php
////	AFFICHAGE DE LA VALEUR PRINCIPALE DU FORMULAIRE
if(db_valeur("SELECT count(*) FROM gt_jointure_messenger_utilisateur WHERE id_utilisateur_messenger='".intval($_GET["id_utilisateur"])."'")==0)
	echo "<script> aff_user('aucun'); </script>";
elseif(db_valeur("SELECT count(*) FROM gt_jointure_messenger_utilisateur WHERE id_utilisateur_messenger='".intval($_GET["id_utilisateur"])."' AND tous_utilisateurs='1'")>0)
	echo "<script> aff_user('tous'); </script>";
else
	echo "<script> aff_user('certains'); </script>";
////	FOOTER
require PATH_INC."footer.inc.php";
?>