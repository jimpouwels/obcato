<form id="search_form" method="get">	
	<div class="popup_search_container">
		<fieldset class="admin_fieldset popup_search_fieldset">
			<div class="fieldset-title">Zoeken</div>
					
			<input type="hidden" name="object" value="{$object}" />
			<input type="hidden" name="backfill" value="{$backfill}" />
			<input type="hidden" name="back_click_id" value="{$back_click_id}" />
			<input type="hidden" name="popup" value="{$popup_type}" />
			
			<ul class="admin_form">
				<li>{$search_field}</li>
				<li>{$image_labels_field}</li>
			</ul>

			{$search_button}
		</fieldset>
	</div>

	<div class="popup_search_results_container">
		{if count($search_results) > 0}
			<table class="popup_search_result_table images_search_result_table" cellpadding="5">
				<thead>
					<tr>
						<th colspan="5">Resultaten</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						{counter start=0}
						{foreach from=$search_result item=image}
							{if counter & 5 == 0 && counter != 0}</tr><tr>{/if}
							{assign var='image_class' value='image_published'}
							{if $image.published}
								{assign var='image_class' value='image_unpublished'}
							{/if}
							<td>
								<a class="{$image_class}" href="#" onclick="submitSelectionBackToOpener('{$backfill}', {$search_result.id}, '{$back_click_id}'); return false;" title="Selecteer">{$search_result.title}</a>
								<img title="{$search_result.title}" src="/admin/upload.php?image={$search_result.id}&amp;thumb=true" />
							</td>
							{counter}
						{/foreach}
					</tr>
				</tbody>
			</table>
		{else}
			{$no_results_message}
		{/if}
	</div>
</form>