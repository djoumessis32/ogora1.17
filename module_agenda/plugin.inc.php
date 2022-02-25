<?php
if(($cfg_plugin["mode"]=="recherche" || $cfg_plugin["mode"]=="nouveautes")  &&  count($AGENDAS_AFFICHES) > 0)
{
	////	EVENEMENTS AYANT COURS SUR LA PERIODE AFFICHEE
	$liste_evts = $evt_tmp_bis = array();
	if($cfg_plugin["mode"]=="nouveautes")
	{
		foreach($AGENDAS_AFFICHES as $agenda)
		{
			// Sélectionne les agendas de ressource visibles + l'agenda perso.
			if($agenda["type"]=="ressource"  ||  ($agenda["type"]=="utilisateur" && is_auteur($agenda["id_utilisateur"])))
			{
				// Evenements ayants cours dans la période (n'affiche qu'une fois)
				foreach(liste_evenements($agenda["id_agenda"],time(),strtotime($cfg_plugin["fin"])) as $evt_tmp)
				{
					if(in_array($evt_tmp["id_evenement"],$evt_tmp_bis)==false){
						$evt_tmp_bis[] = $evt_tmp["id_evenement"];
						$evt_tmp["icone_time_alert"] = "1";
						$liste_evts[] = $evt_tmp;
					}
				}
			}
		}
	}

	////	EVT RECHERCHES / CREES SUR LA PERIODE ?
	$sql_agendas = "";
	foreach($AGENDAS_AFFICHES as $agenda)	{ $sql_agendas .= $agenda["id_agenda"].","; }
	$liste_evts_tmp = db_tableau("SELECT DISTINCT T1.*  FROM  gt_agenda_evenement T1, gt_agenda_jointure_evenement T2  WHERE  T1.id_evenement=T2.id_evenement  AND  T2.id_agenda IN (".trim($sql_agendas,",").")  ".sql_selection_plugins($objet["evenement"],"T1.")."  ORDER BY T1.date_crea desc");
	$liste_evts = array_merge($liste_evts,$liste_evts_tmp);

	////	AJOUT AUX RESULTATS
	foreach($liste_evts as $evt_tmp)
	{
		if(droit_acces($objet["evenement"],$evt_tmp,false)>=1)
		{
			// Agendas à afficher
			$url_agendas = "";
			$agendas = db_colonne("SELECT DISTINCT id_agenda FROM gt_agenda_jointure_evenement WHERE id_evenement='".$evt_tmp["id_evenement"]."'");
			foreach($agendas as $id_agenda)		{ if(array_key_exists($id_agenda,$AGENDAS_AFFICHES)){$url_agendas.="&agendas_demandes[]=".$id_agenda."";} }
			// Ajoute le résultat
			$opener = ($cfg_plugin["mode"]=="recherche") ? "window.opener." : "";
			$forcePopup = ($cfg_plugin["mode"]=="recherche") ? ",true" : "";
			$resultat_tmp = array("type"=>"elem", "module_path"=>$cfg_plugin["module_path"], "icone_time_alert"=>@$evt_tmp["icone_time_alert"]);
			$resultat_tmp["lien_js_icone"]	 = $opener."redir('".ROOT_PATH.$cfg_plugin["module_path"]."/index.php?date_affiche=".strtotime($evt_tmp["date_debut"])."&affichage_demande=mois".$url_agendas."');";
			$resultat_tmp["lien_js_libelle"] = "popupLightbox('".ROOT_PATH.$cfg_plugin["module_path"]."/evenement.php?id_evenement=".$evt_tmp["id_evenement"]."' ".$forcePopup.");";
			$resultat_tmp["libelle"]		 = "<span ".infobulle(txt_affections_evt($evt_tmp)).">".temps($evt_tmp["date_debut"],"plugin",$evt_tmp["date_fin"])." : ".$evt_tmp["titre"]."</span>";
			$cfg_plugin["resultats"][] = $resultat_tmp;
		}
	}

	////	EVENEMENTS A CONFIRMER
	if($cfg_plugin["mode"]=="nouveautes")
	{
		$menu_proposition_evt = menu_proposition_evt();
		if($menu_proposition_evt!="")
		{
			$resultat_tmp = array("type"=>"elem", "module_path"=>$cfg_plugin["module_path"]);
			$resultat_tmp["block_elements"] = "<div style='margin-top:10px;'>".$menu_proposition_evt."</div>";
			$cfg_plugin["resultats"][] = $resultat_tmp;
		}
	}
}
?>