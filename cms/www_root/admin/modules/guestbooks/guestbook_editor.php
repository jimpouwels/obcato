<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once FRONTEND_REQUEST . "libraries/utilities/date_utility.php";
	include_once FRONTEND_REQUEST . "libraries/utilities/string_utility.php";
	
?>

<?php if(!is_null($current_guestbook)): ?>
<form action="/admin/index.php?guestbook=<?= $current_guestbook->getId(); ?>" method="post" id="guestbook_form">
	<fieldset class="admin_fieldset guestbook_meta">
		<div class="fieldset-title">Algemeen</div>
		
		<input id="action" name="action" type="hidden" value="" />
		
		<ul class="admin_form">
			<?php
			
				echo '<li>';
				FormRenderer::renderTextField('guestbook_title', 'Titel', $current_guestbook->getTitle(), true, false, NULL);
				echo '</li>';
				
				echo '<li>';
				FormRenderer::renderSingleValuedCheckbox('guestbook_closed', 'Gesloten', $current_guestbook->isClosed(), false, '');
				echo '</li>';
				
				echo '<li>';
				FormRenderer::renderSingleValuedCheckbox('guestbook_auto_acknowledge', 'Automatische goedkeuring', $current_guestbook->isAutoAcknowledge(), false, '');
				echo '</li>';
				
				echo '<li>';
				FormRenderer::renderText('Aangemaakt door', $current_guestbook->getCreatedBy()->getUserName(), '');
				echo '</li>';
				
				echo '<li>';
				FormRenderer::renderText('Aangemaakt op', DateUtility::mysqlDateToString($current_guestbook->getCreatedAt(), '-'), '');
				echo '</li>';
				
			?>
		</ul>
	</fieldset><br /></br />
	<fieldset class="admin_fieldset messages_list">
		<div class="fieldset-title">Geplaatste berichten</div>
		<?php if (count($current_guestbook->getMessages()) > 0): ?>
			<br />
			<table cellspacing="0" cellpadding="5" border="0" width="800px" class="listing">
				<colgroup width="300px"></colgroup>
				<colgroup width="300px"></colgroup>
				<colgroup width="100px"></colgroup>
				<colgroup width="100px"></colgroup>
				<colgroup width="100px"></colgroup>
				<thead>
					<tr class="header">
						<th>Auteur</th>
						<th>Email adres</th>
						<th>Gepost op</th>
						<th class="acknowledge_column">Goedgekeurd</th>
						<th class="delete_column">Verwijder</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($current_guestbook->getMessages() as $message): ?>
						<tr>
							<td><?= $message->getAuthor(); ?></td>
							<td><?= $message->getEmailAddress(); ?></td>
							<td><?= DateUtility::mysqlDateToString($message->getPostedAt(), '-'); ?></td>
							<td class="acknowledge_column">
								<?php if ($message->isAcknowledged()): ?>
									<img src="/admin/static.php?file=/default/img/default_icons/green_flag.png" alt="Goedgekeurd" />
								<?php else: ?>
									<img src="/admin/static.php?file=/default/img/default_icons/red_flag.png" alt="Niet goedgekeurd" />
								<?php endif; ?>
							</td>
							<td class="delete_column">
								<?php
									FormRenderer::renderSingleValuedCheckbox('guestbook_message_' . $message->getId() . '_delete', '', 0, false, '');
								?>
							</td>
						</tr>
						<tr>
							<td class="message_column" colspan="6"><em><?= $message->getMessage(); ?></em></td>
						</td>
					<?php endforeach; ?>
				</tbody>
			</table>
			<br />
		<?php else: ?>
			<?php 
				include_once FRONTEND_REQUEST . "libraries/renderers/main_renderer.php";
				
				MainRenderer::renderInformationMessage("Er zijn nog geen berichten in dit gastenboek geplaatst.");
			?>
		<?php endif; ?>
	</fieldset>
</form>
<?php else: ?>
<form id="guestbook_form" class="displaynone" method="post" action="/admin/index.php">
	<input id="action" name="action" type="hidden" value="" />
</form>
<?php endif; ?>