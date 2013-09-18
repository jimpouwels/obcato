<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once FRONTEND_REQUEST . "modules/database/dao/database_dao.php";

	// get all tables
	$dao = DatabaseDao::getInstance();
	$tables = $dao->getTables();
?>
<?php foreach ($tables as $table): ?>
	<fieldset class="admin_fieldset">
		<div class="fieldset-title"><?= $table; ?></div>
		
		<table class="table_listing" width="40%" cellspacing="0">
			<colgroup width="33%"></colgroup>
			<colgroup width="33%"></colgroup>
			<colgroup width="33%"></colgroup>
			
			<tr>
				<th>Kolomnaam</th>
				<th>Type</th>
				<th>Null toegestaan</th>
			</tr>
			
			<?php
				$columns = $dao->getColumns($table);
				foreach ($columns as $column): 
			?>
				<tr>
					<td><?= $column->getName(); ?></td>
					<td><?= $column->getType(); ?></td>
				
					<?php
						$allowed_null_value = $column->getAllowedNull();
						$allowed_null_value = ($allowed_null_value == "YES") ? "Ja" : "Nee";
					?>
				
					<td><?= $allowed_null_value; ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</fieldset>
<?php endforeach; ?>