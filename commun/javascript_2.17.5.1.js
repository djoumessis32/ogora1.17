////	ON SELECTIONNE L'ELEMENT PAR SON ATTRIBUT "ID" OU "NAME"
////
function element(id_elem)
{
	elem_id   = document.getElementById(id_elem);
	elem_name = document.getElementsByName(id_elem)[0];
	if(typeof elem_id!=="undefined" && elem_id!=null)			{return elem_id;}
	else if(typeof elem_name!=="undefined" && elem_name!=null)	{return elem_name;}
	else														{return false;}
}


////	ON DONNE UNE VALEUR A UN ELEMENT
////
function set_value(id_elem, valeur, exclure_null)
{
	//on ne fait rien si "exclure_null" est true et qu'il n'y a pas de valeur..
	if(exclure_null!=true || valeur!="")	{element(id_elem).value = valeur;}
}


////	ON RECUPERE LA VALEUR D'UN ELEMENT
////
function get_value(id_elem)
{
	return element(id_elem).value;
}


////	VERIFIE L'EXISTANCE D'UN ELEMENT
////
function existe(id_elem)
{
	if(element(id_elem)!=false)	 return true;
	else						 return false;
}


////	SELECTION D'UNE CHECKBOX / BOUTON RADIO
////
function set_check(id_elem, valeur)
{
	if(existe(id_elem))
	{
		if (valeur==true)			{ element(id_elem).checked = true; }
		else if (valeur==false)		{ element(id_elem).checked = false; }
		else if (valeur=="bascule")
		{
			if(element(id_elem).checked==true)	element(id_elem).checked = false;
			else								element(id_elem).checked = true;
		}
	}
}


////	CHECKBOX / BOUTON RADIO SELECTIONNE ?
////
function is_checked(id_elem)
{
	if (existe(id_elem) && element(id_elem).checked==true)	{return true;}
	else													{return false;}
}


////	NB DE CHECKBOX SELECTIONNEES (TABLEAU)
////
function nb_box_checked(id_elem)
{
	var checked = 0;
	tab_checkbox = document.getElementsByName(id_elem);
	for(var i=0; i<tab_checkbox.length; i++)	{ if(tab_checkbox[i].checked==true){checked++;} }
	return checked;
}


////	BASCULEMENT D'UNE CHECKBOX ET DU STYLE DU TEXTE ASSOCIE
////
function checkbox_text(this_element, style_select)
{
	// Id de l'element cliqué  /  Style du texte  /  Id du texte + Id de la checkbox
	id_element = (typeof(this_element)=="string")  ?  this_element  :  this_element.id;
	if(typeof style_select==="undefined" || style_select==null)		{style_select = "lien_select";}
	racine_id = id_element.replace("box_","").replace("txt_","");
	id_txt = "txt_"+racine_id;
	id_box = "box_"+racine_id;
	// Check / Uncheck  =>  bascule la valeur de la checkbox + change la couleur du text
	if(element(id_box).disabled==false){
		if(element(id_element).type!="checkbox")	set_check(id_box,"bascule");
		element(id_txt).className = (is_checked(id_box))  ?  style_select  :  "lien";
	}
}


////	CONTROLE D'UN MAIL
////
function controle_mail(email)
{
	var express_reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return express_reg.test(email);
}


////	CHERCHE UNE EXPRESSION DANS UNE CHAINE DE CARACTERES
////
function trouver(expression, chaine)
{
	return (chaine.search(expression)==-1)  ?  false  :  true;
}


////	CONFIRMATION AVANT REDIRECTION
////
function confirmer(text_confirm, adresse)
{
	if(confirm(text_confirm)==true)	redir(adresse);
}


////	REDIRECTION D'UNE PAGE
////
function redir(adresse)
{
	window.location.href = adresse;
}


////	 CONTRÔLE DE CONNEXION À L'AGORA
////
function controle_connexion(text_alert, login, password)
{
	if(get_value("login")=="" || get_value("login")==login || get_value("password")=="" || get_value("password")==password){
		alert(text_alert);
		return false;
	}
}


