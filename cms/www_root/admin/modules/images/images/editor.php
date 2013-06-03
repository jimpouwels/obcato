<?php
	// No direct access
	defined('_ACCESS') or die;
	
	$settings_dao = SettingsDao::getInstance();
	$upload_dir = Settings:find();
	
?>

<form action="/admin/index.php?image=<?= $current_image->getId(); ?>" method="post" id="image-editor-form" enctype="multipart/form-data">
	<fieldset class="admin_fieldset image_meta">
		<div class="fieldset-title">Algemeen</div>
		
		<input type="hidden" id="<?= ACTION_FORM_ID ?>" name="<?= ACTION_FORM_ID ?>" value="" />
		<input type="hidden" id="image_id" name="image_id" value="<?= $current_image->getId(); ?>" />
		
		<ul class="admin_form">
			<?php
			
				echo '<li>';
				FormRenderer::renderTextField('image_title', 'Titel', $current_image->getTitle(), true, false, NULL);
				echo '</li>';
				
				echo '<li>';
				FormRenderer::renderSingleValuedCheckbox('image_published', 'Gepubliceerd', $current_image->getPublished(), false, '');
				echo '</li>';
				
				echo '<li>';
				FormRenderer::renderFileUpload('image_file', 'Afbeelding', false);
				echo '</li>';
			?>
		</ul>
	</fieldset>

	<fieldset class="admin_fieldset image_editor">
		<div class="fieldset-title">Labels</div>
		
		<?php
			$all_labels = $image_dao->getAllLabels();
			$image_labels = $image_dao->getLabelsForImage($current_image->getId());
		?>
		
		<select class="label-selector" multiple="multiple" size="10" name="image_select_labels[]">
			<?php if (count($all_labels) > 0): ?>
				<?php foreach($all_labels as $label): ?>
					<?php if (!in_array($label, $image_labels)): ?>
					<option value="<?= $label->getId(); ?>"><?= $label->getName(); ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if (count($all_labels) == 0 && (count($all_labels) - count($image_labels) ==0)): ?>
				<option value="-1"></option>
			<?php endif; ?>
		</select>
		
		<?php if (count($image_labels) > 0): ?>
			<table class="selected-labels" cellpadding="5" cellspacing="0">
				<colgroup width="100px"></colgroup>
				<colgroup width="50px"></colgroup>
				<thead>
					<th>Geselecteerde labels</th>
					<th>Verwijder</th>
				</thead>
				<tbody>
					<?php foreach ($image_labels as $image_label): ?>
					<tr>
						<td><?= $image_label->getName(); ?></td>
						<td class="delete_column">
							<?php
								FormRenderer::renderSingleValuedCheckbox('label_' . $image_label->getId() . '_delete', '', 0, false, '');
							?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
		
	</fieldset>
	<fieldset class="admin_fieldset image_editor">
		<div class="fieldset-title">Afbeelding</div>
		
		<?php if(!is_null($current_image->getFileName()) && $current_image->getFileName() != ''): ?>
			<img title="<?= $current_image->getTitle(); ?>" alt="<?= $current_image->getTitle(); ?>" src="/admin/upload.php?image=<?= $current_image->getId(); ?>" />
		<?php endif; ?>
	</fieldset>
</form>