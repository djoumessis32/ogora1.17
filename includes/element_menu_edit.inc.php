<?php
////	OPTIONS PAR DEFAUT
////
if(!isset($cfg_menu_edit["id_objet"]))						$cfg_menu_edit["id_objet"] = 0;							// nouvel objet
if(!isset($cfg_menu_edit["raccourci"]))						$cfg_menu_edit["raccourci"] = true;						// Création d'un raccourci dans la barre de menu princpale
if(!isset($cfg_menu_edit["notif_mail"]))					$cfg_menu_edit["notif_mail"] = true;					// Envoi de notification par mail aux personnes affectés à l'objet
if(!isset($cfg_menu_edit["fichiers_joint"]))				$cfg_menu_edit["fichiers_joint"] = true;				// Ajout de fichiers joints
if(!isset($cfg_menu_edit["acces_ecriture_obligatoire"]))	$cfg_menu_edit["acces_ecriture_obligatoire"] = false;	// On controle si au moins une personne est affecté en écriture
if(!isset($cfg_menu_edit["ecriture_limite_defaut"]))		$cfg_menu_edit["ecriture_limite_defaut"] = false;		// Sujets du forum :écriture limité par défaut, pour ne pas modifier les autres messages du sujet..
if(!isset($cfg_menu_edit["objet_independant"]))				$cfg_menu_edit["objet_independant"] = objet_independant($cfg_menu_edit["objet"],$cfg_menu_edit["id_objet"]);	// Déjà à "False" si c'est une nouvelle version d'un fichier...
////	OPTIONS DIVERSES
$is_dossier_racine = is_dossier_racine($cfg_menu_edit["objet"],$cfg_menu_edit["id_objet"]);
$is_conteneur = (isset($cfg_menu_edit["objet"]["type_contenu"]))  ?  true  :  false;
$style_disabled = " style=\"opacity:0.5;filter:alpha(opacity=50);\" disabled=\"disabled\" ";
$nb_fichiers_max = 10;
?>


<style type="text/css">
.table_ligne			{ display:table; width:100%; }
.table_ligne:hover		{ background-color:<?php echo STYLE_TR_DESELECT; ?> }
.cellule_lib			{ display:table-cell; padding:0px; text-align:left; }
.cellule_input			{ display:table-cell; padding:0px; text-align:center; width:85px; }
.cellule_input2			{ display:table-cell; padding:0px; text-align:center; width:130px; }
.objet_deselect			{ height:20px; padding-left:5px; cursor:pointer; font-weight:normal; }
.objet_lecture			{ height:20px; padding-left:5px; cursor:pointer; font-weight:bold; <?php echo STYLE_SELECT_YELLOW; ?> }
.objet_ecriture_limite	{ height:20px; padding-left:5px; cursor:pointer; font-weight:bold; <?php echo STYLE_SELECT_ORANGE; ?> }
.objet_ecriture			{ height:20px; padding-left:5px; cursor:pointer; font-weight:bold; <?php echo STYLE_SELECT_RED; ?> }
.div_options			{ display:none; overflow:auto; margin:10px; margin-left:50px; padding:10px; max-height:200px; <?php echo STYLE_BLOCK; ?> }
.puce_point				{ width:8px; margin-right:5px; }
.icone_public_groupe	{ margin:0px; height:15px; }
input[type=checkbox]	{ margin:1px; }
</style>


<script type="text/javascript">
////	AFFICHER PLUS DE DESTINATAIRES POUR LES NOTIFICATIONS
////
function notif_more_users(nb_users_visibles)
{
	for(i=1; i<=nb_users_visibles; i++) {
		afficher("notif_user_"+i, true, "block");
	}
	afficher("bouton_notif_more_users", false);
}


////	AFFICHAGE DES INPUTS FICHIER
////
function affiche_fichiers(id_fichier)
{
	// Affiche un nouvel input
	for(var i=1; i<=<?php echo $nb_fichiers_max; ?>; i++)
	{
		if(get_value("add_fichier_joint"+i)!="")		afficher("div_fichier_joint"+(i+1), true, "block");
	}
	// Fichier multimédia ?  =>  affiche et précoche l'option "ajouter dans la description"  (voir controle_fichier() de type "fichier_joint")
	ext = extension(get_value("add_fichier_joint"+id_fichier));
	if("<?php echo @$_GET["textarea_name"]; ?>"!=""  &&  (ext=="jpg" || ext=="jpeg" || ext=="jpe" || ext=="png" || ext=="gif" || ext=="bmp" || ext=="wbmp" || ext=="mp4" || ext=="mpeg" || ext=="mpg" || ext=="avi" || ext=="flv" || ext=="ogv" || ext=="webm" || ext=="wmv" || ext=="mov" || ext=="mp3" || ext=="swf")){
		checkbox_text("txt_add_fichier_joint"+id_fichier);
		afficher("txt_add_fichier_joint"+id_fichier, true);
	}
}


////	SUPPRESSION D'UN FICHIER JOINT
////
function suppr_fichier_joint(id_fichier)
{
	if(confirm("<?php echo $trad["confirmer_suppr"]; ?>")==true){
		requete_ajax("<?php echo PATH_DIVERS; ?>fichier_joint_suppr.php?id_fichier="+id_fichier+"&module_path=<?php echo MODULE_PATH; ?>");
		if(trouver("oui",retour_ajax))	{afficher("fichier_joint"+id_fichier,false);}
	}
}


