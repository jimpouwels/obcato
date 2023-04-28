<label class="admin_label" for="{$name}">
	{if isset($text_resources[$label_identifier])}
		{$text_resources[$label_identifier]}
	{else}
		{$label_identifier}
	{/if}
	{if $mandatory}
		<span class="mandatory_star">*</span>
	{/if}
</label>