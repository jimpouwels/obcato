<ul class="admin_form">
	<li>{$title_field}</li>
	<li>{$description_field}</li>
	<li>{$published_field}</li>
	<li>{$publication_date_field}</li>
	<li>{$target_pages_field}</li>
	<li>{$image_picker_field}</li>
	{if !is_null($lead_image_id)}
		<li>
			<label class="admin_label"></label>
			<img class="article_selected_image" title="Afbeelding verwijderen" src="/admin/upload.php?image={$lead_image_id}&amp;thumb=true" />
		</li>
	{/if}
</ul>