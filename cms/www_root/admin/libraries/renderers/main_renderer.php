<?php

	// No direct access
	defined('_ACCESS') or die;
	
	include_once "libraries/system/template_engine.php";

	class MainRenderer {
		
		/*
			Private constructor.
		*/
		private function __construct() {
		}
		
		/*
			Renders an warning message block.
			
			@param $message The warning to display
		*/
		public static function renderWarningMessage($message) {
			echo '<div class="information-block">';
			
			echo '<div class="warning-icon">';
			echo '<img src="/admin/static.php?static=/default/img/default_icons/warning.png" alt="notification" />';
			echo '</div>';
			
			echo '<div class="warning-message">';
			echo '<p><em>' . $message . '</em></p>';
			echo '</div>';
			
			echo '</div>';
		}
	}

?>