////	ON MODIFIE LES DROITS ET LE STYLE DU TEXTE
////
function selection_affect(id, action)
{
	// RECUP LES ID DU TEXTE ET DES CHECKBOX  (lecture/écriture)
	id2 = id.replace("text_","").replace("lecture_","").replace("ecriture_limit_","").replace("ecriture_","");
	texte			= "text_"+id2;
	lecture			= "lecture_"+id2;
	ecriture_limit	= "ecriture_limit_"+id2;
	ecriture		= "ecriture_"+id2;

	// COMMANDE DEPUIS UN TEXTE
	if(trouver("text",id)==true)
	{
		// Lecture  (forcée OU rien de coché)
		if(element(lecture).disabled==false  &&  (action=="lecture" || (is_checked(lecture)==false && is_checked(ecriture_limit)==false && is_checked(ecriture)==false))) {
			set_check(lecture, true);
			set_check(ecriture_limit, false);
			set_check(ecriture, false);
		}
		// Ecriture limité  (forcée OU  ecriture limité décochée + ecriture décochée)
		else if(element(ecriture_limit).disabled==false  &&  (action=="ecriture_limit" || (is_checked(ecriture_limit)==false && is_checked(ecriture)==false))) {
			set_check(lecture, false);
			set_check(ecriture_limit, true);
			set_check(ecriture, false);
		}
		// Ecriture  (forcée OU ecriture décoché et lecture coché/désactivé  OU  force l'ecriture)
		else if(element(ecriture).disabled==false  &&  (action=="ecriture" || is_checked(ecriture)==false)) {
			set_check(lecture, false);
			set_check(ecriture_limit, false);
			set_check(ecriture, true);
		}
		// Aucun accès
		else {
			set_check(lecture, false);
			set_check(ecriture_limit, false);
			set_check(ecriture, false);
		}
	}
	// COMMANDE DEPUIS UNE CHECKBOX : DESACTIVE LES AUTRES BOX
	else
	{
		if(trouver("lecture",id))			{ set_check(ecriture_limit,false);  set_check(ecriture,false); }
		if(trouver("ecriture_limit",id))	{ set_check(lecture,false);  		set_check(ecriture,false); }
		else if(trouver("ecriture",id))		{ set_check(lecture,false);  		set_check(ecriture_limit,false); }
	}

	// ECRITURE LIMITE PAR DEFAUT : MESSAGE D'ALERTE SI ECRITURE SELECTIONNE
	<?php if($cfg_menu_edit["ecriture_limite_defaut"]==true){ ?>
	if(is_checked(ecriture) && element(ecriture_limit).disabled==false)  alert("<?php echo $trad["EDIT_OBJET_alert_ecriture_limite_defaut"]; ?>");
	<?php } ?>

	// ON MODIFIE LA COULEUR DU TEXTE
	if(is_checked(lecture))					element(texte).className="objet_lecture";
	else if(is_checked(ecriture_limit))		element(texte).className="objet_ecriture_limite";
	else if(is_checked(ecriture))			element(texte).className="objet_ecriture";
	else									element(texte).className="objet_deselect";
}


////	AFFICHER / MASQUER TOUS LES ESPACES
////
function espaces_display(nb_espaces, init)
{
	// Affiche tous les espaces ?
	afficheTousEspaces = (init==true || afficheTousEspaces==true) ? false : true;
	// Affiche/masque les autres espaces
	if(init!=true)
	{
		// Affiche/masque chaque espace (on part du 2ème espace : i=1)
		for(i=1; i<nb_espaces; i++)
		{
			(afficheTousEspaces==true)  ?  afficher_dynamic("block_espace_"+i,afficheTousEspaces)  :  afficher("block_espace_"+i,afficheTousEspaces);
		}
		// Affiche/Masque "tous les espaces"
		afficher("block_espaces",afficheTousEspaces);
	}
	// Modif le libellé du menu
	if(afficheTousEspaces==false)	$("#derouler_espaces").html("<?php echo $trad["EDIT_OBJET_tous_espaces"]; ?>");
	else							$("#derouler_espaces").html("<?php echo $trad["masquer"]; ?>");
}


