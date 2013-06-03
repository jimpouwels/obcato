<?php
	// No direct access
	defined('_ACCESS') or die;

	$users = $authorization_dao->getAllUsers();
?>

<fieldset class="admin_fieldset user_tree_fieldset">
	<div class="fieldset-title">Gebruikers</div>
	<div class="user_tree">
		<?php if (count($users) > 0): ?>
		<ul>
			<?php foreach ($users as $user): ?>
			<?php 
				$full_name = $user->getFullName();
				$item_html = "<a title=\"" . $full_name . "\" href=\"/admin/index.php?user=" . $user->getId() . "\">" . $full_name . "</a>";
				if (isset($current_user) && $current_user->getId() == $user->getId()) {
					$item_html = "<strong>" . $item_html . "</strong>";
				}
			?>
			<li class="user_list_item"><?= $item_html; ?></li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
	</div>
</fieldset>