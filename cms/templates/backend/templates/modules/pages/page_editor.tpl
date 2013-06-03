<form action="/admin/index.php?page={$id}" method="post" id="{$element_holder_form_id}">
	<fieldset class="admin_fieldset page_meta">
		<div class="fieldset-title">Algemeen</div>
		
		<input type="hidden" id="{$add_element_form_id}" name="{$add_element_form_id}" value="" />
		<input type="hidden" id="{$edit_element_holder_id}" name="{$edit_element_holder_id}" value="{$id}" />
		<input type="hidden" id="{$action_form_id}" name="{$action_form_id}" value="" />
		<input type="hidden" id="{$delete_element_form_id}" name="{$delete_element_form_id}" value="" />
		<input type="hidden" id="{$element_order_id}" name="{$element_order_id}" value="" />
		
		{$page_metadata}
		
	</fieldset>
	
	{$element_container}
	
	{$link_editor}
	
	<fieldset class="admin_fieldset page_blocks">
		<div class="fieldset-title">Blokken</div>
			{$block_selector}
	</fieldset>
</form>