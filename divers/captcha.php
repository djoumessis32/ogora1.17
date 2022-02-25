<?php
////	INIT
define("GLOBAL_EXPRESS",true);//pour bien lancer la session..
require_once "../includes/global.inc.php";//idem
$largeur = 120;
$hauteur = 28;
$font_size = 21;
$nb_carac = 4;
$ligne_couleurs = array("#DD6666","#66DD66","#6666DD","#DDDD66","#DD66DD","#66DDDD","#666666");
$font_couleurs = array("#880000","#008800","#000088","#888800","#880088","#008888","#000000");
$caracteres  ="ABCDEFGHKMNPQRSTUVWXYZ2345689";

////	Fonction qui retourne la couleur au forma hexadecimal
function gd_color($colors){
	return preg_match("/^#?([\dA-F]{6})$/i",$colors,$rgb)  ?  hexdec($rgb[1])  :  false;
}

////	Creation de l'image
$image = imagecreatetruecolor($largeur, $hauteur);
imagefilledrectangle($image, 0, 0, $largeur-1, $hauteur-1, gd_color("#FFFFFF"));

////	Dessine 20 lines en background
for($i=0; $i < 20; $i++){
	imageline($image, mt_rand(0,$largeur-1), mt_rand(0,$hauteur-1), mt_rand(0,$largeur-1), mt_rand(0,$hauteur-1), gd_color($ligne_couleurs[mt_rand(0,count($ligne_couleurs)-1)]));
}

////	Dessine le texte
$_SESSION["captcha"] = "";
$y = ($hauteur/2) + ($font_size/2);
for($i=0; $i < $nb_carac; $i++)
{
	// pour chaque caractere : Police + couleur + angulation
	$captcha_font = "./captcha_fonts/".mt_rand(1,4).".ttf";
	$color = gd_color($font_couleurs[mt_rand(0,count($font_couleurs)-1)]);
	$angle = mt_rand(-20,20);
	// sélectionne le caractère au hazard
	$char = substr($caracteres, mt_rand(0,strlen($caracteres) - 1), 1);
	$x = (intval(($largeur/$nb_carac) * $i) + ($font_size / 2)) - 4;
	$_SESSION["captcha"] .= $char;
	imagettftext($image, $font_size, $angle, $x, $y, $color, $captcha_font, $char);
}

////	Captcha dans Session + affichage de l'image
header("Content-Type: image/jpeg");
imagejpeg($image);
?>