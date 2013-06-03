<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once "libraries/renderers/form_renderer.php";
	include_once "libraries/renderers/main_renderer.php";	
?>

<fieldset class="admin_fieldset image_search">
	<div class="fieldset-title">Zoeken</div>
	
	<form id="image_search" action="/admin/index.php" method="get">
		<ul class="admin_form">
			<li class="displaynone">
				<input type="hidden" name="action" value="search" />
			</li>
			<?php
			
				$default_search_value = NULL;
				if (isset($_GET['s_title'])) {
					$default_search_value = $_GET['s_title'];
				}
				echo '<li>';
				FormRenderer::renderTextField('s_title', 'Titel', $default_search_value, false, false, NULL);
				echo '</li>';
				
				$default_filename_value = NULL;
				if (isset($_GET['s_filename'])) {
					$default_filename_value = $_GET['s_filename'];
				}
				echo '<li>';
				FormRenderer::renderTextField('s_filename', ' Bestandsnaam', $default_filename_value, false, false, NULL);
				echo '</li>';
				
				$labels_name_value_pair = array();
				array_push($labels_name_value_pair, array('name' => '&gt; Selecteer', 'value' => NULL));
				foreach ($image_dao->getAllLabels() as $label) {
					array_push($labels_name_value_pair, array('name' => $label->getName(), 'value' => $label->getId()));
				}
				$label = NULL;
				if (isset($_GET['s_label']) && $_GET['s_label'] != '') {
					$label = $_GET['s_label'];
				}
				echo '<li>';
				FormRenderer::renderPullDown('s_label', 'Label', (is_null($label) ? null : $label), $labels_name_value_pair, 200, false);
				echo '</li>';
			
			?>
		</ul>
		<div class="button_container">
			<?php
				MainRenderer::renderButton("", "Zoeken", "document.getElementById('image_search').submit(); return false;");
			?>
		</div>
		<div class="show_all_link">
			<a href="/admin/index.php" title="Toon alle afbeeldingen">Toon allen</a>
		</div>
		<div class="show_all_link">
			<a href="/admin/index.php?no_labels=true" title="Toon alle afbeeldingen zonder label">Toon allen zonder label</a>
		</div>
	</form>
</fieldset>