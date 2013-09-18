<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once FRONTEND_REQUEST . "libraries/renderers/main_renderer.php";
?>

<div class="module_header_wrapper">
	<div class="module_title_wrapper">
		<h1>
			<?php
				echo $current_module->getTitle();
			?>
		</h1>
	</div>
</div>

<div class="module_tabs">
	<ul>
		<?php
			
			$tabs = array(0 => "Modules", 1 => "Elementen", 2 => "Installeren");
			
			global $current_module_tab;
			if (!isset($current_module_tab) || $current_module_tab == '') {
				$current_module_tab = 0;
			}
		
			MainRenderer::renderTabs($tabs, $current_module_tab);
		?>
	</ul>
</div>
<?php
	switch ($current_module_tab) {
		case 0:
			include "modules/" . $current_module->getIdentifier() . "/modules/index.php";
			break;
		case 1:
			include "modules/" . $current_module->getIdentifier() . "/elements/index.php";
			break;
		case 2:
			include "modules/" . $current_module->getIdentifier() . "/install/index.php";
			break;
	}

?>