<?php
	define("_ACCESS", "GRANTED");
    define("CMS_ROOT", '');

    if (!file_exists("database_config.php"))
        header("Location: /admin/index.php");


    require_once CMS_ROOT . "database_config.php";
	require_once CMS_ROOT . "constants.php";
	require_once CMS_ROOT . "includes.php";
	require_once CMS_ROOT . 'core/data/session.php';
	require_once CMS_ROOT . "libraries/utilities/string_utility.php";
	
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
?>
		
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl">
	<head>
        <link rel="stylesheet" href="/admin/static/css/styles.css" type="text/css" />
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