////	CONTROLE FINAL DU FORMAULAIRE  ->  NB D'ESPACES ET D'UTILISATEURS SÉLECTIONNÉS
////
function Controle_Menu_Objet(pas_controler_affectations)
{
	var formulaire_valide = true;
	
	// INVITE : CONTROLE PSEUDO & IDENTIFICATION VISUELLE
	if(existe("invite")){
		if(get_value("invite")=="")		{ alert("<?php echo $trad["EDIT_OBJET_alert_invite"]; ?>");  formulaire_valide = false; }
		if(controle_captcha()==false)	formulaire_valide = false;
	}

	// ACCES PARTAGE : CONTROLE LE NOMBRE D'AFFECTATIONS (S'IL Y EN A...)
	<?php if($cfg_menu_edit["objet_independant"]==true && $_SESSION["user"]["id_utilisateur"]>0){ ?>
	if(existe("invite")==false && pas_controler_affectations!=true)
	{
		// initialisations
		affectations_lecture		= nb_box_checked("lecture_invites[]") + nb_box_checked("lecture_espaces[]") + nb_box_checked("lecture_groupes[]") + nb_box_checked("lecture_users[]");
		affectations_ecriture_limit	= nb_box_checked("ecriture_limit_invites[]") + nb_box_checked("ecriture_limit_espaces[]") + nb_box_checked("ecriture_limit_groupes[]") + nb_box_checked("ecriture_limit_users[]");
		affectations_ecriture		= nb_box_checked("ecriture_espaces[]") + nb_box_checked("ecriture_groupes[]") + nb_box_checked("ecriture_users[]");
		if(is_checked("lecture_tous_espaces"))			affectations_lecture++;
		if(is_checked("ecriture_limit_tous_espaces"))	affectations_ecriture_limit++;
		if(is_checked("ecriture_tous_espaces"))			affectations_ecriture++;
		// Il doit y avoir au moins une affectation
		if((affectations_lecture + affectations_ecriture_limit + affectations_ecriture)==0){
			alert("<?php echo $trad["EDIT_OBJET_alert_aucune_selection"]; ?>");
			formulaire_valide = false;
		}
		// S'il doit y avoir au moins une affectation en écriture
		<?php if($cfg_menu_edit["acces_ecriture_obligatoire"]==true){ ?>
		if(affectations_ecriture_limit==0 && affectations_ecriture==0){
			alert("<?php echo $trad["EDIT_OBJET_alert_ecriture"]; ?>");
			formulaire_valide = false;
		}
		<?php } ?>
		// Objet affecté à l'utilisateur courant ?
		if((nb_box_checked("lecture_espaces[]")+nb_box_checked("ecriture_limit_espaces[]")+nb_box_checked("ecriture_espaces[]")+nb_box_checked("lecture_groupes[]")+nb_box_checked("ecriture_limit_groupes[]")+nb_box_checked("ecriture_groupes[]"))==0  &&  is_checked("lecture_tous_espaces")==false && is_checked("ecriture_limit_tous_espaces")==false && is_checked("ecriture_tous_espaces")==false)
		{
			user_courant_checked = 0;
			tab_users = document.getElementsByName("lecture_users[]");
			for(i=0; i<tab_users.length; i++)
			{
				id_tmp = tab_users[i].id.replace("lecture_","");
				if(element("text_"+id_tmp).className!="objet_deselect"  &&  id_tmp.substring(id_tmp.lastIndexOf("U")+1)=="<?php echo $_SESSION["user"]["id_utilisateur"]; ?>")
					user_courant_checked ++;
			}
			if(user_courant_checked==0  && confirm("<?php echo $trad["EDIT_OBJET_alert_pas_acces_perso"]; ?>")==false)	formulaire_valide = false;
		}
	}
	<?php } ?>

	// AFFICHAGE DE L'ICONE "LOADING"  +  RENVOI DU RESULTAT
	if(formulaire_valide==true)  afficher('div_loading');
	return formulaire_valide;
}
</script>




<?php
////	IDENTIFICATION INVITE
////
if($_SESSION["user"]["id_utilisateur"]<1)
	{echo "<fieldset>".$trad["EDIT_OBJET_invite"]." &nbsp; <input type='text' name='invite' style='width:330px' onkeyup=\"if(this.value.length>150) this.value=this.value.slice(0,150);\" /><br><br>".menu_captcha()."</fieldset>";}



