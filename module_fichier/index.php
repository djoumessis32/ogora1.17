<?php
////	INIT
define("IS_MAIN_PAGE",true);
require "commun.inc.php";
require PATH_INC."header_menu.inc.php";
init_id_dossier();
elements_width_height_type_affichage("small","110px","bloc");


////	DIVERS CONTROLES AUTO (ADMIN GE)
////
////	Dossier racine existe?
if($_SESSION["user"]["admin_general"]==1 && $_GET["id_dossier"]==1){
	nettoyer_tmp();
	if(is_dir(PATH_MOD_FICHIER)==false)	{ mkdir(PATH_MOD_FICHIER);  @chmod(PATH_MOD_FICHIER,0775); }
}
////	Dossier accessible en écriture?
$droit_acces_dossier = droit_acces_controler($objet["fichier_dossier"], $_GET["id_dossier"], 1);
$chemin_dossier_courant = PATH_MOD_FICHIER.chemin($objet["fichier_dossier"],$_GET["id_dossier"],"url");
if(!is_writable(PATH_STOCK_FICHIERS) && $_SESSION["user"]["admin_general"]==1)		{ alert($trad["MSG_ALERTE_chmod_stock_fichiers"]); }
elseif(!is_writable($chemin_dossier_courant) &&  $droit_acces_dossier>1)			{ alert($trad["FICHIER_ajouter_fichier_alert"]." (id_dossier=".$_GET["id_dossier"].")"); }


////	LISTE DES FICHIERS + PREPARATION DU SCROLLER D'IMAGES ET VIDEOS
////
$liste_fichiers = db_tableau("SELECT * FROM gt_fichier WHERE id_dossier='".intval($_GET["id_dossier"])."'  ".sql_affichage($objet["fichier"],$_GET["id_dossier"])."  ".tri_sql($objet["fichier"]["tri"]));
$_SESSION["cfg"]["espace"]["scroller_images"] = array();
foreach($liste_fichiers as $fichier_tmp)	{ if(controle_fichier("image_browser",$fichier_tmp["nom"])==true) {$_SESSION["cfg"]["espace"]["scroller_images"][]=$fichier_tmp;} }
$_SESSION["cfg"]["espace"]["scroller_videos"] = array();
foreach($liste_fichiers as $fichier_tmp)	{ if(controle_fichier("video_browser",$fichier_tmp["nom"])==true) {$_SESSION["cfg"]["espace"]["scroller_videos"][]=$fichier_tmp;} }
?>


