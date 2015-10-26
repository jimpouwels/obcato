<form id="template_add_form" name="template_add_form" method="post" action"/admin/index.php">
	<input type="hidden" name="action" id="action" value="add_template" />
</form>
<div class="content_left_column">
	{$scope_selector}
</div>
<div class="content_right_column">
	{if isset($template_editor)}
		<div class="template_editor_wrapper">
			{$template_editor}
			{$template_code_viewer}
		</div>
	{elseif isset($template_list)}
		{$template_list}
	{/if}
</div>
