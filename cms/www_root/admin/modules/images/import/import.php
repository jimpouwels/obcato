<?php
	// No direct access
	defined('_ACCESS') or die;
?>

<form action="/admin/index.php" method="post" id="image-import-form" enctype="multipart/form-data">
	<fieldset class="admin_fieldset">
		<div class="fieldset-title">Importeren</div>
		
		<ul class="admin_form">
			<?php		
				echo '<li>';
				FormRenderer::renderFileUpload('import_zip_file', 'ZIP bestand', false);
				echo '</li>';
				
				$labels_name_value_pair = array();
				array_push($labels_name_value_pair, array('name' => '&gt; Selecteer', 'value' => NULL));
				foreach ($image_dao->getAllLabels() as $label) {
					array_push($labels_name_value_pair, array('name' => $label->getName(), 'value' => $label->getId()));
				}
				echo '<li>';
				FormRenderer::renderPullDown('import_label', 'Label', NULL, $labels_name_value_pair, 200, false);
				echo '</li>';
			?>
		</ul>
	</fieldset>
</form>