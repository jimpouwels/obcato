<form id="search_form" method="get">
	<div class="popup_search_container">
		<input type="hidden" name="object" value="{$object}" />
		<input type="hidden" name="backfill" value="{$backfill}" />
		<input type="hidden" name="back_click_id" value="{$back_click_id}" />
		<input type="hidden" name="popup" value="{$popup_type}" />

		<ul class="admin_form">
			<li>{$search_field}</li>
			<li>{$image_labels_field}</li>
		</ul>

		{$search_button}
	</div>

	<div class="popup_search_results_container">
		{if count($search_results) > 0}
			<div class="popup_search_result_table images_search_result">
				{assign var="counter" value=0}
				{foreach from=$search_results item=image}
					<div class="image_search_result">
						{assign var='image_class' value='image_unpublished'}
						{if $image.published}
							{assign var='image_class' value='image_published'}
						{/if}
						<p><a class="{$image_class}" href="#" onclick="submitSelectionBackToOpener('{$backfill}', {$image.id}, '{$back_click_id}'); return false;" title="Selecteer">{$image.title}</a></p>
						<img title="{$image.title}" src="/admin/upload.php?image={$image.id}&amp;thumb=true" />
						{assign var="counter" value=$counter + 1}
					</div>
				{/foreach}
			</div>
		{else}
			{$no_results_message}
		{/if}
	</div>
</form>