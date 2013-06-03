<?php
	// No direct access
	defined('_ACCESS') or die;
?>

<div class="module_header_wrapper">
	<?php if (isset($_GET['template'])): ?>
	<div class="action_buttons_wrapper">
		<ul>
			<li class="action_button">
				<a class="toolbar" id="update_template" href="#">
					<span class="icon_apply"></span>
					Opslaan
				</a>
			</li>
		</ul>
	</div>
	<?php else: ?>
	<div class="action_buttons_wrapper">
		<ul>
			<li class="action_button">
				<a class="toolbar" id="add_template" href="#">
					<span class="icon_add"></span>
					Toevoegen
				</a>
			</li>
			<li class="action_button">
				<a class="toolbar" id="delete_template" href="#">
					<span class="icon_delete"></span>
					Verwijderen
				</a>
			</li>
		</ul>
	</div>
	<?php endif; ?>
	<div class="module_title_wrapper">
		<h1>
			<?php
				echo $current_module->getTitle();
			?>
		</h1>
	</div>
</div>

<?php

	include_once "modules/" . $current_module->getIdentifier() . "/template_overview.php";

?>