////	AFFICHAGE OU MASQUAGE D'UN DIV  (afficher->true|false|bascule  &  style_display->inline|block|table|etc..  &  noResizeLightbox->true|false)
////
function afficher(id_elem, afficher, style_display, noResizeLightbox)
{
	// Init
	if(typeof afficher==="undefined" || afficher==null)				{afficher = "bascule";}
	if(typeof style_display==="undefined" || style_display==null)	{style_display = "inline";}
	// Affiche / Masque
	if(afficher==true || (afficher=="bascule" && $('#'+id_elem).css('display')=="none"))	{$('#'+id_elem).css('display',style_display);}
	else																					{$('#'+id_elem).css('display','none');}
	// Redimentionne la lightbox ? (option à 'true' pour l'ajout de notif ou de pièce jointe d'un element : bug sous chrome)
	if(noResizeLightbox!==true)	{resizePopupLightbox();}
}


////	AFFICHAGE OU MASQUAGE D'UN DIV AVEC FADING  (afficher->true|false  &  type_affichage->show|fade)
////
function afficher_dynamic(id_elem, afficher, type_affichage)
{
	// "Afficher" non défini -> on switch l'affichage (garder l'appel jQuery pour récupérer le type d'affichage.. sinon ça bug)
	if(typeof afficher==="undefined" || afficher==null){
		afficher = ($('#'+id_elem).css('display')=="none")  ?  true  :  false;
	}
	// Affichage en mode "slide" / "fade"
	if(typeof type_affichage==="undefined" || type_affichage==null || type_affichage=="show"){
		if(afficher==true)	{$("#"+id_elem).slideDown();}
		else				{$("#"+id_elem).slideUp();}
	}
	else if(type_affichage=="fade"){
		if(afficher==true)	{$("#"+id_elem).fadeIn(200);}
		else				{$("#"+id_elem).fadeOut(200);}
	}
	// Redimentionne la lightbox + si le contenu est plus petit que la page parent (avec un temps de décalage, le temp de finir l'affichage dynamique en 200ms)
	if($(document).height() < $(window.parent).height())	{setTimeout("resizePopupLightbox()",700);}
	return afficher;
}


////	OUVRE UN POPUP OU UNE LIGHTBOX
////
function popupLightbox(url, forcePopup)
{
	////	ON EST DÉJÀ DANS UNE POPUP => REDIRECTION
	if(inPopup() && forcePopup!=true)
	{
		redir(url);
	}
	////	OUVRE UN POPUP  (PARAMETRAGE PAR DÉFAUT OU POPUP FORCÉ)
	else if(edition_popup==1 || forcePopup==true)
	{
		nom = (navigateur()=='ie')  ?  Math.floor(Math.random()*1000)  :  url;
		window.open(url, nom, "width=600,height=400,left=100,top=100,scrollbars=yes,resizable=yes,directories=no,location=no,menubar=no,status=no,toolbar=no,dependent=yes");
	}	
	////	OUVRE UN LIGHTBOX 
	else
	{
		// FONCTION LANCEE DEPUIS UNE LIGHTBOX => ON ON RELANCE DEPUIS LA FENETRE PARENTE
		if(inLightbox())
		{
			parent.popupLightbox(url);
		}
		//OUVRE UN PDF ?
		else if(trouver("pdf",url))
		{
			proprietesPdf = "type='application/pdf' height='"+Math.floor($(window).height()-100)+"px' width='"+Math.floor($(window).width()-100)+"px'";
			$("#lightbox_content").fancybox({
				type:"html",//affichage de type "Embed"
				content: "<object data=\""+url+"\" "+proprietesPdf+"><embed src=\""+url+"\" "+proprietesPdf+" /></object>"
			}).click();
			//redimentionne en décalé, le temps que le pdf soit chargé
			setTimeout(function(){
				$.fancybox.update();
			},2000);
		}
		// IFRAME (AFFICHAGE NORMAL. HEIGHT=AUTO)
		else
		{
			//"parent" au cas où l'action est lancé depuis l'iframe du fancybox
			$("#lightbox_content").fancybox({
				type:"iframe",
				href:url,
				autoWidth:true,
				openSpeed:200,
				//Récupère le width de l'iframe (de son 'body') initialisé par resizePopupLightbox() => on dimentionne le lightbox en fonction
				beforeShow:function(){
					this.width = $(".fancybox-iframe").contents().find('body').width();
				},
				//Demande confirmation de fermeture si on edite un element
				beforeClose:function(){
					if(trouver("edit",url) && !confirm(confirm_close_lightbox))	{return false;}
				}
			}).click();
		}
	}
}


