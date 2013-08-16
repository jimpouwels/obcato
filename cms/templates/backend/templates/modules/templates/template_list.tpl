{foreach from=$scopes item=scope}
	<fieldset class="admin_fieldset">			
		<div class="fieldset-title">{$scope.name}</div>
		
		{if count($scope.templates) > 0}
			<table class="listing" width="900px" cellpadding="5" cellspacing="0" border="0">
				<colgroup width="200px"></colgroup>
				<colgroup width="200px"></colgroup>
				<colgroup width="150px"></colgroup>
				<colgroup width="100px"></colgroup>
				<thead>
					<tr class="header">
						<th>Naam</th>
						<th>Bestandsnaam</th>
						<th class="file_column">Bestand gevonden</th>
						<th class="delete_column">Verwijder</th>
					</tr>
				</thead>
				<tbody>						
					{foreach from=$scope.templates item=template}
						<tr>
							<td><a href="/admin/index.php?template={$template.id}" title="{$template.name}">{$template.name}</a></td>
							<td>{$template.filename}</td>
							<td class="file_column">
								{if $template.exists}
									<img src="/admin/static.php?static=/<?= $current_module->getIdentifier(); ?>/img/check.gif" alt="Bestand aanwezig" />
								{else}
									<img src="/admin/static.php?static=/<?= $current_module->getIdentifier(); ?>/img/delete.png" alt="Bestand ontbreekt" />
								{/if}
							</td>
							<td class="delete_column">
								{$template.delete_checkbox}
							</td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		{else}
			{$information_message}
		{/if}
	</fieldset>
{/foreach}