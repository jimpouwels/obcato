<form id="add_form_hidden" class="displaynone" method="post" action="/admin/index.php">
	<fieldset>
		<input id="add_block_action" name="add_block_action" type="hidden" value="" />
	</fieldset>
</form>

<div class="content_left_column">
	{$blocks_list}
</div>
<div class="content_right_column">
	{if isset($editor)}
		{$editor}
	{/if}
</div>