////	REDIMENTIONNE UN POPUP / UNE LIGHTBOX  => LANCE DEPUIS SON CONTENU !
////
function resizePopupLightbox(width, height)
{
	// LARGEUR ET HAUTEUR (CONVERTIT EN PIXEL SI ON A DU TEXTE/POURCENTAGE EN ENTRÉE)
	if(typeof width=="string"){
		width=width.replace("px","");
		width=(screen.width/100) * width.replace("%","");
	}
	if(typeof height=="string"){
		height=height.replace("px","");
		height=(screen.height/100) * height.replace("%","");
	}
	//POPUP
	if(inPopup() && typeof width==="number"){
		window.resizeTo(width, height);
	}
	//LIGHTBOX : REDIMENSION AUTO  OU  DIMENTION DU "BODY" DE L'IFRAME (RÉUTILISÉ PAR POPUPLIGHTBOX()!)
	else if(inLightbox()){
		if(typeof width==="undefined")		{parent.$.fancybox.update();}
		else if (typeof width==="number")	{$('body').css('width',width);}
	}
}


////	AFFICHER UN "PROMPT" VIA LE LIGHTBOX
////
function promptLightbox(libelle, action_js, input_type, default_value)
{
	if(typeof input_type==="undefined" || input_type==null)			{input_type = "text";}	//"text" ou "password"
	if(typeof default_value==="undefined" || default_value==null)	{default_value = "";}	//valeur par défaut dans l'input
	// Construit le formulaire
	formulaire = "<h3>"+libelle+"</h3>";
	formulaire += "<input type='"+input_type+"' value='"+default_value+"' id='promptInputText' /> &nbsp; ";
	formulaire += "<input type='button' value='OK' onClick=\"if(get_value('promptInputText')!='') "+action_js+"\" />";
	// Affiche le prompt et met le focus sur l'input
	$("#lightbox_content").fancybox({width:400,content:formulaire}).click();
	element("promptInputText").focus();
}


////	ON EST DANS UN POPUP OU UNE LIGHTBOX ?
////
function inPopup()
{
	return (window.opener && window.opener.location!=window.location)  ?  true  :  false;
}
function inLightbox()
{
	return (window.parent && window.parent.location!=window.location)  ?  true  :  false;
}


////	RECHARGE LA PAGE .PARENT  OU  RECHARGE LA PAGE .OPENER + FERME LE POPUP
////
function reload_close(url)
{
	////	Page spécifique ?  URL du .parent de l'Iframe-lightbox ?  URL du .opener du Popup ?
	if(typeof url!=="undefined" && url!=null && url!="")	{page_redir = url;}
	else if(inLightbox())									{page_redir = window.parent.location;}
	else													{page_redir = window.opener.location;}
	////	Rechagement depuis lightBox ou Popup ?
	if(inLightbox()){window.parent.location.replace(page_redir);}
	else			{window.opener.location.replace(page_redir);  window.close();}
}


////	ANCRE NOMMEE : SCROLL VERS UN ELEMENT AVEC JQUERY
////
function goToByScroll(id_elem)
{
	$('html,body').animate({scrollTop: $("#"+id_elem).offset().top},'slow');
}


