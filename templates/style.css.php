<style type="text/css">
<?php
////	SKIN PRINCIPAL DE L'AGORA : BLANC / NOIR
////
define("STYLE_FONT_FAMILY", "font-family: Arial,Helvetica, FreeSans, sans-serif;");
define("STYLE_FONT_BOLD", "font-weight:bold; font-size:11px;");
define("STYLE_BORDER_RADIUS", "border-radius:4px;");
define("STYLE_BORDER_RADIUS2", "border-radius:2px;");
define("STYLE_SHADOW", "box-shadow: 3px 3px 7px #555;");
define("STYLE_SHADOW_FORT", "box-shadow: 3px 3px 10px #fff;");
define("STYLE_SHADOW_SELECT", "box-shadow: 3px 3px 10px #f00;");
define("STYLE_SHADOW_SOFT","box-shadow:1px 1px 5px #bbb;");
define("STYLE_BORDER_IE",(version_ie_obsolete()?"border:#888 solid 1px;":""));
define("STYLE_BORDER_IE_SELECT",(version_ie_obsolete()?"border:#d00 solid 1px;":""));
if(empty($_SESSION["agora"]["skin"]) || $_SESSION["agora"]["skin"]=="noir")
{
	define("PATH_LOGO", PATH_TPL."divers/logo_skin_noir.png");
	define("STYLE_FONT_COLOR", "color:#fff;");
	define("STYLE_FONT_COLOR_RETRAIT", "color:#ccc;");
	define("STYLE_SELECT_BLUE", "color:#9bf;");
	define("STYLE_SELECT_YELLOW", "color:#fa0;");
	define("STYLE_SELECT_ORANGE", "color:#f60;");
	define("STYLE_SELECT_RED", "color:#f66;");
	define("STYLE_TR_SELECT", "#444");
	define("STYLE_TR_DESELECT", "#222");
	define("STYLE_BACKGROUND_COLOR", "background-color:#000;");
	define("STYLE_BACKGROUND_COLOR_FORUM", "background-color:#333;");
	define("STYLE_BACKGROUND_LIB_FICHIER", "background-image:url(".PATH_TPL."divers/fond_libelle_noir.png);");
	define("STYLE_FOND_OPAQUE", "background-image:url(".PATH_TPL."divers/fond_opaque_noir.png);");
	define("STYLE_BORDER_MENU_CONTEXT","border:#555 solid 1px;");
	define("STYLE_BLOCK", STYLE_BORDER_IE.STYLE_BORDER_RADIUS.STYLE_SHADOW.STYLE_BACKGROUND_COLOR."background-image:url(".PATH_TPL."divers/background_noir.jpg); background-repeat:no-repeat;");
	define("STYLE_BLOCK_SELECT", STYLE_BORDER_IE_SELECT.STYLE_BORDER_RADIUS.STYLE_SHADOW_SELECT."background-color:#777;");
	define("STYLE_ELEMENT_LISTE", "width:99.5%; margin:0px; border-bottom:#555 solid 1px;");
	define("LOADING_IMG","loading_noir.gif");
	define("STYLE_SHADOW_HOVER","box-shadow:0px 0px 7px #f44;");
	define("STYLE_SHADOW_FOCUS","box-shadow:0px 0px 7px #f00;");
}
else
{
	define("PATH_LOGO", PATH_TPL."divers/logo_skin_blanc.png");
	define("STYLE_FONT_COLOR", "color:#000;");
	define("STYLE_FONT_COLOR_RETRAIT", "color:#444;");
	define("STYLE_SELECT_BLUE", "color:#039;");
	define("STYLE_SELECT_YELLOW", "color:#c60;");
	define("STYLE_SELECT_ORANGE", "color:#d40;");
	define("STYLE_SELECT_RED", "color:#d00;");
	define("STYLE_TR_SELECT", "#bbb");
	define("STYLE_TR_DESELECT", "#eee");
	define("STYLE_BACKGROUND_COLOR", "background-color:#fff;");
	define("STYLE_BACKGROUND_COLOR_FORUM", "background-color:#ddd;");
	define("STYLE_BACKGROUND_LIB_FICHIER", "background-image:url(".PATH_TPL."divers/fond_libelle.png);");
	define("STYLE_FOND_OPAQUE", "background-image:url(".PATH_TPL."divers/fond_opaque.png);");
	define("STYLE_BORDER_MENU_CONTEXT","border:#bbb solid 1px;");
	define("STYLE_BLOCK", STYLE_BORDER_IE.STYLE_BORDER_RADIUS.STYLE_SHADOW.STYLE_BACKGROUND_COLOR."background-image:url(".PATH_TPL."divers/background.jpg); background-repeat:no-repeat;");
	define("STYLE_BLOCK_SELECT", STYLE_BORDER_IE_SELECT.STYLE_BORDER_RADIUS.STYLE_SHADOW_SELECT."background-color:#aaa;");
	define("STYLE_ELEMENT_LISTE", "width:99.5%; margin:0px; border-bottom:#ccc solid 1px;");
	define("LOADING_IMG","loading.gif");
	define("STYLE_SHADOW_HOVER","box-shadow:0px 0px 5px #999;");
	define("STYLE_SHADOW_FOCUS","box-shadow:0px 0px 5px #555;");
}

