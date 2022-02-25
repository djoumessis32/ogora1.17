<?php
////	INIT
if($_REQUEST["type_export_import"]=="users")	{require "../module_utilisateurs/commun.inc.php";}
else{
	require "../module_contact/commun.inc.php";
	droit_acces_controler($objet["contact_dossier"], $_REQUEST["id_dossier"], 1);
}
require "contact_import_export.inc.php";


////	EXPORTE LES CONTACTS / UTILISATEURS
////
if(isset($_REQUEST["type_export_import"]) && isset($_POST["export_format"]))
{
	////	LISTE DES CONTACTS
	$contenu_export = "";
	if($_REQUEST["type_export_import"]=="users")	{$liste_contacts = db_tableau("SELECT * FROM gt_utilisateur WHERE 1 ".sql_utilisateurs_espace());}
	else											{$liste_contacts = db_tableau("SELECT * FROM gt_contact WHERE id_dossier='".intval($_REQUEST["id_dossier"])."' ".sql_affichage($objet["contact"],$_REQUEST["id_dossier"]));}
	////	EXPORT CSV
	if(preg_match("/csv/i",$_POST["export_format"]))
	{
		// INIT
		$tab_csv = $formats_csv[$_POST["export_format"]];
		$nom_fichier = $_POST["export_format"].".csv";
		// ENTETE DU FICHIER CSV
		foreach($tab_csv["champs"] as $champ_agora => $champ_csv)	{ $contenu_export .= $tab_csv["delimiteur"].$champ_csv.$tab_csv["delimiteur"].$tab_csv["separateur"]; }
		$contenu_export .= "\n";
		// AJOUT DE CHAQUE CONTACT (exporte les champs de chaque contacts)
		foreach($liste_contacts as $contact)
		{
			foreach($tab_csv["champs"] as $champ_agora => $champ_csv)
			{
				if($tab_csv["delimiteur"]=="'")		{$contact[$champ_agora] = addslashes($contact[$champ_agora]);}
				if(isset($contact[$champ_agora]) && $contact[$champ_agora]!="")		{$contenu_export .= $tab_csv["delimiteur"].$contact[$champ_agora].$tab_csv["delimiteur"].$tab_csv["separateur"];}
				else																{$contenu_export .= $tab_csv["separateur"];}
			}
			$contenu_export .= "\n";
		}
	}
	////	EXPORT LDIF
	elseif($_POST["export_format"]=="ldif")
	{
		// INIT
		$nom_fichier = "contact.ldif";
		// AJOUT DE CHAQUE CONTACT
		foreach($liste_contacts as $contact)
		{
			$contenu_export .= "dn: cn=".$contact["prenom"]." ".$contact["nom"]."\n";
			$contenu_export .= "objectclass: top\n";
			$contenu_export .= "objectclass: person\n";
			$contenu_export .= "objectclass: organizationalPerson\n";
			$contenu_export .= "cn: ".$contact["prenom"]." ".$contact["nom"]."\n";
			$contenu_export .= "givenName: ".$contact["prenom"]."\n";
			$contenu_export .= "sn: ".$contact["nom"]."\n";
			if($contact["mail"]!="")				{$contenu_export .= "mail: ".$contact["mail"]."\n";}
			if($contact["telephone"]!="")			{$contenu_export .= "homePhone: ".$contact["telephone"]."\n";}
			if($contact["telephone"]!="")			{$contenu_export .= "telephonenumber: ".$contact["telephone"]."\n";}
			if($contact["fax"]!="")					{$contenu_export .= "fax: ".$contact["fax"]."\n";}
			if($contact["telmobile"]!="")			{$contenu_export .= "mobile: ".$contact["telmobile"]."\n";}
			if($contact["adresse"]!="")				{$contenu_export .= "homeStreet: ".$contact["adresse"]."\n";}
			if($contact["ville"]!="")				{$contenu_export .= "mozillaHomeLocalityName: ".$contact["ville"]."\n";}
			if($contact["codepostal"]!="")			{$contenu_export .= "mozillaHomePostalCode: ".$contact["codepostal"]."\n";}
			if($contact["pays"]!="")				{$contenu_export .= "mozillaHomeCountryName: ".$contact["pays"]."\n";}
			if($contact["societe_organisme"]!="")	{$contenu_export .= "company: ".$contact["societe_organisme"]."\n";}
			if($contact["fonction"]!="")			{$contenu_export .= "title: ".$contact["fonction"]."\n";}
			if($contact["commentaire"]!="")			{$contenu_export .= "description: ".$contact["commentaire"]."\n";}
			$contenu_export .= "\n";
		}
	}
	/////   LANCEMENT DU TELECHARGEMENT
	telecharger($nom_fichier, null, $contenu_export);
	exit;
}


////	HEADER & TITRE DU POPUP
////
require_once PATH_INC."header.inc.php";
titre_popup(menu_import_export());
?>


<script type="text/javascript"> resizePopupLightbox(500,250); </script>
<style type="text/css">
body { background-image:url('<?php echo PATH_TPL; ?>module_utilisateurs/fond_popup.png'); font-weight:bold; }
</style>


<form action="<?php echo php_self(); ?>" method="post" style="text-align:center;">
	<?php echo $trad["export_format"]; ?>
	<select name="export_format">
		<?php
		foreach($formats_csv as $format_csv=>$infos_csv)	{ echo "<option value='".$format_csv."'>".strtoupper($format_csv)."</option>"; }
		?>
		<option value='ldif'>LDIF</option>
	</select> &nbsp; 
	<input type="submit" value="<?php echo $trad["valider"]; ?>" class="button" />
	<input type="hidden" name="type_export_import" value="<?php echo $_REQUEST["type_export_import"]; ?>" />
	<input type="hidden" name="id_dossier" value="<?php echo @$_REQUEST["id_dossier"]; ?>" />
</form>


<?php require PATH_INC."footer.inc.php"; ?>