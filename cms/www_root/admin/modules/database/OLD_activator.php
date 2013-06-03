<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once "libraries/renderers/main_renderer.php";
	
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
			$tabs = array(0 => "Configuratie", 1 => "Tabellen", 2 => "Query");
			
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
			include_once "modules/" . $current_module->getIdentifier() . "/configuration.php";
			break;
		case 1:
			include_once "modules/" . $current_module->getIdentifier() . "/database_tables.php";
			break;
		case 2:
			include_once "modules/" . $current_module->getIdentifier() . "/queries.php";
			break;
	}

?>