////	AFFICHAGE / MASQUAGE DU MENU CONTEXTUEL (D'UN ELEMENT PAR EXEMPLE)
////
function menu_contextuel(id_menu, id_div_clickDroit)
{
	////	INIT
	var id_icone_menu = "#icone_"+id_menu;

	////	AFFICHE ET POSITIONNE LE MENU CONTEXTUEL
	function menu_contextuel_position(isClickDroit)
	{
		// Affiche le menu & redimensionne si besoin (les menus depuis l'icone "option_inline.png" posent problemes sur certains browsers..)
		afficher_dynamic(id_menu, true, "fade");
		if(element(id_menu).offsetWidth < 250  &&  typeof $(id_icone_menu).attr("src")!=="undefined"  &&  trouver("inline",$(id_icone_menu).attr("src")))
			{element(id_menu).style.width = "250px";}

		// Position de la souris depuis un click droit (sauf pour les evts d'agenda & pour IE<=8 (fait "clignoter" le menu au survol))
		if(isClickDroit==true  &&  trouver("evenement",id_menu)==false  &&  typeof posSourisX!=="undefined"){
			posMenuX = posSourisX;
			posMenuY = posSourisY;
			decalagePosMenu = 12;
		}
		// Position de l'icone survolé faisant apparaitre le menu ("plus" ou autre)
		else{
			position_icone_parent = $(id_icone_menu).position();
			posMenuX = position_icone_parent.left;
			posMenuY = position_icone_parent.top;
			decalagePosMenu = 2;
		}

		// Position normale du menu (position de référence : souris/icone)   OU   position décalée si on est en bordure de page  (Si "(position top menu + hauteur menu) > hauteur page" Alors "position top menu = position top menu - hauteur menu")
		menuX = ((posMenuX + element(id_menu).offsetWidth) < $(window).width())		?  (posMenuX - decalagePosMenu)  :  (posMenuX - element(id_menu).offsetWidth + 5);
		menuY = ((posMenuY + element(id_menu).offsetHeight) < $(window).height())	?  (posMenuY - decalagePosMenu)  :  (posMenuY - element(id_menu).offsetHeight + 5);
		element(id_menu).style.left = menuX+"px";
		element(id_menu).style.top = menuY+"px";
	
		// Appareil mobile : affiche une icone "fermer"
		if(isMobileDevice() && trouver('close.png',element(id_menu).innerHTML)==false)
			element(id_menu).innerHTML = "<img src='../templates/divers/close.png' class='lien'style='height:25px;margin:-10px;float:right;' onClick=\"afficher('"+id_menu+"',false);\" />"+element(id_menu).innerHTML;
	}

	////	APPAREIL MOBILE (TABLETTE OU AUTRE) => CLICK SUR L'ICONE POUR AFFICHER LE MENU
	if(isMobileDevice())
	{
		$(id_icone_menu).click(function(){
			if($("#"+id_menu).css("display")=="none")	menu_contextuel_position(false);
			else										afficher(id_menu,false);
		});
	}
	////	APPAREIL CLASSIQUE (SOURIS/CLAVIER)
	else
	{
		////	POSITION DE LA SOURIS (sauf pour IE<=8)
		if(navigateur()!="ie" || version_ie()>8)
		{
			$(function(){
				$(document).mousemove(function(event){
					posSourisX = event.pageX;
					posSourisY = event.pageY;
				});
			});
		}

		////	AFFICHE LE MENU SI ON SURVOLE LE MENU / MASQUE LE MENU SI ON QUITTE LE MENU
		$("#"+id_menu).mouseover(function(){
			afficher(id_menu,true);
		}).mouseout(function(){
			afficher(id_menu,false);
		});

		////	AFFICHE LE MENU SI ON SURVOLE L'ICONE / MASQUE LE MENU SI ON QUITTE L'ICONE
		$(id_icone_menu).mouseover(function(){
			menu_contextuel_position(false);
		}).mouseout(function(){
			afficher(id_menu,false);
		});

		////	AFFICHE LE MENU SI ON CLICK DROIT SUR LE BLOCK D'UN ELEMENT (OPTIONNEL)
		if(typeof id_div_clickDroit!=="undefined" && id_div_clickDroit!=null && id_div_clickDroit!="")
		{
			$("#"+id_div_clickDroit).bind("contextmenu",function(event){
				event.stopPropagation();
				menu_contextuel_position(true);
				return false;//pour pas afficher le menu du browser..
			});
		}
	}
}


