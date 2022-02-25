<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";
if($_SESSION["user"]["admin_general"]!=1)	exit();
titre_popup($trad["UTILISATEURS_envoi_coordonnees_infos"]);


////	ENVOI DES COORDONNEES DE CONNXION PAR MAIL
////
if(isset($_POST["users_coords"]) && count($_POST["users_coords"])>0)
{
	foreach($_POST["users_coords"] as $id_user_tmp)
	{
		$info_user_tmp = user_infos($id_user_tmp);
		if($info_user_tmp["mail"]!="")
		{
			$nouveau_pass  = mt_rand(10000,99999);
			$sujet_mail = $_SESSION["agora"]["nom"]." : ".$trad["UTILISATEURS_mail_coordonnees"];
			$contenu_mail  = $trad["UTILISATEURS_mail_coordonnees"]." :<br>";
			$contenu_mail .= $trad["identifiant_connexion"]." : ".$info_user_tmp["identifiant"]."<br>";
			$contenu_mail .= $trad["pass"]." : ".$nouveau_pass."<br>";
			$envoi_mail = envoi_mail($info_user_tmp["mail"], $sujet_mail, $contenu_mail, array("message_alert"=>false));
			db_query("UPDATE gt_utilisateur SET pass='".sha1_pass($nouveau_pass)."' WHERE id_utilisateur=".db_format($info_user_tmp["id_utilisateur"]));
		}
	}
	if($envoi_mail==true)	{alert($trad["mail_envoye"]);}
	reload_close();
}
?>


<script type="text/javascript"> resizePopupLightbox(400,800); </script>
<style type="text/css">  body { font-weight:bold;background-image:url('<?php echo PATH_TPL; ?>module_utilisateurs/fond_popup.png'); }  </style>


<form action="<?php echo php_self(); ?>" method="post" style="padding:10px;" onSubmit="if(confirm('<?php echo addslashes($trad["UTILISATEURS_envoi_coordonnees_confirm"]); ?>')==false) return false;">
	<table>
	<?php
	////	AFFICHE LA LISTE DES UTILISATEURS AVEC MAIL
	foreach(db_tableau("SELECT * FROM gt_utilisateur WHERE mail!='' ".sql_utilisateurs_espace()." AND id_utilisateur!='".$_SESSION["user"]["id_utilisateur"]."' ORDER BY ".$_SESSION["agora"]["tri_personnes"]) as $user_tmp)
	{
		echo "<tr>";
			echo "<td><input type=\"checkbox\" name=\"users_coords[]\" value=\"".$user_tmp["id_utilisateur"]."\" /></td>";
			echo "<td>".$user_tmp["prenom"]." ".$user_tmp["nom"]."</td>";
		echo "</tr>";
	}
	?>
	</table>
	<div style="text-align:right;margin-top:20px;"><input type="submit" class="button_big" /></div>
</form>


<?php require PATH_INC."footer.inc.php"; ?>
