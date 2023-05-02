<ul>
	{foreach from=$webforms item=webform}
		<li>
			<a id="webform_item_link" href="{$backend_base_url}&scope={$webform.id}">{$webform.title}</a>
		</li>
	{/foreach}
</ul>
