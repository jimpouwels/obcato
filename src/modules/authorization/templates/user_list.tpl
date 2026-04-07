<ul>
    {if count($users) > 0}
        {foreach from=$users item=user}
            <li class="{if $user.is_current}active{/if}">
                <a title="{$user.fullname}" href="{$backend_base_url}&user={$user.id}">{$user.fullname}</a>
            </li>
        {/foreach}
    {/if}
</ul>
