<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once "libraries/renderers/form_renderer.php";
	include_once "libraries/renderers/main_renderer.php";
	include_once "database/mysql_connector.php";
	include_once "libraries/validators/form_validator.php";
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$query = FormValidator::checkEmpty('query', 'U heeft geen query ingevoerd');
	}
	if (isset($query) && !is_null($query) && $query != '') {
		$mysql_database = MysqlConnector::getInstance(); 
		
		$result = $mysql_database->executeSelectQuery($query);
	}
	
	// information message
	MainRenderer::renderWarningMessage("Let op! Wees voorzichtig met het uitvoeren van queries! Data kan onherstelbaar verloren gaan!");
?>

<fieldset class="admin_fieldset">
	<div class="fieldset-title">Query editor</div>
	
	<div class="queries_form_wrapper">
		<form action="/admin/index.php" method="post" id="query_execute_form">
			<ul>
				<li>
					<?php
						FormRenderer::renderTextArea('query', 'Query', '', 55, 10, true, false);
					?>
				</li>
			</ul>
		</form>
		<?php 
			MainRenderer::renderButton("", "Query uitvoeren", "document.getElementById('query_execute_form').submit(); return false;");
		?>
	</div>
</fieldset>
<fieldset class="admin_fieldset">
	<div class="fieldset-title">Resultaten</div>
	
	<?php if (isset($result) && !is_bool($result)): ?>
		<?php 
			$number_of_fields = mysql_num_fields($result);
		?>
		<table class="query_result_listing" cellspacing="0">
			<?php for ($i = 0; $i < $number_of_fields; ++$i): ?>
				<colgroup width="100px"></colgroup>
			<?php endfor; ?>
			<tr>
				<?php for ($i = 0; $i < $number_of_fields; ++$i): ?>
					<th><?= mysql_field_name($result, $i); ?></th>
				<?php endfor; ?>
			</tr>
			<?php while ($row = mysql_fetch_row($result)): ?>
				<tr>
					<?php foreach ($row as $field): ?>
						<td><?= $field; ?></td>
					<?php endforeach; ?>
				</tr>
			<?php endwhile; ?>
		</table>
	<?php else: ?>
		<div class="query_result_listing">
			<?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && mysql_affected_rows() >= 0): ?>
				<em><?= mysql_affected_rows() ?> records gewijzigd</em>
			<?php endif; ?>
		</div>
	<?php endif;?>
</fieldset>