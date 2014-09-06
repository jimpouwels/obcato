<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once CMS_ROOT . "libraries/renderers/main_renderer.php";
?>

<fieldset class="admin_fieldset element_meta">
	<div class="fieldset-title">Algemeen</div>
	
	<table class="element_meta_table" cellpadding="0" cellspacing="8">
		<colgroup width="100px" />
		<colgroup width="500px" />
		<tr>
			<td class="label-cell">Naam:</td>
			<td><?= $current_element->getName(); ?></td>
		</tr>
		<tr>
			<td class="label-cell">Object naam:</td>
			<td><?= $current_element->getClassName(); ?></td>
		</tr>
		<tr>
			<td class="label-cell">Edit template:</td>
			<td><?= $current_element->getEditPresentation(); ?></td>
		</tr>
		<tr>
			<td class="label-cell">Scope:</td>
			<td><?= $current_element->getScope()->getName(); ?></td>
		</tr>
		<tr>
			<td class="label-cell">Locatie:</td>
			<td><?= $current_element->getIdentifier(); ?></td>
		</tr>
		<?php if (!$current_element->getSystemDefault()): ?>
		<tr>
			<td colspan="2"><?= MainRenderer::renderButton("", "Verwijderen", "uninstallElement(); return false;"); ?></td>
		</tr>
		<?php endif; ?>
	</table>
	<?php if (!$current_element->getSystemDefault()): ?>
		<form class="displaynone" method="post" id="delete_element_form">
			<input type="hidden" value="uninstall_element" name="action" />
			<input type="hidden" value="<?= $current_element->getId(); ?>" name="element_id_to_uninstall" />
		</form>
	<?php endif; ?>
</fieldset>