////	GESTION DU CLICK / DOUBLE CLICK SUR UN BLOCK D'ELEMENT (Jquery ne gère pas les 2 événements séparément.. d'où cette fonction)
////
function click_dblclick(id_element, action_click, action_dblclick)
{
	var clicks = 0, timer = null;
	$("#"+id_element)
	// on travaille uniquement sur les simples "click"
	.click(function(){
		clicks++;
		// Lance la fonction sur un Simple click OU sur un Double click
		if(clicks==1){
			timer = setTimeout(function(){
				if(typeof action_click!=="undefined" && action_click!=null)	eval(action_click);
				clicks = 0; //reset
			}, 400);// Delais qui différencie le click du dblclick..
		}
		else{
			clearTimeout(timer); //reset le simple click
			if(typeof action_dblclick!=="undefined" && action_dblclick!=null)	eval(action_dblclick);
			clicks = 0;//reset
		}
	});
}
////	NE PROBAGE PAS LE CLICK D'UN MENU CONTEXTUEL SUR SON BLOCK CONTENEUR (EVITE LES DOUBLES ACTIONS..)
$(document).ready(function(){
	$(".menu_context").click(function(event){
		event.stopPropagation();
	});
});


////	INFOBULLE 
////
function bulle(message)
{
	if(message!="" && (typeof isMouseDown==="undefined" || isMouseDown==false))
	{
		////	On positionne l'infobulle  (ne pas utiliser Jquery pour la position de la souris : trop lent sur IE!)
		function position_bulle(evenement)
		{
			positionSourisX = (navigateur()!="ie") ? evenement.pageX : event.clientX + document.body.scrollLeft;
			positionSourisY = (navigateur()!="ie") ? evenement.pageY : event.clientY + document.body.scrollTop;
			// Position de la souris OU position décalé si en bordure de page  =>  (position Top souris + Hauteur element) > Hauteur page  ->  position Top element = position Top souris - Hauteur element
			bulleX = ((positionSourisX + element("infobulle").offsetWidth) > $(window).width())    ?  (positionSourisX - element("infobulle").offsetWidth)   :  positionSourisX;
			bulleY = ((positionSourisY + element("infobulle").offsetHeight) > $(window).height())  ?  (positionSourisY - element("infobulle").offsetHeight)  :  positionSourisY + 15;
			// On place l'infobulle
			element("infobulle").style.left = bulleX+"px";
			element("infobulle").style.top = bulleY+"px";
		}

		////	Texte dans l'"infobulle"  (Déclarer le "display" au début, puis le "visibility" à la fin. Cela evite d'afficher l'ascenseur quand l'infobulle est inactive, en bord de page)
		element("infobulle").style.display = "block";
		element("infobulle").innerHTML = "<div class='infobulle_contenu' style='text-align:"+ ((message.length > 30)?'left;':'center;') +"'>"+message+"</div>";

		////	Affichage avec un temps de latence
		function affiche_bulle(){
			if(element("infobulle").style.left.replace("px","")>1)	element("infobulle").style.visibility = "visible";
		}
		document.onmousemove = position_bulle;
		timeoutID_bulle = window.setTimeout(affiche_bulle,300);
	}
}
function bullefin()
{
	if(typeof timeoutID_bulle!=="undefined"){
		window.clearTimeout(timeoutID_bulle);
		element("infobulle").style.display = "none";
		element("infobulle").style.visibility = "hidden";
		document.onmousemove = null;//conserver pour  IE7/8!
	}
}


////	LANCEMENT D'UNE REQUETE ASYNCHRONE VIA "XMLhttpRequest"  =>  UTILISER urlencode() DANS LES VARIABLES DE "page_requete" SI NECESSAIRE !!
////
function requete_ajax(page_requete)
{
	// XMLHttpRequest sous Firefox/chrome OU Internet Explorer
	if(window.XMLHttpRequest)		xhr_object = new XMLHttpRequest();
	else if(window.ActiveXObject)	xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
	else							xhr_object = null;

	// On lance la requete, en précisant au besoin les parametres en GET (true/false = asynchrone?)
	xhr_object.open("GET", page_requete, false);
	xhr_object.send(null);//ne pas enlever

	// On retourne le résultat
	if(xhr_object.readyState==4)	return retour_ajax = xhr_object.responseText;
}


////	RECUP' L'EXTENSION D'UN FICHIER
////
function extension(chemin_fichier)
{
	var tab_fichier = chemin_fichier.substring(chemin_fichier.lastIndexOf("\\")+1).split(".");
	if(tab_fichier.length > 0)	return tab_fichier[tab_fichier.length-1].toLowerCase();
}


