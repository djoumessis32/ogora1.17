<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";
droit_acces_controler($objet["fichier_dossier"], $_GET["id_dossier"], 1.5);
$_SESSION["dossier_tmp_upload"] = mt_rand(111111111,999999999);

////	ESPACE DISQUE SUFFISANT ?  DOSSIER DE DESTINATION ACCESSIBLE ?
if(taille_stock_fichier() > limite_espace_disque)											{ alert($trad["MSG_ALERTE_espace_disque"]);  reload_close(); }
if(!is_dir(PATH_MOD_FICHIER.chemin($objet["fichier_dossier"],$_GET["id_dossier"],"url")))	{ alert($trad["MSG_ALERTE_acces_dossier"]);  reload_close(); }
?>


<link rel="stylesheet" href="upload/jquery.plupload.queue.css" type="text/css" media="screen" />
<script type="text/javascript" src="upload/plupload.js"></script>
<script type="text/javascript" src="upload/jquery.plupload.queue.js"></script>
<script type="text/javascript" src="upload/plupload.html5.js"></script>
<script type="text/javascript" src="upload/plupload.flash.js"></script>


<script type="text/javascript">
////	INIT POPUP
resizePopupLightbox(600,670);

////	CHANGE LE TYPE D'UPLOAD
function select_form_upload(type_selection)
{
	if(type_selection=="plupload")	{ afficher("plupload_inputs",true);		afficher("simple_inputs",false); }
	else							{ afficher("plupload_inputs",false);	afficher("simple_inputs",true); }
	set_value("type_selection", type_selection);
}

////	REDIMENSION D'IMAGE ?
function option_optimiser_image(nom_fichier)
{
	if(extension(nom_fichier)=="jpg" || extension(nom_fichier)=="jpeg" || extension(nom_fichier)=="png")
		{afficher("div_optimiser", true, "block");}
}

////    AJOUT D'UN FICHIER VIA FORMULAIRE SIMPLE (nouvel input + optimiser image?)
function ajouter_fichier(nom_fichier, div_fichier_suivant)
{
	if(nom_fichier!="" && existe(div_fichier_suivant) && get_value("id_fichier_version")=="")
		{afficher(div_fichier_suivant, true, "block");}
	option_optimiser_image(nom_fichier);
}

////	CONTROLE VALIDATION FINALE
function valider_formulaire()
{
	////	Controle les droits d'accès
	if(typeof(Controle_Menu_Objet)=="function" && Controle_Menu_Objet()==false)		return false;
	////	Controle le nombre de fichiers  &  On lance l'upload s'il y a des fichiers
	if(get_value("type_selection")=="plupload")
	{
		var plupload_div = $('#plupload_div').pluploadQueue();
		if(plupload_div.files.length==0)	{ alert("<?php echo $trad["FICHIER_selectionner_fichier"]; ?>");  return false; }
		if(plupload_div.total.uploaded==0)	plupload_div.start();
		plupload_div.bind('UploadProgress', function(){
			if(plupload_div.total.uploaded==plupload_div.files.length)	{element("formulaire_fichiers").submit();}
		});
	}
	if(get_value("type_selection")=="simple")
	{
		if(get_value("fichier_1")=="")	{ alert("<?php echo $trad["FICHIER_selectionner_fichier"]; ?>");  return false; }
		element("formulaire_fichiers").submit();
	}
}
</script>