////	LARGEUR DU CORP DE PAGE FIXE, EN FONCTION DE LA RESOLUTION d'ECRAN
$contenu_principal_centre_width = (@$_SESSION["cfg"]["resolution_width"]<=1250) ? "980px" : "1200px";
?>


/* STYLE DE LA PAGE (PRINCIPALE / POPUP)  +  BLOCKS PRINCIPAUX  */
body						{ <?php echo STYLE_FONT_FAMILY.STYLE_FONT_COLOR.STYLE_BACKGROUND_COLOR.(IS_MAIN_PAGE==false?"background-position:bottom right;background-attachment:fixed;background-repeat:no-repeat;":""); ?> margin:0px; font-size:12px; }
.content					{ <?php echo STYLE_BLOCK; ?> padding:10px; vertical-align:top; }
.div_menu_horizontal		{ <?php echo STYLE_BLOCK; ?> float:left; padding:3px; }
.contenu_principal_centre	{ width:<?php echo $contenu_principal_centre_width; ?>; min-width:<?php echo $contenu_principal_centre_width; ?>; max-width:98%; margin-left:auto; margin-right:auto; margin-top:10px; }
#contenu_principal_table	{ width:100%; padding:5px; }

/* DIVERSES BALISES */
img					{ border:0px; vertical-align:middle; }
table				{ margin:0px; padding:0px; }
td					{ vertical-align:top; }
acronym				{ border-bottom: 1px dotted #777; cursor:help; }
.acronym			{ border-bottom: 1px dotted #777; cursor:help; }
hr					{ height:1px; opacity:0.4; filter:alpha(opacity=40); }
p					{ margin:0; padding: 0; }


/* INIT DU HEADER :  FOND D'ECRAN, INFOBULLE, ETC. */
.img_background			{ z-index:-1000000; position:fixed; left:0px; top:0px; height:100%; width:100%; }
.img_background img		{ height:100%; width:100%; }
.img_loading			{ display:none; position:absolute; bottom:10px; right:10px; }
.infobulle				{ <?php echo STYLE_BORDER_IE.STYLE_BORDER_RADIUS.STYLE_SHADOW; ?> position:absolute; z-index:100000; visibility:hidden; padding:5px; border:1px solid #555; background-image:url('<?php echo PATH_TPL; ?>divers/fond_opaque_infobulle.png'); }
.infobulle_contenu		{ <?php echo STYLE_FONT_BOLD.STYLE_BORDER_RADIUS; ?> background-color:#000; padding:8px; max-width:500px; color:#fff; line-height:18px; }


/* CONTENU */
.div_elem_deselect		{ <?php echo STYLE_BLOCK; ?> float:left; margin:0px; margin-right:8px; margin-bottom:8px; }
.div_elem_select		{ <?php echo STYLE_BLOCK_SELECT; ?> float:left; margin:0px; margin-right:8px; margin-bottom:8px; }
.div_elem_deselect:hover{ <?php echo STYLE_SHADOW_FORT; ?> }
.div_elem_contenu		{ height:100%; position:relative; overflow:hidden; background-repeat:no-repeat; }
.div_elem_table			{ height:100%; width:100%; }
.div_elem_td			{ <?php echo STYLE_FONT_BOLD; ?> vertical-align:middle; padding:0px; }
.div_elem_td_right		{ text-align:right;padding-right:10px; }
.div_elem_image			{ width:85px; cursor:pointer; vertical-align:middle; }
.div_elem_infos			{ <?php echo STYLE_FONT_BOLD; ?> text-align:center; vertical-align:middle; }
.div_elem_aucun			{ <?php echo STYLE_BLOCK; ?> float:left; padding:2px; width:99.5%; height:80px; padding-top:50px; text-align:center; font-size:14px; font-weight:bold; opacity:0.8; filter:alpha(opacity=80); }
.div_infos				{ <?php echo STYLE_BORDER_RADIUS; ?> border: dashed 1px #b00; text-align:center; font-style:italic; padding:5px; }


/* LIENS */
.lien, a				{ <?php echo STYLE_FONT_COLOR.STYLE_FONT_BOLD; ?> cursor:pointer; text-decoration:none; }
.lien:hover, a:hover	{ <?php echo STYLE_FONT_COLOR_RETRAIT; ?> }
.lien_select			{ <?php echo STYLE_SELECT_RED.STYLE_FONT_BOLD; ?> cursor:pointer; text-decoration:none; }
.lien_select2			{ <?php echo STYLE_SELECT_YELLOW.STYLE_FONT_BOLD; ?> cursor:pointer; text-decoration:none; }
.lien_loupe				{ cursor:url('<?php echo PATH_TPL; ?>divers/recherche.png'),pointer; }


/* INPUTS & LIBELLES DES FORMULAIRES */
input[type=text], input[type=password], input[type=file], textarea	{ <?php echo STYLE_FONT_FAMILY.STYLE_BORDER_RADIUS2.STYLE_SHADOW_SOFT; ?> padding:2px; font-weight:normal; font-size:12px; border: #999 1px solid; }	/* champs texte ou textarea */
input[type=text]:hover, input[type=password]:hover, textarea:hover	{ <?php echo STYLE_SHADOW_HOVER; ?> }
input[type=text]:focus, input[type=password]:focus, textarea:focus	{ <?php echo STYLE_SHADOW_FOCUS; ?> }
input[type=checkbox], input[type=radio]								{ vertical-align:middle; }
.button																{ <?php echo STYLE_FONT_BOLD; ?> cursor:pointer; width:100px; }
.button_big															{ <?php echo STYLE_FONT_BOLD; ?> cursor:pointer; width:140px; height:26px; }
.button_small														{ <?php echo STYLE_FONT_BOLD; ?> cursor:pointer; width:70px; }
fieldset			{ <?php echo STYLE_BLOCK; ?> width:98%; margin-top:25px; padding:5px; border: #999 1px solid; }
.fieldset_titre		{ width:95%; text-align:center; margin:0px; margin-bottom:20px; font-weight:bold; }
legend				{ <?php echo STYLE_BLOCK.STYLE_FONT_BOLD.STYLE_FONT_COLOR_RETRAIT.STYLE_SHADOW_SOFT; ?> border: #999 1px solid; position:absolute; z-index:-1; margin-top:-25px; margin-left:-5px; padding:6px; padding-top:3px; }
select				{ font-size:11px; <?php echo STYLE_SHADOW_SOFT; ?>  }
form				{ margin:0px; padding:0px; }
.form_libelle		{ <?php echo STYLE_FONT_BOLD; ?> }
.txt_acces_user		{ <?php echo STYLE_FONT_BOLD.STYLE_SELECT_BLUE; ?> cursor:pointer; }
.txt_acces_admin	{ <?php echo STYLE_FONT_BOLD.STYLE_SELECT_ORANGE; ?> cursor:pointer; }
.champ_desactive	{ opacity:0.70; filter:alpha(opacity=70); }


/* MENU DE GAUCHE : FIXE / FLOTTANT (garder les min & max en cas de dépassements de blocks !)  */
#menu_gauche_block_td		{ <?php echo "width:".(LARGEUR_MENU_GAUCHE-30)."px; min-width:".(LARGEUR_MENU_GAUCHE-30)."px; max-width:".(LARGEUR_MENU_GAUCHE-10)."px;"; ?> vertical-align:top; <?php echo STYLE_FONT_BOLD; ?> }
#menu_gauche_block_flottant	{ <?php echo "width:".(LARGEUR_MENU_GAUCHE-40)."px; min-width:".(LARGEUR_MENU_GAUCHE-40)."px; max-width:".(LARGEUR_MENU_GAUCHE-30)."px;"; ?> margin:0px; padding:0px; }
.menu_gauche_block			{ margin-bottom:10px; }
.menu_gauche_ligne			{ display:table-row; }
.menu_gauche_img			{ display:table-cell; padding-bottom:4px; vertical-align:middle; width:30px; }
.menu_gauche_img img		{ max-height : 28px; max-width : 30px; }
.menu_gauche_txt			{ display:table-cell; padding-bottom:4px; vertical-align:middle; }


/* MENUS CONTEXTUELS (clignotte tjs sous IE7/8?) */
.menu_context				{ <?php echo STYLE_BLOCK.STYLE_BORDER_MENU_CONTEXT.STYLE_FONT_BOLD.STYLE_FONT_COLOR; ?> position:absolute; z-index:10000; display:none; max-width:350px; text-align:left; margin:4px; padding:10px; cursor:default; -moz-user-select:none; -webkit-user-select:none; -ms-user-select:none; overflow:auto; max-height:500px; }
.menu_context_ligne			{ display:table-row; }
.menu_context_img			{ display:table-cell; width:25px; padding:2px; vertical-align:middle; }
.menu_context_img img		{ max-height:22px; max-width:22px; }
.menu_context_txt_left		{ display:table-cell; width:58px; padding:2px; }
.menu_context_txt			{ display:table-cell; padding:2px; }
.menu_context_hr			{ height:1px; max-width:300px; opacity:0.4; filter:alpha(opacity=40); text-align:center; margin-top:5px; margin-bottom:5px; }


/* STYLE DES CHAMPS D'EDITION D'UTILISATEUR */
.user_champ_ligne	{ display:table; width:97%; margin:7px; }
.user_champ_cell_lib	{ display:table-cell; width:40%; text-align:left; vertical-align:top; <?php echo STYLE_FONT_BOLD; ?> }
.user_champ_cell_lib2	{ display:table-cell; width:50%; text-align:left; padding:3px; <?php echo STYLE_FONT_BOLD; ?> }
.user_champ_cell	{ display:table-cell; }
.user_champ_hr		{ width:100%; margin-top:10px; margin-bottom:10px; height:1px; opacity:0.2; filter:alpha(opacity=20); }
.user_champ_photo	{ max-width:100px;max-height:100px; }


/* DIVERSES CLASS */
.forum_citation		{ <?php echo STYLE_BACKGROUND_COLOR_FORUM.STYLE_BORDER_RADIUS; ?> padding:8px; margin:0px; margin-top:10px; margin-bottom:10px; max-height:80px; overflow:auto; opacity:0.75; filter:alpha(opacity=75); }
.forum_citation_ico	{ float:right; opacity:0.7; filter:alpha(opacity=70); }
.mot_search_result	{ border-bottom: 1px dotted #f00; <?php echo STYLE_SELECT_RED; ?> }
.pas_selection		{ -moz-user-select:none; -webkit-user-select:none; -ms-user-select:none; }
.info				{ cursor:help; }
.cacher				{ display:none; }
.ligne_survol:hover	{ background-color:<?php echo STYLE_TR_DESELECT; ?>; }
.calendrier_flottant{ width:220px; height:175px; padding:0px; margin:0px; margin-top:20px; margin-left:-70px; overflow:auto; border:1px solid #555; }
.calendrier_input	{ width:70px; cursor:pointer; }
.icone				{ opacity:0.9; filter:alpha(opacity=90); }
.icone:hover		{ opacity:1; filter:alpha(opacity=100); }
.icone_groupe		{ height:16px; margin:2px; }
.table_nospace		{ padding:0px; margin:0px; border-spacing:0px; border-collapse:collapse; }
.tinymce_textarea	{ width:97%; min-height:180px; }
.div_liste_users	{ overflow:auto; max-height:350px; padding:0px; margin:0px; margin-top:5px; }
.placeholder		{ color:#999; }
.form_edit_element	{ padding:10px; font-weight:bold; }
.submit_edit_element{ text-align:right; margin-top:-10px; }
.player_video		{ width:800px; height:450px; margin:auto; <?php echo STYLE_SHADOW; ?> }
.fancybox-skin		{ <?php echo STYLE_BACKGROUND_COLOR; ?> background-repeat:no-repeat; }/*STYLE DEJA DEFINIS DANS LE .CSS DE FANCYBOX!!*/


/* IMPRESSION  (page-break-after:always; pour les sauts de page (d'agenda)) */
@media print{
	body		{ background-color:#fff; color:#000; }
	.noprint	{ display:none; }
}
</style>