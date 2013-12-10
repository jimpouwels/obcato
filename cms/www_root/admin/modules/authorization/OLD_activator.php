<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once FRONTEND_REQUEST . "database/dao/authorization_dao.php";
	include_once FRONTEND_REQUEST . "libraries/renderers/form_renderer.php";
	

	
?>

<?php
	
	include_once 'modules/' . $current_module->getIdentifier() . '/user_list.php';

	if (!is_null($current_user)) {
		include_once 'modules/' . $current_module->getIdentifier() . '/user_editor.php';
	}
	
?>