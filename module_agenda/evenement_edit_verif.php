<?php
////	INIT
require "commun.inc.php";

////	VERIFIE LE NOMBRE D'AGENDAS OCCUPES SUR CE CRENEAU
////
$txt_creneaux_occupes = "";
$debut_unix	= strtotime($_GET["datetime_debut"]);
$fin_unix	= strtotime($_GET["datetime_fin"]);
foreach($_GET["agendas_selectionnes"] as $id_agenda)
{
	if($id_agenda>0)
	{
		$txt_agenda_occupe = "";
		foreach(liste_evenements($id_agenda,$debut_unix,$fin_unix,false,"tout") as $evt_tmp)
		{
			$evt_debut_unix = strtotime($evt_tmp["date_debut"]);
			$evt_fin_unix	= strtotime($evt_tmp["date_fin"]);
			// Autre evenement que celui courant (en cas de modif)  +  évite que 11h->12h collisionne avec 12h->13h (evenements qui s'enchainent)
			if($evt_tmp["id_evenement"]!=@$_GET["id_evenement"]  &&  ($evt_debut_unix==$debut_unix || ($evt_tmp["periodicite_type"]=="" && $evt_debut_unix!=$debut_unix && $evt_debut_unix!=$fin_unix && $evt_fin_unix!=$debut_unix))){
				if(droit_acces($objet["evenement"],$evt_tmp,false)>=0.5)	{$txt_agenda_occupe .= "[".temps($evt_tmp["date_debut"],"mini",$evt_tmp["date_fin"])."] &nbsp; &nbsp; ";}
				else														{$txt_agenda_occupe .= "&nbsp;";}//Garde tout de même pour valider le texte...
			}
		}
		if($txt_agenda_occupe!="")	{$txt_creneaux_occupes .= "<div style='margin-top:2px;'>".$AGENDAS_AFFECTATIONS[$id_agenda]["titre"]." &nbsp; &nbsp; ".$txt_agenda_occupe."</div>";}
	}
}
if($txt_creneaux_occupes!="")	{echo "creneau_occupe".$trad["AGENDA_creneau_occupe"].$txt_creneaux_occupes;}
?>