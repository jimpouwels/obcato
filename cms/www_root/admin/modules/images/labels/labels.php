<?php
	// No direct access
	defined('_ACCESS') or die;
	
	if ((isset($_GET['label']) && $_GET['label'] != '') || (isset($_GET['new_label']) && $_GET['new_label'] == 'true')) {
		include "modules/" . $current_module->getIdentifier() . "/labels/labels_editor.php"; 
	}
	
	include "modules/" . $current_module->getIdentifier() . "/labels/labels_list.php";
?>