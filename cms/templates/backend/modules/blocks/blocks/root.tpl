<form id="add_form_hidden" class="displaynone" method="post" action="/admin/index.php">
	<fieldset>
		<input id="add_block_action" name="add_block_action" type="hidden" value="" />
	</fieldset>
</form>

{$blocks_list}
{if isset($editor)}
	{$editor}
{/if}