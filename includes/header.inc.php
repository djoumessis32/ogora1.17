<!doctype html>
<html lang="<?php echo $trad["HEADER_HTTP"]; ?>">
<head>
	<!--  AGORA-PROJECT is under the GNU General Public License (http://www.gnu.org/licenses/gpl.html)  -->
	<?php
	if(!empty($_SESSION["agora"]["nom"]))			{echo "<title>".$_SESSION["agora"]["nom"]."</title>";}
	if(!empty($_SESSION["agora"]["description"]))	{echo "<meta name='Description' content=\"".$_SESSION["agora"]["description"]." - ".@$_SESSION["espace"]["description"]."\">";}
	?>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta name="application-name" content="Agora-Project">
	<meta name="application-url" content="http://www.agora-project.net">
	<meta http-equiv="content-language" content="<?php echo $trad["HEADER_HTTP"]; ?>" />
	<link rel="icon" type="image/gif" href="<?php echo PATH_TPL; ?>divers/icone.gif" />
	<script src="<?php echo PATH_COMMUN; ?>jquery/jquery-2.1.4.min.js"></script>
	<script src="<?php echo PATH_COMMUN; ?>jquery/jquery-ui/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="<?php echo PATH_COMMUN; ?>jquery/jquery-ui/smoothness/jquery-ui.css">
	<script src="<?php echo PATH_COMMUN; ?>jquery/floating.js"></script>
	<script src="<?php echo PATH_COMMUN; ?>jquery/placeholder.js"></script>
	<script src="<?php echo PATH_COMMUN; ?>jquery/lightbox/jquery.fancybox.pack.js"></script>
	<link  href="<?php echo PATH_COMMUN; ?>jquery/lightbox/jquery.fancybox.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo PATH_COMMUN; ?>javascript_2.17.5.1.js"></script><!-- toujours après Jquery & assimilé !! -->
	<?php
	////	STYLE CSS  &&  EDITION DES ELEMENTS DANS UN POPUP OU IFRAME ?  &&  PLUGIN JQUERY "PLACEHOLDER" POUR LES ANCIENS NAVIGATEURS ?
	include_once PATH_TPL."style.css.php";
	echo "<script type='text/javascript'>
			var edition_popup='".@$_SESSION["agora"]["edition_popup"]."';
			var confirm_close_lightbox=\"".$trad["confirm_close_lightbox"]."\";
		  </script>";
	if(defined("PLACEHOLDER"))	{echo "<script type='text/javascript'>  $(document).ready(function(){ $('input,textarea').placeholder(); });  </script>";}
	?>
</head>


<body>
	<?php
	////	IMAGE BACKGROUND ("str_replace" POUR LA PAGE DE CONNEXION..)
	if(IS_MAIN_PAGE)
	{
		// FOND D'ECRAN  =>  DE L'ESPACE / DU SITE  =>  FOND D'ECRAN FOURNIT PAR AGORA (BG_DEFAULT) / PERSONNALISE
		if(!empty($_SESSION["espace"]["fond_ecran"])){
			if(preg_match("/".BG_DEFAULT."/i",$_SESSION["espace"]["fond_ecran"]))	{$fond_ecran_tmp  = PATH_WALLPAPER.str_replace(BG_DEFAULT,"",$_SESSION["espace"]["fond_ecran"]);}
			else																	{$fond_ecran_tmp  = PATH_WALLPAPER_USER.$_SESSION["espace"]["fond_ecran"];}
		}elseif(!empty($_SESSION["agora"]["fond_ecran"])){
			if(preg_match("/".BG_DEFAULT."/i",$_SESSION["agora"]["fond_ecran"]))	{$fond_ecran_tmp  = PATH_WALLPAPER.str_replace(BG_DEFAULT,"",$_SESSION["agora"]["fond_ecran"]);}
			else																	{$fond_ecran_tmp  = PATH_WALLPAPER_USER.$_SESSION["agora"]["fond_ecran"];}
		}
		// FOND D'ECRAN EXISTANT / FOND D'ECRAN PAR DEFAUT
		$fond_ecran_tmp = (!empty($fond_ecran_tmp) && is_file($fond_ecran_tmp))  ?  $fond_ecran_tmp  :  PATH_WALLPAPER."1.jpg";
		echo "<div class='img_background'><img src=\"".$fond_ecran_tmp."\" class='noprint'/></div>";
	}
	?>

	<div id="infobulle" class="infobulle noprint">&nbsp;</div>
	<div id="lightbox_content" style="display:none;"></div>
	<div id="div_loading" class="img_loading"><img src="<?php echo PATH_TPL."divers/".LOADING_IMG; ?>" /></div>