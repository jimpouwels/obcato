<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once "libraries/utilities/string_utility.php";
?>

<form action="/admin/index.php?user=<?= $current_user->getId(); ?>" method="post" id="user_form" name="update_user">
	<fieldset class="admin_fieldset user_meta">
		<div class="fieldset-title">Algemeen</div>

		<input type="hidden" name="user_id" value="<?= $current_user->getId(); ?>" />
		<input type="hidden" id="action" name="action" value="" />
		
		<ul class="admin_form">
			<?php
			
				echo '<li>';
				FormRenderer::renderTextField('user_username', 'Gebruikersnaam', $current_user->getUsername(), true, false, NULL);
				echo '</li>';
			
				echo '<li>';
				FormRenderer::renderTextField('user_firstname', 'Voornaam', $current_user->getFirstName(), true, false, 'user_firstname_field');
				echo '</li>';
			
				echo '<li>';
				FormRenderer::renderTextField('user_prefix', 'Tussenvoegsel', $current_user->getPrefix(), false, false, 'user_prefix_field');
				echo '</li>';
			
				echo '<li>';
				FormRenderer::renderTextField('user_lastname', 'Achternaam', $current_user->getLastName(), true, false, 'user_lastname_field');
				echo '</li>';
			
				echo '<li>';
				FormRenderer::renderTextField('user_email', 'E-mail adres', $current_user->getEmailAddress(), true, false, 'user_email_field');
				echo '</li>';
					
				if ($_SESSION['username'] == $current_user->getUsername()) {
					echo '<li>&nbsp;</li><li>&nbsp;</li><li>';
					FormRenderer::renderPasswordField('user_new_password_first', 'Nieuw wachtwoord', '', false, 'user_password_field');
					echo '</li>';
								
					echo '<li>';
					FormRenderer::renderPasswordField('user_new_password_second', 'Herhaal wachtwoord', '', false, 'user_password_field');
					echo '</li>';
				}
				
			?>
		</ul>
	</fieldset>
</form>