<form id="template_editor_form" name="template_editor_form" method="post" action="{$backend_base_url}&template={$current_template_id}" enctype="multipart/form-data">
	<input type="hidden" name="action" id="action" value="update_template" />
	<input type="hidden" name="template_id" id="template_id" value="{$current_template_id}" />
	<div class="content_left_column">
		{$scope_selector}
	</div>
	<div class="content_right_column">
		{if isset($template_editor)}
			{$template_editor}
			{$template_var_editor}
		{elseif isset($template_list)}
			{$template_list}
		{/if}
	</div>
</form>