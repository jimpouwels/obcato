<?php
	define("_ACCESS", "GRANTED");
	define("FRONTEND_REQUEST", '');
	
	// INCLUDE SYSTEM CONSTANTS
	include_once "libraries/system/constants.php";
	
	include_once 'core/data/session.php';

	include_once "libraries/utilities/string_utility.php";
	$errors = array();

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		// get values
		$username = $_POST['username']; 
		$password = $_POST['password'];
		
		$session = new Session();
		$authenticated = $session->logIn($username, $password);
		if ($authenticated) {
			$redirect_to = '/admin/index.php';
			if (isset($_POST['org_url']) && $_POST['org_url'] != '') {
				$redirect_to = $_POST['org_url'];
			}
			header('Location: ' . $redirect_to);
			exit();
		}
		
		$errors['login_unsuccessful'] = 'Verkeerde gebruikersnaam / wachtwoord combinatie';
	}
	
	include_once "view/views/form_textfield.php";
	include_once "view/views/form_password_field.php";
	include_once "libraries/renderers/main_renderer.php";
	include_once "view/views/button.php";
?>
		
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl">
	<head>
		<link rel="stylesheet" href="/admin/static/css/login.css" type="text/css" />
		
		<script type="text/javascript" src="/admin/static/js/jquery-1.6.1.min.js"></script>
		<script type="text/javascript" src="/admin/static/js/login_functions.js"></script>
		
		<title>Site Administrator</title>
		<meta name="robots" content="noindex" />
	</head>
	<body>
		<div id="login-form-box">
			<form id="form-login" method="post" action="/admin/login.php">
				<fieldset class="loginform">
					<div class="header">
						<img src="/admin/static/img/header_text.png" height="60px" width="450px" />
					</div>
					<div class="fields">
						<?php if (isset($_GET['org_url']) && $_GET['org_url'] != ''): ?>
						<input type="hidden" name="org_url" value="<?= urldecode($_GET['org_url']); ?>" />
						<?php endif; ?>
						
						<?php
							$username = new TextField('username', 'Gebruikersnaam', "", true, false, NULL);
							echo $username->render();
							$password = new PasswordField('password', 'Wachtwoord', "", true, false, NULL);
							echo $password->render();
						?>

						<div class="button-holder">
							<?php
								$button = new Button("", "Inloggen", "document.getElementById('form-login').submit(); return false;");
								echo $button->render();
							?>
						</div>
					</div>
					<div class="error">
						<?php
							if (!empty($errors['login_unsuccessful'])) {
								echo "<p class=\"red\">" . $errors['login_unsuccessful'] . "</p>";
							}
						?>
					</div>
				</fieldset>
			</form>
		</div>
	</body>
</html>