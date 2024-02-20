<form id="term_delete_form" action="{$backend_base_url}" method="post">
	<input type="hidden" name="term_delete_action" id="term_delete_action" value="" />
	{if count($all_terms) > 0}
		<table class="listing terms_listing" cellpadding="5" cellspacing="0" border="0">
			<colgroup width="300px"></colgroup>
			<colgroup width="75px"></colgroup>
			<thead>
				<tr class="header">
					<th>{$text_resources.articles_terms_list_name}</th>
					<th class="delete_column">{$text_resources.articles_terms_list_delete}</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$all_terms item=term}
					<tr>
						<td><a href="{$backend_base_url}&term={$term.id}" title="{$term.name}">{$term.name}</a></td>
						<td class="delete_column">{$term.delete_field}</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	{else}
		{$no_terms_message}
	{/if}
</form>
