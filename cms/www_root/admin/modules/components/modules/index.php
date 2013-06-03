<?php
	// No direct access
	defined('_ACCESS') or die;
	
	$module_dao = ModuleDao::getInstance();
	
	$selected_module = NULL;
	if (isset($_GET['module']) && $_GET['module'] != '') {
		$selected_module = $module_dao->getModule($_GET['module']);
	}
	
	include 'modules/' . $current_module->getIdentifier() . '/modules/module_list.php';
	
	if (!is_null($selected_module)) {
		include 'modules/' . $current_module->getIdentifier() . '/modules/module_viewer.php';
	}
	
?>