<ul>
    {foreach from=$template_files item=template_file}
        <li>
            <a id="template_file_link"
               href="{$backend_base_url}&template_file={$template_file.id}">{$template_file.name}</a>
        </li>
    {/foreach}
</ul>
