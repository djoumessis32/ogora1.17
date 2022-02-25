<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";


////	SCROLLER D'IMAGES
////
////	Dimensions & nombre d'images
$nb_img = count($_SESSION["cfg"]["espace"]["scroller_images"]);
$swf_width_total = ($nb_img * 120);//suppose que chaque vignette fait en moyenne 120px de large
$swf_height = "100";
$swf_width =  ($swf_width_total < @$_SESSION["cfg"]["resolution_width"])  ?  $swf_width_total  :  @$_SESSION["cfg"]["resolution_width"];

////	Chemin des fichiers de config
$scroller_dossier	= PATH_TMP."scroller_dossier_".$_REQUEST["id_dossier"]."/";
$scroller_settings	= $scroller_dossier."settings.xml";
$scroller_images	= $scroller_dossier."images.xml";
if(is_dir($scroller_dossier)==false)	{ mkdir($scroller_dossier);  @chmod($scroller_dossier,0775); }

////	création du "images.xml" : liste des images
$scroller_images_tmp  = "<?xml version='1.0' encoding='UTF-8'?>\n";
$scroller_images_tmp .= "<slideshow>\n";
foreach($_SESSION["cfg"]["espace"]["scroller_images"] as $img)  { $scroller_images_tmp .= "\t<photo image=\"".PATH_MOD_FICHIER2.$img["vignette"]."\" url=\"javascript:affiche_img('".$img["id_fichier"]."')\" target='_self' lightboxinfo='description'><![CDATA[".($img["description"]!=""?$img["description"]:"")."]]></photo>\n"; }
$scroller_images_tmp .= "</slideshow>";
$fp = fopen($scroller_images, "w");
fwrite($fp, $scroller_images_tmp);
fclose($fp);

////	création du "settings.xml" : config. générale
copy("scroller/settings.xml", $scroller_settings);
$settings_tmp = file($scroller_settings);
foreach($settings_tmp as $id_ligne => $ligne) {
	if(preg_match("/images.xml/i",$ligne))  	$settings_tmp[$id_ligne] = "<assets value=\"".$scroller_images."\"/>";
	if(preg_match("/componentWidth/i",$ligne))  $settings_tmp[$id_ligne] = "<componentWidth value=\"".$swf_width."\"/>";
}
$fp = fopen($scroller_settings, "w");
fwrite($fp, implode("",$settings_tmp));
fclose($fp);
?>


<style type="text/css">
body		{ background-color:transparent; }
.full_page	{ width:100%; height:100%; padding:0px; margin:0px; }
</style>

<div class="full_page" style="position:absolute;">
	<div class="full_page" style="display:table;text-align:center;">
		<div style='display:table-row;'>
			<?php 
			////	SCROLLER D'IMAGE
			$display_scroller = (count($_SESSION["cfg"]["espace"]["scroller_images"])<2)  ?  "display:none;"  :  "";
			echo "<div style='display:table-cell;height:".$swf_height."px;".$display_scroller."' id='cadre_scroller_flash'>";
				swfobject("div_scroller_flash");
				echo "<script type='text/javascript'> swfobject.embedSWF('scroller/scroller.swf', 'div_scroller_flash', '".$swf_width."', '".$swf_height."', '9.0.0', false, {settingsXML:'".$scroller_settings."'}, {scale:'noscale',salign:'tl',wmode:'transparent'});  </script>";
			echo "</div>";
			?>
		</div>
		<div style="display:table-row;">
			<div style="display:table-cell;vertical-align:middle;" id="cadre_image">&nbsp;</div>
		</div>
	</div>
</div>


<script type="text/javascript">
////	Affiche l'image
////
function affiche_img(id_fichier, diaporama, rotation)
{
	////	Récupère l'image et son menu en AJAX  +  redimmensionne l'image  +  arrête le diaporama si besoin
	requete_ajax("image.php?id_fichier="+id_fichier+"&rotation="+rotation+"&diaporama="+diaporama);
	element("cadre_image").innerHTML = retour_ajax;
	redimentionne_img("ajuste");
	if(diaporama!="1")	lance_diaporama(false);
	////	Affiche l'icone de zoom ?
	cadre_image_width = document.documentElement.clientWidth;
	cadre_image_height = document.documentElement.clientHeight - element("cadre_scroller_flash").style.height.replace("px","");
	if(image_width < cadre_image_width  &&  image_height < cadre_image_height)	element("icone_zoom").style.display = "none";
}


////	Redimentionne l'image : ajustée à la page OU taille normale
////
function redimentionne_img(action)
{
	////	Initialisations
	cadre_image_width = document.documentElement.clientWidth;
	cadre_image_height = document.documentElement.clientHeight - element("cadre_scroller_flash").style.height.replace("px","");
	image_width  = element("image").style.width.replace("px","");
	image_height = element("image").style.height.replace("px","");
	// Redimentionne si l'image si elle dépasse le cadre de référence
	if(image_width > cadre_image_width || image_height > cadre_image_height)
	{
		// Largeur > Hauteur  OU  Hauteur > Largeur  ?
		ratio_width = cadre_image_width / image_width;
		ratio_height = cadre_image_height / image_height;
		ratio = (ratio_width < ratio_height) ? ratio_width : ratio_height;
		if(ratio > 0) {
			element("image").style.width = Math.round(image_width * ratio)+"px";
			element("image").style.height = Math.round(image_height * ratio)+"px";
		}
	}
	// Taille normale
	else {
		element("image").style.width = element("image_width_reference").value+"px";
		element("image").style.height = element("image_height_reference").value+"px";
	}
}


////	Diaporamas
////
function lance_diaporama(action, init)
{
	// Lance le diaporama
	if(action==true) {
		if(init==null)	affiche_img(element("id_img_suiv").value,'1');
		TimeoutImage = window.setTimeout("lance_diaporama(true);",6000); //6 sec.
	}
	// stop
	else {
		if(typeof TimeoutImage!="undefined")	window.clearTimeout(TimeoutImage);
		afficher("icone_lect_diapo", true);
		afficher("icone_stop_diapo", false);
	}
}


<?php
////	IMAGE DEMANDE / DIAPORAMA
if(@$_REQUEST["id_fichier"]>0)	{ echo "$(window).load(function(){  affiche_img('".$_REQUEST["id_fichier"]."');  });"; }
else							{ echo "$(window).load(function(){  affiche_img('".$_SESSION["cfg"]["espace"]["scroller_images"][0]["id_fichier"]."','1');  });		$(function(){  lance_diaporama(true,true);  });"; }
?>
</script>