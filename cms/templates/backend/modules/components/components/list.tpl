<fieldset class="admin_fieldset modules-list-fieldset">
    <div class="fieldset-title">Modules</div>
    <ul>
        {foreach from=$modules item=module}
            <li style="list-style-image: url('{$module.icon_url}')"><a href="/admin/index.php?module={$module.id}" title="{$module.title}">{$module.title}</a></li>
        {/foreach}
    </ul>
</fieldset>