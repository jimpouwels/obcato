{assign var="template_id" value=""}
{if $current_template_id}
	{assign var="template_id" value="?template=$current_template_id"}
{/if}
<form id="template_form" method="post" action="/admin/index.php{$template_id}" enctype="multipart/form-data">
	<fieldset class="displaynone">
		<input type="hidden" name="action" id="action" value="" />
	</fieldset>
	{if isset($template_editor)}
		{$template_editor}
	{elseif isset($template_list)}
		{$template_list}
	{/if}
</form>