////	DROITS D'ACCES
////
if($_SESSION["user"]["id_utilisateur"]>0 && $cfg_menu_edit["objet_independant"]==true)
{
	////	INIT  =>  ID DE L'OBJET SUR LESQUELLES SONT BASEES LES AFFECTATIONS  +  ESPACES AFFECTES A CET OBJET  +  DOSSIER RACINE ?
	$id_objet_affect = (isset($cfg_menu_edit["id_parent_recup_droits"]))  ?  $cfg_menu_edit["id_parent_recup_droits"]  :  $cfg_menu_edit["id_objet"];
	$espaces_affectes = db_colonne("SELECT distinct id_espace FROM gt_jointure_objet WHERE type_objet='".$cfg_menu_edit["objet"]["type_objet"]."' AND id_objet='".intval($id_objet_affect)."' AND id_espace > 0");
	////	CONTENEUR : MODIF DES LIBELLES DETAILLES DES DROITS D'ACCES EN ECRITURE
	if($is_conteneur==true){
		$trad["ecriture_limit_infos"] = change_libelles_objets($cfg_menu_edit["objet"],$trad["ecriture_limit_infos"]);
		$trad["ecriture_infos"] = change_libelles_objets($cfg_menu_edit["objet"],$trad["ecriture_infos_conteneur"]);
	}

	////	FIELDSET DES AFFECTATIONS
	////
	echo "<fieldset class='pas_selection' style='margin-top:45px;'>";
		////	TITRE
		echo "<legend>".$trad["EDIT_OBJET_droit_acces"]."</legend>";

		////	LISTE DES ESPACES
		////
		$liste_espaces = espaces_affectes_user(null,true);
		foreach($liste_espaces as $cpt_espace => $espace_tmp)
		{
			////	DIV PRINCIPAL DE L'ESPACE
			$STYLE_BLOCK_espace = ($is_conteneur==true)  ?  "padding-left:20px;padding-right:10px;"  :  "padding-left:40px;padding-right:30px;";
			if($_SESSION["espace"]["id_espace"]!=$espace_tmp["id_espace"] && in_array($espace_tmp["id_espace"],$espaces_affectes)==false && $is_dossier_racine==false)	{$STYLE_BLOCK_espace .= "display:none;";}
			echo "<div style='padding:5px;".$STYLE_BLOCK_espace."' id='block_espace_".$cpt_espace."'>";

				////	NOM + ENTETE DE L'ESPACE (ICONES DES DROITS D'ACCES)
				////
				// Module en question activé ?
				if($espace_tmp["id_espace"]!=$_SESSION["espace"]["id_espace"]  &&  db_valeur("SELECT count(*) FROM gt_jointure_espace_module WHERE id_espace='".$espace_tmp["id_espace"]."' AND nom_module='".MODULE_NOM."' ")==0)
					{$espace_tmp["details"] = " &nbsp; <img src=\"".PATH_TPL."divers/important.png\" ".infobulle($trad["EDIT_OBJET_espace_pas_module"])." />";}
				// Retour à la ligne
				if($cpt_espace>0)	{echo "<hr style='margin:10px;'>";}
				// Entête du menu
				echo "<div class='table_ligne' style='cursor:help;' id='entete_espace_$cpt_espace'>";
					echo "<div class='cellule_lib' ".infobulle(nl2br($espace_tmp["description"])).">".$espace_tmp["nom"].@$espace_tmp["details"]."</div>";
					echo "<div class='cellule_input' ".infobulle($trad["lecture_infos"]).">".$trad["lecture"]." <img src=\"".PATH_TPL."divers/oeil.png\" /></div>";
					if($is_conteneur==true)  {echo "<div class='cellule_input2' ".infobulle($trad["ecriture_limit_infos"]).">".$trad["ecriture_limit"]." <img src=\"".PATH_TPL."divers/crayon2.png\" /></div>";}
					echo "<div class='cellule_input' ".infobulle($trad["ecriture_infos"]).">".$trad["ecriture"]." <img src=\"".PATH_TPL."divers/crayon.png\" /></div>";
				echo "</div>";

				////	DIV "OVERFLOW:AUTO" DE L'ESPACE
				echo "<div class='div_liste_users' id='body_espace_".$cpt_espace."'>";

				////	INVITES DE L'ESPACE (S'IL EST PUBLIC)
				////
				if($espace_tmp["public"]==1)
				{
					// DROITS D'ACCES ENREGISTRES
					$style_tmp = "objet_deselect";
					$check_1 = $check_15 = "";
					if(array_key_exists($espace_tmp["id_espace"], objet_affectations($cfg_menu_edit["objet"],$id_objet_affect,"invites",1.5)))		{ $check_15="checked";	 $style_tmp="objet_ecriture_limite"; }
					elseif(array_key_exists($espace_tmp["id_espace"], objet_affectations($cfg_menu_edit["objet"],$id_objet_affect,"invites",1)))	{ $check_1="checked";	 $style_tmp="objet_lecture"; }
					// DROITS PAR DEFAUT :  SI C'EST UN NOUVEL OBJET SUR CET ESPACE  OU  SI L'OBJET EST UN "DOSSIER RACINE" AVEC DES DROITS ENCORE INDEFINIS
					if(($id_objet_affect==0 && $espace_tmp["id_espace"]==$_SESSION["espace"]["id_espace"])  ||  ($is_dossier_racine==true && $style_tmp=="objet_deselect")){
						$espaces_affectes[] = $espace_tmp["id_espace"];
						$check_1="checked";
						$style_tmp="objet_lecture";
					}
					// AFFICHAGE
					$id_tmp = "E".$espace_tmp["id_espace"]."_invites";
					echo "<div class='table_ligne'>";
						echo "<div class='cellule_lib ".$style_tmp."' id='text_".$id_tmp."' onClick=\"selection_affect(this.id);\"><img src='".PATH_TPL."divers/point_jaune.png' class='puce_point' /> ".$trad["EDIT_OBJET_espace_invites"]." <img src=\"".PATH_TPL."divers/planete.png\" class='icone_public_groupe' /></div>";
						echo "<div class='cellule_input' title=\"".$trad["lecture_infos"]."\"><input type='checkbox' name='lecture_invites[]' id='lecture_".$id_tmp."' value='".$espace_tmp["id_espace"]."' onClick=\"selection_affect(this.id);\" ".$check_1." /></div>";
						if($is_conteneur==true)  {echo "<div class='cellule_input2' title=\"".$trad["ecriture_limit_infos"]."\"><input type='checkbox' name='ecriture_limit_invites[]' id='ecriture_limit_".$id_tmp."' value='".$espace_tmp["id_espace"]."' onClick=\"selection_affect(this.id);\" ".$check_15." /></div>";}
						echo "<div class='cellule_input'>&nbsp;</div>";
					echo "</div>";
				}

				////	TOUS LES UTILISATEURS DE L'ESPACE
				////
				// DROITS D'ACCES ENREGISTRES
				$style_tmp = "objet_deselect";
				$check_1 = $check_15 = $check_2 = "";
				if(array_key_exists($espace_tmp["id_espace"], objet_affectations($cfg_menu_edit["objet"],$id_objet_affect,"espaces",2)))		{ $check_2="checked";	 $style_tmp="objet_ecriture"; }
				elseif(array_key_exists($espace_tmp["id_espace"], objet_affectations($cfg_menu_edit["objet"],$id_objet_affect,"espaces",1.5)))	{ $check_15="checked";	 $style_tmp="objet_ecriture_limite"; }
				elseif(array_key_exists($espace_tmp["id_espace"], objet_affectations($cfg_menu_edit["objet"],$id_objet_affect,"espaces",1)))	{ $check_1="checked";	 $style_tmp="objet_lecture"; }
				// DROITS PAR DEFAUT :  SI C'EST UN NOUVEL OBJET SUR CET ESPACE  OU  SI L'OBJET EST UN "DOSSIER RACINE" AVEC DES DROITS ENCORE INDEFINIS
				if(($id_objet_affect==0 && $espace_tmp["id_espace"]==$_SESSION["espace"]["id_espace"])  ||  ($is_dossier_racine==true && $style_tmp=="objet_deselect"))
				{
					$espaces_affectes[] = $espace_tmp["id_espace"];
					if($cfg_menu_edit["ecriture_limite_defaut"]==true)		{ $check_15="checked";	$style_tmp="objet_ecriture_limite"; }	// SUJETS DU FORUM => 1.5
					elseif($is_conteneur==true || $is_dossier_racine==true)	{ $check_2="checked";	$style_tmp="objet_ecriture"; }			// CONTENEURS OU DOSSIER RACINE => 2
					else													{ $check_1="checked";	$style_tmp="objet_lecture"; }			// AUTRES => 1
				}
				// AFFICHAGE
				$id_tmp = "E".$espace_tmp["id_espace"]."_tous";
				echo "<div class='table_ligne'>";
					echo "<div class='cellule_lib ".$style_tmp."' id='text_".$id_tmp."' onClick=\"selection_affect(this.id);\"><img src=\"".PATH_TPL."divers/point_jaune.png\" class='puce_point' /> ".$trad["EDIT_OBJET_tous_utilisateurs"]."</div>";
					echo "<div class='cellule_input' title=\"".$trad["lecture_infos"]."\"><input type='checkbox' name='lecture_espaces[]' id='lecture_".$id_tmp."' value='".$espace_tmp["id_espace"]."' onClick=\"selection_affect(this.id);\" ".$check_1." /></div>";
					if($is_conteneur==true)  {echo "<div class='cellule_input2' title=\"".$trad["ecriture_limit_infos"]."\"><input type='checkbox' name='ecriture_limit_espaces[]' id='ecriture_limit_".$id_tmp."' value='".$espace_tmp["id_espace"]."' onClick=\"selection_affect(this.id);\" ".$check_15." /></div>";}
					echo "<div class='cellule_input' title=\"".$trad["ecriture_infos"]."\"><input type='checkbox' name='ecriture_espaces[]' id='ecriture_".$id_tmp."' value='".$espace_tmp["id_espace"]."' onClick=\"selection_affect(this.id);\" ".$check_2." /></div>";
				echo "</div>";

				////	GROUPES D'UTILISATEURS DE L'ESPACE
				////
				foreach(groupes_users($espace_tmp["id_espace"]) as $groupe_tmp)
				{
					// DROITS D'ACCES
					$style_tmp = "objet_deselect";
					$check_1 = $check_15 = $check_2 = "";
					foreach(objet_affectations($cfg_menu_edit["objet"],$id_objet_affect,"groupes",1) as $droit_tmp)		{  if($droit_tmp["id_espace"]==$espace_tmp["id_espace"] && $droit_tmp["id_groupe"]==$groupe_tmp["id_groupe"])  { $check_1="checked";  $style_tmp="objet_lecture"; }   }
					foreach(objet_affectations($cfg_menu_edit["objet"],$id_objet_affect,"groupes",1.5) as $droit_tmp)	{  if($droit_tmp["id_espace"]==$espace_tmp["id_espace"] && $droit_tmp["id_groupe"]==$groupe_tmp["id_groupe"])  { $check_15="checked"; $style_tmp="objet_ecriture_limite"; }   }
					foreach(objet_affectations($cfg_menu_edit["objet"],$id_objet_affect,"groupes",2) as $droit_tmp)		{  if($droit_tmp["id_espace"]==$espace_tmp["id_espace"] && $droit_tmp["id_groupe"]==$groupe_tmp["id_groupe"])  { $check_2="checked";  $style_tmp="objet_ecriture"; }   }
					// AFFICHAGE
					$id_tmp = "E".$espace_tmp["id_espace"]."_G".$groupe_tmp["id_groupe"];
					echo "<div class='table_ligne' ".infobulle($groupe_tmp["users_title"]).">";
						echo "<div class='cellule_lib ".$style_tmp."' id='text_".$id_tmp."' onClick=\"selection_affect(this.id);\"><img src=\"".PATH_TPL."divers/point_jaune.png\" class='puce_point' /> ".$groupe_tmp["titre"]." <img src=\"".PATH_TPL."module_utilisateurs/utilisateurs_groupe.png\" class='icone_public_groupe' /></div>";
						echo "<div class='cellule_input' title=\"".$trad["lecture_infos"]."\"><input type='checkbox' name='lecture_groupes[]' id='lecture_".$id_tmp."' value='".$id_tmp."' onClick=\"selection_affect(this.id);\" ".$check_1." /></div>";
						if($is_conteneur==true)  {echo "<div class='cellule_input2' title=\"".$trad["ecriture_limit_infos"]."\"><input type='checkbox' name='ecriture_limit_groupes[]' id='ecriture_limit_".$id_tmp."' value='".$id_tmp."' onClick=\"selection_affect(this.id);\" ".$check_15." /></div>";}
						echo "<div class='cellule_input' title=\"".$trad["ecriture_infos"]."\"><input type='checkbox' name='ecriture_groupes[]' id='ecriture_".$id_tmp."' value='".$id_tmp."' onClick=\"selection_affect(this.id);\" ".$check_2." /></div>";
					echo "</div>";
				}

				////	UTILISATEURS
				////
				foreach(users_espace($espace_tmp["id_espace"],"tableau") as $user_tmp)
				{
					// DROITS D'ACCES
					$style_tmp = "objet_deselect";
					$check_1 = $check_15 = $check_2 = "";
					foreach(objet_affectations($cfg_menu_edit["objet"],$id_objet_affect,"users",1) as $droit_tmp)		{  if($droit_tmp["id_espace"]==$espace_tmp["id_espace"] && $droit_tmp["id_utilisateur"]==$user_tmp["id_utilisateur"])  { $check_1="checked";  $style_tmp="objet_lecture"; }   }
					foreach(objet_affectations($cfg_menu_edit["objet"],$id_objet_affect,"users",1.5) as $droit_tmp)		{  if($droit_tmp["id_espace"]==$espace_tmp["id_espace"] && $droit_tmp["id_utilisateur"]==$user_tmp["id_utilisateur"])  { $check_15="checked"; $style_tmp="objet_ecriture_limite"; }   }
					foreach(objet_affectations($cfg_menu_edit["objet"],$id_objet_affect,"users",2) as $droit_tmp)		{  if($droit_tmp["id_espace"]==$espace_tmp["id_espace"] && $droit_tmp["id_utilisateur"]==$user_tmp["id_utilisateur"])  { $check_2="checked";  $style_tmp="objet_ecriture"; }   }
					// ADMINISTRATEUR ?
					$desactive_acces_limites = $admin_infobulle = "";
					if(droit_acces_espace($espace_tmp["id_espace"],$user_tmp)==2){
						$desactive_acces_limites = $style_disabled;
						$admin_infobulle = "title=\"".$trad["EDIT_OBJET_admin_espace"]."\"";
					}
					// AFFICHAGE
					$id_tmp = "E".$espace_tmp["id_espace"]."_U".$user_tmp["id_utilisateur"];
					$icone_point = ($user_tmp["id_utilisateur"]==$_SESSION["user"]["id_utilisateur"] || $admin_infobulle!="")  ?  "point_jaune.png"  :  "point_blanc.png";
					echo "<div class='table_ligne' ".$admin_infobulle.">";
						echo "<div class='cellule_lib ".$style_tmp."' id='text_".$id_tmp."' onClick=\"selection_affect(this.id);\"><img src=\"".PATH_TPL."divers/".$icone_point."\" class='puce_point' /> ".$user_tmp["prenom"]." ".$user_tmp["nom"]."</div>";
						echo "<div class='cellule_input' title=\"".$trad["lecture_infos"]."\"><input type='checkbox' name='lecture_users[]' id='lecture_".$id_tmp."' value='".$id_tmp."' onClick=\"selection_affect(this.id);\"  ".$check_1." ".$desactive_acces_limites." /></div>";
						if($is_conteneur==true)  {echo "<div class='cellule_input2' title=\"".$trad["ecriture_limit_infos"]."\"><input type='checkbox' name='ecriture_limit_users[]' id='ecriture_limit_".$id_tmp."' value='".$id_tmp."' onClick=\"selection_affect(this.id);\" ".$check_15." ".$desactive_acces_limites." /></div>";}
						echo "<div class='cellule_input' title=\"".$trad["ecriture_infos"]."\"><input type='checkbox' name='ecriture_users[]' id='ecriture_".$id_tmp."' value='".$id_tmp."' onClick=\"selection_affect(this.id);\"  ".$check_2." /></div>";
					echo "</div>";
				}

				////	FIN  ->  DIV "OVERFLOW:AUTO" DE L'ESPACE  &  DIV PRINCIPAL DE L'ESPACE
				echo "</div>";
			echo "</div>";
		}

		////	AFFECTATION A "TOUS LES ESPACE" (s'il y en a plusieurs et qu'on est admin général)
		////
		if(count($liste_espaces)>1 && $_SESSION["user"]["admin_general"]==1 && $is_dossier_racine==false)
		{
			// INIT
			$style_tmp = "objet_deselect";
			$check_1 = $check_15 = $check_2 = "";
			// DROITS D'ACCES
			if(count(objet_affectations($cfg_menu_edit["objet"],$id_objet_affect,"tous_espaces",1)) > 0)	{ $check_1="checked";	$style_tmp="objet_lecture"; }
			if(count(objet_affectations($cfg_menu_edit["objet"],$id_objet_affect,"tous_espaces",1.5)) > 0)	{ $check_15="checked";	$style_tmp="objet_ecriture_limite"; }
			if(count(objet_affectations($cfg_menu_edit["objet"],$id_objet_affect,"tous_espaces",2)) > 0)	{ $check_2="checked";	$style_tmp="objet_ecriture"; }
			$display_tous_espace = ($check_1=="" && $check_15=="" && $check_2=="")  ?  "display:none;"  :  "";
			// AFFICHAGE
			echo "<div style='".$display_tous_espace."' id='block_espaces'><hr style='margin:10px;' />";
				echo "<table class='table_nospace' cellpadding='0' cellspacing='0' style='width:95%;margin-left:20px;'><tr>";
					echo "<td class='".$style_tmp."' id='text_tous_espaces' onClick=\"selection_affect(this.id);\"><img src=\"".PATH_TPL."divers/espaces.png\" /> <b>".$trad["EDIT_OBJET_tous_utilisateurs_espaces"]."</b></td>";
					echo "<td style='width:110px;' title=\"".$trad["lecture_infos"]."\"><input type='checkbox' name='lecture_tous_espaces' value='1' id='lecture_tous_espaces' onClick=\"selection_affect(this.id);\"  ".$check_1." /> ".$trad["lecture"]." <img src=\"".PATH_TPL."divers/oeil.png\" /></td>";
					if($is_conteneur==true)  {echo "<td style='width:140px;' title=\"".$trad["ecriture_limit_infos"]."\"><input type='checkbox' name='ecriture_limit_tous_espaces' value='1' id='ecriture_limit_tous_espaces' onClick=\"selection_affect(this.id);\"  ".$check_15." /> ".$trad["ecriture_limit"]." <img src=\"".PATH_TPL."divers/crayon2.png\" /></td>";}
					echo "<td style='width:110px;' title=\"".$trad["ecriture_infos"]."\"><input type='checkbox' name='ecriture_tous_espaces' value='1' id='ecriture_tous_espaces' onClick=\"selection_affect(this.id);\"  ".$check_2." /> ".$trad["ecriture"]." <img src=\"".PATH_TPL."divers/crayon.png\" /></td>";
				echo "</tr></table><br>";
			echo "</div>";
		}

		////	OPTION "AFFICHER TOUS LES ESPACES"
		if(count($liste_espaces)>1 && (count($liste_espaces)>count($espaces_affectes) || @$display_tous_espace!=""))
			{echo "<div class='lien' style='text-align:center;margin-top:15px;' onClick=\"espaces_display('".count($liste_espaces)."');\"><span id='derouler_espaces'></span> <img src=\"".PATH_TPL."divers/derouler.png\" /></div>";}
		////	DROITS D'ACCES DES SOUS DOSSIERS
		if(preg_match("/dossier/i",$cfg_menu_edit["objet"]["type_objet"]) && $cfg_menu_edit["id_objet"]>0)
			{echo "<div style='float:right;margin-top:15px;'><span class='lien' id='txt_ssdossier' onClick=\"checkbox_text(this);\"> ".$trad["EDIT_OBJET_droits_ss_dossiers"]."</span><input type='checkbox' name='droits_ss_dossiers' value='1' id='box_ssdossier' onClick=\"checkbox_text(this.id,'ssdossier');\" /></div>";}

		////	INFOS EN PLUS ?
		if(isset($cfg_menu_edit["infos_droits_acces"]))		{echo "<hr /><div style='text-align:center;".STYLE_FONT_COLOR_RETRAIT."'><img src=\"".PATH_TPL."divers/important.png\" id='infos_droits_acces' /> &nbsp; ".$cfg_menu_edit["infos_droits_acces"]."</div>";}

	////	FIN DU MENU
	echo "</fieldset>";
	////	INITIALISE L'AFFICHAGE DES BLOCK D'ESPACES
	echo "<script> $(document).ready(function(){  espaces_display('".count($liste_espaces)."',true);  });  $('#derouler_espaces').effect('pulsate',{times:3},3000); </script>";
}




