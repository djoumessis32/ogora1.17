<?php
////	INIT
require_once "../includes/global.inc.php";
$_SESSION["cfg"]["espace"]["users_connectes"] = users_connectes();


////	LIVECOUNTER PRINCIPAL
////
if($_GET["type"]=="principal")
{
	if(count($_SESSION["cfg"]["espace"]["users_connectes"])>0)
	{
		//Pour comparer à $user_cpt qui commence à 0..
		$nb_users = count($_SESSION["cfg"]["espace"]["users_connectes"]) - 1;
		//Affiche le titre et chaque user
		echo $trad["HEADER_MENU_en_ligne"]." : &nbsp; ";
		foreach($_SESSION["cfg"]["espace"]["users_connectes"] as $user_cpt => $user_tmp){
			$id_user_messenger = "user_messenger".$user_cpt;
			echo "<span class='lien' style='color:#fd4' onClick=\"affichage_messenger();checkbox_text('txt_".$id_user_messenger."');\" ".infobulle($user_tmp["civilite"]." ".$user_tmp["nom"]." ".$user_tmp["prenom"]." ".$trad["HEADER_MENU_connecte_a"]." ".strftime("%H:%M",$user_tmp["derniere_connexion"])).">".$user_tmp["prenom"].(($user_cpt<$nb_users)?", ":"")."</span>";
		}
	}
}


////	LIVECOUNTER DU MESSENGER
////
if($_GET["type"]=="messenger")
{
	// TITRE "SEUL SUR LE SITE"
	if(count($_SESSION["cfg"]["espace"]["users_connectes"])==0)	{ echo "<span style='color:#005;'>".$trad["HEADER_MENU_seul_utilisateur_connecte"]."</span>"; }
	// LISTE UTILISATEURS
	else
	{
		foreach($_SESSION["cfg"]["espace"]["users_connectes"] as $user_cpt => $user_tmp)
		{
			$id_user_messenger = "user_messenger".$user_cpt;
			//  On re-check utilisateurs sélectionnés au dernier message
			if(isset($_SESSION["messenger_users_dest"]) && in_array($user_tmp["id_utilisateur"],$_SESSION["messenger_users_dest"]))	{ $style = "lien_select";	$checked = "checked"; }
			else																													{ $style = "lien";			$checked = ""; }
			// Affiche des checkbox
			echo "<div style='display:table-row;'>
					<div style='display:table-cell;width:35px;padding:3px;'>".photo_user($user_tmp,28,true)."<input type='checkbox' name='tab_users_messenger[]' value=\"".$user_tmp["id_utilisateur"]."\" id='box_".$id_user_messenger."' ".$checked." style='display:none;' /></div>
					<div style='display:table-cell;vertical-align:middle;' class='".$style."' id='txt_".$id_user_messenger."' onClick=\"checkbox_text(this);\" ".infobulle($user_tmp["civilite"]." ".$user_tmp["nom"]." ".$user_tmp["prenom"]).">".$user_tmp["prenom"]."</div>
				</div>";
		}
	}
}
?>