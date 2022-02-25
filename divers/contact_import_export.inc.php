<?php
////	MENU IMPORT / EXPORT
////
function menu_import_export()
{
	global $trad;
	$import_preselect = preg_match("/import/i",php_self())  ?  "selected"  :  "";
	return "<select name='export_import' onChange=\"redir(this.value);\" style='font-size:12px'>
				<option value=\"contact_export.php".variables_get()."\">".$trad["exporter"]."</option>
				<option value=\"contact_import.php".variables_get()."\" ".$import_preselect.">".$trad["importer"]."</option>
			</select>&nbsp; ".$trad["export_import_".$_REQUEST["type_export_import"]];
}


////	LISTE DES FORMATS .CSV  :  CHAMP AGORA => CHAMP SPECIFIQUE
////
$formats_csv = array(
	////	AGORA
	"csv_agora" => array(
		"separateur" => ";",
		"delimiteur" => '"',
		"champs" => array(
			"civilite" => "civilite",
			"nom" => "nom",
			"prenom" => "prenom",
			"societe_organisme" => "societe_organisme",
			"fonction" => "fonction",
			"adresse" => "adresse",
			"codepostal" => "codepostal",
			"ville" => "ville",
			"pays" => "pays",
			"telephone" => "telephone",
			"telmobile" => "telmobile",
			"fax" => "fax",
			"mail" => "mail",
			"siteweb" => "siteweb",
			"competences" => "competences",
			"hobbies" => "hobbies",
			"commentaire" => "commentaire",
			"identifiant" => "identifiant",
			"pass" => "pass"
		)
	),
	////	GMAIL
	"csv_gmail" => array(
		"separateur" => ",",
		"delimiteur" => "",
		"champs" => array(
			"prenom" => "Given Name",
			"nom" => "Family Name",
			"mail" => "E-mail 1 - Value",
			"fax" => "Fax",
			"telmobile" => "Phone 1 - Value",
			"siteweb" => "Site Web",
			"fonction" => "Fonction",
			"societe_organisme" => "Société",
			"adresse" => "Address 1 - Street",
			"ville" => "Address 1 - City",
			"codepostal" => "Address 1 - Postal Code",
			"pays" => "Address 1 - Country",
			"commentaire" => "Notes",
			"commentaire" => "Commentaires"
		)
	),
	////	YAHOO
	"csv_yahoo" => array(
		"separateur" => ",",
		"delimiteur" => '"',
		"champs" => array(
			"prenom" => "Premier",
			"nom" => "Dernier",
			"mail" => "Mail",
			"fax" => "Fax",
			"telmobile" => "Tél. mobile",
			"siteweb" => "Site Web",
			"fonction" => "Fonction",
			"societe_organisme" => "Société",
			"adresse" => "Domicile",
			"ville" => "Ville (domicile)",
			"codepostal" => "Code postal (domicile)",
			"pays" => "Pays (domicile)",
			"hobbies" => "Hobby"
		)
	),
	////	OUTLOOK
	"csv_outlook" => array(
		"separateur" => ",",
		"delimiteur" => '"',
		"champs" => array(
			"prenom" => "Prénom",
			"nom" => "Nom",
			"societe_organisme" => "Société",
			"fonction" => "Fonction",
			"adresse" => "Rue (domicile)",
			"ville" => "Ville (domicile)",
			"adresse" => "Code postal (domicile)",
			"pays" => "Pays (domicile)",
			"fax" => "Fax (domicile)",
			"telephone" => "Téléphone (domicile)",
			"telmobile" => "Tél. mobile",
			"mail" => "Adresse mail",
			"commentaire" => "Notes",
			"siteweb" => "Page Web"
		)
	),
	////	HOTMAIL
	"csv_hotmail" => array(
		"separateur" => ";",
		"delimiteur" => '"',
		"champs" => array(
			"civilite" => "Title",
			"prenom" => "First Name",
			"Middle Name" => "Middle Name",
			"nom" => "Last Name",
			"societe_organisme" => "Company",
			"Department" => "Department",
			"fonction" => "Job Title",
			"adresse" => "Home Street",
			"ville" => "Home City",
			"codepostal" => "Home Postal Code",
			"pays" => "Home Country",
			"fax" => "Home Fax",
			"telephone" => "Home Phone",
			"telmobile" => "Mobile Phone",
			"mail" => "E-mail Address",
			"hobbies" => "Hobby",
			"commentaire" => "Notes",
			"siteweb" => "Web Page"
		)
	),
	////	THUNDERBIRD
	"csv_thunderbird" => array(
		"separateur" => ",",
		"delimiteur" => "",
		"champs" => array(
				"prenom" => "Prénom",
				"nom" => "Nom de famille",
				"mail" => "Première adresse électronique",
				"telephone" => "Tél. personnel",
				"fax" => "Fax",
				"telmobile" => "Portable",
				"adresse" => "Adresse privée",
				"ville" => "Ville",
				"pays" => "Pays/État",
				"codepostal" => "Code postal",
				"fonction" => "Profession",
				"societe_organisme" => "Société",
				"siteweb" => "Page Web 1",
				"commentaire" => "Notes"
		)
	)
);
?>