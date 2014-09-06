<?php

	// No direct access
	defined('_ACCESS') or die;

    require_once CMS_ROOT . "/view/template_engine.php";
	
	abstract class Visual {
		
		abstract function render();
		
	}

?>