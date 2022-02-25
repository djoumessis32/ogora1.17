<?php
////	REDUCTION D'UN TEXTE
////
function text_reduit($chaine, $nb_caract_maxi=200)
{
	if(strlen($chaine)>=$nb_caract_maxi)
	{
		$chaine = strip_tags($chaine); // Enlève les caractères html
		$chaine = substr($chaine, 0, $nb_caract_maxi); // On réduit à la taile maxi
		if(strrpos($chaine," ") > 1)	{ $chaine = substr($chaine, 0, strrpos($chaine," ")); } // On enlève le dernier mot tronqué auquel cas
		$chaine = $chaine."...";
	}
	return $chaine;
}


////	INFOBULLE
////
function infobulle($text,$max_width="400")
{
	if($text!=""){
		$text = str_replace("\"", "&quot;", $text);
		$text = str_replace(array("\n","\r"), "", $text);
		return " onMouseOver=\"bulle('".addslashes("<div style='max-width:".$max_width."px'>".$text."</div>")."');\" onMouseOut=\"bullefin();\" ";
	}
}


////	ENLEVE LES SLASHES SI MAGIC QUOTES EST ACTIVE
////
function magicquotes_strip($chaine)
{
	return (@get_magic_quotes_gpc()=="1")  ?  stripslashes($chaine)  :  $chaine;
}


////	PREPARE LE TEXTE AVANT ENVOI DES NOTIFICATIONS => ENLEVE LES MAGIQUES QUOTES & REMPLACE LES CHEMINS RELATIFS PAR DES CHEMINS ABSOLUS PUBLICS (http)
////
function texteNotification($chaine)
{
	$chaine = str_replace(PATH_STOCK_FICHIERS, $_SESSION["agora"]["adresse_web"]."/stock_fichiers/", magicquotes_strip($chaine));
	return $chaine;
}


////	AFFICHAGE DE LA BARRE DE TITRE D'UN POPUP
////
function titre_popup($titre)
{
	echo "<div style=\"position:fixed;top:0px;left:0px;z-index:100000;width:100%;color:#000;font-weight:bold;font-size:13px;text-align:center;padding:7px;background-image:url(".PATH_TPL."divers/popup_fond_titre.jpg);background-position:bottom left;\">".$titre."</div><hr style='visibility:hidden;height:30px;' />";
}


////	SUPPRIME LES CARACTERES SPECIAUX D'UNE CHAINE DE CARACTERES   (exemple de $etendue avec "L'épée!!"  => "faible":"L'epee!!"  => "normale":"L'epee"  => "maxi":"L_epee")
////
function suppr_carac_spe($text, $etendue, $carac_remplace="_")
{
	// Remplace les caractères accentués ou assimilés
	$text = str_replace(array("à","â","ä"), "a", $text);
	$text = str_replace(array("é","è","ê","ë"), "e", $text);
	$text = str_replace(array("ï","ì"), "i", $text);
	$text = str_replace(array("ô","ö"), "o", $text);
	$text = str_replace(array("ù","û","ü"), "u", $text);
	$text = str_replace("ç", "c", $text);
	// Remplace les ponctuations et autres caractères spéciaux
	if($etendue=="normale" || $etendue=="maxi"){
		$carac_ok = ($etendue=="normale")  ?  array(" ","-",".","_","'","(",")","[","]")  :  array("-",".","_");
		for($i=0; $i<strlen($text); $i++){
			if(!preg_match("/[0-9a-z]/i",$text[$i]) && !in_array($text[$i],$carac_ok))	{$text[$i]=$carac_remplace;}
		}
		$text = str_replace($carac_remplace.$carac_remplace, $carac_remplace, $text);
	}
	return trim($text,$carac_remplace);
}


////	CRYPTE LE PASSWORD SI SHA1() EST ACTIF
////
function sha1_pass($password)
{
	//SALT par défaut
	if(!defined("AGORA_SALT"))	{define("AGORA_SALT","Ag0rA-Pr0j3cT");}
	//Renvoi le password avec sha1 et SALT
	if(function_exists("sha1")) {return sha1(AGORA_SALT.sha1($password));}
	else						{return $password;}
}


