<form id="template_form" name="template_form" method="post" action="{$backend_base_url}&template={$current_template_id}" enctype="multipart/form-data">
	<div class="content_left_column">
		{$scope_selector}
	</div>
	<div class="content_right_column">
		{if isset($template_editor)}
			<div class="template_editor_wrapper">
				{$template_editor}
				{$template_var_editor}
				{$template_code_viewer}
			</div>
		{elseif isset($template_list)}
			{$template_list}
		{/if}
	</div>
</form>