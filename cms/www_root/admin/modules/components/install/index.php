<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once FRONTEND_REQUEST . "libraries/renderers/form_renderer.php";
	include_once FRONTEND_REQUEST . "libraries/renderers/main_renderer.php";
	
	global $install_errors;
?>

<div class="install_box">
	<fieldset class="admin_fieldset install_component_fieldset">
		<div class="fieldset-title">Installeer component</div>
		<form action="/admin/index.php" method="post" enctype="multipart/form-data" id="install_component_form">
			<ul class="admin_form">
				<input type="hidden" name="action" id="action" value="install_component" />
				
				<?php
					echo '<li>';
					FormRenderer::renderFileUpload('component_file', 'ZIP bestand', false);
					echo '</li>';
					
					echo '<li>';
					MainRenderer::renderButton('', 'Installeren', "document.getElementById('install_component_form').submit(); return false;");
					echo '</li>';
				?>
			</ul>
		</form>
		<? if (count($install_errors) > 0): ?>
			<ul class="errors">
				<? foreach ($install_errors as $error): ?>
					<li><?= $error; ?></li>
				<? endforeach; ?>
			</ul>
		<? endif; ?>
	</fieldset>
</div>