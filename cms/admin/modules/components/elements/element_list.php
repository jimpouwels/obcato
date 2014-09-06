<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once "services/url_service.php";
	
	$url_service = UrlService::getInstance();
	
	$element_types = $element_dao->getDefaultElementTypes();
	$custom_element_types = $element_dao->getCustomElementTypes();
?>

<div class="element_list">
	<fieldset class="admin_fieldset element_tree_fieldset">
		<div class="fieldset-title">Standaard elementen</div>
		<div class="element_tree">
			<?php
				if (!is_null($element_types) && count($element_types) > 0):
			?>
				<ul>
					<?php foreach ($element_types as $element_type): ?>
						<li style="list-style-image: url(<?= $element_type->getIconUrlAbsolute(); ?>);">
							<?php
								$element_item_html = '<a href="/admin/index.php?element=' . $element_type->getId() . '" title="' . $element_type->getName() . '">' . $element_type->getName() . '</a>';
								if (isset($current_element) && $current_element->getId() == $element_type->getId()) {
									$element_item_html = '<strong>' . $element_item_html . '</strong>';
								}
								echo $element_item_html;
							?>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php else: ?>
				<?php 
					include_once "libraries/renderers/main_renderer.php";
					MainRenderer::renderInformationMessage("Geen elementen gevonden.");
				?>
				<?php endif; ?>
			</div>
	</fieldset><br /><br />
	<fieldset class="admin_fieldset element_tree_fieldset">
		<div class="fieldset-title">Custom elementen</div>
		<div class="element_tree">
			<?php
				if (!is_null($custom_element_types) && count($custom_element_types) > 0):
			?>
			<ul>
				<?php foreach ($custom_element_types as $custom_element_type): ?>
					<li style="list-style-image: url(<?= $custom_element_type->getIconUrlAbsolute(); ?>);">
						<?php
							$element_item_html = '<a href="/admin/index.php?element=' . $custom_element_type->getId() . '" title="' . $custom_element_type->getName() . '">' . $custom_element_type->getName() . '</a>';
							if (isset($current_element) && $current_element->getId() == $custom_element_type->getId()) {
								$element_item_html = '<strong>' . $element_item_html . '</strong>';
							}
							echo $element_item_html;
						?>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php else: ?>
			<?php 
				include_once "libraries/renderers/main_renderer.php";
				MainRenderer::renderInformationMessage("Geen elementen gevonden.");
			?>
			<?php endif; ?>
		</div>
	</fieldset>
</div>