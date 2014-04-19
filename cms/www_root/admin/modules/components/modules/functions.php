<?php
	// No direct access
	defined('_ACCESS') or die;

	function renderModuleItems($modules) {
		echo '<ul>';
		foreach ($modules as $module) {
			echo '<li style="list-style-image: url(/admin/static.php?file=/modules' . $module->getIconUrl() . '">';
			$selected_module_id = null;
			if (isset($_GET['module'])) {
				$selected_module_id = $_GET['module'];
			}
			$module_item_html = '<a href="/admin/index.php?module=' . $module->getId() . '" title="' . $module->getTitle() . '">' . $module->getTitle() . '</a>';
			if ($selected_module_id == $module->getId()) {
				$module_item_html = '<strong>' . $module_item_html . '</strong>';
			}
			echo $module_item_html;
			echo '</li>';
		}
		echo '</ul>';
	}
	
?>