<?php
	// No direct access
	defined('_ACCESS') or die;
	
	if (isset($_GET['label'])) {
		$label_id = $_GET['label'];
		$current_label = $image_dao->getLabel($label_id);
	}
	
	$form_action = "/admin/index.php";
	if (isset($current_label)) {
		$form_action = $form_action . "?label=" . $current_label->getId();
	} else {
		$form_action = $form_action . "?new_label=true";
	}
?>

<form id="label_form" method="post" action="<?= $form_action; ?>">
	<fieldset class="admin_fieldset">
		<div class="fieldset-title">
			<?php
				if (isset($current_label)) {
					echo 'Label bewerken';
				} else {
					echo 'Nieuw label';
				}
			?>
		</div>
		
		<input type="hidden" value="" name="action" id="action" />
		<input type="hidden" value="<?php if (isset($current_label)) { echo $current_label->getId(); } ?>" name="label_id" id="label_id" />
		
		<?php
			echo '<ul class="admin_form">';
						
			echo '<li>';
			$name_value = NULL;
			if (isset($current_label)) { 
				$name_value = $current_label->getName();
			}
			FormRenderer::renderTextField('name', 'Naam', $name_value, true, false, NULL);
			echo '</li>';
			
			echo '</ul>';
		?>
	</fieldset>
</form>
<br /><br />