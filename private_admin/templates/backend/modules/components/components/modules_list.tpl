<ul>
    {foreach from=$modules item=module}
        <li class="{if $module.is_current}active{/if}" style="list-style-image: url('{$module.icon_url}')">
            <a href="/admin/index.php?module={$module.id}" title="{$module.title}">{$module.title}</a>
        </li>
    {/foreach}
</ul>
