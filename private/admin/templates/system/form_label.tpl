<label class="admin_label" for="{$name}">
	{if isset($text_resources[$label_resource_identifier])}
		{$text_resources[$label_resource_identifier]}
	{else}
		{$label_resource_identifier}
	{/if}
	{if $mandatory}
		<span class="mandatory_star">*</span>
	{/if}
</label>