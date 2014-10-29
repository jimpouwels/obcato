<form id="add_form_hidden" class="displaynone" method="post" action="/admin/index.php">
	<fieldset>
		<input id="add_article_action" name="add_article_action" type="hidden" value="" />
	</fieldset>
</form>

{$search}
{if isset($list)}
	{$list}
{/if}
{if isset($editor)}
	{$editor}
{/if}