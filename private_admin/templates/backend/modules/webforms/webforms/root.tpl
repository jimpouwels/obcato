<div class="content_left_column">
	{$list}
</div>
{if isset($metadata_editor)}
	<div class="content_right_column">
		<form action="{$backend_base_url}&webform_id={$id}" method="post" id="webform-editor-form" enctype="multipart/form-data">
			<input type="hidden" id="webform_item_to_delete" name="webform_item_to_delete" value="" />
			<input type="hidden" id="{$action_form_id}" name="{$action_form_id}" value="" />
			<input type="hidden" id="webform_id" name="webform_id" value="{$id}" />
			{$metadata_editor}
			{$webform_editor}
			{$handlers_editor}
		</form>
	</div>
{/if}

<form id="add_form_hidden" class="displaynone" method="post" action="{$backend_base_url}">
	<input id="add_webform_action" name="add_webform_action" type="hidden" value="" />
</form>
