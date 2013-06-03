<?php

	// No direct access
	defined('_ACCESS') or die;
	
	include_once "libraries/renderers/form_renderer.php";
	
?>

<tr>
	<td>
		<?php FormRenderer::renderTextField('element_' . $element->getId() . '_title', 'Titel', $element->getTitle(), false, true, NULL); ?>
	</td>
</tr>
<tr>
	<td>
		<?php
			FormRenderer::renderTextArea('element_' . $element->getId() . '_embed', 'Embed video', $element->getEmbed(), 100, 5, false, true);
		?>
	</td>
</tr>