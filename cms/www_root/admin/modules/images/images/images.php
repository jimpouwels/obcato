<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include "modules/" . $current_module->getIdentifier() . "/images/search.php";
	
	if (isset($_GET['image'])) {
		include "modules/" . $current_module->getIdentifier() . "/images/editor.php" ;
	} else {
		include "modules/" . $current_module->getIdentifier() . "/images/list.php" ;
	}
	
?>