<form id="template_delete_form" name="template_delete_form" method="post" action="{$backend_base_url}&scope={$scope}">
	<input type="hidden" name="action" id="action" value="delete_templates" />
	{if count($templates) > 0}
		<table class="listing template_listing" width="800px" cellspacing="0" cellpadding="5" border="0">
			<colgroup width="350px"></colgroup>
			<colgroup width="200px"></colgroup>
			<colgroup width="200px"></colgroup>
			<colgroup width="100px"></colgroup>
			<thead>
				<tr>
					<th>{$text_resources.template_list_name_column}</th>
					<th class="center">{$text_resources.template_list_delete_column}</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$templates item=template}
					<tr>
						<td><a href="{$backend_base_url}&template={$template.id}" title="{$template.name}">{$template.name}</a></td>
						<td class="center last">
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