<form class="form_edit_element"  method="post" action="fichier_edit_ajouter_traitement.php" id="formulaire_fichiers" enctype="multipart/form-data">

	<fieldset style="text-align:center;">
		<select name="type_selection" style="float:right;margin-top:-25px;margin-right:-6px;" onChange="select_form_upload(this.value);">
			<option value="plupload"><?php echo $trad["FICHIER_ajout_plupload"]; ?></option>
			<option value="simple"><?php echo $trad["FICHIER_ajout_classique"]; ?></option>
		</select>
		<!--PLUPLOAD-->
		<div id="plupload_inputs" <?php echo infobulle("<div style='text-align:center;line-height:13px;'>".libelle_upload_max_filesize()."<hr style='margin:2px;visibility:hidden;'>".$trad["FICHIER_ajout_multiple_info"]."</div>"); ?>>
			<div id="plupload_div" style="color:#bbb;"><p>Votre navigateur ne prend pas en charge HTML5<br><br>Your browser does not support HTML5</p></div>
		</div>
		<!--SIMPLE-->
		<div id="simple_inputs" style="display:none;" <?php echo infobulle(libelle_upload_max_filesize()); ?>>
			<?php
			// 15 inputs "file"
			for($compteur=1; $compteur<=15; $compteur++)
			{
				echo "<div id='div_fichier_".$compteur."' style='".(($compteur>1)?"display:none;":"")."padding:5px;'>
						<img src=\"".PATH_TPL."divers/description.png\" OnClick=\"afficher_dynamic('description_".$compteur."');\" ".infobulle($trad["description"])." class='lien' style='vertical-align:top;' /> &nbsp;
						<input type='file' name='fichier_".$compteur."' style='margin-bottom:5px;' size='35' onChange=\"ajouter_fichier(this.value,'div_fichier_".($compteur+1)."');\" />
						<input type='text' name='description_".$compteur."' id='description_".$compteur."' style='display:none;width:90%;' />
					</div>";
			}
			?>
		</div>
		<?php
		// Nouvelle version d'un fichier : info sur le remplacement du nom du fichier
		if(isset($_GET["id_fichier_version"])){
			echo "<div class='div_infos' style='margin-top:15px;'>".$trad["FICHIER_maj_nom"]."</div>
			<script> select_form_upload('simple');  afficher('div_type_selection',false); </script>";
		}
		?>
	</fieldset>

	<!--OPTIMISER IMAGES -->
	<fieldset style="display:none;margin-top:15px;text-align:center;" id="div_optimiser">
		<input type="checkbox" name="optimiser" value="1" id="box_optimiser" onClick="checkbox_text(this);" checked="checked" />&nbsp;
		<span class="lien_select" id="txt_optimiser" onClick="checkbox_text(this);"><?php echo $trad["FICHIER_optimiser_images"]; ?></span>&nbsp;
		<?php
		echo "<select name='optimiser_taille'>";
		foreach(array("1024","1280","1600","1900") as $size_tmp)   { echo "<option value='".$size_tmp."'>".$size_tmp." ".$trad["pixels"]."</option>"; }
		echo "</select>";
		echo "<script type='text/javascript'>  set_value('optimiser_taille','".((pref_user("optimiser_taille")=="")?"1280":pref_user("optimiser_taille"))."');  </script>";
		?>
	</fieldset>

	<?php
	////	DROITS D'ACCES ET OPTIONS
	$cfg_menu_edit = array("objet"=>$objet["fichier"], "fichiers_joint"=>false);
	if(isset($_GET["id_fichier_version"]))	{ $cfg_menu_edit["objet_independant"] = false;  $cfg_menu_edit["raccourci"] = false; }
	require_once PATH_INC."element_menu_edit.inc.php";
	?>

	<div class="submit_edit_element">
		<input type="hidden" name="dossier_tmp_upload" value="<?php echo $_SESSION["dossier_tmp_upload"]; ?>">
		<input type="hidden" name="id_dossier" value="<?php echo $_GET["id_dossier"]; ?>">
		<input type="hidden" name="id_fichier_version" value="<?php echo @$_GET["id_fichier_version"]; ?>">
		<button class="button_big" onclick="valider_formulaire(); return false;" /><?php echo $trad["ajouter"]; ?></button>
	</div>

</form>


<script type="text/javascript">
////	INIT PLUPLOAD, UNE FOIS LE LIGHTBOX CHARGÉ!
$(function() {
	$("#plupload_div").pluploadQueue({
		runtimes : 'html5',//flash,
		url : 'upload/upload_filemanager.php',
		max_file_size : '<?php echo trim(str_replace("mo","mb",strtolower(libelle_upload_max_filesize(false)))); ?>',
		//chunk_size : '1mb',//en mode flash
		unique_names : true,
		flash_swf_url : 'upload/plupload.flash.swf',
		// lance option_optimiser_image() à chaque ajout de fichier
		init : {
			FilesAdded: function(up,files) {
				plupload.each(files, function(file){option_optimiser_image(file.name);});
			}
		}
	});
});

////	PAS COMPATIBLE HTML5 : FORMULAIRE SIMPLE
if(navigateur()=="ie" && version_ie()<10)	select_form_upload("simple");
</script>


<?php require PATH_INC."footer.inc.php"; ?>