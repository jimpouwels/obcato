<fieldset class="admin_fieldset template_list_fieldset">
	<form id="template_form" method="post" action="/admin/index.php?scope={$scope}">
		<input type="hidden" name="action" id="action" value="delete_templates" />
		<div class="fieldset-title">{$scope} templates</div>
		{if count($templates) > 0}
			<table class="listing template_listing" width="800px" cellspacing="0" cellpadding="5" border="0">
				<colgroup width="350px"></colgroup>
				<colgroup width="200px"></colgroup>
				<colgroup width="200px"></colgroup>
				<colgroup width="100px"></colgroup>
				<thead>
					<tr>
						<th>Naam</th>
						<th>Bestandsnaam</th>
						<th>Templatebestand aanwezig</th>
						<th>Verwijderen</th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$templates item=template}
						<tr>
							<td>{$template.name}</a></td>
							<td>{$template.filename}</td>
							<td>
								{if $template.exists}
									<img src="/admin/static.php?file=/modules/templates/img/check.gif" alt="Bestand aanwezig" />
								{else}
									<img src="/admin/static.php?file=/modules/templates/img/delete.png" alt="Bestand ontbreekt" />
								{/if}
							</td>
							<td class="last">
								{$template.delete_checkbox}
							</td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		{else}
			{$information_message}
		{/if}
	</form>
</fieldset>