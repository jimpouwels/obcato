<ul>
    {foreach from=$webforms item=webform}
        {assign var=is_selected_class value=""}
        {if $webform.is_selected}
            {assign var=is_selected_class value="selected"}
        {/if}
        <li class="{$is_selected_class}">
            <a id="webform_item_link" href="{$backend_base_url}&webform_id={$webform.id}">{$webform.title}</a>
        </li>
    {/foreach}
</ul>