////	INIT DES OPTIONS
////
$block_options_edit = $details_options_edit = "";

////	RACCOURCIS
////
if($_SESSION["user"]["id_utilisateur"]>0  &&  db_maj_champ_ajoute(null,$cfg_menu_edit["objet"]["table_objet"],"raccourci")==true  &&  $cfg_menu_edit["raccourci"]==true  &&  $is_dossier_racine==false)
{
	$raccourcis_selected = objet_infos($cfg_menu_edit["objet"],$cfg_menu_edit["id_objet"],"raccourci");
	$block_options_edit .= "<input type='checkbox' name='raccourci' value='1' id='box_raccourci' style='visibility:hidden;' ".($raccourcis_selected=="1"?"checked":"")." />";
	$block_options_edit .= "<img src=\"".PATH_TPL."divers/raccourci.png\" />&nbsp;<span id='txt_raccourci' onClick=\"checkbox_text(this);\" class='".($raccourcis_selected=="1"?"lien_select":"lien")."' ".infobulle($trad["EDIT_OBJET_raccourci_info"]).">".$trad["EDIT_OBJET_raccourci"]."</span> &nbsp; &nbsp; &nbsp; ";
}

////	NOTIFICATION PAR MAIL  (selection destinataires possible?)
////
if($_SESSION["user"]["id_utilisateur"]>0  &&  $cfg_menu_edit["notif_mail"]==true  &&  function_exists("mail")==true)
{
	////	Bouton principal
	$block_options_edit .= "<input type='checkbox' name='notification' value='1' id='box_notif' style='visibility:hidden;' />";
	$block_options_edit .= "<img src=\"".PATH_TPL."divers/envoi_mail.png\" />&nbsp;<span id='txt_notif' onClick=\"checkbox_text(this);\" class='lien' ".infobulle($trad["EDIT_OBJET_notif_mail_info"]).">".$trad["EDIT_OBJET_notif_mail"]."</span> &nbsp;";
	$block_options_edit .= "<img src=\"".PATH_TPL."divers/derouler.png\" onclick=\"afficher('div_notif_options','bascule','block',true);redir('#bottom_dest');\" class='lien' ".infobulle($trad["EDIT_OBJET_notif_mail_selection"])." /> &nbsp; &nbsp; &nbsp; ";
	////	Menu detaille (utilisateurs & co)
	$cpt_user_notif = 1;
	$users_espace = users_espace($_SESSION["espace"]["id_espace"]);
	$users_visibles = users_visibles($_SESSION["user"],false,true);
	$details_options_edit .= "<div id='div_notif_options' class='div_options' style='float:left; width:250px;'>";
	// Envoie les fichiers (gestionnaire de fichiers) dans la notification ?
	if($cfg_menu_edit["objet"]["type_objet"]=="fichier")
		{$details_options_edit .= "<input type='checkbox' name='notif_joindre_fichiers' value='1' onClick=\"set_check('notification',true);element('txt_notif').className='lien_select';\" />&nbsp; ".$trad["EDIT_OBJET_notif_mail_joindre_fichiers"]."<hr>";}
	// Users de l'espace  (et masque les autres utilisateurs visibles sur tous les espaces)
	foreach($users_visibles as $user_tmp){
		$details_options_edit .= "<div style='".(in_array($user_tmp["id_utilisateur"],$users_espace)?"":"display:none;")."' id='notif_user_".$cpt_user_notif."' title=\"".$user_tmp["mail"]."\"><input type='checkbox' name='notif_destinataires[]' value='".$user_tmp["id_utilisateur"]."' onClick=\"set_check('notification',true);element('txt_notif').className='lien_select';\">&nbsp; ".$user_tmp["prenom"]." ".$user_tmp["nom"]."</div>";
		$cpt_user_notif ++;
	}
	// Affiche tous les utilisateurs visible ?
	if(count($users_espace)<count($users_visibles))		{$details_options_edit .= "<div style='text-align:right;margin-top:10px;'><a href=\"javascript:notif_more_users(".count($users_visibles).");\" id='bouton_notif_more_users'>".$trad["EDIT_OBJET_notif_tous_users"]."</a></div>";}
	$details_options_edit .= "</div><a name='bottom_dest'></a>";
}