<style>
.icone_fichier			{ <?php echo "max-height:".$height_element.";";   if($_REQUEST["type_affichage"]=="bloc"){echo "margin-top:15px;";} ?> }
.lien_telecharger		{ cursor:url('<?php echo PATH_TPL; ?>divers/telecharger.png'),pointer; }
.lien_infobulle			{ color:#f55; line-height:13px; }
.icone_pdf				{ position:absolute; margin-left:<?php echo $width_element-20;?>px; }
.div_mp3				{ position:absolute; margin-left:30px; }
.div_titre_fichier		{ position:absolute; z-index:100; display:table; <?php echo STYLE_BACKGROUND_LIB_FICHIER;?> }
.div_titre_fichier2		{ margin-top:4px;margin-bottom:4px;text-align:center; }
.icone_fichier_liste	{ text-align:center; width:80px; }
.page_fantome			{ display:none; position:fixed; z-index:100000; top:0px; left:0px; width:100%; height:100%; text-align:center; vertical-align:middle; color:#fff; <?php echo STYLE_FOND_OPAQUE; ?> }
.page_fantome_fermer	{ position:absolute; top:0px; right:0px; padding:5px; margin:-3px; margin-right:-8px; font-style:italic; font-size:13px; }
.page_fantome_table		{ display:table; width:100%; height:100%; text-align:center; vertical-align:middle; padding:0px; margin:0px; }
</style>


<script type="text/javascript">
////	AFFICHER UNE IFRAME DANS LA PAGE FANTOME
////
function iframe_page_fantome(url, page_width)
{
	// Affiche la page fantome
	afficher("page_fantome",true);
	// Nouvelle URL -> on charge l'iframe !
	url_old_src = element("page_fantome_iframe").src.substring(element("page_fantome_iframe").src.lastIndexOf("/")+1);
	url_src = url.substring(url.lastIndexOf("/")+1);
	if(url_old_src=="" || url_old_src!=url_src)
	{
		// Largeur de la page
		if(page_width==undefined || page_width==null)	page_width = "0%";
		element("page_fantome_iframe").style.width = page_width;
		// Affichage pleine page, sans marges
		if(page_width=="100%"){
			element("page_fantome_iframe").style.margin = "0px";
			element("page_fantome_iframe").style.height = element("page_fantome").offsetHeight + "px";
		}
		// Affichage avec marges
		else{
			element("page_fantome_iframe").style.margin = "30px";
			element("page_fantome_iframe").style.height = (element("page_fantome").offsetHeight - 60) + "px";
		}
		// Charge L'iframe
		element("page_fantome_contenu").innerHTML = "";
		element("page_fantome_iframe").src = url;
	}
	// On masque la barre de scroll de la page principale
	document.body.style.overflow = "hidden";
}

////	MASQUER L'IFRAME DE LA PAGE FANTOME
////
function page_fantome_close()
{
	element('page_fantome_contenu').innerHTML = "";
	if(trouver("video",element("page_fantome_iframe").src)==true)	element("page_fantome_iframe").src = "";	// réinitialise pour ne pas faire tourner la video en fond..
	afficher('page_fantome',false);
	document.body.style.overflow = "auto";
}

////	Affiche les images dans une iframe
function afficher_images(id_fichier)
{
	iframe_page_fantome("images.php?id_dossier=<?php echo $_GET["id_dossier"]; ?>&id_fichier="+id_fichier, '100%');
}

////	Affiche les videos dans une iframe
function afficher_videos(id_fichier)
{
	iframe_page_fantome("video.php?id_dossier=<?php echo $_GET["id_dossier"]; ?>&id_fichier="+id_fichier, '100%');
}

////	Telechargement des fichiers : tout le dossier courant OU juste la sélection
function telecharger_fichiers(dossier_courant)
{
	//Dossier courant / + de 20 elements : demande de confirmation!
	if((dossier_courant==true || nb_elements_select(false)>20)  &&  !confirm("<?php echo $trad["FICHIER_telecharger_dossier_confirm"]; ?>"))	{return false;}
	//Télécharge?
	if(dossier_courant==true)				{window.open("telecharger_archive.php?SelectedElems[fichier_dossier]=<?php echo $_GET["id_dossier"]; ?>");}
	else if(nb_elements_select(false)>0)	{window.open('telecharger_archive.php?'+SelectedElems());}
}

////	Défilement des "Images.php" à partir du clavier (precedante / suivante / rotation gauche / rotation droite)
$(window).keypress(function(event) {
	if(trouver("images.php", window.parent.element("page_fantome_iframe").src))
	{
		iframe_img = window.parent.page_fantome_iframe;
		if(event.keyCode==37)						iframe_img.affiche_img(iframe_img.element("id_img_pre").value);
		if(event.keyCode==39 || event.charCode==32)	iframe_img.affiche_img(iframe_img.element("id_img_suiv").value);
		if(event.keyCode==38)						iframe_img.affiche_img(iframe_img.element("id_fichier").value, 0, iframe_img.element("rotation_gauche").value);
		if(event.keyCode==40)						iframe_img.affiche_img(iframe_img.element("id_fichier").value, 0, iframe_img.element("rotation_droite").value);
	}
});
</script>


<div id="page_fantome" class="page_fantome">
	<button onClick="page_fantome_close();" id="page_fantome_fermer" class="button page_fantome_fermer"><?php echo $trad["fermer"]; ?> <img src="<?php echo PATH_TPL; ?>divers/supprimer.png" /></button>
	<div class="page_fantome_table">
		<div id="page_fantome_contenu"></div>
		<iframe id="page_fantome_iframe" name="page_fantome_iframe" allowtransparency="true" frameborder="0">NO IFRAME</iframe>
	</div>
</div>


<table id="contenu_principal_table"><tr>
	<td id="menu_gauche_block_td">
		<div id="menu_gauche_block_flottant">
			<div class="menu_gauche_block content">
				<?php
				////	MENU D'ARBORESCENCE
				$cfg_menu_arbo = array("objet"=>$objet["fichier_dossier"], "id_objet"=>$_GET["id_dossier"], "ajouter_dossier"=>true, "droit_acces_dossier"=>$droit_acces_dossier);
				require_once PATH_INC."menu_arborescence.inc.php";
				?>
			</div>
			<div class="menu_gauche_block content">
				<?php
				////	AJOUTER FICHIER  /  LANCER DIAPORAMA  /  VOIR VIDEOS  /  TELECHARGER LE DOSSIER COURANT
				if($droit_acces_dossier>=1.5)	{echo "<div class='menu_gauche_line lien' onclick=\"popupLightbox('fichier_edit_ajouter.php?id_dossier=".$_GET["id_dossier"]."');\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/ajouter.png\" /></div><div class='menu_gauche_txt'>".$trad["FICHIER_ajouter_fichier"]."</div></div>";}
				if(count($_SESSION["cfg"]["espace"]["scroller_images"])>1)	{echo "<div class='menu_gauche_line lien' onclick=\"afficher_images();\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."module_fichier/diaporama.png\" /></div><div class='menu_gauche_txt'>".$trad["FICHIER_voir_images"]."</div></div>";}
				if(count($_SESSION["cfg"]["espace"]["scroller_videos"])>0)	{echo "<div class='menu_gauche_line lien' onclick=\"afficher_videos(".$_SESSION["cfg"]["espace"]["scroller_videos"][0]["id_fichier"].");\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."module_fichier/videorama.png\" /></div><div class='menu_gauche_txt'>".$trad["FICHIER_voir_videos"]."</div></div>";}
				if(count($liste_fichiers)>0 && !is_dossier_racine($objet["fichier_dossier"],$_GET["id_dossier"]))	{echo "<div class='menu_gauche_line lien' onClick=\"telecharger_fichiers(true);\"><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/telecharger.png\" /></div><div class='menu_gauche_txt'>".$trad["FICHIER_telecharger_dossier"]."</div></div>";}
				echo "<hr />";
				////	MENU ELEMENTS
				$cfg_menu_elements = array("objet"=>$objet["fichier"], "objet_dossier"=>$objet["fichier_dossier"], "id_objet_dossier"=>$_GET["id_dossier"], "droit_acces_dossier"=>$droit_acces_dossier);
				require PATH_INC."elements_menu_selection.inc.php";
				////	MENU D'AFFICHAGE  &  DE TRI  &  CONTENU DU DOSSIER
				echo menu_type_affichage();
				echo menu_tri($objet["fichier"]["tri"]);
				echo contenu_dossier($objet["fichier_dossier"],$_GET["id_dossier"]);
				////	JAUGE D'OCCUPATION DE L'ESPACE DISQUE
				if($_SESSION["user"]["admin_general"]==1)
				{
					$espace_disque_utilise = dossier_taille(PATH_MOD_FICHIER);
					$taux_remplissage = ceil(($espace_disque_utilise/limite_espace_disque)*100);
					$text_barre = $trad["espace_disque_utilise"]." : ".$taux_remplissage."%";
					$text_barre_infobulle = $trad["espace_disque_utilise_mod_fichier"]." : ".afficher_taille($espace_disque_utilise)." ".$trad["de"]." ".afficher_taille(limite_espace_disque);
					$couleur_barre = ($taux_remplissage>80)  ?  "rouge"  :  "verte";
					echo "<div class='menu_gauche_line'><div class='menu_gauche_img'><img src=\"".PATH_TPL."divers/".$image_espace_disque.".png\" /></div><div class='menu_gauche_txt'>".status_bar($taux_remplissage,$text_barre,$text_barre_infobulle,$couleur_barre,"width:150px;","border:solid 1px #ccc;")."</div></div>";
				}
				?>
			</div>
		</div>
	</td>
	<td>
		<?php
		////	MENU CHEMIN + OBJETS_DOSSIERS + TAILLE ICONES
		////
		echo menu_chemin($objet["fichier_dossier"], $_GET["id_dossier"]);
		$cfg_dossiers = array("objet"=>$objet["fichier_dossier"], "id_objet"=>$_GET["id_dossier"], "largeur_icone"=>"80px");
		require_once PATH_INC."dossiers.inc.php";

		////	AFFICHAGE DES FICHIERS
		////
		foreach($liste_fichiers as $fichier_tmp)
		{
			////	INFOS  +  MODIF  +  SUPPR  +  ICONE "PLUS" EN POSITION "ABSOLUTE" ? (VIGNETTE PEUT ALORS PRENDRE TOUTE LA TAILLE DU BLOCK)
			$nb_versions = db_valeur("SELECT count(*) FROM gt_fichier_version WHERE id_fichier='".$fichier_tmp["id_fichier"]."'");
			$cfg_menu_elem = array("objet"=>$objet["fichier"], "objet_infos"=>$fichier_tmp, "fichiers_joint"=>false);
			if($_REQUEST["type_affichage"]=="bloc")		{$cfg_menu_elem["icone_plus_position_absolute"] = true;}
			if($fichier_tmp["vignette"]!="")			{$cfg_menu_elem["select_deselect"] = true;}
			$fichier_tmp["droit_acces"] = droit_acces($objet["fichier"],$fichier_tmp);//ne jamais prendre les droits du dossier!
			if($fichier_tmp["droit_acces"]>=2){
				$cfg_menu_elem["modif"] = "fichier_edit.php?id_fichier=".$fichier_tmp["id_fichier"];
				$cfg_menu_elem["deplacer"] = PATH_DIVERS."deplacer.php?module_path=".MODULE_PATH."&type_objet_dossier=fichier_dossier&id_dossier_parent=".$_GET["id_dossier"]."&SelectedElems[fichier]=".$fichier_tmp["id_fichier"];
				$cfg_menu_elem["suppr"] = "elements_suppr.php?id_fichier=".$fichier_tmp["id_fichier"]."&id_dossier_retour=".$_GET["id_dossier"];
				$cfg_menu_elem["options_divers"][] = array("icone_src"=>PATH_TPL."divers/ajouter.png", "text"=>$trad["FICHIER_ajouter_versions_fichier"], "action_js"=>"popupLightbox('fichier_edit_ajouter.php?id_dossier=".$_GET["id_dossier"]."&id_fichier_version=".$fichier_tmp["id_fichier"]."');");
			}
			////	TAILLE OCTETS  +  FICHIER IMAGE
			$fichier_tmp["afficher_taille"] = afficher_taille($fichier_tmp["taille_octet"]);
			if(controle_fichier("image_browser",$fichier_tmp["nom"])){
				$infos_dernier_fichier = infos_version_fichier($fichier_tmp["id_fichier"]);
				list($width_img,$height_img) = @getimagesize($chemin_dossier_courant.$infos_dernier_fichier["nom_reel"]);
				$fichier_tmp["resolution"] = $width_img." x ".$height_img." ".$trad["FICHIER_pixels"];
				$fichier_tmp["horizontal_vertical"] = ($width_img > $height_img)  ?  "H"  :  "V";
			}
			////	INFOBULLES DES DETAILS DU FICHIER
			$txt_infobulle = "";
			if($fichier_tmp["description"]!="") 	{$txt_infobulle .= "<div>".nl2br($fichier_tmp["description"])."</div>";}
			if(isset($fichier_tmp["resolution"]))	{$txt_infobulle .= "<div>".$fichier_tmp["resolution"]."</div>";}
			$txt_infobulle .= "<div>".$fichier_tmp["afficher_taille"]."</div>";
			////	ICONE  +  LIEN DU FICHIER (visionneuse images ou video / popup / download)
			if($_REQUEST["type_affichage"]=="liste")			{$style_height_width = "height:".$height_element;}
			elseif(@$fichier_tmp["horizontal_vertical"]=="V")	{$style_height_width = "max-height:".$height_element;}
			else												{$style_height_width = "max-width:".$width_element;}
			//lien et icone du fichier
			$lien_telecharger = "<a onClick=\"window.open('telecharger.php?id_fichier=".$fichier_tmp["id_fichier"]."');stopPropager(event);\" class='lien_telecharger'  ".infobulle("<div class='lien_infobulle'>".$trad["telecharger"]." <i><br>".$fichier_tmp["nom"]."</i></div>".$txt_infobulle)." >";
			if($fichier_tmp["vignette"]!="" && !controle_fichier("pdf",$fichier_tmp["nom"]))	{$lien_icone_fichier = "<a onClick=\"afficher_images('".$fichier_tmp["id_fichier"]."');stopPropager(event);\" class='lien_loupe' ".infobulle("<div class='lien_infobulle'>".$trad["FICHIER_apercu"]."</div>".$txt_infobulle).">";}
			elseif(controle_fichier("video_browser",$fichier_tmp["nom"]))						{$lien_icone_fichier = "<a onClick=\"afficher_videos('".$fichier_tmp["id_fichier"]."');stopPropager(event);\" class='lien_loupe' ".infobulle("<div class='lien_infobulle'>".$trad["FICHIER_regarder"]."</div>".$txt_infobulle).">";}
			elseif(controle_fichier("fichier_browser",$fichier_tmp["nom"]))						{$lien_icone_fichier = "<a onClick=\"popupLightbox('afficher_fichier.php?id_fichier=".$fichier_tmp["id_fichier"]."&typeFichier=".extension($fichier_tmp["nom"])."');stopPropager(event);\" class='lien_loupe' ".infobulle("<div class='lien_infobulle'>".$trad["FICHIER_apercu"]."</div>".$txt_infobulle).">";}//"typeFichier" pour afficher spécifiquement les pdf dans le lightBox
			else																				{$lien_icone_fichier = $lien_telecharger;}
			//On ajoute l'image au lien
			if($fichier_tmp["vignette"]!="")	{$lien_icone_fichier .= "<img src=\"".PATH_MOD_FICHIER2.$fichier_tmp["vignette"]."\" style='".$style_height_width."' /></a>";}
			else								{$lien_icone_fichier .= "<img src='".PATH_TPL."module_fichier/type_fichier/".image_fichier($fichier_tmp["nom"]).".png' class='icone_fichier' /></a>";}
			////	LECTEUR MP3 (fichier de moins de 15Mo)
			if(controle_fichier("mp3",$fichier_tmp["nom"])==true && $fichier_tmp["taille_octet"]<15360000)
			{$fichier_tmp["lecteur_mp3"] = "<object type='application/x-shockwave-flash' data=\"".PATH_COMMUN."dewplayer-mini.swf?mp3=telecharger.php%3Fid_fichier%3D".$fichier_tmp["id_fichier"]."\" width='180px' height='18px'><param name='wmode' value='transparent' /><param name='movie' value=\"".PATH_COMMUN."dewplayer-mini.swf?mp3=telecharger.php%3Fid_fichier%3D".$fichier_tmp["id_fichier"]."\" /></object>";}


			////	DIV SELECTIONNABLE + OPTIONS
			$cfg_menu_elem["id_div_element"] = div_element($objet["fichier"],$fichier_tmp["id_fichier"]);
			require PATH_INC."element_menu_contextuel.inc.php";
			////	AFFICHAGE BLOCK
			if($_REQUEST["type_affichage"]=="bloc")
			{
				////	AFFICHE L'ICONE "PDF" / DU LECTEUR MP3 ?
				if($fichier_tmp["vignette"]!="" && controle_fichier("pdf",$fichier_tmp["nom"]))  {echo "<img src=\"".PATH_TPL."module_fichier/type_fichier/pdf2.png\" class='icone_pdf' />";}
				if(isset($fichier_tmp["lecteur_mp3"]))	{echo "<div class='div_mp3'>".$fichier_tmp["lecteur_mp3"]."</div>";}
				////	LIBELLE DU FICHIER (placé en bas)  +  NB VERSION ?
				$versions_tmp = ($nb_versions>1)  ?  "<img src=\"".PATH_TPL."module_fichier/versions.png\" onclick=\"popupLightbox('versions_fichier.php?id_fichier=".$fichier_tmp["id_fichier"]."');stopPropager(event);\" class='lien' ".infobulle($nb_versions." ".$trad["FICHIER_nb_versions_fichier"])." /> &nbsp;"  :  "";
				echo "<div id='titre_fichier_".$fichier_tmp["id_fichier"]."' class='div_titre_fichier'><div class='div_titre_fichier2'>".$versions_tmp.$lien_telecharger.nom_fichier_reduit($fichier_tmp["nom"])."</a></div></div>";
				echo "<script> div_bas_conteneur('".$cfg_menu_elem["id_div_element"]."','titre_fichier_".$fichier_tmp["id_fichier"]."',true); </script>";
				////	IMAGE / ICONE DU FICHIER
				echo "<div id='img_fichier_".$fichier_tmp["id_fichier"]."' style='overflow:hidden;text-align:".(empty($fichier_tmp["vignette"])?"center":"right")."'>".$lien_icone_fichier."</div>";
				echo "<script> div_taille_conteneur('".$cfg_menu_elem["id_div_element"]."','img_fichier_".$fichier_tmp["id_fichier"]."'); </script>";
			}
			////	AFFICHAGE LISTE
			else
			{
				////	NB DE VERSIONS  +  LECTEUR MP3
				$versions_tmp = ($nb_versions>1)  ?  "<a onClick=\"popupLightbox('versions_fichier.php?id_fichier=".$fichier_tmp["id_fichier"]."');stopPropager(event);\">".$nb_versions." ".$trad["FICHIER_nb_versions_fichier"]." &nbsp; <img src=\"".PATH_TPL."module_fichier/versions.png\" /></a>&nbsp;<img src=\"".PATH_TPL."divers/separateur.gif\" />"  :  "";
				$lecteur_mp3 = (isset($fichier_tmp["lecteur_mp3"]))  ?  $fichier_tmp["lecteur_mp3"]."<img src=\"".PATH_TPL."divers/separateur.gif\" />"  :  "";
				////	ICONE FICHIER + NOM
				echo "<div class='div_elem_contenu' >";
					echo "<table class='div_elem_table'><tr>";
						echo "<td class='icone_fichier_liste'>".$lien_icone_fichier."</td>";
						echo "<td class='div_elem_td'>".$lien_telecharger.$fichier_tmp["nom"]."</a></td>";
					echo "<td class='div_elem_td div_elem_td_right'>".$lecteur_mp3.$versions_tmp.$fichier_tmp["afficher_taille"]." <img src=\"".PATH_TPL."divers/separateur.gif\" /> ".$cfg_menu_elem["auteur_tmp"]." <img src=\"".PATH_TPL."divers/separateur.gif\" /> ".temps($fichier_tmp["date_crea"],"date")."</td>";
					echo "</tr></table>";
				echo "</div>";
			}
			echo "</div>";
		}
		////	AUCUN FICHIER
		if(@$cpt_div_element<1)  {echo "<div class='div_elem_aucun'>".$trad["FICHIER_aucun_fichier"]."</div>";}
		?>
	</td>
</tr></table>


<?php require PATH_INC."footer.inc.php"; ?>