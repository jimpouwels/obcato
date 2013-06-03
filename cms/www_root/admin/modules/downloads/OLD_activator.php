<?php
	// No direct access
	defined('_ACCESS') or die;
?>
	
<div class="module_header_wrapper">
	<div class="action_buttons_wrapper">
		<ul>
			<?php if (isset($_GET['download'])): ?>
			<li class="action_button">
				<a class="toolbar" id="update_element_holder" title="Update download" href="#">
					<span class="icon_apply"></span>
					Opslaan
				</a>
			</li>
			<?php endif; ?>
			<li class="action_button">
				<a class="toolbar" id="add_element_holder" title="Nieuwe download" href="#">
					<span class="icon_add"></span>
					Toevoegen
				</a>
			</li>
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