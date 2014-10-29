<form id="template_add_form" name="template_add_form" method="post" action"/admin/index.php">
	<input type="hidden" name="action" id="action" value="add_template" />
</form>
{$scope_selector}
{if isset($template_editor)}
	{$template_editor}
{elseif isset($template_list)}
	{$template_list}
{/if}