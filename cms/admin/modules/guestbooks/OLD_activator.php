<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once FRONTEND_REQUEST . "database/dao/guestbook_dao.php";
	include_once FRONTEND_REQUEST . "libraries/renderers/form_renderer.php";
	
	// get the current page
	$guestbook_dao = GuestBookDao::getInstance();
	$current_guestbook = NULL;
	if (isset($_GET['guestbook'])) {
		$guestbook_id = $_GET['guestbook'];
		$current_guestbook = $guestbook_dao->getGuestBook($guestbook_id);
	}
?>

<div class="module_header_wrapper">
	<div class="action_buttons_wrapper">
		<ul>
			<?php if (!is_null($current_guestbook)): ?>
			<li class="action_button">
				<a class="toolbar" id="update_guestbook" href="#">
					<span class="icon_apply"></span>
					Opslaan
				</a>
			</li>
			<li class="action_button">
				<a class="toolbar" id="delete_guestbook" href="#">
					<span class="icon_delete"></span>
					Verwijderen
				</a>
			</li>
			<?php endif; ?>
			<li class="action_button">
				<a class="toolbar" id="add_guestbook" href="#">
					<span class="icon_add"></span>
					Toevoegen
				</a>
			</li
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
	
	include_once 'modules/' . $current_module->getIdentifier() . '/guestbook_list.php';

	include_once 'modules/' . $current_module->getIdentifier() . '/guestbook_editor.php';
	
?>