////	FORMATAGE D'UNE DATE
////
function temps($debut, $format="normal", $fin="", $heure_optionnelle=false)
{
	if($debut!="" || $fin!="")
	{
		////	Init
		global $trad;
		$format_fin = "";
		$separe_heure = $trad["separateur_horaire"];
		$format_H_M = "%H".$separe_heure."%M";
		$format_jourdumoi = (preg_match("/(win32|win64)/i",$_SERVER['SERVER_SOFTWARE'])) ? "%d" : "%e"; // 01-31 ou 1-31
		// On convertit si besoin les dates en timestamp unix
		if(!is_numeric($debut))				{$debut=strtotime($debut);}
		if(!is_numeric($fin) && $fin!="")	{$fin=strtotime($fin);}
		// Affiche l'année ?
		$format_mois_annee = (strftime("%y",$debut)!=strftime("%y") || ($fin!="" && strftime("%y",$debut)!=strftime("%y",$fin)) || $format=="complet")  ?  "%b %Y"  :  "%b";
		// Jour debut != jour fin ?  heure debut != heure fin ?
		$jour_deb_fin  = ($fin!="" && strftime("%y-%m-%d",$debut)!=strftime("%y-%m-%d",$fin))  ?  true  :  false;
		$heure_deb_fin = ($fin!="" && strftime("%H:%M",$debut)!=strftime("%H:%M",$fin))  ?  true  :  false;

		////	Format normal (menu element)
		if($format=="normal"){
			$format_jour  = $format_jourdumoi." ".$format_mois_annee;
			$format_debut = $format_jour." ".$format_H_M;							// 8 fév. 2010 11h30
			if($jour_deb_fin==true)			{ $format_fin = " > ".$format_debut; }	// 8 fév. 2010 11h30 > 15 mars 2010, 17h30
			elseif($heure_deb_fin==true)	{ $format_fin = "-".$format_H_M; }		// 8 fév. 2010 11h30-12h30
		}
		////	Format plugin (plugin tdb / taches)
		elseif($format=="plugin"){
			$format_jour  = $format_jourdumoi." %b";
			$format_debut = $format_jour." ".$format_H_M;							// 8 fév. 11h30
			if($jour_deb_fin==true)			{ $format_fin = " > ".$format_debut; }	// 8 fév. 11h30 > 15 fév 12h30
			elseif($heure_deb_fin==true)	{ $format_fin = "-".$format_H_M; }		// 8 fév. 11h30-12h30
		}
		////	Format complet (detail evenement)
		if($format=="complet"){
			$format_jour  = "%a ".$format_jourdumoi." ".$format_mois_annee;
			$format_debut = $format_jour.", ".$format_H_M;							// lun. 8 fév. 2010 11h30
			if($jour_deb_fin==true)			{ $format_fin = " > ".$format_debut; }	// lun. 8 fév. 2010 11h30 > mer. 15 mars 2010, 17h30
			elseif($heure_deb_fin==true)	{ $format_fin = "-".$format_H_M; }		// lun. 8 fév. 2010 11h30-12h30
		}
		////	Format mini (evenement dans agenda)
		elseif($format=="mini"){
			if($jour_deb_fin==true)			{ $format_debut = $format_jourdumoi." %b";		$format_fin = " > ".$format_debut; }	// 8 fev. > 15 mars
			elseif($heure_deb_fin==true)	{ $format_debut = $format_H_M;	$format_fin = "-".$format_debut; }						// 11h30-12h30
			else							{ $format_debut = $format_H_M; }														// 11h30
		}
		////	Format date uniquement (element affiché en mode liste)
		elseif($format=="date"){
			$format_debut = $format_jour = $format_jourdumoi." ".$format_mois_annee;	// 8 fév. 2010
			if($jour_deb_fin==true)			{ $format_fin = " > ".$format_debut; }		// 8 fév. 2010 > 15 mars 2010
		}

		////	Formatage strftime
		$phrase_temps = formatime($format_debut, $debut);
		if($fin!="")	{$phrase_temps .= formatime($format_fin, $fin);}

		/////	Nettoyage des heures/minutes vides  (heure optionnelle pour les tâches, ou autre..)
		if($heure_optionnelle==true)	{$phrase_temps = str_replace(" 00".$separe_heure."00", "", $phrase_temps);}
		$phrase_temps = str_replace($separe_heure."00", $separe_heure, $phrase_temps);

		////	Affiche "Aujourd'hui" ?
		if(preg_match("/normal|complet|date/i",$format)  &&  strftime("%y-%m-%d",$debut)==strftime("%y-%m-%d",time()))
			{$phrase_temps = str_replace(formatime($format_jour), $trad["aujourdhui"], $phrase_temps);}

		////	On renvoi le résultat
		return $phrase_temps;
	}
}


////	FORMATER TIME  strftime() + convertion en utf8 si besoin
////
function formatime($format, $timestamp="")
{
	$retour = ($timestamp=="") ? strftime($format) : strftime($format,$timestamp);
	if(!function_exists("mb_detect_encoding") || mb_detect_encoding($retour,"UTF-8",true)=="UTF-8")	 {return $retour;}
	else																							 {return utf8_encode($retour);}
}


////	ENCODER EN UTF-8 OU DECODER DE L'UTF-8
////
function convert_utf8($text, $action="encoder")
{
	if(function_exists("mb_detect_encoding")==false)	{ return $text; }
	else
	{
		if($action=="encoder" && mb_detect_encoding($text,"UTF-8",true)!="UTF-8")		{return utf8_encode($text);}
		elseif($action=="decoder" && mb_detect_encoding($text,"UTF-8",true)=="UTF-8")	{return utf8_decode($text);}
		else																			{return $text;}
	}
}


////	TEXTE EN MAJUSCULE
////
function majuscule($text)
{
	return strtoupper(suppr_carac_spe($text,"faible"));
}


////	TEXT VERS TABLEAU    @@11@@22@@33@@ ou 11@@22@@33  =>  array("1","2","3")
////
function text2tab($text)
{
	if($text!="")	{return explode("@@", trim($text,"@@"));}
	else			{return array();}
}


////	TABLEAU VERS TEXT    array("11","22","33","","")  =>  @@11@@22@@33@@
////
function tab2text($tab)
{
	if(is_array($tab))
	{
		$retour = "";
		foreach($tab as $valeur){
			if($valeur!="")	{$retour .= "@@".$valeur;}
		}
		if($retour!="")	{return $retour."@@";}
	}
}


////	VALEUR NUMERIQUE SUR 2 CARACTERES : 01 / 15
////
function num2carac($valeur)
{
	return (strlen($valeur)<2) ? "0".$valeur : $valeur;
}


////	PHP_SELF
////
function php_self()
{
	return htmlentities($_SERVER["PHP_SELF"]);
}
?>