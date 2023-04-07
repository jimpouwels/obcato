<ul>
	{foreach from=$scopes item=scope}
		<li>
			<a id="scope_item_link" href="{$backend_base_url}&scope={$scope}">{$scope}</a>
		</li>
	{/foreach}
</ul>
