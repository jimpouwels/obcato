<?php
	// No direct access
	defined('_ACCESS') or die;
?>

<fieldset class="admin_fieldset module_meta">
	<div class="fieldset-title">Algemeen</div>
	<table class="module_meta_table" cellpadding="0" cellspacing="8">
		<colgroup width="100px" />
		<colgroup width="500px" />
		<tr>
			<td class="label-cell">Titel:</td>
			<td><?= $selected_module->getTitle(); ?></td>
		</tr>
		<tr>
			<td class="label-cell">Identifier:</td>
			<td><?= $selected_module->getIdentifier(); ?></td>
		</tr>
		<tr>
			<td class="label-cell">Module groep:</td>
			<td><?= $selected_module->getModuleGroup()->getTitle(); ?></td>
		</tr>
		<tr>
			<td class="label-cell">Item op welkompagina:</td>
			<td>
				<?php 
					if ($selected_module->isSystemDefault() == 1) {
						echo 'Ja';
					} else {
						echo 'Nee';
					}
				?></td>
		</tr>
		<tr>
			<td class="label-cell">Pre-Handler:</td>
			<td><?= $selected_module->getPreHandler(); ?></td>
		</tr>
	</table>
</fieldset>