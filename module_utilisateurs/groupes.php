<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";
if(droit_ajout_groupe()!=true)	exit;
$groupes_users = ($_SESSION["cfg"]["espace"]["affichage_users"]=="site")  ?  groupes_users()  :  groupes_users($_SESSION["espace"]["id_espace"]);

////	ON AJOUTE / MODIFIE UN GROUPE
if(isset($_POST["action"]) && $_POST["action"]=="edit")
{
	$id_espaces_tmp = (in_array("tous",$_POST["id_espaces"]))  ?  ""  :  tab2text($_POST["id_espaces"]);
	$corps_sql = " titre=".db_format($_POST["titre"]).", id_utilisateurs='".tab2text($_POST["id_utilisateurs"])."', id_espaces=".db_format($id_espaces_tmp)." ";
	if($_POST["id_groupe"]>0)	db_query("UPDATE gt_utilisateur_groupe SET ".$corps_sql." WHERE id_groupe=".db_format($_POST["id_groupe"]));
	else						db_query("INSERT INTO gt_utilisateur_groupe SET id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."', ".$corps_sql);
	$_SESSION["espace"]["groupes_user_courant"] = groupes_users($_SESSION["espace"]["id_espace"],$_SESSION["user"]["id_utilisateur"]);
	reload_close();
}

////	ON SUPPRIME UN GROUPE (SI ON EST L'AUTEUR DU GROUPE OU L'ADMINISTRATEUR GENERAL)
if(isset($_GET["action"]) && $_GET["action"]=="suppr" && $groupes_users[$_GET["id_groupe"]]["droit"]==2)
{
	db_query("DELETE FROM gt_utilisateur_groupe WHERE id_groupe=".db_format($_GET["id_groupe"]));
	$_SESSION["espace"]["groupes_user_courant"] = groupes_users($_SESSION["espace"]["id_espace"],$_SESSION["user"]["id_utilisateur"]);
	reload_close();
}

////	INIT
$nb_espaces_affectes_user = count(espaces_affectes_user());
$users_selection = ($_SESSION["user"]["admin_general"]==1)  ?  db_tableau("SELECT * FROM gt_utilisateur ORDER BY ".$_SESSION["agora"]["tri_personnes"])  :  users_espace($_SESSION["espace"]["id_espace"],"tout");
$groupes_users = array_merge(array(array("id_groupe"=>0,"id_utilisateur"=>$_SESSION["user"]["id_utilisateur"],"id_utilisateurs"=>"","id_espaces"=>"","droit"=>2)), $groupes_users);
$lib_gestion_groupe = ($_SESSION["cfg"]["espace"]["affichage_users"]=="espace")  ?  $trad["UTILISATEURS_groupe_espace"]  :  $trad["UTILISATEURS_groupe_site"];
?>


<script type="text/javascript">
////	Redimensionne
resizePopupLightbox(700,600);

////	ACTIVE / DESACTIVE CHAQUE USER  :  EST-IL DANS AU MOINS UN ESPACE SELECTIONNE ?
function gestion_espaces_users(id_groupe)
{
	// Init
	tous_users_enabled = true;
	// Controle chaque utilisateur
	for(cpt_user=0;  cpt_user <= <?php echo count(users_visibles()); ?>;  cpt_user++)
	{
		// Init
		user_enabled = true;
		user_tmp_input = element("G"+id_groupe+"_U"+cpt_user);
		user_tmp_div = element("div_G"+id_groupe+"_U"+cpt_user);
		// Pour chaque espace sélectionné : l'user y est affecté ?
		for(id_espace_tmp in users_ensembles){
			if(is_checked("E"+id_espace_tmp+"_G"+id_groupe)==true  &&  in_array(users_ensembles[id_espace_tmp],user_tmp_input.value)==false)	{ user_enabled = tous_users_enabled = false; }
		}
		// Active / désactive l'utilisateur en question
		if(user_enabled==false)		{ user_tmp_input.disabled = true;   user_tmp_div.style.opacity = "0.3"; }
		else						{ user_tmp_input.disabled = false;  user_tmp_div.style.opacity = "1";  }
	}
	// Info s'il y a des users indisponibles
	element("info_users_desactives"+id_groupe).innerHTML = (tous_users_enabled==false)  ?  "<?php echo $trad["UTILISATEURS_groupe_espace_infos"]; ?>"  :  "";
}

////	CONTROLE AVANT VALIDATION FINALE
function controle_formulaire(id_groupe)
{
	// titre obligatoire
	if(get_value("groupe"+id_groupe+"_titre").length==0)	{ alert("<?php echo $trad["specifier_titre"]; ?>");  return false; }
	// Au moins un espace de sélectionné
	espaces_selected = false;
	for(id_espace_tmp in users_ensembles)	{ if(is_checked("E"+id_espace_tmp+"_G"+id_groupe)==true)  espaces_selected = true; }
	if(espaces_selected==false)		{ alert("<?php echo $trad["selectionner_espace"]; ?>"); return false; }
	// Au moins 2 utilisateurs sélectionnés
	nb_user_select = 0;
	for(i=0;  i <= <?php echo count(users_visibles()); ?>;  i++)	{ if(is_checked("G"+id_groupe+"_U"+i)==true && element("G"+id_groupe+"_U"+i).disabled==false)  nb_user_select ++; }
	if(nb_user_select<2)	{ alert("<?php echo $trad["selectionner_2users"]; ?>");  return false; }
}
</script>


<?php
////	TITRE DU FORMULAIRE
echo "<fieldset class='fieldset_titre'><img src=\"".PATH_TPL."module_utilisateurs/utilisateurs_groupe.png\" />&nbsp; ".$lib_gestion_groupe."<div style='margin-top:5px;font-weight:normal;'>".$trad["UTILISATEURS_droit_gestion_groupes"]."</div></fieldset>";

////	FORMULAIRE "AJOUTER"
echo "<div style='margin-top:10px;'><button onClick=\"afficher_dynamic('groupe0_form');this.style.display='none';\" class='button_big'>".$trad["ajouter"]." &nbsp;<img src=\"".PATH_TPL."divers/plus.png\" /></button></div>";


////	LISTE DES GROUPES
////
foreach($groupes_users as $groupe_tmp)
{
	echo "<form action=\"".php_self()."\" method='post' id='groupe".$groupe_tmp["id_groupe"]."_form' onSubmit=\"return controle_formulaire('".$groupe_tmp["id_groupe"]."');\" style='padding:7px;font-weight:bold;".($groupe_tmp["id_groupe"]==0?"display:none;":"")."'><fieldset>";
		////	AUTEUR  +  TITRE
		if($groupe_tmp["id_groupe"]>0)	echo "<div style='float:right;font-style:italic;'>".$trad["cree_par"]." : ".auteur($groupe_tmp["id_utilisateur"])."</div>";
		echo $trad["titre"]." <input type='text' name='titre' value=\"".@$groupe_tmp["titre"]."\" id='groupe".$groupe_tmp["id_groupe"]."_titre' style='width:300px;' />";

		////	AFFECTATION AUX ESPACES  +  AFFECTATION AUX USERS
		echo menu_affect_espaces($groupe_tmp["id_groupe"], "G", $groupe_tmp["id_espaces"], "gestion_espaces_users('".$groupe_tmp["id_groupe"]."');");
		echo "<hr /><div style='width:100%;max-height:150px;overflow:auto;'>";
		foreach($users_selection as $cpt_user => $user_tmp)
		{
			$id_tmp = "G".$groupe_tmp["id_groupe"]."_U".$cpt_user;
			$select_user = (in_array($user_tmp["id_utilisateur"],text2tab($groupe_tmp["id_utilisateurs"])))  ?  "checked "  :  "";
			echo "<div style='float:left;width:33%;' id=\"div_".$id_tmp."\" ><input type='checkbox' name=\"id_utilisateurs[]\" value=\"".$user_tmp["id_utilisateur"]."\" id=\"".$id_tmp."\" ".$select_user." />".$user_tmp["prenom"]." ".$user_tmp["nom"]."</div>";
		}
		echo "</div>";

		////	DIV D'INFO SUR LES USERS DESACTIVES  +  INIT LES ACTIVATION / DESACTIVATIONS D'UTILISATEURS
		echo "<div id='info_users_desactives".$groupe_tmp["id_groupe"]."' style='margin-top:10px;font-style:italic;".STYLE_SELECT_YELLOW."'></div>";
		echo "<script> gestion_espaces_users('".$groupe_tmp["id_groupe"]."'); </script>";

		////	VALIDATION + SUPPRESSION ?
		if($groupe_tmp["droit"]==2)
		{
			echo "<div style='float:right;margin-top:15px;'>";
				echo "<input type='hidden' name='action' value='edit' />";
				echo "<input type='hidden' name='id_groupe' value='".$groupe_tmp["id_groupe"]."' />";
				if($groupe_tmp["id_groupe"]==0)	{ echo "<input type='submit' value=\"".$trad["ajouter"]."\" class='button_big' />"; }
				else							{ echo "<input type='submit' value=\"".$trad["modifier"]."\" class='button' /> &nbsp; ".icone_suppr("groupes.php?action=suppr&id_groupe=".$groupe_tmp["id_groupe"]); }
			echo "</div>";
		}
	echo "</fieldset></form>";
}
?>