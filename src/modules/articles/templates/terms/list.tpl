{if count($all_terms) > 0}
    <ul>
        {foreach from=$all_terms item=term}
            <li class="{if $term.is_active}active{/if}">
                <a href="{$backend_base_url}&term={$term.id}" title="{$term.name}">{$term.name}</a>
            </li>
        {/foreach}
    </ul>
{else}
    {$no_terms_message}
{/if}
