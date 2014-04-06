{if isset($position_editor)}
	{$position_editor}
{/if}

{$position_list}

<form id="add_position_form" class="displaynone" method="post" action="/admin/index.php">
	<fieldset>
		<input id="add_position_action" name="add_position_action" type="hidden" value="add_position_action" />
	</fieldset>
</form>