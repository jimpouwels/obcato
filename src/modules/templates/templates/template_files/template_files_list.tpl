<ul>
    {foreach from=$template_files item=template_file}
        {assign var=li_class value=""}
        {if $template_file.is_active}
            {assign var=li_class value="active"}
        {/if}
        <li class="{$li_class}">
            <a id="template_file_link"
               href="{$backend_base_url}&template_file={$template_file.id}">{$template_file.name}</a>
        </li>
    {/foreach}
</ul>
