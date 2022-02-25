<?php
////	INIT
require_once "../".$_GET["module_path"]."/commun.inc.php";
require_once PATH_INC."header.inc.php";
?>


<meta name="viewport" content="initial-scale=1.0, user-scalable=no"></meta>  <!-- Carte affiché en plein écran et sans redimensionnement possible -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>  <!-- API Google MAPS (sensor=false -> pas de positionnement de l'utilisateur) -->
<script type="text/javascript">
////	UTILISATEURS / CONTACTS A AFFICHER  ("SelectedElems[contact]=1-2-3" -> "array(1,2,3)")
var adresses = [];
<?php
$liste_personnes = (!empty($_GET["SelectedElems"]["contact"]))  ?  explode("-",$_GET["SelectedElems"]["contact"])  :  explode("-",$_GET["SelectedElems"]["utilisateur"]);
foreach($liste_personnes as $id_personne)
{
	$photo_personne = PATH_TPL."module_utilisateurs/user.png";
	// Infos sur l'utilisateur / le contact  (avec controle d'accès)
	if(!empty($_GET["SelectedElems"]["utilisateur"])){
		$personne_tmp = user_infos($id_personne);
		if(controle_affichage_utilisateur($id_personne,"bool")==false)	continue;
		if($personne_tmp["photo"]!="")	$photo_personne = PATH_PHOTOS_USER.$personne_tmp["photo"];
	}else{
		$personne_tmp = objet_infos($objet["contact"],$id_personne);
		if(droit_acces($objet["contact"],$personne_tmp)<1)	continue;
		if($personne_tmp["photo"]!="")	$photo_personne = PATH_PHOTOS_CONTACT.$personne_tmp["photo"];
	}
	// Ajoute la personne au tableau javascript (adresse, libelle, etc)
	if($personne_tmp["adresse"]!="" || $personne_tmp["codepostal"]!="" || $personne_tmp["ville"]!="" || $personne_tmp["pays"]!="")
	{
		$adresse_tmp = trim($personne_tmp["adresse"].", ".$personne_tmp["codepostal"]." ".$personne_tmp["ville"]." ".$personne_tmp["pays"],  ", ");
		$libelle_tmp = htmlspecialchars(trim($personne_tmp["nom"]." ".$personne_tmp["prenom"]." - ".$personne_tmp["fonction"]." - ".$personne_tmp["societe_organisme"]." - ".$adresse_tmp,  " - "));
		echo 'adresses.push( {"adresse":"'.$adresse_tmp.'", "libelle":"'.$libelle_tmp.'", "photo":"'.$photo_personne.'"} );';
	}
}
?>

////	INITIALISE LA CARTE GOOGLE MAP
function initialize_map()
{
	// Charge la carte (avec options)  +  Instancie le gécodeur  +  Objet définissant les limites de la carte
	map		 = new google.maps.Map(document.getElementById("map_canvas"), {zoom:8, mapTypeId:google.maps.MapTypeId.ROADMAP});
	geocoder = new google.maps.Geocoder();
	bounds	 = new google.maps.LatLngBounds();
	// Géocode et marque chaque adresse
	for(key in adresses)	{ geocoderMarkerAddresse(key); }
	//dimensionne le conteur "map_canvas", puis redimentionne le fancybox (car on ne peut donner à "map_canvas" des dimensions en % : cf. "doctype html")
	$(document).ready(function(){
		$('#map_canvas').css('width',$(window.parent).width()+'px');
		$('#map_canvas').css('height',($(window.parent).height()-100)+'px');
		parent.$.fancybox.update();
	});
}

////	GEOCODER + MARKER UNE ADRESSE
function geocoderMarkerAddresse(key)
{
	// Latitude et Longitude en fonction de l'adresse
	geocoder.geocode( {'address':adresses[key]['adresse']}, function(results, status)
	{
		// Géolocalisation OK
		if(status==google.maps.GeocoderStatus.OK)
		{
			// Récupère la latitude et longitude
			adresses[key]['lat'] = results[0].geometry.location.lat();
			adresses[key]['lng'] = results[0].geometry.location.lng();
			// Prépare l'icone du marker (url + position de la photo par rapport au point du marker = centre/bottom + dimension de a photo)
			iconePhoto = new google.maps.MarkerImage(adresses[key]['photo'], null, null, new google.maps.Point(18,0), new google.maps.Size(36,36));
			// Ajoute le marker
			adresses[key]['marker'] = new google.maps.Marker({
				map:map,
				title:adresses[key]['libelle'],
				position:results[0].geometry.location,
				icon:iconePhoto
			});
			// Infobulle du marqueur
			adresses[key]['infobulle_html'] = adresses[key]['libelle']+"<div id='streetView"+key+"' style='width:500px;height:300px;'>Street View loading ...</div>";
			adresses[key]['infobulle'] = new google.maps.InfoWindow( {content:adresses[key]['infobulle_html']} );
			google.maps.event.addListener(adresses[key]['marker'], 'click', function() {
				adresses[key]['infobulle'].open(map, adresses[key]['marker']);
				setTimeout("displayStreetView("+key+");",1000);
			});
			// Etend et repositionne la carte
			bounds.extend(new google.maps.LatLng(adresses[key]['lat'],adresses[key]['lng']));
			map.fitBounds(bounds);
		}
	});
}

////	APPEL DE StreetView UNE FOIS L'INFOBULLE CHARGEE
function displayStreetView(key)
{
	StreetView = new google.maps.StreetViewPanorama(element("streetView"+key));
	StreetView.setPosition(new google.maps.LatLng(adresses[key]['lat'],adresses[key]['lng']));
}

////	LANCE L'AFFICHAGE DE LA CARTE
setTimeout("initialize_map()",700);

////	REDIMENSIONNE
resizePopupLightbox('90%','90%');
</script>


<div id="map_canvas"></div>


<?php require PATH_INC."footer.inc.php"; ?>