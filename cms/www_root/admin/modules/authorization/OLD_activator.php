<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once FRONTEND_REQUEST . "database/dao/authorization_dao.php";
	include_once FRONTEND_REQUEST . "libraries/renderers/form_renderer.php";
	
	// get the current page
	$authorization_dao = AuthorizationDao::getInstance();
	$current_user = NULL;
	if (isset($_GET['user'])) {
		$user_id = $_GET['user'];
		$current_user = $authorization_dao->getUserById($user_id);
	}
	if (is_null($current_user)) {
		 $current_user = $authorization_dao->getUser($_SESSION['username']);
	}
	
?>
	
<div class="module_header_wrapper">
	<div class="action_buttons_wrapper">
		<ul>
			<?php if (!is_null($current_user)): ?>
			<li class="action_button">
				<a class="toolbar" id="update_user" href="#">
					<span class="icon_apply"></span>
					Opslaan
				</a>
			</li>
			<?php endif; ?>
			<?php if ($current_user->getUsername() != $_SESSION['username']): ?>
			<li class="action_button">
				<a class="toolbar" id="delete_user" href="#">
					<span class="icon_delete"></span>
					Verwijderen
				</a>
			</li>
			<?php endif; ?>
			<li class="action_button">
				<a class="toolbar" id="add_user" href="#">
					<span class="icon_add"></span>
					Toevoegen
				</a>
			</li>
		</ul>
	</div>
	<div class="module_title_wrapper">
		<h1>
			<?php
				echo $current_module->getTitle();
			?>
		</h1>
	</div>
</div>

<?php
	
	include_once 'modules/' . $current_module->getIdentifier() . '/user_list.php';

	if (!is_null($current_user)) {
		include_once 'modules/' . $current_module->getIdentifier() . '/user_editor.php';
	}
	
?>