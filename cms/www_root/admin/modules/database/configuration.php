<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once "database/mysql_connector.php";
	
?>

<fieldset class="admin_fieldset">
	<div class="fieldset-title">Database configuratie</div>
	
	<div class="database_info_row">
		<span class="label">Host</span>
		<span class="value">
			<?php
				$database = MysqlConnector::getInstance();
				echo $database->getHostName();
			?>
		</span>
	</div>
	<div class="database_info_row">
		<span class="label">Poort</span>
		<span class="value">
			<?php
				$database = MysqlConnector::getInstance();
				echo $database->getPort();
			?>
		</span>
	</div>
	<div class="database_info_row">
		<span class="label">Database naam</span>
		<span class="value">
			<?php
				$database = MysqlConnector::getInstance();
				echo $database->getDatabaseName();
			?>
		</span>
	</div>
	<div class="database_info_row">
		<span class="label">Type</span>
		<span class="value">
			<?php
				$database = MysqlConnector::getInstance();
				echo $database->getDatabaseType();
			?>
		</span>
	</div>
	<div class="database_info_row">
		<span class="label">Versie</span>
		<span class="value">
			<?php
				$database = MysqlConnector::getInstance();
				echo $database->getDatabaseVersion();
			?>
		</span>
	</div>
</fieldset>