<fieldset class="admin_fieldset table_selector">
	<div class="fieldset-title">Tabellen</div>
</fieldset>

{foreach $tables as $table}
	<fieldset class="admin_fieldset table_details displaynone">
		<div class="fieldset-title">{$table.name}</div>
		<table class="table_listing" width="700px" cellspacing="0">
			<colgroup width="33%"></colgroup>
			<colgroup width="33%"></colgroup>
			<colgroup width="33%"></colgroup>
			
			<tr>
				<th>Kolomnaam</th>
				<th>Type</th>
				<th>Null toegestaan</th>
			</tr>
			
			{foreach from=$table.columns item=column}
				<tr>
					<td>{$column.name}</td>
					<td>{$column.type}</td>
					<td>{$column.allowed_null}</td>
				</tr>
			{/foreach}
		</table>
	</fieldset>
{/foreach}