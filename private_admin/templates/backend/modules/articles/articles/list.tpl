<fieldset class="admin_fieldset article_list">
	<div class="fieldset-title">{$text_resources.articles_search_results_title}</div>
	{if count($search_results) > 0}
		{if !is_null($search_query)}
			<p class="search_job"><strong><em>Zoekterm: </em></strong>&nbsp;'{$search_query}'<br />
		{/if}
		{if !is_null($search_term)}
			<strong><em>Term: </em></strong>&nbsp;'{$search_term}'</p>
		{/if}
		<table class="listing search_results" width="800px" cellspacing="0" cellpadding="5" border="0">
			<colgroup width="350px"></colgroup>
			<colgroup width="200px"></colgroup>
			<colgroup width="150px"></colgroup>
			<colgroup width="100px"></colgroup>
			<thead>
				<tr>
					<th>{$text_resources.articles_search_results_title_column}</th>
					<th>{$text_resources.articles_search_creation_date_column}</th>
					<th>{$text_resources.articles_search_author_column}</th>
					<th class="center">{$text_resources.articles_search_results_published_column}</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$search_results item=search_result}
					<tr>
						<td><a href="/admin/index.php?article={$search_result.id}" title="{$search_result.title}">{$search_result.title}</a></td>
						<td>{$search_result.created_at}</td>
						<td>{$search_result.created_by}</td>
						<td class="center">
							{if $search_result.published}
								<img alt="Publiceren" src="/admin/static.php?file=/default/img/default_icons/green_flag.png" />
							{else}
								<img alt="Depubliceren" src="/admin/static.php?file=/default/img/default_icons/red_flag.png" />
							{/if}
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	{else}
		{$no_results_message}
	{/if}
</fieldset>