////	FONCTION IN_ARRAY() SIMILAIRE A PHP
////
function in_array(tableau, valeur)
{
	for(var compteur=0; compteur < tableau.length; compteur++){
		if(tableau[compteur]==valeur)	return true;
	}
	return false;
}


////	CONTENU DU TinyMCE VIDE ?
////
function tinymce_vide(nom_champ)
{
	contenu = tinyMCE.get(nom_champ).getContent();
	if(contenu.length==0 || contenu=="<div>&nbsp;</div>")	return true;
}


////	URLENCODE POUR JAVASCRIPT
////
function urlencode(texte)
{
	texte = texte.replace('%','%25');//toujours en premier!
	texte = texte.replace(' ','%20');
	texte = texte.replace('!','%21');
	texte = texte.replace('"','%22');
	texte = texte.replace('#','%23');
	texte = texte.replace('$','%24');
	texte = texte.replace('&','%26');
	texte = texte.replace('\'','%27');
	texte = texte.replace('(','%28');
	texte = texte.replace(')','%29');
	texte = texte.replace('*','%2A');
	texte = texte.replace('+','%2B');
	texte = texte.replace(',','%2C');
	texte = texte.replace('-','%2D');
	texte = texte.replace('.','%2E');
	texte = texte.replace('/','%2F');
	texte = texte.replace(':','%3A');
	texte = texte.replace(';','%3B');
	texte = texte.replace('<','%3C');
	texte = texte.replace('=','%3D');
	texte = texte.replace('>','%3E');
	texte = texte.replace('?','%3F');
	texte = texte.replace('@','%40');
	texte = texte.replace('[','%5B');
	texte = texte.replace('\\','%5C');
	texte = texte.replace(']','%5D');
	texte = texte.replace('^','%5E');
	texte = texte.replace('_','%5F');
	texte = texte.replace('`','%60');
	texte = texte.replace('{','%7B');
	texte = texte.replace('|','%7C');
	texte = texte.replace('}','%7D');
	texte = texte.replace('~','%7E');
	texte = texte.replace('£', '%A3');
	texte = texte.replace('§', '%A7');
	texte = texte.replace('@', '%40');
	return texte;
}


////	CHANGE LA COULEUR ET LE STYLE D'UN INPUT <SELECT>  (TEXT+BACKGROUND)
////
function style_select(input_name)
{
	input = element(input_name);
	element(input_name).style.color = element(input_name).options[element(input_name).selectedIndex].style.color;
	element(input_name).style.fontWeight = element(input_name).options[element(input_name).selectedIndex].style.fontWeight;
	element(input_name).style.backgroundColor = element(input_name).options[element(input_name).selectedIndex].style.backgroundColor;
}


////	PLACER UN DIV EN BAS D'UN BLOCK CONTENEUR  (ON JOUE SUR LE "MARGINTOP" DU DIV A REPLACER)  +  LE DIV DOIT FAIRE LA LARGEUR DU CONTENEUR ?
////
function div_bas_conteneur(id_block_conteneur, id_block_cible, div_largeur_conteneur)
{
	$("#"+id_block_conteneur).ready(function(){
		hauteur_block_cible = (element(id_block_cible).offsetHeight < 1)  ?  20  :  element(id_block_cible).offsetHeight;  // Chrome : 20px par défaut pour compenser une erreur d'affichage...
		element(id_block_cible).style.marginTop = (element(id_block_conteneur).offsetHeight - hauteur_block_cible)+'px';
		if(typeof div_largeur_conteneur!=="undefined" && div_largeur_conteneur!=null)	element(id_block_cible).style.width = element(id_block_conteneur).clientWidth+'px';
	});
}


////	LE DIV DOIT FAIRE LA TAILLE DU CONTENEUR
////
function div_taille_conteneur(id_block_conteneur, id_block_cible)
{
	element(id_block_cible).style.width = element(id_block_conteneur).clientWidth+'px';
	element(id_block_cible).style.height = element(id_block_conteneur).clientHeight+'px';
}


