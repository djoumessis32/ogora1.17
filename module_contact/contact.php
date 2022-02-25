<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";

////	INFOS + DROIT ACCES + LOGS
$contact_tmp = objet_infos($objet["contact"], $_GET["id_contact"]);
$droit_acces = droit_acces_controler($objet["contact"], $contact_tmp, 1);
add_logs("consult", $objet["contact"], $_GET["id_contact"]);

////	MODIFIER ?
if($droit_acces>=2)  {echo "<span class='lien_select' style='float:right;margin:5px;' onClick=\"popupLightbox('contact_edit.php?id_contact=".$_GET["id_contact"]."');\">".$trad["modifier"]." <img src=\"".PATH_TPL."divers/crayon.png\" /></span>";}
?>


<script type="text/javascript">resizePopupLightbox(550,400);</script>
<style type="text/css">
body		{ background-image:url('<?php echo PATH_TPL; ?>module_contact/fond_popup.png'); }
.tab_user	{ width:100%; border-spacing:3px; font-weight:bold; }
.lib_user	{ width:150px; font-weight:normal; }
</style>


<table style="width:100%;border-spacing:8px;"><tr>
	<td style="max-width:200px;vertical-align:middle;">
		<img src="<?php echo ($contact_tmp["photo"]=="") ? PATH_TPL."module_utilisateurs/user.png" : PATH_PHOTOS_CONTACT.$contact_tmp["photo"]; ?>" alt="photo" />
	</td>
	<td style="text-align:left;vertical-align:middle;">
		<table class="tab_user">
<?php
		////	INFOS SUR L'UTILISATEUR
		////
		echo "<div style=\"font-size:14px;margin-bottom:10px;font-weight:bold;\">".$contact_tmp["civilite"]." ".$contact_tmp["nom"]." ".$contact_tmp["prenom"]."</div>";
		if($contact_tmp["adresse"]!="")				echo "<tr><td class=\"lib_user\"><img src=\"".PATH_TPL."divers/carte.png\" /> ".$trad["adresse"]." </td><td>".$contact_tmp["adresse"]."</td></tr>";
		if($contact_tmp["codepostal"]!="")			echo "<tr><td class=\"lib_user\"><img src=\"".PATH_TPL."divers/carte.png\" /> ".$trad["codepostal"]." </td><td>".$contact_tmp["codepostal"]."</td></tr>";
		if($contact_tmp["ville"]!="")				echo "<tr><td class=\"lib_user\"><img src=\"".PATH_TPL."divers/carte.png\" /> ".$trad["ville"]." </td><td>".$contact_tmp["ville"]."</td></tr>";
		if($contact_tmp["pays"]!="")				echo "<tr><td class=\"lib_user\"><img src=\"".PATH_TPL."module_utilisateurs/user_pays.png\" /> ".$trad["pays"]." </td><td>".$contact_tmp["pays"]."</td></tr>";
		if($contact_tmp["telephone"]!="")			echo "<tr><td class=\"lib_user\"><img src=\"".PATH_TPL."module_utilisateurs/user_telephone.png\" /> ".$trad["telephone"]." </td><td>".$contact_tmp["telephone"]."</td></tr>";
		if($contact_tmp["telmobile"]!="")			echo "<tr><td class=\"lib_user\"><img src=\"".PATH_TPL."module_utilisateurs/user_telmobile.png\" /> ".$trad["telmobile"]." </td><td>".$contact_tmp["telmobile"]."</td></tr>";
		if($contact_tmp["fax"]!="")					echo "<tr><td class=\"lib_user\"><img src=\"".PATH_TPL."module_utilisateurs/user_fax.png\" /> ".$trad["fax"]." </td><td>".$contact_tmp["fax"]."</td></tr>";
		if($contact_tmp["mail"]!="")				echo "<tr><td class=\"lib_user\"><img src=\"".PATH_TPL."module_utilisateurs/user_mail.png\" /> ".$trad["mail"]." </td><td><a href=\"mailto:".$contact_tmp["mail"]."\">".$contact_tmp["mail"]."</a></td></tr>";
		if($contact_tmp["siteweb"]!="")				echo "<tr><td class=\"lib_user\"><img src=\"".PATH_TPL."module_utilisateurs/user_siteweb.png\" /> ".$trad["siteweb"]." </td><td><a href=\"".$contact_tmp["siteweb"]."\" target=\"_blank\">".$contact_tmp["siteweb"]."</a></td></tr>";
		if($contact_tmp["competences"]!="")			echo "<tr><td class=\"lib_user\"><img src=\"".PATH_TPL."module_utilisateurs/user_competences.png\" /> ".$trad["competences"]." </td><td><a href=\"".$contact_tmp["competences"]."\" target=\"_blank\">".$contact_tmp["competences"]."</a></td></tr>";
		if($contact_tmp["hobbies"]!="")				echo "<tr><td class=\"lib_user\"><img src=\"".PATH_TPL."module_utilisateurs/user_hobbies.png\" /> ".$trad["hobbies"]." </td><td><a href=\"".$contact_tmp["hobbies"]."\" target=\"_blank\">".$contact_tmp["hobbies"]."</a></td></tr>";
		if($contact_tmp["fonction"]!="")			echo "<tr><td class=\"lib_user\"><img src=\"".PATH_TPL."module_utilisateurs/user_fonction.png\" /> ".$trad["fonction"]." </td><td>".$contact_tmp["fonction"]."</td></tr>";
		if($contact_tmp["societe_organisme"]!="")	echo "<tr><td class=\"lib_user\"><img src=\"".PATH_TPL."module_utilisateurs/user_societe_organisme.png\" /> ".$trad["societe_organisme"]." </td><td>".$contact_tmp["societe_organisme"]."</td></tr>";
		if($contact_tmp["commentaire"]!="")			echo "<tr><td class=\"lib_user\"><img src=\"".PATH_TPL."module_utilisateurs/user_commentaire.png\" /> ".$trad["commentaire"]." </td><td>".nl2br($contact_tmp["commentaire"])."</td></tr>";
		////	CARTE MASHUP
		if($contact_tmp["adresse"]!="" || $contact_tmp["codepostal"]!="" || $contact_tmp["ville"]!="" || $contact_tmp["pays"]!="") {
			$addr_tmp = addslashes($contact_tmp["adresse"]."+".$contact_tmp["codepostal"]."+".$contact_tmp["ville"]."+".$contact_tmp["pays"]);
			echo "<tr><td colspan=\"2\" style=\"cursor:pointer;\" onClick=\"window.open('http://maps.google.fr/maps?f=q&hl=fr&geocode=&time=&date=&ttype=&q=".$addr_tmp."','carte','width=800,height=600');\"><br><img src=\"".PATH_TPL."divers/carte.png\" /> &nbsp; ".$trad["localiser_carte"]."</td></tr>";
		}
?>
		</table>
	</td>
</tr></table>


<?php
////	Fichiers joints + footer
affiche_fichiers_joints($objet["contact"], $_GET["id_contact"], "popup");
require PATH_INC."footer.inc.php";
?>
