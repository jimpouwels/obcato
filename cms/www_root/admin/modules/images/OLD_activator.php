<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once "dao/image_dao.php";
	include_once "libraries/renderers/form_renderer.php";
	include_once "libraries/renderers/main_renderer.php";
	
	$image_dao = ImageDao::getInstance();
	
	// get the current image
	if (isset($_GET['image'])) {
		$current_image = $image_dao->getImage($_GET['image']);
	}
?>

<div class="module_header_wrapper">
	<div class="action_buttons_wrapper">
		<ul>
			<?php if (isset($_GET['image'])): ?>
			<li class="action_button">
				<a class="toolbar" id="update_image" title="Update afbeelding" href="#">
					<span class="icon_apply"></span>
					Opslaan
				</a>
			</li>
			<?php endif; ?>
			<?php if (!(isset($current_module_tab)) || $current_module_tab == 0): ?>
				<li class="action_button">
					<a class="toolbar" id="add_image" title="Nieuwe afbeelding" href="#">
						<span class="icon_add"></span>
						Toevoegen
					</a>
				</li>
				<?php if (isset($current_image) && !is_null($current_image)): ?>
				<li class="action_button">
					<a class="toolbar" id="delete_image" title="Verwijder afbeelding" href="#">
						<span class="icon_delete"></span>
						Verwijderen
					</a>
				</li>
				<?php endif; ?>
			<?php elseif (!(isset($current_module_tab)) || $current_module_tab == 1): ?>
				<?php if (isset($_GET['label']) || isset($_GET['new_label'])): ?>
				<li class="action_button">
					<a class="toolbar" id="update_label" title="Update label" href="#">
						<span class="icon_apply"></span>
						Opslaan
					</a>
				</li>
				<?php endif; ?>
				<li class="action_button">
					<a class="toolbar" id="add_label" title="Nieuw label" href="/admin/index.php?new_label=true">
						<span class="icon_add"></span>
						Toevoegen
					</a>
				</li>
				<li class="action_button">
					<a class="toolbar" id="delete_labels" title="Verwijder label(s)" href="#">
						<span class="icon_delete"></span>
						Verwijderen
					</a>
				</li>
			<?php elseif (!(isset($current_module_tab)) || $current_module_tab == 2): ?>
				<li class="action_button">
					<a class="toolbar" id="upload_zip" title="Importeer afbeeldingen" href="#">
						<span class="icon_upload"></span>
						Importeren
					</a>
				</li>
			<?php endif; ?>
		</ul>
	</div>
	<div class="module_header_wrapper">
		<div class="module_title_wrapper">
			<h1>
				<?php
					echo $current_module->getTitle();
				?>
			</h1>
		</div>
	</div>
</div>

<form id="add_form_hidden" class="displaynone" method="post" action="/admin/index.php">
	<fieldset>
		<input id="add_image_action" name="add_image_action" type="hidden" value="" />
	</fieldset>
</form>

<div class="module_tabs">
	<ul>
		<?php		
			$tabs = array(0 => "Afbeeldingen", 1 => "Labels", 2 => "Importeren");
			
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
			include_once "modules/" . $current_module->getIdentifier() . "/images/images.php";
			break;
		case 1:
			include_once "modules/" . $current_module->getIdentifier() . "/labels/labels.php";
			break;
		case 2:
			include_once "modules/" . $current_module->getIdentifier() . "/import/import.php";
			break;
	}

?>