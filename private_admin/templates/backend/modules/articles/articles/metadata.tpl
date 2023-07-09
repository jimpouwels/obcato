<input type="hidden" id="{$add_element_form_id}" name="{$add_element_form_id}" value="" />
<input type="hidden" id="{$edit_element_holder_id}" name="{$edit_element_holder_id}" value="{$current_article_id}" />
<input type="hidden" id="{$delete_element_form_id}" name="{$delete_element_form_id}" value="" />
<input type="hidden" id="draggable_order" name="draggable_order" value="" />
<input type="hidden" id="{$action_form_id}" name="{$action_form_id}" value="" />
<input type="hidden" id="delete_lead_image_field" name="delete_lead_image_field" value="" />
<input type="hidden" id="delete_parent_article_field" name="delete_parent_article_field" value="" />

<div class="admin_form_v2">
	{$title_field}</li>
	{$url_field}</li>
	{$description_field}</li>
	{$published_field}</li>
	{$publication_date_field}</li>
    {$sort_date_field}</li>
	{$target_pages_field}</li>
	{$parent_article_field}
	<div class="admin_form_field_v2">
		{if isset($parent_article)}
			<div class="admin_label_wrapper">
				<label class="admin_label">{$text_resources.article_editor_select_parent_article_label}</label>
			</div>
			<div class="admin_field_wrapper">
				<div class="selected_parent_article">
					<p><i><a title="{$parent_article.title}" href="{$parent_article.url}">{$parent_article.title}</a></i></p>
				</div>
				<div class="selected_parent_article_delete_button">
					{$delete_parent_article_button}
				</div>
			</div>
		{/if}
	</div>
	{if count($child_articles) > 0}
		<div class="admin_form_field_v2">
			<div class="admin_label_wrapper">
				<label class="admin_label">{$text_resources.article_editor_child_articles_label}</label>
			</div>
			<div class="admin_field_wrapper">
				{foreach from=$child_articles item=child_article}
					<li>
						<a title="{$child_article.title}" href="{$child_article.url}">{$child_article.title}</a>
					</li>
				{/foreach}
			</div>
		</div>
	{/if}
	{$comment_forms_field}
	{$template_field}
	{$image_picker_field}
	{if !is_null($lead_image_id)}
		<div class="admin_form_field_v2">
			<div class="admin_label_wrapper">
				<label class="admin_label">{$text_resources.article_editor_selected_image_label}</label>
			</div>
			<div class="admin_field_wrapper">
				<img class="article_selected_image" title="Afbeelding verwijderen" src="/admin/upload.php?image={$lead_image_id}&amp;thumb=true" />
				{$delete_lead_image_button}
			</div>
		</div>
	{/if}
</div>
