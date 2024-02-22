<ul>
    {foreach from=$scopes item=scope}
        <li>
            <a id="scope_item_link" href="{$backend_base_url}&scope={$scope.identifier}">{$scope.label}</a>
        </li>
    {/foreach}
</ul>
