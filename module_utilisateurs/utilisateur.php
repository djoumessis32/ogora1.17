<?php
////	INIT
define("NO_MODULE_CONTROL",true);
require "commun.inc.php";
require_once PATH_INC."header.inc.php";

////	INFOS + DROIT ACCES + LOGS
$user_tmp = db_ligne("SELECT * FROM gt_utilisateur WHERE id_utilisateur='".intval($_GET["id_utilisateur"])."'");
controle_affichage_utilisateur($user_tmp["id_utilisateur"]);
add_logs("consult", "", "", auteur($user_tmp["id_utilisateur"]));


////	MODIFIER
////
if(droit_modif_utilisateur($_GET["id_utilisateur"])>0)
	{echo "<span class='lien_select' style='float:right;margin:5px;' onClick=\"popupLightbox('".ROOT_PATH."/module_utilisateurs/utilisateur_edit.php?id_utilisateur=".$_GET["id_utilisateur"]."');\">".$trad["modifier"]." <img src=\"".PATH_TPL."divers/crayon.png\" /></span>";}
?>


<script type="text/javascript"> resizePopupLightbox(550,380); </script>
<style type="text/css">
body		{ background-image:url('<?php echo PATH_TPL; ?>module_utilisateurs/fond_popup.png'); }
.tab_user	{ width:100%; border-spacing:3px; font-weight:bold; }
.lib_user	{ width:150px; font-weight:normal; }
</style>


<table style="width:100%;border-spacing:8px;"><tr>
	<td style="max-width:200px;text-align:center;vertical-align:middle;">
		<img src="<?php echo ($user_tmp["photo"]=="") ? PATH_TPL."module_utilisateurs/user.png" : PATH_PHOTOS_USER.$user_tmp["photo"]; ?>" />
	</td>
	<td style="text-align:left;vertical-align:middle;">
		<table class="tab_user">
<?php
			////	INFOS SUR L'UTILISATEUR
			////
			echo "<div style=\"font-size:14px;margin-bottom:10px;font-weight:bold;\">".$user_tmp["civilite"]." ".$user_tmp["nom"]." ".$user_tmp["prenom"]."</div>";
			if($user_tmp["adresse"]!="")			{echo "<tr><td class='lib_user'><img src=\"".PATH_TPL."divers/carte.png\" /> ".$trad["adresse"]." </td><td>".$user_tmp["adresse"]."</td></tr>";}
			if($user_tmp["codepostal"]!="")			{echo "<tr><td class='lib_user'><img src=\"".PATH_TPL."divers/carte.png\" /> ".$trad["codepostal"]." </td><td>".$user_tmp["codepostal"]."</td></tr>";}
			if($user_tmp["ville"]!="")				{echo "<tr><td class='lib_user'><img src=\"".PATH_TPL."divers/carte.png\" /> ".$trad["ville"]." </td><td>".$user_tmp["ville"]."</td></tr>";}
			if($user_tmp["pays"]!="")				{echo "<tr><td class='lib_user'><img src=\"".PATH_TPL."module_utilisateurs/user_pays.png\" /> ".$trad["pays"]." </td><td>".$user_tmp["pays"]."</td></tr>";}
			if($user_tmp["telephone"]!="")			{echo "<tr><td class='lib_user'><img src=\"".PATH_TPL."module_utilisateurs/user_telephone.png\" /> ".$trad["telephone"]." </td><td>".$user_tmp["telephone"]."</td></tr>";}
			if($user_tmp["telmobile"]!="")			{echo "<tr><td class='lib_user'><img src=\"".PATH_TPL."module_utilisateurs/user_telmobile.png\" /> ".$trad["telmobile"]." </td><td>".$user_tmp["telmobile"]."</td></tr>";}
			if($user_tmp["fax"]!="")				{echo "<tr><td class='lib_user'><img src=\"".PATH_TPL."module_utilisateurs/user_fax.png\" /> ".$trad["fax"]." </td><td>".$user_tmp["fax"]."</td></tr>";}
			if($user_tmp["mail"]!="")				{echo "<tr><td class='lib_user'><img src=\"".PATH_TPL."module_utilisateurs/user_mail.png\" /> ".$trad["mail"]." </td><td><a href=\"mailto:".$user_tmp["mail"]."\">".$user_tmp["mail"]."</a></td></tr>";}
			if($user_tmp["siteweb"]!="")			{echo "<tr><td class='lib_user'><img src=\"".PATH_TPL."module_utilisateurs/user_siteweb.png\" /> ".$trad["siteweb"]." </td><td><a href=\"".$user_tmp["siteweb"]."\" target=\"_blank\">".$user_tmp["siteweb"]."</a></td></tr>";}
			if($user_tmp["competences"]!="")		{echo "<tr><td class='lib_user'><img src=\"".PATH_TPL."module_utilisateurs/user_competences.png\" /> ".$trad["competences"]." </td><td><a href=\"".$user_tmp["competences"]."\" target=\"_blank\">".$user_tmp["competences"]."</a></td></tr>";}
			if($user_tmp["hobbies"]!="")			{echo "<tr><td class='lib_user'><img src=\"".PATH_TPL."module_utilisateurs/user_hobbies.png\" /> ".$trad["hobbies"]." </td><td><a href=\"".$user_tmp["hobbies"]."\" target=\"_blank\">".$user_tmp["hobbies"]."</a></td></tr>";}
			if($user_tmp["fonction"]!="")			{echo "<tr><td class='lib_user'><img src=\"".PATH_TPL."module_utilisateurs/user_fonction.png\" /> ".$trad["fonction"]." </td><td>".$user_tmp["fonction"]."</td></tr>";}
			if($user_tmp["societe_organisme"]!="")	{echo "<tr><td class='lib_user'><img src=\"".PATH_TPL."module_utilisateurs/user_societe_organisme.png\" /> ".$trad["societe_organisme"]." </td><td>".$user_tmp["societe_organisme"]."</td></tr>";}
			if($user_tmp["commentaire"]!="")		{echo "<tr><td class='lib_user'><img src=\"".PATH_TPL."module_utilisateurs/user_commentaire.png\" /> ".$trad["commentaire"]." </td><td>".nl2br($user_tmp["commentaire"])."</td></tr>";}
			////	DERNIERE CONNEXION
			$derniere_connexion = ($user_tmp["derniere_connexion"]>0)  ?  temps($user_tmp["derniere_connexion"],"complet")  :  $trad["UTILISATEURS_pas_connecte"];
			echo "<tr><td class='lib_user'><img src=\"".PATH_TPL."module_utilisateurs/user_connexion.png\" /> ".$trad["UTILISATEURS_derniere_connexion"]." </td><td>".$derniere_connexion."</td></tr>";
			////	CARTE MASHUP
			if($user_tmp["adresse"]!="" || $user_tmp["codepostal"]!="" || $user_tmp["ville"]!="" || $user_tmp["pays"]!="") {
				$addr_tmp = addslashes($user_tmp["adresse"]."+".$user_tmp["codepostal"]."+".$user_tmp["ville"]."+".$user_tmp["pays"]);
				echo "<tr><td colspan=\"2\" style=\"cursor:pointer;\" onClick=\"window.open('https://maps.google.fr/maps?f=q&hl=fr&geocode=&time=&date=&ttype=&q=".$addr_tmp."','carte','width=800,height=600');\"><br><img src=\"".PATH_TPL."divers/carte.png\" /> &nbsp; ".$trad["localiser_carte"]."</td></tr>";
			}
?>
		</table>
	</td>
</tr></table>


<?php require PATH_INC."footer.inc.php"; ?>
