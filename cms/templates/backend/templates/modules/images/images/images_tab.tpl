{$search}
{if isset($list)}
	{$list}
{else}
	{$editor}
{/if}

<form id="add_form_hidden" class="displaynone" method="post" action="/admin/index.php">
	<input id="add_image_action" name="add_image_action" type="hidden" value="" />
</form>