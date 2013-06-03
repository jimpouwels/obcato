<?php
	// No direct access
	defined('_ACCESS') or die;
?>
<form id="label_delete_form" method="post" action="/admin/index.php">
	<fieldset class="admin_fieldset">
		<input type="hidden" name="label_delete_action" id="label_delete_action" value="" />
		
		<div class="fieldset-title">Labels</div>
		
		<?php
			$labels = $image_dao->getAllLabels();
		?>
		
		<?php if (count($labels) > 0): ?>
		<br />
		<table class="listing" cellpadding="5" cellspacing="0" border="0">
			<colgroup width="300px"></colgroup>
			<colgroup width="75px"></colgroup>
			<thead>
				<tr class="header">
					<th>Naam</th>
					<th class="delete_column">Verwijder</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($labels as $label): ?>
					<tr>
						<td><a href="/admin/index.php?label=<?= $label->getId(); ?>" title="<?= $label->getName(); ?>"><?= $label->getName(); ?></a></td>
						<td class="delete_column">
							<?php
								FormRenderer::renderSingleValuedCheckbox('label_' . $label->getId() . '_delete', '', 0, false, '');
							?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tr>
			</tbody>
		</table>
		<?php else: ?>
		<?php 
			include_once "libraries/renderers/main_renderer.php";
			MainRenderer::renderInformationMessage("Geen artikelen gevonden.");
		?>
		<?php endif; ?>
	</fieldset>
</form>