////	FICHIERS JOINTS
////
if(@$cfg_menu_edit["fichiers_joint"]==true)
{
	////	Bouton principal
	$block_options_edit .= "<img src=\"".PATH_TPL."divers/fichier_joint.png\" />&nbsp;<span onclick=\"afficher('div_fichiers_joints','bascule','block',true);redir('#bottom_fichier');\" class='lien'>".$trad["EDIT_OBJET_fichier_joint"]."</span>";
	////	Menu
	$fichiers_joints = db_tableau("SELECT * FROM gt_jointure_objet_fichier WHERE type_objet='".$cfg_menu_edit["objet"]["type_objet"]."' AND id_objet='".$cfg_menu_edit["id_objet"]."' ORDER BY nom_fichier");
	$details_options_edit .= "<div id='div_fichiers_joints' class='div_options' style='float:left;".(count($fichiers_joints)>0?"display:block;":"")."' >";
	////	Inputs d'ajout de fichiers + ajoute dans le textarea (si possible)
	for($i=1; $i<=$nb_fichiers_max; $i++){
		$details_options_edit .= "<div id='div_fichier_joint".$i."'  ".($i>1?"class='cacher'":"")."><input type='file' name='add_fichier_joint".$i."' onChange=\"affiche_fichiers(".$i.");\" title=\"".libelle_upload_max_filesize()."\" /> &nbsp; ";
		$details_options_edit .= "<span id='txt_add_fichier_joint".$i."' onClick=\"checkbox_text(this);\" title=\"".$trad["EDIT_OBJET_inserer_fichier_info"]."\" class='lien' style='display:none;'><img src=\"".PATH_TPL."divers/fleche_droite.png\" /> ".$trad["EDIT_OBJET_inserer_fichier"]." <img src=\"".PATH_TPL."divers/message_insert.png\" /></span><input type='checkbox' name='tab_add_fichier_joint[]' value='add_fichier_joint".$i."' id='box_add_fichier_joint".$i."' style='visibility:hidden;' /></div>";
	}
	////	fichiers joints existants
	if(count($fichiers_joints)>0)	{$details_options_edit .= "<hr style='margin:10px;' />";}
	foreach($fichiers_joints as $fichier_tmp)
	{
		$details_options_edit .= "<div style='margin:5px' id='fichier_joint".$fichier_tmp["id_fichier"]."'>";
		$details_options_edit .= "<i>".majuscule($fichier_tmp["nom_fichier"])."</i> &nbsp; &nbsp;<img src=\"".PATH_TPL."divers/supprimer.png\" class='lien' height='15px' onClick=\"suppr_fichier_joint('".$fichier_tmp["id_fichier"]."');\" ".infobulle($trad["supprimer"])." /> &nbsp; &nbsp;";
		if(controle_fichier("fichier_joint",$fichier_tmp["nom_fichier"])==true && isset($_GET["textarea_name"]))	{$details_options_edit .= "<img src=\"".PATH_TPL."divers/message_insert.png\" ".insert_fichier_joint($fichier_tmp)." ".infobulle($trad["EDIT_OBJET_inserer_fichier_info"])." class='lien' style='height:18px;' />";}
		$details_options_edit .= "</div>";
	}
	$details_options_edit .= "</div>";
	$block_options_edit .= "<a name='bottom_fichier'></a>";
}

////	AFFICHAGE DES OPTIONS ?
////
if($block_options_edit!="")	{echo "<div id='menu_edit_objet_options pas_selection' style='margin-top:20px;'>".$block_options_edit."<hr style='visibility:hidden;margin:0px;' />".$details_options_edit."</div>";}