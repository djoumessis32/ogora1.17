<?php
////	INITIALISATION
require "commun.inc.php";
$chemin_image = urldecode($_GET["chemin_fichier"]);

////	ROTATION D'IMAGE
////
// On vérifie si l' image peut être traité par gd2
if(controle_fichier("image_gd",$chemin_image)==true)
{
	// Création d'une image temporaire
	if(preg_match("/(\.jpg|\.jpeg)$/i",$chemin_image))	{ $source = imagecreatefromjpeg($chemin_image); }
	elseif(preg_match("/(\.png)$/i",$chemin_image))		{ $source = imagecreatefrompng($chemin_image); }
	elseif(preg_match("/(\.gif)$/i",$chemin_image))		{ $source = imagecreatefromgif($chemin_image); }

	// Rotation & Affichage
	$rotation = imagerotate($source, $_GET["rotation"], -1);
	if(preg_match("/(\.jpg|\.jpeg)$/i",$chemin_image))	{ header("Content-type: image/jpeg");	imagejpeg($rotation,"",80); }
	elseif(preg_match("/(\.png)$/i",$chemin_image))		{ header("Content-type: image/png");	imagepng($rotation,"",7); }
	elseif(preg_match("/(\.gif)$/i",$chemin_image))		{ header("Content-type: image/gif");	imagegif($rotation,""); }
}
?>
