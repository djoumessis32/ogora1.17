<script type="text/javascript">
////	NB ELEMENTS SELECTIONNES
////	type_element => si on ne souhaite qu'un certain type d'element
function nb_elements_select(alerte, type_element)
{
	var nb_elements_select = 0;
	SelectedElemsBoxes = document.getElementsByName("SelectedElems[]");//!IE le considère comme un objet, et non comme un simple tableau de valeurs
	for(var i=0; i < SelectedElemsBoxes.length; i++)
	{
		if(SelectedElemsBoxes[i].checked==true  &&  (type_element==undefined || SelectedElemsBoxes[i].value.substring(0,type_element.length)==type_element))
			{nb_elements_select++;}
	}
	if(nb_elements_select==0 && alerte==true)	{alert("<?php echo $trad["aucun_element_selectionne"]; ?>");}
	return nb_elements_select;
}


////	SÉLECTION / DÉSELECTION D'UN ÉLEMENT  (onClick sur l'élement ("bascule") / via selection_tous_elements() )
////	select => true / false / "bascule"
function selection_element(cpt_div_element, select)
{
	if(isNaN(cpt_div_element))	cpt_div_element = cpt_div_element.replace('checkbox_element_','').replace('div_elem_','');
	checkbox_element = element("checkbox_element_"+cpt_div_element);
	if(select==true ||  (select==undefined && checkbox_element.checked==false))			{ checkbox_element.checked = true;   element("div_elem_"+cpt_div_element).className = "div_elem_select"; }
	else if(select==false ||  (select==undefined && checkbox_element.checked==true))	{ checkbox_element.checked = false;  element("div_elem_"+cpt_div_element).className = "div_elem_deselect"; }
	// Déroule / enroule le menu d'édition des éléments ?
	if(existe("menu_elements_edit")){
		if(nb_elements_select()>0 && element("menu_elements_edit").style.display=="none")	{afficher_dynamic('menu_elements_edit',true);  setTimeout(function(){menuFlottant();},500);}
		if(nb_elements_select()==0 && element("menu_elements_edit").style.display!="none")	{afficher_dynamic('menu_elements_edit',false); setTimeout(function(){menuFlottant();},500);}
	}
	// Affiche le bon libellé principal
	libelle_principal = (nb_elements_select()>0)  ?  get_value("text_inverser_selection")  :  get_value("text_tout_selectionner");
	element("text_selection_menu_elements").innerHTML = libelle_principal;
}


////	SELECTION / DESELECTION DE TOUS LES ELEMENTS
////	select		 => true / false / "bascule"
////	type_element => si on ne souhaite qu'un certain type d'element (genre "fichier-")
function selection_tous_elements(select, type_element)
{
	////	Init
	nb_selections = 0;
	if(select==undefined)  select = "bascule";
	SelectedElemsBoxes = document.getElementsByName("SelectedElems[]");
	////	On lance la sélection/déselection
	for(var i=0; i< SelectedElemsBoxes.length; i++)
	{
		if(type_element==undefined || SelectedElemsBoxes[i].value.substring(0,type_element.length)==type_element)
		{
			cpt_div_element = i + 1;
			if(select==true || (select=="bascule" && element("checkbox_element_"+cpt_div_element).checked==false))	{ select2 = true;  nb_selections ++; }
			else																									{ select2 = false; }
			selection_element(cpt_div_element, select2);
		}
	}
	afficher_block = (nb_selections>0) ? true : false;
	afficher_dynamic("menu_elements_edit",afficher_block);
	setTimeout(function(){menuFlottant();},500);//desactive si besoin le menu flottant..
}


////	PREPARE L'URL A PARTIR DES ELEMENTS SELECTIONNES VIA "SelectedElems[]"
////
function SelectedElems()
{
	// Init
	typeElemTmp = "";
	SelectedElemsURL = "";
	// Parcour les Inputs hidden "SelectedElems[]" (IE considère 'SelectedElemsBoxes' comme un objet et non un simple tableau)
	SelectedElemsBoxes = document.getElementsByName("SelectedElems[]");
	for(var i=0; i < SelectedElemsBoxes.length; i++)
	{
		if(SelectedElemsBoxes[i].checked==true)
		{
			// Transforme par exemple "contact-1" en tableau à 2 valeurs "contact" et "1"
			ElemTmp = SelectedElemsBoxes[i].value.split("-");
			// "&SelectedElems[contact]=1"  OU  "-1"  si le type est dejà spécifié dans l'url
			if(typeElemTmp!=ElemTmp[0])		{ SelectedElemsURL += "&SelectedElems["+ElemTmp[0]+"]="+ElemTmp[1];  typeElemTmp = ElemTmp[0]; }
			else							{ SelectedElemsURL += "-"+ElemTmp[1]; }
		}
	}
	// Retoure quelquechose comme  &SelectedElems[contact]=1-2-3
	return SelectedElemsURL;
}


