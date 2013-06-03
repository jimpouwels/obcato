<?php
	// No direct access
	defined('_ACCESS') or die;

	$guestbooks = $guestbook_dao->getAllGuestBooks();
?>

<fieldset class="admin_fieldset guestbook_list">
	<div class="fieldset-title">Gastenboeken</div>
	<div class="guestbook_tree">
		<?php if (count($guestbooks) > 0): ?>
		<ul>
			<?php foreach ($guestbooks as $guestbook): ?>
				<?php 
					$title = $guestbook->getTitle();
					$item_html = "<a title=\"" . $title . "\" href=\"/admin/index.php?guestbook=" . $guestbook->getId() . "\">" . $title . "</a>";
					if (isset($current_guestbook) && $current_guestbook->getId() == $guestbook->getId()) {
						$item_html = "<strong>" . $item_html . "</strong>";
					}
				?>
				<li class="guestbook_list_item"><?= $item_html; ?></li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
	</div>
</fieldset>