<?php
////	INIT
require_once "../".$_GET["module_path"]."/commun.inc.php";
require_once PATH_INC."header.inc.php";
?>


<script type="text/javascript">
////	On redimensionne
resizePopupLightbox(450,400);
////    On indique le dossier sélectionné dans le formulaire
function select_dossier_action(id_dossier)	{ set_value("id_dossier",id_dossier); }
</script>


<form action="<?php echo ROOT_PATH.$_GET["module_path"]."/deplacer.php"; ?>" method="post">
	
	<fieldset class="fieldset_titre"><?php echo $trad["deplacer_autre_dossier"]; ?></fieldset>
	
	<?php
	////	MENU D'ARBORESCENCE
	$cfg_menu_arbo = array("objet"=>$objet[$_GET["type_objet_dossier"]], "id_objet"=>$_GET["id_dossier_parent"], "is_menu_deplacer"=>true);
	require_once PATH_INC."menu_arborescence.inc.php";
	?>
	<div style="text-align:right;">
		<input type="hidden" name="id_dossier" id="id_dossier" value="<?php echo $_GET["id_dossier_parent"]; ?>">
		<?php
		// Mets en mémoire les elements à déplacer
		foreach($_GET["SelectedElems"] as $type_elem => $ids_elems){
			echo "<input type='hidden' name='SelectedElems[".$type_elem."]' value=\"".$ids_elems."\" />";
		}
		?>
		<input type="submit" value="<?php echo $trad["modifier"]; ?>" class="button_big" />
	</div>
</form>


<?php require PATH_INC."footer.inc.php"; ?>
