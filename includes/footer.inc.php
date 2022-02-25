<?php
////	TEMPS D'EXECUTION DE LA PAGE / MESSAGE D'ALERTE?
$mtime = explode(" ",microtime());
$endtime = $mtime[1] + $mtime[0];
$tps_execution = round($endtime-$starttime, 3);
if(defined("HOST_DOMAINE"))	{alert_domaine();}

////	PAGE PRINCIPALE
if(IS_MAIN_PAGE==true)
{
	echo "<script type='text/javascript'>
		//INITIALISE LE MENU FLOTTANT (CHARGEMENT DE LA PAGE)
		$(window).load(function(){
			menuFlottant(true);
		});
		//RESOLUTION DU NAVIGATEUR VIA AJAX (RENVOI SI LE BROWSER EST REDIMENSIONNE)
		var resizeTimer;//Timer: le temps de terminer le redimentionnement..
		$(window).resize(function(){
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(function(){requete_ajax('".PATH_DIVERS."config_navigateur.php?resolution_width='+$(document).width()+'&resolution_height='+$(document).height());},1000);
		});
	</script>";
	// ENREGISTRE LA RESOLUTION DU NAVIGATEUR EN AJAX
	if(!$_SESSION["cfg"]["resolution_width"])  {echo "<script type='text/javascript'> requete_ajax('".PATH_DIVERS."config_navigateur.php?resolution_width='+$(document).width()+'&resolution_height='+$(document).height()); </script>";}
	// AFFICHE LE LOGO A DROITE
	if($_SESSION["agora"]["logo_url"])	{echo "<a href=\"".$_SESSION["agora"]["logo_url"]."\" target='_blank' style='position:fixed;bottom:0px;right:0px;z-index:1000;margin:5px;' id='footer_debug'><img src=\"".path_logo_footer()."\" id='logo_footer' style='max-height:".@$_SESSION["logo_footer_height"]."px;'  ".infobulle(AGORA_PROJECT_URL."<br>".$trad["FOOTER_page_generee"]." ".$tps_execution." sec.")." /></a>";}
	////	TEXTE/HTML DU FOOTER (description, script de stats, etc)
	if(!empty($_SESSION["agora"]["footer_html"]))	{echo $_SESSION["agora"]["footer_html"];}
}

////	MESSAGE D'ALERTE?  /  FERMETURE BDD
if(!empty($_GET["msg_alerte"]))	{alert($trad["MSG_ALERTE_".$_GET["msg_alerte"]]);}
db_close();
?>


</body>
</html>
