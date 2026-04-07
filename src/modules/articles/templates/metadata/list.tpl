<ul>
    {foreach from=$metadata_fields item=metadata_field}
        {assign var=li_class value=""}
        {if $metadata_field.is_active}
            {assign var=li_class value="active"}
        {/if}
        <li class="{$li_class}">
            <a href="{$backend_base_url}&metadata_field={$metadata_field.id}" title="{$metadata_field.name}">{$metadata_field.name}</a>
        </li>
    {/foreach}
</ul>