////	MENU FLOTTANT
////
function menuFlottant()
{
	if(existe('menu_gauche_block_flottant') && isMobileDevice()==false)
	{
		//Hauteur page html  >  (hauteur menu de gauche + hauteur barre menu principal)  =>  float du menu !
		if(document.documentElement.clientHeight > (element('contenu_principal_table').offsetTop + element('menu_gauche_block_flottant').offsetHeight)){
			if(typeof menuFlottantOn==="undefined")	{$('#menu_gauche_block_flottant').makeFloat({x:'current',y:'current',Speed:'fast'});  menuFlottantOn=true;}	//Initialise le menuFlottant
			else									{$('#menu_gauche_block_flottant').restartFloat();}															//Relance le menuFlottant
		}
		//Sinon Stop le menu flottant
		else {$('#menu_gauche_block_flottant').stopFloat();}
	}
}


////	TYPE DE NAVIGATEUR
////
function navigateur()
{
	nav = navigator.userAgent.toLowerCase();
	if(trouver("msie",nav))				return "ie";
	else if(trouver("firefox",nav))		return "firefox";
	else if(trouver("chrome",nav))		return "chrome";
	else if(trouver("safari",nav))		return "safari";
	else if(trouver("webkit",nav))		return "webkit";
	else if(trouver("opera",nav))		return "opera";
	else if(trouver("netscape",nav))	return "netscape";
}


////	VERSION D'INTERNET EXPLORER
////
function version_ie()
{
	if(navigateur()=="ie") {
		var ms_version = navigator.appVersion.split("MSIE");
		return parseFloat(ms_version[1]);
	}
}


////	ON EST SUR UN APPAREIL MOBILE ?
////
function isMobileDevice()
{
	//return (/Android|iPhone|iPad|iPod|BlackBerry|windows phone|tablet|Touch/i.test(navigator.userAgent))  ?  false  :  true;
	return (/Android|iPhone|iPad|iPod|BlackBerry|windows phone|tablet|Touch/i.test(navigator.userAgent))  ?  true  :  false;
}


////	SELECTION D'UN GROUPE D'UTILISATEURS
////
function selection_groupe(id_groupe)
{
	// Groupe sélectionné / déselectionné?
	groupe_selected = (is_checked("box_"+id_groupe))  ?  true  :  false;
	// check/décheck les users du groupe
	for(var i=0; i < users_ensembles[id_groupe].length; i++)
	{
		// Init
		user_tmp = users_ensembles[id_groupe][i];
		var user_autre_groupe = false;
		// Vérifie s'il se trouve déjà dans un autre groupe sélectionné  (autre groupe ? checké ? user dans ce groupe ?)
		for(id_groupe2 in users_ensembles){
			if(id_groupe2!=id_groupe && is_checked("box_"+id_groupe2) && in_array(users_ensembles[id_groupe2],user_tmp))  user_autre_groupe = true;
		}
		// Si l'user pas encore selectionné dans un autre groupe : check / décheck
		if(user_autre_groupe==false)
		{
			//	Sélectionne / déselectionne la box, si activée
			if(element("box_"+user_tmp).disabled==false)		set_check('box_'+user_tmp, groupe_selected);
			// Sélection d'agenda : sélectionne l'agenda / proposition uniquement
			if(typeof(select_agenda)=="function"){
				if(element("box_"+user_tmp).disabled==false)	select_agenda("box_"+user_tmp, user_tmp, true);
				else											set_check('box_proposition_'+user_tmp, groupe_selected);
			}
			// Sélectionne / déselectionne le texte
			if(element("box_"+user_tmp).disabled==false)	checkbox_text("box_"+user_tmp);
		}
	}
}
var users_ensembles = new Array();


