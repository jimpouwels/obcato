<?php
	// No direct access
	defined('_ACCESS') or die;
	
	$element_dao = ElementDao::getInstance();
	
	$current_element = NULL;
	if (isset($_GET['element']) && $_GET['element'] != '') {
		$current_element = $element_dao->getElementType($_GET['element']);
	}
	
	include 'modules/' . $current_module->getIdentifier() . '/elements/element_list.php';
	
	if (!is_null($current_element)) {
		include 'modules/' . $current_module->getIdentifier() . '/elements/element_viewer.php';
	}
	
?>