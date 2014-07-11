<?php

	// No direct access
	defined('_ACCESS') or die;
	
	abstract class ModuleVisual extends Visual {
		
		abstract function getActionButtons();
		
		abstract function getHeadIncludes();
		
		abstract function getPreHandlers();
		
		abstract function onPreHandled();
		
		abstract function getTitle();
	
	}

?>