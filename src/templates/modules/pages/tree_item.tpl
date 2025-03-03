{assign var='li_class' value=''}
{if {$published}}
    {assign var='li_class' value='class="published"'}
{else}
    {assign var='li_class' value='class="depublished"'}
{/if}
<li {$li_class}>
    {assign var='class' value='page_tree_link'}
    {if $active}
        {assign var='class' value=$class|cat:' active'}
    {/if}
    <a title="{$title}" href="{$backend_base_url}&page={$page_id}" class="{$class}">{$name}</a>
    {if isset($sub_pages) && count($sub_pages) > 0}
        <ul>
            {foreach from=$sub_pages item=sub_page}
                {$sub_page}
            {/foreach}
        </ul>
    {/if}
</li>