<?php

	// DIRECT ACCESS GRANTED
	define("_ACCESS", "GRANTED");
	define("FRONTEND_REQUEST", '');
	
	// INCLUDE SYSTEM CONSTANTS
	include_once FRONTEND_REQUEST . "libraries/system/constants.php";
		
	include_once FRONTEND_REQUEST . "core/data/session.php";
	include_once FRONTEND_REQUEST . "libraries/system/constants.php";
	include_once FRONTEND_REQUEST . "database/mysql_connector.php";
	include_once FRONTEND_REQUEST . "backend.php";
	
	// AUTHENTICATE
	$backend = new Backend("site_administrator");
	$backend->isAuthenticated();
	
	// only Developer account may access this section
	if ($_SESSION['username'] != "Developer") {
		header('Location: /admin/login.php');
		exit();
	}
	
	$website_settings = Settings::find();
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if ($_POST['action'] == 'update_database') {
			$update_script = $website_settings->getConfigDir() . '/update_scripts/update_database.xml';
			$doc = new DOMDocument();
			$doc->load($update_script);
			
			$new_version = NULL;
			$current_version_found = false;
			$versions = $doc->getElementsByTagName("version");
			
			$mysql_database = MysqlConnector::getInstance();
			foreach($versions as $version) {
				if ($current_version_found) {
					$queries = $version->getElementsByTagName('query');
					foreach ($queries as $query) {
						$mysql_database->executeQuery($query->nodeValue);
					}
					$new_version = $version->getElementsByTagName('number')->item(0)->nodeValue;
				} else {			
					$version_number = $version->getElementsByTagName('number')->item(0)->nodeValue;
					if ($version_number == $website_settings->getDatabaseVersion()) {
						$current_version_found = true;
					}
				}
			}
		}
	}
	// update settings object
	$website_settings = Settings::find();
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl_NL" lang="nl_NL">
	<head>
		<title>Site Administration - Updates</title>
	</head>
	<body>
		<table>
			<tr>
				<td><strong>System version:</strong></td>
				<td><?= SYSTEM_VERSION; ?></td>
			</tr>
			<tr>
				<td><strong>Database version:</strong></td>
				<td><?= $website_settings->getDatabaseVersion(); ?></td>
			</tr>
		</table>
		<?php if (SYSTEM_VERSION != $website_settings->getDatabaseVersion()): ?>
		<form action="/admin/system_update.php" method="post">
			<input type="hidden" value="update_database" name="action" />
			<input type="submit" value="Update database" />
		</form>
		<?php endif; ?>
	</body>
</html>