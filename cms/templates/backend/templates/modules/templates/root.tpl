<form id="template_form" name="template_form" method="post" action"/admin/index.php">
	<input type="hidden" name="action" id="action" value="add_template" />
</form>
{if isset($template_editor)}
	{$template_editor}
{elseif isset($scope_selector)}
	{$scope_selector}
	{if isset($template_list)}
		{$template_list}
	{/if}
{/if}