////	SUPPRIMER LES ÉLEMENTS SÉLECTIONNÉS
////
function supprimer_elements(action)
{
<?php
	// On confirme 1 ou 2 fois?
	$confirmer_suppr = 'confirm("'.$trad["confirmer_suppr"].' (x"+nb_elements_select()+")")';
	if(isset($cfg_menu_elements["confirmer_suppr_bis"]))	{$confirmer_suppr .= ' && confirm("'.$trad["confirmer_suppr_bis"].'")';}
?>
	// On confirme avant la suppression
	if(nb_elements_select(true) > 0  &&  <?php echo $confirmer_suppr; ?>)
		{redir("elements_suppr.php?"+SelectedElems()+"&action="+action+"&id_dossier_retour=<?php echo @$cfg_menu_elements["id_objet_dossier"]; ?>");}
}
</script>



<?php
////	SELECTIONNER TOUS LES ELEMENTS  /  INVERSER LA SELECTION
echo "<div class='menu_gauche_line lien' onClick=\"selection_tous_elements('bascule');\">";
	echo "<div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/check.png\" /></div>";
	echo "<div class='menu_gauche_txt'><span id='text_selection_menu_elements'>".$trad["tout_selectionner"]."</span><input type='hidden' name='text_inverser_selection' value=\"".$trad["inverser_selection"]."\" /><input type='hidden' name='text_tout_selectionner' value=\"".$trad["tout_selectionner"]."\" /></div>";
echo "</div>";

////	ACTION SUR LES ELEMENTS SELECTIONNES
echo "<div id='menu_elements_edit' style='padding-left:20px;display:none;'>";
	//TELECHARGER LA SELECTION
	if(MODULE_NOM=="fichier")
		{echo "<div class='menu_gauche_line lien' onClick=\"telecharger_fichiers();\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/telecharger.png\" /></div><div class='menu_gauche_txt'>".$trad["FICHIER_telecharger_selection"]."</div></div>";}
	// VOIR DES CONTACTS SUR UNE CARTE
	if(MODULE_NOM=="contact" || MODULE_NOM=="utilisateurs")
		{echo "<div class='menu_gauche_line lien' onClick=\"if(nb_elements_select(true)>0) popupLightbox('".PATH_DIVERS."contacts_map.php?module_path=".MODULE_PATH."&'+SelectedElems());\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/carte2.png\" /></div><div class='menu_gauche_txt'>".$trad["voir_sur_carte"]."</div></div>";}
	// DEPLACER / SUPPRIMER
	if(@$cfg_menu_elements["droit_acces_dossier"]>=2)
	{
		echo "<div class='menu_gauche_line lien' onClick=\"if(nb_elements_select(true)>0)  popupLightbox('".PATH_DIVERS."deplacer.php?module_path=".MODULE_PATH."&type_objet_dossier=".@$cfg_menu_elements["objet_dossier"]["type_objet"]."&id_dossier_parent=".@$cfg_menu_elements["id_objet_dossier"]."&'+SelectedElems());\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/dossier_deplacer.png\" /></div><div class='menu_gauche_txt'>".$trad["deplacer_elements"]."</div></div>";
		echo "<div class='menu_gauche_line lien' onClick=\"supprimer_elements('suppression');\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/supprimer.png\" /></div><div class='menu_gauche_txt'>".$trad["suppr_elements"]."</div></div>";
	}
	// UTILISATEUR : DESAFFECTER (admin espace) / SUPPRIMER (admin général)
	if($cfg_menu_elements["objet"]["type_objet"]=="utilisateur" && $_SESSION["espace"]["droit_acces"]==2)
	{
		if(@$_SESSION["cfg"]["espace"]["affichage_users"]=="espace")
			{echo "<div class='menu_gauche_line lien' onClick=\"supprimer_elements('desaffectation');\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/supprimer.png\" /></div><div class='menu_gauche_txt'>".$trad["UTILISATEURS_desaffecter"]."</div></div>";}
		if($_SESSION["user"]["admin_general"]==1)
			{echo "<div class='menu_gauche_line lien' onClick=\"supprimer_elements('suppression');\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/supprimer.png\" /></div><div class='menu_gauche_txt'>".$trad["UTILISATEURS_suppr_definitivement"]."</div></div>";}
	}
echo "<hr /></div>";