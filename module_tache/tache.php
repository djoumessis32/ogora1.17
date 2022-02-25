<?php
////	INIT
require "commun.inc.php";
require_once PATH_INC."header.inc.php";

////	INFOS + DROIT ACCES + LOGS
$tache_tmp = objet_infos($objet["tache"], $_GET["id_tache"]);
$droit_acces = droit_acces_controler($objet["tache"], $tache_tmp, 1);
add_logs("consult", $objet["tache"], $_GET["id_tache"]);
?>


<script type="text/javascript">resizePopupLightbox(650,500);</script>
<style type="text/css">  body { font-weight:bold; background-image:url('<?php echo PATH_TPL; ?>module_tache/fond_popup.png'); }  </style>


<?php
////	ENTETE DU POPUP  (DEBUT + FIN + AVANCEMENT + CHARGE + BUDGETS + MODIF + ...)
////
$titre_popup = "<table class='table_nospace' style='width:100%;'><tr>";
	$titre_popup .= "<td style='text-align:left;'>";
		////	DEBUT / FIN / AVANCEMENT / CHARGE / BUDGETS
		$titre_popup .= tache_budget($tache_tmp);
		$titre_popup .= tache_barre_avancement_charge($tache_tmp);
		$titre_popup .= tache_debut_fin($tache_tmp);
		////	RESPONSABLES
		$responsables = db_colonne("SELECT id_utilisateur FROM gt_tache_responsable WHERE id_tache='".$tache_tmp["id_tache"]."'");
		if(count($responsables)>0)
		{
			$titre_popup .= "<div style='margin-top:10px;'><img src=\"".PATH_TPL."module_utilisateurs/utilisateurs_small.png\" /> ".$trad["TACHE_responsables"]." : ";
			foreach($responsables as $id_user)	{ $titre_popup .= auteur($id_user).", "; }
			$titre_popup = substr($titre_popup,0,-2)."</div>";
		}
	$titre_popup .= "</td>";
	////	MODIFIER?
	if($droit_acces>=2)
		{$titre_popup .= "<td style='text-align:right;'><span class='lien_select' onClick=\"popupLightbox('tache_edit.php?id_tache=".$_GET["id_tache"]."');\">".$trad["modifier"]." <img src=\"".PATH_TPL."divers/crayon.png\" /></span></td>";}
$titre_popup .= "</tr></table>";


////	TITRE & DESCRIPTION
////
echo "<fieldset class='fieldset_titre'>".$titre_popup."</fieldset>";
echo "<div style='margin:0px;padding:10px;'>";
	if($tache_tmp["priorite"]!="")	{echo "<img src=\"".PATH_TPL."module_tache/priorite".$tache_tmp["priorite"].".png\" ".infobulle($trad["TACHE_priorite"]." ".$trad["TACHE_priorite".$tache_tmp["priorite"]])." />&nbsp; ";}
	echo "<span style='margin-left:5px;font-size:13px'>".$tache_tmp["titre"]."</span>";
	echo "<div style='margin-top:10px;margin-left:30px;font-weight:normal;'>".$tache_tmp["description"]."</div>";
echo "</div>";


////	Fichiers joints + footer
affiche_fichiers_joints($objet["tache"], $_GET["id_tache"], "popup");
require PATH_INC."footer.inc.php";
?>