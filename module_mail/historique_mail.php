<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";

////	ON SUPPRIME UN MAIL
if(isset($_GET["action"]) && $_GET["action"]=="suppr" && $_GET["id_mail"]>0)
	db_query("DELETE FROM gt_historique_mails WHERE id_mail=".db_format($_GET["id_mail"])." AND id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."'");
?>


<script type="text/javascript">resizePopupLightbox(600,400);</script>
<style type="text/css">
body	{ background-image:url('<?php echo PATH_TPL; ?>module_mail/fond_popup.png'); }
</style>


<?php
echo "<fieldset class='fieldset_titre'>".$trad["MAIL_historique_mail"]."</fieldset>";

////	AFFICHAGE DES MAILS
$liste_mails = db_tableau("SELECT * FROM gt_historique_mails WHERE id_utilisateur='".$_SESSION["user"]["id_utilisateur"]."' AND id_utilisateur > 0  ORDER BY date_crea desc");
if(count($liste_mails)==0)	{echo "<h3>".$trad["MAIL_aucun_mail"]."</h3>";}
else
{
	echo "<ul>";
	foreach($liste_mails as $infos_mail)
	{
		$auteur = $trad["MAIL_envoye_par"]." ".auteur(user_infos($infos_mail["id_utilisateur"]));
		$date = $trad["le"]." ".temps($infos_mail["date_crea"]);
		echo "<li style='padding:7px;'>";
			echo "<span class='lien' style='font-weight:bold;' OnClick=\"afficher_dynamic('description_".$infos_mail["id_mail"]."');\" >";
				echo $infos_mail["titre"]." ".icone_suppr("historique_mail.php?action=suppr&id_mail=".$infos_mail["id_mail"]);
			echo "</span>";
			echo "<div style='display:none;padding:5px;' id=\"description_".$infos_mail["id_mail"]."\">";
				echo $auteur." ".$date."<br>".$trad["MAIL_destinataires"]." : ".$infos_mail["destinataires"]."<br><br>";
				echo $infos_mail["description"];
			echo "</div>";
		echo "</li>";
	}
	echo "</ul>";
}

////	FOOTER
require PATH_INC."footer.inc.php";
?>