////	RECUPERATION DES DATE DE DEBUT / FIN  (agenda + tache)  !!! FORMAT UNIX : ATTENTION NE PRENDS PAS FORCEMENT LE FUSEAU HORAIRE (depend du client) !!!
////
function recup_dates()
{
	// annee / mois / jour / heure / minutes
	annee_debut		= get_value("date_debut").substr(0, 4);
	mois_debut		= get_value("date_debut").substr(5, 2);
	jour_debut		= get_value("date_debut").substr(8, 2);
	heure_debut		= (existe("heure_debut") && get_value("heure_debut")!="")  ?  get_value("heure_debut") : 0;
	minute_debut	= (existe("minute_debut") && get_value("minute_debut")!="")  ?  get_value("minute_debut") : 0;
	annee_fin		= get_value("date_fin").substr(0, 4);
	mois_fin		= get_value("date_fin").substr(5, 2);
	jour_fin		= get_value("date_fin").substr(8, 2);
	heure_fin		= (existe("heure_fin") && get_value("heure_fin")!="")  ?  get_value("heure_fin") : 0;
	minute_fin		= (existe("minute_fin") && get_value("minute_fin")!="")  ?  get_value("minute_fin") : 0;
	// Dates au format unix
	date_debut_unix = date_fin_unix = null;
	if(get_value("date_debut")!=""){
		date_debut_unix = new Date(annee_debut, mois_debut-1, jour_debut, heure_debut, minute_debut);
		date_debut_unix = (date_debut_unix.getTime() / 1000);
		datetime_debut = annee_debut+"-"+mois_debut+"-"+jour_debut+" "+heure_debut+":"+minute_debut;
	}
	if(get_value("date_fin")!=""){
		date_fin_unix = new Date(annee_fin, mois_fin-1, jour_fin, heure_fin, minute_fin);
		date_fin_unix = (date_fin_unix.getTime() / 1000);
		datetime_fin = annee_fin+"-"+mois_fin+"-"+jour_fin+" "+heure_fin+":"+minute_fin;
	}
}


////	CONTROLE LES DATES DE DEBUT / FIN
////
function modif_dates_debutfin(id_champ, text_alert)
{
	recup_dates();
	// SI DEBUT APRES LA FIN  :  DATE DE FIN = DATE DE DEBUT
	if(date_debut_unix > date_fin_unix  &&  date_fin_unix > 0)
	{
		if(trouver("fin",id_champ)==true)	{alert(text_alert);}
		set_value("date_fin", annee_debut+"-"+mois_debut+"-"+jour_debut);
		set_value("heure_fin", heure_debut);
		set_value("minute_fin", minute_debut);
	}
	// SI AUCUNE DATE SELECTIONEE, PAS D'HEURE/MINUTES
	if(date_debut_unix==null)	{ set_value("heure_debut",'');	set_value("minute_debut",''); }
	if(date_fin_unix==null)		{ set_value("heure_fin",'');	set_value("minute_fin",''); }
}


////	AFFECTATIONS :  UTILISATEURS AUX ESPACES  -OU-  DES ESPACES AUX UTILISATEURS
////
function affect_users_espaces(this_element, id_tmp)
{
	// Init
	var txt		= id_tmp+"_txt";
	var box_1	= id_tmp+"_box_1"; //user
	var box_2	= id_tmp+"_box_2"; //admin

	// Sélection à partir du texte : user > admin > aucun
	if(this_element.type!="checkbox")
	{
		if(!is_checked(box_1) && !is_checked(box_2))		{ set_check(box_1,true);  set_check(box_2,false); }
		else if(is_checked(box_1) && !is_checked(box_2))	{ set_check(box_1,false); set_check(box_2,true); }
		else												{ set_check(box_1,false); set_check(box_2,false); }
	}
	// Sélection à partir d'une box : déselectionne l'autre
	else
	{
		if(trouver("box_1",this_element.id) && this_element.checked==true)		{ set_check(box_1,true);  set_check(box_2,false); }
		else if(trouver("box_2",this_element.id) && this_element.checked==true)	{ set_check(box_1,false); set_check(box_2,true); }
	}

	// Modifie la couleur du texte
	if(is_checked(box_1))			element(txt).className = "txt_acces_user";
	else if(is_checked(box_2))		element(txt).className = "txt_acces_admin";
	else							element(txt).className = "lien";
}


////	ON STOPPE LA PROPAGATION D'UN EVENEMENT (UN CLICK SUR LE LIEN d'UN ELEMENT NE DOIT PAS SELECTIONNER L'ELEMENT..)
////
function stopPropager(evt)
{
	//selection de l'evenement par défaut
	if(!evt){
		evt = window.event;
	}
	//Principaux navigateur
	if(evt.stopPropagation){
		evt.stopPropagation();
	}
	//IE8 ou + ancien
	else{
		evt.cancelBubble = true;
	}
}