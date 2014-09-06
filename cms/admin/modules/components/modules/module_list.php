<?php
	// No direct access
	defined('_ACCESS') or die;
	
	$modules = $module_dao->getDefaultModules();
	$custom_modules = $module_dao->getCustomModules();
	
	include_once CMS_ROOT . "/modules/"  . $current_module->getIdentifier() . "/modules/functions.php";
?>

<div class="module_list">
	<fieldset class="admin_fieldset module_tree_fieldset">
		<div class="fieldset-title">Standaard modules</div>
		<div class="module_tree">
			<?php 
				if (!is_null($modules) && count($modules) > 0) {
					renderModuleItems($modules);
				} else {
					include_once CMS_ROOT . "/libraries/renderers/main_renderer.php";
					MainRenderer::renderInformationMessage("Geen modules gevonden.");
				}
			?>
		</div>
	</fieldset><br /><br />
	<fieldset class="admin_fieldset">
		<div class="fieldset-title">Custom modules</div>
		<div class="module_tree">
			<?php 
				if (!is_null($custom_modules) && count($custom_modules) > 0) {
					renderModuleItems($custom_modules);
				} else {
					include_once CMS_ROOT . "/libraries/renderers/main_renderer.php";
					MainRenderer::renderInformationMessage("Geen modules gevonden.");
				}
			?>
		</div>
	</fieldset>
</div>