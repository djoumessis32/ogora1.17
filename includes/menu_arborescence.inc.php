<script type="text/javascript">
////	SELECTIONNE UN DOSSIER
////
function select_dossier(id_dossier, initialise)
{
	// Met en valeur le dossier sélectionné  /  Réinitialise le style du dossier (déplacer.php)
	for(compteur in tab_dossiers){
		element("lib_dossier_"+tab_dossiers[compteur]).className = (tab_dossiers[compteur]==id_dossier)  ?  "lien_select"  :  "lien";
	}
	// Lance  "select_dossier_action()" OU redirige la page courante vers le dossier sélectionné
	if(initialise==undefined) {
		if(typeof(select_dossier_action)=="function")	select_dossier_action(id_dossier);
		else											redir("<?php echo php_self(); ?>?id_dossier="+id_dossier);
	}
}
var tab_dossiers = new Array();

////	OUVRE/FERME LE CONTENU D'UN DOSSIER
////
function select_contenu_dossier(id_dossier)
{
	affiche_contenu = afficher_dynamic('div_extend_dossier_'+id_dossier);
	element('ico_extend_dossier_'+id_dossier).src = (affiche_contenu==false)  ?  "<?php echo PATH_TPL; ?>divers/dossier_ouvrir.png"  :  "<?php echo PATH_TPL; ?>divers/dossier_ouvert.png";
}
</script>


<?php
////	CHEMIN DU DOSSIER COURANT (TABLEAU) & ARBORESCENCE COMPETE (TABLEAU)
$path_dossier_courant = chemin($cfg_menu_arbo["objet"],$cfg_menu_arbo["id_objet"],"tableau");
$arbo_dossiers = arborescence($cfg_menu_arbo["objet"]);


////	AFFICHE CHAQUE DOSSIER
////
foreach($arbo_dossiers as $compteur => $dossier_tmp)
{	
	// Dossier precedent de niveau inférieur : Ouvre un div conteneur
	if($dossier_tmp["niveau"]>1 && @$arbo_dossiers[$compteur-1]["niveau"]<$dossier_tmp["niveau"]){
		$conteur_ouvert = (array_multi_search($path_dossier_courant,"id_dossier",$dossier_tmp["id_dossier_parent"])==false)  ?  "display:none;"  :  "";
		echo "<div style='".$conteur_ouvert."padding-left:20px;' id='div_extend_dossier_".$dossier_tmp["id_dossier_parent"]."'>";
	}

	////	AFFICHAGE
	echo "<div style='padding:2px;'>";
		// Icone d'extention du contenu  &  icone de dépendence
		if($dossier_tmp["niveau"]>0)
		{
			// S'il y a des dossier enfant : icone d'extention du contenu avec désactivation du menu flottant (peut masquer le menu du dessous..)
			$dossiers_enfant = false;
			foreach($arbo_dossiers as $dossier_tmp2)	{ if($dossier_tmp2["id_dossier_parent"]==$dossier_tmp["id_dossier"]) {$dossiers_enfant = true;} }
			if($dossiers_enfant==true){
				$icone_ouvert_ouvrir = (array_multi_search($path_dossier_courant,"id_dossier",$dossier_tmp["id_dossier"]))  ?  "dossier_ouvert.png"  :  "dossier_ouvrir.png";
				echo "<img src=\"".PATH_TPL."divers/".$icone_ouvert_ouvrir."\" id='ico_extend_dossier_".$dossier_tmp["id_dossier"]."' style='position:absolute;margin-left:1px;margin-top:3px;cursor:pointer;' onClick=\"$(window).unbind('scroll',$.floatMgr.onChange);select_contenu_dossier('".$dossier_tmp["id_dossier"]."');\" />";
			}
			// icone de dépendence
			echo "<img src=\"".PATH_TPL."divers/dependance_dossier.png\" />";
		}
		// Icone, nom du dossier et menu contextuel
		echo "<img src=\"".PATH_TPL."divers/dossier_arborescence.png\" onClick=\"select_dossier(".$dossier_tmp["id_dossier"].");\" />";
		echo "<span id='lib_dossier_".$dossier_tmp["id_dossier"]."' onClick=\"select_dossier(".$dossier_tmp["id_dossier"].");\" title=\"".ucfirst($dossier_tmp["description"])."\"> ".text_reduit(ucfirst($dossier_tmp["nom"]),30)."</span>&nbsp;";
		if($cfg_menu_arbo["id_objet"]==$dossier_tmp["id_dossier"])
		{
			$cfg_menu_elem = array("objet"=>$cfg_menu_arbo["objet"], "objet_infos"=>$dossier_tmp, "taille_icone"=>"small_inline");
			$cfg_menu_elem_modif = ($_SESSION["user"]["admin_general"]==1  ||  (is_dossier_racine($cfg_menu_arbo["objet"],$dossier_tmp["id_dossier"])==false && @$cfg_menu_arbo["droit_acces_dossier"]==3))  ?  true  :  false;
			if($cfg_menu_elem_modif==true && empty($cfg_menu_arbo["is_menu_deplacer"])){
				$cfg_menu_elem["modif"] = PATH_DIVERS."dossier_edit.php?module_path=".MODULE_PATH."&type_objet_dossier=".$cfg_menu_arbo["objet"]["type_objet"]."&id_dossier=".$dossier_tmp["id_dossier"];
			}
			require PATH_INC."element_menu_contextuel.inc.php";
		}
	echo "</div>";

	// Dossier suivant de niveau inférieur : Ferme le/les div conteneur
	$niveau_suivant = (isset($arbo_dossiers[$compteur+1]["niveau"]))  ?  $arbo_dossiers[$compteur+1]["niveau"]  :  1;
	if($dossier_tmp["niveau"]>1 && $niveau_suivant<$dossier_tmp["niveau"]){
		for($i=$dossier_tmp["niveau"]; $i>$niveau_suivant; $i--)  { echo "</div>"; }
	}

	// Ajoute le dossier courant au tableau Javascript
	echo "<script type='text/javascript'> tab_dossiers.push('".$dossier_tmp["id_dossier"]."'); </script>";
}


////	AJOUTER UN DOSSIER
////
if(isset($cfg_menu_arbo["ajouter_dossier"]) && @$cfg_menu_arbo["droit_acces_dossier"]>=2) {
	echo "<div class='lien' style='text-align:right;margin:-5px;margin-top:5px;font-style:italic;font-size:10px;' onclick=\"popupLightbox('".PATH_DIVERS."dossier_edit.php?module_path=".MODULE_PATH."&type_objet_dossier=".$cfg_menu_arbo["objet"]["type_objet"]."&id_dossier_parent=".$cfg_menu_arbo["id_objet"]."');\">";
	echo $trad["ajouter_dossier"]."&nbsp;<img src=\"".PATH_TPL."divers/dossier_ajouter.png\" /></div>";
}
?>


<script type="text/javascript">  select_dossier(<?php echo $cfg_menu_arbo["id_objet"]; ?>, true);  </script>