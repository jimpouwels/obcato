<input type="hidden" id="{$add_element_form_id}" name="{$add_element_form_id}" value="" />
<input type="hidden" id="{$edit_element_holder_id}" name="{$edit_element_holder_id}" value="{$current_article_id}" />
<input type="hidden" id="{$delete_element_form_id}" name="{$delete_element_form_id}" value="" />
<input type="hidden" id="draggable_order" name="draggable_order" value="" />
<input type="hidden" id="{$action_form_id}" name="{$action_form_id}" value="" />
<input type="hidden" id="delete_lead_image_field" name="delete_lead_image_field" value="" />

<ul class="admin_form">
	<li>{$title_field}</li>
	<li>{$url_field}</li>
	<li>{$description_field}</li>
	<li>{$published_field}</li>
	<li>{$publication_date_field}</li>
    <li>{$sort_date_field}</li>
	<li>{$target_pages_field}</li>
	<li>{$comment_forms_field}</li>
	<li>{$template_field}</li>
	<li>
		<div>
			{$image_picker_field}
		</div>
        {if !is_null($lead_image_id)}
            <div>
                {$delete_lead_image_button}
            </div>
        {/if}
	</li>
	{if $lead_image_id}
		<li>
			<label class="admin_label"></label>
			<img class="article_selected_image" title="Afbeelding verwijderen" src="/admin/upload.php?image={$lead_image_id}&amp;thumb=true" />
		</li>
	{/if}
</ul>
