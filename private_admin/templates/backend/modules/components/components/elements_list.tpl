<ul>
    {foreach from=$elements item=element}
        <li class="{if $element.is_current}active{/if}" style="list-style-image: url('{$element.icon_url}')">
            <a href="{$backend_base_url}&element={$element.id}" title="{$element.name}">{$element.name}</a>
        </li>
    {/foreach}
</ul>
